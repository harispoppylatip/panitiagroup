@extends('layout.masteradmin')
@section('konten')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-2">Setting Login Scan</h1>
                        <p class="text-muted mb-4">Akun ini dipakai untuk login ke halaman Scan Absensi.</p>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('admin.scan.login.setting.update') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Username Login Scan</label>
                                <input type="text" name="username" class="form-control" required
                                    value="{{ old('username', $user->username) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Kosongkan jika tidak diubah">
                                <small class="text-muted">Minimal 8 karakter.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password baru">
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-brand">Simpan Perubahan</button>
                                <a href="{{ route('scan.login') }}" class="btn btn-outline-secondary">Buka Login Scan</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
