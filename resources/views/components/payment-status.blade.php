@props(['nimuser'])

@php
    use App\Models\Datasikadmodel;
    use App\Models\GrubkasActivityLog;

    $member = Datasikadmodel::where('Nim', $nimuser)->with('latestIuran')->first();
    $latestIuran = optional($member)->latestIuran;
    $currentDebt = max(0, (int) optional($latestIuran)->Nominal);

    // Get latest payment for this user
    $latestPayment = GrubkasActivityLog::where('user_nim', $nimuser)
        ->whereIn('transaction_status', ['awaiting_confirmation', 'paid', 'cancelled', 'failed'])
        ->latest('occurred_at')
        ->first();

    $paymentStatus = 'belum_bayar';
    $statusLabel = 'Belum Bayar';
    $statusBadgeClass = 'bg-secondary';
    $statusIcon = 'bi-circle';
    $showProof = false;
    $proofPath = null;
    $proofName = null;
    $rejectionReason = null;
    $lastUpdated = null;
    $debtMessage = null;

    if ($latestPayment) {
        $lastUpdated = $latestPayment->occurred_at;

        if ($latestPayment->transaction_status === 'awaiting_confirmation') {
            $paymentStatus = 'sedang_verifikasi';
            $statusLabel = 'Sedang Verifikasi';
            $statusBadgeClass = 'bg-warning';
            $statusIcon = 'bi-hourglass-split';
            $showProof = true;
            $proofPath = $latestPayment->proof_path;
            $proofName = $latestPayment->proof_name;
        } elseif ($latestPayment->transaction_status === 'paid' && $currentDebt === 0) {
            $paymentStatus = 'sudah_bayar';
            $statusLabel = 'Sudah Bayar';
            $statusBadgeClass = 'bg-success';
            $statusIcon = 'bi-check-circle';
        } elseif ($latestPayment->transaction_status === 'cancelled') {
            $paymentStatus = 'ditolak';
            $statusLabel = 'Ditolak - Upload Ulang';
            $statusBadgeClass = 'bg-danger';
            $statusIcon = 'bi-x-circle';
            $showProof = true;
            $proofPath = $latestPayment->proof_path;
            $proofName = $latestPayment->proof_name;
            $rejectionReason = $latestPayment->description;
        }
    }

    // If there is still debt, status must stay "Belum Bayar" even after a paid transaction.
    if ($paymentStatus !== 'sedang_verifikasi' && $currentDebt > 0) {
        $paymentStatus = 'belum_bayar';
        $statusLabel = 'Belum Bayar';
        $statusBadgeClass = 'bg-secondary';
        $statusIcon = 'bi-circle';
        $debtMessage = 'Sisa utang Anda saat ini: Rp ' . number_format($currentDebt, 0, ',', '.');
    }
@endphp

