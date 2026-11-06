<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tempat Sampah / Arsip Beasiswa – ScholarGO Admin">
    <title>Tempat Sampah Beasiswa – ScholarGO Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:          #1a7a4a;
            --brand-dark:     #155f39;
            --brand-light:    #e8f5ee;
            --bg-page:        #f0f2f5;
            --bg-card:        #ffffff;
            --border:         #e5e7eb;
            --border-hover:   #d1d5db;
            --text-primary:   #111827;
            --text-secondary: #6b7280;
            --text-muted:     #9ca3af;
            --success-bg:     #f0fdf4;
            --success-border: #22c55e;
            --success-text:   #15803d;
            --radius:         12px;
            --radius-sm:      8px;
            --radius-xs:      6px;
            --shadow-card:    0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.05);
            --transition:     .18s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem 1rem 5rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ── Breadcrumb ── */
        .breadcrumb {
            margin-bottom: 0.5rem;
            font-size: 0.78rem;
            color: var(--text-secondary);
        }
        .breadcrumb a { color: var(--text-secondary); text-decoration: none; }
        .breadcrumb a:hover { color: var(--brand); }
        .breadcrumb span { margin: 0 0.3rem; }

        /* ── Header ── */
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .page-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* ── Banner Info ── */
        .info-banner {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 0.85rem 1.15rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* ── Button ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.15rem;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            font-family: inherit;
        }
        .btn-ghost {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-primary);
        }
        .btn-ghost:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }
        .btn-restore {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 0.38rem 0.75rem;
            border-radius: var(--radius-xs);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-restore:hover {
            background: #10b981;
            color: #ffffff;
            border-color: #10b981;
        }
        .btn-force-delete {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 0.38rem 0.75rem;
            border-radius: var(--radius-xs);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-force-delete:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        /* ── Alert ── */
        .alert {
            border-radius: var(--radius-sm);
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: var(--success-bg);
            border-color: var(--success-border);
            color: var(--success-text);
        }

        /* ── Card ── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        /* ── Table ── */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            text-align: left;
        }
        thead {
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
        }
        th {
            padding: 0.85rem 1.15rem;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background var(--transition);
        }
        tbody tr:last-child {
            border-bottom: none;
        }
        tbody tr:hover {
            background: #fafbfb;
        }
        td {
            padding: 0.95rem 1.15rem;
            vertical-align: middle;
            color: var(--text-primary);
        }

        /* ── Poster image ── */
        .poster-thumb {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-xs);
            object-fit: cover;
            border: 1px solid var(--border);
            background: #f3f4f6;
        }
        .no-poster {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-xs);
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: var(--text-muted);
            text-align: center;
            border: 1px dashed var(--border-hover);
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1.5rem;
            color: var(--text-secondary);
        }
        .empty-state p {
            margin-bottom: 1.25rem;
            font-size: 0.92rem;
        }

        /* ── Pagination ── */
        .pagination-container {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            background: #fafbfb;
        }
    </style>
</head>
<body>

    <div class="container">
        {{-- Breadcrumb --}}
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="#">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.scholarships.index') }}">Beasiswa</a>
            <span>/</span>
            <span>Tempat Sampah</span>
        </nav>

        {{-- Header Section --}}
        <div class="header-section">
            <h1 class="page-title">
                <span>🗑️ Tempat Sampah Beasiswa</span>
            </h1>
            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-ghost">
                &larr; Kembali ke Daftar Beasiswa
            </a>
        </div>

        {{-- Info Banner --}}
        <div class="info-banner">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>
                Data di bawah ini adalah beasiswa yang telah diarsipkan (soft delete). Anda dapat <strong>Memulihkan</strong> data agar kembali aktif, atau <strong>Hapus Permanen</strong> jika sudah tidak dibutuhkan.
            </span>
        </div>

        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <span style="font-weight:700;">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 60px;">Poster</th>
                            <th>Nama Beasiswa</th>
                            <th>Penyelenggara</th>
                            <th>Jenjang</th>
                            <th>Waktu Dihapus</th>
                            <th style="text-align: center; width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scholarships as $index => $scholarship)
                            <tr>
                                <td>
                                    {{ method_exists($scholarships, 'firstItem') ? $scholarships->firstItem() + $index : $index + 1 }}
                                </td>
                                <td>
                                    @if ($scholarship->poster)
                                        <img src="{{ asset('storage/' . $scholarship->poster) }}" alt="Poster" class="poster-thumb">
                                    @else
                                        <div class="no-poster">No Pic</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $scholarship->nama }}</strong>
                                </td>
                                <td>
                                    <span style="color: var(--text-secondary);">{{ $scholarship->penyelenggara ?? '-' }}</span>
                                </td>
                                <td>
                                    <span style="font-size:0.8rem; font-weight:600; color:var(--brand);">{{ $scholarship->jenjang }}</span>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--text-secondary);">
                                    {{ $scholarship->deleted_at ? $scholarship->deleted_at->format('d M Y, H:i') : '-' }}
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">
                                        ({{ $scholarship->deleted_at?->diffForHumans() }})
                                    </div>
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    {{-- Form Restore / Pulihkan --}}
                                    <form method="POST"
                                          action="{{ route('admin.scholarships.restore', $scholarship->id) }}"
                                          style="display: inline;"
                                          onsubmit="return confirm('Pulihkan beasiswa \"{{ addslashes($scholarship->nama) }}\" kembali ke daftar aktif?')">
                                        @csrf
                                        <button type="submit" class="btn-restore" title="Kembalikan ke daftar beasiswa aktif">
                                            ♻️ Pulihkan
                                        </button>
                                    </form>

                                    {{-- Form Force Delete / Hapus Permanen --}}
                                    <form method="POST"
                                          action="{{ route('admin.scholarships.forceDelete', $scholarship->id) }}"
                                          style="display: inline; margin-left: 6px;"
                                          onsubmit="return confirm('⚠️ PERINGATAN KERAS:\n\nBeasiswa \"{{ addslashes($scholarship->nama) }}\" beserta file posternya akan DIHAPUS PERMANEN dari database dan server!\n\nTindakan ini TIDAK DAPAT dibatalkan. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-force-delete" title="Hapus selamanya dari database">
                                            ❌ Hapus Permanen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div style="font-size:2rem; margin-bottom:0.5rem;">🎉</div>
                                        <p>Tempat sampah kosong. Tidak ada beasiswa yang diarsipkan saat ini.</p>
                                        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-ghost">
                                            Kembali ke Daftar Beasiswa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($scholarships, 'hasPages') && $scholarships->hasPages())
                <div class="pagination-container">
                    {{ $scholarships->links() }}
                </div>
            @endif
        </div>
    </div>

</body>
</html>
