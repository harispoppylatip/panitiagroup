@props(['nimuser' => null])

<div class="payment-status-section mb-4">
    <div class="card payment-status-card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="payment-status-icon">
                    <i class="bi bi-slash-circle"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold payment-status-title">Status Pembayaran Dinonaktifkan</h6>
                    <p class="mb-0 text-muted">Component ini dipertahankan sebagai desain dummy. Tidak ada query
                        database, tidak ada status verifikasi, dan tidak ada bukti pembayaran.</p>
                </div>
            </div>
            <div class="alert alert-secondary mt-3 mb-0 small payment-status-alert neutral">
                <i class="bi bi-info-circle me-2"></i>
                Backend payment sudah dihapus, jadi komponen ini hanya tampil visual.
            </div>
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
        flex-shrink: 0;
    }

    .payment-status-icon i {
        color: inherit;
    }
</style>
