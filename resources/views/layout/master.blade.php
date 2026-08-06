<!doctype html>
<html lang="id">

<head>
    @yield('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemuda Akhir Zaman</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="https://minio.umkt.ac.id/dev-umkt-static/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-900: #0d1c2e;
            --brand-700: #004ac6;
            --brand-500: #2563eb;
            --accent: #943700;
            --surface: #f8f9ff;
            --surface-dim: #ccdbf3;
            --surface-bright: #f8f9ff;
            --surface-container-lowest: #ffffff;
            --surface-container-low: #eff4ff;
            --surface-container: #e6eeff;
            --surface-container-high: #dce9ff;
            --surface-elevated: #ffffff;
            --surface-variant: #d5e3fc;
            --on-surface-variant: #434655;
            --text-main: #0d1c2e;
            --text-muted: #434655;
            --border-soft: #e2e8f0;
            --shadow-soft: rgba(0, 0, 0, 0.05);
            --nav-bg: rgba(248, 249, 255, 0.92);
            --nav-menu-bg: #ffffff;
            --footer-bg: #eff4ff;
            --toggle-bg: rgba(13, 28, 46, 0.06);
            --toggle-color: var(--brand-900);
        }

        body[data-theme='dark'] {
            --brand-900: #eaf1ff;
            --brand-700: #b4c5ff;
            --brand-500: #2563eb;
            --accent: #ffb596;
            --surface: #0f1724;
            --surface-dim: #233144;
            --surface-bright: #0f1724;
            --surface-container-lowest: #1b2537;
            --surface-container-low: #1b2537;
            --surface-container: #233144;
            --surface-container-high: #2b3a52;
            --surface-elevated: #233144;
            --surface-variant: rgba(35, 49, 68, 0.65);
            --on-surface-variant: #b4c5ff;
            --text-main: #eaf1ff;
            --text-muted: #b4c5ff;
            --border-soft: rgba(148, 163, 184, 0.18);
            --shadow-soft: rgba(0, 0, 0, 0.35);
            --nav-bg: rgba(35, 49, 68, 0.92);
            --nav-menu-bg: #233144;
            --footer-bg: #1b2537;
            --toggle-bg: rgba(255, 255, 255, 0.08);
            --toggle-color: #eaf1ff;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Inter', sans-serif;
        }

        .theme-toggle {
            background: var(--toggle-bg);
            color: var(--toggle-color);
            border: 1px solid var(--border-soft);
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .theme-toggle:hover {
            color: var(--brand-500);
            border-color: var(--brand-500);
        }

        main {
            flex: 1;
        }

        @media (max-width: 767.98px) {
            body {
                padding-bottom: 6.75rem;
            }
        }

        .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .navbar-brand {
            color: var(--brand-700) !important;
            font-weight: 700;
            letter-spacing: -0.01em;
            margin-right: 1rem;
        }

        .navbar-brand:hover {
            color: var(--brand-500) !important;
        }

        .nav-link {
            color: var(--on-surface-variant) !important;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 0.5rem;
            padding: 0.45rem 0.8rem !important;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .nav-link:hover {
            color: var(--brand-500) !important;
            background: var(--surface-container-low);
        }

        .nav-link.active {
            color: var(--brand-700) !important;
            font-weight: 700;
        }

        .navbar-toggler {
            border: 1px solid var(--border-soft);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15) !important;
        }

        @media (min-width: 992px) {
            .navbar .navbar-collapse {
                display: flex !important;
                flex-basis: auto;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
            }
        }

        @media (max-width: 991.98px) {
            .navbar .navbar-collapse {
                background-color: var(--nav-menu-bg) !important;
                border-top: 1px solid var(--border-soft);
                padding: 0.5rem 0;
                visibility: visible !important;
            }

            .navbar .navbar-collapse.collapsing,
            .navbar .navbar-collapse.show {
                visibility: visible !important;
            }

            .navbar .navbar-nav {
                width: 100%;
            }

            .navbar .nav-link {
                padding: 0.75rem 1rem;
            }
        }

        /* Bottom navigation (mobile), gaya aplikasi: bar mengambang + tombol scan bulat di tengah */
        .bottom-nav {
            position: fixed;
            bottom: 0.6rem;
            left: 0.6rem;
            right: 0.6rem;
            z-index: 1030;
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 0.35rem;
            background: var(--surface-container-lowest);
            border: 1px solid var(--border-soft);
            border-radius: 1.4rem;
            box-shadow: 0 8px 28px var(--shadow-soft);
            padding: 0.4rem 0.35rem calc(0.4rem + env(safe-area-inset-bottom, 0px));
            backdrop-filter: blur(12px);
            overflow: visible;
        }

        .bottom-nav-group {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 0;
        }

        .bottom-nav-group-left {
            padding-right: 2.15rem;
        }

        .bottom-nav-group-right {
            padding-left: 2.15rem;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.1rem;
            padding: 0.25rem 0;
            border-radius: 0.9rem;
            color: var(--text-muted);
            font-size: 0.62rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .bottom-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.15rem;
            height: 1.7rem;
            border-radius: 0.7rem;
            transition: background 0.2s ease;
        }

        .bottom-nav-item i {
            font-size: 1.15rem;
            line-height: 1;
        }

        .bottom-nav-item:hover {
            color: var(--brand-700);
        }

        .bottom-nav-item.active {
            color: var(--brand-700);
        }

        .bottom-nav-item.active .bottom-nav-icon {
            background: var(--surface-container-high);
            color: var(--brand-700);
        }

        .bottom-nav-scan {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -58%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 0.1rem;
            margin: 0;
            padding-bottom: 0.25rem;
            color: var(--text-muted);
            font-size: 0.62rem;
            font-weight: 600;
            text-decoration: none;
            z-index: 2;
        }

        .bottom-nav-scan i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.4rem;
            height: 3.4rem;
            margin-top: -1.55rem;
            border-radius: 50%;
            background: var(--brand-500);
            color: #ffffff;
            font-size: 1.55rem;
            border: 3px solid var(--surface-container-lowest);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .bottom-nav-scan:hover i {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        @media (max-width: 767.98px) {
            .bottom-nav {
                display: flex;
            }
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            border-width: 1px;
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #ffffff;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.5rem 1.15rem;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .btn-brand:hover,
        .btn-brand:focus {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-primary {
            background-color: var(--brand-500);
            border-color: var(--brand-500);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-outline-primary {
            border-color: var(--brand-500);
            color: var(--brand-700);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--brand-500);
            border-color: var(--brand-500);
            color: #ffffff;
        }

        .btn-outline-secondary {
            color: var(--text-muted);
            border-color: var(--border-soft);
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus {
            color: var(--text-main);
            background-color: var(--surface-container-low);
            border-color: var(--border-soft);
        }

        .btn-warning {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: #1f2937;
        }

        .btn-warning:hover,
        .btn-warning:focus {
            background-color: #d97706;
            border-color: #d97706;
            color: #ffffff;
        }

        .btn-success {
            background-color: #16a34a;
            border-color: #16a34a;
        }

        .btn-success:hover,
        .btn-success:focus {
            background-color: #15803d;
            border-color: #15803d;
        }

        .btn-danger {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .btn-danger:hover,
        .btn-danger:focus {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        body[data-theme='dark'] .btn-brand {
            background: var(--brand-500);
            border-color: var(--brand-500);
            color: #ffffff;
        }

        body[data-theme='dark'] .btn-brand:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        footer {
            margin-top: auto;
            background: var(--footer-bg);
            color: var(--text-muted);
            border-top: 1px solid var(--border-soft);
        }

        .site-footer {
            padding: 1.5rem 0;
        }

        .footer-name {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand-900);
            margin-bottom: 0.4rem;
        }

        .footer-desc {
            max-width: 30rem;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .footer-heading {
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.5rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--brand-500);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .footer-bottom {
            border-top: 1px solid var(--border-soft);
            padding: 1rem 0 0;
            text-align: center;
            font-size: 0.875rem;
        }

        @media (max-width: 767.98px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        .card,
        .dropdown-menu,
        .list-group-item,
        .modal-content {
            background-color: var(--surface-elevated);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .card {
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .card.shadow-sm,
        .card.shadow-lg,
        .card.shadow {
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .card-header,
        .card-footer {
            background-color: var(--surface-container-low);
            border-color: var(--border-soft);
        }

        /* Badges */
        .badge {
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.4em 0.7em;
        }

        .badge.rounded-pill {
            border-radius: 999px;
        }

        .text-bg-primary {
            background-color: var(--brand-500);
        }

        .text-bg-secondary {
            background-color: var(--surface-variant);
            color: var(--text-main);
        }

        .text-bg-light {
            background-color: var(--surface-container-low);
            color: var(--text-main);
        }

        .text-bg-dark {
            background-color: var(--brand-900);
            color: #ffffff;
        }

        body[data-theme='dark'] .text-bg-dark {
            color: #0d1c2e;
        }

        /* Tables */
        .table {
            --bs-table-color: var(--text-main);
            --bs-table-border-color: var(--border-soft);
            --bs-table-hover-bg: var(--surface-container-low);
        }

        .table thead th {
            color: var(--text-muted);
            font-weight: 600;
            border-bottom-color: var(--border-soft);
        }

        /* Forms */
        .form-control,
        .form-select {
            background-color: var(--surface-elevated);
            color: var(--text-main);
            border-color: var(--border-soft);
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
            background-color: var(--surface-elevated);
            color: var(--text-main);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .input-group-text {
            background-color: var(--surface-container-low);
            color: var(--text-muted);
            border-color: var(--border-soft);
        }

        /* Alerts */
        .alert {
            border-radius: 0.75rem;
            border: 1px solid var(--border-soft);
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-color: #fde68a;
        }

        .alert-info {
            background-color: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        body[data-theme='dark'] .alert-success {
            background-color: rgba(22, 101, 52, 0.25);
            color: #86efac;
            border-color: rgba(22, 101, 52, 0.4);
        }

        body[data-theme='dark'] .alert-danger {
            background-color: rgba(153, 27, 27, 0.25);
            color: #fca5a5;
            border-color: rgba(153, 27, 27, 0.4);
        }

        body[data-theme='dark'] .alert-warning {
            background-color: rgba(146, 64, 14, 0.25);
            color: #fcd34d;
            border-color: rgba(146, 64, 14, 0.4);
        }

        body[data-theme='dark'] .alert-info {
            background-color: rgba(30, 64, 175, 0.25);
            color: #bfdbfe;
            border-color: rgba(30, 64, 175, 0.4);
        }

        /* Pagination */
        .pagination {
            gap: 0.35rem;
        }

        .pagination .page-link {
            min-width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.75rem;
            border-radius: 0.5rem;
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            color: var(--brand-700);
            font-weight: 600;
            transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .pagination .page-link:hover {
            background: var(--surface-container-low);
            border-color: var(--border-soft);
            color: var(--brand-500);
        }

        .pagination .page-item.active .page-link {
            background: var(--brand-500);
            border-color: var(--brand-500);
            color: #ffffff;
        }

        .pagination .page-item.disabled .page-link {
            background: var(--surface-elevated);
            border-color: var(--border-soft);
            color: var(--text-muted);
            opacity: 0.5;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 0.5rem;
        }

        /* Nav tabs & list group */
        .nav-tabs {
            border-bottom-color: var(--border-soft);
        }

        .nav-tabs .nav-link {
            color: var(--text-muted);
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .nav-tabs .nav-link.active {
            background: var(--surface-elevated);
            color: var(--brand-700);
            border-color: var(--border-soft) var(--border-soft) var(--surface-elevated);
        }

        .list-group-item {
            background-color: var(--surface-elevated);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .list-group-item.active {
            background-color: var(--brand-500);
            border-color: var(--brand-500);
            color: #ffffff;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        body[data-theme='dark'] .text-muted,
        body[data-theme='dark'] .subtitle,
        body[data-theme='dark'] .helper,
        body[data-theme='dark'] .small,
        body[data-theme='dark'] .footer-text,
        body[data-theme='dark'] .footer-text.secondary,
        body[data-theme='dark'] .site-footer,
        body[data-theme='dark'] .footer-desc,
        body[data-theme='dark'] .footer-links a {
            color: var(--text-muted) !important;
        }

        body[data-theme='dark'] .footer-name {
            color: var(--brand-900) !important;
        }

        body[data-theme='dark'] .footer-links a:hover {
            color: var(--brand-500) !important;
        }

        body[data-theme='dark'] .btn-outline-secondary {
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .navbar-toggler {
            border-color: var(--border-soft);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'><path stroke='%23434655' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/></svg>");
        }

        /* Ensure hamburger icon is visible in dark mode */
        body[data-theme='dark'] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'><path stroke='%23eaf1ff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/></svg>");
            filter: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Pemuda Akhir Zaman</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                            href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('jadwal') ? 'active' : '' }}"
                            href="{{ route('jadwal') }}">Jadwal Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('tugas') ? 'active' : '' }}"
                            href="{{ route('tugas') }}">Tugas</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('galeri.*') ? 'active' : '' }}"
                            href="{{ route('galeri.index') }}">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('grubkas.*') ? 'active' : '' }}"
                            href="{{ route('grubkas.index') }}">Kas Grub</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('scan.login') ? 'active' : '' }}"
                            href="{{ route('scan.login') }}">Scan Absen</a></li>
                    <li class="nav-item ms-lg-2">
                        <button type="button" class="theme-toggle" id="themeToggle">
                            <i class="bi bi-moon-stars"></i>
                            <span id="themeToggleText">Dark</span>
                        </button>
                    </li>
                    @auth
                        <li class="nav-item ms-lg-2">
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-brand btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="btn btn-brand btn-sm ms-lg-2" href="{{ route('admin.login') }}">Log
                                In</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bottom Navigation (mobile) -->
    <nav class="bottom-nav" aria-label="Navigasi bawah">
        <div class="bottom-nav-group bottom-nav-group-left">
            <a class="bottom-nav-item {{ request()->is('/') ? 'active' : '' }}" href="/">
                <span class="bottom-nav-icon"><i class="bi bi-house-door"></i></span>
                <span>Beranda</span>
            </a>
            <a class="bottom-nav-item {{ request()->routeIs('jadwal') ? 'active' : '' }}"
                href="{{ route('jadwal') }}">
                <span class="bottom-nav-icon"><i class="bi bi-calendar3"></i></span>
                <span>Jadwal</span>
            </a>
        </div>
        <a class="bottom-nav-scan" href="{{ route('scan.login') }}" aria-label="Scan Absen">
            <i class="bi bi-qr-code-scan"></i>
            <span>Scan</span>
        </a>
        <div class="bottom-nav-group bottom-nav-group-right">
            <a class="bottom-nav-item {{ request()->routeIs('tugas') ? 'active' : '' }}"
                href="{{ route('tugas') }}">
                <span class="bottom-nav-icon"><i class="bi bi-journal-text"></i></span>
                <span>Tugas</span>
            </a>
            <a class="bottom-nav-item {{ request()->routeIs('galeri.*') ? 'active' : '' }}"
                href="{{ route('galeri.index') }}">
                <span class="bottom-nav-icon"><i class="bi bi-images"></i></span>
                <span>Galeri</span>
            </a>
            <a class="bottom-nav-item {{ request()->routeIs('grubkas.*') ? 'active' : '' }}"
                href="{{ route('grubkas.index') }}">
                <span class="bottom-nav-icon"><i class="bi bi-wallet2"></i></span>
                <span>Kas</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-5 flex-grow-1">
        @yield('konten')
    </main>

    <footer id="kontak" class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <p class="footer-name">Pemuda Akhir Zaman</p>
                    <p class="footer-desc">
                        Kelompok mahasiswa Universitas Muhammadiyah Kalimantan Timur. Bersatu dalam ide, berkarya
                        dengan teknologi.
                    </p>
                </div>
                <div class="footer-col">
                    <p class="footer-heading">Navigasi</p>
                    <ul class="footer-links">
                        <li><a href="/">Beranda</a></li>
                        <li><a href="{{ route('jadwal') }}">Jadwal Kuliah</a></li>
                        <li><a href="{{ route('tugas') }}">Tugas</a></li>
                        <li><a href="{{ route('galeri.index') }}">Galeri</a></li>
                        <li><a href="{{ route('grubkas.index') }}">Kas Grub</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <p class="footer-heading">Layanan</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('scan.login') }}">Scan Absen</a></li>
                        <li><a href="{{ route('admin.login') }}">Login Admin</a></li>
                    </ul>
                    <p class="footer-heading mt-4">Sosial</p>
                    <ul class="footer-links">
                        <li>
                            <a href="https://www.instagram.com/paz.team214" target="_blank" rel="noopener">
                                @paz.team214
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">© 2026 Pemuda Akhir Zaman | Dibuat Oleh Tim Kami</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'theme-mode';
            const body = document.body;
            const themeToggle = document.getElementById('themeToggle');
            const themeToggleText = document.getElementById('themeToggleText');
            const toggler = document.querySelector('.navbar-toggler');
            const menu = document.getElementById('navbarNav');

            function applyTheme(theme) {
                body.setAttribute('data-theme', theme);
                localStorage.setItem(storageKey, theme);

                if (themeToggleText) {
                    themeToggleText.textContent = theme === 'dark' ? 'Light' : 'Dark';
                }

                if (themeToggle) {
                    const icon = themeToggle.querySelector('i');
                    if (icon) {
                        icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
                    }
                }
            }

            const savedTheme = localStorage.getItem(storageKey) || 'light';
            applyTheme(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const nextTheme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    applyTheme(nextTheme);
                });
            }

            if (!window.bootstrap && toggler && menu) {
                toggler.addEventListener('click', function() {
                    const isOpen = menu.classList.toggle('show');
                    toggler.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }
        });
    </script>

</body>

</html>
