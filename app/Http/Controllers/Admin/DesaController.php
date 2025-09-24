<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Desa::withCount('distribusiPupuk')->orderBy('nama_desa');

        // Search functionality
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_desa', 'like', '%' . $request->search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $request->search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->per_page ?? 10;
        $desa = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Desa', [
            'desa' => $desa,
            'filters' => [
                'search' => $request->search,
                'per_page' => $perPage,
            ],
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

    public function exportPdf(Request $request)
    {
        $query = Desa::withCount('distribusiPupuk')->orderBy('nama_desa');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_desa', 'like', '%' . $request->search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $request->search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $request->search . '%');
            });
        }

        $desa = $query->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.desa', compact('desa'));

        return $pdf->download('data-desa.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Desa::withCount('distribusiPupuk')->orderBy('nama_desa');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_desa', 'like', '%' . $request->search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $request->search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $request->search . '%');
            });
        }

        $desa = $query->get();

        $excelService = app(\App\Services\ExcelExportService::class);

        $headers = ['No', 'Nama Desa', 'Kecamatan', 'Kabupaten', 'Jumlah Distribusi', 'Status'];

        $data = $desa->map(function ($item, $index) {
            return [
                $index + 1,
                $item->nama_desa,
                $item->kecamatan,
                $item->kabupaten,
                $item->distribusi_pupuk_count . ' distribusi',
                ucfirst($item->status)
            ];
        })->toArray();

        return $excelService->export('Data Desa', $headers, $data, 'data-desa.xlsx');
    }
}
