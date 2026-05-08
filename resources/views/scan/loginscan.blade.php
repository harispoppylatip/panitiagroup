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
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Sans+3:wght@400;500;600&display=swap"
        rel="stylesheet">

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
            --toggle-bg: rgba(255, 255, 255, 0.08);
            --toggle-color: #e5eef9;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Source Sans 3', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at top right, rgba(46, 91, 135, 0.14), transparent 46%),
                radial-gradient(circle at bottom left, rgba(195, 143, 60, 0.12), transparent 42%),
                var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .scan-login-card {
            width: 100%;
            max-width: 460px;
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            background: var(--surface-elevated);
            box-shadow: 0 18px 45px rgba(18, 38, 63, 0.15);
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
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            z-index: 10;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            margin: 0 auto;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .title {
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            color: var(--brand-900);
        }

        .subtitle {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--brand-700);
            font-weight: 700;
            font-size: 0.86rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid var(--border-soft);
            padding: 0.7rem 0.85rem;
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(46, 91, 135, 0.16);
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.65rem 1rem;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
            color: #fff;
        }

        .btn-back {
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            color: var(--brand-700);
            font-weight: 700;
            padding: 0.62rem 1rem;
            background: transparent;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            border-color: var(--brand-500);
            color: var(--brand-500);
            background: rgba(46, 91, 135, 0.08);
        }

        body[data-theme='dark'] .icon-box {
            background: linear-gradient(135deg, #2c5b87 0%, #21486f 55%, #183551 100%);
            border: 1px solid rgba(148, 163, 184, 0.28);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.35);
        }

        body[data-theme='dark'] .btn-brand {
            background: linear-gradient(135deg, #3f74a8 0%, #305e8c 52%, #264d74 100%);
            border: 1px solid rgba(148, 163, 184, 0.35);
            color: #f8fbff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.26);
        }

        body[data-theme='dark'] .btn-brand:hover {
            background: linear-gradient(135deg, #4c85bc 0%, #3a6e9f 52%, #2d5a84 100%);
            border-color: rgba(148, 163, 184, 0.48);
            color: #ffffff;
        }

        body[data-theme='dark'] .btn-back {
            color: #d7e5f7;
            border-color: rgba(148, 163, 184, 0.35);
        }

        body[data-theme='dark'] .btn-back:hover {
            color: #edf4ff;
            border-color: rgba(148, 163, 184, 0.52);
            background: rgba(148, 163, 184, 0.14);
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
        <div class="icon-box mb-3">
            <i class="bi bi-qr-code-scan"></i>
        </div>
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
