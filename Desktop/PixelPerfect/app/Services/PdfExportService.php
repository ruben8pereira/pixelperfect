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
        $pdf = PDF::loadView('reports.pdf-export', [
            'report' => $report,
            'includeComments' => $includeComments,
        ]);

        // Set paper size and other options for a professional result
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('defaultFont', 'Arial');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        // Set margins to match example
        $pdf->setOption('margin-top', '10mm');
        $pdf->setOption('margin-right', '10mm');
        $pdf->setOption('margin-bottom', '10mm');
        $pdf->setOption('margin-left', '10mm');

        return $pdf;
    }
}
