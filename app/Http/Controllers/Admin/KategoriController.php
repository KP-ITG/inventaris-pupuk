<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPupuk;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        $query = KategoriPupuk::withCount('pupuk');

        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $kategori = $query->orderBy('nama_kategori')
                         ->paginate($perPage)
                         ->withQueryString();

        return Inertia::render('Admin/Kategori', [
            'kategori' => $kategori,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage
            ]
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

    public function exportPdf(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'nama_kategori');
        $order = $request->get('order', 'asc');

        $query = KategoriPupuk::withCount('pupuk');

        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $kategori = $query->orderBy($sort, $order)->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.kategori-pdf', compact('kategori'));

        return $pdf->download('kategori-pupuk.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'nama_kategori');
        $order = $request->get('order', 'asc');

        $query = KategoriPupuk::withCount('pupuk');

        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $kategori = $query->orderBy($sort, $order)->get();

        $headers = ['No', 'Nama Kategori', 'Deskripsi', 'Jumlah Pupuk', 'Tanggal Dibuat'];

        $data = [];
        foreach ($kategori as $index => $item) {
            $data[] = [
                $index + 1,
                $item->nama_kategori,
                $item->deskripsi ?: '-',
                $item->pupuk_count . ' pupuk',
                $item->created_at->format('d/m/Y')
            ];
        }

        $filename = 'kategori-pupuk-' . date('Y-m-d') . '.xlsx';

        return ExcelExportService::export($data, $headers, $filename, 'Data Kategori Pupuk');
    }
}
