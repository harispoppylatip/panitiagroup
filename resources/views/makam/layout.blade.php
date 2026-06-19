<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Makam Admin Panel | @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/x-icon" href="https://minio.umkt.ac.id/dev-umkt-static/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Sans+3:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --brand-900: #0d2818;
            --brand-700: #1a3d2b;
            --brand-500: #2d6a4f;
            --accent: #40916c;
            --accent-hover: #2d6a4f;
            --surface: #f0f4f2;
            --surface-elevated: rgba(255, 255, 255, 0.92);
            --text-main: #0d2818;
            --text-muted: #5a7a6a;
            --border-soft: rgba(13, 40, 24, 0.1);
            --shadow-soft: rgba(13, 40, 24, 0.12);
            --nav-bg: linear-gradient(135deg, #0d2818, #1a3d2b);
            --footer-bg: #ffffff;
            --toggle-bg: rgba(255, 255, 255, 0.1);
            --toggle-color: #ffffff;
            --card-bg: #ffffff;
        }

        body[data-theme='dark'] {
            --brand-900: #d8f3dc;
            --brand-700: #b7e4c7;
            --brand-500: #95d5b2;
            --accent: #52b788;
            --accent-hover: #40916c;
            --surface: #081c11;
            --surface-elevated: rgba(10, 30, 18, 0.92);
            --text-main: #d8f3dc;
            --text-muted: #95b5a5;
            --border-soft: rgba(200, 220, 210, 0.12);
            --shadow-soft: rgba(0, 0, 0, 0.4);
            --nav-bg: linear-gradient(135deg, #051408, #081c11);
            --footer-bg: #081c11;
            --toggle-bg: rgba(255, 255, 255, 0.08);
            --toggle-color: #d8f3dc;
            --card-bg: rgba(10, 30, 18, 0.92);
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Source Sans 3', sans-serif;
            color: var(--text-main);
            background: var(--surface);
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

        .navbar {
            background: var(--nav-bg) !important;
            border-bottom: 1px solid rgba(64, 145, 108, 0.25);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: 800;
            letter-spacing: 0.02em;
            font-size: 1.15rem;
        }

        .navbar-brand img {
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
            transition: color 0.25s ease;
            padding: 0.5rem 0.8rem !important;
            border-radius: 8px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .theme-toggle {
            background: var(--toggle-bg);
            color: var(--toggle-color);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
        }

        .theme-toggle:hover {
            color: var(--toggle-color);
            transform: translateY(-1px);
        }

        .btn-makam {
            background: var(--accent);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.45rem 1.2rem;
            transition: all 0.2s ease;
        }

        .btn-makam:hover {
            background: var(--accent-hover);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-makam-outline {
            background: transparent;
            border: 2px solid var(--accent);
            color: var(--accent);
            font-weight: 700;
            border-radius: 8px;
            padding: 0.4rem 1.1rem;
            transition: all 0.2s ease;
        }

        .btn-makam-outline:hover {
            background: var(--accent);
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

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-soft);
            color: var(--text-main);
        }

        .table {
            color: var(--text-main);
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(233, 69, 96, 0.04);
        }

        .form-control,
        .form-select {
            background-color: var(--card-bg);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-main);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(64, 145, 108, 0.15);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .page-link {
            background-color: var(--card-bg);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        .page-link:hover {
            background-color: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(64, 145, 108, 0.15);
        }

        .page-item.disabled .page-link {
            background-color: var(--card-bg);
            color: var(--text-muted);
            border-color: var(--border-soft);
        }

        .alert {
            border-radius: 10px;
        }

        .stat-card {
            border-radius: 14px;
            padding: 1.5rem;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .stat-icon[style*="accent"] {
            background: rgba(64, 145, 108, 0.12) !important;
        }

        .stat-card .stat-icon[style*="brand-500"] {
            background: rgba(45, 106, 79, 0.12) !important;
        }

        .content-preview {
            background: var(--surface);
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid var(--border-soft);
            min-height: 120px;
            white-space: pre-wrap;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: #fff;
        }

        @media (max-width: 767.98px) {
            .navbar .navbar-collapse {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.5rem 0;
            }

            .navbar .navbar-nav {
                width: 100%;
            }

            .navbar .nav-link {
                padding: 0.6rem 0.5rem !important;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('makam.dashboard') }}">
                <img src="{{ asset('images/Logo-Makam-Mu.png') }}" alt="Makam.Mu"
                    style="height: 32px; width: auto; margin-right: 8px;">
                Makam Admin Panel
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#makamNavbar"
                aria-controls="makamNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="makamNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('makam.dashboard') ? 'active' : '' }}"
                            href="{{ route('makam.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('makam.news.*') ? 'active' : '' }}"
                            href="{{ route('makam.news.index') }}">
                            <i class="bi bi-newspaper"></i> Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="bi bi-globe"></i> Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="theme-toggle" id="themeToggle">
                            <i class="bi bi-moon-stars"></i>
                            <span id="themeToggleText">Dark</span>
                        </button>
                    </li>
                    <li class="nav-item ms-lg-1">
                        <form action="{{ route('makam.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-makam btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('konten')
        </div>
    </main>

    <footer class="text-center py-3">
        <div class="container">
            <p class="mb-0">Makam Admin Panel &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'makam-theme-mode';
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
    @stack('scripts')
</body>

</html>
