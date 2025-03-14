<?php

namespace App\Services;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

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
        // Save current locale to restore later
        $previousLocale = App::getLocale();

        try {
            // Increment export counter
            $report->increment('pdf_export_count');

            // Set language for PDF
            $language = $language ?? $report->language ?? config('app.locale', 'en');

            // Make sure the language exists
            if (!in_array($language, config('app.available_locales', ['en', 'fr', 'de']))) {
                $language = config('app.locale', 'en');
            }

            // Set the locale for translations
            App::setLocale($language);

            Log::info("Generating PDF with language: {$language}, App locale: " . App::getLocale());

            // Load necessary relationships with eager loading
            $report->load([
                'reportDefects.defectType',
                'reportImages',
                'reportComments.user' => function ($query) use ($includeComments) {
                    if ($includeComments) {
                        $query->where('include_in_pdf', true)
                              ->orderBy('created_at', 'asc');
                    }
                },
                'organization',
                'creator'
            ]);

            // Debug check
            Log::info("Report loaded with " . $report->reportDefects->count() . " defects");

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf-export', [
                'report' => $report,
                'includeComments' => $includeComments,
            ]);

            // Set paper size and other options for a professional result
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption('defaultFont', 'Arial');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            // Set margins
            $pdf->setOption('margin-top', '10mm');
            $pdf->setOption('margin-right', '10mm');
            $pdf->setOption('margin-bottom', '15mm'); // Increased for footer
            $pdf->setOption('margin-left', '10mm');

            // Enable footer
            $footerHtml = View::make('reports.pdf-footer', [
                'report' => $report,
                'pageNumber' => true,
            ])->render();

            $pdf->setOption('footer-html', $footerHtml);

            return $pdf;
        }
        catch (\Exception $e) {
            // Log the error
            Log::error("PDF generation error: " . $e->getMessage());
            throw $e;
        }
        finally {
            // Reset to previous locale
            App::setLocale($previousLocale);
        }
    }
}
