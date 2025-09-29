<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::query();

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

        return Inertia::render('Admin/Users', [
            'users' => $users->items(),
            'filters' => $request->only(['role', 'status', 'search', 'sort', 'direction'])
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
        $user->update(['status' => 'approved']);

        return back()->with('success', 'User berhasil disetujui');
    }

    public function reject($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->update(['status' => 'rejected']);

        return back()->with('success', 'User berhasil ditolak');
    }

    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
