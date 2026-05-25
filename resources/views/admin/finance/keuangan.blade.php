@extends('layout.masteradmin')

@section('konten')
    @php
        $pendingPayments = $pendingPayments ?? [];
        $historyPayments = $historyPayments ?? [];
        $activityLogs = $activityLogs ?? [];
        $memberChoices = $memberChoices ?? [];
        $memberBalances = $memberBalances ?? [];
        $isAdmin = auth()->user()?->role === 'admin';
        $historyCount = $historyCount ?? count($historyPayments);
        $pendingCount = $pendingCount ?? count($pendingPayments);
        $totalKas = $totalKas ?? 0;
        $weeklyFee = $weeklyFee ?? 10000;
        $stats = $stats ?? [
            [
                'label' => 'Total kas',
                'value' => 'Rp ' . number_format($totalKas, 0, ',', '.'),
                'meta' => 'Diperbarui dari database',
                'icon' => 'bi-wallet2',
                'tone' => 'primary',
            ],
            [
                'label' => 'Sudah bayar',
                'value' => $historyCount . ' anggota',
                'meta' => 'Berstatus lunas',
                'icon' => 'bi-people',
                'tone' => 'success',
            ],
            [
                'label' => 'Menunggu konfirmasi',
                'value' => $pendingCount,
                'meta' => 'Perlu dicek',
                'icon' => 'bi-bell',
                'tone' => 'warning',
            ],
        ];
    @endphp

    <style>
        .finance-page {
            padding: 0 0 2.5rem;
        }

        .finance-shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .finance-hero {
            background: radial-gradient(circle at top left, rgba(46, 91, 135, 0.20), transparent 36%), radial-gradient(circle at bottom right, rgba(195, 143, 60, 0.18), transparent 34%), linear-gradient(135deg, rgba(31, 59, 92, 0.96), rgba(18, 38, 63, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(18, 38, 63, 0.18);
        }

        .hero-kicker {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.84rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 800;
        }

        .hero-title {
            font-size: clamp(1.7rem, 3vw, 2.5rem);
            font-weight: 800;
            margin: 0.35rem 0 0.5rem;
            letter-spacing: -0.02em;
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.78);
            max-width: 700px;
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            height: 100%;
        }

        .hero-stat-label {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 0.35rem;
        }

        .hero-stat-value {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .hero-stat-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.72);
            margin-top: 0.25rem;
        }

        .dashboard-tabs {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
            margin: 1.1rem 0 1.25rem;
        }

        .dashboard-tab {
            border: 1px solid var(--border-soft);
            background: var(--surface-elevated);
            color: var(--text-muted);
            border-radius: 999px;
            padding: 0.55rem 0.9rem;
            font-size: 0.88rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .dashboard-tab:hover,
        .dashboard-tab.active {
            color: var(--brand-500);
            border-color: rgba(46, 91, 135, 0.26);
            background: linear-gradient(135deg, rgba(46, 91, 135, 0.08), rgba(195, 143, 60, 0.08));
            transform: translateY(-1px);
        }

        .finance-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            box-shadow: 0 14px 32px var(--shadow-soft);
            overflow: hidden;
        }

        .finance-card-head {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .finance-card-title {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .finance-card-title .icon-badge,
        .row-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
        }

        .icon-badge {
            background: rgba(46, 91, 135, 0.10);
            color: var(--brand-500);
        }

        .badge-soft {
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .summary-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex: 0 0 42px;
        }

        .summary-badge.primary {
            background: rgba(46, 91, 135, 0.12);
            color: var(--brand-500);
        }

        .summary-badge.success {
            background: rgba(34, 197, 94, 0.12);
            color: #2f855a;
        }

        .summary-badge.warning {
            background: rgba(245, 158, 11, 0.12);
            color: #a16207;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .metric-value {
            color: var(--text-main);
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .metric-meta {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }

        .form-label-soft {
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .input-group-text {
            background: var(--surface-elevated);
            border-color: var(--border-soft);
            color: var(--text-main);
        }

        .form-control:focus,
        .form-select:focus,
        .form-control:focus-visible,
        .form-select:focus-visible {
            border-color: rgba(46, 91, 135, 0.42);
            box-shadow: 0 0 0 0.18rem rgba(46, 91, 135, 0.12);
        }

        .date-input-group .input-group-text {
            border-right: 0;
        }

        .date-input-group .form-control {
            border-left: 0;
        }

        .glass-note {
            background: linear-gradient(135deg, rgba(46, 91, 135, 0.10), rgba(195, 143, 60, 0.10));
            border: 1px solid rgba(46, 91, 135, 0.16);
            border-radius: 16px;
            padding: 1rem 1.05rem;
        }

        .btn-pill {
            border-radius: 999px;
            font-weight: 800;
            padding: 0.62rem 1.05rem;
        }

        .btn-brand-solid {
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border: 0;
            color: #ffffff;
        }

        .btn-brand-solid:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
        }

        .btn-outline-soft {
            border: 1px solid var(--border-soft);
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        .btn-outline-soft:hover {
            background: rgba(46, 91, 135, 0.05);
            color: var(--brand-500);
        }

        .member-row,
        .history-row,
        .log-row {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 0.95rem 1.05rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .member-row:last-child,
        .history-row:last-child,
        .log-row:last-child {
            border-bottom: 0;
        }

        .row-icon.avatar {
            background: rgba(46, 91, 135, 0.10);
            color: var(--brand-500);
            font-size: 0.72rem;
            font-weight: 800;
        }

        .row-icon.in {
            background: rgba(34, 197, 94, 0.14);
            color: #2f855a;
        }

        .row-icon.out {
            background: rgba(148, 163, 184, 0.16);
            color: var(--text-muted);
        }

        .row-icon.set {
            background: rgba(46, 91, 135, 0.14);
            color: var(--brand-500);
        }

        .row-icon.cal {
            background: rgba(245, 158, 11, 0.16);
            color: #a16207;
        }

        .row-main {
            flex: 1;
            min-width: 0;
        }

        .row-title {
            color: var(--text-main);
            font-size: 0.94rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 0.2rem;
        }

        .row-meta {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .row-right {
            text-align: right;
            flex-shrink: 0;
        }

        .amount-positive {
            color: #2f855a;
            font-weight: 800;
        }

        .amount-negative {
            color: #b45309;
            font-weight: 800;
        }

        .chip-row {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            padding: 0.95rem 1.05rem 0;
        }

        .filter-chip {
            border: 1px solid var(--border-soft);
            background: var(--surface-elevated);
            color: var(--text-muted);
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.42rem 0.75rem;
            transition: all 0.18s ease;
        }

        .filter-chip.active,
        .filter-chip:hover {
            border-color: rgba(46, 91, 135, 0.26);
            background: rgba(46, 91, 135, 0.08);
            color: var(--brand-500);
        }

        .payment-note {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.45rem;
        }

        .attachment-box {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: rgba(148, 163, 184, 0.10);
            border-radius: 14px;
            padding: 0.65rem 0.8rem;
            margin-bottom: 0.7rem;
        }

        .attachment-box i {
            color: var(--text-muted);
        }

        .attachment-box span {
            color: var(--text-muted);
            font-size: 0.82rem;
            flex: 1;
        }

        .attachment-box button {
            border: 0;
            background: transparent;
            color: var(--brand-500);
            font-weight: 700;
            font-size: 0.78rem;
            padding: 0;
        }

        .proof-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.68);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 1100;
        }

        .proof-modal.show {
            display: flex;
        }

        .proof-modal-card {
            width: min(100%, 760px);
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.24);
        }

        .proof-modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .proof-modal-title {
            font-weight: 800;
            color: var(--text-main);
        }

        .proof-modal-close {
            border: 0;
            background: transparent;
            color: var(--text-muted);
            font-size: 1.4rem;
            line-height: 1;
        }

        .proof-modal-body {
            padding: 1rem;
        }

        .proof-modal-image {
            width: 100%;
            max-height: 72vh;
            object-fit: contain;
            border-radius: 14px;
            background: rgba(148, 163, 184, 0.12);
        }

        .proof-modal-empty {
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
        }

        .balance-summary-list .row-meta {
            line-height: 1.45;
        }

        .balance-positive {
            color: #2f855a;
            font-weight: 800;
        }

        .balance-negative {
            color: #b45309;
            font-weight: 800;
        }

        .reset-warning-box {
            border: 1px solid rgba(220, 38, 38, 0.18);
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.10), rgba(245, 158, 11, 0.10));
            border-radius: 18px;
            padding: 1rem;
        }

        .reset-warning-title {
            font-weight: 800;
            color: #dc2626;
            margin-bottom: 0.35rem;
        }

        .reset-danger-input {
            border-color: rgba(220, 38, 38, 0.18);
        }

        .toast-holder {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 1090;
            pointer-events: none;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            color: var(--text-main);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            box-shadow: 0 12px 30px var(--shadow-soft);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .toast-item.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-item.success i {
            color: #2f855a;
        }

        .toast-item.danger i {
            color: #b45309;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        @media (max-width: 991.98px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .member-row,
            .history-row,
            .log-row {
                flex-direction: column;
                align-items: stretch;
            }

            .row-right {
                text-align: left;
            }
        }
    </style>

    <div class="finance-page">
        <div class="finance-shell">
            <div class="finance-hero p-4 p-lg-5 mb-4">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-8">
                        <div class="hero-kicker">Panel admin kas grub</div>
                        <h1 class="hero-title">Keuangan untuk admin dan akuntan</h1>
                        <p class="hero-desc mb-0">Pantau iuran mingguan, konfirmasi pembayaran, catat pengeluaran, dan jaga
                            saldo tetap selaras dengan catatan fisik. Tampilan ini mengikuti bahasa visual aplikasi: bersih,
                            tegas, dan tetap ringan dibaca.</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="hero-stat">
                                    <div class="hero-stat-label">Kas terkini</div>
                                    <div class="hero-stat-value">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
                                    <div class="hero-stat-meta">Diperbarui hari ini</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat">
                                    <div class="hero-stat-label">Pending</div>
                                    <div class="hero-stat-value">{{ $pendingCount }}</div>
                                    <div class="hero-stat-meta">Menunggu konfirmasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success border-0 rounded-4 mb-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4">{{ $errors->first() }}</div>
            @endif

            <div class="summary-grid mb-4">
                @foreach ($stats as $stat)
                    <div class="finance-card p-3 p-lg-4">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="summary-badge {{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                            <div>
                                <div class="metric-label mb-1">{{ $stat['label'] }}</div>
                                <div class="metric-value">{{ $stat['value'] }}</div>
                                <div class="metric-meta">{{ $stat['meta'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="dashboard-tabs">
                <button class="dashboard-tab active" type="button" data-tab="dashboard"><i
                        class="bi bi-speedometer2 me-1"></i>Dashboard</button>
                <button class="dashboard-tab" type="button" data-tab="konfirmasi"><i class="bi bi-bell me-1"></i>Konfirmasi
                    <span class="badge text-bg-danger ms-1" id="pendingBadge">{{ $pendingCount }}</span></button>
                <button class="dashboard-tab" type="button" data-tab="history"><i
                        class="bi bi-clock-history me-1"></i>Riwayat bayar</button>
                <button class="dashboard-tab" type="button" data-tab="log"><i class="bi bi-list-check me-1"></i>Log
                    aktivitas</button>
                @if ($isAdmin)
                    <button class="dashboard-tab" type="button" data-tab="reset"><i class="bi bi-trash3 me-1"></i>Reset
                        admin</button>
                @endif
            </div>

            <div id="tab-dashboard" class="tab-panel active">
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="finance-card h-100">
                            <div class="finance-card-head">
                                <div class="finance-card-title"><span class="icon-badge"><i
                                            class="bi bi-coin"></i></span>Set iuran mingguan</div>
                                <span class="badge text-bg-info badge-soft">Aktif: Rp
                                    {{ number_format($weeklyFee, 0, ',', '.') }}</span>
                            </div>
                            <form class="p-4" method="POST" action="{{ route('admin.finance.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label-soft">Nominal iuran per minggu</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="weekly_fee"
                                            value="{{ old('weekly_fee', $weeklyFee) }}" min="1000" step="1000"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Berlaku mulai tanggal</label>
                                    <div class="input-group date-input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" class="form-control" name="berlaku_mulai"
                                            value="{{ old('berlaku_mulai') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Catatan perubahan</label>
                                    <textarea class="form-control" rows="3" name="catatan_perubahan" placeholder="Alasan perubahan nominal..."></textarea>
                                </div>
                                <button class="btn btn-brand-solid btn-pill w-100" type="submit"><i
                                        class="bi bi-check2-circle me-1"></i>Simpan perubahan</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="finance-card h-100">
                            <div class="finance-card-head">
                                <div class="finance-card-title"><span class="icon-badge"><i
                                            class="bi bi-cash-coin"></i></span>Input cash manual</div>
                                <span class="badge text-bg-success badge-soft">Langsung lunas</span>
                            </div>
                            <form class="p-4" method="POST" action="{{ route('admin.finance.manual-cash.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label-soft">NIM anggota</label>
                                    <select class="form-select" name="nim" required>
                                        <option value="">Pilih nama anggota</option>
                                        @foreach ($memberChoices as $member)
                                            <option value="{{ $member->Nim }}">{{ $member->nama }} ·
                                                {{ $member->Nim }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Nominal cash</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="amount" placeholder="0"
                                            min="1" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Tanggal bayar</label>
                                    <div class="input-group date-input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" class="form-control" name="tanggal_pembayaran"
                                            value="{{ old('tanggal_pembayaran', now()->toDateString()) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Keterangan</label>
                                    <textarea class="form-control" rows="3" name="keterangan"
                                        placeholder="Catatan tambahan atau keterangan pembayaran..."></textarea>
                                </div>
                                <button class="btn btn-brand-solid btn-pill w-100" type="submit"><i
                                        class="bi bi-plus-lg me-1"></i>Simpan cash manual</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="finance-card h-100">
                            <div class="finance-card-head">
                                <div class="finance-card-title"><span class="icon-badge"><i
                                            class="bi bi-journal-plus"></i></span>Input utang manual</div>
                                <span class="badge text-bg-warning badge-soft">Tambah tagihan</span>
                            </div>
                            <form class="p-4" method="POST" action="{{ route('admin.finance.manual-debt.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label-soft">NIM anggota</label>
                                    <select class="form-select" name="nim" required>
                                        <option value="">Pilih nama anggota</option>
                                        @foreach ($memberChoices as $member)
                                            <option value="{{ $member->Nim }}">{{ $member->nama }} ·
                                                {{ $member->Nim }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Nominal utang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="amount" placeholder="0"
                                            min="1" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Tanggal input</label>
                                    <div class="input-group date-input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" class="form-control" name="tanggal_pembayaran"
                                            value="{{ old('tanggal_pembayaran', now()->toDateString()) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-soft">Keterangan</label>
                                    <textarea class="form-control" rows="3" name="keterangan"
                                        placeholder="Catatan tambahan atau alasan penambahan utang..."></textarea>
                                </div>
                                <button class="btn btn-outline-soft btn-pill w-100" type="submit"><i
                                        class="bi bi-journal-plus me-1"></i>Simpan utang manual</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="finance-card mb-4">
                    <div class="finance-card-head">
                        <div class="finance-card-title"><span class="icon-badge"><i
                                    class="bi bi-sliders2"></i></span>Kalibrasi saldo</div>
                        <span class="badge text-bg-warning badge-soft">Hati-hati</span>
                    </div>
                    <div class="p-4">
                        <div class="glass-note mb-4">Gunakan fitur ini untuk menyesuaikan saldo jika ada selisih antara
                            catatan dan uang fisik. Semua perubahan akan dicatat di log aktivitas.</div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-soft">Saldo saat ini (sistem)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" value="{{ $totalKas }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-soft">Saldo aktual (fisik)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" id="actualBalance" class="form-control"
                                        placeholder="Masukkan saldo aktual...">
                                </div>
                            </div>
                        </div>

                        <div id="balanceDiff" class="alert alert-warning mt-3 d-none mb-0" role="alert"></div>

                        <div class="mt-3">
                            <label class="form-label-soft">Alasan kalibrasi</label>
                            <textarea class="form-control" rows="3" placeholder="cth: Ada pembayaran tunai yang belum tercatat..."></textarea>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button class="btn btn-brand-solid btn-pill" type="button"
                                data-toast="Kalibrasi saldo berhasil disimpan" data-type="success"><i
                                    class="bi bi-sliders2-vertical me-1"></i>Simpan kalibrasi</button>
                            <button class="btn btn-outline-soft btn-pill" type="button" id="clearBalanceBtn">Reset
                                input</button>
                        </div>
                    </div>
                </div>

                <div class="finance-card mb-4">
                    <div class="finance-card-head">
                        <div class="finance-card-title"><span class="icon-badge"><i
                                    class="bi bi-people"></i></span>Posisi anggota</div>
                        <span class="badge text-bg-secondary badge-soft">Utang dan saldo lebih</span>
                    </div>

                    <div class="balance-summary-list">
                        @forelse ($memberBalances as $member)
                            <div class="member-row">
                                <span class="row-icon avatar">{{ strtoupper(substr($member['name'], 0, 2)) }}</span>
                                <div class="row-main">
                                    <div class="row-title">{{ $member['name'] }}</div>
                                    <div class="row-meta">NIM {{ $member['nim'] }}</div>
                                </div>
                                <div class="row-right">
                                    <div class="balance-negative">Utang Rp
                                        {{ number_format($member['utang'], 0, ',', '.') }}</div>
                                    <div class="balance-positive">Saldo lebih Rp
                                        {{ number_format($member['saldo_lebih'], 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">Belum ada anggota dengan utang atau saldo lebih.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div id="tab-konfirmasi" class="tab-panel">
                <div class="finance-card">
                    <div class="finance-card-head">
                        <div class="finance-card-title"><span class="icon-badge"><i
                                    class="bi bi-bell"></i></span>Pembayaran menunggu konfirmasi</div>
                        <span class="badge text-bg-warning badge-soft" id="pendingCountLabel">{{ $pendingCount }}
                            pending</span>
                    </div>

                    <div class="chip-row border-bottom">
                        <button class="filter-chip active" type="button">Semua</button>
                        <button class="filter-chip" type="button">Minggu 18</button>
                        <button class="filter-chip" type="button">Minggu 17</button>
                        <button class="filter-chip" type="button">Minggu 16</button>
                    </div>

                    @forelse ($pendingPayments as $payment)
                        <div class="member-row" data-pending-row>
                            <span class="row-icon avatar">{{ $payment['initial'] }}</span>
                            <div class="row-main">
                                <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start">
                                    <div>
                                        <div class="row-title">{{ $payment['name'] }}</div>
                                        <div class="row-meta">NIM {{ $payment['nim'] }} · {{ $payment['week'] }} ·
                                            {{ $payment['time'] }}</div>
                                    </div>
                                    <div class="amount-positive">{{ $payment['amount'] }}</div>
                                </div>

                                <div class="attachment-box mt-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>{{ $payment['file'] }}</span>
                                    @if (!empty($payment['proof_url']))
                                        <button type="button" data-preview-proof="{{ $payment['proof_url'] }}"
                                            data-preview-title="{{ $payment['proof_name'] ?? $payment['file'] }}">Lihat
                                            bukti <i class="bi bi-box-arrow-up-right ms-1"></i></button>
                                    @else
                                        <span class="text-muted" style="font-size: 0.78rem;">Bukti belum tersedia</span>
                                    @endif
                                </div>

                                <div class="payment-note">Catatan: “{{ $payment['note'] }}”</div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <form method="POST"
                                        action="{{ route('admin.finance.payment.approve', $payment['nim']) }}">
                                        @csrf
                                        <button class="btn btn-success btn-pill" type="submit"><i
                                                class="bi bi-check2 me-1"></i>Terima</button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('admin.finance.payment.reject', $payment['nim']) }}"
                                        class="flex-grow-1">
                                        @csrf
                                        <textarea class="form-control form-control-sm mb-2" name="alasan_penolakan" rows="2"
                                            placeholder="Alasan penolakan dari admin/akuntan" required></textarea>
                                        <button class="btn btn-outline-danger btn-pill w-100" type="submit"><i
                                                class="bi bi-x-lg me-1"></i>Tolak</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">Belum ada pembayaran yang menunggu konfirmasi.</div>
                    @endforelse
                </div>
            </div>

            <div id="tab-history" class="tab-panel">
                <div class="finance-card">
                    <div class="finance-card-head">
                        <div class="finance-card-title"><span class="icon-badge"><i
                                    class="bi bi-clock-history"></i></span>Log pembayaran dikonfirmasi</div>
                        <span class="badge text-bg-success badge-soft" id="historyCountLabel">{{ $historyCount }}
                            transaksi</span>
                    </div>

                    <div class="chip-row">
                        <button class="filter-chip active" type="button">Semua</button>
                        <button class="filter-chip" type="button">Minggu 18</button>
                        <button class="filter-chip" type="button">Minggu 17</button>
                        <button class="filter-chip" type="button">Minggu 16</button>
                    </div>

                    <div>
                        @forelse ($historyPayments as $payment)
                            <div class="history-row">
                                <span class="row-icon avatar">{{ $payment['initial'] }}</span>
                                <div class="row-main">
                                    <div class="row-title">{{ $payment['name'] }}</div>
                                    <div class="row-meta">NIM {{ $payment['nim'] }} · {{ $payment['week'] }} ·
                                        {{ $payment['time'] }}</div>
                                    @if (!empty($payment['proof_url']))
                                        <button type="button" class="btn btn-link p-0 text-decoration-none"
                                            style="font-size: 0.8rem;" data-preview-proof="{{ $payment['proof_url'] }}"
                                            data-preview-title="{{ $payment['proof_name'] ?? 'Bukti pembayaran' }}">Lihat
                                            bukti</button>
                                    @endif
                                </div>
                                <div class="row-right">
                                    <div class="amount-positive">{{ $payment['amount'] }}</div>
                                    <span class="badge text-bg-success mt-1">Dikonfirmasi</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">Belum ada riwayat pembayaran yang dikonfirmasi.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div id="tab-log" class="tab-panel">
                <div class="finance-card">
                    <div class="finance-card-head">
                        <div class="finance-card-title"><span class="icon-badge"><i
                                    class="bi bi-list-check"></i></span>Log semua aktivitas</div>
                        <span class="badge text-bg-secondary badge-soft" id="logCountLabel">{{ count($activityLogs) }}
                            entri</span>
                    </div>

                    <div class="chip-row">
                        <button class="filter-chip active" type="button">Semua</button>
                        <button class="filter-chip" type="button">Pemasukan</button>
                        <button class="filter-chip" type="button">Pengeluaran</button>
                        <button class="filter-chip" type="button">Setting</button>
                        <button class="filter-chip" type="button">Kalibrasi</button>
                    </div>

                    <div>
                        @forelse ($activityLogs as $log)
                            @php
                                $iconMap = [
                                    'in' => ['class' => 'in', 'icon' => 'bi-arrow-up-right'],
                                    'out' => ['class' => 'out', 'icon' => 'bi-arrow-down-left'],
                                    'set' => ['class' => 'set', 'icon' => 'bi-coin'],
                                    'cal' => ['class' => 'cal', 'icon' => 'bi-sliders2'],
                                ];
                                $icon = $iconMap[$log['type']];
                            @endphp
                            <div class="log-row">
                                <span class="row-icon {{ $icon['class'] }}"><i
                                        class="bi {{ $icon['icon'] }}"></i></span>
                                <div class="row-main">
                                    <div class="row-title">{{ $log['title'] }}</div>
                                    <div class="row-meta">{{ $log['detail'] }}</div>
                                </div>
                                <div class="row-right">
                                    <div
                                        class="{{ in_array($log['type'], ['in', 'cal'], true) ? 'amount-positive' : 'amount-negative' }}">
                                        {{ $log['amount'] }}</div>
                                    <div class="row-meta mt-1">{{ $log['time'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">Belum ada log aktivitas dari data pembayaran.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if ($isAdmin)
                <div id="tab-reset" class="tab-panel">
                    <div class="finance-card">
                        <div class="finance-card-head">
                            <div class="finance-card-title"><span class="icon-badge"><i
                                        class="bi bi-trash3"></i></span>Reset total grubkas</div>
                            <span class="badge text-bg-danger badge-soft">Admin only</span>
                        </div>

                        <div class="p-4">
                            <div class="reset-warning-box mb-4">
                                <div class="reset-warning-title">Peringatan keras</div>
                                <div class="text-muted">Aksi ini akan menghapus semua data grubkas, log aktivitas,
                                    konfigurasi iuran, cache dashboard, dan file bukti pembayaran. Tidak bisa dibatalkan.
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.finance.reset') }}">
                                @csrf
                                <div class="mb-3 form-check">
                                    <input class="form-check-input" type="checkbox" id="resetAcknowledge" required>
                                    <label class="form-check-label" for="resetAcknowledge">
                                        Saya memahami bahwa semua data grubkas akan dihapus permanen.
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-soft">Ketik RESET untuk melanjutkan</label>
                                    <input type="text" class="form-control reset-danger-input" name="confirm_text"
                                        placeholder="RESET" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-soft">Password admin</label>
                                    <input type="password" class="form-control reset-danger-input" name="admin_password"
                                        placeholder="Masukkan password admin" required>
                                </div>

                                <button class="btn btn-danger btn-pill w-100" type="submit"
                                    onclick="return confirm('Reset ini akan menghapus semua data grubkas. Lanjutkan?')">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Jalankan reset total
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="toast-holder">
        <div id="financeToast" class="toast-item" role="status" aria-live="polite"><i
                class="bi bi-check2-circle"></i><span id="financeToastText"></span></div>
    </div>

    <div id="proofModal" class="proof-modal" aria-hidden="true">
        <div class="proof-modal-card" role="dialog" aria-modal="true" aria-labelledby="proofModalTitle">
            <div class="proof-modal-head">
                <div class="proof-modal-title" id="proofModalTitle">Preview bukti pembayaran</div>
                <button type="button" class="proof-modal-close" id="closeProofModal"
                    aria-label="Tutup">&times;</button>
            </div>
            <div class="proof-modal-body">
                <img id="proofModalImage" class="proof-modal-image d-none" alt="Preview bukti pembayaran">
                <div id="proofModalEmpty" class="proof-modal-empty">Belum ada bukti untuk ditampilkan.</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.dashboard-tab');
            const panels = document.querySelectorAll('.tab-panel');
            const toast = document.getElementById('financeToast');
            const toastText = document.getElementById('financeToastText');
            const pendingBadge = document.getElementById('pendingBadge');
            const pendingCountLabel = document.getElementById('pendingCountLabel');
            const historyCountLabel = document.getElementById('historyCountLabel');
            const logCountLabel = document.getElementById('logCountLabel');
            const balanceInput = document.getElementById('actualBalance');
            const balanceDiff = document.getElementById('balanceDiff');
            const clearBalanceBtn = document.getElementById('clearBalanceBtn');
            const proofModal = document.getElementById('proofModal');
            const proofModalTitle = document.getElementById('proofModalTitle');
            const proofModalImage = document.getElementById('proofModalImage');
            const proofModalEmpty = document.getElementById('proofModalEmpty');
            const closeProofModal = document.getElementById('closeProofModal');
            const systemBalance = {{ $totalKas }};
            let hideTimer;

            function showToast(message, type = 'success') {
                toast.classList.remove('success', 'danger', 'show');
                toast.classList.add(type);
                toast.querySelector('i').className = type === 'success' ? 'bi bi-check2-circle' :
                    'bi bi-exclamation-triangle';
                toastText.textContent = message;
                requestAnimationFrame(() => toast.classList.add('show'));
                clearTimeout(hideTimer);
                hideTimer = setTimeout(() => toast.classList.remove('show'), 2500);
            }

            function switchTab(tabName) {
                tabs.forEach((tab) => tab.classList.toggle('active', tab.dataset.tab === tabName));
                panels.forEach((panel) => panel.classList.toggle('active', panel.id === 'tab-' + tabName));
            }

            tabs.forEach((tab) => {
                tab.addEventListener('click', function() {
                    switchTab(this.dataset.tab);
                });
            });

            document.querySelectorAll('[data-toast]').forEach((button) => {
                button.addEventListener('click', function() {
                    showToast(this.dataset.toast, this.dataset.type || 'success');
                });
            });

            const openProofModal = (url, title) => {
                if (!proofModal || !proofModalImage || !proofModalEmpty || !proofModalTitle) {
                    return;
                }

                proofModalTitle.textContent = title || 'Preview bukti pembayaran';
                if (url) {
                    proofModalImage.src = url;
                    proofModalImage.classList.remove('d-none');
                    proofModalEmpty.classList.add('d-none');
                } else {
                    proofModalImage.removeAttribute('src');
                    proofModalImage.classList.add('d-none');
                    proofModalEmpty.classList.remove('d-none');
                }

                proofModal.classList.add('show');
                proofModal.setAttribute('aria-hidden', 'false');
            };

            const closeProofPreview = () => {
                if (!proofModal || !proofModalImage) {
                    return;
                }

                proofModal.classList.remove('show');
                proofModal.setAttribute('aria-hidden', 'true');
                proofModalImage.removeAttribute('src');
            };

            document.querySelectorAll('[data-preview-proof]').forEach((button) => {
                button.addEventListener('click', function() {
                    openProofModal(this.dataset.previewProof, this.dataset.previewTitle);
                });
            });

            closeProofModal?.addEventListener('click', closeProofPreview);
            proofModal?.addEventListener('click', function(event) {
                if (event.target === proofModal) {
                    closeProofPreview();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeProofPreview();
                }
            });

            document.querySelectorAll('[data-action="accept"]').forEach((button) => {
                button.addEventListener('click', function() {
                    const name = this.dataset.name || 'pembayaran';
                    const row = this.closest('[data-pending-row]');
                    if (row) {
                        row.style.opacity = '0.45';
                        row.style.background = 'rgba(34, 197, 94, 0.08)';
                        setTimeout(() => row.remove(), 350);
                    }

                    const currentPending = Math.max(0, Number(pendingBadge.textContent || 0) - 1);
                    pendingBadge.textContent = currentPending;
                    pendingCountLabel.textContent = currentPending + ' pending';

                    if (currentPending === 0) {
                        pendingBadge.classList.add('d-none');
                    }

                    const nextHistory = Number(historyCountLabel.textContent.split(' ')[0] ||
                        {{ $historyCount }}) + 1;
                    historyCountLabel.textContent = nextHistory + ' transaksi';
                    logCountLabel.textContent = (Number(logCountLabel.textContent.split(' ')[0] ||
                        {{ count($activityLogs) }}) + 1) + ' entri';

                    showToast('Pembayaran ' + name + ' diterima & dicatat', 'success');
                });
            });

            document.querySelectorAll('[data-action="reject"]').forEach((button) => {
                button.addEventListener('click', function() {
                    const name = this.dataset.name || 'pembayaran';
                    const row = this.closest('[data-pending-row]');
                    if (row) {
                        row.style.opacity = '0.45';
                        row.style.background = 'rgba(245, 158, 11, 0.08)';
                        setTimeout(() => row.remove(), 350);
                    }

                    const currentPending = Math.max(0, Number(pendingBadge.textContent || 0) - 1);
                    pendingBadge.textContent = currentPending;
                    pendingCountLabel.textContent = currentPending + ' pending';

                    if (currentPending === 0) {
                        pendingBadge.classList.add('d-none');
                    }

                    showToast('Pembayaran ' + name + ' ditolak', 'danger');
                });
            });

            balanceInput?.addEventListener('input', function() {
                const actual = Number(this.value || 0);
                if (!actual || actual === systemBalance) {
                    balanceDiff.classList.add('d-none');
                    balanceDiff.textContent = '';
                    return;
                }

                const diff = actual - systemBalance;
                const formatted = new Intl.NumberFormat('id-ID').format(Math.abs(diff));
                balanceDiff.classList.remove('d-none');
                balanceDiff.innerHTML = 'Selisih: <strong>' + (diff > 0 ? '+' : '-') + 'Rp ' + formatted +
                    '</strong> - saldo akan ' + (diff > 0 ? 'ditambah' : 'dikurangi') + ' sebesar Rp ' +
                    formatted;
            });

            clearBalanceBtn?.addEventListener('click', function() {
                if (balanceInput) {
                    balanceInput.value = '';
                    balanceInput.dispatchEvent(new Event('input'));
                }
            });
        });
    </script>
@endsection
