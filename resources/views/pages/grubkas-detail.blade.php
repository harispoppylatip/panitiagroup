@extends('layout.master')
@section('head')
@endsection

@section('konten')
    @php
        use App\Models\GrubkasActivityLog;

        $latestPayment = GrubkasActivityLog::where('user_nim', $nimuser)
            ->whereIn('transaction_status', ['awaiting_confirmation', 'paid', 'cancelled', 'failed'])
            ->latest('occurred_at')
            ->first();

        $isAwaitingConfirmation = $latestPayment?->transaction_status === 'awaiting_confirmation';
    @endphp

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="grubkas-detail-card card border-0 shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <h1 class="h3 fw-bold mb-2 grubkas-title">Detail Pembayaran Anggota Grub</h1>
                                <p class="grubkas-subtitle mb-0">Atur nominal dan keterangan pembayaran sebelum melanjutkan
                                    proses
                                    bayar.</p>
                            </div>

                            <!-- Payment Status Component -->
                            <x-payment-status :nimuser="$nimuser" />

                            <form method="POST" action="{{ url('/bayar') }}">
                                @csrf
                                <input type="hidden" name="nim" value="{{ $nimuser }}">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold grubkas-label">Nama Anggota</label>
                                        <input type="text" class="form-control grubkas-input" value="{{ $namauser }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="custom_amount" class="form-label fw-semibold grubkas-label">Jumlah yang
                                            ingin
                                            dibayar</label>
                                        <input type="number" min="1" step="1"
                                            class="form-control grubkas-input" id="custom_amount" name="custom_amount"
                                            value="{{ old('custom_amount', $jumlah) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="custom_description"
                                            class="form-label fw-semibold grubkas-label">Keterangan</label>
                                        <input type="text" class="form-control grubkas-input" id="custom_description"
                                            name="custom_description"
                                            value="{{ old('custom_description', 'Pembayaran grubkas') }}" maxlength="255"
                                            required>
                                        <small class="grubkas-help d-block mt-2">Default: Pembayaran grubkas. Bisa diganti
                                            sesuai kebutuhan.</small>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-4 gap-2">
                                        <a href="{{ route('grubkas') }}" class="btn btn-outline-secondary">Kembali</a>
                                        <button id="btnGoCheckout" type="submit" class="btn btn-brand px-4"
                                            @disabled($isAwaitingConfirmation)
                                            title="{{ $isAwaitingConfirmation ? 'Pembayaran masih sedang diverifikasi' : 'Lanjut ke checkout pembayaran' }}">
                                            Bayar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @if ($isAwaitingConfirmation)
                                <div class="alert alert-warning mt-3 mb-0 small grubkas-alert">
                                    <i class="bi bi-hourglass-split me-2"></i>
                                    Pembayaran Anda masih <strong>Sedang Verifikasi</strong>. Tombol Bayar Sekarang
                                    dinonaktifkan sampai status berubah.
                                </div>
                            @endif
                        @endsection

                        <style>
                            .grubkas-detail-card {
                                border-radius: 18px;
                                background: linear-gradient(180deg, #ffffff 0%, #f5f8fc 100%);
                                border: 1px solid rgba(148, 163, 184, 0.16);
                                box-shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
                                color: #1f2937;
                            }

                            .grubkas-title {
                                color: #0f172a;
                            }

                            .grubkas-subtitle,
                            .grubkas-help,
                            .grubkas-alert {
                                color: #64748b;
                            }

                            .grubkas-label {
                                color: #334155;
                            }

                            .grubkas-input {
                                background: #ffffff !important;
                                border-color: rgba(148, 163, 184, 0.24) !important;
                                color: #0f172a !important;
                            }

                            .grubkas-input:focus {
                                border-color: rgba(37, 99, 235, 0.9) !important;
                                box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.18) !important;
                            }

                            .grubkas-detail-card .alert,
                            .grubkas-detail-card .payment-status-section .card,
                            .grubkas-detail-card .card {
                                background: rgba(255, 255, 255, 0.82);
                            }

                            .grubkas-detail-card .text-muted {
                                color: #64748b !important;
                            }

                            .grubkas-detail-card .btn-outline-secondary {
                                color: #dbeafe;
                                border-color: rgba(148, 163, 184, 0.35);
                            }

                            .grubkas-detail-card .btn-outline-secondary:hover {
                                background: rgba(148, 163, 184, 0.12);
                            }

                            body[data-theme='dark'] .grubkas-detail-card {
                                background: linear-gradient(180deg, rgba(17, 24, 39, 0.98) 0%, rgba(10, 16, 28, 0.98) 100%);
                                border-color: rgba(148, 163, 184, 0.18);
                                box-shadow: 0 18px 60px rgba(0, 0, 0, 0.36);
                                color: #e5eefc;
                            }

                            body[data-theme='dark'] .grubkas-title {
                                color: #f8fbff;
                            }

                            body[data-theme='dark'] .grubkas-subtitle,
                            body[data-theme='dark'] .grubkas-help,
                            body[data-theme='dark'] .grubkas-alert {
                                color: rgba(226, 232, 240, 0.78);
                            }

                            body[data-theme='dark'] .grubkas-label {
                                color: #d8e3f5;
                            }

                            body[data-theme='dark'] .grubkas-input {
                                background: rgba(10, 16, 28, 0.82) !important;
                                border-color: rgba(148, 163, 184, 0.22) !important;
                                color: #f8fbff !important;
                            }

                            body[data-theme='dark'] .grubkas-detail-card .alert,
                            body[data-theme='dark'] .grubkas-detail-card .payment-status-section .card,
                            body[data-theme='dark'] .grubkas-detail-card .card {
                                background: rgba(255, 255, 255, 0.03);
                            }

                            body[data-theme='dark'] .grubkas-detail-card .text-muted {
                                color: rgba(203, 213, 225, 0.72) !important;
                            }

                            body[data-theme='dark'] .grubkas-detail-card .btn-outline-secondary {
                                color: #dbeafe;
                                border-color: rgba(148, 163, 184, 0.35);
                            }

                            body[data-theme='dark'] .grubkas-detail-card .btn-outline-secondary:hover {
                                background: rgba(148, 163, 184, 0.12);
                            }

                            body[data-theme='dark'] .grubkas-detail-card .grubkas-alert.alert-warning {
                                background: rgba(250, 204, 21, 0.12) !important;
                                border-color: rgba(250, 204, 21, 0.24) !important;
                                color: #fde68a !important;
                            }

                            body[data-theme='dark'] .grubkas-detail-card .grubkas-alert.alert-secondary {
                                background: rgba(148, 163, 184, 0.12) !important;
                                border-color: rgba(148, 163, 184, 0.24) !important;
                                color: #cbd5e1 !important;
                            }

                            @media (max-width: 768px) {
                                .grubkas-detail-card {
                                    border-radius: 14px;
                                }
                            }
                        </style>
