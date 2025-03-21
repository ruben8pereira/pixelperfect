<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportDefect;
use App\Models\ReportImage;
use App\Models\DefectType;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

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

        // Apply search filters
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply date range filter
        if ($request->has('date_range')) {
            $range = $request->input('date_range');
            if ($range === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($range === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($range === 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            } elseif ($range === 'year') {
                $query->whereYear('created_at', now()->year);
            }
        }

        // Apply severity filter
        if ($request->has('severity') && !empty($request->input('severity'))) {
            $severity = $request->input('severity');
            $query->whereHas('reportDefects', function($q) use ($severity) {
                $q->where('severity', $severity);
            });
        }

        // Apply organization filter for admins
        if ($user->role && $user->role->name === 'Administrator' && $request->has('organization')) {
            $query->where('organization_id', $request->input('organization'));
        }

        // Different query based on role
        if ($user->role->name === 'Administrator') {
            // Admin sees all reports
            $reports = $query->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->role->name === 'Organization') {
            // Organization users see reports for their organization
            $reports = $query->where('organization_id', $user->organization_id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        } elseif ($user->role->name === 'User') {
            // Regular users see reports they created or from their organization
            $reports = $query->where(function($q) use ($user) {
                $q->where('organization_id', $user->organization_id)
                  ->orWhere('created_by', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        } else {
            // Guest users don't see any reports by default
            $reports = collect([]);
        }

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

        // Get next report number for display purposes
        $nextReportNumber = Report::count() + 1;

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
            $report->description = $request->description;
            $report->language = $request->language ?? 'en';
            $report->pdf_export_count = 0;
            $report->created_by = Auth::id();

            // Add custom fields if present
            if ($request->has('weather')) {
                $report->weather = $request->weather;
            }

            if ($request->has('location')) {
                $report->location = $request->location;
            }

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

                ReportImage::create([
                    'report_id' => $report->id,
                    'file_path' => $mapPath,
                    'caption' => 'Map',
                ]);
            }

            // Process other report images if provided
            if ($request->hasFile('report_images')) {
                foreach ($request->file('report_images') as $index => $image) {
                    $imagePath = $image->store('report-images', 'public');

                    // Get caption if provided
                    $caption = null;
                    if ($request->has('report_image_captions') && isset($request->report_image_captions[$index])) {
                        $caption = $request->report_image_captions[$index];
                    }

                    ReportImage::create([
                        'report_id' => $report->id,
                        'file_path' => $imagePath,
                        'caption' => $caption,
                    ]);
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

                    // Handle coordinates/metadata
                    $coordinates = [];
                    if (isset($defectData['coordinates'])) {
                        foreach ($defectData['coordinates'] as $key => $value) {
                            if (!empty($value)) {
                                $coordinates[$key] = $value;
                            }
                        }
                    }
                    $defect->coordinates = $coordinates;
                    $defect->save();

                    // Process defect image if provided
                    if ($request->hasFile("defect_images.{$index}")) {

                        $imagePath = $request->file("defect_images.{$index}")->store('report-images', 'public');

                        ReportImage::create([
                            'report_id' => $report->id,
                            'defect_id' => $defect->id,
                            'file_path' => $imagePath,
                            'caption' => substr($defect->description, 0, 30),
                        ]);
                    }

                }
            }

            // Process pipe sections if provided
            if ($request->has('sections')) {
                // Your implementation for pipe sections here
            }

            DB::commit();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating report: ' . $e->getMessage());

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

        // Load relationships for better performance
        $report->load([
            'reportDefects.defectType',
            'reportImages',
            'reportComments.user',
            'organization',
            'creator'
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

        // Load relationships
        $report->load(['reportDefects.defectType', 'reportImages', 'organization']);

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
            $report->description = $request->description;
            $report->language = $request->language ?? 'en';

            // Update custom fields if present
            if ($request->has('weather')) {
                $report->weather = $request->weather;
            }

            if ($request->has('location')) {
                $report->location = $request->location;
            }

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

                ReportImage::create([
                    'report_id' => $report->id,
                    'file_path' => $mapPath,
                    'caption' => 'Map',
                ]);
            } elseif ($request->has('keep_map_image') && $request->keep_map_image == 0) {
                // Remove map image if the user unchecked "keep this image"
                $oldMapImage = $report->reportImages->where('caption', 'Map')->first();
                if ($oldMapImage) {
                    Storage::disk('public')->delete($oldMapImage->file_path);
                    $oldMapImage->delete();
                }
            }

            // Process other report images if provided
            if ($request->hasFile('report_images')) {
                foreach ($request->file('report_images') as $index => $image) {
                    $imagePath = $image->store('report-images', 'public');

                    // Get caption if provided
                    $caption = null;
                    if ($request->has('report_image_captions') && isset($request->report_image_captions[$index])) {
                        $caption = $request->report_image_captions[$index];
                    }

                    ReportImage::create([
                        'report_id' => $report->id,
                        'file_path' => $imagePath,
                        'caption' => $caption,
                    ]);
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

                    // Handle coordinates/metadata
                    $coordinates = [];
                    if (isset($defectData['coordinates'])) {
                        foreach ($defectData['coordinates'] as $key => $value) {
                            if (!empty($value)) {
                                $coordinates[$key] = $value;
                            }
                        }
                    }
                    $defect->coordinates = $coordinates;
                    $defect->save();

                    // Process defect image if provided
                    if ($request->hasFile("defect_images.{$index}")) {
                        // Remove old image if it exists
                        $oldDefectImage = $report->reportImages->where('defect_id', $defect->id)->first();
                        if ($oldDefectImage) {
                            Storage::disk('public')->delete($oldDefectImage->file_path);
                            $oldDefectImage->delete();
                        }

                        // Store new defect image
                        $imagePath = $request->file("defect_images.{$index}")->store('report-images', 'public');

                        ReportImage::create([
                            'report_id' => $report->id,
                            'defect_id' => $defect->id,
                            'file_path' => $imagePath,
                            'caption' => substr($defect->description, 0, 30),
                        ]);
                    } elseif ($request->has("keep_defect_images.{$index}") && $request->{"keep_defect_images.".$index} == 0) {
                        // Remove defect image if the user unchecked "keep this image"
                        $oldDefectImage = $report->reportImages->where('defect_id', $defect->id)->first();
                        if ($oldDefectImage) {
                            Storage::disk('public')->delete($oldDefectImage->file_path);
                            $oldDefectImage->delete();
                        }
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

            // Process pipe sections if provided
            if ($request->has('sections')) {
                // Your implementation for pipe sections here
            }

            DB::commit();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating report: ' . $e->getMessage());

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
            Log::error('Error deleting report: ' . $e->getMessage());

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
    public function previewPdf(Request $request, Report $report, PdfExportService $pdfService)
    {
        // Check permission to view report
        $this->authorize('view', $report);

        try {
            $includeComments = $request->has('include_comments') ? (bool)$request->include_comments : true;
            $language = $request->input('language', $report->language);

            Log::info("Generating PDF preview with language: {$language}, includeComments: " . ($includeComments ? 'true' : 'false'));

            $pdf = $pdfService->generateReportPdf(
                $report,
                $includeComments,
                $language
            );

            return $pdf->stream("report-{$report->id}-preview.pdf");

        } catch (\Exception $e) {
            Log::error('Error generating PDF preview: ' . $e->getMessage());

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
    public function exportPdf(Request $request, Report $report, PdfExportService $pdfService)
    {
        // Check permission to export PDF
        $this->authorize('exportPdf', $report);

        try {
            $includeComments = $request->has('include_comments') ? (bool)$request->input('include_comments') : true;
            $language = $request->input('language', $report->language);

            Log::info("Exporting PDF with language: {$language}, includeComments: " . ($includeComments ? 'true' : 'false'));

            // Ensure the language is loaded
            App::setLocale($language);

            $pdf = $pdfService->generateReportPdf(
                $report,
                $includeComments,
                $language
            );

            // Generate a filename with the language code
            $filename = "report-{$report->id}-{$language}.pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());

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
            'description' => 'nullable|string',
            'language' => 'required|string|size:2|in:en,fr,de',
            'map_image' => 'nullable|image|max:10240', // Max 10MB
            'weather' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',

            // Defects validation
            'defects' => 'required|array|min:1',
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.description' => 'required|string|max:1000',
            'defects.*.severity' => 'required|string|in:low,medium,high,critical',
            'defects.*.coordinates' => 'nullable|array',

            // Defect images
            'defect_images.*' => 'nullable|image|max:10240', // Max 10MB

            // Report images
            'report_images.*' => 'nullable|image|max:10240', // Max 10MB
            'report_image_captions.*' => 'nullable|string|max:255',
        ];

        // If user is admin, validate organization_id
        if (Auth::user()->role && Auth::user()->role->name === 'Administrator') {
            $rules['organization_id'] = 'required|exists:organizations,id';
        }

        $request->validate($rules);
    }
}
