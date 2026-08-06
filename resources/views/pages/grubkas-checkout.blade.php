@extends('layout.master')

@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-3 d-flex justify-content-between">
                                <div>
                                    <h1 class="h4 mb-1">Checkout Pembayaran</h1>
                                    <p class="text-muted mb-0">Pastikan data sudah benar sebelum mengunggah bukti.</p>
                                </div>
                                <div class="text-end">
                                    <div class="h5 mb-0">Rp {{ number_format((int) $amount ?? 0, 0, ',', '.') }}</div>
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

                            <div class="row g-3">
                                <div class="col-md-4 text-center">
                                    @if (!empty($qrisError))
                                        <div class="alert alert-danger py-2 small mb-3">{{ $qrisError }}</div>
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;border:1px dashed #d1d5db;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                                            <span class="text-muted small px-3">QR belum tersedia.</span>
                                        </div>
                                    @elseif (!empty($qrimage))
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;background:#fff;border:1px solid #d1d5db;display:flex;align-items:center;justify-content:center;border-radius:8px;padding:10px;">
                                            <img src="{{ $qrimage }}" alt="QRIS"
                                                style="width:100%;height:100%;object-fit:contain;">
                                        </div>
                                        <div class="fw-semibold mt-2">Rp
                                            {{ number_format((int) $amount ?? 0, 0, ',', '.') }}</div>
                                        <small class="text-muted d-block">Scan QRIS untuk membayar</small>
                                        @if (!empty($expired))
                                            <small class="text-muted d-block">Berlaku sampai
                                                {{ \Carbon\Carbon::parse($expired)->format('d M Y H:i') }}</small>
                                        @endif
                                    @else
                                        <div class="qr-box mx-auto"
                                            style="width:220px;height:220px;border:1px dashed #d1d5db;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                                            <span class="text-muted small px-3">QR belum tersedia.</span>
                                        </div>
                                        <small class="text-muted d-block mt-2">Expired: {{ $expired ?? '-' }}</small>
                                    @endif
                                </div>

                                <div class="col-md-8">
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
                                            <label class="form-label fw-semibold">Unggah Bukti Pembayaran</label>
                                            <input type="file" name="gambar" class="form-control" accept="image/*"
                                                required />
                                            <small class="text-muted d-block mt-2">Unggah bukti transfer atau screenshot
                                                pembayaran. <strong>Max 5MB</strong></small>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-success">Unggah Bukti</button>
                                            <a href="{{ route('grubkas.index') }}"
                                                class="btn btn-outline-secondary">Batal</a>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('grubkas.checkout.confirm') }}" class="mt-3">
                                        @csrf
                                        {{-- <input type="hidden" name="nim" value="{{ $nim }}" />
                                        <input type="hidden" name="amount" value="{{ $amount }}" />
                                        <input type="hidden" name="description" value="{{ $description }}" />
                                        <input type="hidden" name="order_id"
                                            value="{{ session('grubkas_checkout.order_id', $order_id ?? '') }}" />
                                        <input type="hidden" name="link_code"
                                            value="{{ session('grubkas_checkout.link_code', $link_code ?? '') }}" />
                                        <input type="hidden" name="proof_path"
                                            value="{{ session('proof_path') ?? '' }}" />
                                        <input type="hidden" name="proof_name"
                                            value="{{ session('proof_name') ?? '' }}" /> --}}
                                        <button type="submit" class="btn btn-brand" @disabled(!session('proof_path'))>
                                            Saya Sudah Bayar
                                        </button>
                                    </form>

                                    @if (!session('proof_path'))
                                        <small class="text-warning d-block mt-2">Upload gambar terlebih dahulu agar tombol
                                            sudah bayar aktif.</small>
                                    @endif

                                    @if (session('proof_name'))
                                        <div class="mt-3">
                                            <p class="mb-1"><strong>Bukti yang diunggah:</strong></p>
                                            <p class="text-muted">{{ session('proof_name') }}</p>
                                        </div>
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
