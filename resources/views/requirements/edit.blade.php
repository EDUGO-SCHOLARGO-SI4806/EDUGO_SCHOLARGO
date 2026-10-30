@extends('layouts.admin')

@section('title', 'Atur Kriteria Kualifikasi - ' . $scholarship->name)
@section('breadcrumb', 'Beasiswa Saya / Atur Kriteria')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button & Heading -->
    <div>
        <a href="{{ route('requirements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand-600 transition mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Daftar Beasiswa
        </a>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 bg-brand-50 text-brand-700 text-xs font-semibold rounded-full border border-brand-200">
                PBI-13
            </span>
            <h2 class="text-2xl font-bold text-gray-900">Atur Kriteria Kualifikasi Beasiswa</h2>
        </div>
        <p class="text-sm text-gray-500 mt-1">Konfigurasi batas minimal IPK dan semester aktif untuk validasi otomatis pendaftar.</p>
    </div>

    <!-- Info Banner Beasiswa -->
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 rounded-2xl shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wider text-brand-400 font-semibold">Program Beasiswa Terpilih</p>
            <h3 class="text-xl font-bold mt-1">{{ $scholarship->name }}</h3>
            <p class="text-xs text-gray-300 mt-1 flex items-center gap-2">
                <span>Penyelenggara: <strong>{{ $scholarship->organizer }}</strong></span>
                <span>•</span>
                <span>ID: #{{ $scholarship->id }}</span>
            </p>
        </div>
        <div class="hidden sm:block">
            <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-medium border border-white/20">
                Portal Mitra ScholarGO
            </span>
        </div>
    </div>

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

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('requirements.update', $scholarship->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input Min GPA (PBI-13) -->
                <div>
                    <label for="min_gpa" class="block text-sm font-semibold text-gray-800 mb-1">
                        Minimal Indeks Prestasi Kumulatif (IPK) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-2">Nilai skala 0.00 – 4.00 (gunakan tanda titik untuk desimal).</p>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <input
                            type="number"
                            id="min_gpa"
                            name="min_gpa"
                            step="0.01"
                            min="0.00"
                            max="4.00"
                            value="{{ old('min_gpa', $requirement->min_gpa ?? 3.00) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('min_gpa') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-sm text-gray-800 font-medium transition"
                            placeholder="Contoh: 3.25"
                            required
                        >
                    </div>
                    @error('min_gpa')
                        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Min Semester (PBI-13) -->
                <div>
                    <label for="min_semester" class="block text-sm font-semibold text-gray-800 mb-1">
                        Minimal Semester Aktif Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-2">Semester minimal pendaftar saat mengajukan beasiswa (1 – 14).</p>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <input
                            type="number"
                            id="min_semester"
                            name="min_semester"
                            min="1"
                            max="14"
                            value="{{ old('min_semester', $requirement->min_semester ?? 1) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border {{ $errors->has('min_semester') ? 'border-red-400 ring-1 ring-red-400' : 'border-gray-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} text-sm text-gray-800 font-medium transition"
                            placeholder="Contoh: 3"
                            required
                        >
                    </div>
                    @error('min_semester')
                        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Business Rule Box -->
            <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-4 text-xs text-blue-800 flex items-start gap-3">
                <div class="p-1 bg-blue-100 rounded-lg text-blue-700 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <strong class="font-semibold">Logika Seleksi Otomatis (Auto-Screening):</strong>
                    <p class="mt-0.5 text-blue-700 leading-relaxed">
                        Mahasiswa yang memiliki IPK di bawah nilai minimal atau belum mencapai semester minimal tidak akan dapat melanjutkan ke tahap unggah dokumen berkas (FR-05).
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('requirements.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 text-sm font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-md shadow-brand-600/30 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span>Simpan Kriteria</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
