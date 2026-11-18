@extends('layouts.app')
@section('title', 'Profil Saya - ScholarGo')

@section('content')
<div class="animate-in">
    {{-- Header Profil --}}
    <div class="profile-header">
        <div class="d-flex align-items-center gap-4">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="mb-1" style="font-weight: 700;">{{ $user->name }}</h3>
                <p class="mb-0 opacity-75"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>
                @if($profile->kampus)
                    <p class="mb-0 opacity-75"><i class="bi bi-building me-1"></i> {{ $profile->kampus }}</p>
                @endif
            </div>
        </div>
        <div class="mt-3">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size: 0.85rem; opacity: 0.8;">Kelengkapan Profil</span>
                <span class="fw-bold">{{ $completionPercentage }}%</span>
            </div>
            <div class="progress mt-1" style="height: 6px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage }}%; background: #48bb78; border-radius: 10px;" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Data Pribadi --}}
        <div class="col-md-6">
            <div class="sg-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person-vcard me-2" style="color: var(--sg-primary);"></i>Data Pribadi</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sg-outline btn-sm">
                        <i class="bi bi-pencil me-1"></i> Ubah
                    </a>
                </div>
                <div class="card-body">
                    <div class="profile-info-item">
                        <span class="profile-info-label">Nama Lengkap</span>
                        <span class="profile-info-value">{{ $user->name }}</span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Email</span>
                        <span class="profile-info-value">{{ $user->email }}</span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">No. HP</span>
                        <span class="profile-info-value {{ $profile->phone ? '' : 'empty' }}">
                            {{ $profile->phone ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Tanggal Lahir</span>
                        <span class="profile-info-value {{ $profile->tanggal_lahir ? '' : 'empty' }}">
                            {{ $profile->tanggal_lahir ? $profile->tanggal_lahir->format('d F Y') : 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Alamat</span>
                        <span class="profile-info-value {{ $profile->alamat ? '' : 'empty' }}">
                            {{ $profile->alamat ?? 'Belum diisi' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Akademik --}}
        <div class="col-md-6">
            <div class="sg-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-mortarboard me-2" style="color: var(--sg-primary);"></i>Data Akademik</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sg-outline btn-sm">
                        <i class="bi bi-pencil me-1"></i> Ubah
                    </a>
                </div>
                <div class="card-body">
                    <div class="profile-info-item">
                        <span class="profile-info-label">NIM</span>
                        <span class="profile-info-value {{ $profile->nim ? '' : 'empty' }}">
                            {{ $profile->nim ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Kampus</span>
                        <span class="profile-info-value {{ $profile->kampus ? '' : 'empty' }}">
                            {{ $profile->kampus ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Program Studi</span>
                        <span class="profile-info-value {{ $profile->program_studi ? '' : 'empty' }}">
                            {{ $profile->program_studi ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Jenjang</span>
                        <span class="profile-info-value {{ $profile->jenjang ? '' : 'empty' }}">
                            {{ $profile->jenjang ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">Semester</span>
                        <span class="profile-info-value {{ $profile->semester ? '' : 'empty' }}">
                            {{ $profile->semester ? 'Semester ' . $profile->semester : 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="profile-info-item">
                        <span class="profile-info-label">IPK</span>
                        <span class="profile-info-value {{ $profile->ipk ? '' : 'empty' }}">
                            @if($profile->ipk)
                                <span class="sg-badge {{ $profile->ipk >= 3.5 ? 'sg-badge-success' : ($profile->ipk >= 3.0 ? 'sg-badge-info' : 'sg-badge-warning') }}">
                                    {{ number_format($profile->ipk, 2) }}
                                </span>
                            @else
                                Belum diisi
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dokumen Profil --}}
        <div class="col-12">
            <div class="sg-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text me-2" style="color: var(--sg-primary);"></i>Dokumen Profil</span>
                    <a href="{{ route('profile.documents') }}" class="btn btn-sg-primary btn-sm">
                        <i class="bi bi-cloud-upload me-1"></i> Upload Dokumen
                    </a>
                </div>
                <div class="card-body">
                    @if($user->profileDocuments->count() > 0)
                        @foreach($user->profileDocuments as $doc)
                            <div class="doc-item">
                                <div class="doc-icon {{ strtolower($doc->file_extension) }}">
                                    <i class="bi bi-file-earmark-{{ strtolower($doc->file_extension) === 'pdf' ? 'pdf' : 'image' }}"></i>
                                </div>
                                <div class="doc-info">
                                    <div class="doc-name">{{ $doc->document_type_label }}</div>
                                    <div class="doc-meta">{{ $doc->original_name }} • {{ $doc->file_size_formatted }} • {{ $doc->created_at->format('d M Y') }}</div>
                                </div>
                                <div>
                                    <a href="{{ route('profile.documents.download', $doc->id) }}" class="btn btn-sm btn-sg-outline">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-folder2-open" style="font-size: 3rem; color: var(--sg-border);"></i>
                            <p class="mt-2 text-muted">Belum ada dokumen yang diunggah</p>
                            <a href="{{ route('profile.documents') }}" class="btn btn-sg-primary btn-sm">
                                <i class="bi bi-cloud-upload me-1"></i> Upload Dokumen Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
