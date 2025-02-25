<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportImageController extends Controller
{
    /**
     * Store a newly created image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Report $report)
    {
        // Check permission to update report
        $this->authorize('update', $report);

        $request->validate([
            'image' => 'required|image|max:10240', // Max 10MB
            'caption' => 'nullable|string|max:255',
        ]);

        try {
            $path = $request->file('image')->store('report-images', 'public');

            ReportImage::create([
                'report_id' => $report->id,
                'file_path' => $path,
                'caption' => $request->caption,
            ]);

            return redirect()->route('reports.show', $report)
                ->with('success', 'Image uploaded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while uploading the image: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @param  \App\Models\ReportImage  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report, ReportImage $image)
    {
        // Check permission to update report
        $this->authorize('update', $report);

        // Ensure the image belongs to the report
        if ($image->report_id !== $report->id) {
            return redirect()->back()
                ->with('error', 'The image does not belong to this report.');
        }

        $request->validate([
            'caption' => 'nullable|string|max:255',
            'drawing_data' => 'nullable|json',
        ]);

        try {
            $image->update([
                'caption' => $request->caption,
                'drawing_data' => $request->drawing_data,
            ]);

            return redirect()->route('reports.show', $report)
                ->with('success', 'Image updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the image: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified image from storage.
     *
     * @param  \App\Models\Report  $report
     * @param  \App\Models\ReportImage  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report, ReportImage $image)
    {
        // Check permission to update report
        $this->authorize('update', $report);

        // Ensure the image belongs to the report
        if ($image->report_id !== $report->id) {
            return redirect()->back()
                ->with('error', 'The image does not belong to this report.');
        }

        try {
            // Delete the file from storage
            if (Storage::disk('public')->exists($image->file_path)) {
                Storage::disk('public')->delete($image->file_path);
            }

            // Delete the database record
            $image->delete();

            return redirect()->route('reports.edit', $report)
                ->with('success', 'Image deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the image: ' . $e->getMessage());
        }
    }
}
