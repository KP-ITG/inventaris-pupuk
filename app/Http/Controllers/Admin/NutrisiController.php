<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nutrisi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;
use App\Exports\NutrisiExport;
use Maatwebsite\Excel\Facades\Excel;

class NutrisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nutrisi::withCount('pupuk')->orderBy('nama_nutrisi');

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_nutrisi', 'like', '%' . $request->search . '%')
                  ->orWhere('formula_kimia', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_nutrisi', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->per_page ?? 10;
        $nutrisi = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Nutrisi', [
            'nutrisi' => $nutrisi,
            'filters' => [
                'search' => $request->search,
                'per_page' => $perPage,
            ],
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

    public function exportPdf(Request $request)
    {
        $query = Nutrisi::withCount('pupuk')->orderBy('nama_nutrisi');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_nutrisi', 'like', '%' . $request->search . '%')
                  ->orWhere('formula_kimia', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_nutrisi', 'like', '%' . $request->search . '%');
            });
        }

        $nutrisi = $query->get();

        $pdf = PDF::loadView('pdf.nutrisi', compact('nutrisi'));

        return $pdf->download('data-nutrisi-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Nutrisi::withCount('pupuk')->orderBy('nama_nutrisi');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_nutrisi', 'like', '%' . $request->search . '%')
                  ->orWhere('formula_kimia', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_nutrisi', 'like', '%' . $request->search . '%');
            });
        }

        $nutrisi = $query->get();

        $headers = ['No', 'Nama Nutrisi', 'Formula Kimia', 'Satuan', 'Jumlah Pupuk', 'Deskripsi'];

        $data = [];
        foreach ($nutrisi as $index => $item) {
            $data[] = [
                $index + 1,
                $item->nama_nutrisi,
                $item->formula_kimia ?: '-',
                $item->satuan,
                $item->pupuk_count . ' pupuk',
                $item->deskripsi_nutrisi ?: '-'
            ];
        }

        $filename = 'data-nutrisi-' . date('Y-m-d') . '.csv';

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
