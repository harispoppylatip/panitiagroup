@extends('layout.master')
@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <!-- Heading Section -->
                            <div class="text-center mb-5">
                                <h1 class="h2 fw-bold mb-2" style="color: var(--text-main); font-size: 2rem;">Login</h1>
                                <p class="text-muted mb-0" style="font-size: 0.95rem;">
                                    Untuk <span class="fw-semibold">Admin</span> | <span class="fw-semibold">Akuntan</span>
                                    | <span class="fw-semibold">Anggota</span>
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.login.submit') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="login_input" class="form-label fw-semibold">Email atau Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"
                                            style="border-radius: 8px 0 0 8px;">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" id="login_input" name="login_input"
                                            class="form-control border-0" value="{{ old('login_input') }}"
                                            placeholder="Masukan email atau username" style="border-radius: 0 8px 8px 0;"
                                            required autofocus>
                                    </div>
                                    <small class="text-muted d-block mt-1">Gunakan email atau username Anda untuk
                                        masuk</small>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"
                                            style="border-radius: 8px 0 0 8px;">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" id="password" name="password" class="form-control border-0"
                                            placeholder="Masukan password Anda" style="border-radius: 0 8px 8px 0;"
                                            required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-brand w-100 fw-semibold py-2"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
