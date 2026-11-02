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

            {{-- PBI-21 (persyaratan, dokumen, periode) dan PBI-22 (cek kesesuaian) ditambahkan di sini --}}
        </div>
    </div>
</div>
</body>
</html>