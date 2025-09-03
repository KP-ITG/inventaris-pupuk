<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\Pupuk;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StokController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Admin dapat melihat semua stok
            $stocks = Stok::with(['pupuk.kategori', 'pengguna'])->get();
            $users = Pengguna::where('role', 'distributor')->where('status', 'approved')->get();
        } else {
            // Distributor hanya melihat stok miliknya
            $stocks = Stok::with(['pupuk.kategori'])
                ->where('pengguna_id', $user->id)
                ->get();
            $users = [];
        }

        $pupuks = Pupuk::all();

        return Inertia::render('Admin/Stok', [
            'stocks' => $stocks,
            'pupuks' => $pupuks,
            'users' => $users,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $request->validate([
                'pupuk_id' => 'required|exists:pupuk,id',
                'pengguna_id' => 'required|exists:pengguna,id',
                'jumlah_stok' => 'required|numeric|min:0',
            ]);

            Stok::create([
                'pupuk_id' => $request->pupuk_id,
                'pengguna_id' => $request->pengguna_id,
                'jumlah_stok' => $request->jumlah_stok,
            ]);
        } else {
            // Distributor hanya bisa menambah stok untuk dirinya
            $request->validate([
                'pupuk_id' => 'required|exists:pupuk,id',
                'jumlah_stok' => 'required|numeric|min:0',
            ]);

            Stok::create([
                'pupuk_id' => $request->pupuk_id,
                'pengguna_id' => $user->id,
                'jumlah_stok' => $request->jumlah_stok,
            ]);
        }

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $stock = Stok::findOrFail($id);

        if ($user->role === 'admin') {
            $request->validate([
                'pupuk_id' => 'required|exists:pupuk,id',
                'pengguna_id' => 'required|exists:pengguna,id',
                'jumlah_stok' => 'required|numeric|min:0',
            ]);

            $stock->update([
                'pupuk_id' => $request->pupuk_id,
                'pengguna_id' => $request->pengguna_id,
                'jumlah_stok' => $request->jumlah_stok,
            ]);
        } else {
            // Distributor hanya bisa update stok miliknya dan hanya jumlah
            if ($stock->pengguna_id !== $user->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah stok ini');
            }

            $request->validate([
                'jumlah_stok' => 'required|numeric|min:0',
            ]);

            $stock->update([
                'jumlah_stok' => $request->jumlah_stok,
            ]);
        }

        return redirect()->back()->with('success', 'Stok berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $stock = Stok::findOrFail($id);

        // Hanya admin yang bisa menghapus stok
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus stok');
        }

        $stock->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus');
    }
}
