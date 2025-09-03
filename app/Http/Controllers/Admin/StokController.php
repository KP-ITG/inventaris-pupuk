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
        // Sistem stok pusat - hanya admin yang bisa akses
        $stocks = Stok::with(['pupuk.kategori'])
                      ->orderBy('updated_at', 'desc')
                      ->get();

        // Ambil semua pupuk untuk keperluan edit
        $allPupuks = Pupuk::with('kategori')
                          ->orderBy('nama_pupuk')
                          ->get();

        return Inertia::render('Admin/Stok', [
            'stocks' => $stocks,
            'pupuks' => $allPupuks,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pupuk_id' => 'required|exists:pupuk,id',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'stok_maksimum' => 'nullable|numeric|min:0',
            'lokasi_gudang' => 'nullable|string|max:255',
        ]);

        // Cek apakah pupuk sudah ada di stok
        $existingStock = Stok::where('pupuk_id', $request->pupuk_id)->first();

        if ($existingStock) {
            return redirect()->back()->with('error', 'Stok untuk pupuk ini sudah ada. Gunakan fitur edit untuk mengubah jumlah.');
        }

        $stok = Stok::create([
            'pupuk_id' => $request->pupuk_id,
            'jumlah_stok' => $request->jumlah_stok,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'stok_maksimum' => $request->stok_maksimum ?? 0,
            'lokasi_gudang' => $request->lokasi_gudang,
        ]);

        $stok->updateStatusStok();

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $stock = Stok::findOrFail($id);

        $request->validate([
            'pupuk_id' => 'required|exists:pupuk,id',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'stok_maksimum' => 'nullable|numeric|min:0',
            'lokasi_gudang' => 'nullable|string|max:255',
        ]);

        $stock->update([
            'pupuk_id' => $request->pupuk_id,
            'jumlah_stok' => $request->jumlah_stok,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'stok_maksimum' => $request->stok_maksimum ?? 0,
            'lokasi_gudang' => $request->lokasi_gudang,
        ]);

        $stock->updateStatusStok();

        return redirect()->back()->with('success', 'Stok berhasil diperbarui');
    }

    public function destroy($id)
    {
        $stock = Stok::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus');
    }
}
