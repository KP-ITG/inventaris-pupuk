<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PdfExportService
{
    /**
     * Export data to PDF
     *
     * @param string $title
     * @param array $headers
     * @param Collection $data
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function export($title, $headers, $data, $filename)
    {
        $pdf = PDF::loadView('exports.pdf', [
            'title' => $title,
            'headers' => $headers,
            'data' => $data,
            'date' => now()->format('d/m/Y H:i:s'),
        ]);

        return $pdf->download($filename . '.pdf');
    }
}