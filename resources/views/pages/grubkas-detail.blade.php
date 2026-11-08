@extends('layout.master')

@section('head')
    <style>
        .detail-shell {
            max-width: 640px;
            margin: 0 auto;
        }

        .payment-card {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            background: var(--surface-elevated);
        }

        .payment-header {
            padding: 1.15rem 1.35rem;
            background: var(--surface-container-low);
            border-bottom: 1px solid var(--border-soft);
        }

        .payment-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.15rem;
            color: var(--brand-900);
        }

        .payment-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .choice-card {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            padding: 0.9rem 1rem;
            text-align: left;
            background: var(--surface-container-lowest);
            color: var(--text-main);
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
        }

        .choice-card.is-button {
            cursor: pointer;
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
        }

        .choice-card.is-button:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.2);
            outline-offset: 2px;
        }

        .choice-card.is-active {
            border-color: var(--brand-500);
            box-shadow: inset 0 0 0 1px var(--brand-500);
            background: var(--surface-container-low);
        }

        .choice-card .choice-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .choice-card .choice-value {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 0.2rem;
            color: var(--brand-900);
            font-variant-numeric: tabular-nums;
        }

        .detail-summary {
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            padding: 1.1rem 1.2rem;
        }

        .custom-amount-box {
            display: none;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-soft);
            background: var(--surface-container-low);
        }

        .custom-amount-box.is-visible {
            display: block;
        }

        .custom-amount-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.45rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.875rem;
            margin-bottom: 0.55rem;
        }

        .detail-row .label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .detail-row .value {
            color: var(--text-main);
            font-weight: 600;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .detail-row .value.is-warning {
            color: #b45309;
        }

        .detail-row .value.is-success {
            color: #15803d;
        }

        body[data-theme='dark'] .detail-row .value.is-warning {
            color: #fbbf24;
        }

        body[data-theme='dark'] .detail-row .value.is-success {
            color: #4ade80;
        }

        .detail-row .value.is-primary {
            color: var(--brand-900);
            font-size: 1rem;
            font-weight: 700;
        }

        .detail-divider {
            border-color: var(--border-soft);
            margin: 0.8rem 0;
        }

        .pay-button {
            border: 1px solid var(--brand-500);
            border-radius: var(--radius-sm);
            background: var(--brand-500);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-variant-numeric: tabular-nums;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .pay-button:hover:not(:disabled) {
            color: #ffffff;
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .pay-button:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        @media (max-width: 575.98px) {
            .choice-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('konten')
    <section class="grubkas-detail-section">
        <div class="container">
            <div class="detail-shell">
                <div class="mb-3">
                    <a href="{{ route('grubkas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Kas Grub
                    </a>
                </div>

                <form class="card payment-card" method="POST" action="{{ route('grubkas.checkout.page') }}" id="paymentForm">
                    @csrf
                    <div class="payment-header">
                        <div class="payment-title">Rincian Pembayaran Kas</div>
                        <div class="payment-subtitle">Status saat ini: {{ $paymentStatusLabel ?? 'Belum Bayar' }}</div>
                    </div>

                    <div class="card-body p-3 p-md-4 payment-inner">
                        <input type="hidden" name="nama" value="{{ $data->datasikad->nama }}">
                        <input type="hidden" name="nim" value="{{ $data->datasikad->Nim }}">
                        <input type="hidden" name="uang" id="paymentAmountInput" value="{{ max(0, (int) $sisaUtang) }}">

                        <div class="choice-grid mb-3" id="paymentChoices">
                            <button type="button" class="choice-card is-button is-active" data-choice="20"
                                data-amount="{{ max(0, (int) $sisaUtang) }}" data-label="Iuran + Lunasi"
                                data-display="Rp {{ number_format(max(0, (int) $sisaUtang), 0, ',', '.') }}"
                                @disabled(!($canPay ?? true))>
                                <div class="choice-label">Bayar Penuh</div>
                                <div class="choice-value">Rp {{ number_format(max(0, (int) $sisaUtang), 0, ',', '.') }}</div>
                            </button>
                            <button type="button" class="choice-card is-button" data-choice="custom" data-label="Jumlah lain"
                                data-display="Custom" @disabled(!($canPay ?? true))>
                                <div class="choice-label">Nominal Lain</div>
                                <div class="choice-value">Input Manual</div>
                            </button>
                        </div>

                        @unless ($canPay ?? true)
                            <div class="alert alert-warning mb-3">
                                Tagihan saat ini nol, tidak ada pembayaran yang perlu dilakukan.
                            </div>
                        @endunless

                        @if (!empty($rejectionReason))
                            <div class="alert alert-danger mb-3">
                                <strong>Pembayaran ditolak:</strong> {{ $rejectionReason }}
                            </div>
                        @elseif (($data->Status_Pembayaran ?? 1) === 2)
                            <div class="alert alert-info mb-3">
                                Pembayaran sedang menunggu konfirmasi admin.
                            </div>
                        @endif

                        <div class="custom-amount-box" id="customAmountBox">
                            <div class="custom-amount-label">Masukkan nominal pembayaran</div>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control custom-amount-input" id="customAmountInput" min="1000"
                                    step="1000" placeholder="Contoh: 15000">
                                <button type="button" class="btn btn-brand" id="applyCustomAmount">Pakai</button>
                            </div>
                        </div>

                        <div class="detail-summary mb-3">
                            <div class="detail-row">
                                <div class="label">Nama Anggota</div>
                                <div class="value">{{ $data->datasikad->nama }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="label">NIM</div>
                                <div class="value">{{ $data->datasikad->Nim }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="label">Status Pembayaran</div>
                                <div
                                    class="value {{ ($data->Status_Pembayaran ?? 1) === 3 ? 'is-success' : (($data->Status_Pembayaran ?? 1) === 2 ? 'is-warning' : '') }}">
                                    {{ $paymentStatusLabel ?? 'Belum Bayar' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="label">Total Utang</div>
                                <div class="value is-warning">Rp {{ number_format(max(0, (int) $data->Utang_Anggota), 0, ',', '.') }}</div>
                            </div>
                            @if ((int) $data->Saldo_Lebih > 0)
                                <div class="detail-row">
                                    <div class="label">Potongan Saldo Lebih</div>
                                    <div class="value is-success">
                                        -Rp {{ number_format(max(0, (int) $data->Saldo_Lebih), 0, ',', '.') }}
                                    </div>
                                </div>
                            @endif
                            <div class="detail-row mb-0">
                                <div class="label">Sisa Tagihan</div>
                                <div class="value is-warning">Rp {{ number_format(max(0, (int) $sisaUtang), 0, ',', '.') }}</div>
                            </div>

                            <hr class="detail-divider">

                            <div class="detail-row mb-0 align-items-center">
                                <div class="label fw-bold text-main">Bayar Sekarang</div>
                                <div class="value is-primary" id="currentPayAmount">Rp {{ number_format(max(0, (int) $sisaUtang), 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <button type="submit" class="pay-button" id="payNowButton" @disabled(!($canPay ?? true))>
                            {{ $canPay ?? true ? 'Bayar Rp ' . number_format(max(0, (int) $sisaUtang), 0, ',', '.') : 'Tidak Ada Tagihan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const choiceButtons = document.querySelectorAll('#paymentChoices .choice-card');
            const currentPayAmount = document.getElementById('currentPayAmount');
            const payNowButton = document.getElementById('payNowButton');
            const paymentForm = document.getElementById('paymentForm');
            const paymentAmountInput = document.getElementById('paymentAmountInput');
            const customAmountBox = document.getElementById('customAmountBox');
            const customAmountInput = document.getElementById('customAmountInput');
            const applyCustomAmountButton = document.getElementById('applyCustomAmount');

            if (payNowButton.disabled) {
                return;
            }

            if (!choiceButtons.length || !currentPayAmount || !payNowButton || !paymentForm ||
                !paymentAmountInput || !customAmountBox || !customAmountInput || !applyCustomAmountButton) {
                return;
            }

            const formatCurrency = (value) => {
                const numericValue = Number(value || 0);
                return `Rp ${numericValue.toLocaleString('id-ID')}`;
            };

            const setPaymentAmount = (amountText, rawAmount = '') => {
                currentPayAmount.textContent = amountText;
                payNowButton.textContent = `Bayar ${amountText}`;
                paymentAmountInput.value = rawAmount === null ? '' : String(rawAmount);
            };

            const activateChoice = (button) => {
                choiceButtons.forEach((choiceButton) => choiceButton.classList.remove('is-active'));
                button.classList.add('is-active');

                const displayAmount = button.dataset.display || 'Rp 0';
                const rawAmount = button.dataset.amount || '';
                const isCustomChoice = button.dataset.choice === 'custom';

                customAmountBox.classList.toggle('is-visible', isCustomChoice);

                if (isCustomChoice) {
                    const enteredAmount = Number(customAmountInput.value);
                    const initialAmount = enteredAmount >= 1000 ? formatCurrency(enteredAmount) : 'Rp 0';
                    setPaymentAmount(initialAmount, enteredAmount >= 1000 ? enteredAmount : '');
                } else {
                    setPaymentAmount(displayAmount, rawAmount);
                }
            };

            const applyCustomAmount = () => {
                const enteredAmount = Number(customAmountInput.value);

                if (!enteredAmount || enteredAmount < 1000) {
                    customAmountInput.focus();
                    return;
                }

                setPaymentAmount(formatCurrency(enteredAmount), enteredAmount);
            };

            choiceButtons.forEach((button) => {
                button.addEventListener('click', () => activateChoice(button));
                button.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        activateChoice(button);
                    }
                });
            });

            customAmountInput.addEventListener('input', () => {
                if (document.querySelector('#paymentChoices .choice-card.is-active')?.dataset.choice ===
                    'custom') {
                    const enteredAmount = Number(customAmountInput.value);
                    setPaymentAmount(enteredAmount >= 1000 ? formatCurrency(enteredAmount) : 'Rp 0',
                        enteredAmount >= 1000 ? enteredAmount : '');
                }
            });

            paymentForm.addEventListener('submit', (event) => {
                const activeChoice = document.querySelector('#paymentChoices .choice-card.is-active');

                if (activeChoice?.dataset.choice === 'custom') {
                    const enteredAmount = Number(customAmountInput.value);

                    if (!enteredAmount || enteredAmount < 1000) {
                        event.preventDefault();
                        customAmountInput.focus();
                        return;
                    }

                    paymentAmountInput.value = String(enteredAmount);
                }
            });

            applyCustomAmountButton.addEventListener('click', applyCustomAmount);
            customAmountInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    applyCustomAmount();
                }
            });
        });
    </script>
@endsection
