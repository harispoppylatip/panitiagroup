<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $panelRole = auth()->user()?->role;
        $panelTitle = match ($panelRole) {
            'akuntan' => 'Akuntan Panel',
            'admin' => 'Admin Panel',
            'anggota' => 'Member Panel',
            default => 'Admin Panel',
        };
    @endphp
    <title>{{ $panelTitle }} | Pemuda Akhir Zaman</title>

    {{-- icon --}}
    <link rel="icon" type="image/x-icon" href="https://minio.umkt.ac.id/dev-umkt-static/images/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            --nav-bg: rgba(17, 24, 39, 0.88);
            --nav-menu-bg: #111827;
            --footer-bg: #111827;
            --toggle-bg: rgba(255, 255, 255, 0.06);
            --toggle-color: #f8fafc;
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

        .table td {
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
            transition: border-color 0.15s ease, color 0.15s ease;
        }

        .theme-toggle:hover {
            border-color: var(--brand-500);
            color: var(--brand-500);
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

        .navbar-toggler {
            border-color: var(--border-soft);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23475569' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        body[data-theme='dark'] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23f8fafc' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (min-width: 768px) {
            .navbar .navbar-collapse {
                display: flex !important;
                flex-basis: auto;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
            }
        }

        @media (max-width: 767.98px) {
            .navbar .navbar-collapse {
                border-top: 1px solid var(--border-soft);
                padding: 0.5rem 0;
                visibility: visible !important;
                background: var(--nav-menu-bg);
                margin: 0.5rem -0.75rem -0.5rem;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                border-radius: 0 0 var(--radius-lg) var(--radius-lg);
                box-shadow: var(--shadow-card);
            }

            .navbar .navbar-collapse.collapsing,
            .navbar .navbar-collapse.show {
                visibility: visible !important;
            }

            .navbar .navbar-nav {
                width: 100%;
            }

            .navbar .nav-link {
                padding: 0.65rem 0.5rem;
            }
        }

        .navbar-brand {
            color: var(--brand-900) !important;
            font-weight: 700;
            font-size: 1.025rem;
            letter-spacing: -0.02em;
        }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: var(--radius-sm);
            padding: 0.4rem 0.7rem !important;
            transition: color 0.15s ease, background-color 0.15s ease;
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

        .btn {
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 0.4rem 0.9rem;
        }

        .btn-brand:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        body[data-theme='dark'] .btn-brand:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        main {
            flex: 1;
        }

        footer {
            margin-top: auto;
            background: var(--footer-bg);
            color: var(--text-muted);
            font-size: 0.85rem;
            border-top: 1px solid var(--border-soft);
        }

        .card,
        .dropdown-menu,
        .list-group-item,
        .form-control,
        .form-select,
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

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-main);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .form-control,
        .form-select {
            border-radius: var(--radius-sm);
            font-size: 0.9375rem;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            background-color: var(--surface-elevated);
            color: var(--text-main);
        }

        .btn-primary {
            background: var(--brand-500);
            border-color: var(--brand-500);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        body[data-theme='dark'] .text-muted,
        body[data-theme='dark'] .subtitle,
        body[data-theme='dark'] .helper,
        body[data-theme='dark'] .small,
        body[data-theme='dark'] .footer-text,
        body[data-theme='dark'] .footer-text.secondary {
            color: var(--text-muted) !important;
        }

        body[data-theme='dark'] input[type='date'],
        body[data-theme='dark'] select.form-select {
            color-scheme: dark;
        }

        body[data-theme='dark'] .text-success {
            color: #4ade80 !important;
        }

        body[data-theme='dark'] .text-danger {
            color: #f87171 !important;
        }

        body[data-theme='dark'] .text-warning {
            color: #fbbf24 !important;
        }

        body[data-theme='dark'] .alert-success {
            background-color: rgba(34, 197, 94, 0.12);
            border-color: rgba(34, 197, 94, 0.25);
            color: #86efac;
        }

        body[data-theme='dark'] .alert-danger {
            background-color: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }

        body[data-theme='dark'] .alert-warning {
            background-color: rgba(245, 158, 11, 0.12);
            border-color: rgba(245, 158, 11, 0.25);
            color: #fcd34d;
        }

        body[data-theme='dark'] .btn-outline-danger {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.5);
        }

        body[data-theme='dark'] .btn-outline-danger:hover {
            background-color: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(248, 113, 113, 0.6);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md">
        <div class="container">
            @php
                $currentRole = auth()->user()?->role;
                $homeRoute = route('admin.beranda.index');
                $sharedMenus = [
                    ['label' => 'Beranda', 'route' => 'admin.beranda.index', 'active' => 'admin.beranda.*'],
                    ['label' => 'Tugas', 'route' => 'admin.tugas.index', 'active' => 'admin.tugas.*'],
                    ['label' => 'Galeri', 'route' => 'admin.galeri.index', 'active' => 'admin.galeri.*'],
                ];
                $adminOnlyMenuVisible = $currentRole === 'admin';
            @endphp

            <a class="navbar-brand" href="{{ $homeRoute }}">
                @php
                    $roleName = match ($currentRole) {
                        'akuntan' => 'Akuntan Panel',
                        'admin' => 'Admin Panel',
                        'anggota' => 'Member Panel',
                        default => 'Admin Panel',
                    };
                @endphp
                {{ $roleName }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    @foreach ($sharedMenus as $menu)
                        @if (in_array($currentRole, ['admin', 'akuntan', 'anggota'], true))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs($menu['active']) ? 'active' : '' }}"
                                    href="{{ route($menu['route']) }}">{{ $menu['label'] }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if (in_array($currentRole, ['admin', 'akuntan'], true))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}"
                                href="{{ route('admin.finance.index') }}">Keuangan</a>
                        </li>
                    @endif

                    @if (in_array($currentRole, ['admin', 'akuntan'], true))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.tim.index') ? 'active' : '' }}"
                                href="{{ route('admin.tim.index') }}">Management Tim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.tim.detail') ? 'active' : '' }}"
                                href="{{ route('admin.tim.detail') }}">Detail Tim</a>
                        </li>
                    @endif

                    @if ($adminOnlyMenuVisible)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.scan.login.setting') ? 'active' : '' }}"
                                href="{{ route('admin.scan.login.setting') }}">Setting Login Scan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">Management User</a>
                        </li>
                    @endif

                    <li class="nav-item ms-lg-1">
                        <button type="button" class="theme-toggle" id="themeToggle" aria-label="Ganti tema tampilan">
                            <i class="bi bi-moon-stars"></i>
                            <span id="themeToggleText">Dark</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Lihat Website</a>
                    </li>
                    <li class="nav-item ms-lg-1">
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-brand btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 py-md-5">
        @yield('konten')
    </main>

    <footer class="text-center py-3">
        <div class="container">
            @php
                $footerPanelTitle = match (auth()->user()?->role) {
                    'akuntan' => 'Akuntan Panel',
                    'admin' => 'Admin Panel',
                    'anggota' => 'Member Panel',
                    default => 'Admin Panel',
                };
            @endphp
            <p class="mb-0">{{ $footerPanelTitle }} Pemuda Akhir Zaman</p>
        </div>
    </footer>

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

            applyTheme(localStorage.getItem(storageKey) || 'light');

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
