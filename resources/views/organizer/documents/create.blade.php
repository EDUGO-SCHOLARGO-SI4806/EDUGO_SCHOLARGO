@extends('layouts.admin')

@section('title', 'Unggah Dokumen Legalitas')
@section('breadcrumb', 'Unggah Dokumen Legalitas')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Verifikasi Akun Penyelenggara</h2>
        <p class="text-sm text-gray-500 mt-1">
            Lengkapi data lembaga dan unggah dokumen legalitas agar akun Anda dapat diverifikasi oleh Super Admin.
        </p>
    </div>

    <div class="mb-6 rounded-xl border border-brand-100 bg-brand-50 p-4 text-sm text-brand-800">
        <p class="font-semibold">Ketentuan file</p>
        <p>Format <strong>PDF atau JPG</strong>, ukuran maksimal <strong>{{ \App\Models\OrganizerDocument::MAX_SIZE_KB / 1024 }} MB</strong> per file. Semua dokumen bertanda * wajib diunggah.</p>
    </div>

    @if ($errors->any())
        <div dusk="error-summary" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Pengajuan belum dapat dikirim.</p>
            <p>Periksa {{ $errors->count() }} isian yang bermasalah di bawah ini, lalu pilih ulang file yang diperlukan.</p>
        </div>
    @endif

    <form dusk="upload-form" method="POST" action="{{ route('organizer.documents.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
        @csrf

        {{-- Data lembaga --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-4">
            <h3 class="font-semibold text-gray-900">Data Lembaga</h3>

            <div>
                <label for="institution_name" class="block text-sm font-medium text-gray-700">Nama Lembaga <span class="text-red-500">*</span></label>
                <input id="institution_name" type="text" name="institution_name" value="{{ old('institution_name') }}"
                       class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('institution_name')
                    <p dusk="error-institution_name" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="representative_name" class="block text-sm font-medium text-gray-700">Nama Perwakilan <span class="text-red-500">*</span></label>
                <input id="representative_name" type="text" name="representative_name" value="{{ old('representative_name') }}"
                       class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('representative_name')
                    <p dusk="error-representative_name" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Dokumen legalitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">
            <h3 class="font-semibold text-gray-900">Dokumen Legalitas</h3>

            @foreach ($types as $type)
                <div>
                    <label for="doc-{{ $type->value }}" class="block text-sm font-medium text-gray-700">
                        {{ $type->label() }} <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">{{ $type->hint() }}</p>
                    <input id="doc-{{ $type->value }}" type="file" name="documents[{{ $type->value }}]" accept=".pdf,.jpg,.jpeg"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
                    @error('documents.'.$type->value)
                        <p dusk="error-{{ $type->value }}" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="flex justify-end">
            <button dusk="submit-upload" type="submit"
                    class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/30 hover:bg-brand-700 transition">
                Kirim Pengajuan Verifikasi
            </button>
        </div>
    </form>
</div>
@endsection
