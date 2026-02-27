<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\PenggunaanPupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class KelolaPupukController extends Controller
{
    /**
     * Hitung stok bersih (distribusi masuk - penggunaan) untuk suatu desa.
     */
    private function hitungStokDesa(int $desaId): \Illuminate\Support\Collection
    {
        // Total pupuk yang diterima/selesai dari distribusi
        $distribusi = DB::table('distribusi_pupuk_detail')
            ->join('distribusi_pupuk', 'distribusi_pupuk_detail.distribusi_pupuk_id', '=', 'distribusi_pupuk.id')
            ->join('pupuk', 'distribusi_pupuk_detail.pupuk_id', '=', 'pupuk.id')
            ->join('kategori_pupuk', 'pupuk.kategori_id', '=', 'kategori_pupuk.id')
            ->where('distribusi_pupuk.desa_id', $desaId)
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
            ->where('desa_id', $desaId)
            ->select('pupuk_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
            ->groupBy('pupuk_id')
            ->get()
            ->keyBy('pupuk_id');

        return $distribusi->map(function ($item) use ($penggunaan) {
            $digunakan   = (int) ($penggunaan[$item->pupuk_id]->total_digunakan ?? 0);
            $sisaStok    = max(0, (int) $item->total_masuk - $digunakan);

            return [
                'pupuk_id'    => $item->pupuk_id,
                'nama_pupuk'  => $item->nama_pupuk,
                'kategori_id' => $item->kategori_id,
                'kategori'    => $item->kategori,
                'total_masuk' => (int) $item->total_masuk,
                'total_digunakan' => $digunakan,
                'jumlah_stok' => $sisaStok,
                'status_stok' => $sisaStok <= 0 ? 'habis' : ($sisaStok <= 50 ? 'hampir_habis' : 'aman'),
            ];
        })->values();
    }

    public function index()
    {
        $user = auth()->user();

        $stokPupuk = $this->hitungStokDesa($user->desa_id);

        $riwayat = PenggunaanPupuk::with('pupuk')
            ->where('desa_id', $user->desa_id)
            ->orderBy('tanggal_penggunaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($p) => [
                'id'               => $p->id,
                'nama_pupuk'       => $p->pupuk->nama_pupuk ?? '-',
                'jumlah_digunakan' => $p->jumlah_digunakan,
                'keterangan'       => $p->keterangan,
                'tanggal_penggunaan' => $p->tanggal_penggunaan->format('Y-m-d'),
            ]);

        return Inertia::render('KepalaDesa/KelolaPupuk', [
            'stokPupuk' => $stokPupuk,
            'riwayat'   => $riwayat,
            'desa'      => $user->desa,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'pupuk_id'           => 'required|exists:pupuk,id',
            'jumlah_digunakan'   => 'required|integer|min:1',
            'keterangan'         => 'nullable|string|max:500',
            'tanggal_penggunaan' => 'required|date|before_or_equal:today',
        ], [
            'pupuk_id.required'           => 'Jenis pupuk harus dipilih',
            'pupuk_id.exists'             => 'Pupuk tidak valid',
            'jumlah_digunakan.required'   => 'Jumlah harus diisi',
            'jumlah_digunakan.min'        => 'Jumlah minimal 1 kg',
            'tanggal_penggunaan.required' => 'Tanggal penggunaan harus diisi',
            'tanggal_penggunaan.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
        ]);

        // Cek stok mencukupi
        $stokList = $this->hitungStokDesa($user->desa_id);
        $stok = $stokList->firstWhere('pupuk_id', $validated['pupuk_id']);

        if (!$stok || $stok['jumlah_stok'] < $validated['jumlah_digunakan']) {
            $tersedia = $stok['jumlah_stok'] ?? 0;
            return back()->withErrors([
                'jumlah_digunakan' => "Stok tidak mencukupi. Tersedia: {$tersedia} kg, diminta: {$validated['jumlah_digunakan']} kg."
            ])->withInput();
        }

        PenggunaanPupuk::create([
            'desa_id'            => $user->desa_id,
            'kepala_desa_id'     => $user->id,
            'pupuk_id'           => $validated['pupuk_id'],
            'jumlah_digunakan'   => $validated['jumlah_digunakan'],
            'keterangan'         => $validated['keterangan'] ?? null,
            'tanggal_penggunaan' => $validated['tanggal_penggunaan'],
        ]);

        return back()->with('success', 'Penggunaan pupuk berhasil dicatat.');
    }
}
