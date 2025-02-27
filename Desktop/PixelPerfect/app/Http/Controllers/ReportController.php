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
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


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
        // Ensure user can view reports
        if (!Gate::allows('view-reports')) {
            abort(403, 'You are not authorized to view reports');
        }

        $user = Auth::user();

        // Different query based on role
        if ($user->role->name === 'Organization') {
            $reports = Report::where('organization_id', $user->organization_id)->get();
        } elseif ($user->role->name === 'User') {
            $reports = Report::where('organization_id', $user->organization_id)->get();
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
        // Ensure user can create reports
        if (!Gate::allows('create-edit-reports')) {
            abort(403, 'You are not authorized to create reports');
        }

        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-edit-reports')) {
            abort(403, 'You are not authorized to create reports');
        }
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'organization_id' => Auth::user()->role->name === 'Administrator' ? 'required|exists:organizations,id' : '',
            'language' => 'required|string|size:2',
            'defects' => 'required|array|min:1',
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.severity' => 'required|in:low,medium,high,critical',
            'defects.*.description' => 'nullable|string',
            'defects.*.coordinates.latitude' => 'nullable|numeric',
            'defects.*.coordinates.longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|max:10240', // Max 10MB
        ]);

        // Determine organization_id based on user role
        $organizationId = Auth::user()->role->name === 'Administrator'
            ? $request->organization_id
            : Auth::user()->organization_id;

        if (!$organizationId) {
            return redirect()->back()->with('error', 'You need to be part of an organization to create reports.')->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the report
            $report = Report::create([
                'title' => $request->title,
                'organization_id' => Auth::user()->organization_id,
                'created_by' => Auth::id(),
                'created_by' => Auth::id(),
                'language' => $request->language,
            ]);

            // Create the defects
            foreach ($request->defects as $defectData) {
                $coordinates = null;
                if (isset($defectData['coordinates']) &&
                    isset($defectData['coordinates']['latitude']) &&
                    isset($defectData['coordinates']['longitude'])) {
                    $coordinates = [
                        'latitude' => $defectData['coordinates']['latitude'],
                        'longitude' => $defectData['coordinates']['longitude'],
                    ];
                }

                ReportDefect::create([
                    'report_id' => $report->id,
                    'defect_type_id' => $defectData['defect_type_id'],
                    'description' => $defectData['description'] ?? null,
                    'severity' => $defectData['severity'],
                    'coordinates' => $coordinates,
                ]);
            }

            // Upload and save images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('report-images', 'public');

                    ReportImage::create([
                        'report_id' => $report->id,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while creating the report: ' . $e->getMessage())
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'organization_id' => Auth::user()->role->name === 'Administrator' ? 'required|exists:organizations,id' : '',
            'language' => 'required|string|size:2',
            'defects' => 'required|array|min:1',
            'defects.*.id' => 'nullable|exists:report_defects,id,report_id,' . $report->id,
            'defects.*.defect_type_id' => 'required|exists:defect_types,id',
            'defects.*.severity' => 'required|in:low,medium,high,critical',
            'defects.*.description' => 'nullable|string',
            'defects.*.coordinates.latitude' => 'nullable|numeric',
            'defects.*.coordinates.longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|max:10240', // Max 10MB
        ]);

        try {
            DB::beginTransaction();

            // Update report details
            $report->title = $request->title;
            $report->description = $request->description;
            $report->language = $request->language;

            // Only administrators can change organization
            if (Auth::user()->role->name === 'Administrator') {
                $report->organization_id = $request->organization_id;
            }

            $report->save();

            // Process defects
            $existingDefectIds = [];

            foreach ($request->defects as $defectData) {
                $coordinates = null;
                if (isset($defectData['coordinates']) &&
                    isset($defectData['coordinates']['latitude']) &&
                    isset($defectData['coordinates']['longitude'])) {
                    $coordinates = [
                        'latitude' => $defectData['coordinates']['latitude'],
                        'longitude' => $defectData['coordinates']['longitude'],
                    ];
                }

                if (isset($defectData['id'])) {
                    // Update existing defect
                    $defect = ReportDefect::where('id', $defectData['id'])
                        ->where('report_id', $report->id)
                        ->first();

                    if ($defect) {
                        $defect->update([
                            'defect_type_id' => $defectData['defect_type_id'],
                            'description' => $defectData['description'] ?? null,
                            'severity' => $defectData['severity'],
                            'coordinates' => $coordinates,
                        ]);

                        $existingDefectIds[] = $defect->id;
                    }
                } else {
                    // Create new defect
                    $defect = ReportDefect::create([
                        'report_id' => $report->id,
                        'defect_type_id' => $defectData['defect_type_id'],
                        'description' => $defectData['description'] ?? null,
                        'severity' => $defectData['severity'],
                        'coordinates' => $coordinates,
                    ]);

                    $existingDefectIds[] = $defect->id;
                }
            }

            // Delete defects that weren't included in the request
            ReportDefect::where('report_id', $report->id)
                ->whereNotIn('id', $existingDefectIds)
                ->delete();

            // Upload and save new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('report-images', 'public');

                    ReportImage::create([
                        'report_id' => $report->id,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('reports.show', $report)
                ->with('success', 'Report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while updating the report: ' . $e->getMessage())
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
     * Export the report as PDF.
     *
     * @param  \App\Models\Report  $report
     * @param  \App\Services\PdfExportService  $pdfService
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Report $report, PdfExportService $pdfService)
    {
        if (!Gate::allows('export-pdf', $report)) {
            abort(403, 'You are not authorized to export this report');
        }
        // Check permission to view report
        $this->authorize('view', $report);

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
}
