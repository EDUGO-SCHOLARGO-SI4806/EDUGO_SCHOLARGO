@extends('layouts.admin')

@section('title', 'Atur Periode Pendaftaran - ' . $scholarship->name)
@section('breadcrumb', 'Beasiswa Saya / Periode Pendaftaran')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Back Button & Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('requirements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand-600 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Daftar Beasiswa
            </a>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-brand-50 text-brand-700 text-xs font-semibold rounded-full border border-brand-200">
                    Periode Pendaftaran
                </span>
                <h2 class="text-2xl font-bold text-gray-900">Mengatur Periode dan Batas Waktu Pendaftaran</h2>
            </div>
            <p class="text-sm text-gray-500 mt-1">Konfigurasi tanggal pembukaan, batas waktu penutupan, dan status pembukaan pendaftaran beasiswa.</p>
        </div>
    </div>

    <!-- Flash Message Success -->
    @if(session('success'))
        <div class="bg-brand-50 border border-brand-200 text-brand-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm animate-fade-in">
            <div class="p-1 bg-brand-100 rounded-full text-brand-700 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Pembaruan Periode Berhasil</p>
                <p class="text-xs text-brand-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Summary (if any) -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Mohon periksa kembali input Anda:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 ml-6 text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Info Banner Beasiswa & Navigasi Antar Fitur (PBI-13, 14, 15) -->
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-brand-400 font-semibold">Program Beasiswa Terpilih</p>
            <h3 class="text-xl font-bold mt-1">{{ $scholarship->name }}</h3>
            <p class="text-xs text-gray-300 mt-1 flex flex-wrap items-center gap-2">
                <span>Penyelenggara: <strong>{{ $scholarship->organizer }}</strong></span>
                <span>•</span>
                <span>Status Saat Ini:
                    @if($period->isOpen())
                        <strong class="text-emerald-400">Pendaftaran Sedang Dibuka</strong>
                    @elseif($period->isUpcoming())
                        <strong class="text-amber-400">Belum Dibuka (Mendatang)</strong>
                    @else
                        <strong class="text-rose-400">Pendaftaran Ditutup</strong>
                    @endif
                </span>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('requirements.edit', $scholarship->id) }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-semibold border border-white/20 transition">
                Kriteria IPK & Semester
            </a>
            <a href="{{ route('requirements.documents', $scholarship->id) }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-semibold border border-white/20 transition">
                Dokumen Wajib
            </a>
            <a href="{{ route('scholarships.requirements', ['id' => $scholarship->id, 'tab' => 'persyaratan']) }}" target="_blank" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>Preview Mahasiswa</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri (1 Col): Ringkasan Status & Quick Toggle -->
        <div class="space-y-6">

            <!-- Card Status Realtime -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-sm">Status Periode Saat Ini</h3>

                @if($period->isOpen())
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Sedang Dibuka</span>
                        </div>
                        <p class="text-2xl font-extrabold text-emerald-700">
                            {{ $period->daysRemaining() }} Hari Tersisa
                        </p>
                        <p class="text-[11px] text-emerald-700">
                            Pendaftaran aktif hingga <strong>{{ \Carbon\Carbon::parse($period->close_date)->translatedFormat('d F Y') }}</strong>.
                        </p>
                    </div>
                @elseif($period->isUpcoming())
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Pendaftaran Mendatang</span>
                        </div>
                        <p class="text-lg font-bold text-amber-800">
                            Dibuka: {{ \Carbon\Carbon::parse($period->open_date)->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-[11px] text-amber-700">
                            Pendaftaran belum dapat diakses oleh mahasiswa sebelum tanggal pembukaan.
                        </p>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Pendaftaran Ditutup</span>
                        </div>
                        <p class="text-sm font-semibold text-rose-800">
                            @if(!$period->is_active)
                                Ditutup manual oleh admin
                            @else
                                Telah berakhir pada {{ \Carbon\Carbon::parse($period->close_date)->translatedFormat('d F Y') }}
                            @endif
                        </p>
                        <p class="text-[11px] text-rose-700">
                            Mahasiswa tidak dapat mengirimkan formulir pendaftaran baru.
                        </p>
                    </div>
                @endif

                <!-- Detail Jadwal -->
                <div class="space-y-2.5 pt-2 text-xs text-gray-600 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Tanggal Pembukaan:</span>
                        <strong class="text-gray-800">{{ \Carbon\Carbon::parse($period->open_date)->translatedFormat('d M Y') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Batas Waktu Penutupan:</span>
                        <strong class="text-gray-800">{{ \Carbon\Carbon::parse($period->close_date)->translatedFormat('d M Y') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Saklar Akses Sistem:</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $period->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $period->is_active ? 'AKTIF (ON)' : 'NONAKTIF (OFF)' }}
                        </span>
                    </div>
                </div>

                <!-- Quick Toggle Button -->
                <div class="pt-3 border-t border-gray-100">
                    <form action="{{ route('requirements.period.toggle', $scholarship->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 px-3 rounded-xl border {{ $period->is_active ? 'border-rose-200 text-rose-700 hover:bg-rose-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }} text-xs font-semibold transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            <span>{{ $period->is_active ? 'Tutup Pendaftaran Sekarang (Manual)' : 'Buka Kembali Pendaftaran' }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Business Rule Box (FR-05) -->
            <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 text-xs text-blue-800 space-y-2">
                <div class="flex items-center gap-2 font-bold text-blue-900">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Ketentuan Sistem (FR-05):</span>
                </div>
                <p class="text-blue-700 leading-relaxed">
                    Batas waktu pendaftaran digunakan oleh sistem ScholarGO untuk mengunci formulir pendaftaran secara otomatis saat tanggal penutupan terlewati, sehingga pendaftaran hanya terbuka pada waktunya.
                </p>
            </div>

        </div>

        <!-- Kolom Kanan (2 Cols): Form Atur Tanggal & Keterangan Gelombang -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Form Pengaturan Periode & Batas Waktu</h3>
                <p class="text-xs text-gray-500 mt-0.5">Tentukan rentang waktu pendaftaran bagi calon pendaftar beasiswa ini.</p>
            </div>

            <form action="{{ route('requirements.period.update', $scholarship->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Quick Preset Rentang Waktu -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Pintasan Cepat Durasi Penutupan <span class="text-gray-400 font-normal">(Dari hari ini)</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDurationPreset(14)" class="px-3 py-1.5 rounded-xl border border-gray-200 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 text-xs text-gray-600 font-medium transition">
                            +14 Hari (2 Minggu)
                        </button>
                        <button type="button" onclick="setDurationPreset(30)" class="px-3 py-1.5 rounded-xl border border-gray-200 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 text-xs text-gray-600 font-medium transition">
                            +30 Hari (1 Bulan)
                        </button>
                        <button type="button" onclick="setDurationPreset(60)" class="px-3 py-1.5 rounded-xl border border-gray-200 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 text-xs text-gray-600 font-medium transition">
                            +60 Hari (2 Bulan)
                        </button>
                        <button type="button" onclick="setDurationPreset(90)" class="px-3 py-1.5 rounded-xl border border-gray-200 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-700 text-xs text-gray-600 font-medium transition">
                            +90 Hari (3 Bulan)
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Input Open Date -->
                    <div>
                        <label for="open_date" class="block text-xs font-semibold text-gray-800 mb-1">
                            Tanggal Pembukaan Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="date"
                                id="open_date"
                                name="open_date"
                                value="{{ old('open_date', optional($period->open_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('open_date') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-sm text-gray-800 font-medium transition focus:outline-none"
                            >
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Tanggal mulai pendaftar dapat mengisi berkas.</p>
                        @error('open_date')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Close Date (Deadline) -->
                    <div>
                        <label for="close_date" class="block text-xs font-semibold text-gray-800 mb-1">
                            Batas Waktu Penutupan (Deadline) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="date"
                                id="close_date"
                                name="close_date"
                                value="{{ old('close_date', optional($period->close_date)->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('close_date') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-sm text-gray-800 font-medium transition focus:outline-none"
                            >
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Batas akhir pengumpulan formulir pendaftaran.</p>
                        @error('close_date')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Input Status Aktif -->
                <div>
                    <label for="is_active" class="block text-xs font-semibold text-gray-800 mb-1">
                        Status Ketersediaan Pendaftaran <span class="text-red-500">*</span>
                    </label>
                    <select id="is_active" name="is_active" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:border-brand-500 focus:outline-none transition">
                        <option value="1" {{ old('is_active', $period->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>
                            Aktif (Pendaftaran mengikuti jadwal tanggal pembukaan & penutupan)
                        </option>
                        <option value="0" {{ old('is_active', $period->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>
                            Nonaktif (Tutup pendaftaran sementara secara manual oleh admin)
                        </option>
                    </select>
                </div>

                <!-- Input Deskripsi / Nama Gelombang -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-800 mb-1">
                        Nama Gelombang / Keterangan Periode <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        id="description"
                        name="description"
                        value="{{ old('description', $period->description) }}"
                        placeholder="Contoh: Gelombang 1 Tahun Akademik 2026/2027"
                        maxlength="255"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:border-brand-500 focus:outline-none transition"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Catatan informasi jadwal yang akan tampil di halaman pendaftaran mahasiswa.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('requirements.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/30 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>Simpan Periode Pendaftaran</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<script>
    function setDurationPreset(days) {
        const openInput = document.getElementById('open_date');
        const closeInput = document.getElementById('close_date');

        const startDate = openInput.value ? new Date(openInput.value) : new Date();
        const endDate = new Date(startDate.getTime());
        endDate.setDate(endDate.getDate() + days);

        const yyyy = endDate.getFullYear();
        const mm = String(endDate.getMonth() + 1).padStart(2, '0');
        const dd = String(endDate.getDate()).padStart(2, '0');

        closeInput.value = `${yyyy}-${mm}-${dd}`;
    }
</script>
@endsection
