@extends('layout.master')
@section('konten')
    <section class="kas-grub-section">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- Header Section -->
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="kas-title mb-2">Kas Grub</h1>
                            <p class="kas-subtitle mb-0">Ituran mingguan Rp 10.000/orang · Minggu ke-18</p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>

                    <!-- Total Kas Card -->
                    <div class="kas-total-card">
                        <p class="kas-total-label">TOTAL KAS TERKUMPUL</p>
                        <h2 class="kas-total-amount">Rp 730.000</h2>
                        <p class="kas-total-info">Dikekola via Midtrans - Terakhir diperbarui hari ini</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-4 col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">7</div>
                                <div class="stat-label">Sudah bayar minggu ini</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">3</div>
                                <div class="stat-label">Belum bayar</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">10</div>
                                <div class="stat-label">Total anggota</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Activity Section -->
                <div class="col-lg-6">
                    <div class="activity-section">
                        <div class="section-header mb-3">
                            <h3 class="section-title">Aktivitas terbaru</h3>
                        </div>

                        <div class="activity-tabs mb-3">
                            <button class="tab-btn active" data-tab="minggu-ini">Minggu ini</button>
                            <button class="tab-btn" data-tab="bayar-luran">Bayar luran</button>
                        </div>

                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon up">
                                    <i class="bi bi-arrow-up"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Lisa Kurnia</p>
                                    <p class="activity-date">Kamis, 07 Mei · 07:55</p>
                                </div>
                                <div class="activity-amount positive">+Rp 10.000</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon up">
                                    <i class="bi bi-arrow-up"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Andi Reza</p>
                                    <p class="activity-date">Rabu, 08 Mei · 14:20</p>
                                </div>
                                <div class="activity-amount positive">+Rp 10.000</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon down">
                                    <i class="bi bi-arrow-down"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Makan bareng W17</p>
                                    <p class="activity-date">Minggu, 05 Mei · 19:00</p>
                                </div>
                                <div class="activity-amount negative">-Rp 150.000</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon up">
                                    <i class="bi bi-arrow-up"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Bagus Wicaksono</p>
                                    <p class="activity-date">Rabu, 08 Mei · 10:30</p>
                                </div>
                                <div class="activity-amount positive">+Rp 10.000</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon up">
                                    <i class="bi bi-arrow-up"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Yusuf Pratama</p>
                                    <p class="activity-date">Senin, 06 Mei · 11:00</p>
                                </div>
                                <div class="activity-amount positive">+Rp 10.000</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon up">
                                    <i class="bi bi-arrow-up"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-name">Fahmi Hendra</p>
                                    <p class="activity-date">Selasa, 07 Mei · 14:02</p>
                                </div>
                                <div class="activity-amount positive">+Rp 10.000</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-lg-6">
                    <div class="payment-section">
                        <div class="section-header mb-3">
                            <h3 class="section-title">Tagihan minggu ini</h3>
                        </div>

                        <div class="payment-amount-card">
                            <p class="payment-label">TAGIHAN MINGGU INI</p>
                            <h2 class="payment-amount">Rp 10.000</h2>
                            <p class="payment-deadline">Deadline: Minggu, 12 Mei 2025</p>
                        </div>

                        <div class="payment-methods mb-4">
                            <p class="payment-methods-label">METODE PEMBAYARAN</p>

                            <div class="payment-option">
                                <input type="radio" id="method-qris" name="payment-method" value="qris" checked>
                                <label for="method-qris" class="payment-option-label">
                                    <span class="option-radio"></span>
                                    <span class="option-text">QRIS · GoPay · OVO · Dana</span>
                                </label>
                            </div>

                            <div class="payment-option">
                                <input type="radio" id="method-transfer" name="payment-method" value="transfer">
                                <label for="method-transfer" class="payment-option-label">
                                    <span class="option-radio"></span>
                                    <span class="option-text">Transfer Bank</span>
                                </label>
                            </div>

                            <div class="payment-option">
                                <input type="radio" id="method-retail" name="payment-method" value="retail">
                                <label for="method-retail" class="payment-option-label">
                                    <span class="option-radio"></span>
                                    <span class="option-text">Alfamart / Indomaret</span>
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-payment w-100">
                            <span>Bayar via Midtrans</span>
                        </button>

                        <p class="payment-footer">Secured by Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .kas-grub-section {
            padding: 2rem 0;
            min-height: calc(100vh - 70px);
        }

        /* Header Styles */
        .kas-title {
            font-family: 'Manrope', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .kas-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Total Kas Card */
        .kas-total-card {
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border-radius: 1.25rem;
            padding: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 16px rgba(46, 91, 135, 0.2);
        }

        .kas-total-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            opacity: 0.8;
            margin-bottom: 0.5rem;
            margin: 0 0 0.5rem 0;
        }

        .kas-total-amount {
            font-family: 'Manrope', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            color: white;
        }

        .kas-total-info {
            font-size: 0.875rem;
            opacity: 0.9;
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Stat Cards */
        .stat-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            padding: 1.5rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--brand-500);
            box-shadow: 0 2px 8px rgba(46, 91, 135, 0.1);
        }

        .stat-value {
            font-family: 'Manrope', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--brand-500);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Section Styles */
        .section-header {
            border-bottom: 1px solid var(--border-soft);
            padding-bottom: 1rem;
        }

        .section-title {
            font-family: 'Manrope', sans-serif;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* Activity Section */
        .activity-section {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .activity-tabs {
            display: flex;
            gap: 0.5rem;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: var(--brand-500);
            border-bottom-color: var(--brand-500);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-icon {
            flex-shrink: 0;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
        }

        .activity-icon.up {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .activity-icon.down {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-name {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.25rem;
            margin: 0 0 0.25rem 0;
            font-size: 0.9375rem;
        }

        .activity-date {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin: 0;
        }

        .activity-amount {
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .activity-amount.positive {
            color: #22c55e;
        }

        .activity-amount.negative {
            color: #dc2626;
        }

        /* Payment Section */
        .payment-section {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .payment-amount-card {
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .payment-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            opacity: 0.8;
            margin: 0 0 0.5rem 0;
        }

        .payment-amount {
            font-family: 'Manrope', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            margin: 0 0 0.5rem 0;
            color: white;
        }

        .payment-deadline {
            font-size: 0.8125rem;
            opacity: 0.9;
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }

        .payment-methods-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            margin: 0 0 0.75rem 0;
        }

        .payment-methods {
            margin-bottom: 1.5rem;
        }

        .payment-option {
            position: relative;
            margin-bottom: 0.75rem;
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .payment-option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0;
        }

        .payment-option input[type="radio"]:checked+.payment-option-label {
            background: rgba(46, 91, 135, 0.05);
            border-color: var(--brand-500);
        }

        .option-radio {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--border-soft);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .payment-option input[type="radio"]:checked+.payment-option-label .option-radio {
            border-color: var(--brand-500);
            background: var(--brand-500);
        }

        .payment-option input[type="radio"]:checked+.payment-option-label .option-radio::after {
            content: '✓';
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .option-text {
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.9375rem;
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
            margin-top: 0.75rem;
            margin: 0.75rem 0 0 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .kas-title {
                font-size: 1.5rem;
            }

            .kas-total-amount,
            .payment-amount {
                font-size: 1.75rem;
            }

            .row.g-4 {
                row-gap: 1.5rem;
            }

            .activity-section,
            .payment-section {
                margin-bottom: 1rem;
            }
        }

        /* Dark theme support */
        body[data-theme='dark'] {
            .kas-total-card {
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            }

            .stat-card:hover {
                box-shadow: 0 2px 8px rgba(135, 169, 204, 0.15);
            }

            .btn-payment:hover {
                box-shadow: 0 4px 12px rgba(214, 173, 98, 0.3);
            }
        }
    </style>
@endsection
