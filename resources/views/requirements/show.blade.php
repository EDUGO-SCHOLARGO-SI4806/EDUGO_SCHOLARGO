<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $scholarship->name }} - Persyaratan Beasiswa ScholarGO</title>

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
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen">

    <!-- Top Navigation (Mockup Gambar 26) -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md shadow-brand-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 tracking-tight">Scholar<span class="text-brand-600">GO</span></span>
            </div>

            <!-- Search Bar Mock -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" placeholder="Search beasiswa..." class="w-full pl-9 pr-4 py-1.5 rounded-full bg-gray-50 border border-gray-200 text-xs focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex items-center gap-6 text-xs font-semibold text-gray-600">
                <a href="#" class="text-brand-600 hover:text-brand-700">Beranda</a>
                <a href="#" class="hover:text-gray-900">Pendaftaranku</a>
                <a href="#" class="hover:text-gray-900">Notifikasi</a>
                <div class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center font-bold text-gray-600 text-xs">
                    MH
                </div>
            </nav>
        </div>
    </header>

    <!-- Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Top Breadcrumb & Admin Switcher -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('requirements.index') }}" class="hover:text-brand-600 flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Cari Beasiswa
                </a>
                <span>/</span>
                <span class="text-gray-800 font-semibold truncate">{{ $scholarship->name }}</span>
            </div>

            <a href="{{ route('requirements.edit', $scholarship->id) }}" class="text-xs bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200 px-3 py-1.5 rounded-xl font-semibold transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span>Edit Kriteria (Admin Mode)</span>
            </a>
        </div>

        <!-- Hero Card (Mockup Gambar 26) -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex items-start gap-5">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-500 font-medium">{{ $scholarship->organizer }}</p>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $scholarship->name }}</h1>
                <div class="flex flex-wrap items-center gap-3 mt-3 text-xs">
                    <span class="px-3 py-1 bg-brand-50 text-brand-700 font-semibold rounded-full border border-brand-200">
                        Pendanaan Penuh
                    </span>
                    <span class="text-gray-500 font-medium">S1 • Dalam Negeri</span>
                    <span class="text-amber-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Ditutup 17 Oktober 2026
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Body Grid: Tabs & Alur Pendaftaran -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left 2 Cols: Details with Tabs -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm space-y-6">
                <!-- Navigation Tabs (Mockup Gambar 26) -->
                <div class="flex border-b border-gray-200 gap-8 text-sm font-semibold">
                    <button class="pb-3 text-gray-400 hover:text-gray-600">Ringkasan</button>
                    <!-- Tab Persyaratan (Active - PBI-13) -->
                    <button class="pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2">
                        <span>Persyaratan</span>
                        <span class="px-2 py-0.5 bg-brand-100 text-brand-800 rounded-full text-[10px] font-bold">PBI-13</span>
                    </button>
                    <button class="pb-3 text-gray-400 hover:text-gray-600 flex items-center gap-1.5">
                        <span>Dokumen</span>
                        <span class="text-[10px] text-gray-400">(PBI-14)</span>
                    </button>
                </div>

                <!-- Tab Content: Kriteria Kualifikasi Beasiswa (PBI-13) -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kriteria Kualifikasi Akademik</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Pastikan Anda memenuhi persyaratan kualifikasi berikut sebelum mengajukan berkas pendaftaran beasiswa ini.
                        </p>
                    </div>

                    <!-- Kriteria Cards Display -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Min GPA Card -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-50/70 to-emerald-100/40 border border-emerald-200 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-brand-600/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase font-semibold text-emerald-800 tracking-wider">Minimal IPK Kumulatif</p>
                                <p class="text-3xl font-extrabold text-brand-700 mt-1">
                                    {{ number_format($scholarship->requirement->min_gpa ?? 3.00, 2) }}
                                </p>
                                <p class="text-[11px] text-gray-500 mt-1">Skala standar 4.00 resmi dari perguruan tinggi</p>
                            </div>
                        </div>

                        <!-- Min Semester Card -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/70 to-blue-100/40 border border-blue-200 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-600/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase font-semibold text-blue-800 tracking-wider">Minimal Semester Aktif</p>
                                <p class="text-3xl font-extrabold text-blue-700 mt-1">
                                    Semester {{ $scholarship->requirement->min_semester ?? 1 }}
                                </p>
                                <p class="text-[11px] text-gray-500 mt-1">Status mahasiswa aktif pada jenjang D4/S1</p>
                            </div>
                        </div>
                    </div>

                    <!-- Live Screening Simulator (Interactive Verification Box) -->
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                                <h4 class="font-bold text-sm text-gray-800">Simulasi Validasi Kualifikasi Calon Pelamar</h4>
                            </div>
                            <span class="text-[11px] text-gray-400 font-medium">Uji Coba Auto-Screening</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Masukkan IPK Anda:</label>
                                <input type="number" id="test_gpa" step="0.01" min="0" max="4" value="3.50" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm focus:border-brand-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Masukkan Semester Anda:</label>
                                <input type="number" id="test_semester" min="1" max="14" value="4" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm focus:border-brand-500 focus:outline-none">
                            </div>
                        </div>

                        <div id="screening_result" class="p-3.5 rounded-xl border text-xs font-medium flex items-center gap-2.5">
                            <!-- Populated via script -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Alur Pendaftaran (Mockup Gambar 26) -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6 h-fit">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Alur Pendaftaran</h3>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                        <span>Langkah 1 dari 4</span>
                        <span class="font-semibold text-brand-600">25%</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
                        <div class="bg-brand-600 h-2 rounded-full w-1/4"></div>
                    </div>
                </div>

                <!-- Stepper Steps -->
                <div class="space-y-4 text-xs">
                    <!-- Step 1: Active -->
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            1
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">1. Cek Kualifikasi (PBI-13)</p>
                            <p class="text-gray-400 text-[11px]">Validasi IPK dan status semester</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start gap-3 opacity-60">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            2
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">2. Unggah Dokumen (PBI-14)</p>
                            <p class="text-gray-400 text-[11px]">Transkrip, KTP, & Rekomendasi</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start gap-3 opacity-60">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            3
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">3. Tinjau Kembali</p>
                            <p class="text-gray-400 text-[11px]">Periksa seluruh data pendaftaran</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-start gap-3 opacity-60">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            4
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">4. Kirim & Konfirmasi</p>
                            <p class="text-gray-400 text-[11px]">Kirim pendaftaran untuk ditinjau</p>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-gray-100">
                    <button class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition shadow-md shadow-brand-600/30 flex items-center justify-center gap-1.5">
                        <span>Lanjut ke Unggah Berkas</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>

        </div>

    </main>

    <!-- JavaScript Live Screener Simulator -->
    <script>
        const minGpa = {{ $scholarship->requirement->min_gpa ?? 3.00 }};
        const minSemester = {{ $scholarship->requirement->min_semester ?? 1 }};

        const gpaInput = document.getElementById('test_gpa');
        const semInput = document.getElementById('test_semester');
        const resultBox = document.getElementById('screening_result');

        function checkEligibility() {
            const currentGpa = parseFloat(gpaInput.value) || 0;
            const currentSem = parseInt(semInput.value) || 0;

            const gpaOk = currentGpa >= minGpa;
            const semOk = currentSem >= minSemester;

            if (gpaOk && semOk) {
                resultBox.className = "p-3.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-medium flex items-center gap-2.5";
                resultBox.innerHTML = `
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <strong class="font-bold">Lolos Kualifikasi Awal!</strong>
                        <p class="text-[11px] text-emerald-700 mt-0.5">IPK (${currentGpa.toFixed(2)}) dan Semester (${currentSem}) memenuhi kriteria minimal beasiswa ini.</p>
                    </div>
                `;
            } else {
                let reasons = [];
                if (!gpaOk) reasons.push(`IPK minimal adalah ${minGpa.toFixed(2)} (IPK Anda: ${currentGpa.toFixed(2)})`);
                if (!semOk) reasons.push(`Semester minimal adalah ${minSemester} (Semester Anda: ${currentSem})`);

                resultBox.className = "p-3.5 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 text-xs font-medium flex items-center gap-2.5";
                resultBox.innerHTML = `
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div>
                        <strong class="font-bold">Belum Memenuhi Kualifikasi:</strong>
                        <p class="text-[11px] text-amber-700 mt-0.5">${reasons.join(' dan ')}.</p>
                    </div>
                `;
            }
        }

        gpaInput.addEventListener('input', checkEligibility);
        semInput.addEventListener('input', checkEligibility);
        checkEligibility();
    </script>
</body>
</html>
