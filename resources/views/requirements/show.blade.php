<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $scholarship->name }} - Persyaratan & Dokumen Wajib ScholarGO</title>

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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('requirements.index') }}" class="hover:text-brand-600 flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Cari Beasiswa
                </a>
                <span>/</span>
                <span class="text-gray-800 font-semibold truncate">{{ $scholarship->name }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('requirements.edit', $scholarship->id) }}" class="text-xs bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span>Atur Kriteria (PBI-13)</span>
                </a>
                <a href="{{ route('requirements.documents', $scholarship->id) }}" class="text-xs bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200 px-3 py-1.5 rounded-xl font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Atur Dokumen Wajib (PBI-14)</span>
                </a>
            </div>
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

            <!-- Left 2 Cols: Details with Interactive Tabs (PBI-13 & PBI-14) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm space-y-6">
                <!-- Navigation Tabs (Mockup Gambar 26) -->
                <div class="flex border-b border-gray-200 gap-6 sm:gap-8 text-sm font-semibold overflow-x-auto">
                    <button type="button" onclick="switchTab('persyaratan')" class="pb-3 text-gray-400 hover:text-gray-600 whitespace-nowrap">
                        Ringkasan
                    </button>

                    <!-- Tab Persyaratan (PBI-13) -->
                    <button
                        type="button"
                        id="tab-btn-persyaratan"
                        onclick="switchTab('persyaratan')"
                        class="pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2 whitespace-nowrap transition"
                    >
                        <span>Persyaratan</span>
                        <span class="px-2 py-0.5 bg-brand-100 text-brand-800 rounded-full text-[10px] font-bold">PBI-13</span>
                    </button>

                    <!-- Tab Dokumen (PBI-14) -->
                    <button
                        type="button"
                        id="tab-btn-dokumen"
                        onclick="switchTab('dokumen')"
                        class="pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition"
                    >
                        <span>Dokumen</span>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold">
                            PBI-14 ({{ $scholarship->requiredDocuments->count() }})
                        </span>
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- TAB 1 CONTENT: Kriteria Kualifikasi Beasiswa (PBI-13)      -->
                <!-- ========================================================= -->
                <div id="panel-persyaratan" class="space-y-6">
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

                <!-- ========================================================= -->
                <!-- TAB 2 CONTENT: Daftar Dokumen Wajib Beasiswa (PBI-14)      -->
                <!-- ========================================================= -->
                <div id="panel-dokumen" class="space-y-6 hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daftar Dokumen Persyaratan Beasiswa</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Siapkan seluruh berkas wajib di bawah ini sebelum melanjutkan ke tahap pengunggahan dokumen pendaftaran.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $scholarship->requiredDocuments->where('is_mandatory', true)->count() }} Wajib
                            </span>
                            @if($scholarship->requiredDocuments->where('is_mandatory', false)->count() > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $scholarship->requiredDocuments->where('is_mandatory', false)->count() }} Opsional
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Checklist Persiapan Dokumen Mahasiswa -->
                    <div class="space-y-3">
                        @forelse($scholarship->requiredDocuments as $index => $doc)
                            <label class="p-4 rounded-2xl border {{ $doc->is_mandatory ? 'border-gray-200 bg-white hover:border-brand-500' : 'border-gray-200 bg-gray-50/60 hover:border-blue-400' }} transition flex items-start gap-4 cursor-pointer select-none shadow-sm">
                                <input
                                    type="checkbox"
                                    class="doc-check-item mt-1 w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-gray-300"
                                    data-mandatory="{{ $doc->is_mandatory ? '1' : '0' }}"
                                    onchange="updateDocChecklistProgress()"
                                >
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-xs font-bold text-gray-400">#{{ $index + 1 }}</span>
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $doc->document_name }}</h4>
                                            @if($doc->is_mandatory)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    WAJIB DIUNGGAH
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    OPSIONAL
                                                </span>
                                            @endif
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                            Format: {{ $doc->file_format }} • Maks. 2 MB
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                        {{ $doc->description ?? 'Pastikan berkas dapat terbaca dengan jelas dan sesuai dengan format yang ditentukan.' }}
                                    </p>
                                </div>
                            </label>
                        @empty
                            <div class="p-8 rounded-2xl border border-dashed border-gray-300 text-center space-y-2">
                                <p class="text-sm font-semibold text-gray-700">Belum Ada Daftar Dokumen Wajib</p>
                                <p class="text-xs text-gray-400">Admin belum menambahkan daftar berkas persyaratan untuk beasiswa ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Simulasi Kesiapan Berkas Mahasiswa -->
                    @if($scholarship->requiredDocuments->count() > 0)
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">Checklist Kesiapan Berkas Anda</p>
                                    <p id="doc_checklist_status" class="text-[11px] text-gray-500">
                                        Centang dokumen di atas yang sudah Anda siapkan di perangkat Anda.
                                    </p>
                                </div>
                            </div>
                            <span id="doc_checklist_badge" class="px-3 py-1 rounded-xl text-xs font-bold bg-gray-200 text-gray-700 shrink-0">
                                0 / {{ $scholarship->requiredDocuments->count() }} Berkas Siap
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 1 Col: Alur Pendaftaran (Mockup Gambar 26) -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6 h-fit">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Alur Pendaftaran</h3>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                        <span id="stepper_label">Langkah 1 dari 4</span>
                        <span id="stepper_percent" class="font-semibold text-brand-600">25%</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
                        <div id="stepper_bar" class="bg-brand-600 h-2 rounded-full w-1/4 transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Stepper Steps -->
                <div class="space-y-4 text-xs">
                    <!-- Step 1: Cek Kualifikasi (PBI-13) -->
                    <div id="step_item_1" onclick="switchTab('persyaratan')" class="flex items-start gap-3 cursor-pointer">
                        <div id="step_circle_1" class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            1
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">1. Cek Kualifikasi (PBI-13)</p>
                            <p class="text-gray-400 text-[11px]">Validasi IPK dan status semester</p>
                        </div>
                    </div>

                    <!-- Step 2: Unggah Dokumen (PBI-14) -->
                    <div id="step_item_2" onclick="switchTab('dokumen')" class="flex items-start gap-3 opacity-60 cursor-pointer">
                        <div id="step_circle_2" class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            2
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">2. Daftar Dokumen Wajib (PBI-14)</p>
                            <p class="text-gray-400 text-[11px]">
                                {{ $scholarship->requiredDocuments->count() }} berkas persyaratan beasiswa
                            </p>
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
                    <button
                        type="button"
                        id="btn_next_step"
                        onclick="switchTab('dokumen')"
                        class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition shadow-md shadow-brand-600/30 flex items-center justify-center gap-1.5"
                    >
                        <span id="btn_next_step_text">Lihat Daftar Dokumen Wajib</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>

        </div>

    </main>

    <!-- JavaScript Live Screener & Tab Switcher -->
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

        function switchTab(tabName) {
            const panelPersyaratan = document.getElementById('panel-persyaratan');
            const panelDokumen = document.getElementById('panel-dokumen');
            const btnPersyaratan = document.getElementById('tab-btn-persyaratan');
            const btnDokumen = document.getElementById('tab-btn-dokumen');

            const stepItem2 = document.getElementById('step_item_2');
            const stepCircle2 = document.getElementById('step_circle_2');
            const stepperLabel = document.getElementById('stepper_label');
            const stepperPercent = document.getElementById('stepper_percent');
            const stepperBar = document.getElementById('stepper_bar');
            const btnNextText = document.getElementById('btn_next_step_text');

            if (tabName === 'dokumen') {
                panelPersyaratan.classList.add('hidden');
                panelDokumen.classList.remove('hidden');

                btnPersyaratan.className = "pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition";
                btnDokumen.className = "pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2 whitespace-nowrap transition";

                stepItem2.classList.remove('opacity-60');
                stepCircle2.className = "w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5";
                stepperLabel.textContent = "Langkah 2 dari 4";
                stepperPercent.textContent = "50%";
                stepperBar.className = "bg-brand-600 h-2 rounded-full w-2/4 transition-all duration-300";
                btnNextText.textContent = "Siap Mengunggah Berkas";
            } else {
                panelDokumen.classList.add('hidden');
                panelPersyaratan.classList.remove('hidden');

                btnPersyaratan.className = "pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2 whitespace-nowrap transition";
                btnDokumen.className = "pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition";

                stepItem2.classList.add('opacity-60');
                stepCircle2.className = "w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5";
                stepperLabel.textContent = "Langkah 1 dari 4";
                stepperPercent.textContent = "25%";
                stepperBar.className = "bg-brand-600 h-2 rounded-full w-1/4 transition-all duration-300";
                btnNextText.textContent = "Lihat Daftar Dokumen Wajib";
            }
        }

        function updateDocChecklistProgress() {
            const checkboxes = document.querySelectorAll('.doc-check-item');
            const total = checkboxes.length;
            let checked = 0;
            let mandatoryTotal = 0;
            let mandatoryChecked = 0;

            checkboxes.forEach(cb => {
                const isMandatory = cb.getAttribute('data-mandatory') === '1';
                if (isMandatory) mandatoryTotal++;
                if (cb.checked) {
                    checked++;
                    if (isMandatory) mandatoryChecked++;
                }
            });

            const badge = document.getElementById('doc_checklist_badge');
            const status = document.getElementById('doc_checklist_status');
            if (!badge || !status) return;

            badge.textContent = `${checked} / ${total} Berkas Siap`;

            if (mandatoryChecked === mandatoryTotal && mandatoryTotal > 0) {
                badge.className = "px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0";
                status.textContent = "Seluruh dokumen wajib telah Anda siapkan! Anda siap melanjutkan pendaftaran.";
                status.className = "text-[11px] text-emerald-700 font-semibold";
            } else {
                badge.className = "px-3 py-1 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0";
                status.textContent = `Masih ada ${mandatoryTotal - mandatoryChecked} dokumen wajib yang perlu Anda siapkan.`;
                status.className = "text-[11px] text-amber-700 font-medium";
            }
        }

        gpaInput.addEventListener('input', checkEligibility);
        semInput.addEventListener('input', checkEligibility);
        checkEligibility();

        // Buka tab Dokumen secara otomatis apabila parameter URL ?tab=dokumen diberikan
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'dokumen') {
            switchTab('dokumen');
        }
    </script>
</body>
</html>
