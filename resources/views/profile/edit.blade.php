@extends('layouts.app')
@section('title', 'Edit Profil - ScholarGo')

@section('content')
<div class="animate-in">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" style="color: var(--sg-primary);">Profil</a></li>
            <li class="breadcrumb-item active">Edit Profil</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="sg-card">
                <div class="card-header">
                    <i class="bi bi-pencil-square me-2" style="color: var(--sg-primary);"></i>
                    Edit Data Profil & Akademik
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Data Pribadi Section --}}
                        <h6 class="fw-bold mb-3" style="color: var(--sg-primary);">
                            <i class="bi bi-person me-1"></i> Data Pribadi
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="phone">No. HP</label>
                                    <input type="text" id="phone" name="phone" class="form-control sg-form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone) }}" placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control sg-form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $profile->tanggal_lahir?->format('Y-m-d')) }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="sg-form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control sg-form-control @error('alamat') is-invalid @enderror" rows="2" placeholder="Alamat lengkap">{{ old('alamat', $profile->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Data Akademik Section --}}
                        <h6 class="fw-bold mb-3" style="color: var(--sg-primary);">
                            <i class="bi bi-mortarboard me-1"></i> Data Akademik
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" id="nim" name="nim" class="form-control sg-form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $profile->nim) }}" placeholder="Nomor Induk Mahasiswa">
                                    @error('nim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="kampus">Kampus</label>
                                    <input type="text" id="kampus" name="kampus" class="form-control sg-form-control @error('kampus') is-invalid @enderror" value="{{ old('kampus', $profile->kampus) }}" placeholder="Nama kampus/universitas">
                                    @error('kampus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="program_studi">Program Studi</label>
                                    <input type="text" id="program_studi" name="program_studi" class="form-control sg-form-control @error('program_studi') is-invalid @enderror" value="{{ old('program_studi', $profile->program_studi) }}" placeholder="Nama program studi">
                                    @error('program_studi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="jenjang">Jenjang</label>
                                    <select id="jenjang" name="jenjang" class="form-select sg-form-control @error('jenjang') is-invalid @enderror">
                                        <option value="">-- Pilih Jenjang --</option>
                                        @foreach(['D3', 'D4', 'S1', 'S2', 'S3'] as $j)
                                            <option value="{{ $j }}" {{ old('jenjang', $profile->jenjang) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenjang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="semester">Semester</label>
                                    <input type="number" id="semester" name="semester" class="form-control sg-form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $profile->semester) }}" min="1" max="14" placeholder="1-14">
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sg-form-group">
                                    <label for="ipk">IPK</label>
                                    <input type="number" id="ipk" name="ipk" class="form-control sg-form-control @error('ipk') is-invalid @enderror" value="{{ old('ipk', $profile->ipk) }}" step="0.01" min="0" max="4" placeholder="0.00 - 4.00">
                                    @error('ipk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Masukkan IPK dalam rentang 0.00 - 4.00</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-sg-outline">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-sg-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
