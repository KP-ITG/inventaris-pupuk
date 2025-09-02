<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Pupuk;
use App\Models\Stok;
use App\Models\TransaksiDistribusi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->distributorDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => Pengguna::count(),
            'pending_users' => Pengguna::where('status', 'pending')->count(),
            'total_pupuk' => Pupuk::count(),
            'total_stok' => Stok::sum('jumlah_stok'),
            'total_transaksi' => TransaksiDistribusi::count(),
        ];

        $recentUsers = Pengguna::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $lowStock = Stok::with(['pupuk', 'pengguna'])
            ->where('jumlah_stok', '<', 10)
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'lowStock' => $lowStock,
        ]);
    }

    private function distributorDashboard()
    {
        $user = auth()->user();

        $myStocks = Stok::with('pupuk')
            ->where('pengguna_id', $user->id)
            ->get();

        $myTransactions = TransaksiDistribusi::with('pupuk')
            ->where('pengguna_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_stok' => $myStocks->sum('jumlah_stok'),
            'jenis_pupuk' => $myStocks->count(),
            'total_transaksi' => $myTransactions->count(),
            'stok_rendah' => $myStocks->where('jumlah_stok', '<', 10)->count(),
        ];

        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'stats' => $stats,
            'myStocks' => $myStocks,
            'myTransactions' => $myTransactions,
        ]);
    }
}
