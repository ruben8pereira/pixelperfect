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
use Illuminate\Support\Facades\Redirect;


class ReportController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Use policy-based authorization
        $this->authorize('viewAny', Report::class);
        
        $user = Auth::user();

        // Different query based on role
        file_put_contents('policy.txt', $user->role->name);
        if ($user->role->name === 'Organization') {
            $reports = Report::where('organization_id', $user->organization_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role->name === 'User') {
            $reports = Report::where('organization_id', $user->organization_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $reports = collect([]); // Empty collection for other roles
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
        return view('reports.create', compact('defectTypes'));
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
            } elseif (!$request->has('keep_map_image')) {
                // Remove map image if the user unchecked "keep this image"
                $oldMapImage = $report->reportImages->where('caption', 'Map')->first();
                if ($oldMapImage) {
                    Storage::disk('public')->delete($oldMapImage->file_path);
                    $oldMapImage->delete();
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

                    $defect->save();

                    // Process defect image if provided
                    if ($request->hasFile("defect_images.{$index}")) {
                        // Remove old image if it exists for this report and matches the defect description
                        $oldDefectImage = $report->reportImages
                            ->where('caption', 'like', '%' . substr($defect->description, 0, 30) . '%')
                            ->first();

                        if ($oldDefectImage) {
                            Storage::disk('public')->delete($oldDefectImage->file_path);
                            $oldDefectImage->delete();
                        }

                        // Store new defect image
                        $imagePath = $request->file("defect_images.{$index}")->store('report-images', 'public');

                        $defectImage = new ReportImage();
                        $defectImage->report_id = $report->id;
                        $defectImage->file_path = $imagePath;
                        $defectImage->caption = substr($defect->description, 0, 30);
                        $defectImage->save();
                    } elseif (!$request->has("keep_defect_images.{$index}")) {
                        // Remove defect image if the user unchecked "keep this image"
                        $oldDefectImage = $report->reportImages
                            ->where('caption', 'like', '%' . substr($defect->description, 0, 30) . '%')
                            ->first();

                        if ($oldDefectImage) {
                            Storage::disk('public')->delete($oldDefectImage->file_path);
                            $oldDefectImage->delete();
                        }
                    }
                }
            }

            // Delete defects that were removed
            $report->reportDefects()->whereNotIn('id', $existingDefectIds)->get()->each(function ($defect) use ($report) {
                // Delete associated images first
                $report->reportImages
                    ->where('caption', 'like', '%' . substr($defect->description, 0, 30) . '%')
                    ->each(function ($image) {
                        Storage::disk('public')->delete($image->file_path);
                        $image->delete();
                    });

                // Delete the defect
                $defect->delete();
            });

            DB::commit();

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

            // Defects validation
            'defects' => 'required|array|min:1',
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.description' => 'required|string|max:1000',
            'defects.*.severity' => 'required|string|in:low,medium,high,critical',
            'defects.*.coordinates' => 'nullable|array',

            // Defect images
            'defect_images.*' => 'nullable|image|max:10240', // Max 10MB
        ];

        // If user is admin, validate organization_id
        if (Auth::user()->role && Auth::user()->role->name === 'Administrator') {
            $rules['organization_id'] = 'required|exists:organizations,id';
        }

        $request->validate($rules);
    }

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

            // If user is admin and can select organization
            if (Auth::user()->role && Auth::user()->role->name === 'Administrator' && $request->has('organization_id')) {
                $report->organization_id = $request->organization_id;
            }

            $report->save();

            // Process network map if provided (similar logic to store method)
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

            // Process defects (similar logic to store method)
            $existingDefectIds = [];

            if ($request->has('defects')) {
                foreach ($request->defects as $index => $defectData) {
                    // Same logic as in store method for processing defects
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

            $pdf = $pdfService->generateReportPdf(
                $report,
                $includeComments,
                $language
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
            $pdf = $pdfService->generateReportPdf(
                $report,
                request('include_comments', true),
                request('language')
            );

            return $pdf->download("report-{$report->id}.pdf");

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
            'description' => 'nullable|string',
            'language' => 'required|string|size:2|in:en,fr,de',
            'map_image' => 'nullable|image|max:10240', // Max 10MB

            // Defects validation
            'defects' => 'required|array|min:1',
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.description' => 'required|string|max:1000',
            'defects.*.severity' => 'required|string|in:low,medium,high,critical',
            'defects.*.coordinates' => 'nullable|array',

            // Defect images
            'defect_images.*' => 'nullable|image|max:10240', // Max 10MB
        ];

        // If user is admin, validate organization_id
        if (Auth::user()->role && Auth::user()->role->name === 'Administrator') {
            $rules['organization_id'] = 'required|exists:organizations,id';
        }

        $request->validate($rules);
    }
}
