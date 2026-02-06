<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\KategoriPupuk;
use App\Models\Nutrisi;
use App\Models\Pupuk;
use App\Models\Desa;
use App\Models\Stok;
use App\Models\DistribusiPupuk;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ExcelExportService;

class ExportAllController extends Controller
{
    protected $excelService;

    public function __construct(ExcelExportService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function index(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Apply date filters for Stok
        $stokQuery = Stok::with('pupuk');
        if ($month || $year) {
            $stokQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            });
        }
        $stok = $stokQuery->get();

        // Apply date filters for DistribusiPupuk
        $distribusiQuery = DistribusiPupuk::with(['details.pupuk', 'desa']);
        if ($month || $year) {
            $distribusiQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('tanggal_distribusi', $month);
                }
                if ($year) {
                    $query->whereYear('tanggal_distribusi', $year);
                }
            });
        }
        $distribusi = $distribusiQuery->get();

        // Jika ada filter periode, hanya tampilkan data master yang terkait dengan transaksi
        if ($month || $year) {
            // Ambil ID yang terkait dengan transaksi
            $pupukIds = collect([]);
            $pupukIds = $pupukIds->merge($stok->pluck('pupuk_id'));
            // Ambil pupuk_id dari details, bukan dari distribusi langsung
            $distribusi->each(function($dist) use (&$pupukIds) {
                if ($dist->details) {
                    $pupukIds = $pupukIds->merge($dist->details->pluck('pupuk_id'));
                }
            });
            $pupukIds = $pupukIds->unique()->values();

            $desaIds = $distribusi->pluck('desa_id')->unique()->values();

            // Filter data master berdasarkan ID yang terkait
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->whereIn('id', $pupukIds)->get();
            $kategoriIds = $pupuk->pluck('kategori_id')->unique()->values();
            $kategori = KategoriPupuk::whereIn('id', $kategoriIds)->get();

            // Ambil nutrisi yang terkait dengan pupuk yang ada
            $nutrisiIds = $pupuk->flatMap(function($p) {
                return $p->nutrisi->pluck('id');
            })->unique()->values();
            $nutrisi = Nutrisi::whereIn('id', $nutrisiIds)->get();

            $desa = Desa::whereIn('id', $desaIds)->get();
        } else {
            // Jika tidak ada filter, tampilkan semua data
            $kategori = KategoriPupuk::all();
            $nutrisi = Nutrisi::all();
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->get();
            $desa = Desa::all();
        }

        // Prepare summary
        $summary = [
            'kategori' => $kategori->count(),
            'nutrisi' => $nutrisi->count(),
            'pupuk' => $pupuk->count(),
            'desa' => $desa->count(),
            'stok' => $stok->count(),
            'distribusi' => $distribusi->count(),
        ];

        // Prepare data for preview (limit to 5 items each for performance)
        $data = [
            'kategori' => $kategori->take(5),
            'nutrisi' => $nutrisi->take(5),
            'desa' => $desa->take(5),
            'pupuk' => $pupuk->take(5),
            'stok' => $stok->take(5),
            'distribusi' => $distribusi->take(5),
        ];

        return Inertia::render('Admin/ExportAll', [
            'summary' => $summary,
            'data' => $data,
            'filters' => [
                'month' => $month,
                'year' => $year,
            ],
        ]);
    }

    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Apply date filters for Stok
        $stokQuery = Stok::with('pupuk');
        if ($month || $year) {
            $stokQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            });
        }
        $stok = $stokQuery->orderBy('created_at', 'desc')->get();

        // Apply date filters for DistribusiPupuk
        $distribusiQuery = DistribusiPupuk::with(['details.pupuk', 'desa']);
        if ($month || $year) {
            $distribusiQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('tanggal_distribusi', $month);
                }
                if ($year) {
                    $query->whereYear('tanggal_distribusi', $year);
                }
            });
        }
        $distribusi = $distribusiQuery->orderBy('tanggal_distribusi', 'desc')->get();

        // Jika ada filter periode, hanya tampilkan data master yang terkait dengan transaksi
        if ($month || $year) {
            // Ambil ID yang terkait dengan transaksi
            $pupukIds = collect([]);
            $pupukIds = $pupukIds->merge($stok->pluck('pupuk_id'));
            // Ambil pupuk_id dari details
            $distribusi->each(function($dist) use (&$pupukIds) {
                if ($dist->details) {
                    $pupukIds = $pupukIds->merge($dist->details->pluck('pupuk_id'));
                }
            });
            $pupukIds = $pupukIds->unique()->values();

            $desaIds = $distribusi->pluck('desa_id')->unique()->values();

            // Filter data master berdasarkan ID yang terkait
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->whereIn('id', $pupukIds)->orderBy('nama_pupuk')->get();
            $kategoriIds = $pupuk->pluck('kategori_id')->unique()->values();
            $kategori = KategoriPupuk::whereIn('id', $kategoriIds)->orderBy('nama_kategori')->get();

            // Ambil nutrisi yang terkait dengan pupuk yang ada
            $nutrisiIds = $pupuk->flatMap(function($p) {
                return $p->nutrisi->pluck('id');
            })->unique()->values();
            $nutrisi = Nutrisi::whereIn('id', $nutrisiIds)->orderBy('nama_nutrisi')->get();

            $desa = Desa::whereIn('id', $desaIds)->orderBy('nama_desa')->get();
        } else {
            // Jika tidak ada filter, tampilkan semua data
            $kategori = KategoriPupuk::orderBy('nama_kategori')->get();
            $nutrisi = Nutrisi::orderBy('nama_nutrisi')->get();
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->orderBy('nama_pupuk')->get();
            $desa = Desa::orderBy('nama_desa')->get();
        }

        $data = [
            'kategori' => $kategori,
            'nutrisi' => $nutrisi,
            'pupuk' => $pupuk,
            'desa' => $desa,
            'stok' => $stok,
            'distribusi' => $distribusi,
            'tanggal' => now()->format('d F Y'),
            'periode' => $this->getPeriodeText($month, $year),
        ];

        $pdf = Pdf::loadView('exports.all-data-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('chroot', public_path());
        $pdf->setOption('enable_php', false);
        $pdf->setOption('debugKeepTemp', false);

        $filename = 'semua-data-inventaris-pupuk';
        if ($month || $year) {
            $filename .= '-' . ($year ?: 'semua-tahun') . '-' . ($month ?: 'semua-bulan');
        }
        $filename .= '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Apply date filters for Stok
        $stokQuery = Stok::with('pupuk');
        if ($month || $year) {
            $stokQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            });
        }
        $stok = $stokQuery->orderBy('created_at', 'desc')->get();

        // Apply date filters for DistribusiPupuk
        $distribusiQuery = DistribusiPupuk::with(['details.pupuk', 'desa']);
        if ($month || $year) {
            $distribusiQuery->where(function($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('tanggal_distribusi', $month);
                }
                if ($year) {
                    $query->whereYear('tanggal_distribusi', $year);
                }
            });
        }
        $distribusi = $distribusiQuery->orderBy('tanggal_distribusi', 'desc')->get();

        // Jika ada filter periode, hanya tampilkan data master yang terkait dengan transaksi
        if ($month || $year) {
            // Ambil ID yang terkait dengan transaksi
            $pupukIds = collect([]);
            $pupukIds = $pupukIds->merge($stok->pluck('pupuk_id'));
            // Ambil pupuk_id dari details
            $distribusi->each(function($dist) use (&$pupukIds) {
                if ($dist->details) {
                    $pupukIds = $pupukIds->merge($dist->details->pluck('pupuk_id'));
                }
            });
            $pupukIds = $pupukIds->merge($distribusi->pluck('pupuk_id'));
            $pupukIds = $pupukIds->unique()->values();

            $desaIds = $distribusi->pluck('desa_id')->unique()->values();

            // Filter data master berdasarkan ID yang terkait
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->whereIn('id', $pupukIds)->orderBy('nama_pupuk')->get();
            $kategoriIds = $pupuk->pluck('kategori_id')->unique()->values();
            $kategori = KategoriPupuk::whereIn('id', $kategoriIds)->orderBy('nama_kategori')->get();

            // Ambil nutrisi yang terkait dengan pupuk yang ada
            $nutrisiIds = $pupuk->flatMap(function($p) {
                return $p->nutrisi->pluck('id');
            })->unique()->values();
            $nutrisi = Nutrisi::whereIn('id', $nutrisiIds)->orderBy('nama_nutrisi')->get();

            $desa = Desa::whereIn('id', $desaIds)->orderBy('nama_desa')->get();
        } else {
            // Jika tidak ada filter, tampilkan semua data
            $kategori = KategoriPupuk::orderBy('nama_kategori')->get();
            $nutrisi = Nutrisi::orderBy('nama_nutrisi')->get();
            $pupuk = Pupuk::with(['kategori', 'nutrisi'])->orderBy('nama_pupuk')->get();
            $desa = Desa::orderBy('nama_desa')->get();
        }

        $data = [
            'kategori' => $kategori,
            'nutrisi' => $nutrisi,
            'pupuk' => $pupuk,
            'desa' => $desa,
            'stok' => $stok,
            'distribusi' => $distribusi,
        ];

        return $this->excelService->exportAllData($data);
    }

    private function getPeriodeText($month, $year)
    {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        if ($month && $year) {
            return $months[$month] . ' ' . $year;
        } elseif ($month) {
            return 'Bulan ' . $months[$month] . ' (Semua Tahun)';
        } elseif ($year) {
            return 'Tahun ' . $year . ' (Semua Bulan)';
        }

        return 'Semua Periode';
    }
}
