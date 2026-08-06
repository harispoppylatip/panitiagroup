<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Scan Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-900: #0d1c2e;
            --brand-700: #004ac6;
            --brand-500: #2563eb;
            --accent: #943700;
            --surface: #f8f9ff;
            --surface-elevated: #ffffff;
            --surface-container-low: #eff4ff;
            --text-main: #0d1c2e;
            --text-muted: #434655;
            --border-soft: #e2e8f0;
            --shadow-soft: rgba(0, 0, 0, 0.05);
            --toggle-bg: rgba(13, 28, 46, 0.06);
            --toggle-color: var(--brand-900);
        }

        body[data-theme='dark'] {
            --brand-900: #eaf1ff;
            --brand-700: #b4c5ff;
            --brand-500: #2563eb;
            --accent: #ffb596;
            --surface: #0f1724;
            --surface-elevated: #233144;
            --surface-container-low: #1b2537;
            --text-main: #eaf1ff;
            --text-muted: #b4c5ff;
            --border-soft: rgba(148, 163, 184, 0.18);
            --shadow-soft: rgba(0, 0, 0, 0.35);
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
            align-items: center;
            justify-content: center;
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
        }

        .scan-login-card {
            width: 100%;
            max-width: 460px;
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            background: var(--surface-elevated);
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
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
            z-index: 10;
        }


        .title {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: var(--brand-900);
        }

        .subtitle {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--brand-700);
            font-weight: 600;
            font-size: 0.86rem;
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid var(--border-soft);
            padding: 0.7rem 0.85rem;
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.65rem 1rem;
        }

        .btn-brand:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        .btn-back {
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            color: var(--brand-700);
            font-weight: 600;
            padding: 0.62rem 1rem;
            background: transparent;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            border-color: var(--brand-500);
            color: var(--brand-500);
            background: var(--surface-container-low);
        }

        body[data-theme='dark'] .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
        }

        body[data-theme='dark'] .btn-brand:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        body[data-theme='dark'] .btn-back {
            color: var(--brand-700);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .btn-back:hover {
            color: var(--brand-500);
            border-color: var(--brand-500);
            background: var(--surface-container-low);
        }

        .helper {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <button type="button" class="theme-toggle" id="themeToggle">
        <i class="bi bi-moon-stars"></i>
        <span id="themeToggleText">Dark</span>
    </button>

    <div class="scan-login-card p-4 p-md-5">
        <h1 class="title h3 text-center mb-2">Login Scan Absensi</h1>
        <p class="subtitle text-center mb-4">Gunakan akun yang sesuai.</p>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ url('/sesi/login') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-12">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="Masukkan username" autocomplete="username"
                    name="username" value="{{ old('username') }}" required />
            </div>

            <div class="col-12">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Masukkan password"
                    autocomplete="current-password" name="password" required />
            </div>

            <div class="col-12 d-grid">
                <button type="submit" class="btn btn-brand">Masuk ke Scanner</button>
            </div>

            <div class="col-12 d-grid">
                <a href="{{ url('/') }}" class="btn-back text-center">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>
        </form>

        <p class="helper text-center mt-4 mb-0">Akses scanner untuk proses absensi QR.</p>
    </div>

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
