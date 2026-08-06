@props(['nimuser' => null])

<div class="payment-status-section mb-4">
    <div class="card payment-status-card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <h6 class="mb-1 fw-bold payment-status-title">Status Pembayaran Dinonaktifkan</h6>
                    <p class="mb-0 text-muted">Component ini dipertahankan sebagai desain dummy. Tidak ada query
                        database, tidak ada status verifikasi, dan tidak ada bukti pembayaran.</p>
                </div>
            </div>
            <div class="alert alert-secondary mt-3 mb-0 small payment-status-alert neutral">
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
        background: var(--surface-elevated);
        border: 1px solid var(--border-soft);
        color: var(--text-main);
    }

    .payment-status-title {
        color: var(--text-main);
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
</style>
