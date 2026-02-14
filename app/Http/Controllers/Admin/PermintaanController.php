<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanDistribusi;
use App\Models\DistribusiPupuk;
use App\Models\DistribusiPupukDetail;
use App\Models\DistribusiStatusLog;
use App\Notifications\PermintaanApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaan = PermintaanDistribusi::with(['kepalaDesa', 'desa', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Load pupuk names for items
        foreach ($permintaan as $item) {
            $items = $item->items;
            foreach ($items as &$pupukItem) {
                $pupuk = \App\Models\Pupuk::find($pupukItem['pupuk_id']);
                if ($pupuk) {
                    $pupukItem['nama_pupuk'] = $pupuk->nama_pupuk;
                }
            }
            $item->items = $items;
        }

        return Inertia::render('Admin/Permintaan', [
            'permintaan' => $permintaan,
        ]);
    }

    public function approve($id)
    {
        $permintaan = PermintaanDistribusi::with('kepalaDesa')->findOrFail($id);

        if ($permintaan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses sebelumnya');
        }

        DB::beginTransaction();
        try {
            // Update status permintaan
            $permintaan->update([
                'status' => 'approved',
                'alasan_penolakan' => null,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Buat entry distribusi pupuk
            $distribusi = DistribusiPupuk::create([
                'permintaan_distribusi_id' => $permintaan->id,
                'nomor_distribusi' => DistribusiPupuk::generateNomorDistribusi(),
                'desa_id' => $permintaan->desa_id,
                'pengguna_id' => auth()->id(),
                'tanggal_distribusi' => now()->toDateString(),
                'status_distribusi' => 'rencana',
                'catatan' => 'Distribusi dari permintaan: ' . $permintaan->keterangan,
            ]);

            // Buat detail distribusi untuk setiap item
            foreach ($permintaan->items as $item) {
                DistribusiPupukDetail::create([
                    'distribusi_pupuk_id' => $distribusi->id,
                    'pupuk_id' => $item['pupuk_id'],
                    'jumlah_distribusi' => $item['jumlah_diminta'],
                ]);
            }

            // Log status awal
            DistribusiStatusLog::create([
                'distribusi_pupuk_id' => $distribusi->id,
                'status_lama' => null,
                'status_baru' => 'rencana',
                'user_id' => auth()->id(),
                'keterangan' => 'Distribusi direncanakan dari permintaan yang disetujui',
            ]);

            DB::commit();

            // Send email notification
            try {
                $permintaan->kepalaDesa->notify(new PermintaanApprovalNotification('approved', null, $permintaan));
            } catch (\Exception $e) {
                Log::error('Failed to send approval email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Permintaan berhasil disetujui dan distribusi telah direncanakan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve permintaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses permintaan');
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan harus diisi',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter',
        ]);

        $permintaan = PermintaanDistribusi::with('kepalaDesa')->findOrFail($id);

        if ($permintaan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses sebelumnya');
        }

        $permintaan->update([
            'status' => 'rejected',
            'alasan_penolakan' => $validated['alasan_penolakan'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Send email notification with rejection reason
        try {
            $permintaan->kepalaDesa->notify(new PermintaanApprovalNotification('rejected', $validated['alasan_penolakan'], $permintaan));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
    }
}
