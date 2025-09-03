<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Pupuk;
use App\Models\Stok;
use App\Models\DistribusiPupuk;
use App\Models\Desa;
use App\Models\KategoriPupuk;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            'stok_terdistribusi_bulan_ini' => DistribusiPupuk::whereMonth('created_at', now()->month)
                                                            ->whereYear('created_at', now()->year)
                                                            ->where('status_distribusi', 'selesai')
                                                            ->sum('jumlah_distribusi'),
            'distribusi_pending' => DistribusiPupuk::where('status_distribusi', 'rencana')->count(),
        ];

        // Stok pupuk yang rendah (kurang dari 100 kg)
        $lowStock = Stok::with(['pupuk.kategori'])
            ->where('jumlah_stok', '<', 100)
            ->orderBy('jumlah_stok', 'asc')
            ->take(5)
            ->get();

        // Distribusi terbaru
        $recentDistribusi = DistribusiPupuk::with(['pupuk', 'desa'])
            ->latest()
            ->take(5)
            ->get();

        // Statistik status distribusi
        $statusStats = [
            'rencana' => DistribusiPupuk::where('status_distribusi', 'rencana')->count(),
            'dalam_perjalanan' => DistribusiPupuk::where('status_distribusi', 'dalam_perjalanan')->count(),
            'selesai' => DistribusiPupuk::where('status_distribusi', 'selesai')->count(),
            'batal' => DistribusiPupuk::where('status_distribusi', 'batal')->count(),
        ];

        // Top 5 desa dengan distribusi terbanyak
        $topDesa = DistribusiPupuk::with('desa')
            ->selectRaw('desa_id, COUNT(*) as total_distribusi, SUM(jumlah_distribusi) as total_pupuk')
            ->groupBy('desa_id')
            ->orderByDesc('total_distribusi')
            ->take(5)
            ->get();

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
