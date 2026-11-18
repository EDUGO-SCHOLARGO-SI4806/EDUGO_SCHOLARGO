@extends('layouts.admin')

@section('title', 'Legalitas Lembaga')
@section('breadcrumb', 'Legalitas Lembaga')

@section('content')
@php
    $badgeClass = match ($profile->verification_status) {
        \App\Models\OrganizerProfile::STATUS_APPROVED => 'bg-green-100 text-green-700 ring-green-200',
        \App\Models\OrganizerProfile::STATUS_REJECTED => 'bg-red-100 text-red-700 ring-red-200',
        default => 'bg-amber-100 text-amber-700 ring-amber-200',
    };
@endphp

<div class="max-w-4xl mx-auto space-y-6">

    @if (session('success'))
        <div dusk="flash-success" class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div dusk="flash-error" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Ringkasan pengajuan --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Status Verifikasi Akun</p>
                <h2 class="mt-1 text-xl font-bold text-gray-900">{{ $profile->institution_name }}</h2>
                <p class="text-sm text-gray-500">Perwakilan: {{ $profile->representative_name }}</p>
                @if ($profile->submitted_at)
                    <p class="text-xs text-gray-400 mt-1">Diajukan pada {{ $profile->submitted_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            <span dusk="status-badge" class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold ring-1 {{ $badgeClass }}">
                {{ $profile->statusLabel() }}
            </span>
        </div>

        @if ($profile->isPending())
            <p class="mt-4 text-sm text-gray-600">Dokumen Anda sedang ditinjau oleh Super Admin. Mohon tunggu hingga proses verifikasi selesai.</p>
        @elseif ($profile->isApproved())
            <p class="mt-4 text-sm text-gray-600">Akun Anda sudah terverifikasi sebagai penyelenggara beasiswa.</p>
        @elseif ($profile->isRejected())
            <div dusk="rejection-note" class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Catatan penolakan dari Super Admin</p>
            <p class="mt-1">{{ $profile->verification_note ?: 'Tidak ada catatan dari Super Admin.' }}</p>
            </div>
        @endif
        
    </div>

    {{-- Daftar dokumen --}}
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Dokumen Legalitas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Jenis Dokumen</th>
                        <th class="px-6 py-3">File</th>
                        <th class="px-6 py-3">Ukuran</th>
                        <th class="px-6 py-3">Diunggah</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($types as $type)
                        @php($document = $documents->get($type->value))
                        <tr dusk="doc-row-{{ $type->value }}">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $type->label() }}</td>
                            @if ($document)
                                <td class="px-6 py-4 text-gray-600">{{ $document->original_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $document->sizeForHumans() }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $document->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <a dusk="download-{{ $type->value }}" href="{{ route('organizer.documents.download', $document) }}"
                                       class="font-semibold text-brand-600 hover:text-brand-700">Unduh</a>
                                </td>
                            @else
                                <td class="px-6 py-4 text-gray-400" colspan="4">Belum diunggah</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- PBI-03: unggah ulang (hanya saat ditolak) --}}
    @if ($profile->isRejected())
    <form dusk="resubmit-form" method="POST" action="{{ route('organizer.documents.resubmit') }}" enctype="multipart/form-data"
            class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5" novalidate>
        @csrf
        @method('PUT')

            <div>
                <h3 class="font-semibold text-gray-900">Unggah Ulang Dokumen</h3>
                <p class="text-sm text-gray-500">
                    Pilih hanya dokumen yang kurang atau tidak sesuai. Format PDF atau JPG, maksimal {{ \App\Models\OrganizerDocument::MAX_SIZE_KB / 1024 }} MB per file.
                </p>
                @error('documents')
                    <p dusk="error-documents" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @foreach ($types as $type)
                <div>
                    <label for="resubmit-{{ $type->value }}" class="block text-sm font-medium text-gray-700">{{ $type->label() }}</label>
                    <input id="resubmit-{{ $type->value }}" type="file" name="documents[{{ $type->value }}]" accept=".pdf,.jpg,.jpeg"
                           class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
                    @error('documents.'.$type->value)
                        <p dusk="error-resubmit-{{ $type->value }}" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-end">
                <button dusk="submit-resubmit" type="submit"
                        class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/30 hover:bg-brand-700 transition">
                    Kirim Ulang Dokumen
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
