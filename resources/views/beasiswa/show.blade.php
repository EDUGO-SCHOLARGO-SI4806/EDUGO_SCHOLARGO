<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $beasiswa->nama }} - ScholarGO</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --green:#047857; --green-btn:#059669; --navy:#0f2744; --bg:#f5f7fb; --muted:#6b7280; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:'Inter',system-ui,sans-serif; background:var(--bg); color:var(--navy); }
        a { text-decoration:none; color:inherit; }

        .nav { background:#fff; border-bottom:1px solid #e5e7eb; padding:16px 32px;
               display:flex; align-items:center; gap:32px; }
        .logo { display:flex; align-items:center; gap:8px; font-size:20px; color:var(--navy); }
        .logo .emo { font-size:24px; line-height:1; }
        .logo b { color:var(--green-btn); font-weight:400; }

        .wrap { max-width:900px; margin:32px auto; padding:0 32px 48px; }

        .back { display:inline-flex; align-items:center; gap:6px; font-size:14px; color:var(--muted);
                font-weight:500; margin-bottom:24px; }
        .back:hover { color:var(--navy); }

        .hero-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; }
        .hero-top { background:var(--green); color:#fff; padding:28px 32px; }
        .hero-top h1 { margin:0 0 8px; font-size:24px; font-weight:700; }
        .hero-meta { font-size:13px; opacity:.85; display:flex; flex-wrap:wrap; gap:16px; }
        .hero-meta span { display:flex; align-items:center; gap:5px; }

        .hero-body { padding:28px 32px; }

        .badge { display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; margin-right:8px; }
        .badge-green { background:#d1fae5; color:#065f46; }
        .badge-blue { background:#dbeafe; color:#1e40af; }
        .badge-yellow { background:#fef3c7; color:#92400e; }
        .badge-red { background:#fee2e2; color:#991b1b; }
        .badge-gray { background:#f3f4f6; color:#374151; }

        .section { margin-top:28px; }
        .section h3 { font-size:16px; font-weight:700; margin:0 0 12px; color:var(--navy);
                      padding-bottom:8px; border-bottom:2px solid #e5e7eb; }

        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .info-item { background:#f9fafb; border-radius:10px; padding:14px 16px; }
        .info-item label { display:block; font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
        .info-item span { font-size:14px; font-weight:600; color:var(--navy); }

        .deadline-banner { background:#fffbeb; border:1px solid #fcd34d; border-radius:12px; padding:16px 20px;
                           display:flex; align-items:center; gap:12px; margin-top:16px; }
        .deadline-banner .days { font-size:28px; font-weight:800; color:#b45309; }
        .deadline-banner p { margin:0; font-size:13px; color:#78350f; }

        .deskripsi { font-size:14px; line-height:1.7; color:#374151; }

        .cta { display:flex; gap:12px; margin-top:28px; }
        .btn-primary { background:var(--green-btn); color:#fff; font-weight:700; font-size:15px;
                       padding:14px 28px; border-radius:10px; border:none; cursor:pointer; }
        .btn-primary:hover { background:#047857; }
        .btn-outline { background:#fff; color:var(--navy); font-weight:600; font-size:14px;
                       padding:14px 24px; border-radius:10px; border:1px solid #d1d5db; }

        @media (max-width:600px) { .info-grid { grid-template-columns:1fr; } .cta { flex-direction:column; } }
    </style>
</head>
<body>

<header class="nav">
    <a href="{{ route('beasiswa.index') }}" class="logo">
        <span class="emo">🎓</span><span>Scholar<b>GO</b></span>
    </a>
</header>

<main class="wrap">
    <a href="{{ route('beasiswa.index') }}" class="back">← Kembali ke Daftar Beasiswa</a>

    <div class="hero-card">
        {{-- Header --}}
        <div class="hero-top">
            <h1>{{ $beasiswa->nama }}</h1>
            <div class="hero-meta">
                <span>🏛️ {{ $beasiswa->penyelenggara }}</span>
                @if($beasiswa->jenjang)
                    <span>🎓 {{ $beasiswa->jenjang }}</span>
                @endif
                @if($beasiswa->lokasi)
                    <span>📍 {{ $beasiswa->lokasi }}</span>
                @endif
            </div>
        </div>

        <div class="hero-body">
            {{-- Badges --}}
            <div>
                @if($beasiswa->jenis_pendanaan)
                    <span class="badge badge-green">{{ $beasiswa->jenis_pendanaan }}</span>
                @endif
                @if($beasiswa->kategori)
                    <span class="badge badge-blue">{{ $beasiswa->kategori }}</span>
                @endif
                @if($beasiswa->sisa_hari > 0)
                    <span class="badge badge-yellow">Sisa {{ $beasiswa->sisa_hari }} hari</span>
                @else
                    <span class="badge badge-red">Pendaftaran Ditutup</span>
                @endif
            </div>

            {{-- Info Grid --}}
            <div class="section">
                <h3>Informasi Beasiswa</h3>
                <div class="info-grid">
                    @if($beasiswa->nominal)
                        <div class="info-item">
                            <label>Nilai Beasiswa</label>
                            <span>Rp {{ number_format($beasiswa->nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($beasiswa->kuota)
                        <div class="info-item">
                            <label>Kuota Penerima</label>
                            <span>{{ $beasiswa->kuota }} orang</span>
                        </div>
                    @endif
                    @if($beasiswa->tanggal_buka)
                        <div class="info-item">
                            <label>Tanggal Buka</label>
                            <span>{{ $beasiswa->tanggal_buka->translatedFormat('d F Y') }}</span>
                        </div>
                    @endif
                    @if($beasiswa->tanggal_tutup)
                        <div class="info-item">
                            <label>Batas Pendaftaran</label>
                            <span>{{ $beasiswa->tanggal_tutup->translatedFormat('d F Y') }}</span>
                        </div>
                    @endif
                </div>

                @if($beasiswa->sisa_hari > 0 && $beasiswa->sisa_hari <= 30)
                    <div class="deadline-banner">
                        <div class="days">{{ $beasiswa->sisa_hari }}</div>
                        <p><strong>hari lagi</strong> sebelum pendaftaran ditutup.<br>Segera siapkan berkas-berkasmu!</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            @if($beasiswa->deskripsi)
                <div class="section">
                    <h3>Tentang Beasiswa</h3>
                    <p class="deskripsi">{!! nl2br(e($beasiswa->deskripsi)) !!}</p>
                </div>
            @endif

            {{-- Link Sumber --}}
            @if($beasiswa->link_sumber)
                <div class="section">
                    <h3>Sumber Resmi</h3>
                    <a href="{{ $beasiswa->link_sumber }}" target="_blank" rel="noopener" style="color:var(--green-btn);font-weight:600;">
                        🔗 Lihat Halaman Resmi Beasiswa
                    </a>
                </div>
            @endif

            {{-- CTA --}}
            <div class="cta">
                @if($beasiswa->link_sumber && $beasiswa->sisa_hari > 0)
                    <a href="{{ $beasiswa->link_sumber }}" target="_blank" class="btn-primary">Daftar Sekarang</a>
                @endif
                <a href="{{ route('beasiswa.index') }}" class="btn-outline">Lihat Beasiswa Lain</a>
            </div>
        </div>
    </div>
</main>

</body>
</html>
