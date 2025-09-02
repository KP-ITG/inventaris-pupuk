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
        $stocks = Stok::with(['pupuk.kategoriPupuk', 'pengguna'])->get();
        $pupuks = Pupuk::all();
        $users = Pengguna::where('role', 'distributor')->where('status', 'approved')->get();

        return Inertia::render('Admin/Stok', [
            'stocks' => $stocks,
            'pupuks' => $pupuks,
            'users' => $users,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
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

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pupuk_id' => 'required|exists:pupuk,id',
            'pengguna_id' => 'required|exists:pengguna,id',
            'jumlah_stok' => 'required|numeric|min:0',
        ]);

        $stock = Stok::findOrFail($id);
        $stock->update([
            'pupuk_id' => $request->pupuk_id,
            'pengguna_id' => $request->pengguna_id,
            'jumlah_stok' => $request->jumlah_stok,
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diupdate');
    }

    public function destroy($id)
    {
        $stock = Stok::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus');
    }

    // For distributor - only their own stock
    public function myStock()
    {
        $userId = auth()->id();
        $stocks = Stok::with(['pupuk.kategoriPupuk'])
            ->where('pengguna_id', $userId)
            ->get();
        
        $pupuks = Pupuk::all();

        return Inertia::render('Admin/Stok', [
            'stocks' => $stocks,
            'pupuks' => $pupuks,
            'users' => [],
            'user' => auth()->user(),
        ]);
    }

    public function updateMyStock(Request $request, $id)
    {
        $request->validate([
            'jumlah_stok' => 'required|numeric|min:0',
        ]);

        $stock = Stok::where('id', $id)
            ->where('pengguna_id', auth()->id())
            ->firstOrFail();

        $stock->update([
            'jumlah_stok' => $request->jumlah_stok,
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diupdate');
    }
}
