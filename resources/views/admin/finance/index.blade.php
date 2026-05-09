@extends('layout.masteradmin')
@section('konten')
    <div class="container finance-dashboard">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Management Uang Kas</h2>
                <p class="text-muted mb-0">Kelola iuran mingguan, utang/saldo positif anggota, dan pengeluaran manual.</p>
            </div>
        </div>

        <div class="row g-3 mb-4 finance-summary-grid">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="finance-summary-card finance-summary-card--primary h-100">
                    <div class="finance-summary-icon"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <p class="finance-summary-label mb-1">Total Kas</p>
                        <h3 class="finance-summary-value mb-1">Rp {{ number_format((int) $totalKas, 0, ',', '.') }}</h3>
                        <p class="finance-summary-helper mb-0">Selisih masuk dan keluar kas</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="finance-summary-card finance-summary-card--danger h-100">
                    <div class="finance-summary-icon"><i class="bi bi-arrow-down-circle"></i></div>
                    <div>
                        <p class="finance-summary-label mb-1">Total Pengeluaran</p>
                        <h3 class="finance-summary-value mb-1">Rp {{ number_format((int) $totalPengeluaran, 0, ',', '.') }}
                        </h3>
                        <p class="finance-summary-helper mb-0">Semua transaksi keluar</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="finance-summary-card finance-summary-card--warning h-100">
                    <div class="finance-summary-icon"><i class="bi bi-receipt"></i></div>
                    <div>
                        <p class="finance-summary-label mb-1">Jumlah Transaksi</p>
                        <h3 class="finance-summary-value mb-1">{{ number_format((int) $jumlahTransaksi, 0, ',', '.') }}</h3>
                        <p class="finance-summary-helper mb-0">Total log transaksi tersimpan</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="finance-summary-card finance-summary-card--success h-100">
                    <div class="finance-summary-icon"><i class="bi bi-check2-circle"></i></div>
                    <div>
                        <p class="finance-summary-label mb-1">Pembayaran Berhasil</p>
                        <h3 class="finance-summary-value mb-1">{{ number_format((int) $pembayaranBerhasil, 0, ',', '.') }}
                        </h3>
                        <p class="finance-summary-helper mb-0">Status capture / settlement</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Pengaturan Iuran Mingguan</h5>
                        <form action="{{ route('admin.finance.weekly-fee') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label for="weekly_fee" class="form-label">Nominal Iuran per Minggu</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" min="0" class="form-control" id="weekly_fee"
                                        name="weekly_fee" value="{{ old('weekly_fee', $setting->weekly_fee) }}" required>
                                </div>
                                <small class="text-muted">Perubahan ini langsung dipakai di halaman Kas Grub dan command
                                    mingguan.</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Nominal Iuran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Input Pengeluaran Manual</h5>
                        <form action="{{ route('admin.finance.expense.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label for="amount" class="form-label">Nominal Pengeluaran</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" min="1" class="form-control" id="amount" name="amount"
                                        value="{{ old('amount') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Keterangan Pengeluaran</label>
                                <textarea id="description" name="description" class="form-control" rows="3" required
                                    placeholder="Contoh: Makan bersama Rp 150.000">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger">Catat Pengeluaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm h-100 border-0 finance-adjustment-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1 text-white">Sinkronisasi Total Kas</h5>
                                <p class="mb-0 finance-adjustment-subtitle">Penyesuaian manual untuk menyamakan saldo fisik
                                    dan saldo sistem.</p>
                            </div>
                            <div class="finance-adjustment-badge">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                        </div>

                        @if (Route::has('admin.finance.cash-adjustment.store'))
                            <form action="{{ route('admin.finance.cash-adjustment.store') }}" method="POST"
                                class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <label for="cash_adjustment_amount" class="form-label text-white-50">Nominal
                                        Penyesuaian</label>
                                    <div class="input-group input-group-lg finance-adjustment-input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" min="1" class="form-control"
                                            id="cash_adjustment_amount" name="amount" value="{{ old('amount') }}"
                                            required>
                                    </div>
                                    <small class="text-white-50">Gunakan jika ada selisih saat stok kas fisik
                                        dicek.</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50">Arah Penyesuaian</label>
                                    <div class="d-grid gap-2 adjustment-toggle-group">
                                        <input type="radio" class="btn-check" name="adjustment_type"
                                            id="adjustment_add" value="add" autocomplete="off" checked>
                                        <label class="btn btn-outline-light adjustment-toggle" for="adjustment_add">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Kas
                                        </label>

                                        <input type="radio" class="btn-check" name="adjustment_type"
                                            id="adjustment_subtract" value="subtract" autocomplete="off">
                                        <label class="btn btn-outline-light adjustment-toggle" for="adjustment_subtract">
                                            <i class="bi bi-dash-circle me-1"></i> Kurangi Kas
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="cash_adjustment_description"
                                        class="form-label text-white-50">Keterangan</label>
                                    <textarea id="cash_adjustment_description" name="description" class="form-control finance-adjustment-textarea"
                                        rows="3" placeholder="Contoh: Koreksi kas setelah hitung manual">{{ old('description') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-light w-100 fw-bold">Simpan Penyesuaian</button>
                                </div>
                            </form>
                        @else
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="alert alert-warning mb-0">Fitur penyesuaian kas belum terdaftar pada server
                                        ini.</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Kelola Utang dan Saldo Positif Anggota</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Utang Saat Ini</th>
                                <th>Saldo Positif</th>
                                <th>Keterangan</th>
                                <th style="min-width: 280px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($members as $member)
                                @php
                                    $latest = $member->latestIuran;
                                @endphp
                                <tr>
                                    <td>{{ $member->nama }}</td>
                                    <td>{{ $member->Nim }}</td>
                                    <td>Rp {{ number_format((int) ($latest->Nominal ?? 0), 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format((int) ($latest->Saldo_Lebih ?? 0), 0, ',', '.') }}</td>
                                    <td>{{ $latest->Keterangan ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.finance.member.update', $member->Nim) }}"
                                            method="POST" class="row g-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-4">
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    name="nominal" value="{{ $latest->Nominal ?? 0 }}"
                                                    placeholder="Utang" required>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    name="saldo_lebih" value="{{ $latest->Saldo_Lebih ?? 0 }}"
                                                    placeholder="Saldo" required>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-primary w-100">Update</button>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="keterangan"
                                                    value="{{ $latest->Keterangan ?? 'Penyesuaian manual oleh admin' }}"
                                                    placeholder="Keterangan perubahan (opsional)">
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Data anggota belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Riwayat Pengeluaran Terbaru</h5>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentExpenses as $expense)
                                <tr>
                                    <td>{{ optional($expense->occurred_at)->format('d-m-Y H:i') }}</td>
                                    <td>Rp {{ number_format((int) $expense->amount, 0, ',', '.') }}</td>
                                    <td>{{ $expense->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada pengeluaran manual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .finance-dashboard {
            padding-top: 0.5rem;
            padding-bottom: 1rem;
        }

        .finance-summary-card {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 1rem;
            color: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .finance-summary-card::after {
            content: '';
            position: absolute;
            inset: auto -20% -45% auto;
            width: 10rem;
            height: 10rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            filter: blur(2px);
        }

        .finance-summary-card--primary {
            background: linear-gradient(135deg, #1f4b7a, #2f6ea4);
        }

        .finance-summary-card--danger {
            background: linear-gradient(135deg, #8b2d3b, #c5535f);
        }

        .finance-summary-card--warning {
            background: linear-gradient(135deg, #8a641f, #d39c3c);
        }

        .finance-summary-card--success {
            background: linear-gradient(135deg, #1f6b4d, #2b9a68);
        }

        .finance-summary-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.9rem;
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .finance-summary-label {
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.82);
            margin: 0;
        }

        .finance-summary-value {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .finance-summary-helper {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.78);
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .finance-adjustment-card {
            background: linear-gradient(135deg, #24486f, #152c45);
            color: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        }

        .finance-adjustment-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .finance-adjustment-badge {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.85rem;
            background: rgba(255, 255, 255, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .finance-adjustment-card .form-control,
        .finance-adjustment-card .input-group-text {
            border-color: rgba(255, 255, 255, 0.18);
        }

        .finance-adjustment-card .form-control {
            background: rgba(255, 255, 255, 0.96);
        }

        .finance-adjustment-card .input-group-text {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .finance-adjustment-textarea {
            background: rgba(255, 255, 255, 0.96);
        }

        .adjustment-toggle-group .adjustment-toggle {
            text-align: left;
            border-color: rgba(255, 255, 255, 0.28);
            color: #fff;
            background: rgba(255, 255, 255, 0.04);
        }

        .adjustment-toggle-group .btn-check:checked+.adjustment-toggle {
            background: rgba(255, 255, 255, 0.88);
            color: #1f3b5c;
            border-color: rgba(255, 255, 255, 0.88);
        }

        body[data-theme='dark'] .finance-dashboard h2,
        body[data-theme='dark'] .finance-dashboard h5,
        body[data-theme='dark'] .finance-dashboard .fw-bold,
        body[data-theme='dark'] .finance-dashboard .text-muted,
        body[data-theme='dark'] .finance-dashboard .form-label,
        body[data-theme='dark'] .finance-dashboard small,
        body[data-theme='dark'] .finance-dashboard td,
        body[data-theme='dark'] .finance-dashboard th {
            color: var(--text-main);
        }

        body[data-theme='dark'] .finance-dashboard .card,
        body[data-theme='dark'] .finance-dashboard .table-wrapper,
        body[data-theme='dark'] .finance-dashboard .table {
            background: rgba(17, 24, 39, 0.9);
            color: var(--text-main);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .finance-dashboard .card.shadow-sm {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.28) !important;
        }

        body[data-theme='dark'] .finance-dashboard .card-body {
            color: var(--text-main);
        }

        body[data-theme='dark'] .finance-dashboard .card:not(.finance-adjustment-card) {
            background: rgba(17, 24, 39, 0.9);
            border-color: rgba(148, 163, 184, 0.16);
        }

        body[data-theme='dark'] .finance-dashboard .table thead th {
            background: linear-gradient(135deg, #2c5b87, #1f3b5c);
            color: #f8fbff;
        }

        body[data-theme='dark'] .finance-dashboard .table tbody td {
            background: rgba(17, 24, 39, 0.9);
            color: #e5eef9;
            border-color: rgba(148, 163, 184, 0.12);
        }

        body[data-theme='dark'] .finance-dashboard .table tbody tr:hover {
            background: rgba(46, 91, 135, 0.12);
        }

        body[data-theme='dark'] .finance-dashboard .alert-success {
            background: linear-gradient(135deg, rgba(22, 101, 52, 0.2), rgba(34, 197, 94, 0.1));
            border-color: rgba(74, 222, 128, 0.28);
            color: #dcfce7;
        }

        body[data-theme='dark'] .finance-dashboard .alert-danger {
            background: linear-gradient(135deg, rgba(127, 29, 29, 0.24), rgba(239, 68, 68, 0.12));
            border-color: rgba(248, 113, 113, 0.28);
            color: #fee2e2;
        }

        body[data-theme='dark'] .finance-dashboard .alert-warning {
            background: linear-gradient(135deg, rgba(120, 53, 15, 0.24), rgba(245, 158, 11, 0.12));
            border-color: rgba(251, 191, 36, 0.3);
            color: #fef3c7;
        }

        body[data-theme='dark'] .finance-dashboard .form-control,
        body[data-theme='dark'] .finance-dashboard .input-group-text,
        body[data-theme='dark'] .finance-dashboard textarea,
        body[data-theme='dark'] .finance-dashboard select {
            background: rgba(15, 23, 36, 0.92);
            color: #edf4ff;
            border-color: rgba(148, 163, 184, 0.22);
        }

        body[data-theme='dark'] .finance-dashboard .form-control::placeholder,
        body[data-theme='dark'] .finance-dashboard textarea::placeholder {
            color: rgba(167, 180, 197, 0.72);
        }

        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card {
            background: linear-gradient(135deg, #24486f, #13273d);
            border-color: rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card .form-control,
        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card .input-group-text,
        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card textarea {
            background: rgba(15, 23, 36, 0.94);
            color: #edf4ff;
            border-color: rgba(148, 163, 184, 0.2);
        }

        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card .input-group-text {
            background: rgba(255, 255, 255, 0.1);
        }

        body[data-theme='dark'] .finance-dashboard .finance-adjustment-subtitle,
        body[data-theme='dark'] .finance-dashboard .finance-adjustment-card .text-white-50 {
            color: rgba(229, 238, 249, 0.76) !important;
        }

        body[data-theme='dark'] .finance-dashboard .adjustment-toggle-group .adjustment-toggle {
            background: rgba(15, 23, 36, 0.85);
            color: #e5eef9;
            border-color: rgba(148, 163, 184, 0.22);
        }

        body[data-theme='dark'] .finance-dashboard .adjustment-toggle-group .btn-check:checked+.adjustment-toggle {
            background: rgba(229, 238, 249, 0.92);
            color: #13253c;
            border-color: rgba(229, 238, 249, 0.92);
        }

        body[data-theme='dark'] .finance-dashboard .badge {
            color: inherit;
        }

        body[data-theme='dark'] .finance-dashboard .btn-primary,
        body[data-theme='dark'] .finance-dashboard .btn-outline-primary,
        body[data-theme='dark'] .finance-dashboard .btn-danger,
        body[data-theme='dark'] .finance-dashboard .btn-light {
            box-shadow: none;
        }

        body[data-theme='dark'] .finance-dashboard .btn-outline-primary {
            color: #d7e5f7;
            border-color: rgba(148, 163, 184, 0.28);
            background: rgba(15, 23, 36, 0.6);
        }

        body[data-theme='dark'] .finance-dashboard .btn-outline-primary:hover {
            background: rgba(46, 91, 135, 0.18);
            border-color: rgba(135, 169, 204, 0.6);
            color: #ffffff;
        }

        body[data-theme='dark'] .finance-dashboard .btn-light {
            background: rgba(229, 238, 249, 0.94);
            color: #13253c;
            border-color: rgba(229, 238, 249, 0.94);
        }

        body[data-theme='dark'] .finance-dashboard .btn-light:hover {
            background: #ffffff;
            color: #10253f;
        }

        @media (max-width: 576px) {
            .finance-summary-card {
                padding: 1rem;
            }

            .finance-summary-value {
                font-size: 1.2rem;
            }
        }
    </style>
@endsection
