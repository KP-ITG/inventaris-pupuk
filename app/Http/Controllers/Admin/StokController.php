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
    public function index(Request $request)
    {
        // Sistem stok pusat - hanya admin yang bisa akses
        $query = Stok::with(['pupuk.kategori']);

        // Search functionality
        if ($request->search) {
            $query->whereHas('pupuk', function($q) use ($request) {
                $q->where('nama_pupuk', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_pupuk', 'like', '%' . $request->search . '%');
            })->orWhere('lokasi_gudang', 'like', '%' . $request->search . '%');
        }

        // Period filter
        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $perPage = $request->per_page ?? 10;
        $stocks = $query->orderBy('updated_at', 'desc')->paginate($perPage)->withQueryString();

        // Ambil semua pupuk untuk keperluan edit
        $allPupuks = Pupuk::with('kategori')
                          ->orderBy('nama_pupuk')
                          ->get();

        return Inertia::render('Admin/Stok', [
            'stocks' => $stocks,
            'pupuks' => $allPupuks,
            'filters' => [
                'search' => $request->search,
                'per_page' => $perPage,
                'month' => $request->month,
                'year' => $request->year
            ]
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

    public function exportPdf(Request $request)
    {
        $query = Stok::with(['pupuk.kategori']);

        // Period filter
        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $stocks = $query->orderBy('updated_at', 'desc')->get();

        $pdf = \PDF::loadView('pdf.stok', compact('stocks'));

        return $pdf->download('data-stok-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Stok::with(['pupuk.kategori']);

        // Period filter
        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $stocks = $query->orderBy('updated_at', 'desc')->get();

        $headers = ['No', 'Nama Pupuk', 'Kategori', 'Jumlah Stok', 'Stok Min', 'Stok Max', 'Lokasi Gudang', 'Status'];

        $data = [];
        foreach ($stocks as $index => $stock) {
            $data[] = [
                $index + 1,
                $stock->pupuk->nama_pupuk ?? '-',
                $stock->pupuk->kategori->nama_kategori ?? '-',
                $stock->jumlah_stok . ' kg',
                $stock->stok_minimum . ' kg',
                $stock->stok_maksimum . ' kg',
                $stock->lokasi_gudang ?? '-',
                ucfirst(str_replace('_', ' ', $stock->status_stok))
            ];
        }

        $filename = 'data-stok-' . date('Y-m-d') . '.csv';

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $headers, ';');
            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }
}
