<?php

namespace App\Services;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PdfExportService
{
    /**
     * Generate a PDF for a report.
     *
     * @param  \App\Models\Report  $report
     * @param  bool  $includeComments
     * @param  string|null  $language
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateReportPdf(Report $report, $includeComments = true, $language = null)
    {
        // Increment export counter
        $report->increment('pdf_export_count');

        // Set language for PDF
        $language = $language ?? $report->language;
        App::setLocale($language);

        // Load necessary relationships
        $report->load([
            'reportDefects.defectType',
            'reportImages',
            'organization',
            'creator'
        ]);

        // Load comments if requested
        if ($includeComments) {
            $report->load(['reportComments' => function ($query) {
                $query->where('include_in_pdf', true)
                      ->orderBy('created_at', 'asc');
            }, 'reportComments.user']);
        }

        // Generate PDF with fixed dimensions
        $pdf = PDF::loadView('reports.pdf', [
            'report' => $report,
            'includeComments' => $includeComments,
        ]);

        // Set paper size and other options
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('defaultFont', 'sans-serif');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf;
    }
}
