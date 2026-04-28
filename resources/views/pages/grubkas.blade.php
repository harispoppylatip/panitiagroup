@extends('layout.master')
@section('konten')
    <section class="kas-grub-section">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 mb-5">
                <!-- Header Section -->
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="kas-title mb-2">Kas Grub</h1>
                            <p class="kas-subtitle mb-0">Iuran mingguan Rp
                                {{ number_format((int) $weeklyFee, 0, ',', '.') }}/orang</p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>

                    <!-- Total Kas Card -->
                    <div class="kas-total-card">
                        <p class="kas-total-label">TOTAL KAS TERKUMPUL</p>
                        <h2 class="kas-total-amount">Rp {{ number_format((int) $totalKasTerkumpul, 0, ',', '.') }}</h2>
                        <p class="kas-total-info">Dikekola via Midtrans - Terakhir diperbarui hari ini</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-4 col-md-4">
                            <details class="stat-dropdown stat-dropdown--success">
                                <summary class="stat-card stat-summary">
                                    <div class="stat-value stat-value--success">{{ $sudahBayar }}</div>
                                    <div class="stat-label">Sudah bayar minggu ini</div>
                                    <i class="bi bi-chevron-down stat-chevron"></i>
                                </summary>
                                <div class="stat-dropdown-panel">
                                    @forelse ($sudahBayarMembers as $member)
                                        <div class="stat-member-item">
                                            <div class="stat-member-avatar stat-member-avatar--success">
                                                {{ $member['initials'] }}
                                            </div>
                                            <div class="stat-member-content">
                                                <p class="stat-member-name">{{ $member['nama'] }}</p>
                                                <p class="stat-member-meta">{{ $member['nim'] }} ·
                                                    {{ $member['keterangan'] }}</p>
                                            </div>
                                            <span class="stat-pill stat-pill--success">Lunas</span>
                                        </div>
                                    @empty
                                        <div class="stat-empty-state">Belum ada anggota yang tercatat lunas.</div>
                                    @endforelse
                                </div>
                            </details>
                        </div>
                        <div class="col-4 col-md-4">
                            <details class="stat-dropdown stat-dropdown--warning">
                                <summary class="stat-card stat-summary">
                                    <div class="stat-value stat-value--warning">{{ $belumBayar }}</div>
                                    <div class="stat-label">Belum bayar</div>
                                    <i class="bi bi-chevron-down stat-chevron"></i>
                                </summary>
                                <div class="stat-dropdown-panel">
                                    @forelse ($belumBayarMembers as $member)
                                        <div class="stat-member-item">
                                            <div class="stat-member-avatar stat-member-avatar--warning">
                                                {{ $member['initials'] }}
                                            </div>
                                            <div class="stat-member-content">
                                                <p class="stat-member-name">{{ $member['nama'] }}</p>
                                                <p class="stat-member-meta">{{ $member['nim'] }} ·
                                                    {{ $member['keterangan'] }}</p>
                                            </div>
                                            <span class="stat-pill stat-pill--warning">Pending</span>
                                        </div>
                                    @empty
                                        <div class="stat-empty-state">Semua anggota sudah membayar.</div>
                                    @endforelse
                                </div>
                            </details>
                        </div>
                        <div class="col-4 col-md-4">
                            <details class="stat-dropdown stat-dropdown--info">
                                <summary class="stat-card stat-summary">
                                    <div class="stat-value stat-value--info">{{ $totalAnggota }}</div>
                                    <div class="stat-label">Total anggota</div>
                                    <i class="bi bi-chevron-down stat-chevron"></i>
                                </summary>
                                <div class="stat-dropdown-panel stat-dropdown-panel--total">
                                    @forelse ($memberStats as $member)
                                        <div class="stat-member-item">
                                            <div
                                                class="stat-member-avatar stat-member-avatar--{{ $member['status_class'] }}">
                                                {{ $member['initials'] }}
                                            </div>
                                            <div class="stat-member-content">
                                                <p class="stat-member-name">{{ $member['nama'] }}</p>
                                                <p class="stat-member-meta">{{ $member['nim'] }} ·
                                                    {{ $member['keterangan'] }}</p>
                                            </div>
                                            <span
                                                class="stat-pill stat-pill--{{ $member['status_class'] }}">{{ $member['status_label'] }}</span>
                                        </div>
                                    @empty
                                        <div class="stat-empty-state">Belum ada data anggota.</div>
                                    @endforelse
                                </div>
                            </details>
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
                            <button class="tab-btn active" type="button" data-filter="minggu-1">Minggu 1</button>
                            <button class="tab-btn" type="button" data-filter="minggu-2">Minggu 2</button>
                            <button class="tab-btn" type="button" data-filter="bulan-ini">Bulan ini</button>
                            <button class="tab-btn" type="button" data-filter="bulan-lalu">Bulan lalu</button>
                            <button class="tab-btn" type="button" data-filter="semua">Semua</button>
                        </div>

                        <div class="activity-list" id="activity-list">
                            @forelse ($activityLogs as $log)
                                <details class="activity-dropdown"
                                    data-periods="{{ implode(',', $log->period_keys ?? ['semua']) }}">
                                    <summary class="activity-summary">
                                        <div class="activity-item">
                                            <div class="activity-icon {{ $log->direction === 'out' ? 'down' : 'up' }}">
                                                <i
                                                    class="bi {{ $log->direction === 'out' ? 'bi-arrow-down' : 'bi-arrow-up' }}"></i>
                                            </div>
                                            <div class="activity-content">
                                                <p class="activity-name">{{ $log->title }}</p>
                                                <p class="activity-date">
                                                    {{ optional($log->occurred_at)->translatedFormat('l, d M Y') ?? '-' }}
                                                    ·
                                                    {{ optional($log->occurred_at)->format('H:i') ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="activity-summary-right">
                                                <div
                                                    class="activity-amount {{ $log->direction === 'out' ? 'negative' : 'positive' }}">
                                                    {{ $log->direction === 'out' ? '-' : '+' }}Rp
                                                    {{ number_format((int) $log->amount, 0, ',', '.') }}
                                                </div>
                                                <i class="bi bi-chevron-down activity-chevron"></i>
                                            </div>
                                        </div>
                                    </summary>

                                    <div class="activity-detail-panel">
                                        <div class="activity-detail-grid">
                                            <div class="activity-detail-row">
                                                <span class="activity-detail-label">Waktu</span>
                                                <span class="activity-detail-value">
                                                    {{ optional($log->occurred_at)->translatedFormat('d-m-Y H:i') ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="activity-detail-row">
                                                <span class="activity-detail-label">Nominal</span>
                                                <span class="activity-detail-value">
                                                    Rp {{ number_format((int) $log->amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="activity-detail-row">
                                                <span class="activity-detail-label">Keterangan</span>
                                                <span class="activity-detail-value">
                                                    {{ $log->description ?: 'Tidak ada keterangan' }}
                                                </span>
                                            </div>
                                            <div class="activity-detail-row">
                                                <span class="activity-detail-label">Status</span>
                                                <span class="activity-detail-value">
                                                    {{ $log->transaction_status ?: '-' }}
                                                </span>
                                            </div>
                                            @if ($log->order_id)
                                                <div class="activity-detail-row">
                                                    <span class="activity-detail-label">Order ID</span>
                                                    <span class="activity-detail-value">{{ $log->order_id }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </details>
                            @empty
                                <div class="activity-item">
                                    <div class="activity-content">
                                        <p class="activity-name">Belum ada aktivitas pembayaran</p>
                                        <p class="activity-date">Transaksi yang berhasil akan muncul di sini</p>
                                    </div>
                                </div>
                            @endforelse

                            <div class="activity-filter-empty d-none" id="activity-filter-empty">
                                <p class="activity-name">Tidak ada aktivitas pada periode ini</p>
                                <p class="activity-date">Coba pilih tab periode lain untuk melihat data transaksi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-lg-6">
                    <form action="/bayar" method="POST">
                        @csrf
                        <div class="payment-section">
                            <div class="section-header mb-3">
                                <h3 class="section-title">Tagihan minggu ini</h3>
                            </div>

                            <div class="payment-amount-card">
                                <p class="payment-label">TAGIHAN PERMINGGU</p>
                                <h2 class="payment-amount">Rp {{ number_format((int) $weeklyFee, 0, ',', '.') }}</h2>
                                {{-- <p class="payment-deadline">Deadline: Minggu, 12 Mei 2025</p> --}}
                            </div>

                            <div class="payment-methods mb-4">
                                {{-- <p class="payment-methods-label">METODE PEMBAYARAN</p> --}}

                                {{-- <div class="payment-option">
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
                            </div> --}}

                                <div class="payer-select mt-3">
                                    <label for="payer-name" class="payer-select-label">Pilih Nama Anggota</label>
                                    <select id="payer-name" name="payer_info" class="payer-select-input" required>
                                        <option value="" selected disabled>-- Pilih nama untuk pembayaran --</option>
                                        @foreach ($datauser as $item)
                                            <option value="{{ $item->Nim }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button class="btn btn-payment w-100" type="submit">
                                <span>Click Untuk Lihat Detail Pembayaran</span>
                            </button>

                            <p class="payment-footer">Secured by Midtrans</p>
                        </div>
                    </form>

                    <div class="send-fund-section mt-4">
                        <div class="section-header mb-3">
                            <h3 class="section-title">Kirim Dana</h3>
                            <p class="send-fund-subtitle mb-0">Bukan anggota? Kirim dana bebas untuk keperluan apa saja.
                            </p>
                        </div>
                        <div class="send-fund-cta">
                            <div class="send-fund-cta-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="send-fund-cta-content">
                                <p class="send-fund-cta-title">Bukan anggota?</p>
                                <p class="send-fund-cta-text">Buka halaman kirim dana untuk mencatat donasi atau transfer
                                    kas.</p>
                            </div>
                            <a href="{{ route('grubkas.kirim-dana.page') }}"
                                class="btn btn-outline-secondary send-fund-cta-btn">
                                Kirim Dana <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
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

        .stat-dropdown {
            position: relative;
        }

        .stat-summary {
            list-style: none;
            cursor: pointer;
            position: relative;
            padding-bottom: 2rem;
        }

        .stat-summary::-webkit-details-marker {
            display: none;
        }

        .stat-summary:focus-visible {
            outline: 2px solid var(--brand-500);
            outline-offset: 2px;
        }

        .stat-chevron {
            position: absolute;
            right: 1rem;
            bottom: 0.9rem;
            color: var(--text-muted);
            transition: transform 0.2s ease;
            font-size: 0.8rem;
        }

        .stat-dropdown[open] .stat-chevron {
            transform: rotate(180deg);
        }

        .stat-dropdown-panel {
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 0.75rem;
            max-height: 22rem;
            overflow: auto;
        }

        .stat-dropdown-panel--total {
            max-height: 28rem;
        }

        .stat-member-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .stat-member-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .stat-member-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 0.55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .stat-member-avatar--success {
            background: rgba(34, 197, 94, 0.18);
            color: #22c55e;
        }

        .stat-member-avatar--warning {
            background: rgba(245, 158, 11, 0.18);
            color: #d97706;
        }

        .stat-member-avatar--info {
            background: rgba(59, 130, 246, 0.18);
            color: #60a5fa;
        }

        .stat-member-content {
            flex: 1;
            min-width: 0;
            text-align: left;
        }

        .stat-member-name {
            margin: 0;
            color: var(--text-main);
            font-weight: 700;
            font-size: 0.875rem;
            line-height: 1.35;
        }

        .stat-member-meta {
            margin: 0.15rem 0 0;
            color: var(--text-muted);
            font-size: 0.75rem;
            line-height: 1.35;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1;
            flex-shrink: 0;
        }

        .stat-pill--success {
            background: rgba(34, 197, 94, 0.16);
            color: #22c55e;
        }

        .stat-pill--warning {
            background: rgba(245, 158, 11, 0.16);
            color: #d97706;
        }

        .stat-pill--info {
            background: rgba(59, 130, 246, 0.16);
            color: #60a5fa;
        }

        .stat-empty-state {
            padding: 0.75rem 0.25rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-align: left;
        }

        .stat-value {
            font-family: 'Manrope', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--brand-500);
            margin-bottom: 0.25rem;
        }

        .stat-value--success {
            color: #84cc16;
        }

        .stat-value--warning {
            color: #f59e0b;
        }

        .stat-value--info {
            color: #60a5fa;
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
            flex-wrap: wrap;
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

        .activity-filter-empty {
            border: 1px dashed var(--border-soft);
            border-radius: 0.9rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
        }

        .activity-dropdown {
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.02);
            overflow: hidden;
        }

        .activity-dropdown[open] {
            border-color: rgba(46, 91, 135, 0.25);
            box-shadow: 0 6px 18px rgba(46, 91, 135, 0.08);
        }

        .activity-summary {
            list-style: none;
            cursor: pointer;
            padding: 1rem;
        }

        .activity-summary::-webkit-details-marker {
            display: none;
        }

        .activity-summary:focus-visible {
            outline: 2px solid var(--brand-500);
            outline-offset: 2px;
        }

        .activity-summary .activity-item {
            padding-bottom: 0;
            border-bottom: none;
        }

        .activity-summary-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .activity-chevron {
            color: var(--text-muted);
            transition: transform 0.2s ease;
        }

        .activity-dropdown[open] .activity-chevron {
            transform: rotate(180deg);
        }

        .activity-detail-panel {
            border-top: 1px solid var(--border-soft);
            padding: 1rem;
            background: rgba(46, 91, 135, 0.03);
        }

        .activity-detail-grid {
            display: grid;
            gap: 0.75rem;
        }

        .activity-detail-row {
            display: grid;
            gap: 0.25rem;
        }

        .activity-detail-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .activity-detail-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.5;
            word-break: break-word;
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

        .payer-select {
            margin-top: 1rem;
        }

        .payer-select-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-muted);
            margin: 0 0 0.5rem 0;
            letter-spacing: 0.02em;
        }

        .payer-select-input {
            width: 100%;
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            padding: 0.75rem 0.9rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-main);
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .payer-select-input:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(46, 91, 135, 0.15);
            outline: none;
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

        .send-fund-section {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            padding: 1.25rem;
        }

        .send-fund-subtitle {
            font-size: 0.86rem;
            color: var(--text-muted);
            margin-top: 0.35rem;
        }

        .send-fund-cta {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.9rem;
            padding: 0.95rem;
            background: rgba(255, 255, 255, 0.02);
        }

        .send-fund-cta-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.6rem;
            background: rgba(34, 197, 94, 0.16);
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .send-fund-cta-content {
            flex: 1;
            min-width: 0;
        }

        .send-fund-cta-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .send-fund-cta-text {
            margin: 0.2rem 0 0;
            color: var(--text-muted);
            font-size: 0.82rem;
            line-height: 1.35;
        }

        .send-fund-cta-btn {
            white-space: nowrap;
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
            min-height: 90px;
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

            .tab-btn {
                padding: 0.45rem 0.7rem;
                font-size: 0.8rem;
            }

            .send-fund-cta {
                align-items: flex-start;
                flex-wrap: wrap;
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabButtons = document.querySelectorAll('.activity-tabs .tab-btn[data-filter]');
            var activityItems = document.querySelectorAll('#activity-list .activity-dropdown');
            var emptyState = document.getElementById('activity-filter-empty');

            if (!tabButtons.length || !activityItems.length) {
                return;
            }

            function applyActivityFilter(filterKey) {
                var visibleCount = 0;

                activityItems.forEach(function(item) {
                    var periodValues = (item.getAttribute('data-periods') || '')
                        .split(',')
                        .map(function(value) {
                            return value.trim();
                        })
                        .filter(Boolean);

                    var isVisible = periodValues.indexOf(filterKey) !== -1;

                    item.classList.toggle('d-none', !isVisible);

                    if (isVisible) {
                        visibleCount += 1;
                    } else {
                        item.removeAttribute('open');
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('d-none', visibleCount > 0);
                }
            }

            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    tabButtons.forEach(function(otherButton) {
                        otherButton.classList.remove('active');
                    });

                    button.classList.add('active');
                    applyActivityFilter(button.getAttribute('data-filter'));
                });
            });

            var activeButton = document.querySelector('.activity-tabs .tab-btn.active[data-filter]');
            applyActivityFilter(activeButton ? activeButton.getAttribute('data-filter') : 'minggu-1');
        });
    </script>
@endsection
