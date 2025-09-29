<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Display the registration view.
     */
    public function register(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role ?? 'distributor',
            'status' => 'pending', // Default status pending untuk approval
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        // Tidak langsung login, tunggu approval
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan dari administrator.');
    }

    /**
     * Display the login view.
     */
    public function login(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => false,
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $request->password])) {
            $user = Auth::user();

            // Check if user is approved
            if ($user->status !== 'approved') {
                Auth::logout();
                $message = match($user->status) {
                    'pending' => 'Akun Anda sedang menunggu persetujuan dari administrator.',
                    'rejected' => 'Akun Anda telah ditolak oleh administrator.',
                    default => 'Status akun Anda tidak valid.'
                };

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
