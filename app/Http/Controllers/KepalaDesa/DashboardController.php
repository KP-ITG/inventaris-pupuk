<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use App\Models\PermintaanDistribusi;
use App\Models\DistribusiPupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil stok di desa (dari distribusi yang sudah diterima/selesai)
        // Bukan dari stok pusat karena itu untuk admin
        // Total masuk dari distribusi
        $distribusiMasuk = DB::table('distribusi_pupuk_detail')
            ->join('distribusi_pupuk', 'distribusi_pupuk_detail.distribusi_pupuk_id', '=', 'distribusi_pupuk.id')
            ->join('pupuk', 'distribusi_pupuk_detail.pupuk_id', '=', 'pupuk.id')
            ->join('kategori_pupuk', 'pupuk.kategori_id', '=', 'kategori_pupuk.id')
            ->where('distribusi_pupuk.desa_id', $user->desa_id)
            ->whereIn('distribusi_pupuk.status_distribusi', ['diterima', 'selesai'])
            ->select(
                'pupuk.id as pupuk_id',
                'pupuk.nama_pupuk',
                'kategori_pupuk.id as kategori_id',
                'kategori_pupuk.nama_kategori as kategori',
                DB::raw('SUM(distribusi_pupuk_detail.jumlah_distribusi) as total_masuk')
            )
            ->groupBy('pupuk.id', 'pupuk.nama_pupuk', 'kategori_pupuk.id', 'kategori_pupuk.nama_kategori')
            ->get()
            ->keyBy('pupuk_id');

        // Total penggunaan per pupuk
        $penggunaan = DB::table('penggunaan_pupuk')
            ->where('desa_id', $user->desa_id)
            ->select('pupuk_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
            ->groupBy('pupuk_id')
            ->get()
            ->keyBy('pupuk_id');

        $stokPupuk = $distribusiMasuk->map(function ($stok) use ($penggunaan, $user) {
            $digunakan = (int) ($penggunaan[$stok->pupuk_id]->total_digunakan ?? 0);
            $sisaStok  = max(0, (int) $stok->total_masuk - $digunakan);
            $status_stok = $sisaStok <= 0 ? 'habis' : ($sisaStok <= 50 ? 'hampir_habis' : 'aman');

            return [
                'id' => $stok->pupuk_id,
                'pupuk_id' => $stok->pupuk_id,
                'kategori_id' => $stok->kategori_id,
                'nama_pupuk' => $stok->nama_pupuk,
                'kategori' => $stok->kategori,
                'jumlah_stok' => $sisaStok,
                'status_stok' => $status_stok,
                'lokasi_gudang' => 'Gudang Desa ' . ($user->desa->nama_desa ?? ''),
            ];
        })->values();

        // Ambil distribusi pupuk untuk desa ini dengan status log
        $distribusi = DistribusiPupuk::with(['details.pupuk', 'statusLogs.user', 'permintaanDistribusi'])
            ->where('desa_id', $user->desa_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($dist) {
                return [
                    'id' => $dist->id,
                    'nomor_distribusi' => $dist->nomor_distribusi,
                    'tanggal_distribusi' => $dist->tanggal_distribusi,
                    'tanggal_realisasi' => $dist->tanggal_realisasi,
                    'status_distribusi' => $dist->status_distribusi,
                    'catatan' => $dist->catatan,
                    'items' => $dist->details->map(function ($detail) {
                        return [
                            'nama_pupuk' => $detail->pupuk->nama_pupuk,
                            'jumlah' => $detail->jumlah_distribusi,
                        ];
                    }),
                    'status_logs' => $dist->statusLogs->map(function ($log) {
                        return [
                            'status_lama' => $log->status_lama,
                            'status_baru' => $log->status_baru,
                            'keterangan' => $log->keterangan,
                            'user_nama' => $log->user->nama ?? 'System',
                            'created_at' => $log->created_at,
                        ];
                    }),
                ];
            });

        return Inertia::render('KepalaDesa/Dashboard', [
            'stokPupuk' => $stokPupuk,
            'distribusi' => $distribusi,
            'desa' => $user->desa,
        ]);
    }

    public function historiPermintaan()
    {
        $user = auth()->user();

        // Ambil history permintaan dari kepala desa yang login dengan detail pupuk
        $permintaan = PermintaanDistribusi::with(['desa', 'approver'])
            ->where('kepala_desa_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $items = $item->items;
                foreach ($items as &$pupukItem) {
                    $pupuk = Pupuk::find($pupukItem['pupuk_id']);
                    if ($pupuk) {
                        $pupukItem['nama_pupuk'] = $pupuk->nama_pupuk;
                    }
                }
                $item->items = $items;
                return $item;
            });

        return Inertia::render('KepalaDesa/HistoriPermintaan', [
            'permintaan' => $permintaan,
            'desa' => $user->desa,
        ]);
    }

    public function ajukanPermintaan()
    {
        $user = auth()->user();

        // Ambil daftar pupuk yang tersedia di gudang pusat (stok_gudang_pusat > 0)
        $stokPupuk = Pupuk::with(['kategori'])
            ->where('stok_gudang_pusat', '>', 0)
            ->get()
            ->map(function ($pupuk) {
                return [
                    'id'         => $pupuk->id,
                    'pupuk_id'   => $pupuk->id,
                    'kategori_id'=> $pupuk->kategori_id,
                    'nama_pupuk' => $pupuk->nama_pupuk,
                    'kategori'   => $pupuk->kategori->nama_kategori ?? '-',
                    'jumlah'     => $pupuk->stok_gudang_pusat,
                    'satuan'     => 'kg',
                ];
            });

        return Inertia::render('KepalaDesa/AjukanPermintaan', [
            'stokPupuk' => $stokPupuk,
            'desa' => $user->desa,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.pupuk_id' => 'required|exists:pupuk,id',
            'items.*.kategori_id' => 'required|exists:kategori_pupuk,id',
            'items.*.jumlah_diminta' => 'required|numeric|min:1',
            'keterangan' => 'required|string|min:20',
        ], [
            'items.required' => 'Pilih minimal 1 jenis pupuk',
            'items.min' => 'Pilih minimal 1 jenis pupuk',
            'items.*.pupuk_id.required' => 'Pupuk harus dipilih',
            'items.*.pupuk_id.exists' => 'Pupuk tidak valid',
            'items.*.kategori_id.required' => 'Kategori harus dipilih',
            'items.*.kategori_id.exists' => 'Kategori tidak valid',
            'items.*.jumlah_diminta.required' => 'Jumlah harus diisi',
            'items.*.jumlah_diminta.numeric' => 'Jumlah harus berupa angka',
            'items.*.jumlah_diminta.min' => 'Jumlah minimal 1',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.min' => 'Keterangan minimal 20 karakter',
        ]);

        PermintaanDistribusi::create([
            'kepala_desa_id' => $user->id,
            'desa_id' => $user->desa_id,
            'items' => $validated['items'],
            'keterangan' => $validated['keterangan'],
            'status' => 'pending',
        ]);

        return redirect()->route('kepala-desa.dashboard')->with('success', 'Permintaan distribusi berhasil diajukan');
    }
}
