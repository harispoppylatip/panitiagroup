@extends('layout.master')

@section('head')
    <style>
        .grubkas-detail-page {
            min-height: calc(100vh - 76px);
            padding: 1.25rem 0 2.5rem;
            background:
                radial-gradient(circle at top left, rgba(46, 91, 135, 0.18), transparent 30%),
                radial-gradient(circle at top right, rgba(195, 143, 60, 0.12), transparent 26%),
                radial-gradient(circle at bottom left, rgba(46, 91, 135, 0.08), transparent 28%),
                var(--surface);
        }

        .detail-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .detail-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .detail-back,
        .detail-menu {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 0.8rem;
            border: 1px solid var(--border-soft);
            background: var(--surface-elevated);
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 8px 18px var(--shadow-soft);
        }

        .detail-back:hover,
        .detail-menu:hover {
            color: var(--brand-500);
        }

        .hero-card,
        .payment-card {
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 18px 38px var(--shadow-soft);
        }

        .hero-card {
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
            color: #f8fbff;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            inset: auto -1.5rem -1.5rem auto;
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .hero-inner,
        .payment-inner {
            position: relative;
            z-index: 1;
        }

        .member-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 0.8rem;
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            flex: 0 0 3rem;
        }

        .member-name {
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .member-meta {
            font-size: 0.84rem;
            opacity: 0.88;
        }

        .summary-tile {
            min-height: 100%;
            border-radius: 0.65rem;
            padding: 0.9rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(6px);
        }

        .summary-label {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            font-weight: 800;
            text-transform: uppercase;
            color: rgba(248, 251, 255, 0.68);
        }

        .summary-value {
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.15;
            margin-top: 0.2rem;
        }

        .summary-note {
            font-size: 0.78rem;
            color: rgba(248, 251, 255, 0.72);
        }

        .summary-value.is-warning {
            color: #f5c36b;
        }

        .summary-value.is-success {
            color: #86efac;
        }

        .payment-card {
            background: var(--surface-elevated);
        }

        .payment-header {
            padding: 1rem 1.15rem 0.9rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .payment-title {
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            color: var(--text-main);
        }

        .payment-subtitle {
            font-size: 0.84rem;
            color: var(--text-muted);
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .choice-card {
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            padding: 0.95rem 0.85rem;
            text-align: center;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(244, 247, 251, 0.95));
            color: var(--text-main);
            min-height: 72px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .choice-card.is-button {
            cursor: pointer;
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
        }

        .choice-card.is-button:focus-visible {
            outline: 3px solid rgba(46, 91, 135, 0.25);
            outline-offset: 2px;
        }

        .choice-card.is-active {
            border-color: rgba(46, 91, 135, 0.28);
            box-shadow: inset 0 0 0 1px rgba(46, 91, 135, 0.06);
            background: linear-gradient(180deg, rgba(233, 241, 249, 0.98), rgba(225, 235, 245, 0.98));
        }

        .choice-card .choice-label {
            font-size: 0.85rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .choice-card .choice-value {
            font-size: 0.92rem;
            font-weight: 800;
            margin-top: 0.1rem;
            color: var(--brand-900);
        }

        .detail-summary {
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.98), rgba(241, 245, 249, 0.98));
            border: 1px solid var(--border-soft);
            border-radius: 0.8rem;
            padding: 1rem;
        }

        .custom-amount-box {
            display: none;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(46, 91, 135, 0.16);
            background: linear-gradient(180deg, rgba(244, 247, 251, 0.96), rgba(233, 241, 249, 0.96));
        }

        .custom-amount-box.is-visible {
            display: block;
        }

        .custom-amount-label {
            font-size: 0.84rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.45rem;
        }

        .custom-amount-input {
            border-radius: 0.7rem;
            border-color: var(--border-soft);
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .custom-amount-input:focus {
            border-color: rgba(46, 91, 135, 0.38);
            box-shadow: 0 0 0 0.2rem rgba(46, 91, 135, 0.12);
        }

        .custom-amount-help {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.45rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.9rem;
            margin-bottom: 0.55rem;
        }

        .detail-row .label {
            color: var(--text-muted);
            font-weight: 700;
        }

        .detail-row .value {
            color: var(--text-main);
            font-weight: 800;
            text-align: right;
        }

        .detail-row .value.is-warning {
            color: #d97706;
        }

        .detail-row .value.is-primary {
            color: var(--brand-500);
        }

        .detail-divider {
            border-color: var(--border-soft);
            margin: 0.8rem 0;
        }

        .pay-button {
            border: 0;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            color: #ffffff;
            font-weight: 800;
            padding: 0.9rem 1rem;
            width: 100%;
            box-shadow: 0 10px 22px rgba(46, 91, 135, 0.2);
        }

        .pay-button:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
        }

        .security-note {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-align: center;
        }

        body[data-theme='dark'] .hero-card {
            background: linear-gradient(135deg, #2f5f8e 0%, #224a72 52%, #1a3a5a 100%);
            border-color: rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .summary-tile {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.1);
        }

        body[data-theme='dark'] .payment-card,
        body[data-theme='dark'] .choice-card,
        body[data-theme='dark'] .detail-summary,
        body[data-theme='dark'] .custom-amount-box {
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        body[data-theme='dark'] .choice-card.is-active {
            background: linear-gradient(180deg, rgba(31, 41, 55, 0.96), rgba(17, 24, 39, 0.98));
            border-color: rgba(148, 163, 184, 0.22);
        }

        body[data-theme='dark'] .choice-card .choice-value {
            color: #b7c7dc;
        }

        .detail-summary .value,
        .detail-summary .label,
        .summary-value,
        .summary-note,
        .payment-title,
        .payment-subtitle,
        .security-note {
            transition: color 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
        }

        .choice-card.is-button:active {
            transform: translateY(1px);
        }

        @media (max-width: 767.98px) {
            .grubkas-detail-page {
                padding-top: 1rem;
            }

            .choice-grid {
                grid-template-columns: 1fr;
            }

            .detail-toolbar {
                margin-bottom: 0.6rem;
            }
        }

        @media (max-width: 575.98px) {
            .detail-shell {
                padding: 0 0.75rem;
            }

            .hero-card,
            .payment-card {
                border-radius: 0.9rem;
            }
        }
    </style>
@endsection

@section('konten')
    <div class="grubkas-detail-page">

        <form class="card payment-card" method="POST" action="{{ route('grubkas.checkout.page') }}" id="paymentForm">
            @csrf
            <div class="payment-header">
                <div class="payment-title">Jumlah yang Mau Dibayar</div>
            </div>

            <div class="card-body p-3 p-md-4 payment-inner">
                <input type="hidden" name="nama" value="{{ $data->datasikad->nama }}">
                <input type="hidden" name="nim" value="{{ $data->datasikad->Nim }}">
                <input type="hidden" name="uang" id="paymentAmountInput" value="{{ $data->Utang_Anggota }}">

                <div class="choice-grid mb-3" id="paymentChoices">
                    <button type="button" class="choice-card is-button is-active" data-choice="20"
                        data-amount="{{ $data->Utang_Anggota }}" data-label="Iuran + Lunasi"
                        data-display="Rp {{ number_format($data->Utang_Anggota, 0, ',', '.') }}">
                        <div class="choice-label">Lunasi</div>
                        <div class="choice-value">Rp {{ number_format($data->Utang_Anggota, 0, ',', '.') }}</div>
                    </button>
                    <button type="button" class="choice-card is-button" data-choice="custom" data-label="Jumlah lain"
                        data-display="Custom">
                        <div class="choice-label">Jumlah lain</div>
                        <div class="choice-value">Custom</div>
                    </button>
                </div>

                <div class="custom-amount-box" id="customAmountBox">
                    <div class="custom-amount-label">Masukkan jumlah yang mau dibayar</div>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control custom-amount-input" id="customAmountInput" min="1000"
                            step="1000" placeholder="Contoh: 15000">
                        <button type="button" class="btn btn-brand" id="applyCustomAmount">Pakai</button>
                    </div>
                </div>

                <div class="detail-summary mb-3">
                    <div class="detail-row">
                        <div class="label">Nama</div>
                        <div class="value">{{ $data->datasikad->nama }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="label">NIM</div>
                        <div class="value">{{ $data->datasikad->Nim }}</div>
                    </div>
                    <div class="detail-row mb-0">
                        <div class="label">Total utang</div>
                        <div class="value is-warning">{{ number_format($data->Utang_Anggota, 0, ',', '.') }}</div>
                    </div>

                    <hr class="detail-divider">

                    <div class="detail-row mb-0">
                        <div class="label fw-bold text-main">Bayar Sekarang</div>
                        <div class="value is-primary" id="currentPayAmount">Rp
                            {{ number_format($data->Utang_Anggota, 0, ',', '.') }}</div>
                    </div>
                </div>

                <button type="submit" class="pay-button mb-2" id="payNowButton">Bayar
                    {{ number_format($data->Utang_Anggota, 0, ',', '.') }}</button>
            </div>
        </form>
    </div>
    </div>

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
