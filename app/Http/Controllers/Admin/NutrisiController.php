<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nutrisi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NutrisiController extends Controller
{
    public function index()
    {
        $nutrisi = Nutrisi::withCount('pupuk')->orderBy('nama_nutrisi')->get();

        return Inertia::render('Admin/Nutrisi', [
            'nutrisi' => $nutrisi
        ]);
    }    public function store(Request $request)
    {
        $request->validate([
            'nama_nutrisi' => 'required|string|max:255|unique:nutrisi,nama_nutrisi',
            'satuan' => 'required|string|max:50',
            'formula_kimia' => 'nullable|string|max:100',
            'deskripsi_nutrisi' => 'nullable|string|max:500'
        ]);

        Nutrisi::create([
            'nama_nutrisi' => $request->nama_nutrisi,
            'satuan' => $request->satuan,
            'formula_kimia' => $request->formula_kimia,
            'deskripsi_nutrisi' => $request->deskripsi_nutrisi
        ]);

        return redirect()->back()->with('success', 'Nutrisi berhasil ditambahkan');
    }

    public function update(Request $request, Nutrisi $nutrisi)
    {
        $request->validate([
            'nama_nutrisi' => 'required|string|max:255|unique:nutrisi,nama_nutrisi,' . $nutrisi->id,
            'satuan' => 'required|string|max:50',
            'formula_kimia' => 'nullable|string|max:100',
            'deskripsi_nutrisi' => 'nullable|string|max:500'
        ]);

        $nutrisi->update([
            'nama_nutrisi' => $request->nama_nutrisi,
            'satuan' => $request->satuan,
            'formula_kimia' => $request->formula_kimia,
            'deskripsi_nutrisi' => $request->deskripsi_nutrisi
        ]);

        return redirect()->back()->with('success', 'Nutrisi berhasil diperbarui');
    }

    public function destroy(Nutrisi $nutrisi)
    {
        // Check if nutrisi is being used by any pupuk
        if ($nutrisi->pupuk()->count() > 0) {
            return redirect()->back()->with('error', 'Nutrisi tidak dapat dihapus karena masih digunakan oleh pupuk');
        }

        $nutrisi->delete();

        return redirect()->back()->with('success', 'Nutrisi berhasil dihapus');
    }
}
