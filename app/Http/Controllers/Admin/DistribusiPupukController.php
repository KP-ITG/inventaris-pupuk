<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DistribusiPupuk;
use App\Models\Desa;
use App\Models\Pupuk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Exports\DistribusiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfExportService;

class DistribusiPupukController extends Controller
{
    public function index()
    {
        $distribusi = DistribusiPupuk::with(['pupuk', 'desa', 'pengguna'])
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return Inertia::render('Admin/DistribusiPupuk', [
            'distribusi' => $distribusi,
            'pupuks' => [],
            'desas' => []
        ]);
    }

    public function create()
    {
        $pupuks = Pupuk::with(['stok', 'kategori'])
                       ->whereHas('stok', function($q) {
                           $q->where('jumlah_stok', '>', 0);
                       })
                       ->get();

        $desas = Desa::orderBy('nama_desa')->get();

        return Inertia::render('Admin/DistribusiPupuk/Create', [
            'pupuks' => $pupuks,
            'desas' => $desas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pupuk_id' => 'required|exists:pupuk,id',
            'desa_id' => 'required|exists:desa,id',
            'jumlah_distribusi' => 'required|integer|min:1',
            'tanggal_distribusi' => 'required|date',
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'no_telepon_penerima' => 'nullable|string|max:20'
        ]);

        // Cek stok
        $stok = Stok::where('pupuk_id', $validated['pupuk_id'])->first();
        if (!$stok || $stok->jumlah_stok < $validated['jumlah_distribusi']) {
            return redirect()->back()
                           ->with('error', 'Stok pupuk tidak mencukupi untuk distribusi ini');
        }

        // Generate nomor distribusi
        $validated['nomor_distribusi'] = DistribusiPupuk::generateNomorDistribusi();
        $validated['pengguna_id'] = auth()->id();

        $distribusi = DistribusiPupuk::create($validated);

        // Kurangi stok jika status bukan rencana atau batal
        if (in_array($validated['status_distribusi'], ['dalam_perjalanan', 'selesai'])) {
            $stok->kurangiStok($validated['jumlah_distribusi']);
        }

        return redirect()->route('admin.distribusi-pupuk.index')
                        ->with('success', 'Distribusi pupuk berhasil ditambahkan');
    }

    public function show(DistribusiPupuk $distribusiPupuk)
    {
        $distribusiPupuk->load(['pupuk', 'desa', 'pengguna']);

        return Inertia::render('Admin/DistribusiPupuk/Show', [
            'distribusi' => $distribusiPupuk
        ]);
    }

    public function edit(DistribusiPupuk $distribusiPupuk)
    {
        $pupuks = Pupuk::with(['stok', 'kategori'])
                       ->whereHas('stok', function($q) {
                           $q->where('jumlah_stok', '>', 0);
                       })
                       ->get();

        $desas = Desa::orderBy('nama_desa')->get();

        // Load relasi yang diperlukan
        $distribusiPupuk->load(['pupuk', 'desa']);

        // Siapkan data distribusi dengan format tanggal yang tepat
        $distribusiData = $distribusiPupuk->toArray();

        // Debug - cek format tanggal asli
        \Log::info('Original tanggal_distribusi:', [
            'raw' => $distribusiPupuk->getRawOriginal('tanggal_distribusi'),
            'carbon' => $distribusiPupuk->tanggal_distribusi,
            'formatted' => $distribusiPupuk->tanggal_distribusi ? $distribusiPupuk->tanggal_distribusi->format('Y-m-d') : null
        ]);

        // Force format tanggal untuk input date HTML
        if ($distribusiPupuk->tanggal_distribusi) {
            $distribusiData['tanggal_distribusi'] = $distribusiPupuk->tanggal_distribusi->format('Y-m-d');
        } else {
            $distribusiData['tanggal_distribusi'] = '';
        }

        return Inertia::render('Admin/DistribusiPupuk/Edit', [
            'distribusi' => $distribusiData,
            'pupuks' => $pupuks,
            'desas' => $desas
        ]);
    }    public function update(Request $request, DistribusiPupuk $distribusiPupuk)
    {
        $validated = $request->validate([
            'pupuk_id' => 'required|exists:pupuk,id',
            'desa_id' => 'required|exists:desa,id',
            'jumlah_distribusi' => 'required|integer|min:1',
            'tanggal_distribusi' => 'required|date',
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'no_telepon_penerima' => 'nullable|string|max:20'
        ]);

        // Handle perubahan stok berdasarkan status
        $statusLama = $distribusiPupuk->status_distribusi;
        $statusBaru = $validated['status_distribusi'];
        $jumlahLama = $distribusiPupuk->jumlah_distribusi;
        $jumlahBaru = $validated['jumlah_distribusi'];

        $stok = Stok::where('pupuk_id', $validated['pupuk_id'])->first();

        // Kembalikan stok jika status lama mengurangi stok
        if (in_array($statusLama, ['dalam_perjalanan', 'selesai'])) {
            $stok->tambahStok($jumlahLama);
        }

        // Kurangi stok jika status baru mengurangi stok
        if (in_array($statusBaru, ['dalam_perjalanan', 'selesai'])) {
            if ($stok->jumlah_stok < $jumlahBaru) {
                return redirect()->back()
                               ->with('error', 'Stok pupuk tidak mencukupi untuk distribusi ini');
            }
            $stok->kurangiStok($jumlahBaru);
        }

        $distribusiPupuk->update($validated);

        return redirect()->route('admin.distribusi-pupuk.index')
                        ->with('success', 'Distribusi pupuk berhasil diperbarui');
    }

