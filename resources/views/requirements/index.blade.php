@extends('layouts.admin')

@section('title', 'Kelola Persyaratan Beasiswa')
@section('breadcrumb', 'Beasiswa Saya / Persyaratan Kualifikasi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Flash Message Success -->
    @if(session('success'))
        <div class="bg-brand-50 border border-brand-200 text-brand-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm animate-fade-in">
            <div class="p-1 bg-brand-100 rounded-full text-brand-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Pembaruan Berhasil</p>
                <p class="text-xs text-brand-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-brand-50 text-brand-700 text-xs font-semibold rounded-full mb-2 border border-brand-200">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                Sprint 1 • PBI-13
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Persyaratan Beasiswa</h2>
            <p class="text-sm text-gray-500 mt-1">
                Tentukan kriteria kualifikasi IPK minimal dan batas semester aktif untuk seleksi awal pendaftar.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-2 rounded-xl font-medium">
                Total: <strong class="text-gray-800">{{ $scholarships->count() }} Program Beasiswa</strong>
            </span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm">Daftar Program Beasiswa Aktif</h3>
            <span class="text-xs text-gray-400">Atur kriteria per beasiswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="py-3.5 px-6">Program Beasiswa</th>
                        <th class="py-3.5 px-6">Min. IPK</th>
                        <th class="py-3.5 px-6">Min. Semester</th>
                        <th class="py-3.5 px-6">Status Skrining</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($scholarships as $scholarship)
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Nama Beasiswa -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($scholarship->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $scholarship->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $scholarship->organizer }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Min IPK (PBI-13) -->
                            <td class="py-4 px-6">
                                @if($scholarship->requirement)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        IPK &ge; {{ number_format($scholarship->requirement->min_gpa, 2) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum diatur</span>
                                @endif
                            </td>

                            <!-- Min Semester (PBI-13) -->
                            <td class="py-4 px-6">
                                @if($scholarship->requirement)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Semester &ge; {{ $scholarship->requirement->min_semester }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum diatur</span>
                                @endif
                            </td>

                            <!-- Status Skrining -->
                            <td class="py-4 px-6">
                                @if($scholarship->requirement)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Auto-Screening Siap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        Perlu Konfigurasi
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('scholarships.requirements', $scholarship->id) }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 hover:text-gray-900 text-xs font-medium transition inline-flex items-center gap-1" title="Lihat tampilan tab mahasiswa (Mockup Gambar 26)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <span>Preview Mahasiswa</span>
                                    </a>

                                    <a href="{{ route('requirements.edit', $scholarship->id) }}" class="px-3.5 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        <span>Atur Kriteria</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                Belum ada data beasiswa yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
