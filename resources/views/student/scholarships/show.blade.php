<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $scholarship->name }} - ScholarGO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-link px-0">&larr; Kembali</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3">{{ $scholarship->name }}</h1>

            @if($scholarship->end_date)
                @php $sisa = (int) today()->diffInDays($scholarship->end_date, false); @endphp
                <span class="badge {{ $sisa >= 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                    {{ $sisa >= 0 ? "Sisa $sisa hari" : 'Pendaftaran ditutup' }}
                </span>
            @endif

            <p class="text-muted mt-2">
                Penyelenggara: {{ $scholarship->organizer_name }}
                &middot; Jenjang: {{ $scholarship->level }}
                @if($scholarship->category) &middot; Kategori: {{ $scholarship->category }} @endif
            </p>

            <h4 class="h5">Deskripsi</h4>
            <p>{!! nl2br(e($scholarship->description)) !!}</p>

            <hr>

            <h4 class="h5">Persyaratan</h4>
            @if($scholarship->requirement)
                <ul>
                    <li>IPK minimum: {{ number_format($scholarship->requirement->min_gpa, 2) }}</li>
                    <li>Semester minimum: {{ $scholarship->requirement->min_semester }}</li>
                </ul>
            @else
                <p class="text-muted">Belum ada persyaratan kualifikasi.</p>
            @endif

            <h4 class="h5">Dokumen Wajib</h4>
            @if($scholarship->requiredDocuments->isEmpty())
                <p class="text-muted">Belum ada daftar dokumen.</p>
            @else
                <ul class="list-unstyled">
                    @foreach($scholarship->requiredDocuments as $doc)
                        <li class="mb-2">
                            <strong>{{ $doc->document_name }}</strong>
                            <span class="badge {{ $doc->is_mandatory ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $doc->is_mandatory ? 'Wajib' : 'Opsional' }}
                            </span>
                            <span class="text-muted small">({{ $doc->file_format }})</span>
                            @if($doc->description)
                                <div class="text-muted small">{{ $doc->description }}</div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <h4 class="h5">Periode Pendaftaran</h4>
            @if($periodeStatus === 'belum_ditentukan')
                <p class="text-muted">Periode pendaftaran belum ditentukan.</p>
            @else
                <p>
                    {{ $scholarship->start_date->translatedFormat('d F Y') }}
                    s/d
                    {{ $scholarship->end_date->translatedFormat('d F Y') }}
                    @if($periodeStatus === 'dibuka')
                        <span class="badge bg-success">Dibuka</span>
                    @elseif($periodeStatus === 'belum_dibuka')
                        <span class="badge bg-info text-dark">Belum dibuka</span>
                    @else
                        <span class="badge bg-secondary">Ditutup</span>
                    @endif
                </p>
            @endif

            {{-- PBI-22 (cek kesesuaian) ditambahkan di sini --}}
        </div>
    </div>
</div>
</body>
</html>