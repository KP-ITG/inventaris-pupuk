<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Notifications\UserApprovalNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    // List all approved users
    public function index(Request $request)
    {
        $query = Pengguna::with('desa')
            ->where('status', 'approved'); // Only approved users

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy($request->get('sort', 'created_at'), $request->get('direction', 'desc'))
                       ->paginate(10)
                       ->withQueryString();

        return Inertia::render('Admin/UserList', [
            'users' => $users,
            'filters' => $request->only(['role', 'status', 'search', 'sort', 'direction'])
        ]);
    }

    // Approval page for pending users
    public function approvals(Request $request)
    {
        $query = Pengguna::with('desa')
            ->where('status', 'pending'); // Only pending users

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        return Inertia::render('Admin/UserApprovals', [
            'users' => $users,
            'filters' => $request->only(['role', 'search'])
        ]);
    }

    public function validations()
    {
        $pendingUsers = Pengguna::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Users', [
            'users' => $pendingUsers,
            'isValidationPage' => true
        ]);
    }

    public function approve($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->update([
            'status' => 'approved',
            'alasan_penolakan' => null
        ]);

        // Kirim email notifikasi
        try {
            $user->notify(new UserApprovalNotification('approved'));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'User berhasil disetujui dan email notifikasi telah dikirim');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter'
        ]);

        $user = Pengguna::findOrFail($id);

        // Simpan desa_id jika user adalah kepala_desa (untuk dihapus nanti)
        $desaId = ($user->role === 'kepala_desa') ? $user->desa_id : null;

        // Kirim email notifikasi penolakan
        try {
            $user->notify(new UserApprovalNotification('rejected', $request->alasan_penolakan));
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        // Hapus user dari database
        $user->delete();

        // Hapus data desa jika user adalah kepala_desa
        if ($desaId) {
            \App\Models\Desa::where('id', $desaId)->delete();
        }

        return back()->with('success', 'User berhasil ditolak, email notifikasi telah dikirim, dan data telah dihapus dari sistem');
    }

    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
