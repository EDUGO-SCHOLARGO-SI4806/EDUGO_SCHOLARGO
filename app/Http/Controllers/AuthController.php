<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login pengguna
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return $this->redirectBasedOnRole($user)
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau kata sandi yang Anda masukkan salah.']);
    }

    /**
     * Menampilkan halaman pendaftaran akun baru
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran akun baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email|max:150',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:penyelenggara,mahasiswa,admin',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan login.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required'      => 'Pilih peran akun Anda.',
        ]);

        $user = User::create([
            'name'     => trim($validated['name']),
            'email'    => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isOrganizer()) {
            return redirect()
                ->route('organizer.documents.create')
                ->with('success', "Akun penyelenggara berhasil dibuat! Silakan lengkapi profil dan unggah bukti legalitas lembaga Anda.");
        }

        return redirect()
            ->route('organizer.documents.index')
            ->with('success', "Pendaftaran berhasil! Selamat datang, {$user->name}.");
    }

    /**
     * Memproses keluar / logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Helper redirect halaman berdasarkan status profil penyelenggara
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->isOrganizer()) {
            if (!$user->organizerProfile) {
                return redirect()->route('organizer.documents.create');
            }
            return redirect()->route('organizer.documents.index');
        }

        return redirect()->route('organizer.documents.index');
    }
}
