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
            --brand-900: #0f172a;
            --brand-700: #1d4ed8;
            --brand-600: #2563eb;
            --brand-500: #2563eb;
            --accent: #b45309;
            --surface: #f8fafc;
            --surface-dim: #e2e8f0;
            --surface-bright: #ffffff;
            --surface-container-lowest: #ffffff;
            --surface-container-low: #f1f5f9;
            --surface-container: #e2e8f0;
            --surface-container-high: #cbd5e1;
            --surface-elevated: #ffffff;
            --surface-variant: #e2e8f0;
            --on-surface-variant: #475569;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-soft: #e2e8f0;
            --shadow-soft: rgba(15, 23, 42, 0.04);
            --shadow-card: 0px 1px 2px rgba(15, 23, 42, 0.04), 0px 4px 12px -2px rgba(15, 23, 42, 0.05);
            --shadow-card-hover: 0px 4px 8px -2px rgba(15, 23, 42, 0.06), 0px 12px 24px -4px rgba(15, 23, 42, 0.08);
            --nav-bg: rgba(255, 255, 255, 0.88);
            --nav-menu-bg: #ffffff;
            --footer-bg: #ffffff;
            --toggle-bg: #f1f5f9;
            --toggle-color: #0f172a;
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 0.875rem;
        }

        body[data-theme='dark'] {
            --brand-900: #f8fafc;
            --brand-700: #93c5fd;
            --brand-600: #60a5fa;
            --brand-500: #3b82f6;
            --accent: #fbbf24;
            --surface: #0b0f19;
            --surface-dim: #1e293b;
            --surface-bright: #111827;
            --surface-container-lowest: #111827;
            --surface-container-low: #1e293b;
            --surface-container: #1e293b;
            --surface-container-high: #334155;
            --surface-elevated: #111827;
            --surface-variant: rgba(30, 41, 59, 0.7);
            --on-surface-variant: #94a3b8;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-soft: rgba(148, 163, 184, 0.15);
            --shadow-soft: rgba(0, 0, 0, 0.35);
            --shadow-card: 0px 1px 2px rgba(0, 0, 0, 0.25), 0px 4px 12px -2px rgba(0, 0, 0, 0.35);
            --shadow-card-hover: 0px 8px 20px -4px rgba(0, 0, 0, 0.45);
            --nav-bg: rgba(17, 24, 39, 0.88);
            --nav-menu-bg: #111827;
            --footer-bg: #111827;
            --toggle-bg: rgba(255, 255, 255, 0.06);
            --toggle-color: #f8fafc;
        }

        html,
        body {
            overflow-x: clip;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-main);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            text-wrap: balance;
            letter-spacing: -0.02em;
        }

        p {
            text-wrap: pretty;
        }

        .tabular-nums,
        .table td,
        .stat-value,
        .tugas-date {
            font-variant-numeric: tabular-nums;
        }

        .theme-toggle {
            background: var(--toggle-bg);
            color: var(--toggle-color);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-sm);
            padding: 0.4rem 0.75rem;
            font-weight: 600;
            font-size: 0.8125rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }

        .theme-toggle:hover {
            color: var(--brand-500);
            border-color: var(--brand-500);
        }

        main {
            flex: 1;
            padding-top: 2.5rem;
            padding-bottom: 3.5rem;
        }

        @media (max-width: 991.98px) {
            body {
                padding-bottom: calc(5.5rem + env(safe-area-inset-bottom, 0px));
            }
        }

        @media (max-width: 767.98px) {
            main {
                padding-top: 1.25rem;
                padding-bottom: 2rem;
            }
        }

        .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }

        .navbar-brand {
            color: var(--brand-900) !important;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
            margin-right: 1.5rem;
        }

        .navbar-brand:hover {
            color: var(--brand-500) !important;
        }

        .navbar-nav {
            gap: 0.25rem;
        }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: var(--radius-sm);
            padding: 0.4rem 0.75rem !important;
            transition: color 0.15s ease, background 0.15s ease;
        }

        .nav-link:hover {
            color: var(--text-main) !important;
            background: var(--surface-container-low);
        }

        .nav-link.active {
            color: var(--brand-900) !important;
            background: var(--surface-container-low);
            font-weight: 600;
        }

        .navbar-actions {
            gap: 0.5rem;
        }

        @media (max-width: 575.98px) {
            .theme-toggle {
                padding: 0.4rem 0.55rem;
            }

            #themeToggleText {
                display: none;
            }
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
                display: none !important;
            }
        }

        /* Bottom navigation (mobile) */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 0.25rem;
            background: var(--nav-bg);
            border-top: 1px solid var(--border-soft);
            padding: 0.4rem 0.5rem calc(0.4rem + env(safe-area-inset-bottom, 0px));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .bottom-nav-group {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-around;
            min-width: 0;
        }

        .bottom-nav-group-left {
            padding-right: 1.85rem;
        }

        .bottom-nav-group-right {
            padding-left: 1.85rem;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.15rem;
            padding: 0.2rem 0;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 0.65rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .bottom-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 1.55rem;
            border-radius: 0.5rem;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .bottom-nav-item i {
            font-size: 1.1rem;
            line-height: 1;
        }

        .bottom-nav-item:hover,
        .bottom-nav-item.active {
            color: var(--brand-900);
            font-weight: 600;
        }

        .bottom-nav-item.active .bottom-nav-icon {
            background: var(--surface-container-low);
            color: var(--brand-500);
        }

        .bottom-nav-scan {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -56%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 0.15rem;
            margin: 0;
            padding-bottom: 0.2rem;
            color: var(--text-muted);
            font-size: 0.65rem;
            font-weight: 600;
            text-decoration: none;
            z-index: 2;
        }

        .bottom-nav-scan i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.1rem;
            height: 3.1rem;
            margin-top: -1.35rem;
            border-radius: 50%;
            background: var(--brand-500);
            color: #ffffff;
            font-size: 1.35rem;
            border: 3px solid var(--surface);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .bottom-nav-scan:hover i {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        @media (max-width: 991.98px) {
            .bottom-nav {
                display: flex;
            }
        }

        .btn {
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            border-width: 1px;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #ffffff;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 0.45rem 1rem;
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
            border-color: var(--border-soft);
            color: var(--text-muted);
            background: var(--surface-elevated);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--surface-container-low);
            border-color: var(--border-soft);
            color: var(--text-main);
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
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        footer {
            margin-top: auto;
            background: var(--footer-bg);
            color: var(--text-muted);
            border-top: 1px solid var(--border-soft);
        }

        .site-footer {
            padding: 2.5rem 0;
        }

        .footer-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--brand-900);
            margin-bottom: 0.35rem;
        }

        .footer-desc {
            max-width: 28rem;
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .footer-heading {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.45rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .footer-links a:hover {
            color: var(--text-main);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 767.98px) {
            footer {
                background: transparent;
            }

            .site-footer {
                padding: 1.75rem 0 1.5rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .footer-col-services .footer-links {
                display: flex;
                flex-wrap: wrap;
                column-gap: 1.25rem;
                row-gap: 0.45rem;
            }

            .footer-col-services .footer-heading {
                font-size: 0.7rem;
                margin-bottom: 0.5rem;
            }

            .footer-desc {
                font-size: 0.8125rem;
                line-height: 1.55;
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
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
        }

        .card.shadow-sm,
        .card.shadow-lg,
        .card.shadow {
            box-shadow: var(--shadow-card) !important;
        }

        .card-header,
        .card-footer {
            background-color: var(--surface-container-low);
            border-color: var(--border-soft);
        }

        /* Page header pattern */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--brand-900);
            margin-bottom: 0.35rem;
        }

        .page-subtitle {
            font-size: 0.9375rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        /* Badges */
        .badge {
            font-weight: 600;
            border-radius: 0.375rem;
            padding: 0.35em 0.65em;
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
            color: #0f172a;
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
            font-size: 0.8125rem;
            border-bottom-color: var(--border-soft);
        }

        /* Forms */
        .form-control,
        .form-select {
            background-color: var(--surface-elevated);
            color: var(--text-main);
            border-color: var(--border-soft);
            border-radius: var(--radius-sm);
            font-size: 0.9375rem;
            padding: 0.55rem 0.85rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
            border-radius: var(--radius-md);
            border: 1px solid var(--border-soft);
            font-size: 0.9375rem;
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
            background-color: rgba(22, 101, 52, 0.2);
            color: #86efac;
            border-color: rgba(22, 101, 52, 0.4);
        }

        body[data-theme='dark'] .alert-danger {
            background-color: rgba(153, 27, 27, 0.2);
            color: #fca5a5;
            border-color: rgba(153, 27, 27, 0.4);
        }

        body[data-theme='dark'] .alert-warning {
            background-color: rgba(146, 64, 14, 0.2);
            color: #fcd34d;
            border-color: rgba(146, 64, 14, 0.4);
        }

        body[data-theme='dark'] .alert-info {
            background-color: rgba(30, 64, 175, 0.2);
            color: #bfdbfe;
            border-color: rgba(30, 64, 175, 0.4);
        }

        /* Pagination */
        .pagination {
            gap: 0.35rem;
        }

        .pagination .page-link {
            min-width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.7rem;
            border-radius: var(--radius-sm);
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            color: var(--text-main);
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
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
            border-radius: var(--radius-sm);
        }

        /* Nav tabs & list group */
        .nav-tabs {
            border-bottom-color: var(--border-soft);
        }

        .nav-tabs .nav-link {
            color: var(--text-muted);
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
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
            background: var(--brand-500);
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
            color: var(--text-main) !important;
        }

        body[data-theme='dark'] .btn-outline-secondary {
            color: var(--text-main);
            border-color: var(--border-soft);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">Pemuda Akhir Zaman</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                </ul>
            </div>
            <div class="navbar-actions d-flex align-items-center">
                <button type="button" class="theme-toggle" id="themeToggle" aria-label="Ganti tema tampilan">
                    <i class="bi bi-moon-stars"></i>
                    <span id="themeToggleText">Dark</span>
                </button>
                @auth
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-brand btn-sm">Logout</button>
                    </form>
                @else
                    <a class="btn btn-brand btn-sm" href="{{ route('admin.login') }}">Log In</a>
                @endauth
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
            <a class="bottom-nav-item {{ request()->routeIs('tugas') ? 'active' : '' }}" href="{{ route('tugas') }}">
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
    <main class="flex-grow-1">
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
                <div class="footer-col footer-col-services">
                    <p class="footer-heading">Layanan</p>
                    <ul class="footer-links list-unstyled">
                        <li><a href="{{ route('scan.login') }}">Scan Absen</a></li>
                        <li><a href="{{ route('admin.login') }}">Login Admin</a></li>
                        <li><a href="https://www.instagram.com/paz.team214" target="_blank"
                                rel="noopener">@paz.team214</a></li>
                    </ul>
                </div>
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
        });
    </script>
</body>

</html>
