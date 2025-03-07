<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportDefect;
use App\Models\ReportImage;
use App\Models\ReportSection;
use App\Models\DefectType;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class ReportController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Use policy-based authorization
        $this->authorize('viewAny', Report::class);

        $user = Auth::user();
        $query = Report::query();

        // Different query based on role
        if ($user->role->name === 'Administrator') {
            // Admin can see all reports
            if ($request->has('organization_id')) {
                $query->where('organization_id', $request->input('organization_id'));
            }
        } else if ($user->role->name === 'Organization') {
            // Organization can see their own reports
            $query->where('organization_id', $user->organization_id);
        } else if ($user->role->name === 'User') {
            // User can see reports from their organization
            $query->where('organization_id', $user->organization_id);

            // Optionally filter to only see their own reports
            if ($request->has('my_reports') && $request->input('my_reports')) {
                $query->where('created_by', $user->id);
            }
        } else {
            // For other roles (like Guest), return empty collection
            return view('reports.index', ['reports' => collect([])]);
        }

        // Apply search filters if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply severity filter if provided
        if ($request->has('severity') && $request->input('severity')) {
            $severity = $request->input('severity');
            $query->whereHas('reportDefects', function($q) use ($severity) {
                $q->where('severity', $severity);
            });
        }

        // Apply date range filter
        if ($request->has('date_range')) {
            $dateRange = $request->input('date_range');
            if ($dateRange == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } else if ($dateRange == 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } else if ($dateRange == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            } else if ($dateRange == 'year') {
                $query->whereYear('created_at', Carbon::now()->year);
            }
        }

        // Get results with pagination
        $reports = $query->with(['organization', 'creator', 'reportDefects'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    // Check permission to create reports using policy
    $this->authorize('create', Report::class);

    $defectTypes = DefectType::all();

    // Safely get the max report number or default to 1
    try {
        $nextReportNumber = Report::max('report_number');
        $nextReportNumber = is_numeric($nextReportNumber) ? $nextReportNumber + 1 : 1;
    } catch (\Exception $e) {
        // If column doesn't exist yet or other errors
        $nextReportNumber = 1;
    }

    return view('reports.create', compact('defectTypes', 'nextReportNumber'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check permission to create reports
        $this->authorize('create', Report::class);

        // Validate the request
        $this->validateReport($request);

        try {
            DB::beginTransaction();

            // Create report
            $report = new Report();
            $report->title = $request->title;
            $report->report_number = $request->report_number;
            $report->description = $request->description;
            $report->language = $request->language ?? 'en';
            $report->pdf_export_count = 0;
            $report->created_by = Auth::id();
            $report->inspection_date = $request->inspection_date ?? now();
            $report->operator = $request->operator;
            $report->client = $request->client;
            $report->location = $request->location;
            $report->intervention_reason = $request->intervention_reason;
            $report->weather = $request->weather;

            // If user is admin and can select organization
            if (Auth::user()->role && Auth::user()->role->name === 'Administrator' && $request->has('organization_id')) {
                $report->organization_id = $request->organization_id;
            } else {
                // Otherwise, use the user's organization
                $report->organization_id = Auth::user()->organization_id;
            }

            $report->save();

            // Process network map if provided
            if ($request->hasFile('map_image')) {
                $mapPath = $request->file('map_image')->store('report-images', 'public');

                $mapImage = new ReportImage();
                $mapImage->report_id = $report->id;
                $mapImage->file_path = $mapPath;
                $mapImage->caption = 'Map';
                $mapImage->save();
            }

            // Process additional report images
            if ($request->hasFile('report_images')) {
                foreach ($request->file('report_images') as $index => $image) {
                    $imagePath = $image->store('report-images', 'public');

                    $reportImage = new ReportImage();
                    $reportImage->report_id = $report->id;
                    $reportImage->file_path = $imagePath;
                    $reportImage->caption = $request->input('report_image_captions.' . $index, '');
                    $reportImage->save();
                }
            }

            // Process pipe sections if provided
            if ($request->has('sections')) {
                foreach ($request->sections as $sectionData) {
                    $section = new ReportSection();
                    $section->report_id = $report->id;
                    $section->name = $sectionData['name'];
                    $section->diameter = $sectionData['diameter'];
                    $section->material = $sectionData['material'];
                    $section->length = $sectionData['length'];
                    $section->start_manhole = $sectionData['start_manhole'] ?? null;
                    $section->end_manhole = $sectionData['end_manhole'] ?? null;
                    $section->location = $sectionData['location'] ?? null;
                    $section->comments = $sectionData['comments'] ?? null;
                    $section->save();
                }
            }

            // Process defects
            if ($request->has('defects')) {
                foreach ($request->defects as $index => $defectData) {
                    $defect = new ReportDefect();
                    $defect->report_id = $report->id;
                    $defect->defect_type_id = $defectData['defect_type_id'];
                    $defect->description = $defectData['description'];
                    $defect->severity = $defectData['severity'];

                    // Handle coordinates
                    $coordinates = [];
                    if (isset($defectData['coordinates'])) {
                        foreach ($defectData['coordinates'] as $key => $value) {
                            if (!empty($value)) {
                                $coordinates[$key] = $value;
                            }
                        }
                    }
                    $defect->coordinates = $coordinates;

                    // Set mark_on_map if checked
                    if (isset($defectData['mark_on_map'])) {
                        $defect->mark_on_map = true;
                    }

                    $defect->save();

                    // Process defect image if provided
                    if ($request->hasFile("defect_images.{$index}")) {
                        $imagePath = $request->file("defect_images.{$index}")->store('report-images', 'public');

                        $defectImage = new ReportImage();
                        $defectImage->report_id = $report->id;
                        $defectImage->defect_id = $defect->id;
                        $defectImage->file_path = $imagePath;
                        $defectImage->caption = substr($defect->description, 0, 30);
                        $defectImage->save();
                    }
                }
            }

            DB::commit();

            // Generate PDF if requested
            if ($request->has('generate_languages')) {
                foreach ($request->generate_languages as $language) {
                    $pdfService = new PdfExportService();
                    $includeCover = $request->has('include_cover_page');
                    $includeSummary = $request->has('include_summary');
                    $includeMap = $request->has('include_map');
                    $includeImages = $request->has('include_images');
                    $includeComments = $request->has('include_comments');

                    $pdfService->generateReportPdf(
                        $report,
                        $includeComments,
                        $language,
                        [
                            'include_cover' => $includeCover,
                            'include_summary' => $includeSummary,
                            'include_map' => $includeMap,
                            'include_images' => $includeImages
                        ]
                    );
                }
            }

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        // Check permission to view report
        $this->authorize('view', $report);

        $report->load([
            'reportDefects.defectType',
            'reportImages',
            'reportComments.user',
            'organization',
            'creator',
            'reportSections'
        ]);

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        // Check permission to edit report
        $this->authorize('update', $report);

        $report->load([
            'reportDefects.defectType',
            'reportImages',
            'organization',
            'reportSections'
        ]);

        $defectTypes = DefectType::all();

        return view('reports.edit', compact('report', 'defectTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        // Check permission to update report
        $this->authorize('update', $report);

        // Validate the request
        $this->validateReport($request, $report);

        try {
            DB::beginTransaction();

            // Update report data
            $report->title = $request->title;
            $report->report_number = $request->report_number;
            $report->description = $request->description;
            $report->language = $request->language ?? 'en';
            $report->inspection_date = $request->inspection_date;
            $report->operator = $request->operator;
            $report->client = $request->client;
            $report->location = $request->location;
            $report->intervention_reason = $request->intervention_reason;
            $report->weather = $request->weather;

            // If user is admin and can select organization
            if (Auth::user()->role && Auth::user()->role->name === 'Administrator' && $request->has('organization_id')) {
                $report->organization_id = $request->organization_id;
            }

            $report->save();

            // Process network map if provided
            if ($request->hasFile('map_image')) {
                // Remove old map image if exists
                $oldMapImage = $report->reportImages->where('caption', 'Map')->first();
                if ($oldMapImage) {
                    Storage::disk('public')->delete($oldMapImage->file_path);
                    $oldMapImage->delete();
                }

                // Store new map image
                $mapPath = $request->file('map_image')->store('report-images', 'public');

                $mapImage = new ReportImage();
                $mapImage->report_id = $report->id;
                $mapImage->file_path = $mapPath;
                $mapImage->caption = 'Map';
                $mapImage->save();
            }

            // Process additional report images
            if ($request->hasFile('report_images')) {
                foreach ($request->file('report_images') as $index => $image) {
                    $imagePath = $image->store('report-images', 'public');

                    $reportImage = new ReportImage();
                    $reportImage->report_id = $report->id;
                    $reportImage->file_path = $imagePath;
                    $reportImage->caption = $request->input('report_image_captions.' . $index, '');
                    $reportImage->save();
                }
            }

            // Process pipe sections
            // First delete existing sections
            if ($request->has('sections')) {
                $report->reportSections()->delete();

                // Then add new ones
                foreach ($request->sections as $sectionData) {
                    $section = new ReportSection();
                    $section->report_id = $report->id;
                    $section->name = $sectionData['name'];
                    $section->diameter = $sectionData['diameter'];
                    $section->material = $sectionData['material'];
                    $section->length = $sectionData['length'];
                    $section->start_manhole = $sectionData['start_manhole'] ?? null;
                    $section->end_manhole = $sectionData['end_manhole'] ?? null;
                    $section->location = $sectionData['location'] ?? null;
                    $section->comments = $sectionData['comments'] ?? null;
                    $section->save();
                }
            }

            // Process defects
            $existingDefectIds = [];

            if ($request->has('defects')) {
                foreach ($request->defects as $index => $defectData) {
                    // Check if this is an existing defect or a new one
                    if (isset($defectData['id'])) {
                        $defect = ReportDefect::findOrFail($defectData['id']);

                        // Verify this defect belongs to this report
                        if ($defect->report_id != $report->id) {
                            continue; // Skip if defect doesn't belong to this report
                        }

                        $existingDefectIds[] = $defect->id;
                    } else {
                        $defect = new ReportDefect();
                        $defect->report_id = $report->id;
                    }

                    // Update defect data
                    $defect->defect_type_id = $defectData['defect_type_id'];
                    $defect->description = $defectData['description'];
                    $defect->severity = $defectData['severity'];

                    // Handle coordinates
                    $coordinates = [];
                    if (isset($defectData['coordinates'])) {
                        foreach ($defectData['coordinates'] as $key => $value) {
                            if (!empty($value)) {
                                $coordinates[$key] = $value;
                            }
                        }
                    }
                    $defect->coordinates = $coordinates;

                    // Set mark_on_map if checked
                    $defect->mark_on_map = isset($defectData['mark_on_map']);

                    $defect->save();

                    // Process defect image if provided
                    if ($request->hasFile("defect_images.{$index}")) {
                        // Remove old defect image if exists
                        $oldDefectImage = $report->reportImages->where('defect_id', $defect->id)->first();
                        if ($oldDefectImage) {
                            Storage::disk('public')->delete($oldDefectImage->file_path);
                            $oldDefectImage->delete();
                        }

                        // Store new defect image
                        $imagePath = $request->file("defect_images.{$index}")->store('report-images', 'public');

                        $defectImage = new ReportImage();
                        $defectImage->report_id = $report->id;
                        $defectImage->file_path = $imagePath;
                        $defectImage->defect_id = $defect->id;
                        $defectImage->caption = substr($defect->description, 0, 30);
                        $defectImage->save();
                    }
                }
            }

            // Delete defects that were removed
            $report->reportDefects()->whereNotIn('id', $existingDefectIds)->get()->each(function ($defect) {
                // Delete associated images first
                $defect->images()->get()->each(function ($image) {
                    Storage::disk('public')->delete($image->file_path);
                    $image->delete();
                });

                // Delete the defect
                $defect->delete();
            });

            DB::commit();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        // Check permission to delete report
        $this->authorize('delete', $report);

        try {
            DB::beginTransaction();

            // Delete related images from storage
            foreach ($report->reportImages as $image) {
                Storage::disk('public')->delete($image->file_path);
            }

            // The related defects, images, and comments will be deleted by cascade
            $report->delete();

            DB::commit();

            return redirect()->route('reports.index')
                ->with('success', 'Report deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the report: ' . $e->getMessage());
        }
    }

    /**
     * Preview the report as PDF.
     *
     * @param  \App\Models\Report  $report
     * @param  \App\Services\PdfExportService  $pdfService
     * @return \Illuminate\Http\Response
     */
    public function previewPdf(Report $report, PdfExportService $pdfService)
    {
        // Check permission to view report
        $this->authorize('view', $report);

        try {
            $includeComments = request('include_comments', true);
            $language = request('language', $report->language);
            $includeCover = request('include_cover', true);
            $includeSummary = request('include_summary', true);
            $includeMap = request('include_map', true);
            $includeImages = request('include_images', true);

            $pdf = $pdfService->generateReportPdf(
                $report,
                $includeComments,
                $language,
                [
                    'include_cover' => $includeCover,
                    'include_summary' => $includeSummary,
                    'include_map' => $includeMap,
                    'include_images' => $includeImages
                ]
            );

            return $pdf->stream('preview.pdf');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while generating the PDF preview: ' . $e->getMessage());
        }
    }

    /**
     * Export the report as PDF.
     *
     * @param  \App\Models\Report  $report
     * @param  \App\Services\PdfExportService  $pdfService
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Report $report, PdfExportService $pdfService)
    {
        // Check permission to export PDF
        $this->authorize('exportPdf', $report);

        try {
            $includeComments = request('include_comments', true);
            $language = request('language', $report->language);
            $includeCover = request('include_cover', true);
            $includeSummary = request('include_summary', true);
            $includeMap = request('include_map', true);
            $includeImages = request('include_images', true);

            $pdf = $pdfService->generateReportPdf(
                $report,
                $includeComments,
                $language,
                [
                    'include_cover' => $includeCover,
                    'include_summary' => $includeSummary,
                    'include_map' => $includeMap,
                    'include_images' => $includeImages
                ]
            );

            // Increment the export count
            $report->increment('pdf_export_count');

            return $pdf->download("rapport-tv-{$report->report_number}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while generating the PDF: ' . $e->getMessage());
        }
    }

    /**
     * Validate the report request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report|null  $report
     * @return void
     */
    protected function validateReport(Request $request, Report $report = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'report_number' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'inspection_date' => 'nullable|date',
            'operator' => 'nullable|string|max:255',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'intervention_reason' => 'nullable|string|max:100',
            'weather' => 'nullable|string|max:50',
            'language' => 'required|string|size:2|in:en,fr,de',
            'map_image' => 'nullable|image|max:10240', // Max 10MB
            'include_cover_page' => 'nullable|boolean',
            'include_summary' => 'nullable|boolean',
            'include_map' => 'nullable|boolean',
            'include_images' => 'nullable|boolean',
            'include_comments' => 'nullable|boolean',
            'generate_languages' => 'nullable|array',
            'generate_languages.*' => 'in:en,fr,de',

            // Sections validation
            'sections' => 'nullable|array',
            'sections.*.name' => 'required|string|max:100',
            'sections.*.diameter' => 'nullable|numeric',
            'sections.*.material' => 'nullable|string|max:50',
            'sections.*.length' => 'nullable|numeric',
            'sections.*.start_manhole' => 'nullable|string|max:100',
            'sections.*.end_manhole' => 'nullable|string|max:100',
            'sections.*.location' => 'nullable|string|max:255',
            'sections.*.comments' => 'nullable|string',

            // Defects validation
            'defects' => 'required|array|min:1',
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.description' => 'required|string|max:1000',
            'defects.*.severity' => 'required|string|in:low,medium,high,critical',
            'defects.*.coordinates' => 'nullable|array',
            'defects.*.mark_on_map' => 'nullable|boolean',

            // Defect images
            'defect_images.*' => 'nullable|image|max:10240', // Max 10MB

            // Additional report images
            'report_images' => 'nullable|array',
            'report_images.*' => 'image|max:10240', // Max 10MB
            'report_image_captions.*' => 'nullable|string|max:255',
        ];

        // If user is admin, validate organization_id
        if (Auth::user()->role && Auth::user()->role->name === 'Administrator') {
            $rules['organization_id'] = 'required|exists:organizations,id';
        }

        $request->validate($rules);
    }
}
