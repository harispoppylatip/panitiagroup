@extends('layout.masteradmin')
@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <h1 class="h3 fw-bold mb-2 text-dark">Form Insert Token</h1>
                                <p class="text-muted mb-0">Tambahkan data integrasi backend untuk scanning presensi QR.</p>
                            </div>

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

                            <form action="{{ route('admin.savetoken') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label fw-semibold">Nama</label>
                                        <input type="text" id="nama" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan nama" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Nim" class="form-label fw-semibold">NIM</label>
                                        <input type="text" id="Nim" name="Nim"
                                            class="form-control @error('Nim') is-invalid @enderror"
                                            placeholder="Masukkan NIM" value="{{ old('Nim') }}" required>
                                        @error('Nim')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="access_token" class="form-label fw-semibold">Access Token</label>
                                        <textarea id="access_token" name="access_token" class="form-control @error('access_token') is-invalid @enderror"
                                            rows="3" placeholder="Masukkan access token" required>{{ old('access_token') }}</textarea>
                                        @error('access_token')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="refresh_token" class="form-label fw-semibold">Refresh Token</label>
                                        <textarea id="refresh_token" name="refresh_token" class="form-control @error('refresh_token') is-invalid @enderror"
                                            rows="3" placeholder="Masukkan refresh token" required>{{ old('refresh_token') }}</textarea>
                                        @error('refresh_token')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="urlpost" class="form-label fw-semibold">URL Post Backend
                                            (Opsional)</label>
                                        <input type="url" id="urlpost" name="urlpost"
                                            class="form-control @error('urlpost') is-invalid @enderror"
                                            placeholder="https://example.com/api/post" value="{{ old('urlpost') }}">
                                        @error('urlpost')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="status_onoff"
                                                name="status_onoff" value="on"
                                                {{ old('status_onoff') === 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_onoff">
                                                Aktifkan token ini untuk scanning
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('admin.membertoken') }}"
                                        class="btn btn-outline-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-brand px-4">Simpan Token</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
