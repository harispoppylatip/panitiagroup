@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Foto Hero Beranda</h2>
                <p class="text-muted mb-0">Ubah URL foto hero section (foto utama dan samping)</p>
            </div>
            <a href="{{ route('admin.beranda.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

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

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.beranda.update-hero') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Main Image -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-image"></i> Foto Utama (Main)
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Upload Foto</label>
                                <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                                    name="main_image" accept="image/*">
                                <small class="text-muted d-block mt-1">Max 5MB (JPG, PNG, GIF, WebP)</small>
                                @error('main_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Atau URL Foto</label>
                                <input type="url" class="form-control @error('main_image_url') is-invalid @enderror"
                                    name="main_image_url"
                                    value="{{ old('main_image_url', $heroImages->get('main')?->image_url) }}"
                                    placeholder="https://example.com/image.jpg">
                                @error('main_image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alt Text (Deskripsi)</label>
                                <input type="text" class="form-control @error('main_alt_text') is-invalid @enderror"
                                    name="main_alt_text"
                                    value="{{ old('main_alt_text', $heroImages->get('main')?->alt_text) }}"
                                    placeholder="Foto utama tim">
                                @error('main_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($heroImages->get('main'))
                                <div class="col-12">
                                    <img src="{{ $heroImages->get('main')->image_url }}" class="img-fluid rounded"
                                        style="max-height: 300px; width: 100%; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Side Images --> mb-2">Foto Samping 1</label>
                    <div class="mb-2">
                        <label class="form-label form-label-sm">Upload</label>
                        <input type="file" class="form-control @error('side1_image') is-invalid @enderror"
                            name="side1_image" accept="image/*">
                        <small class="text-muted d-block mt-1">Max 5MB</small>
                    </div>
                    <div>
                        <label class="form-label form-label-sm">Atau URL</label>
                        <input type="url" class="form-control @error('side1_image_url') is-invalid @enderror"
                            name="side1_image_url"
                            value="{{ old('side1_image_url', $heroImages->get('side1')?->image_url) }}"
                            placeholder="https://example.com/image.jpg">
                        @error('side1_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted d-block mt-2">Alt Text</small>
                    <input type="text" class="form-control form-control-sm @error('side1_alt_text') is-invalid @enderror"
                        name="side1_alt_text" value="{{ old('side1_alt_text', $heroImages->get('side1')?->alt_text) }}"
                        placeholder="Deskripsi foto">
                    @if ($heroImages->get('side1'))
                        <img src="{{ $heroImages->get('side1')->image_url }}" class="img-fluid rounded mt-3"
                            style="max-height: 200px; width: 100%; object-fit: cover;">
                    @endif
            </div>

            <!-- Side 2 -->
            <div class="col-md-6">
                <label class="form-label mb-2">Foto Samping 2</label>
                <div class="mb-2">
                    <label class="form-label form-label-sm">Upload</label>
                    <input type="file" class="form-control @error('side2_image') is-invalid @enderror" name="side2_image"
                        accept="image/*">
                    <small class="text-muted d-block mt-1">Max 5MB</small>
                </div>
                <div>
                    <label class="form-label form-label-sm">Atau URL</label>
                    <input type="url" class="form-control @error('side2_image_url') is-invalid @enderror"
                        name="side2_image_url" value="{{ old('side2_image_url', $heroImages->get('side2')?->image_url) }}"
                        placeholder="https://example.com/image.jpg">
                    @error('side2_image_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>->
                <div class="col-md-6">
                    <label class="form-label">Foto Samping 2</label>
                    <input type="url" class="form-control @error('side2_image_url') is-invalid @enderror"
                        name="side2_image_url" value="{{ old('side2_image_url', $heroImages->get('side2')?->image_url) }}"
                        placeholder="https://example.com/image.jpg" required>
                    @error('side2_image_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-2">Alt Text</small>
                    <input type="text"
                        class="form-control form-control-sm @error('side2_alt_text') is-invalid @enderror"
                        name="side2_alt_text" value="{{ old('side2_alt_text', $heroImages->get('side2')?->alt_text) }}"
                        placeholder="Deskripsi foto">
                    @if ($heroImages->get('side2'))
                        Anda bisa upload file gambar atau gunakan URL gambar eksternal. Jika upload file, foto akan disimpan
                        di server. Ukuran maksimal file: 5MB (JPG, PNG, GIF, WebP)
                        style="max-height: 200px; width: 100%; object-fit: cover;">
                    @endif
                </div>
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
    </div>

    <div class="alert alert-info mt-4">
        <strong>Tip:</strong> Gunakan URL lengkap dari gambar (contoh: https://images.unsplash.com/photo-...). Pastikan URL
        valid dan gambar dapat diakses publik.
    </div>
    </div>
@endsection
