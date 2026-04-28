<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Panitia Akhir Zaman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Sans+3:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-900: #12263f;
            --brand-700: #1f3b5c;
            --brand-500: #2e5b87;
            --accent: #c38f3c;
            --surface: #f4f7fb;
            --surface-elevated: rgba(255, 255, 255, 0.92);
            --text-main: #1f2a37;
            --text-muted: #5f6f84;
            --border-soft: rgba(18, 38, 63, 0.1);
            --shadow-soft: rgba(18, 38, 63, 0.15);
            --nav-bg: rgba(18, 38, 63, 0.96);
            --footer-bg: #ffffff;
            --toggle-bg: rgba(255, 255, 255, 0.08);
            --toggle-color: #ffffff;
        }

        body[data-theme='dark'] {
            --brand-900: #e5eef9;
            --brand-700: #b7c7dc;
            --brand-500: #87a9cc;
            --accent: #d6ad62;
            --surface: #0f1724;
            --surface-elevated: rgba(17, 24, 39, 0.92);
            --text-main: #e5eef9;
            --text-muted: #a7b4c5;
            --border-soft: rgba(148, 163, 184, 0.18);
            --shadow-soft: rgba(0, 0, 0, 0.35);
            --nav-bg: rgba(15, 23, 36, 0.92);
            --footer-bg: #111827;
            --toggle-bg: rgba(255, 255, 255, 0.08);
            --toggle-color: #e5eef9;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Source Sans 3', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at top right, rgba(46, 91, 135, 0.12), transparent 50%),
                radial-gradient(circle at bottom left, rgba(195, 143, 60, 0.1), transparent 45%),
                var(--surface);
            display: flex;
            flex-direction: column;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Manrope', sans-serif;
        }

        .theme-toggle {
            background: var(--toggle-bg);
            color: var(--toggle-color);
            border: 1px solid var(--border-soft);
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .theme-toggle:hover {
            color: var(--toggle-color);
            transform: translateY(-1px);
        }

        .navbar {
            background: var(--nav-bg) !important;
            border-bottom: 1px solid rgba(195, 143, 60, 0.25);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.35);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
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

        .navbar-brand {
            color: #fff !important;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.86) !important;
            font-weight: 600;
            transition: color 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--accent), #b07b2e);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.45rem 1rem;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #b07b2e, #8f6426);
            color: #fff;
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
        .table,
        .list-group-item,
        .form-control,
        .modal-content {
            background-color: var(--surface-elevated);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        body[data-theme='dark'] .text-muted,
        body[data-theme='dark'] .subtitle,
        body[data-theme='dark'] .helper,
        body[data-theme='dark'] .small,
        body[data-theme='dark'] .footer-text,
        body[data-theme='dark'] .footer-text.secondary {
            color: var(--text-muted) !important;
        }

        body[data-theme='dark'] .navbar-toggler {
            border-color: var(--border-soft);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.upload') }}">
                <i class="bi bi-speedometer2"></i> Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.beranda.*') ? 'active' : '' }}"
                            href="{{ route('admin.beranda.index') }}">Management Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.upload') ? 'active' : '' }}"
                            href="{{ route('admin.upload') }}">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.editor') ? 'active' : '' }}"
                            href="{{ route('admin.editor') }}">Editor Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tugas.*') ? 'active' : '' }}"
                            href="{{ route('admin.tugas.index') }}">Management Tugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.membertoken') ? 'active' : '' }}"
                            href="{{ route('admin.membertoken') }}">Management Token</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}"
                            href="{{ route('admin.finance.index') }}">Management Uang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.scan.login.setting') ? 'active' : '' }}"
                            href="{{ route('admin.scan.login.setting') }}">Setting Login Scan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">Management User</a>
                    </li>
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
                                <i class="bi bi-box-arrow-right"></i> Logout
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
            <p class="mb-0">Admin Panel Panitia Akhir Zaman</p>
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
