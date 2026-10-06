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
            --brand-900: #0d1c2e;
            --brand-700: #004ac6;
            --brand-500: #2563eb;
            --accent: #943700;
            --surface: #f8f9ff;
            --surface-dim: #ccdbf3;
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
            transition: border-color 0.2s ease, color 0.2s ease;
        }

        .theme-toggle:hover {
            border-color: var(--brand-500);
            color: var(--brand-500);
        }

        .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 8px var(--shadow-soft);
        }

        .navbar-toggler {
            border-color: var(--border-soft);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23434655' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        body[data-theme='dark'] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23eaf1ff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
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
                margin: 0 -0.75rem;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                border-radius: 0 0 1rem 1rem;
                box-shadow: 0 10px 24px var(--shadow-soft);
            }

            .navbar .navbar-collapse.collapsing,
            .navbar .navbar-collapse.show {
                visibility: visible !important;
            }

            .navbar .navbar-nav {
                width: 100%;
            }

            .navbar .nav-link {
                padding: 0.75rem 0.5rem;
            }
        }

        .navbar-brand {
            color: var(--brand-700) !important;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .nav-link {
            color: var(--on-surface-variant) !important;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--brand-500) !important;
            background: var(--surface-container-low);
        }

        .nav-link.active {
            color: var(--brand-700) !important;
            font-weight: 700;
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.45rem 1rem;
        }

        .btn-brand:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        body[data-theme='dark'] .btn-brand:hover {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        main {
            flex: 1;
        }

        footer {
            margin-top: auto;
            background: var(--footer-bg);
            color: var(--text-muted);
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
            border-radius: 1rem;
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-main);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
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
        <div class="container-fluid">
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
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
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

                    <li class="nav-item">
                        <button type="button" class="theme-toggle" id="themeToggle">
                            <i class="bi bi-moon-stars"></i>
                            <span id="themeToggleText">Dark</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Lihat Website</a>
                    </li>
                    <li class="nav-item ms-lg-2">
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

    <main class="py-5">
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
