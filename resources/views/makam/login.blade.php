<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Makam Admin Panel</title>
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
            --accent: #40916c;
            --surface: #f0f4f2;
            --text-main: #0d2818;
            --text-muted: #5a7a6a;
            --card-bg: #ffffff;
            --border-soft: rgba(13, 40, 24, 0.1);
        }

        body[data-theme='dark'] {
            --brand-900: #d8f3dc;
            --accent: #52b788;
            --surface: #081c11;
            --text-main: #d8f3dc;
            --text-muted: #95b5a5;
            --card-bg: rgba(10, 30, 18, 0.92);
            --border-soft: rgba(200, 220, 210, 0.12);
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Source Sans 3', sans-serif;
            color: var(--text-main);
            background: linear-gradient(135deg, #0d2818 0%, #1a3d2b 50%, #2d6a4f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
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

        .login-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-soft);
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-control {
            background-color: var(--card-bg);
            color: var(--text-main);
            border: 2px solid var(--border-soft);
            border-radius: 10px;
            padding: 0.7rem 1rem;
        }

        .form-control:focus {
            background-color: var(--card-bg);
            color: var(--text-main);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(64, 145, 108, 0.15);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-makam {
            background: var(--accent);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            padding: 0.7rem;
            transition: all 0.2s ease;
        }

        .btn-makam:hover {
            background: #2d6a4f;
            color: #fff;
            transform: translateY(-1px);
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            cursor: pointer;
            z-index: 100;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <button type="button" class="theme-toggle" id="themeToggle">
        <i class="bi bi-moon-stars"></i>
        <span id="themeToggleText">Dark</span>
    </button>

    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/Logo-Makam-Mu.png') }}" alt="Makam.Mu Logo">
        </div>
        <h2 class="text-center fw-bold mb-1" style="color: var(--text-main);">Makam Admin</h2>
        <p class="text-center mb-4" style="color: var(--text-muted); font-size: 0.9rem;">Masuk ke panel administrasi</p>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('makam.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="login_input" class="form-control"
                    placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-makam w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <p class="text-center mt-3 mb-0" style="color: var(--text-muted); font-size: 0.8rem;">
            <i class="bi bi-info-circle"></i> Gunakan credential yang diberikan oleh admin
        </p>
    </div>

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
</body>

</html>
