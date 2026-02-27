<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DistribusiPupuk;
use App\Models\DistribusiPupukDetail;
use App\Models\DistribusiStatusLog;
use App\Models\Desa;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DistribusiPupukController extends Controller
{
    public function index(Request $request)
    {
        $query = DistribusiPupuk::with(['details.pupuk', 'desa', 'pengguna', 'statusLogs.user']);

        // Search functionality
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_distribusi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('details.pupuk', function($q2) use ($request) {
                      $q2->where('nama_pupuk', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('desa', function($q2) use ($request) {
                      $q2->where('nama_desa', 'like', '%' . $request->search . '%')
                         ->orWhere('kecamatan', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by period using tanggal_distribusi
        if ($request->month) {
            $query->whereMonth('tanggal_distribusi', $request->month);
        }
        if ($request->year) {
            $query->whereYear('tanggal_distribusi', $request->year);
        }

        $perPage = $request->per_page ?? 10;
        $distribusi = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/DistribusiPupuk', [
            'distribusi' => $distribusi,
            'pupuks' => [],
            'desas' => [],
            'filters' => [
                'search' => $request->search,
                'per_page' => $perPage,
                'month' => $request->month,
                'year' => $request->year,
            ],
        ]);
    }

    public function create()
    {
        $pupuks = Pupuk::with(['kategori'])
                       ->where('stok_gudang_pusat', '>', 0)
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
            'desa_id' => 'required|exists:desa,id',
            'items' => 'required|array|min:1',
            'items.*.pupuk_id' => 'required|exists:pupuk,id',
            'items.*.jumlah_distribusi' => 'required|integer|min:1',
            'items.*.catatan_item' => 'nullable|string',
            'tanggal_distribusi' => 'required|date',
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'no_telepon_penerima' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();

            // Validasi stok untuk setiap item
            foreach ($validated['items'] as $item) {
                $pupuk = Pupuk::find($item['pupuk_id']);
                if (!$pupuk || $pupuk->stok_gudang_pusat < $item['jumlah_distribusi']) {
                    throw new \Exception("Stok {$pupuk->nama_pupuk} tidak mencukupi (tersedia: {$pupuk->stok_gudang_pusat} kg)");
                }
            }

            // Generate nomor distribusi
            $validated['nomor_distribusi'] = DistribusiPupuk::generateNomorDistribusi();
            $validated['pengguna_id'] = auth()->id();

            // Hapus items dari validated untuk create distribusi header
            $items = $validated['items'];
            unset($validated['items']);

            // Create distribusi header
            $distribusi = DistribusiPupuk::create($validated);

            // Log status awal
            DistribusiStatusLog::create([
                'distribusi_pupuk_id' => $distribusi->id,
                'status_lama' => null,
                'status_baru' => $validated['status_distribusi'],
                'user_id' => auth()->id(),
                'keterangan' => 'Distribusi dibuat'
            ]);

            // Create detail items dan kurangi stok jika perlu
            foreach ($items as $item) {
                DistribusiPupukDetail::create([
                    'distribusi_pupuk_id' => $distribusi->id,
                    'pupuk_id' => $item['pupuk_id'],
                    'jumlah_distribusi' => $item['jumlah_distribusi'],
                    'catatan_item' => $item['catatan_item'] ?? null
                ]);

                // Kurangi stok gudang pusat jika status bukan rencana atau batal
                if (in_array($validated['status_distribusi'], ['dalam_perjalanan', 'selesai'])) {
                    Pupuk::where('id', $item['pupuk_id'])->decrement('stok_gudang_pusat', $item['jumlah_distribusi']);
                }
            }

            DB::commit();

            return redirect()->route('admin.distribusi-pupuk.index')
                            ->with('success', 'Distribusi pupuk berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', $e->getMessage());
        }
    }

    public function show(DistribusiPupuk $distribusiPupuk)
    {
        $distribusiPupuk->load(['details.pupuk', 'desa', 'pengguna']);

        return Inertia::render('Admin/DistribusiPupuk/Show', [
            'distribusi' => $distribusiPupuk
        ]);
    }

    public function edit(DistribusiPupuk $distribusiPupuk)
    {
        $pupuks = Pupuk::with(['kategori'])
                       ->where('stok_gudang_pusat', '>', 0)
                       ->get();

        $desas = Desa::orderBy('nama_desa')->get();

        // Load relasi yang diperlukan
        $distribusiPupuk->load(['details.pupuk', 'desa']);

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
            'desa_id' => 'required|exists:desa,id',
            'items' => 'required|array|min:1',
            'items.*.pupuk_id' => 'required|exists:pupuk,id',
            'items.*.jumlah_distribusi' => 'required|integer|min:1',
            'items.*.catatan_item' => 'nullable|string',
            'tanggal_distribusi' => 'required|date',
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'nullable|string|max:255',
            'jabatan_penerima' => 'nullable|string|max:255',
            'no_telepon_penerima' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();

            $statusLama = $distribusiPupuk->status_distribusi;
            $statusBaru = $validated['status_distribusi'];

            // Kembalikan stok untuk items lama jika status lama mengurangi stok
            if (in_array($statusLama, ['dalam_perjalanan', 'selesai'])) {
                foreach ($distribusiPupuk->details as $detail) {
                    Pupuk::where('id', $detail->pupuk_id)->increment('stok_gudang_pusat', $detail->jumlah_distribusi);
                }
            }

            // Hapus semua detail lama
            $distribusiPupuk->details()->delete();

            // Validasi stok jika status baru mengurangi stok
            if (in_array($statusBaru, ['dalam_perjalanan', 'selesai'])) {
                foreach ($validated['items'] as $item) {
                    $pupuk = Pupuk::find($item['pupuk_id']);
                    if (!$pupuk || $pupuk->stok_gudang_pusat < $item['jumlah_distribusi']) {
                        throw new \Exception("Stok {$pupuk->nama_pupuk} tidak mencukupi (tersedia: {$pupuk->stok_gudang_pusat} kg)");
                    }
                }
            }

            // Buat detail baru dan kurangi stok jika perlu
            foreach ($validated['items'] as $item) {
                DistribusiPupukDetail::create([
                    'distribusi_pupuk_id' => $distribusiPupuk->id,
                    'pupuk_id' => $item['pupuk_id'],
                    'jumlah_distribusi' => $item['jumlah_distribusi'],
                    'catatan_item' => $item['catatan_item'] ?? null
                ]);

                if (in_array($statusBaru, ['dalam_perjalanan', 'selesai'])) {
                    Pupuk::where('id', $item['pupuk_id'])->decrement('stok_gudang_pusat', $item['jumlah_distribusi']);
                }
            }

            // Update distribusi header
            $items = $validated['items'];
            unset($validated['items']);
            $distribusiPupuk->update($validated);

            DB::commit();

            return redirect()->route('admin.distribusi-pupuk.index')
                            ->with('success', 'Distribusi pupuk berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', $e->getMessage());
        }
    }

    public function destroy(DistribusiPupuk $distribusiPupuk)
    {
        try {
            DB::beginTransaction();

            // Kembalikan stok jika distribusi dalam status yang mengurangi stok
            if (in_array($distribusiPupuk->status_distribusi, ['dalam_perjalanan', 'selesai'])) {
                foreach ($distribusiPupuk->details as $detail) {
                    Pupuk::where('id', $detail->pupuk_id)->increment('stok_gudang_pusat', $detail->jumlah_distribusi);
                }
            }

            // Hapus details (cascade delete juga akan menghapus ini, tapi lebih baik explicit)
            $distribusiPupuk->details()->delete();

            // Hapus distribusi
            $distribusiPupuk->delete();

            DB::commit();

            return redirect()->route('admin.distribusi-pupuk.index')
                            ->with('success', 'Distribusi pupuk berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Gagal menghapus distribusi: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $distribusiPupuk = DistribusiPupuk::with('details')->findOrFail($id);

        $validated = $request->validate([
            'status_distribusi' => 'required|in:rencana,dalam_perjalanan,selesai,batal'
        ]);

        $statusLama = $distribusiPupuk->status_distribusi;
        $statusBaru = $validated['status_distribusi'];

        // Jika tidak ada perubahan status, tidak perlu proses apapun
        if ($statusLama === $statusBaru) {
            return redirect()->back()->with('info', 'Status tidak berubah');
        }

        // Validasi: status hanya bisa maju, tidak bisa mundur (untuk keamanan)
        $statusOrder = ['rencana' => 1, 'dalam_perjalanan' => 2, 'selesai' => 3, 'batal' => 0];
        if ($statusBaru !== 'batal' && $statusOrder[$statusBaru] < $statusOrder[$statusLama]) {
            return redirect()->back()->withErrors(['status_distribusi' => 'Status tidak dapat dikembalikan ke tahap sebelumnya']);
        }

        try {
            DB::beginTransaction();

            // Logic untuk mengelola stok berdasarkan perubahan status
            $statusYangMengurangiStok = ['dalam_perjalanan', 'selesai'];

            // Process untuk setiap item di details
            foreach ($distribusiPupuk->details as $detail) {
                $pupuk = Pupuk::find($detail->pupuk_id);

                // Jika status lama mengurangi stok dan status baru tidak, kembalikan stok
                if (in_array($statusLama, $statusYangMengurangiStok) && !in_array($statusBaru, $statusYangMengurangiStok)) {
                    Pupuk::where('id', $detail->pupuk_id)->increment('stok_gudang_pusat', $detail->jumlah_distribusi);
                }
                // Jika status lama tidak mengurangi stok dan status baru mengurangi, validasi & kurangi stok
                elseif (!in_array($statusLama, $statusYangMengurangiStok) && in_array($statusBaru, $statusYangMengurangiStok)) {
                    if (!$pupuk || $pupuk->stok_gudang_pusat < $detail->jumlah_distribusi) {
                        throw new \Exception('Stok ' . ($pupuk->nama_pupuk ?? 'unknown') . ' tidak mencukupi (tersedia: ' . ($pupuk->stok_gudang_pusat ?? 0) . ' kg, dibutuhkan: ' . $detail->jumlah_distribusi . ' kg)');
                    }
                    Pupuk::where('id', $detail->pupuk_id)->decrement('stok_gudang_pusat', $detail->jumlah_distribusi);
                }
            }

            // Update status distribusi
            $distribusiPupuk->update(['status_distribusi' => $statusBaru]);

            // Log perubahan status
            DistribusiStatusLog::create([
                'distribusi_pupuk_id' => $distribusiPupuk->id,
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
                'user_id' => auth()->id(),
                'keterangan' => 'Status diupdate dari ' . $statusLama . ' menjadi ' . $statusBaru
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Status berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['status_distribusi' => $e->getMessage()]);
        }
    }

    public function exportPdf(Request $request)
    {
        $query = DistribusiPupuk::with(['details.pupuk', 'desa', 'pengguna']);

        // Filter by period
        if ($request->month) {
            $query->whereMonth('tanggal_distribusi', $request->month);
        }
        if ($request->year) {
            $query->whereYear('tanggal_distribusi', $request->year);
        }

        $distribusi = $query->orderBy('created_at', 'desc')->get();

        $pdf = \PDF::loadView('pdf.distribusi-pupuk', compact('distribusi'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('data-distribusi-pupuk-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = DistribusiPupuk::with(['details.pupuk', 'desa', 'pengguna']);

        // Filter by period
        if ($request->month) {
            $query->whereMonth('tanggal_distribusi', $request->month);
        }
        if ($request->year) {
            $query->whereYear('tanggal_distribusi', $request->year);
        }

        $distribusi = $query->orderBy('created_at', 'desc')->get();

        $headers = ['No', 'Nomor Distribusi', 'Pupuk', 'Jumlah per Item', 'Total Jumlah', 'Desa Tujuan', 'Tanggal Distribusi', 'Status', 'Penerima'];

        $data = [];
        $no = 1;
        foreach ($distribusi as $item) {
            // Jika ada detail items
            if ($item->details && $item->details->count() > 0) {
                $firstRow = true;
                $itemCount = $item->details->count();
                $totalJumlah = $item->details->sum('jumlah_distribusi');

                foreach ($item->details as $detail) {
                    $data[] = [
                        $firstRow ? $no : '',
                        $firstRow ? $item->nomor_distribusi : '',
                        $detail->pupuk->nama_pupuk ?? '-',
                        $detail->jumlah_distribusi . ' kg',
                        $firstRow ? $totalJumlah . ' kg' : '',
                        $firstRow ? ($item->desa->nama_desa ?? '-') : '',
                        $firstRow ? ($item->tanggal_distribusi ? $item->tanggal_distribusi->format('d/m/Y') : '-') : '',
                        $firstRow ? ucfirst(str_replace('_', ' ', $item->status_distribusi)) : '',
                        $firstRow ? ($item->nama_penerima ?? '-') : ''
                    ];
                    $firstRow = false;
                }
                $no++;
            } else {
                // Fallback untuk data lama tanpa details
                $data[] = [
                    $no++,
                    $item->nomor_distribusi,
                    '-',
                    '-',
                    '0 kg',
                    $item->desa->nama_desa ?? '-',
                    $item->tanggal_distribusi ? $item->tanggal_distribusi->format('d/m/Y') : '-',
                    ucfirst(str_replace('_', ' ', $item->status_distribusi)),
                    $item->nama_penerima ?? '-'
                ];
            }
        }

        $filename = 'data-distribusi-pupuk-' . date('Y-m-d') . '.csv';

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
