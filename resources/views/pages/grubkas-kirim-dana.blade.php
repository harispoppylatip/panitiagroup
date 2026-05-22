@extends('layout.master')

@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <span class="badge text-bg-dark mb-3">Dummy send-fund</span>
                                    <h1 class="h4 mb-1">Kirim Dana</h1>
                                    <p class="text-muted mb-0">Halaman desain statis tanpa form, tanpa API, tanpa backend.
                                    </p>
                                </div>
                                <a href="/grubkas" class="btn btn-outline-secondary">Kembali</a>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-4 rounded-4 h-100"
                                        style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <div class="fw-semibold mb-2">Tujuan pengiriman</div>
                                        <div class="text-muted">Contoh: kegiatan, rapat, atau kebutuhan kelompok.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 rounded-4 h-100"
                                        style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <div class="fw-semibold mb-2">Nominal</div>
                                        <div class="text-muted">Rp 0</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info border-0 mb-0">
                                        Tombol pengiriman dan pencatatan kas sudah dihapus. Yang tersisa hanya tampilan
                                        dummy agar halaman tetap bisa dibuka.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
