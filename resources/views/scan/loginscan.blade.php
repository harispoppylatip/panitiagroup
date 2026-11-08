<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login Scan Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-900: #0f172a;
            --brand-700: #1d4ed8;
            --brand-500: #2563eb;
            --surface: #f8fafc;
            --surface-elevated: #ffffff;
            --surface-container-low: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-soft: #e2e8f0;
            --shadow-card: 0px 1px 2px rgba(15, 23, 42, 0.04), 0px 4px 12px -2px rgba(15, 23, 42, 0.05);
            --toggle-bg: #f1f5f9;
            --toggle-color: #0f172a;
            --radius-sm: 0.5rem;
            --radius-lg: 0.875rem;
        }

        body[data-theme='dark'] {
            --brand-900: #f8fafc;
            --brand-700: #93c5fd;
            --brand-500: #3b82f6;
            --surface: #0b0f19;
            --surface-elevated: #111827;
            --surface-container-low: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-soft: rgba(148, 163, 184, 0.15);
            --shadow-card: 0px 1px 2px rgba(0, 0, 0, 0.25), 0px 4px 12px -2px rgba(0, 0, 0, 0.35);
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
            align-items: center;
            justify-content: center;
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
        }

        .scan-login-card {
            width: 100%;
            max-width: 440px;
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            background: var(--surface-elevated);
            box-shadow: var(--shadow-card);
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
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
            z-index: 10;
            transition: color 0.15s ease, border-color 0.15s ease;
        }

        .theme-toggle:hover {
            color: var(--brand-500);
            border-color: var(--brand-500);
        }

        .title {
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--brand-900);
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-label {
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .form-control {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-soft);
            padding: 0.6rem 0.85rem;
            font-size: 0.9375rem;
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .form-control:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--radius-sm);
            padding: 0.6rem 1rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .btn-brand:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        .btn-brand:disabled {
            background: var(--brand-500);
            border-color: var(--brand-500);
            opacity: 0.75;
            cursor: not-allowed;
        }

        .btn-back {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-soft);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.58rem 1rem;
            background: transparent;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-back:hover {
            border-color: var(--border-soft);
            color: var(--text-main);
            background: var(--surface-container-low);
        }

        .helper {
            color: var(--text-muted);
            font-size: 0.825rem;
        }
    </style>
</head>

<body>
    <button type="button" class="theme-toggle" id="themeToggle" aria-label="Ganti tema tampilan">
        <i class="bi bi-moon-stars"></i>
        <span id="themeToggleText">Dark</span>
    </button>

    <div class="scan-login-card p-4 p-md-5">
        <h1 class="title h4 mb-1">Login Scan Absensi</h1>
        <p class="subtitle mb-4">Gunakan akun scanner untuk melanjutkan.</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form id="scanLoginForm" action="{{ route('scan.login.submit') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-12">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="Masukkan username" autocomplete="username"
                    name="username" value="{{ old('username') }}" required autofocus />
            </div>

            <div class="col-12">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Masukkan password"
                    autocomplete="current-password" name="password" required />
            </div>

            <div class="col-12 d-grid pt-1">
                <button type="submit" id="submitBtn" class="btn btn-brand">Masuk ke Scanner</button>
            </div>

            <div class="col-12 d-grid">
                <a href="{{ url('/') }}" class="btn-back text-center">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>
        </form>

        <p class="helper text-center mt-4 mb-0">Akses scanner khusus untuk proses absensi QR.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'theme-mode';
            const body = document.body;
            const themeToggle = document.getElementById('themeToggle');
            const themeToggleText = document.getElementById('themeToggleText');
            const loginForm = document.getElementById('scanLoginForm');
            const submitBtn = document.getElementById('submitBtn');
            let isSubmitting = false;

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    isSubmitting = true;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                });
            }

            // Tangani pemulihan dari back-forward cache browser pada smartphone
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    window.location.reload();
                } else if (submitBtn && isSubmitting) {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Masuk ke Scanner';
                }
            });

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
