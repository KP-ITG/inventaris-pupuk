<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Pupuk;
use App\Models\Stok;
use App\Models\DistribusiPupuk;
use App\Models\DistribusiPupukDetail;
use App\Models\Desa;
use App\Models\KategoriPupuk;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Sistem sekarang hanya untuk admin (dinas pertanian internal)
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        // Hitung total stok terdistribusi bulan ini (dari detail yang selesai)
        $stokTerdistribusiBulanIni = DB::table('distribusi_pupuk_detail')
            ->join('distribusi_pupuk', 'distribusi_pupuk_detail.distribusi_pupuk_id', '=', 'distribusi_pupuk.id')
            ->whereMonth('distribusi_pupuk.created_at', now()->month)
            ->whereYear('distribusi_pupuk.created_at', now()->year)
            ->where('distribusi_pupuk.status_distribusi', 'selesai')
            ->sum('distribusi_pupuk_detail.jumlah_distribusi');

        // Statistik utama sistem dinas pertanian
        $stats = [
            'total_pupuk' => Pupuk::count(),
            'total_stok' => Stok::sum('jumlah_stok'),
            'total_distribusi' => DistribusiPupuk::count(),
            'total_desa' => Desa::count(),
            'kategori_pupuk' => KategoriPupuk::count(),
            'distribusi_bulan_ini' => DistribusiPupuk::whereMonth('created_at', now()->month)
                                                   ->whereYear('created_at', now()->year)
                                                   ->count(),
            'stok_terdistribusi_bulan_ini' => $stokTerdistribusiBulanIni,
            'distribusi_pending' => DistribusiPupuk::where('status_distribusi', 'rencana')->count(),
        ];

        // Stok pupuk yang rendah (kurang dari 100 kg)
        $lowStock = Stok::with(['pupuk.kategori'])
            ->where('jumlah_stok', '<', 100)
            ->orderBy('jumlah_stok', 'asc')
            ->take(5)
            ->get();

        // Distribusi terbaru - transform untuk backward compatibility dengan frontend
        $recentDistribusiRaw = DistribusiPupuk::with(['details.pupuk', 'desa'])
            ->latest()
            ->take(5)
            ->get();

        // Transform untuk compatibility dengan frontend
        $recentDistribusi = $recentDistribusiRaw->map(function($dist) {
            $firstDetail = $dist->details->first();
            return [
                'id' => $dist->id,
                'nomor_distribusi' => $dist->nomor_distribusi,
                'desa' => $dist->desa,
                'status_distribusi' => $dist->status_distribusi,
                'pupuk' => $firstDetail ? $firstDetail->pupuk : null,
                'jumlah_distribusi' => $dist->details->sum('jumlah_distribusi'),
                'detail_count' => $dist->details->count()
            ];
        });

        // Statistik status distribusi
        $statusStats = [
            'rencana' => DistribusiPupuk::where('status_distribusi', 'rencana')->count(),
            'dalam_perjalanan' => DistribusiPupuk::where('status_distribusi', 'dalam_perjalanan')->count(),
            'selesai' => DistribusiPupuk::where('status_distribusi', 'selesai')->count(),
            'batal' => DistribusiPupuk::where('status_distribusi', 'batal')->count(),
        ];

        // Top 5 desa dengan distribusi terbanyak
        $topDesaRaw = DB::table('distribusi_pupuk')
            ->join('desa', 'distribusi_pupuk.desa_id', '=', 'desa.id')
            ->leftJoin('distribusi_pupuk_detail', 'distribusi_pupuk.id', '=', 'distribusi_pupuk_detail.distribusi_pupuk_id')
            ->select(
                'desa.id as desa_id',
                'desa.nama_desa',
                'desa.kecamatan',
                DB::raw('COUNT(DISTINCT distribusi_pupuk.id) as total_distribusi'),
                DB::raw('COALESCE(SUM(distribusi_pupuk_detail.jumlah_distribusi), 0) as total_pupuk')
            )
            ->groupBy('desa.id', 'desa.nama_desa', 'desa.kecamatan')
            ->orderByDesc('total_distribusi')
            ->take(5)
            ->get();

        // Transform untuk match frontend structure
        $topDesa = $topDesaRaw->map(function($item) {
            return [
                'desa_id' => $item->desa_id,
                'desa' => [
                    'id' => $item->desa_id,
                    'nama_desa' => $item->nama_desa,
                    'kecamatan' => $item->kecamatan
                ],
                'total_distribusi' => $item->total_distribusi,
                'total_pupuk' => $item->total_pupuk
            ];
        });

        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'stats' => $stats,
            'statusStats' => $statusStats,
            'lowStock' => $lowStock,
            'recentDistribusi' => $recentDistribusi,
            'topDesa' => $topDesa,
        ]);
    }
}
