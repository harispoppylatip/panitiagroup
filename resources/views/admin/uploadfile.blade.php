@extends('layout.masteradmin')
@section('konten')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title mb-4 text-dark fw-bold">Upload Gambar</h2>

                        {{-- Tampilkan semua error jika ada --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-circle"></i> Validasi Gagal!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Tampilkan success message jika ada --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.upload.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="detailgambar">
                                    <i class="bi bi-pencil-square"></i> Detail Gambar
                                </label>
                                <input type="text" name="detailgambar" id="detailgambar"
                                    placeholder="Masukkan nama/detail gambar"
                                    class="form-control @error('detailgambar') is-invalid @enderror"
                                    value="{{ old('detailgambar') }}">
                                @error('detailgambar')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="gambar">
                                    <i class="bi bi-image"></i> Pilih Gambar
                                </label>
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                    id="gambar" name="gambar" accept="image/*" required>
                                <small class="d-block text-muted mt-2">
                                    <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF (Max 2MB)
                                </small>
                                @error('gambar')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cloud-upload"></i> Upload Gambar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
