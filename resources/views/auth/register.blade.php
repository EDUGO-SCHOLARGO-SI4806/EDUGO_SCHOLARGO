<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penyelenggara Beasiswa - ScholarGO</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-emerald-50/40 text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg space-y-6">

        <!-- Logo & Heading -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-3 justify-center mb-1">
                <div class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center text-white shadow-lg shadow-brand-600/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold text-gray-900 tracking-tight">Scholar<span class="text-brand-600">GO</span></span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Daftar Akun ScholarGO</h1>
            <p class="text-xs text-gray-500">Pilih peran Anda dan isi data diri untuk membuat akun baru.</p>
        </div>

        <!-- Info Alur berdasarkan role -->
        <div id="info-penyelenggara" class="bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl p-4 text-xs flex items-start gap-3 shadow-sm">
            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <strong class="font-bold">Alur Verifikasi Akun Penyelenggara:</strong>
                <p class="mt-0.5 leading-relaxed">
                    Setelah mengisi formulir ini, Anda akan langsung diarahkan ke halaman <strong>Unggah Dokumen Legalitas (Akta, NIB/NPWP, Surat Kuasa)</strong> untuk diverifikasi oleh Admin.
                </p>
            </div>
        </div>
        <div id="info-mahasiswa" class="hidden bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-xs flex items-start gap-3 shadow-sm">
            <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <strong class="font-bold">Akun Mahasiswa:</strong>
                <p class="mt-0.5 leading-relaxed">
                    Akun mahasiswa dapat digunakan untuk mencari dan mendaftar program beasiswa yang tersedia di ScholarGO.
                </p>
            </div>
        </div>

        <!-- Error Summary (if any) -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-xs flex items-start gap-3 shadow-sm">
                <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <strong class="font-bold">Mohon periksa data Anda:</strong>
                    <ul class="list-disc list-inside mt-0.5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Card Form Register -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xl shadow-gray-200/50 space-y-6">

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-xs font-semibold text-gray-800 mb-2">
                        Daftar Sebagai <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Mahasiswa -->
                        <label id="label-mahasiswa" class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-brand-400 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                            <input type="radio" name="role" value="mahasiswa" class="accent-brand-600" {{ old('role', 'mahasiswa') == 'mahasiswa' ? 'checked' : '' }} onchange="toggleRoleInfo(this.value)">
                            <div>
                                <p class="text-xs font-bold text-gray-800">🎓 Mahasiswa</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Cari & daftar beasiswa</p>
                            </div>
                        </label>
                        <!-- Penyelenggara -->
                        <label id="label-penyelenggara" class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-brand-400 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                            <input type="radio" name="role" value="penyelenggara" class="accent-brand-600" {{ old('role') == 'penyelenggara' ? 'checked' : '' }} onchange="toggleRoleInfo(this.value)">
                            <div>
                                <p class="text-xs font-bold text-gray-800">🏛️ Penyelenggara</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Publikasikan beasiswa</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    function toggleRoleInfo(role) {
                        document.getElementById('info-penyelenggara').classList.toggle('hidden', role !== 'penyelenggara');
                        document.getElementById('info-mahasiswa').classList.toggle('hidden', role !== 'mahasiswa');
                    }
                    // Set default on load
                    document.addEventListener('DOMContentLoaded', function() {
                        const checked = document.querySelector('input[name="role"]:checked');
                        if (checked) toggleRoleInfo(checked.value);
                    });
                </script>

                <!-- Input Nama Lengkap Perwakilan -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-800 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 font-medium focus:border-brand-500 focus:outline-none transition"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Nama sesuai KTP / identitas resmi Anda.</p>
                </div>

                <!-- Input Email Lembaga -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-800 mb-1">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email@example.com"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 font-medium focus:border-brand-500 focus:outline-none transition"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Email aktif yang dapat dihubungi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-800 mb-1">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 font-medium focus:border-brand-500 focus:outline-none transition"
                        >
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-800 mb-1">
                            Ulangi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ketik ulang kata sandi"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 font-medium focus:border-brand-500 focus:outline-none transition"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition shadow-md shadow-brand-600/30 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <!-- Footer: Link ke Login -->
            <div class="text-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 underline ml-1">
                    Masuk di sini
                </a>
            </div>

        </div>

    </div>
</body>
</html>
