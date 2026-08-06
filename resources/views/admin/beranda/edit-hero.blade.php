@extends('layout.masteradmin')

@section('konten')
    <style>
        .hero-upload-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .hero-preview {
            max-height: 220px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 1px solid var(--border-soft);
            background: var(--surface-container-low);
        }

        .photo-label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Foto Hero Beranda</h2>
                <p class="text-muted mb-0">Ubah foto utama hero melalui upload gambar</p>
            </div>
            <a href="{{ route('admin.beranda.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.beranda.update-hero') }}" method="POST" enctype="multipart/form-data" id="heroForm">
            @csrf
            @method('PUT')

            <!-- Foto Utama -->
            <div class="hero-upload-card p-4 mb-4">
                <h6 class="fw-bold mb-1">Foto Utama (Main)</h6>
                <small class="text-muted d-block mb-3">Tampil sebagai latar hero di halaman beranda</small>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label photo-label">Upload Foto</label>
                        <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                            name="main_image" accept="image/jpeg,image/png,image/gif,image/webp" data-max-mb="5">
                        <small class="text-muted d-block mt-1">Max 5MB (JPG, PNG, GIF, WebP)</small>
                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label photo-label">Alt Text (Deskripsi)</label>
                        <input type="text" class="form-control @error('main_alt_text') is-invalid @enderror"
                            name="main_alt_text" value="{{ old('main_alt_text', $heroImages->get('main')?->alt_text) }}"
                            placeholder="Foto utama tim">
                        @error('main_alt_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($heroImages->get('main'))
                        <div class="col-12">
                            <label class="form-label photo-label">Foto Saat Ini</label>
                            <img src="{{ $heroImages->get('main')->image_url }}" alt="Foto utama saat ini"
                                class="hero-preview">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.beranda.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        // Cek ukuran file di sisi klien sebelum dikirim, maksimal 5MB per foto
        document.addEventListener('DOMContentLoaded', function() {
            var MAX_MB = 5;
            var MAX_BYTES = MAX_MB * 1024 * 1024;

            document.querySelectorAll('input[type="file"][data-max-mb]').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (this.files.length === 0) return;
                    var file = this.files[0];
                    if (file.size > MAX_BYTES) {
                        var mb = (file.size / (1024 * 1024)).toFixed(2);
                        alert('File "' + file.name + '" berukuran ' + mb +
                            ' MB, melebihi batas maksimal ' + MAX_MB +
                            ' MB. Silakan pilih gambar yang lebih kecil.');
                        this.value = '';
                    }
                });
            });
        });
    </script>
@endsection
