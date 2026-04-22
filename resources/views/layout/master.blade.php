<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tim Kami | Profesional dan Terstruktur</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
            --nav-bg: rgba(255, 255, 255, 0.92);
            --nav-menu-bg: rgba(255, 255, 255, 0.98);
            --footer-bg: #ffffff;
            --toggle-bg: rgba(18, 38, 63, 0.08);
            --toggle-color: var(--brand-900);
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
            --nav-menu-bg: rgba(15, 23, 36, 0.98);
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

        main {
            flex: 1;
        }

        .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            color: var(--brand-900) !important;
            font-weight: 800;
            letter-spacing: 0.01em;
            margin-right: 1rem;
        }

        .navbar-brand:hover {
            color: var(--brand-500) !important;
        }

        .nav-link {
            color: var(--brand-700) !important;
            font-weight: 600;
            transition: color 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--brand-500) !important;
        }

        .navbar-toggler {
            border: 1px solid rgba(18, 38, 63, 0.2);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(46, 91, 135, 0.2) !important;
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
            }

            .navbar .navbar-nav {
                width: 100%;
            }

            .navbar .nav-link {
                padding: 0.75rem 1rem;
            }
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.45rem 1rem;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
            color: #fff;
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

        body[data-theme='dark'] .btn-outline-secondary {
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .navbar-toggler {
            border-color: var(--border-soft);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Tim Kami</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('jadwal') }}">Jadwal Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tugas') }}">Tugas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('scan.login') }}">Scan Absen</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('admin.upload') }}">Upload</a></li> --}}
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.inserttoken.form') }}">Insert
                                Token</a>
                        </li>
                    @endauth
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
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
                                <button type="submit" class="btn btn-brand btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="btn btn-brand btn-sm ms-lg-2" href="{{ route('admin.login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Log In
                            </a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-5 flex-grow-1">
        @yield('konten')
    </main>

    <footer id="kontak" class="text-center py-4">
        <div class="container">
            <p class="mb-1 fw-semibold" style="color: var(--brand-900);">Tim Kami</p>
            <p class="mb-0">© 2026 Tim Kami | Dibuat dengan Laravel</p>
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
