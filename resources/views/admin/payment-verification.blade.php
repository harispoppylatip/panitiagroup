@extends('layout.masteradmin')
@section('konten')
    <div class="container payment-verification-dashboard">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Verifikasi Pembayaran</h2>
                <p class="text-muted mb-0">Review bukti pembayaran dari anggota dan terima atau tolak pembayaran.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.payment.history') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-history"></i> Lihat History
                </a>
                <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Finance
                </a>
            </div>
        </div>

        @if ($pendingPayments->count() > 0)
            <div class="alert alert-warning d-flex align-items-start" role="alert">
                <i class="bi bi-clock-history me-2 flex-shrink-0"></i>
                <div>
                    <strong>Ada {{ $pendingPayments->total() }} pembayaran yang menunggu verifikasi</strong>
                    <p class="mb-0 small mt-1">Mohon segera review bukti pembayaran dari anggota di bawah ini.</p>
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($pendingPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="bg-light border-bottom">
                                    <th style="width: 120px;">Order ID</th>
                                    <th style="width: 150px;">Nama / NIM</th>
                                    <th style="width: 100px; text-align: center;">Nominal</th>
                                    <th style="width: 120px;">Upload Pada</th>
                                    <th style="width: 100px; text-align: center;">Bukti</th>
                                    <th style="width: 200px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingPayments as $payment)
                                    <tr class="border-bottom">
                                        <td>
                                            <small class="font-monospace text-primary fw-bold">
                                                {{ substr($payment->order_id, 0, 20) }}...
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $payment->user_name ?? 'Non-Anggota' }}</div>
                                            @if ($payment->user_nim)
                                                <small class="text-muted">NIM: {{ $payment->user_nim }}</small>
                                            @else
                                                <small class="text-muted text-danger">Manual Entry</small>
                                            @endif
                                        </td>
                                        <td class="text-end text-primary fw-bold">
                                            Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <small>{{ $payment->occurred_at?->format('d M Y H:i') ?? '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if ($payment->proof_path)
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                    data-bs-target="#proofModal{{ $payment->id }}">
                                                    <i class="bi bi-file-image"></i> Lihat
                                                </button>
                                            @else
                                                <span class="badge bg-secondary">Tanpa Bukti</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if ($payment->activity_type === 'payment')
                                                    <form action="{{ route('admin.payment.verify', $payment->order_id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Terima dan verifikasi pembayaran ini">
                                                            <i class="bi bi-check-circle"></i> Terima
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.payment.approve', $payment->order_id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Setujui permintaan ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Setujui permintaan ini">
                                                            <i class="bi bi-check-circle"></i> Setujui
                                                        </button>
                                                    </form>
                                                @endif
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $payment->id }}"
                                                    title="Tolak pembayaran ini">
                                                    <i class="bi bi-x-circle"></i> Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Proof Image Modal -->
                                    @if ($payment->proof_path)
                                        <div class="modal fade" id="proofModal{{ $payment->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bukti Pembayaran</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $payment->proof_path) }}"
                                                            alt="Bukti Pembayaran" class="img-fluid rounded"
                                                            style="max-height: 500px; object-fit: contain;">
                                                        <p class="text-muted mt-2 small">
                                                            {{ $payment->proof_name ?? 'Bukti Pembayaran' }}
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <a href="{{ asset('storage/' . $payment->proof_path) }}" download
                                                            class="btn btn-primary">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pembayaran</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.payment.reject', $payment->order_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-muted">Order ID:
                                                            <code>{{ $payment->order_id }}</code>
                                                        </p>
                                                        <div class="mb-3">
                                                            <label for="reason{{ $payment->id }}"
                                                                class="form-label">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="reason{{ $payment->id }}" name="rejection_reason" rows="3"
                                                                placeholder="Contoh: Bukti tidak jelas, nominal tidak sesuai, dll." required></textarea>
                                                        </div>
                                                        <div class="alert alert-info small">
                                                            <i class="bi bi-info-circle"></i>
                                                            Pembayaran akan dibatalkan di sistem dan user diminta upload
                                                            ulang.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-exclamation-circle"></i> Tolak Pembayaran
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($pendingPayments->hasPages())
                        <nav aria-label="Page navigation" class="mt-4">
                            {{ $pendingPayments->links('pagination::bootstrap-4') }}
                        </nav>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold">Tidak Ada Pembayaran Menunggu</h5>
                        <p class="text-muted">Semua pembayaran telah diverifikasi.</p>
                        <a href="{{ route('admin.finance.index') }}" class="btn btn-primary btn-sm mt-2">
                            Kembali ke Management Uang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .payment-verification-dashboard {
            padding: 2rem 0;
        }

        .table thead th {
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
        }

        .modal-body img {
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
