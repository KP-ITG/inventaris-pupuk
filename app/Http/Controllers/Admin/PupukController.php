<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use App\Models\KategoriPupuk;
use App\Models\Nutrisi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\PupukExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfExportService;

class PupukController extends Controller
{
    public function index()
    {
        $pupuks = Pupuk::with(['kategori', 'nutrisi'])->get();
        $categories = KategoriPupuk::all();
        $nutrisiList = Nutrisi::all();

        return Inertia::render('Admin/Pupuk', [
            'pupuks' => $pupuks,
            'categories' => $categories,
            'nutrisiList' => $nutrisiList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pupuk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pupuk,id',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'nutrisi' => 'nullable|array',
            'nutrisi.*.nutrisi_id' => 'required|exists:nutrisi,id',
            'nutrisi.*.kandungan_persen' => 'required|numeric|min:0|max:100',
        ]);

        $pupuk = Pupuk::create([
            'nama_pupuk' => $request->nama_pupuk,
            'kategori_id' => $request->kategori_id,
            'harga_jual' => $request->harga_jual,
            'deskripsi' => $request->deskripsi,
        ]);

        // Attach nutrisi with kandungan_persen
        if ($request->nutrisi) {
            foreach ($request->nutrisi as $nutrisiData) {
                $pupuk->nutrisi()->attach($nutrisiData['nutrisi_id'], [
                    'kandungan_persen' => $nutrisiData['kandungan_persen']
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pupuk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pupuk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pupuk,id',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'nutrisi' => 'nullable|array',
            'nutrisi.*.nutrisi_id' => 'required|exists:nutrisi,id',
            'nutrisi.*.kandungan_persen' => 'required|numeric|min:0|max:100',
        ]);

        $pupuk = Pupuk::findOrFail($id);
        $pupuk->update([
            'nama_pupuk' => $request->nama_pupuk,
            'kategori_id' => $request->kategori_id,
            'harga_jual' => $request->harga_jual,
            'deskripsi' => $request->deskripsi,
        ]);

        // Sync nutrisi relationships
        $pupuk->nutrisi()->detach();
        if ($request->nutrisi) {
            foreach ($request->nutrisi as $nutrisiData) {
                $pupuk->nutrisi()->attach($nutrisiData['nutrisi_id'], [
                    'kandungan_persen' => $nutrisiData['kandungan_persen']
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pupuk berhasil diupdate');
    }

    public function destroy($id)
    {
        $pupuk = Pupuk::findOrFail($id);

        // Check if pupuk is used in stock
        if ($pupuk->stok) {
            return back()->withErrors(['error' => 'Pupuk tidak dapat dihapus karena masih ada stok tersedia']);
        }

        // Check if pupuk is used in distributions
        if ($pupuk->distribusiPupuk()->count() > 0) {
            return back()->withErrors(['error' => 'Pupuk tidak dapat dihapus karena sudah pernah didistribusikan']);
        }

        $pupuk->delete();

        // Return proper Inertia response
        return redirect()->route('admin.pupuk.index')->with('success', 'Pupuk berhasil dihapus');
    }

    /**
     * Export data pupuk to Excel
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        return Excel::download(new PupukExport(), 'daftar-pupuk.xlsx');
    }

    /**
     * Export data pupuk to PDF
     * 
     * @param \App\Services\PdfExportService $pdfService
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(PdfExportService $pdfService)
    {
        $export = new PupukExport();
        
        return $pdfService->export(
            'Daftar Pupuk',
            $export->headings(),
            $export->collection(),
            'daftar-pupuk'
        );
    }
}
