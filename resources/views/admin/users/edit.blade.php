@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit User</h2>
                <p class="text-muted mb-0">Update informasi user</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Admin</option>
                                <option value="scanabsen" {{ old('role', $user->role) === 'scanabsen' ? 'selected' : '' }}>
                                    Scan Absen</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <hr class="my-2">
                            <p class="text-muted small mb-2">Kosongkan password jika tidak ingin mengubah</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">
                            Lihat Detail
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
