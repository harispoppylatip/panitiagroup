@extends('layout.master')

@section('konten')
    <section class="checkout-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <div class="mb-3">
                        <a href="{{ route('grubkas.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Kas Grub
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-start gap-2"
                                style="border-color: var(--border-soft) !important;">
                                <div>
                                    <h1 class="h4 fw-bold mb-1" style="color: var(--brand-900);">Checkout Pembayaran</h1>
                                    <p class="text-muted small mb-0">Scan QRIS lalu unggah bukti transfer untuk konfirmasi.</p>
                                </div>
                                <div class="text-md-end">
                                    <div class="h4 fw-bold mb-0 tabular-nums" style="color: var(--brand-900);">
                                        Rp {{ number_format((int) ($amount ?? 0), 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">{{ $name ?? '-' }}</small>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif

                            <div class="row g-4 align-items-start">
                                <div class="col-md-5 text-center">
                                    @if (!empty($qrisError))
                                        <div class="alert alert-danger py-2 small mb-3">{{ $qrisError }}</div>
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;border:1px dashed var(--border-soft);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-md);">
                                            <span class="text-muted small px-3">QR belum tersedia.</span>
                                        </div>
                                    @elseif (!empty($qrimage))
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;background:#ffffff;border:1px solid var(--border-soft);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-md);padding:12px;">
                                            <img src="{{ $qrimage }}" alt="QRIS"
                                                style="width:100%;height:100%;object-fit:contain;">
                                        </div>
                                        <div class="fw-bold mt-3 tabular-nums" style="color: var(--brand-900);">
                                            Rp {{ number_format((int) ($amount ?? 0), 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted d-block">Scan QRIS di atas untuk membayar</small>
                                        @if (!empty($expired))
                                            <small class="text-muted d-block tabular-nums mt-1">Berlaku sampai
                                                {{ \Carbon\Carbon::parse($expired)->format('d M Y H:i') }}</small>
                                        @endif
                                    @else
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;border:1px dashed var(--border-soft);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-md);">
                                            <span class="text-muted small px-3">QR belum tersedia.</span>
                                        </div>
                                        <small class="text-muted d-block mt-2">Expired: {{ $expired ?? '-' }}</small>
                                    @endif
                                </div>

                                <div class="col-md-7">
                                    <form method="POST" action="{{ route('grubkas.checkout.upload') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="nim"
                                            value="{{ old('nim') ?? (request('nim') ?? '') }}" />
                                        <input type="hidden" name="name" value="{{ $name ?? '' }}" />
                                        <input type="hidden" name="amount" value="{{ $amount ?? '' }}" />
                                        <input type="hidden" name="link_code" value="{{ $link_code ?? '' }}" />
                                        <input type="hidden" name="order_id" value="{{ $order_id ?? '' }}" />

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">1. Pilih & Unggah Bukti Pembayaran</label>
                                            <input type="file" name="gambar" class="form-control" accept="image/*"
                                                required />
                                            <small class="text-muted d-block mt-1">Format JPG, PNG, atau WebP (Maksimal 5MB).</small>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-outline-primary">Unggah Bukti</button>
                                            <a href="{{ route('grubkas.index') }}"
                                                class="btn btn-outline-secondary">Batal</a>
                                        </div>
                                    </form>

                                    <hr class="my-4" style="border-color: var(--border-soft);">

                                    @if (session('proof_name'))
                                        <div class="p-3 rounded-3 mb-3"
                                            style="background: var(--surface-container-low); border: 1px solid var(--border-soft);">
                                            <div class="small text-muted">Bukti terunggah</div>
                                            <div class="fw-semibold small text-break">{{ session('proof_name') }}</div>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('grubkas.checkout.confirm') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-brand w-100 py-2" @disabled(!session('proof_path'))>
                                            2. Konfirmasi Saya Sudah Bayar
                                        </button>
                                    </form>

                                    @if (!session('proof_path'))
                                        <small class="text-muted d-block mt-2">Unggah gambar bukti pembayaran terlebih dahulu untuk mengaktifkan tombol konfirmasi.</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
