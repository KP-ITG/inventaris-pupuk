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

    public static function exportAllData($data)
    {
        $spreadsheet = new Spreadsheet();

        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        // Create and fill Kategori sheet
        $sheetKategori = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Kategori');
        $spreadsheet->addSheet($sheetKategori);
        self::fillKategoriSheet($sheetKategori, $data['kategori']);

        // Create and fill Nutrisi sheet
        $sheetNutrisi = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Nutrisi');
        $spreadsheet->addSheet($sheetNutrisi);
        self::fillNutrisiSheet($sheetNutrisi, $data['nutrisi']);

        // Create and fill Desa sheet
        $sheetDesa = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Desa');
        $spreadsheet->addSheet($sheetDesa);
        self::fillDesaSheet($sheetDesa, $data['desa']);

        // Create and fill Pupuk sheet
        $sheetPupuk = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Pupuk');
        $spreadsheet->addSheet($sheetPupuk);
        self::fillPupukSheet($sheetPupuk, $data['pupuk']);

        // Create and fill Stok sheet
        $sheetStok = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Stok');
        $spreadsheet->addSheet($sheetStok);
        self::fillStokSheet($sheetStok, $data['stok']);

        // Create and fill Distribusi sheet
        $sheetDistribusi = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Distribusi');
        $spreadsheet->addSheet($sheetDistribusi);
        self::fillDistribusiSheet($sheetDistribusi, $data['distribusi']);

        // Set active sheet to first
        $spreadsheet->setActiveSheetIndex(0);

        // Create response
        $writer = new Xlsx($spreadsheet);
        $filename = 'semua-data-inventaris-pupuk-' . date('Y-m-d') . '.xlsx';

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

    private static function fillKategoriSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA KATEGORI PUPUK');
        $sheet->mergeCells('A1:C1');
        self::styleHeader($sheet, 'A1:C1');

        $headers = ['No', 'Nama Kategori', 'Deskripsi'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:C3');

        $row = 4;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_kategori);
            $sheet->setCellValue('C' . $row, $item->deskripsi ?? '-');
            $row++;
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C']);
    }

    private static function fillNutrisiSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA NUTRISI');
        $sheet->mergeCells('A1:D1');
        self::styleHeader($sheet, 'A1:D1');

        $headers = ['No', 'Nama Nutrisi', 'Simbol', 'Deskripsi'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:D3');

        $row = 4;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_nutrisi);
            $sheet->setCellValue('C' . $row, $item->simbol_nutrisi);
            $sheet->setCellValue('D' . $row, $item->deskripsi_nutrisi ?? '-');
            $row++;
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C', 'D']);
    }

    private static function fillDesaSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA DESA');
        $sheet->mergeCells('A1:E1');
        self::styleHeader($sheet, 'A1:E1');

        $headers = ['No', 'Nama Desa', 'Kecamatan', 'Luas Wilayah (ha)', 'Jumlah Penduduk'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:E3');

        $row = 4;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_desa);
            $sheet->setCellValue('C' . $row, $item->kecamatan);
            $sheet->setCellValue('D' . $row, $item->luas_wilayah);
            $sheet->setCellValue('E' . $row, $item->jumlah_penduduk);
            $row++;
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E']);
    }

    private static function fillPupukSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA PUPUK');
        $sheet->mergeCells('A1:E1');
        self::styleHeader($sheet, 'A1:E1');

        $headers = ['No', 'Nama Pupuk', 'Kategori', 'Harga Jual (Rp)', 'Nutrisi'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:E3');

        $row = 4;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_pupuk);
            $sheet->setCellValue('C' . $row, $item->kategori->nama_kategori ?? '-');
            $sheet->setCellValue('D' . $row, 'Rp ' . number_format($item->harga_jual ?? 0, 0, ',', '.'));
            $nutrisiList = $item->nutrisi->pluck('nama_nutrisi')->implode(', ');
            $sheet->setCellValue('E' . $row, $nutrisiList ?: '-');
            $row++;
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E']);
    }

    private static function fillStokSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA STOK');
        $sheet->mergeCells('A1:F1');
        self::styleHeader($sheet, 'A1:F1');

        $headers = ['No', 'Nama Pupuk', 'Jumlah Stok (kg)', 'Min (kg)', 'Max (kg)', 'Lokasi Gudang'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:F3');

        $row = 4;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->pupuk->nama_pupuk ?? '-');
            $sheet->setCellValue('C' . $row, $item->jumlah_stok);
            $sheet->setCellValue('D' . $row, $item->stok_minimum);
            $sheet->setCellValue('E' . $row, $item->stok_maksimum);
            $sheet->setCellValue('F' . $row, $item->lokasi_gudang ?? '-');
            $row++;
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E', 'F']);
    }

    private static function fillDistribusiSheet($sheet, $data)
    {
        $sheet->setCellValue('A1', 'DATA DISTRIBUSI PUPUK');
        $sheet->mergeCells('A1:H1');
        self::styleHeader($sheet, 'A1:H1');

        $headers = ['No', 'Nomor Distribusi', 'Desa', 'Kecamatan', 'Daftar Pupuk', 'Jumlah per Item', 'Total (kg)', 'Tanggal'];
        $sheet->fromArray($headers, null, 'A3');
        self::styleTableHeader($sheet, 'A3:H3');

        $row = 4;
        $no = 1;
        foreach ($data as $item) {
            // Jika ada detail items
            if ($item->details && $item->details->count() > 0) {
                $firstRow = true;
                $startRow = $row;

                foreach ($item->details as $detail) {
                    if ($firstRow) {
                        $sheet->setCellValue('A' . $row, $no);
                        $sheet->setCellValue('B' . $row, $item->nomor_distribusi);
                        $sheet->setCellValue('C' . $row, $item->desa->nama_desa ?? '-');
                        $sheet->setCellValue('D' . $row, $item->desa->kecamatan ?? '-');
                    }

                    $sheet->setCellValue('E' . $row, $detail->pupuk->nama_pupuk ?? '-');
                    $sheet->setCellValue('F' . $row, $detail->jumlah_distribusi . ' kg');

                    if ($firstRow) {
                        $sheet->setCellValue('G' . $row, $item->details->sum('jumlah_distribusi') . ' kg');
                        $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($item->tanggal_distribusi)));
                    }

                    $row++;
                    $firstRow = false;
                }

                // Merge cells untuk kolom yang sama di multiple rows
                if ($item->details->count() > 1) {
                    $endRow = $row - 1;
                    $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                    $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                    $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                    $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                    $sheet->mergeCells('G' . $startRow . ':G' . $endRow);
                    $sheet->mergeCells('H' . $startRow . ':H' . $endRow);

                    // Center align merged cells
                    $sheet->getStyle('A' . $startRow . ':A' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('B' . $startRow . ':B' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('C' . $startRow . ':C' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('D' . $startRow . ':D' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('G' . $startRow . ':G' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('H' . $startRow . ':H' . $endRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

                $no++;
            } else {
                // Fallback untuk data lama tanpa details
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $item->nomor_distribusi);
                $sheet->setCellValue('C' . $row, $item->desa->nama_desa ?? '-');
                $sheet->setCellValue('D' . $row, $item->desa->kecamatan ?? '-');
                $sheet->setCellValue('E' . $row, 'Tidak ada item');
                $sheet->setCellValue('F' . $row, '-');
                $sheet->setCellValue('G' . $row, '0 kg');
                $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($item->tanggal_distribusi)));
                $row++;
                $no++;
            }
        }

        self::autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']);
    }

    private static function styleHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669']
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);
    }

    private static function styleTableHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
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
                'startColor' => ['rgb' => '10B981']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
    }

    private static function autoSizeColumns($sheet, $columns)
    {
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
