<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPupuk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriPupuk::withCount('pupuk')->orderBy('nama_kategori')->get();

        return Inertia::render('Admin/Kategori', [
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pupuk,nama_kategori',
            'deskripsi' => 'nullable|string|max:500'
        ]);

        KategoriPupuk::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, KategoriPupuk $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pupuk,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:500'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(KategoriPupuk $kategori)
    {
        // Check if category is being used by any pupuk
        if ($kategori->pupuk()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh pupuk');
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
