@extends('layouts.admin')

@section('title', 'Daftar Dokumen Wajib - ' . $scholarship->name)
@section('breadcrumb', 'Beasiswa Saya / Daftar Dokumen Wajib')

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
                    PBI-14 • ED-14
                </span>
                <h2 class="text-2xl font-bold text-gray-900">Menentukan Daftar Dokumen Wajib</h2>
            </div>
            <p class="text-sm text-gray-500 mt-1">Atur daftar berkas persyaratan yang wajib maupun opsional disiapkan oleh mahasiswa saat mendaftar.</p>
        </div>
    </div>

    <!-- Flash Message Success -->
    @if(session('success'))
        <div class="bg-brand-50 border border-brand-200 text-brand-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-brand-100 rounded-full text-brand-700 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Pembaruan Daftar Dokumen Berhasil</p>
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

    <!-- Info Banner Beasiswa -->
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold">Program Beasiswa Terpilih</p>
            <h3 class="text-xl font-bold mt-1">{{ $scholarship->name }}</h3>
            <p class="text-xs text-gray-300 mt-1 flex flex-wrap items-center gap-2">
                <span>Penyelenggara: <strong>{{ $scholarship->organizer }}</strong></span>
                <span>•</span>
                <span>Total Berkas: <strong>{{ $scholarship->requiredDocuments->count() }} Dokumen ({{ $scholarship->requiredDocuments->where('is_mandatory', true)->count() }} Wajib)</strong></span>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('requirements.edit', $scholarship->id) }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-semibold border border-white/20 transition">
                Kriteria IPK & Semester (PBI-13)
            </a>
            <a href="{{ route('scholarships.requirements', ['id' => $scholarship->id, 'tab' => 'dokumen']) }}" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>Preview Tab Dokumen Mahasiswa</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri (1 Col): Form Tambah Dokumen Wajib -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 h-fit space-y-5">
            <div>
                <h3 class="text-base font-bold text-gray-900">Tambah Dokumen Persyaratan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pilih dari template cepat atau ketik nama berkas baru.</p>
            </div>

            <form action="{{ route('requirements.documents.store', $scholarship->id) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Template Cepat (Quick Preset) -->
                <div>
                    <label for="preset_selector" class="block text-xs font-semibold text-gray-600 mb-1">
                        Pilih Cepat Jenis Dokumen Umum <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <select id="preset_selector" onchange="applyPreset(this)" class="w-full px-3.5 py-2 rounded-xl border border-gray-200 bg-gray-50 text-xs text-gray-700 focus:border-brand-500 focus:outline-none">
                        <option value="">-- Ketik Manual atau Pilih Template --</option>
                        @foreach($presetDocuments as $presetName => $presetDesc)
                            <option value="{{ $presetName }}" data-desc="{{ $presetDesc }}">{{ $presetName }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Nama Dokumen -->
                <div>
                    <label for="document_name" class="block text-xs font-semibold text-gray-800 mb-1">
                        Nama Dokumen Persyaratan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="document_name"
                        name="document_name"
                        value="{{ old('document_name') }}"
                        placeholder="Contoh: Transkrip Nilai Akademik"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition"
                    >
                </div>

                <!-- Input Keterangan / Deskripsi -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-800 mb-1">
                        Instruksi / Keterangan Berkas
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Contoh: Disahkan oleh pihak kampus dan terbaca jelas."
                        class="w-full px-3.5 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition resize-none"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Format Berkas & Sifat Dokumen -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="file_format" class="block text-xs font-semibold text-gray-800 mb-1">
                            Format File <span class="text-red-500">*</span>
                        </label>
                        <select id="file_format" name="file_format" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:border-brand-500 focus:outline-none">
                            <option value="PDF, JPG" {{ old('file_format') === 'PDF, JPG' ? 'selected' : '' }}>PDF, JPG (Maks 2MB)</option>
                            <option value="PDF" {{ old('file_format') === 'PDF' ? 'selected' : '' }}>Hanya PDF (Maks 2MB)</option>
                            <option value="JPG" {{ old('file_format') === 'JPG' ? 'selected' : '' }}>Hanya JPG (Maks 2MB)</option>
                        </select>
                    </div>

                    <div>
                        <label for="is_mandatory" class="block text-xs font-semibold text-gray-800 mb-1">
                            Sifat Berkas <span class="text-red-500">*</span>
                        </label>
                        <select id="is_mandatory" name="is_mandatory" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:border-brand-500 focus:outline-none">
                            <option value="1" {{ old('is_mandatory', '1') == '1' ? 'selected' : '' }}>Wajib Diunggah</option>
                            <option value="0" {{ old('is_mandatory') == '0' ? 'selected' : '' }}>Opsional / Pendukung</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-md shadow-brand-600/30 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Tambahkan Dokumen</span>
                </button>
            </form>
        </div>

        <!-- Kolom Kanan (2 Cols): Daftar Dokumen Wajib Saat Ini -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Daftar Dokumen Persyaratan Beasiswa</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Daftar berkas ini akan tampil otomatis pada halaman informasi dan pendaftaran mahasiswa.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                        {{ $scholarship->requiredDocuments->count() }} Berkas Terdaftar
                    </span>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($scholarship->requiredDocuments as $index => $doc)
                        <div class="p-5 hover:bg-gray-50/80 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl {{ $doc->is_mandatory ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200' }} border flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $doc->document_name }}</h4>
                                        @if($doc->is_mandatory)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                WAJIB
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                OPSIONAL
                                            </span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                            Format: {{ $doc->file_format }} • Maks. 2 MB
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        {{ $doc->description ?? 'Pastikan dokumen terlihat jelas dan sesuai dengan ketentuan beasiswa.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Aksi Toggle Wajib/Opsional & Hapus -->
                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                <form action="{{ route('requirements.documents.toggle', [$scholarship->id, $doc->id]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-medium transition" title="Ubah status Wajib / Opsional">
                                        Set {{ $doc->is_mandatory ? 'Opsional' : 'Wajib' }}
                                    </button>
                                </form>

                                <form action="{{ route('requirements.documents.destroy', [$scholarship->id, $doc->id]) }}" method="POST" onsubmit="return confirm('Hapus dokumen {{ $doc->document_name }} dari persyaratan beasiswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Dokumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Belum Ada Dokumen Persyaratan</p>
                            <p class="text-xs text-gray-400 max-w-sm mx-auto">
                                Gunakan formulir di sebelah kiri untuk menambahkan dokumen wajib atau opsional bagi calon pendaftar beasiswa ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Info NFR-05 -->
            <div class="p-4 bg-gray-50 border-t border-gray-100 text-xs text-gray-500 flex items-center justify-between">
                <span>Ketentuan Sistem (NFR-05): Setiap berkas dibatasi maksimal <strong>2 MB</strong> (PDF/JPG).</span>
                <a href="{{ route('requirements.index') }}" class="text-brand-600 font-semibold hover:underline">Selesai & Kembali</a>
            </div>
        </div>

    </div>

</div>

<script>
    function applyPreset(selectEl) {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const docNameInput = document.getElementById('document_name');
        const descInput = document.getElementById('description');

        if (selectEl.value) {
            docNameInput.value = selectEl.value;
            descInput.value = selectedOption.getAttribute('data-desc') || '';
        }
    }
</script>
@endsection