<div class="payment-status-section mb-4">
    <div class="card payment-status-card border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="payment-status-icon">
                        <i class="bi {{ $statusIcon }}"></i>
                    </div>
                </div>
                <div class="col">
                    <h6 class="mb-1 fw-bold payment-status-title">Status Pembayaran</h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                        @if ($lastUpdated)
                            <small class="text-muted">
                                {{ $lastUpdated->format('d M Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            @if ($paymentStatus === 'sedang_verifikasi')
                <div class="alert alert-warning mt-3 mb-0 small payment-status-alert warning">
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>Pembayaran Anda sedang diverifikasi oleh admin.</strong>
                    <br>Anda akan diberitahu setelah admin mengecek bukti pembayaran.
                </div>
            @elseif ($paymentStatus === 'sudah_bayar')
                <div class="alert alert-success mt-3 mb-0 small payment-status-alert success">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Pembayaran telah diterima!</strong>
                    <br>Terima kasih telah melunasi iuran Anda.
                </div>
            @elseif ($paymentStatus === 'ditolak')
                <div class="alert alert-danger mt-3 mb-0 small payment-status-alert danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Pembayaran Ditolak</strong>
                    <br>Mohon upload ulang bukti pembayaran dengan benar.
                    @if ($rejectionReason)
                        <br><span class="fw-semibold">Alasan:</span> {{ $rejectionReason }}
                    @endif
                </div>
            @elseif ($paymentStatus === 'belum_bayar' && $debtMessage)
                <div class="alert alert-secondary mt-3 mb-0 small payment-status-alert neutral">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Masih ada tagihan aktif.</strong>
                    <br>{{ $debtMessage }}
                </div>
            @endif

            @if ($showProof && $proofPath)
                <div class="mt-3 pt-3 border-top">
                    <p class="mb-2 small fw-bold">Bukti Pembayaran Terakhir:</p>
                    <div class="d-flex gap-2 align-items-center">
                        <img src="{{ asset('storage/' . $proofPath) }}" alt="Bukti Pembayaran"
                            class="payment-proof-thumb"
                            style="max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;">
                        <div>
                            <small class="text-muted d-block">{{ $proofName ?? 'Bukti Pembayaran' }}</small>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="modal"
                                data-bs-target="#fullProofModal">
                                <i class="bi bi-expand"></i> Lihat Ukuran Penuh
                            </button>
                        </div>
                    </div>

                    <!-- Full Proof Modal -->
                    <div class="modal fade" id="fullProofModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Bukti Pembayaran</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body d-flex justify-content-center">
                                    <img src="{{ asset('storage/' . $proofPath) }}" alt="Bukti Pembayaran"
                                        class="img-fluid rounded d-block mx-auto payment-proof-modal-image"
                                        style="max-height: 500px; object-fit: contain;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <a href="{{ asset('storage/' . $proofPath) }}" download class="btn btn-primary">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .payment-status-section {
        animation: slideDown 0.3s ease-out;
    }

    .payment-status-card {
        background: linear-gradient(135deg, #f8fbff 0%, #eef4fb 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        color: #1f2937;
    }

    .payment-status-title {
        color: #111827;
    }

    .payment-status-alert {
        border-width: 1px;
    }

    body[data-theme='dark'] .payment-status-card {
        background: linear-gradient(180deg, rgba(16, 24, 40, 0.96) 0%, rgba(10, 16, 28, 0.96) 100%);
        border-color: rgba(148, 163, 184, 0.16);
        color: #e5eefc;
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.34);
    }

    body[data-theme='dark'] .payment-status-title {
        color: #f8fbff;
    }

    body[data-theme='dark'] .payment-status-alert.warning {
        background: rgba(250, 204, 21, 0.12) !important;
        border-color: rgba(250, 204, 21, 0.24) !important;
        color: #fde68a;
    }

    body[data-theme='dark'] .payment-status-alert.success {
        background: rgba(34, 197, 94, 0.1) !important;
        border-color: rgba(34, 197, 94, 0.22) !important;
        color: #bbf7d0;
    }

    body[data-theme='dark'] .payment-status-alert.danger {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: rgba(239, 68, 68, 0.22) !important;
        color: #fecaca;
    }

    body[data-theme='dark'] .payment-status-alert.neutral {
        background: rgba(148, 163, 184, 0.1) !important;
        border-color: rgba(148, 163, 184, 0.22) !important;
        color: #cbd5e1;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .payment-status-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.82);
        border-radius: 50%;
        font-size: 1.5rem;
        color: #6c757d;
    }

    body[data-theme='dark'] .payment-status-icon {
        background: rgba(255, 255, 255, 0.06);
        color: #93c5fd;
    }

    .payment-status-icon i {
        color: inherit;
    }

    .payment-status-section .bg-success .payment-status-icon {
        color: #198754;
    }

    .payment-status-section .bg-warning .payment-status-icon {
        color: #ffc107;
    }

    .payment-status-section .bg-danger .payment-status-icon {
        color: #dc3545;
    }

    .payment-proof-thumb {
        display: block;
        object-fit: cover;
    }

    .payment-proof-modal-image {
        max-width: 100%;
    }
</style>
