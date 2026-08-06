@extends('layout.master')

@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <h1 class="h4 mb-1">Pembayaran Terkirim</h1>
                                <p class="text-muted mb-0">Bukti pembayaran berhasil dikirim dan sedang menunggu
                                    konfirmasi admin.</p>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3"
                                        style="background: var(--surface-container-lowest); border: 1px solid var(--border-soft);">
                                        <div class="small text-muted">Nama</div>
                                        <div class="fw-semibold">{{ $data->datasikad->nama ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3"
                                        style="background: var(--surface-container-lowest); border: 1px solid var(--border-soft);">
                                        <div class="small text-muted">NIM</div>
                                        <div class="fw-semibold">{{ $data->Nim_key ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3"
                                        style="background: var(--surface-container-lowest); border: 1px solid var(--border-soft);">
                                        <div class="small text-muted">Total utang</div>
                                        <div class="fw-semibold">Rp
                                            {{ number_format((int) ($data->Utang_Anggota ?? 0), 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3"
                                        style="background: var(--surface-container-lowest); border: 1px solid var(--border-soft);">
                                        <div class="small text-muted">Nominal dibayar</div>
                                        <div class="fw-semibold">Rp
                                            {{ number_format((int) ($data->Nominal_Bayar ?? 0), 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3"
                                        style="background: var(--surface-container-lowest); border: 1px solid var(--border-soft);">
                                        <div class="small text-muted">Status</div>
                                        <div class="fw-semibold">{{ $data->Status?->Status ?? 'Menunggu Konfirmasi' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($data->Keterangan)
                                <div class="alert alert-info border-0 mb-4">{{ $data->Keterangan }}</div>
                            @endif

                            @if ($data->Bukti_Pembayaran)
                                <div class="mb-4">
                                    <p class="small text-muted mb-2">Bukti yang diunggah</p>
                                    <a href="{{ asset('storage/' . $data->Bukti_Pembayaran) }}" target="_blank"
                                        rel="noopener" class="btn btn-outline-secondary btn-sm">Lihat Bukti Pembayaran</a>
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('grubkas.index') }}" class="btn btn-brand">Kembali ke Kas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
