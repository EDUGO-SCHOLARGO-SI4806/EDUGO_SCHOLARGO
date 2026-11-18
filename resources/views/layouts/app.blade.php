<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ScholarGo')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sg-primary: #0d6b5e;
            --sg-primary-dark: #0a5549;
            --sg-primary-light: #e8f5f2;
            --sg-secondary: #1a3a5c;
            --sg-accent: #f0a500;
            --sg-bg: #f5f7fa;
            --sg-white: #ffffff;
            --sg-text: #2d3748;
            --sg-text-light: #718096;
            --sg-border: #e2e8f0;
            --sg-success: #38a169;
            --sg-danger: #e53e3e;
            --sg-warning: #dd6b20;
            --sg-info: #3182ce;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--sg-bg);
            color: var(--sg-text);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .sg-navbar {
            background: var(--sg-white);
            border-bottom: 1px solid var(--sg-border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .sg-navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--sg-primary);
            text-decoration: none;
        }

        .sg-navbar .navbar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--sg-primary), var(--sg-primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .sg-navbar .nav-link {
            color: var(--sg-text);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sg-navbar .nav-link:hover,
        .sg-navbar .nav-link.active {
            color: var(--sg-primary);
            background: var(--sg-primary-light);
        }

        .sg-navbar .nav-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--sg-primary), var(--sg-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .sg-navbar .nav-avatar:hover {
            transform: scale(1.05);
        }

        /* ===== MAIN CONTENT ===== */
        .sg-main {
            padding: 2rem 0;
            min-height: calc(100vh - 70px);
        }

        /* ===== CARDS ===== */
        .sg-card {
            background: var(--sg-white);
            border-radius: 16px;
            border: 1px solid var(--sg-border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .sg-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .sg-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--sg-border);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sg-card .card-body {
            padding: 1.5rem;
        }

        /* ===== BUTTONS ===== */
        .btn-sg-primary {
            background: linear-gradient(135deg, var(--sg-primary), var(--sg-primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(13, 107, 94, 0.3);
        }

        .btn-sg-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(13, 107, 94, 0.4);
            color: white;
        }

        .btn-sg-outline {
            background: transparent;
            color: var(--sg-primary);
            border: 2px solid var(--sg-primary);
            border-radius: 10px;
            padding: 0.55rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-sg-outline:hover {
            background: var(--sg-primary);
            color: white;
            transform: translateY(-1px);
        }

        .btn-sg-danger {
            background: var(--sg-danger);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-sg-danger:hover {
            background: #c53030;
            color: white;
            transform: translateY(-1px);
        }

        /* ===== FORM STYLES ===== */
        .sg-form-group {
            margin-bottom: 1.25rem;
        }

        .sg-form-group label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--sg-text);
            margin-bottom: 0.4rem;
            display: block;
        }

        .sg-form-control {
            border: 1.5px solid var(--sg-border);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: var(--sg-white);
        }

        .sg-form-control:focus {
            border-color: var(--sg-primary);
            box-shadow: 0 0 0 3px rgba(13, 107, 94, 0.15);
            outline: none;
        }

        .sg-form-control.is-invalid {
            border-color: var(--sg-danger);
        }

        /* ===== BADGE ===== */
        .sg-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .sg-badge-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .sg-badge-warning {
            background: #fefcbf;
            color: #744210;
        }

        .sg-badge-danger {
            background: #fed7d7;
            color: #742a2a;
        }

        .sg-badge-info {
            background: #bee3f8;
            color: #2a4365;
        }

        /* ===== ALERTS ===== */
        .sg-alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sg-alert-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .sg-alert-danger {
            background: #fed7d7;
            color: #742a2a;
        }

        .sg-alert-warning {
            background: #fefcbf;
            color: #744210;
        }

        /* ===== PROFILE SPECIFIC ===== */
        .profile-header {
            background: linear-gradient(135deg, var(--sg-primary), var(--sg-secondary));
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .profile-info-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--sg-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-info-item:last-child {
            border-bottom: none;
        }

        .profile-info-label {
            color: var(--sg-text-light);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .profile-info-value {
            font-weight: 600;
            color: var(--sg-text);
        }

        .profile-info-value.empty {
            color: var(--sg-text-light);
            font-style: italic;
            font-weight: 400;
        }

        /* ===== DOCUMENT LIST ===== */
        .doc-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--sg-bg);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .doc-item:hover {
            background: var(--sg-primary-light);
        }

        .doc-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .doc-icon.pdf {
            background: #fed7d7;
            color: #e53e3e;
        }

        .doc-icon.jpg, .doc-icon.jpeg, .doc-icon.png {
            background: #bee3f8;
            color: #3182ce;
        }

        .doc-info {
            flex: 1;
        }

        .doc-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .doc-meta {
            font-size: 0.8rem;
            color: var(--sg-text-light);
        }

        /* ===== UPLOAD AREA ===== */
        .upload-area {
            border: 2px dashed var(--sg-border);
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--sg-bg);
        }

        .upload-area:hover {
            border-color: var(--sg-primary);
            background: var(--sg-primary-light);
        }

        .upload-area .upload-icon {
            font-size: 3rem;
            color: var(--sg-primary);
            margin-bottom: 0.75rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .profile-header {
                padding: 1.5rem;
            }

            .sg-card .card-body {
                padding: 1rem;
            }

            .profile-info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="sg-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="navbar-brand">
                    <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                    Scholar<span style="color: var(--sg-secondary)">GO</span>
                </a>

                <div class="d-flex align-items-center gap-1">
                    <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>
                    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person"></i> Profil
                    </a>
                    <a href="#" class="nav-link">
                        <i class="bi bi-bell"></i> Notifikasi
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3">
                    @isset($user)
                        <a href="{{ route('profile.show') }}" class="nav-avatar text-decoration-none" title="{{ $user->name }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </a>
                    @endisset
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="sg-alert sg-alert-success animate-in" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="sg-alert sg-alert-danger animate-in" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="sg-alert sg-alert-warning animate-in" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('warning') }}
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="sg-main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
