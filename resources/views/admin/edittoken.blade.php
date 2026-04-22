@extends('layout.masteradmin')
@section('konten')
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
        }

        .edit-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .breadcrumb-section {
            margin-bottom: 2rem;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "→";
            margin: 0 0.5rem;
        }

        .breadcrumb-item.active {
            color: var(--text-main);
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: var(--brand-500);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--brand-700);
            text-decoration: underline;
        }

        .header-title {
            margin-bottom: 2rem;
        }

        .header-title h1 {
            color: var(--brand-900);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .header-title p {
            color: var(--text-muted);
        }

        .card-edit {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(18, 38, 63, 0.09);
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-title {
            color: var(--brand-700);
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            color: var(--brand-700);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border-soft);
            padding: 0.75rem 1rem;
            background: var(--surface);
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 3px rgba(46, 91, 135, 0.1);
            outline: none;
            background: var(--surface-elevated);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
        }

        .form-check {
            padding: 0;
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            margin-right: 0.75rem;
            border-radius: 4px;
            border: 1px solid var(--border-soft);
            cursor: pointer;
        }

        .form-check-input:checked {
            background: var(--brand-500);
            border-color: var(--brand-500);
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: block;
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-soft);
        }

        .btn-save {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: transparent;
            border: 2px solid var(--border-soft);
            color: var(--text-main);
            font-weight: 700;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            border-color: var(--brand-500);
            background: var(--surface);
            color: var(--brand-500);
        }

        .info-box {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .info-box.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-left-color: #f59e0b;
        }

        body[data-theme='dark'] .info-box {
            background: rgba(30, 58, 138, 0.3);
            border-left-color: #60a5fa;
        }

        body[data-theme='dark'] .info-box.warning {
            background: rgba(78, 70, 20, 0.3);
            border-left-color: #fbbf24;
        }

        .info-box-text {
            font-size: 0.9rem;
            margin: 0;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn-save,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="edit-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-section" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.membertoken') }}">Management Token</a></li>
                <li class="breadcrumb-item active">Edit Token #{{ $data->id }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="header-title">
            <h1><i class="bi bi-pencil-square"></i> Edit Token</h1>
            <p>Perbarui data token untuk user <strong>{{ $data->nama }}</strong></p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <p class="info-box-text">
                <i class="bi bi-info-circle"></i>
                <strong>Catatan:</strong> Pastikan token yang diinput valid dan masih berlaku. Token yang expired akan
                menyebabkan kegagalan pada saat scanning.
            </p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert-danger">
                <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card-edit">
            <div class="card-body">
                <form action="{{ route('admin.token.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-person"></i> Informasi Dasar
                        </div>

                        <div class="form-group">
                            <label for="nama" class="form-label">Nama User</label>
                            <input type="text" id="nama" name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $data->nama) }}" required>
                            @error('nama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Nim" class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                            <input type="text" id="Nim" name="Nim"
                                class="form-control @error('Nim') is-invalid @enderror" value="{{ old('Nim', $data->Nim) }}"
                                required>
                            @error('Nim')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Token Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-key"></i> Data Token
                        </div>

                        <div class="form-group">
                            <label for="access_token" class="form-label">Access Token</label>
                            <textarea id="access_token" name="access_token" class="form-control @error('access_token') is-invalid @enderror"
                                required>{{ old('access_token', $data->access_token) }}</textarea>
                            <div class="form-text">Token akses untuk autentikasi ke API presensi</div>
                            @error('access_token')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="refresh_token" class="form-label">Refresh Token</label>
                            <textarea id="refresh_token" name="refresh_token" class="form-control @error('refresh_token') is-invalid @enderror"
                                required>{{ old('refresh_token', $data->refresh_token) }}</textarea>
                            <div class="form-text">Token refresh untuk memperbarui akses token yang sudah expired</div>
                            @error('refresh_token')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Settings Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-sliders"></i> Pengaturan Tambahan
                        </div>

                        <div class="form-group">
                            <label for="urlpost" class="form-label">URL Post Backend (Opsional)</label>
                            <input type="url" id="urlpost" name="urlpost"
                                class="form-control @error('urlpost') is-invalid @enderror"
                                value="{{ old('urlpost', $data->urlpost) }}" placeholder="https://example.com/api/post">
                            <div class="form-text">URL endpoint untuk mengirim data presensi (jika diperlukan)</div>
                            @error('urlpost')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="status_onoff" class="form-check-input" name="status_onoff"
                                    value="on"
                                    {{ old('status_onoff', $data->status_onoff) === 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_onoff">
                                    Aktifkan Token
                                </label>
                            </div>
                            <div class="form-text">Token ini akan digunakan untuk scanning QR absensi</div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="info-box warning">
                        <p class="info-box-text">
                            <strong>📅 Informasi Update:</strong>
                            Dibuat: {{ $data->created_at->format('d/m/Y H:i') }} |
                            Diupdate: {{ $data->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.membertoken') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
