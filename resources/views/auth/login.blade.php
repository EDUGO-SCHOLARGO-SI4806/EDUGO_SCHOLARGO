<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun - ScholarGO</title>

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

    <div class="w-full max-w-md space-y-6">

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
            <h1 class="text-xl font-bold text-gray-900">Portal Login</h1>
            <p class="text-xs text-gray-500">Masuk untuk mengelola bukti legalitas dan verifikasi akun</p>
        </div>

        <!-- Flash Message Success -->
        @if(session('success'))
            <div class="bg-brand-50 border border-brand-200 text-brand-800 rounded-2xl p-4 text-xs flex items-start gap-3 shadow-sm">
                <svg class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <div>
                    <strong class="font-bold">Berhasil!</strong>
                    <p class="mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Summary (if any) -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-xs flex items-start gap-3 shadow-sm">
                <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <strong class="font-bold">Gagal Masuk:</strong>
                    <ul class="list-disc list-inside mt-0.5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Card Form Login -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xl shadow-gray-200/50 space-y-6">

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-800 mb-1">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="email"
                            required
                            autocomplete="email"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-xs text-gray-800 font-medium transition focus:outline-none"
                        >
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-gray-800">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <a href="#" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">Lupa kata sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-xs text-gray-800 font-medium transition focus:outline-none"
                        >
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye_icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-gray-300">
                        <span class="text-gray-600">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition shadow-md shadow-brand-600/30 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    <span>Masuk ke Akun</span>
                </button>
            </form>



            <!-- Footer: Link ke Register -->
            <div class="text-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                <span>Penyelenggara baru?</span>
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-700 underline ml-1">
                    Daftar Akun
                </a>
            </div>

        </div>

        <p class="text-center text-[11px] text-gray-400">
            &copy; 2026 ScholarGO • EduGo Platform Beasiswa Indonesia
        </p>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>
