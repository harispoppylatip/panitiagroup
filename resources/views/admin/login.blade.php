@extends('layout.master')
@section('konten')
    <section class="login-section py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7" style="max-width: 440px;">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <h1 class="h4 fw-bold mb-1" style="color: var(--brand-900);">Masuk ke Panel</h1>
                                <p class="text-muted small mb-0">
                                    Akses khusus Admin, Akuntan, dan Anggota.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.login.submit') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="login_input" class="form-label fw-semibold small">Email atau Username</label>
                                    <input type="text" id="login_input" name="login_input"
                                        class="form-control" value="{{ old('login_input') }}"
                                        placeholder="Masukkan email atau username"
                                        autocomplete="username" required autofocus>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold small">Password</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Masukkan password" autocomplete="current-password"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-brand w-100 py-2">
                                    Masuk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
