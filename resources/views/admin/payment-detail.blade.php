@extends('layout.masteradmin')
@section('konten')
    <div class="container payment-detail-page">
        <div class="mb-4">
            <a href="{{ route('admin.payment.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke History
            </a>
        </div>

        <div class="row g-4">
            <!-- Bukti Transfer -->
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-light border-0 p-4">
                        <h5 class="mb-0">Bukti Transfer</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if ($payment->proof_path)
                            <img src="{{ asset('storage/' . $payment->proof_path) }}" 
                                alt="Bukti Pembayaran" 
                                class="img-fluid rounded" 
                                style="max-width: 100%; height: auto; object-fit: contain;">
                            <p class="text-muted mt-3 mb-0">
                                <small>{{ $payment->proof_name ?? 'Bukti Pembayaran' }}</small>
                            </p>
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $payment->proof_path) }}" 
                                    download class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-circle"></i> Tidak ada bukti transfer yang tersimpan
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="col-lg-6">
                <!-- Info Anggota -->
                @if ($member)
                    <div class="card shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-header bg-light border-0 p-4">
                            <h5 class="mb-0">Data Anggota</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted">Nama</small>
                                    <p class="fw-bold mb-0">{{ $member->nama ?? '-' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <small class="text-muted">NIM</small>
                                    <p class="fw-bold mb-0">{{ $member->Nim ?? '-' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <small class="text-muted">Angkatan</small>
                                    <p class="fw-bold mb-0">{{ $member->angkatan ?? '-' }}</p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <small class="text-muted">Jurusan</small>
                                    <p class="fw-bold mb-0">{{ $member->jurusan ?? '-' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">Email</small>
                                    <p class="fw-bold mb-0">{{ $member->Email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm mb-4 border-warning" style="border-radius: 12px;">
                        <div class="card-header bg-light border-0 p-4">
                            <h5 class="mb-0">Data Pembayaran</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i> Pembayaran dari Non-Anggota / Manual Entry
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Detail Transaksi -->
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-light border-0 p-4">
                        <h5 class="mb-0">Detail Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Nominal -->
                        <div class="mb-4 pb-4 border-bottom">
                            <small class="text-muted d-block mb-2">Nominal Pembayaran</small>
                            <h4 class="text-success mb-0">Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}</h4>
                        </div>

                        <!-- Tanggal Verifikasi -->
                        <div class="mb-4 pb-4 border-bottom">
                            <small class="text-muted d-block mb-2">Tanggal & Waktu Verifikasi</small>
                            <p class="fw-bold mb-0">
                                {{ $payment->occurred_at ? $payment->occurred_at->format('d MMMM Y, H:i') : '-' }}
                            </p>
                        </div>

                        <!-- Tipe Aktivitas -->
                        <div class="mb-4 pb-4 border-bottom">
                            <small class="text-muted d-block mb-2">Tipe Aktivitas</small>
                            <p class="fw-bold mb-0">
                                @if ($payment->activity_type === 'payment')
                                    <span class="badge bg-success">Pembayaran Iuran</span>
                                @elseif ($payment->activity_type === 'send_funds')
                                    <span class="badge bg-info">Kirim Dana</span>
                                @else
                                    <span class="badge bg-secondary">{{ $payment->activity_type }}</span>
                                @endif
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="mb-4 pb-4 border-bottom">
                            <small class="text-muted d-block mb-2">Status Transaksi</small>
                            <p class="fw-bold mb-0">
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Terverifikasi
                                </span>
                            </p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <small class="text-muted d-block mb-2">Deskripsi</small>
                            <p class="mb-0">
                                {{ $payment->description ?? $payment->title ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Teknis -->
                <div class="card shadow-sm mt-4 border-secondary" style="border-radius: 12px;">
                    <div class="card-header bg-light border-0 p-3">
                        <h6 class="mb-0 text-muted">Info Teknis</h6>
                    </div>
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-2">Order ID</small>
                        <code class="d-block mb-3 p-2 bg-light rounded">{{ $payment->order_id }}</code>
                        
                        <small class="text-muted d-block mb-2">ID Transaksi</small>
                        <code class="d-block p-2 bg-light rounded">#{{ $payment->id }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .payment-detail-page {
            padding: 2rem 0;
            min-height: 100vh;
        }

        .card {
            border: 1px solid #dee2e6;
        }

        .card-header {
            border-bottom: 1px solid #dee2e6;
        }

        .card-body img {
            max-height: 600px;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .card-body img {
                max-height: 400px;
            }

            .row.g-4 {
                gap: 2rem !important;
            }
        }
    </style>
@endsection
