<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use App\Models\KategoriPupuk;
use App\Models\Nutrisi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PupukController extends Controller
{
    public function index(Request $request)
    {
        $query = Pupuk::with(['kategori', 'nutrisi']);

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pupuk', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('kategori', function ($q) use ($request) {
                      $q->where('nama_kategori', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $perPage = $request->per_page ?? 10;
        $pupuks = $query->paginate($perPage)->withQueryString();

        $categories = KategoriPupuk::all();
        $nutrisiList = Nutrisi::all();

        return Inertia::render('Admin/Pupuk', [
            'pupuks' => $pupuks,
            'categories' => $categories,
            'nutrisiList' => $nutrisiList,
            'filters' => [
                'search' => $request->search,
                'per_page' => $perPage,
            ],
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

    public function exportPdf(Request $request)
    {
        $query = Pupuk::with(['kategori', 'nutrisi']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pupuk', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('kategori', function ($q) use ($request) {
                      $q->where('nama_kategori', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pupuks = $query->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.pupuk', compact('pupuks'));

        return $pdf->download('data-pupuk.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Pupuk::with(['kategori', 'nutrisi']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pupuk', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('kategori', function ($q) use ($request) {
                      $q->where('nama_kategori', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pupuks = $query->get();

        $excelService = app(\App\Services\ExcelExportService::class);

        $headers = ['No', 'Nama Pupuk', 'Kategori', 'Harga Jual', 'Kandungan Nutrisi', 'Deskripsi'];

        $data = $pupuks->map(function ($pupuk, $index) {
            $nutrisi = $pupuk->nutrisi->map(function ($n) {
                return $n->nama_nutrisi . ' (' . $n->pivot->kandungan_persen . '%)';
            })->implode(', ');

            return [
                $index + 1,
                $pupuk->nama_pupuk,
                $pupuk->kategori->nama_kategori ?? '-',
                'Rp ' . number_format($pupuk->harga_jual, 0, ',', '.'),
                $nutrisi ?: '-',
                $pupuk->deskripsi ?: '-'
            ];
        })->toArray();

        return $excelService->export('Data Pupuk', $headers, $data, 'data-pupuk.xlsx');
    }
}
