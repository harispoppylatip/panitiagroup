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
                                <p class="text-muted mb-0">Isi data integrasi backend. Halaman ini saat ini hanya tampilan
                                    form.</p>
                            </div>

                            <form action="{{ route('admin.savetoken') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label fw-semibold">Nama</label>
                                        <input type="text" id="nama" name="nama" class="form-control"
                                            placeholder="Masukkan nama">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nim" class="form-label fw-semibold">NIM</label>
                                        <input type="text" id="nim" name="nim" class="form-control"
                                            placeholder="Masukkan NIM">
                                    </div>
                                    <div class="col-12">
                                        <label for="access_token" class="form-label fw-semibold">Access Token</label>
                                        <textarea id="access_token" name="access_token" class="form-control" rows="3" placeholder="Masukkan access token"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="refresh_token" class="form-label fw-semibold">Refresh Token</label>
                                        <textarea id="refresh_token" name="refresh_token" class="form-control" rows="3"
                                            placeholder="Masukkan refresh token"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="url_post" class="form-label fw-semibold">URL Post Backend</label>
                                        <input type="url" id="url_post" name="url_post" class="form-control"
                                            placeholder="https://example.com/api/post">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-brand px-4">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
