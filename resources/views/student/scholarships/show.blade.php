<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $scholarship->name }} - ScholarGO</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS (agar semua selector pengujian Dusk tetap valid) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .tab-btn.active { color: #059669; border-color: #059669; }
        a { text-decoration: none; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen">

    @php
        $period = $scholarship->registrationPeriod;
        $openDate = $period?->open_date ?? $scholarship->start_date;
        $closeDate = $period?->close_date ?? $scholarship->end_date;
        $isRegistrationOpen = ($periodeStatus === 'dibuka');
        $namaUser = auth()->user()?->name ?? 'Mahasiswa';
    @endphp

    <!-- Top Navigation (Mockup Gambar 26) -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <!-- Brand -->
            <a href="{{ route('beasiswa.index') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md shadow-brand-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 tracking-tight">Scholar<span class="text-brand-600">GO</span></span>
            </a>

            <!-- Search Bar Mock -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <form action="{{ route('beasiswa.index') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="q" placeholder="Cari beasiswa impianmu..." class="w-full pl-9 pr-4 py-1.5 rounded-full bg-gray-50 border border-gray-200 text-xs focus:outline-none focus:border-brand-500">
                </form>
            </div>

            <!-- Nav Links -->
            <nav class="flex items-center gap-6 text-xs font-semibold text-gray-600">
                <a href="{{ route('beasiswa.index') }}" class="text-brand-600 hover:text-brand-700">Beranda</a>
                <a href="{{ route('profile.show') }}" class="hover:text-gray-900">Profil</a>
                <a href="#" class="hover:text-gray-900">Notifikasi</a>
                @auth
                    <a href="{{ route('profile.show') }}" class="w-8 h-8 rounded-full bg-brand-100 text-brand-800 border border-brand-200 flex items-center justify-center font-bold text-xs" title="{{ $namaUser }}">
                        {{ strtoupper(substr($namaUser, 0, 2)) }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-brand-600 text-white rounded-lg hover:bg-brand-700">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Breadcrumb & Back Button -->
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('beasiswa.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-brand-600 transition bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Daftar Beasiswa
            </a>
            <div class="text-xs text-gray-400">
                <span>Katalog</span> / <span class="font-medium text-gray-700">{{ $scholarship->name }}</span>
            </div>
        </div>

        <!-- Hero Card (Mockup Gambar 26) -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-6">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500">Penyelenggara: {{ $scholarship->organizer_name }}</span>
                    @if($scholarship->level)
                        <span class="text-gray-300">•</span>
                        <span class="text-xs font-semibold text-brand-700 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-full">Jenjang: {{ $scholarship->level }}</span>
                    @endif
                    @if($scholarship->category)
                        <span class="text-gray-300">•</span>
                        <span class="text-xs text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full font-semibold">Kategori: {{ $scholarship->category }}</span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mt-1.5">{{ $scholarship->name }}</h1>

                <div class="flex flex-wrap items-center gap-3 mt-3 text-xs">
                    <span class="px-3 py-1 bg-brand-50 text-brand-700 font-bold rounded-full border border-brand-200">
                        {{ $scholarship->category ?? 'Pendanaan Penuh' }}
                    </span>

                    <!-- Status Periode Pendaftaran -->
                    @if($periodeStatus === 'dibuka')
                        <span class="badge bg-success text-white px-3 py-1.5 rounded-full font-semibold inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Dibuka
                        </span>
                    @elseif($periodeStatus === 'belum_dibuka')
                        <span class="badge bg-info text-dark px-3 py-1.5 rounded-full font-semibold">
                            Belum dibuka
                        </span>
                    @else
                        <span class="badge bg-secondary text-white px-3 py-1.5 rounded-full font-semibold">
                            Ditutup
                        </span>
                    @endif

                    @if($scholarship->end_date)
                        @php $sisa = (int) today()->diffInDays($scholarship->end_date, false); @endphp
                        <span class="badge {{ $sisa >= 0 ? 'bg-warning text-dark' : 'bg-secondary' }} px-3 py-1.5 rounded-full font-semibold">
                            {{ $sisa >= 0 ? "Sisa $sisa hari" : 'Pendaftaran ditutup' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Body: Tabs & Stepper Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left 2 Cols: Interactive Tabs (Persyaratan, Dokumen, Jadwal, Deskripsi) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm space-y-6">

                <!-- Navigation Tabs (Mockup Gambar 26) -->
                <div class="flex border-b border-gray-200 gap-4 sm:gap-6 text-sm font-semibold overflow-x-auto pb-px">
                    <button type="button" onclick="switchTab('persyaratan')" id="tab-btn-persyaratan" class="tab-btn pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2 whitespace-nowrap transition active">
                        <span>Persyaratan Kualifikasi</span>
                    </button>
                    <button type="button" onclick="switchTab('dokumen')" id="tab-btn-dokumen" class="tab-btn pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition">
                        <span>Dokumen Wajib</span>
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-[10px] font-bold">
                            ({{ $scholarship->requiredDocuments->count() }})
                        </span>
                    </button>
                    <button type="button" onclick="switchTab('jadwal')" id="tab-btn-jadwal" class="tab-btn pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition">
                        <span>Jadwal & Periode</span>
                    </button>
                    <button type="button" onclick="switchTab('deskripsi')" id="tab-btn-deskripsi" class="tab-btn pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition">
                        <span>Tentang Beasiswa</span>
                    </button>
                </div>

                <!-- ========================================== -->
                <!-- PANEL 1: Persyaratan & Cek Profil (PBI-13 & PBI-21) -->
                <!-- ========================================== -->
                <div id="panel-persyaratan" class="space-y-6">
                    <div>
                        <h4 class="h5 font-bold text-gray-900">Persyaratan</h4>
                        <p class="text-xs text-gray-500 mt-1">Kriteria kualifikasi minimum yang harus dipenuhi calon pendaftar.</p>
                    </div>

                    @if($scholarship->requirement)
                        <!-- Card Highlights IPK & Semester -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs uppercase font-bold text-emerald-800">Minimal IPK Kumulatif</p>
                                    <p class="text-3xl font-extrabold text-brand-700 mt-0.5">{{ number_format($scholarship->requirement->min_gpa, 2) }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Skala standar 4.00 resmi dari kampus</p>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs uppercase font-bold text-blue-800">Minimal Semester Aktif</p>
                                    <p class="text-3xl font-extrabold text-blue-700 mt-0.5">Semester {{ $scholarship->requirement->min_semester }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Status mahasiswa aktif berjalan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Teks Persyaratan (Dusk Assertion) -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm">
                            <ul class="space-y-1 list-disc list-inside text-gray-700">
                                <li>IPK minimum: {{ number_format($scholarship->requirement->min_gpa, 2) }}</li>
                                <li>Semester minimum: {{ $scholarship->requirement->min_semester }}</li>
                            </ul>
                        </div>
                    @else
                        <p class="text-muted text-sm bg-gray-50 p-4 rounded-xl border border-gray-200">Belum ada persyaratan kualifikasi.</p>
                    @endif

                    <hr class="border-gray-200">

                    <!-- Cek Kesesuaian Profil Mahasiswa (Fitur Fira - PBI 21) -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="h5 font-bold text-gray-900 mb-0">Cek Kesesuaian Profil</h4>
                            <span class="text-xs text-brand-600 font-semibold">Fitur Kesesuaian Profil</span>
                        </div>

                        @if(is_null($eligibility))
                            <div class="alert alert-info mb-0 rounded-xl">
                                Login untuk mengecek kesesuaian profilmu dengan syarat beasiswa ini.
                                @if(Route::has('login')) <a href="{{ route('login') }}" class="font-bold underline ml-1">Login Sekarang</a> @endif
                            </div>
                        @elseif($eligibility['status'] === 'no_requirement')
                            <div class="alert alert-secondary mb-0 rounded-xl">
                                Beasiswa ini belum punya syarat IPK dan semester, jadi tidak ada yang dicek.
                            </div>
                        @elseif($eligibility['status'] === 'incomplete_profile')
                            <div class="alert alert-warning mb-0 rounded-xl">
                                Data akademik (IPK dan semester) belum lengkap. Lengkapi profilmu dulu agar bisa dicek.
                                @if(Route::has('profile.edit')) <a href="{{ route('profile.edit') }}" class="font-bold underline ml-1">Lengkapi Profil</a> @endif
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table table-sm w-auto table-bordered rounded-xl overflow-hidden text-sm">
                                    <thead class="bg-gray-100 text-gray-700 font-semibold">
                                        <tr>
                                            <th class="px-4 py-2">Syarat</th>
                                            <th class="px-4 py-2">Dibutuhkan</th>
                                            <th class="px-4 py-2">Profilmu</th>
                                            <th class="px-4 py-2 text-center">Hasil</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($eligibility['checks'] as $c)
                                            <tr>
                                                <td class="px-4 py-2 font-medium">{{ $c['label'] }}</td>
                                                <td class="px-4 py-2">&ge; {{ $c['required'] }}</td>
                                                <td class="px-4 py-2 font-bold">{{ $c['actual'] }}</td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($c['passed'])
                                                        <span class="badge bg-success px-2.5 py-1 rounded-full">Sesuai</span>
                                                    @else
                                                        <span class="badge bg-danger px-2.5 py-1 rounded-full">Belum sesuai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($eligibility['status'] === 'eligible')
                                <div class="alert alert-success mb-0 rounded-xl flex items-center gap-2 font-semibold">
                                    <svg class="w-5 h-5 text-green-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Profilmu memenuhi syarat beasiswa ini.
                                </div>
                            @else
                                <div class="alert alert-danger mb-0 rounded-xl flex items-center gap-2 font-semibold">
                                    <svg class="w-5 h-5 text-red-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Profilmu belum memenuhi syarat beasiswa ini.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- PANEL 2: Dokumen Wajib (PBI-14)            -->
                <!-- ========================================== -->
                <div id="panel-dokumen" class="space-y-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="h5 font-bold text-gray-900">Dokumen Wajib</h4>
                            <p class="text-xs text-gray-500 mt-1">Daftar berkas yang wajib diunggah calon penerima beasiswa.</p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full">
                            {{ $scholarship->requiredDocuments->where('is_mandatory', true)->count() }} Berkas Wajib
                        </span>
                    </div>

                    @if($scholarship->requiredDocuments->isEmpty())
                        <p class="text-muted text-sm bg-gray-50 p-4 rounded-xl border border-gray-200">Belum ada daftar dokumen.</p>
                    @else
                        <!-- List view untuk Dusk Test Assertions -->
                        <ul class="list-unstyled space-y-3">
                            @foreach($scholarship->requiredDocuments as $doc)
                                <li class="mb-2 p-4 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-emerald-50/30 transition flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            📄
                                        </div>
                                        <div>
                                            <strong class="text-gray-900 font-bold text-sm">{{ $doc->document_name }}</strong>
                                            <span class="badge {{ $doc->is_mandatory ? 'bg-danger' : 'bg-secondary' }} px-2 py-0.5 rounded-full text-[10px] ml-1">
                                                {{ $doc->is_mandatory ? 'Wajib' : 'Opsional' }}
                                            </span>
                                            <span class="text-muted small text-xs text-gray-400">({{ $doc->file_format }})</span>
                                            @if($doc->description)
                                                <div class="text-muted small text-xs text-gray-500 mt-1">{{ $doc->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer shrink-0">
                                        <input type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        <span>Sudah Siap</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- PANEL 3: Jadwal & Periode (PBI-15)         -->
                <!-- ========================================== -->
                <div id="panel-jadwal" class="space-y-6 hidden">
                    <div>
                        <h4 class="h5 font-bold text-gray-900">Periode Pendaftaran</h4>
                        <p class="text-xs text-gray-500 mt-1">Batas waktu pembukaan dan penutupan pendaftaran beasiswa.</p>
                    </div>

                    @if($periodeStatus === 'belum_ditentukan' || ! $openDate || ! $closeDate)
                        <p class="text-muted text-sm bg-gray-50 p-4 rounded-xl border border-gray-200">Periode pendaftaran belum ditentukan.</p>
                    @else
                        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $openDate->translatedFormat('d F Y') }}
                                s/d
                                {{ $closeDate->translatedFormat('d F Y') }}
                                @if($periodeStatus === 'dibuka')
                                    <span class="badge bg-success ml-2 px-3 py-1 rounded-full">Dibuka</span>
                                @elseif($periodeStatus === 'belum_dibuka')
                                    <span class="badge bg-info text-dark ml-2 px-3 py-1 rounded-full">Belum dibuka</span>
                                @else
                                    <span class="badge bg-secondary ml-2 px-3 py-1 rounded-full">Ditutup</span>
                                @endif
                            </p>
                            @if($isRegistrationOpen)
                                <p class="text-xs text-emerald-700 mt-2 font-medium">
                                    Pendaftaran saat ini sedang aktif dibuka. Silakan persiapkan seluruh dokumen sebelum batas penutupan.
                                </p>
                            @else
                                <p class="text-xs text-rose-700 mt-2 font-medium">
                                    Pendaftaran saat ini ditutup. Anda tetap dapat membaca persyaratan beasiswa ini.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- PANEL 4: Deskripsi Beasiswa (PBI-20)       -->
                <!-- ========================================== -->
                <div id="panel-deskripsi" class="space-y-6 hidden">
                    <div>
                        <h4 class="h5 font-bold text-gray-900">Deskripsi</h4>
                        <p class="text-sm text-gray-700 leading-relaxed mt-2">{!! nl2br(e($scholarship->description)) !!}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        @if($scholarship->amount)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="text-xs text-gray-500 font-semibold block uppercase">Nominal Bantuan</span>
                                <span class="text-lg font-bold text-brand-700">Rp {{ number_format($scholarship->amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($scholarship->quota)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="text-xs text-gray-500 font-semibold block uppercase">Kuota Penerima</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($scholarship->quota, 0, ',', '.') }} Mahasiswa</span>
                            </div>
                        @endif
                    </div>

                    @if($scholarship->source_url)
                        <div class="pt-4 border-t border-gray-100">
                            <a href="{{ $scholarship->source_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 underline">
                                🔗 Buka Halaman Resmi / Sumber Pendaftaran Beasiswa
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right 1 Col: Stepper Alur Pendaftaran & CTA Card -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6 h-fit">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Alur Pendaftaran</h3>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                        <span id="stepper_label">Langkah 1 dari 4</span>
                        <span id="stepper_percent" class="font-semibold text-brand-600">25%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
                        <div id="stepper_bar" class="bg-brand-600 h-2 rounded-full w-1/4 transition-all duration-300"></div>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div onclick="switchTab('persyaratan')" class="flex items-start gap-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">1</div>
                        <div>
                            <p class="font-bold text-gray-900">1. Cek Kualifikasi IPK & Semester</p>
                            <p class="text-gray-400 text-[11px]">Validasi kriteria dengan profil akunmu</p>
                        </div>
                    </div>

                    <div onclick="switchTab('dokumen')" class="flex items-start gap-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">2</div>
                        <div>
                            <p class="font-bold text-gray-900">2. Siapkan Dokumen Wajib</p>
                            <p class="text-gray-400 text-[11px]">{{ $scholarship->requiredDocuments->count() }} berkas persyaratan</p>
                        </div>
                    </div>

                    <div onclick="switchTab('jadwal')" class="flex items-start gap-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">3</div>
                        <div>
                            <p class="font-bold text-gray-900">3. Jadwal & Batas Waktu</p>
                            <p class="text-gray-400 text-[11px]">Pantau tanggal buka dan tutup pendaftaran</p>
                        </div>
                    </div>

                    <div onclick="switchTab('deskripsi')" class="flex items-start gap-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">4</div>
                        <div>
                            <p class="font-bold text-gray-900">4. Informasi Lengkap & Pendaftaran</p>
                            <p class="text-gray-400 text-[11px]">Pelajari rincian beasiswa & sumber resmi</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="pt-4 border-t border-gray-100 space-y-2">
                    @if($isRegistrationOpen && $scholarship->source_url)
                        <a href="{{ $scholarship->source_url }}" target="_blank" rel="noopener noreferrer" class="w-full block text-center py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md shadow-brand-600/30 transition">
                            Daftar Sekarang &rarr;
                        </a>
                    @elseif($isRegistrationOpen)
                        <button type="button" onclick="alert('Pendaftaran beasiswa telah dibuka! Silakan lengkapi berkas dan profil Anda.');" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md shadow-brand-600/30 transition">
                            Ajukan Pendaftaran &rarr;
                        </button>
                    @else
                        <button disabled class="w-full py-3 bg-gray-300 text-gray-500 font-bold text-sm rounded-xl cursor-not-allowed">
                            Pendaftaran Ditutup
                        </button>
                    @endif
                    <a href="{{ route('profile.show') }}" class="w-full block text-center py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl border border-gray-200 transition">
                        Kelola Profil Saya
                    </a>
                </div>
            </div>

        </div>

    </main>

    <!-- Tab Switching Script -->
    <script>
        function switchTab(tabId) {
            const tabs = ['persyaratan', 'dokumen', 'jadwal', 'deskripsi'];
            tabs.forEach(t => {
                const panel = document.getElementById('panel-' + t);
                const btn = document.getElementById('tab-btn-' + t);
                if (panel) {
                    if (t === tabId) {
                        panel.classList.remove('hidden');
                    } else {
                        panel.classList.add('hidden');
                    }
                }
                if (btn) {
                    if (t === tabId) {
                        btn.className = "tab-btn pb-3 text-brand-600 border-b-2 border-brand-600 flex items-center gap-2 whitespace-nowrap transition active";
                    } else {
                        btn.className = "tab-btn pb-3 text-gray-400 hover:text-gray-600 border-b-2 border-transparent flex items-center gap-2 whitespace-nowrap transition";
                    }
                }
            });

            // Update Stepper
            const stepMap = {
                'persyaratan': { label: 'Langkah 1 dari 4', percent: '25%', width: 'w-1/4' },
                'dokumen': { label: 'Langkah 2 dari 4', percent: '50%', width: 'w-2/4' },
                'jadwal': { label: 'Langkah 3 dari 4', percent: '75%', width: 'w-3/4' },
                'deskripsi': { label: 'Langkah 4 dari 4', percent: '100%', width: 'w-full' },
            };

            if (stepMap[tabId]) {
                const s = stepMap[tabId];
                document.getElementById('stepper_label').textContent = s.label;
                document.getElementById('stepper_percent').textContent = s.percent;
                document.getElementById('stepper_bar').className = `bg-brand-600 h-2 rounded-full ${s.width} transition-all duration-300`;
            }
        }
    </script>

</body>
</html>
