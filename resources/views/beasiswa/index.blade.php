<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ScholarGO - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --green:#047857; --green-btn:#059669; --navy:#0f2744; --bg:#f5f7fb; --muted:#6b7280; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:'Inter',system-ui,sans-serif; background:var(--bg); color:var(--navy); }
        a { text-decoration:none; color:inherit; }

        /*navbar*/
        .nav { background:#fff; border-bottom:1px solid #e5e7eb; padding:16px 32px;
               display:flex; align-items:center; gap:32px; }
        .logo { display:flex; align-items:center; gap:8px; font-size:20px; color:var(--navy); }
        .logo .emo { font-size:24px; line-height:1; }
        .logo b { color:var(--green-btn); font-weight:400; }
        .search { flex:0 1 296px; margin:0 auto 0 80px; }
        .search input { width:100%; border:0; background:#f1f4f8; border-radius:8px; padding:11px 14px; font:inherit; font-size:14px; outline:none; }
        .menu { display:flex; align-items:center; gap:30px; }
        .menu a { font-size:14px; font-weight:500; color:var(--navy); }
        .menu a.on { color:var(--green-btn); }
        .avatar { width:32px; height:32px; border-radius:50%; background:var(--green); color:#fff;
                  display:flex; align-items:center; justify-content:center; font-size:14px; }

        .wrap { max-width:1216px; margin:0 auto; padding:24px 32px 48px; }

        /*heroo*/
        .hero { background:var(--green); color:#fff; border-radius:16px; padding:24px; }
        .hero h1 { margin:0 0 6px; font-size:26px; font-weight:500; }
        .hero p { margin:0 0 20px; font-size:13px; opacity:.9; }
        .filters { display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
        .sel { background:#fff; color:var(--navy); border-radius:8px; padding:8px 14px; width:220px; }
        .sel small { display:block; font-size:10px; color:var(--muted); }
        .sel select { width:100%; border:0; background:transparent; font:inherit; font-size:13px; font-weight:500; outline:none; padding:2px 0; }
        .btn-cari { background:var(--navy); color:#fff; border:0; border-radius:8px; height:50px; padding:0 28px;
                    font:inherit; font-size:14px; font-weight:600; cursor:pointer; }
        .count { font-size:13px; opacity:.9; }

        /*heading*/
        .head { display:flex; justify-content:space-between; align-items:flex-end; margin:28px 0 16px; }
        .head h2 { margin:0; font-size:20px; }
        .head p { margin:4px 0 0; font-size:12px; color:var(--muted); }
        .sort { font-size:12px; color:var(--muted); }
        .sort select { border:0; background:transparent; font:inherit; font-weight:600; color:var(--navy); cursor:pointer; outline:none; }

        /*card*/
        .grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; display:flex; flex-direction:column; }
        .org { display:flex; align-items:center; gap:10px; font-size:12px; color:var(--muted); }
        .org span.ic { width:36px; height:36px; background:#eef2f9; border-radius:8px; display:flex; align-items:center; justify-content:center; }
        .card h3 { margin:16px 0 28px; font-size:18px; }
        .row { display:flex; justify-content:space-between; align-items:center; }
        .badge { background:#e7f6ef; color:var(--green); font-size:11px; font-weight:600; padding:5px 10px; border-radius:999px; }
        .sisa { font-size:11px; font-weight:600; color:#b45309; }
        .meta { font-size:11px; color:var(--muted); }
        .btn-detail { background:var(--green-btn); color:#fff; font-weight:600; font-size:14px; padding:12px 16px; border-radius:8px; }
        .card .row + .row { margin-top:14px; }

        .empty { background:#fff; border:1px dashed #cbd5e1; border-radius:12px; padding:48px; text-align:center; color:var(--muted); grid-column:1/-1; }

        /*buat searcg */
        .result-info { display:flex; justify-content:space-between; align-items:center; background:#fff; border:1px solid #e5e7eb;
                       border-radius:10px; padding:12px 16px; margin-bottom:16px; font-size:13px; }
        .result-info b { color:var(--green); }
        .result-info a { color:var(--green-btn); font-weight:600; }
        .empty h4 { margin:0 0 6px; font-size:16px; color:var(--navy); }
        .empty p { margin:0 0 14px; font-size:13px; }
        .empty a { display:inline-block; background:var(--green-btn); color:#fff; font-weight:600; font-size:13px; padding:10px 16px; border-radius:8px; }

        .foot { margin-top:24px; font-size:12px; color:var(--muted); }

        @media (min-width: 1600px) { body { zoom: 1.2; } }
        @media (max-width: 900px) { .grid { grid-template-columns:repeat(2,1fr); } .search { display:none; } }
        @media (max-width: 600px) { .grid { grid-template-columns:1fr; } .menu { display:none; } .sel { width:100%; } }
    </style>
</head>
<body>
@php
    $nama = auth()->user()->name ?? 'Fira';
@endphp

<header class="nav">
    <a href="{{ route('beasiswa.index') }}" class="logo"><span class="emo">🎓</span><span>Scholar<b>GO</b></span></a>

    <div class="search">
        <input form="filter-form" type="search" name="q" value="{{ $keyword }}"
               maxlength="100" placeholder="Cari Beasiswa" autocomplete="off">
    </div>

    <nav class="menu">
        <a href="{{ route('beasiswa.index') }}" class="on">Beranda</a>
        <a href="#">Pendaftaranku</a>
        <a href="#">Notifikasi</a>
        <span class="avatar">{{ strtoupper(substr($nama, 0, 1)) }}</span>
    </nav>
</header>

<main class="wrap">
    <form id="filter-form" method="GET" action="{{ route('beasiswa.index') }}">
        <section class="hero">
            <h1>Temukan &amp; Daftar Beasiswa Impianmu dengan Mudah</h1>
            <p>Halo, {{ $nama }}! Langkah besar masa depanmu dimulai dari sini.</p>

            <div class="filters">
                <label class="sel">
                    <small>Batas Waktu</small>
                    <select name="batas_waktu">
                        <option value="">Semua batas waktu</option>
                        @foreach ([7 => '7 hari ke depan', 14 => '14 hari ke depan', 30 => '30 hari ke depan'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('batas_waktu') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="sel">
                    <small>Jenjang</small>
                    <select name="jenjang">
                        <option value="">Semua jenjang</option>
                        @foreach (['D3', 'D4', 'S1', 'S2', 'S3'] as $j)
                            <option value="{{ $j }}" @selected(request('jenjang') == $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="sel">
                    <small>Jenis Pendanaan</small>
                    <select name="pendanaan">
                        <option value="">Semua pendanaan</option>
                        @foreach (['Pendanaan Penuh', 'Pendanaan Parsial'] as $p)
                            <option value="{{ $p }}" @selected(request('pendanaan') == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </label>

                <button type="submit" class="btn-cari">Cari Beasiswa</button>
                <span class="count">{{ $beasiswas->total() }} peluang tersedia</span>
            </div>
        </section>

        <div class="head">
            <div>
                <h2>Pilihan beasiswa untukmu</h2>
                <p>Temukan dukungan yang sesuai dengan mimpi dan potensimu.</p>
            </div>
            <label class="sort">Urutkan:
                <select name="urutan" onchange="this.form.submit()">
                    <option value="" @selected(!request('urutan'))>Paling relevan</option>
                    <option value="deadline" @selected(request('urutan') == 'deadline')>Deadline terdekat</option>
                    <option value="terbaru" @selected(request('urutan') == 'terbaru')>Terbaru</option>
                </select>
            </label>
        </div>
    </form>

    @if ($keyword !== '')
        <div class="result-info">
            <span>
                Hasil pencarian untuk <b>"{{ $keyword }}"</b>:
                {{ $beasiswas->total() }} beasiswa ditemukan
            </span>
            <a href="{{ route('beasiswa.index') }}">Reset pencarian</a>
        </div>
    @endif

    <div class="grid">
        @forelse ($beasiswas as $b)
            <article class="card">
                <div class="org">
                    <span class="ic">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0f2744" stroke-width="1.6">
                            <circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>
                        </svg>
                    </span>
                    {{ $b->penyelenggara }}
                </div>

                <h3>{{ $b->nama }}</h3>

                <div class="row">
                    <span class="badge">{{ $b->jenis_pendanaan }}</span>
                    <span class="sisa">Sisa {{ $b->sisa_hari }} hari</span>
                </div>

                <div class="row">
                    <span class="meta">{{ $b->jenjang }} · {{ $b->lokasi }}</span>
                    <a href="#" class="btn-detail">Lihat Detail</a>
                </div>
            </article>
        @empty
            <div class="empty">
                @if ($keyword !== '')
                    <h4>Beasiswa "{{ $keyword }}" tidak ditemukan</h4>
                    <p>Coba kata kunci lain, periksa ejaan, atau kurangi filter yang aktif.</p>
                @else
                    <h4>Belum ada beasiswa yang tersedia</h4>
                    <p>Silakan cek kembali nanti.</p>
                @endif
                <a href="{{ route('beasiswa.index') }}">Lihat semua beasiswa</a>
            </div>
        @endforelse
    </div>

    <div class="foot">
        @if ($beasiswas->total())
            Menampilkan {{ $beasiswas->firstItem() }}–{{ $beasiswas->lastItem() }} dari {{ $beasiswas->total() }} beasiswa
        @endif
    </div>
</main>
</body>
</html>