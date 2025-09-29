<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExcelExportService
{
    public static function export($data, $headers, $filename, $title = 'Data Export')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Dinas Pertanian')
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription('Data export dari sistem inventaris pupuk');

        // Set title
        $sheet->setCellValue('A1', strtoupper($title));
        $sheet->mergeCells('A1:' . chr(65 + count($headers) - 1) . '1');

        // Style title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Green-600
            ]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);

        // Add date
        $sheet->setCellValue('A2', 'Dicetak pada: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . chr(65 + count($headers) - 1) . '2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Set headers starting from row 4
        $headerRow = 4;
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . $headerRow, $header);
        }

        // Style headers
        $headerRange = 'A' . $headerRow . ':' . chr(65 + count($headers) - 1) . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '16A34A'] // Green-600
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Add data
        $dataStartRow = $headerRow + 1;
        foreach ($data as $rowIndex => $row) {
            $currentRow = $dataStartRow + $rowIndex;
            foreach ($row as $colIndex => $value) {
                $column = chr(65 + $colIndex);
                $sheet->setCellValue($column . $currentRow, $value);
            }
        }

        // Style data rows
        if (!empty($data)) {
            $dataRange = 'A' . $dataStartRow . ':' . chr(65 + count($headers) - 1) . ($dataStartRow + count($data) - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Alternate row colors
            for ($i = 0; $i < count($data); $i += 2) {
                $rowNum = $dataStartRow + $i;
                $rowRange = 'A' . $rowNum . ':' . chr(65 + count($headers) - 1) . $rowNum;
                $sheet->getStyle($rowRange)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9F9F9');
            }
        }

        // Auto-size columns
        foreach (range('A', chr(65 + count($headers) - 1)) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set minimum column widths
        foreach (range('A', chr(65 + count($headers) - 1)) as $column) {
            $currentWidth = $sheet->getColumnDimension($column)->getWidth();
            if ($currentWidth < 15) {
                $sheet->getColumnDimension($column)->setWidth(15);
            }
        }

        // Create response
        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}