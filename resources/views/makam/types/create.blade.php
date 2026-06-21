@extends('makam.layout')
@section('title', 'Tambah Jenis Makam')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tambah Jenis Makam</h2>
            <p class="text-muted mb-0">Tambahkan jenis makam baru untuk dijual</p>
        </div>
        <a href="{{ route('makam.types.index') }}" class="btn btn-makam-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('makam.types.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Jenis Makam <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" placeholder="Contoh: Makam Keluarga, Makam VIP" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Blok</label>
                        <input type="text" name="blok" class="form-control @error('blok') is-invalid @enderror"
                            value="{{ old('blok') }}" placeholder="Contoh: A, B, C">
                        @error('blok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga') }}" placeholder="0" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Stok Tersedia <span class="text-danger">*</span></label>
                        <input type="number" name="stok_tersedia"
                            class="form-control @error('stok_tersedia') is-invalid @enderror"
                            value="{{ old('stok_tersedia', 0) }}" min="0" required>
                        @error('stok_tersedia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active"
                                value="1" checked>
                            <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                            placeholder="Deskripsi tentang jenis makam ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-makam">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="{{ route('makam.types.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
