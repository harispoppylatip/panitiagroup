@extends('layout.master')

@section('konten')
    <style>
        .grubkas-shell {
            max-width: 920px;
            margin: 0 auto;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            padding: 1.15rem 1.25rem;
            box-shadow: var(--shadow-card);
        }

        .stat-card.summary-card {
            background: #0f172a;
            border-color: #1e293b;
            color: #ffffff;
        }

        body[data-theme='dark'] .stat-card.summary-card {
            background: #1e293b;
            border-color: rgba(148, 163, 184, 0.22);
        }

        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 600;
        }

        .stat-card.summary-card .stat-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .stat-value {
            font-size: 1.45rem;
            font-weight: 700;
            margin-top: 0.35rem;
            color: var(--brand-900);
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.02em;
        }

        .stat-card.summary-card .stat-value {
            color: #ffffff;
        }

        .stat-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .stat-card.summary-card .stat-meta {
            color: rgba(255, 255, 255, 0.68);
        }

        .grubkas-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .grubkas-card-header {
            padding: 1.1rem 1.35rem;
            border-bottom: 1px solid var(--border-soft);
            background: var(--surface-container-low);
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand-900);
            margin-bottom: 0.15rem;
        }

        .section-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.85rem;
        }

        .member-card {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            background: var(--surface-container-lowest);
            color: var(--text-main);
            padding: 0.9rem 1rem;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
            width: 100%;
            text-align: left;
        }

        .member-card:hover:not(:disabled) {
            border-color: var(--brand-500);
            background: var(--surface-container-low);
        }

        .member-card.is-selected {
            border-color: var(--brand-500);
            background: var(--surface-container-low);
            box-shadow: inset 0 0 0 1px var(--brand-500);
        }

        .member-name {
            font-size: 0.925rem;
            font-weight: 600;
            color: var(--brand-900);
            line-height: 1.25;
        }

        .member-tag {
            display: block;
            margin-top: 0.2rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            font-variant-numeric: tabular-nums;
        }

        .member-status {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.3rem;
        }

        .member-status.is-paid {
            color: #15803d;
        }

        .member-status.is-pending {
            color: #b45309;
        }

        .member-status.is-unpaid {
            color: var(--brand-700);
        }

        .member-status.is-rejected {
            color: #dc2626;
        }

        body[data-theme='dark'] .member-status.is-paid {
            color: #4ade80;
        }

        body[data-theme='dark'] .member-status.is-pending {
            color: #fbbf24;
        }

        body[data-theme='dark'] .member-status.is-unpaid {
            color: #93c5fd;
        }

        body[data-theme='dark'] .member-status.is-rejected {
            color: #f87171;
        }

        .member-card.is-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .invoice-box {
            margin-top: 1.25rem;
            padding: 1.15rem;
            border-radius: var(--radius-md);
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
        }

        .invoice-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
        }

        .invoice-row strong {
            color: var(--text-main);
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .invoice-total {
            padding-top: 0.75rem;
            margin-top: 0.25rem;
            border-top: 1px solid var(--border-soft);
            font-weight: 700;
            color: var(--brand-900);
        }

        .invoice-total strong {
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand-900);
        }

        .pay-button {
            border-radius: var(--radius-sm);
            border: 1px solid var(--brand-500);
            background: var(--brand-500);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            height: 44px;
            width: 100%;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .pay-button:hover:not(:disabled) {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .pay-button:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .security-note {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-align: center;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.85rem 0;
            border-top: 1px solid var(--border-soft);
        }

        .activity-item:first-of-type {
            border-top: 0;
            padding-top: 0.25rem;
        }

        .activity-left {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 0;
        }

        .activity-icon.up {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
        }

        body[data-theme='dark'] .activity-icon.up {
            background: rgba(74, 222, 128, 0.14);
            color: #4ade80;
        }

        .activity-icon.down {
            background: rgba(148, 163, 184, 0.14);
            color: var(--text-muted);
        }

        .activity-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.1rem;
        }

        .activity-meta {
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .activity-amount {
            font-size: 0.875rem;
            font-weight: 700;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .amount-positive {
            color: #15803d;
        }

        .amount-negative {
            color: #dc2626;
        }

        body[data-theme='dark'] .amount-positive {
            color: #4ade80;
        }

        body[data-theme='dark'] .amount-negative {
            color: #f87171;
        }

        @media (max-width: 767.98px) {
            .stat-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .grubkas-card-header {
                padding: 0.95rem 1.1rem;
            }
        }
    </style>

    <section class="grubkas-section">
        <div class="container">
            <div class="grubkas-shell">
                <div class="page-header">
                    <h1 class="page-title">Iuran Kas Grub</h1>
                    <p class="page-subtitle">Kontribusi rutin mingguan untuk kas bersama dan operasional kegiatan kelompok.</p>
                </div>

                <div class="stat-grid">
                    <div class="stat-card summary-card">
                        <div class="stat-label">Saldo Kas Saat Ini</div>
                        <div class="stat-value">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
                        <div class="stat-meta">Dana aktif siap digunakan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Masuk</div>
                        <div class="stat-value">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                        <div class="stat-meta">Pembayaran terkonfirmasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Keluar</div>
                        <div class="stat-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                        <div class="stat-meta">Pengeluaran operasional</div>
                    </div>
                </div>

                <div class="card grubkas-card mb-4">
                    <div class="grubkas-card-header">
                        <div class="section-title">Bayar Iuran Minggu Ini</div>
                        <div class="section-subtitle">Pilih nama kamu lalu lanjut ke rincian pembayaran</div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <div class="section-label">Daftar Anggota</div>

                        <form action="{{ route('grubkas.detail') }}" method="POST" id="formBayar">
                            @csrf

                            <div class="row g-2 g-md-3">
                                @foreach ($datauser as $item)
                                    @php
                                        $tagihan = max(0, (int) $item->Utang_Anggota - (int) $item->Saldo_Lebih);
                                        $saldoLebih = max(0, (int) $item->Saldo_Lebih);
                                        $statusId = (int) $item->Status_Pembayaran;
                                        $statusLabel = match ($statusId) {
                                            1 => 'Belum Bayar',
                                            2 => 'Menunggu Konfirmasi',
                                            3 => 'Sudah Bayar',
                                            4 => 'Ditolak',
                                            default => 'Status Tidak Diketahui',
                                        };
                                        $statusClass = match ($statusId) {
                                            1 => 'is-unpaid',
                                            2 => 'is-pending',
                                            3 => 'is-paid',
                                            4 => 'is-rejected',
                                            default => 'is-unpaid',
                                        };
                                    @endphp
                                    <div class="col-md-6">
                                        <button type="button" class="member-card {{ $tagihan < 1 ? 'is-disabled' : '' }}"
                                            data-nama="{{ $item->datasikad->nama }}" data-nim="{{ $item->datasikad->Nim }}"
                                            data-tagihan="{{ $tagihan }}" data-status="{{ $statusLabel }}"
                                            @disabled($tagihan < 1) onclick="pilihMember(this)">
                                            <span class="d-flex justify-content-between align-items-start gap-2">
                                                <span>
                                                    <span class="member-name d-block" name="nama">{{ $item->datasikad->nama }}</span>
                                                    <span class="member-tag">Tagihan: Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
                                                    @if ($saldoLebih > 0)
                                                        <span class="member-tag">Saldo lebih: Rp {{ number_format($saldoLebih, 0, ',', '.') }}</span>
                                                    @endif
                                                </span>
                                                <span class="member-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                            </span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="nama" id="nama">
                            <input type="hidden" name="nim" id="nim">
                            <input type="hidden" name="tagihan" id="tagihan">

                            <div class="invoice-box">
                                <div class="invoice-row">
                                    <span>Produk</span>
                                    <strong>Iuran Keanggotaan Mingguan</strong>
                                </div>
                                <div class="invoice-row">
                                    <span>Atas nama</span>
                                    <strong id="previewNama">Belum dipilih</strong>
                                </div>
                                <div class="invoice-row">
                                    <span>NIM</span>
                                    <strong id="previewNim">-</strong>
                                </div>
                                <div class="invoice-row invoice-total mb-3">
                                    <span>Total Bayar</span>
                                    <strong id="previewTagihan">Rp 0</strong>
                                </div>

                                <div id="previewStatus" class="small text-muted mb-3">Pilih anggota yang memiliki tagihan untuk melanjutkan pembayaran.</div>

                                <button class="btn pay-button mb-2" type="submit" id="btnBayar" disabled>
                                    Lanjut ke Detail Pembayaran
                                </button>
                                <div class="security-note">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Pembayaran diproses aman melalui QRIS
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card grubkas-card">
                    <div class="grubkas-card-header d-flex justify-content-between align-items-center">
                        <div class="section-title mb-0">Aktivitas Kas Terbaru</div>
                        <span class="small text-muted">Riwayat transaksi</span>
                    </div>

                    <div class="card-body px-3 px-md-4 py-2">
                        @forelse ($activityLogs as $activity)
                            <div class="activity-item">
                                <div class="activity-left">
                                    <div class="activity-icon {{ $activity['type'] === 'out' ? 'down' : 'up' }}">
                                        <i class="bi {{ $activity['type'] === 'out' ? 'bi-arrow-down-short' : 'bi-arrow-up-short' }} fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-meta">{{ $activity['detail'] ?: 'Aktivitas kas terbaru' }} · {{ $activity['time'] }}</div>
                                    </div>
                                </div>
                                <div class="activity-amount {{ $activity['type'] === 'out' ? 'amount-negative' : 'amount-positive' }}">
                                    {{ $activity['amount'] }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Belum ada aktivitas kas yang tercatat.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function pilihMember(button) {
            if (button.disabled) {
                return;
            }

            const nama = button.dataset.nama;
            const nim = button.dataset.nim;
            const tagihan = button.dataset.tagihan;
            const status = button.dataset.status || 'Status Tidak Diketahui';

            document.querySelectorAll('.member-card').forEach((item) => item.classList.remove('is-selected'));
            button.classList.add('is-selected');

            document.getElementById('nama').value = nama;
            document.getElementById('nim').value = nim;
            document.getElementById('tagihan').value = tagihan;

            document.getElementById('previewNama').innerText = nama;
            document.getElementById('previewNim').innerText = nim;
            document.getElementById('previewTagihan').innerText = Number(tagihan) > 0 ? 'Rp ' + Number(tagihan)
                .toLocaleString('id-ID') : 'Rp 0';
            document.getElementById('previewStatus').innerText = 'Status pembayaran: ' + status + (Number(tagihan) > 0 ?
                '' : ' | tidak ada tagihan yang bisa dibayar.');

            document.getElementById('btnBayar').disabled = Number(tagihan) < 1;
        }
    </script>
@endsection
