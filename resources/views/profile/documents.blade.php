@extends('layouts.app')
@section('title', 'Upload Dokumen Profil - ScholarGo')

@section('content')
<div class="animate-in">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" style="color: var(--sg-primary);">Profil</a></li>
            <li class="breadcrumb-item active">Dokumen Profil</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Form Upload --}}
        <div class="col-md-5">
            <div class="sg-card">
                <div class="card-header">
                    <i class="bi bi-cloud-upload me-2" style="color: var(--sg-primary);"></i>
                    Upload Dokumen
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.documents.store') }}" enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <div class="sg-form-group">
                            <label for="document_type">Jenis Dokumen</label>
                            <select id="document_type" name="document_type" class="form-select sg-form-control @error('document_type') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Dokumen --</option>
                                @foreach($documentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sg-form-group">
                            <label>File Dokumen</label>
                            <div class="upload-area" id="uploadArea" onclick="document.getElementById('document').click();">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <p class="fw-semibold mb-1" id="uploadText">Klik atau seret file ke sini</p>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">Format: PDF, JPG, JPEG, PNG (Maks. 2 MB)</p>
                            </div>
                            <input type="file" id="document" name="document" class="d-none @error('document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                            @error('document')
                                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sg-primary w-100">
                            <i class="bi bi-upload me-1"></i> Upload Dokumen
                        </button>
                    </form>

                    <div class="mt-3 p-3" style="background: var(--sg-primary-light); border-radius: 12px;">
                        <h6 class="fw-bold mb-2" style="font-size: 0.85rem; color: var(--sg-primary);">
                            <i class="bi bi-info-circle me-1"></i> Ketentuan Upload
                        </h6>
                        <ul class="mb-0" style="font-size: 0.8rem; color: var(--sg-text-light); padding-left: 1rem;">
                            <li>Format file: PDF, JPG, JPEG, atau PNG</li>
                            <li>Ukuran maksimal: 2 MB per file</li>
                            <li>Upload ulang jenis yang sama akan mengganti file sebelumnya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Dokumen --}}
        <div class="col-md-7">
            <div class="sg-card">
                <div class="card-header">
                    <i class="bi bi-file-earmark-text me-2" style="color: var(--sg-primary);"></i>
                    Dokumen Tersimpan ({{ $documents->count() }})
                </div>
                <div class="card-body">
                    @if($documents->count() > 0)
                        @foreach($documents as $doc)
                            <div class="doc-item">
                                <div class="doc-icon {{ strtolower($doc->file_extension) }}">
                                    @if(strtolower($doc->file_extension) === 'pdf')
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    @else
                                        <i class="bi bi-file-earmark-image"></i>
                                    @endif
                                </div>
                                <div class="doc-info">
                                    <div class="doc-name">{{ $doc->document_type_label }}</div>
                                    <div class="doc-meta">
                                        {{ $doc->original_name }} • {{ $doc->file_size_formatted }} • {{ $doc->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('profile.documents.download', $doc->id) }}" class="btn btn-sm btn-sg-outline" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form method="POST" action="{{ route('profile.documents.destroy', $doc->id) }}" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-sg-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder2-open" style="font-size: 3.5rem; color: var(--sg-border);"></i>
                            <p class="mt-2 text-muted">Belum ada dokumen yang diunggah</p>
                            <p class="text-muted" style="font-size: 0.85rem;">Upload KTM, transkrip, atau foto profil Anda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateFileName(input) {
    const uploadText = document.getElementById('uploadText');
    const uploadArea = document.getElementById('uploadArea');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (file.size > maxSize) {
            alert('Ukuran file melebihi 2 MB. Silakan pilih file yang lebih kecil.');
            input.value = '';
            uploadText.textContent = 'Klik atau seret file ke sini';
            uploadArea.style.borderColor = '#e2e8f0';
            return;
        }

        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, JPG, JPEG, atau PNG.');
            input.value = '';
            uploadText.textContent = 'Klik atau seret file ke sini';
            uploadArea.style.borderColor = '#e2e8f0';
            return;
        }

        uploadText.textContent = file.name;
        uploadArea.style.borderColor = '#0d6b5e';
        uploadArea.style.background = '#e8f5f2';
    }
}

// Drag and drop support
const uploadArea = document.getElementById('uploadArea');
const documentInput = document.getElementById('document');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.style.borderColor = '#0d6b5e';
        uploadArea.style.background = '#e8f5f2';
    });
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f5f7fa';
    });
});

uploadArea.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files.length) {
        documentInput.files = files;
        updateFileName(documentInput);
    }
});
</script>
@endpush