    public function destroy(DistribusiPupuk $distribusiPupuk)
    {
        // Kembalikan stok jika distribusi dalam status yang mengurangi stok
        if (in_array($distribusiPupuk->status_distribusi, ['dalam_perjalanan', 'selesai'])) {
            $stok = Stok::where('pupuk_id', $distribusiPupuk->pupuk_id)->first();
            if ($stok) {
                $stok->tambahStok($distribusiPupuk->jumlah_distribusi);
            }
        }

        $distribusiPupuk->delete();

        return redirect()->route('admin.distribusi-pupuk.index')
                        ->with('success', 'Distribusi pupuk berhasil dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $distribusiPupuk = DistribusiPupuk::findOrFail($id);

        $validated = $request->validate([
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal'
        ]);

        $statusLama = $distribusiPupuk->status_distribusi;
        $statusBaru = $validated['status_distribusi'];

        // Jika tidak ada perubahan status, tidak perlu proses apapun
        if ($statusLama === $statusBaru) {
            return response()->json(['message' => 'Status tidak berubah']);
        }

        $stok = Stok::where('pupuk_id', $distribusiPupuk->pupuk_id)->first();

        if (!$stok) {
            return response()->json(['error' => 'Stok pupuk tidak ditemukan'], 400);
        }

        // Logic untuk mengelola stok berdasarkan perubahan status
        $statusYangMengurangiStok = ['dalam_perjalanan', 'selesai'];

        // Jika status lama mengurangi stok dan status baru tidak, kembalikan stok
        if (in_array($statusLama, $statusYangMengurangiStok) && !in_array($statusBaru, $statusYangMengurangiStok)) {
            $stok->tambahStok($distribusiPupuk->jumlah_distribusi);
        }
        // Jika status lama tidak mengurangi stok dan status baru mengurangi, kurangi stok
        elseif (!in_array($statusLama, $statusYangMengurangiStok) && in_array($statusBaru, $statusYangMengurangiStok)) {
            if ($stok->jumlah_stok < $distribusiPupuk->jumlah_distribusi) {
                return response()->json(['error' => 'Stok pupuk tidak mencukupi'], 400);
            }
            $stok->kurangiStok($distribusiPupuk->jumlah_distribusi);
        }

        // Update status distribusi
        $distribusiPupuk->update(['status_distribusi' => $statusBaru]);

        return response()->json(['message' => 'Status berhasil diupdate']);
    }

    /**
     * Export data distribusi pupuk to Excel
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        return Excel::download(new DistribusiExport(), 'daftar-distribusi-pupuk.xlsx');
    }

    /**
     * Export data distribusi pupuk to PDF
     * 
     * @param \App\Services\PdfExportService $pdfService
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(PdfExportService $pdfService)
    {
        $export = new DistribusiExport();
        
        return $pdfService->export(
            'Daftar Distribusi Pupuk',
            $export->headings(),
            $export->collection(),
            'daftar-distribusi-pupuk'
        );
    }
}
