@extends('layout.master')

@section('head')
    <script type="text/javascript"
        src="{{ config('midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.Client_Key') }}"></script>
@endsection

@section('konten')
    <section class="py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9">
                    <div class="send-fund-page-card">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <h1 class="send-fund-page-title">Kirim Dana</h1>
                                <p class="send-fund-page-subtitle mb-0">Bukan anggota? Kirim dana bebas untuk keperluan apa
                                    saja.</p>
                            </div>
                            <a href="{{ route('grubkas') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                        </div>

                        <div class="send-fund-divider"></div>

                        <form action="{{ route('grubkas.kirim-dana.non-anggota') }}" method="POST" id="send-fund-form"
                            class="mt-3">
                            @csrf

                            <div class="mb-3">
                                <label for="recipient_name" class="send-fund-label">Nama / Tujuan Pengiriman</label>
                                <input type="text" class="send-fund-input" id="recipient_name" name="recipient_name"
                                    placeholder="cth: Dana makan siang, Beli snack rapat, Andi"
                                    value="{{ old('recipient_name', $recipientName ?? '') }}" maxlength="150" required>
                                <p class="send-fund-hint">Boleh nama orang, nama kegiatan, atau keperluan lainnya.</p>
                            </div>

                            <div class="mb-3">
                                <label class="send-fund-label">Nominal</label>
                                <div class="quick-amount-grid">
                                    <button type="button" class="quick-amount-btn" data-amount="10000">Rp 10rb</button>
                                    <button type="button" class="quick-amount-btn" data-amount="25000">Rp 25rb</button>
                                    <button type="button" class="quick-amount-btn" data-amount="50000">Rp 50rb</button>
                                    <button type="button" class="quick-amount-btn" data-amount="100000">Rp 100rb</button>
                                </div>
                                <input type="number" min="1000" step="1" class="send-fund-input mt-2"
                                    id="send_amount" name="amount" placeholder="Nominal lainnya..."
                                    value="{{ old('amount', $amount ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="send_description" class="send-fund-label">Keterangan (opsional)</label>
                                <textarea class="send-fund-textarea" id="send_description" name="description" rows="3"
                                    placeholder="cth: Buat beli makan siang bareng pas rapat Senin...">{{ old('description', $description ?? '') }}</textarea>
                            </div>

                            <div class="send-fund-summary">
                                <div>
                                    <p class="send-fund-summary-label">Nama / Tujuan</p>
                                    <p class="send-fund-summary-value" id="summary-recipient">-</p>
                                </div>
                                <div class="text-end">
                                    <p class="send-fund-summary-label">Jumlah</p>
                                    <p class="send-fund-summary-amount" id="summary-amount">Rp -</p>
                                </div>
                            </div>

                            <button class="btn btn-payment w-100 mt-3" type="submit">
                                <span>Kirim Dana</span>
                            </button>

                            <p class="payment-footer">Setiap kirim dana akan dicatat ke log kas dan otomatis menambah total
                                kas.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .send-fund-page-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .send-fund-page-title {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
        }

        .send-fund-page-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 0.35rem;
        }

        .send-fund-divider {
            height: 1px;
            background: var(--border-soft);
        }

        .send-fund-label {
            display: block;
            font-size: 0.79rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .send-fund-input,
        .send-fund-textarea {
            width: 100%;
            border: 1px solid var(--border-soft);
            border-radius: 0.7rem;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-main);
            padding: 0.72rem 0.85rem;
            font-size: 0.92rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .send-fund-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .send-fund-input:focus,
        .send-fund-textarea:focus {
            outline: none;
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.18rem rgba(46, 91, 135, 0.15);
        }

        .send-fund-hint {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin: 0.4rem 0 0;
        }

        .quick-amount-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .quick-amount-btn {
            border: 1px solid var(--border-soft);
            background: transparent;
            color: var(--text-main);
            border-radius: 0.6rem;
            padding: 0.58rem 0.25rem;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .quick-amount-btn:hover {
            border-color: var(--brand-500);
            color: var(--brand-500);
        }

        .quick-amount-btn.active {
            background: rgba(46, 91, 135, 0.15);
            border-color: rgba(46, 91, 135, 0.4);
            color: var(--brand-500);
        }

        .send-fund-summary {
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            padding: 0.75rem 0.85rem;
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
        }

        .send-fund-summary-label {
            margin: 0;
            font-size: 0.74rem;
            color: var(--text-muted);
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .send-fund-summary-value {
            margin: 0.3rem 0 0;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .send-fund-summary-amount {
            margin: 0.3rem 0 0;
            font-size: 1.5rem;
            font-weight: 800;
            font-family: 'Manrope', sans-serif;
            color: #84cc16;
            line-height: 1;
        }

        .btn-payment {
            background: linear-gradient(135deg, var(--accent), #b07b2e);
            border: none;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.3s ease;
            font-family: 'Manrope', sans-serif;
        }

        .btn-payment:hover {
            background: linear-gradient(135deg, #b07b2e, #8f6426);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(195, 143, 60, 0.3);
        }

        .payment-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 0.75rem 0 0 0;
        }

        @media (max-width: 768px) {
            .send-fund-page-title {
                font-size: 1.5rem;
            }

            .quick-amount-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .send-fund-summary {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var amountInput = document.getElementById('send_amount');
            var recipientInput = document.getElementById('recipient_name');
            var summaryAmount = document.getElementById('summary-amount');
            var summaryRecipient = document.getElementById('summary-recipient');
            var quickButtons = document.querySelectorAll('.quick-amount-btn');

            function formatRupiah(amount) {
                var number = Number(amount || 0);
                if (!Number.isFinite(number) || number <= 0) {
                    return 'Rp -';
                }
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function updateSummary() {
                summaryAmount.textContent = formatRupiah(amountInput.value);
                var recipient = (recipientInput.value || '').trim();
                summaryRecipient.textContent = recipient !== '' ? recipient : '-';
            }

            quickButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    quickButtons.forEach(function(otherButton) {
                        otherButton.classList.remove('active');
                    });
                    button.classList.add('active');
                    amountInput.value = button.getAttribute('data-amount') || '';
                    updateSummary();
                });
            });

            amountInput.addEventListener('input', function() {
                quickButtons.forEach(function(button) {
                    if ((button.getAttribute('data-amount') || '') !== String(amountInput.value ||
                            '')) {
                        button.classList.remove('active');
                    }
                });
                updateSummary();
            });

            recipientInput.addEventListener('input', updateSummary);
            updateSummary();

            @if (!empty($snapToken) && !empty($openSnapOnLoad))
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = '{{ route('grubkas') }}';
                    },
                    onPending: function(result) {
                        console.log('Pembayaran pending:', result);
                    },
                    onError: function(result) {
                        console.log('Pembayaran error:', result);
                    },
                    onClose: function() {
                        console.log('Customer menutup popup tanpa menyelesaikan pembayaran');
                    }
                });
            @endif
        });
    </script>
@endsection
