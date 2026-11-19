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
            // Peran admin hanya boleh diberikan secara internal, tidak lewat registrasi publik.
            'role'     => 'required|in:penyelenggara,mahasiswa',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan login.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required'      => 'Pilih peran akun Anda.',
            'role.in'             => 'Peran akun yang dipilih tidak valid.',
        ]);

        $user = User::create([
            'name'     => trim($validated['name']),
            'email'    => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user)
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
     * Helper redirect halaman berdasarkan role pengguna
     */
    protected function redirectBasedOnRole($user)
    {
        // 1. Role Mahasiswa -> ke Katalog Beasiswa (Beranda Mahasiswa)
        if ($user->role === User::ROLE_STUDENT) {
            return redirect()->route('beasiswa.index');
        }

        // 2. Role Penyelenggara -> ke Katalog Beasiswa (fitur PBI 10-15)
        if ($user->isOrganizer()) {
            return redirect()->route('admin.scholarships.index');
        }

        // 3. Role Admin -> ke Manajemen & Verifikasi Akun Penyelenggara (PBI 4-6)
        if ($user->isAdmin()) {
            return redirect()->route('admin.accounts.index');
        }

        // Default fallback
        return redirect()->route('profile.show');
    }
}
