@extends('layout.masteradmin')

@section('konten')
    @php
        $folderId = config('services.google_drive.folder_id');
        $credentialsPath =
            config('services.google_drive.service_account_path') ??
            storage_path('app/google-drive/service-account.json');
    @endphp

    <div class="galeri-admin">
        <div class="galeri-shell">
            <div class="galeri-hero">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="hero-kicker mb-1">GALERI</p>
                        <h1 class="hero-title mb-2">Galeri Google Drive</h1>
                        <p class="hero-desc mb-0">
                            Foto galeri website diambil otomatis dari folder Google Drive. Cukup taruh foto di folder
                            Drive, lalu klik Sinkronkan.
                        </p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('galeri.index') }}" class="btn btn-brand" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Galeri Publik
                        </a>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="hero-stat">
                            <div class="hero-stat-label">Status Konfigurasi</div>
                            <div class="hero-stat-value">
                                @if ($configured)
                                    <span class="text-success">Terkonfigurasi</span>
                                @else
                                    <span class="text-warning">Belum Lengkap</span>
                                @endif
                            </div>
                            <div class="hero-stat-meta">
                                {{ $configured ? 'Kredensial & folder siap' : 'Perlu setup satu kali' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="hero-stat">
                            <div class="hero-stat-label">Foto di Drive</div>
                            <div class="hero-stat-value">{{ count($files) }}</div>
                            <div class="hero-stat-meta">Gambar di folder galeri</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="hero-stat">
                            <div class="hero-stat-label">Terakhir Sinkron</div>
                            <div class="hero-stat-value">
                                {{ $syncedAt ? \Carbon\Carbon::parse($syncedAt)->diffForHumans() : 'Belum pernah' }}
                            </div>
                            <div class="hero-stat-meta">Klik Sinkronkan untuk memperbarui</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="hero-stat">
                            <div class="hero-stat-label">Folder Drive</div>
                            <div class="hero-stat-value" style="font-size: 1rem; word-break: break-all;">
                                {{ $folderId ?: '-' }}
                            </div>
                            <div class="hero-stat-meta">ID folder sumber galeri</div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($configured)
                <div class="card border-0 shadow-sm mt-4">
                    <div
                        class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-bold">Sinkronisasi Galeri</h5>
                            <p class="text-muted mb-0">
                                Mengunduh semua foto dari Google Drive ke server agar tampil cepat. Foto yang sudah ada
                                akan dilewati.
                            </p>
                        </div>
                        <form action="{{ route('admin.galeri.sync') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-brand px-4">
                                <i class="bi bi-arrow-repeat me-1"></i> Sinkronkan Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Foto di Galeri ({{ count($files) }})</h5>

                        @if (count($files) > 0)
                            <div class="row g-2">
                                @foreach ($files as $file)
                                    <div class="col-4 col-md-3 col-lg-2">
                                        <a href="{{ route('galeri.photo', $file['id']) }}" target="_blank"
                                            class="d-block text-decoration-none">
                                            <img src="{{ route('galeri.photo', $file['id']) }}" alt="{{ $file['name'] }}"
                                                loading="lazy" class="img-fluid rounded-3 border"
                                                style="aspect-ratio: 4/3; object-fit: cover; width: 100%;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                Folder Drive masih kosong. Taruh foto di folder galeri, lalu klik Sinkronkan Sekarang.
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Setup Google Drive (Satu Kali)</h5>
                        <p class="text-muted">
                            Ikuti langkah-langkah berikut agar website bisa membaca foto dari Google Drive.
                        </p>

                        <ol class="setup-steps">
                            <li>
                                <strong>Buat project di Google Cloud</strong> — buka
                                <a href="https://console.cloud.google.com" target="_blank"
                                    rel="noopener">console.cloud.google.com</a>,
                                login dengan akun Google yang dipakai untuk Drive, lalu buat project baru (gratis).
                            </li>
                            <li>
                                <strong>Aktifkan Google Drive API</strong> — di menu
                                <em>APIs &amp; Services → Library</em>, cari <em>Google Drive API</em>, klik lalu
                                <em>Enable</em>.
                            </li>
                            <li>
                                <strong>Buat Service Account</strong> — buka <em>APIs &amp; Services → Credentials →
                                    Create Credentials → Service Account</em>. Isi nama bebas, lalu klik
                                <em>Create and Continue</em> (role boleh dibiarkan None), <em>Done</em>.
                            </li>
                            <li>
                                <strong>Unduh kunci JSON</strong> — pada daftar service account, klik ikon pensil
                                (Edit) → tab <em>Keys → Add Key → Create new key → JSON</em> → file terunduh.
                            </li>
                            <li>
                                <strong>Salin file JSON</strong> ke folder:
                                <code>storage/app/google-drive/service-account.json</code>
                                di project ini.
                            </li>
                            <li>
                                <strong>Buat folder galeri di Google Drive</strong> (misal "Galeri PAZ"),
                                lalu <em>klik kanan folder → Share → tambahkan email service account</em>
                                (berakhiran <code>@...iam.gserviceaccount.com</code>) dengan akses <em>Viewer</em>.
                            </li>
                            <li>
                                <strong>Isi folder ID di .env</strong> — buka folder galeri di browser, ambil ID pada
                                URL (bagian <code>folders/XXXX</code>), lalu set:
                                <code>GOOGLE_DRIVE_FOLDER_ID=XXXX</code>
                            </li>
                            <li>
                                <strong>Jalankan</strong> <code>php artisan config:clear</code> lalu kembali ke halaman
                                ini dan klik Sinkronkan.
                            </li>
                        </ol>

                        <p class="text-muted mt-3 mb-1">Status saat ini:</p>
                        <ul class="mb-0">
                            <li>File kredensial:
                                <code>{{ $credentialsPath }}</code>
                                — {{ is_file($credentialsPath) ? 'ada' : 'belum ada' }}
                            </li>
                            <li>Folder ID: {{ $folderId ?: 'belum diisi' }}</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .galeri-admin {
            padding: 0 0 2.5rem;
        }

        .galeri-shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .galeri-hero {
            background: radial-gradient(circle at top left, rgba(46, 91, 135, 0.20), transparent 36%), radial-gradient(circle at bottom right, rgba(195, 143, 60, 0.18), transparent 34%), linear-gradient(135deg, rgba(31, 59, 92, 0.96), rgba(18, 38, 63, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #ffffff;
            border-radius: 18px;
            padding: 1.6rem;
            box-shadow: 0 18px 40px rgba(18, 38, 63, 0.18);
        }

        .hero-kicker {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.84rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .hero-title {
            font-size: clamp(1.7rem, 3vw, 2.3rem);
            font-weight: 800;
            margin: 0.35rem 0 0.5rem;
            letter-spacing: -0.02em;
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.78);
            max-width: 700px;
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            height: 100%;
        }

        .hero-stat-label {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 0.35rem;
        }

        .hero-stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.15;
        }

        .hero-stat-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.72);
            margin-top: 0.25rem;
        }

        .setup-steps {
            padding-left: 1.4rem;
            line-height: 1.7;
        }

        .setup-steps li {
            margin-bottom: 0.65rem;
        }

        .setup-steps code {
            background: rgba(18, 38, 63, 0.07);
            padding: 0.1rem 0.4rem;
            border-radius: 6px;
            font-size: 0.88em;
        }
    </style>
@endsection
