<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\DesaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfExportService;

class DesaController extends Controller
{
    public function index()
    {
        $desa = Desa::withCount('distribusiPupuk')
                   ->orderBy('nama_desa')
                   ->get();

        return Inertia::render('Admin/Desa', [
            'desa' => $desa
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Desa/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'nama_kepala_desa' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,non_aktif'
        ]);

        Desa::create($validated);

        return redirect()->route('admin.desa.index')
                        ->with('success', 'Data desa berhasil ditambahkan');
    }

    public function show(Desa $desa)
    {
        $desa->load(['distribusiPupuk.pupuk', 'distribusiPupuk.pengguna']);

        return Inertia::render('Admin/Desa/Show', [
            'desa' => $desa
        ]);
    }

    public function edit(Desa $desa)
    {
        $desa->loadCount('distribusiPupuk');

        return Inertia::render('Admin/Desa/Edit', [
            'desa' => $desa
        ]);
    }

    public function update(Request $request, Desa $desa)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'nama_kepala_desa' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,non_aktif'
        ]);

        $desa->update($validated);

        return redirect()->route('admin.desa.index')
                        ->with('success', 'Data desa berhasil diperbarui');
    }

    public function destroy(Desa $desa)
    {
        // Cek apakah ada distribusi yang terkait
        if ($desa->distribusiPupuk()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Tidak dapat menghapus desa yang sudah memiliki riwayat distribusi pupuk');
        }

        $desa->delete();

        return redirect()->route('admin.desa.index')
                        ->with('success', 'Data desa berhasil dihapus');
    }

    /**
     * Export data desa to Excel
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        return Excel::download(new DesaExport(), 'daftar-desa.xlsx');
    }

    /**
     * Export data desa to PDF
     * 
     * @param \App\Services\PdfExportService $pdfService
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(PdfExportService $pdfService)
    {
        $export = new DesaExport();
        
        return $pdfService->export(
            'Daftar Desa',
            $export->headings(),
            $export->collection(),
            'daftar-desa'
        );
    }
}
