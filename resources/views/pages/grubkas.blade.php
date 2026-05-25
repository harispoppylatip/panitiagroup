@extends('layout.master')

@section('konten')
    <style>
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 0.85rem;
        }

        .stat-card {
            border-radius: 1rem;
            padding: 0.95rem 1rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-label {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.68);
            font-weight: 800;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 800;
            margin-top: 0.3rem;
            color: #ffffff;
        }

        .stat-meta {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.68);
            margin-top: 0.2rem;
        }

        .grubkas-page {
            padding: 1.5rem 0 2.5rem;
            background:
                radial-gradient(circle at top left, rgba(46, 91, 135, 0.12), transparent 32%),
                radial-gradient(circle at bottom right, rgba(195, 143, 60, 0.10), transparent 30%),
                var(--surface);
            color: var(--text-main);
            min-height: calc(100vh - 76px);
        }

        .grubkas-shell {
            max-width: 940px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .grubkas-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            box-shadow: 0 16px 34px var(--shadow-soft);
        }

        .summary-card {
            border: 1px solid rgba(46, 91, 135, 0.20);
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
            box-shadow: 0 16px 34px rgba(18, 38, 63, 0.18);
        }

        .summary-icon,
        .activity-icon,
        .send-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
        }

        .summary-icon {
            background: rgba(255, 255, 255, 0.14);
            color: #f5c36b;
        }

        .section-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.15rem;
        }

        .section-subtitle {
            font-size: 0.86rem;
            color: var(--text-muted);
        }

        .member-card {
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.96));
            color: var(--text-main);
            padding: 0.9rem 1rem;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            width: 100%;
            text-align: left;
        }

        .member-card:hover,
        .member-card.is-selected {
            border-color: rgba(46, 91, 135, 0.45);
            background: linear-gradient(180deg, rgba(236, 245, 252, 0.98), rgba(226, 237, 247, 0.98));
            transform: translateY(-1px);
        }

        .member-card .badge-initial {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.18);
            color: var(--brand-700);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            margin-right: 0.7rem;
            flex: 0 0 26px;
        }

        .member-name {
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.15;
        }

        .member-tag {
            display: block;
            margin-top: 0.15rem;
            font-size: 0.7rem;
            color: var(--accent);
        }

        .member-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 800;
            margin-top: 0.2rem;
            opacity: 0.9;
        }

        .member-status.is-paid {
            color: #86efac;
        }

        .member-status.is-pending {
            color: #fcd34d;
        }

        .member-status.is-unpaid {
            color: #bfdbfe;
        }

        .member-status.is-rejected {
            color: #fca5a5;
        }

        .member-card.is-disabled {
            opacity: 0.58;
            cursor: not-allowed;
        }

        .invoice-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .invoice-row strong {
            color: var(--text-main);
            font-weight: 700;
        }

        .invoice-total {
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-soft);
            font-weight: 800;
            color: var(--text-main);
        }

        .pay-button {
            border-radius: 10px;
            border: 1px solid rgba(46, 91, 135, 0.18);
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            color: #ffffff;
            font-weight: 700;
            height: 44px;
            width: 100%;
            opacity: 0.98;
        }

        .security-note {
            color: var(--text-muted);
            font-size: 0.78rem;
            text-align: center;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 0;
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
        }

        .activity-icon.up {
            background: rgba(34, 197, 94, 0.14);
            color: #2f855a;
        }

        .activity-icon.down {
            background: rgba(148, 163, 184, 0.14);
            color: var(--text-muted);
        }

        .activity-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.1rem;
        }

        .activity-meta {
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .activity-amount {
            font-size: 0.88rem;
            font-weight: 800;
        }

        .amount-positive {
            color: #2f855a;
        }

        .amount-negative {
            color: #b45309;
        }

        .activity-amount.amount-positive {
            color: #86efac;
        }

        .activity-amount.amount-negative {
            color: #fca5a5;
        }

        .send-banner {
            background: linear-gradient(135deg, rgba(46, 91, 135, 0.08), rgba(195, 143, 60, 0.08));
            border: 1px solid var(--border-soft);
        }

        .send-icon {
            background: rgba(46, 91, 135, 0.12);
            color: var(--brand-500);
        }

        .btn-send {
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-500), var(--brand-700));
            border: none;
            color: #ffffff;
            font-weight: 800;
            white-space: nowrap;
        }

        .btn-send:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-700), var(--brand-900));
        }

        .muted-divider {
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .grubkas-page {
            background:
                radial-gradient(circle at top left, rgba(46, 91, 135, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(195, 143, 60, 0.12), transparent 30%),
                var(--surface);
        }

        body[data-theme='dark'] .grubkas-card,
        body[data-theme='dark'] .member-card {
            background: var(--surface-elevated);
            color: var(--text-main);
        }

        body[data-theme='dark'] .summary-card {
            background: linear-gradient(135deg, #2f5f8e 0%, #224a72 52%, #1a3a5a 100%);
            border-color: rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .summary-icon {
            background: rgba(255, 255, 255, 0.14);
            color: #f5c36b;
        }

        body[data-theme='dark'] .send-banner {
            background: linear-gradient(135deg, rgba(46, 91, 135, 0.12), rgba(195, 143, 60, 0.08));
        }

        @media (max-width: 575.98px) {
            .grubkas-page {
                padding-top: 1rem;
            }

            .invoice-row,
            .activity-item {
                gap: 0.75rem;
            }

            .summary-card .display-6 {
                font-size: 1.9rem;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="grubkas-page">
        <div class="grubkas-shell">
            <div class="card summary-card text-white rounded-4 mb-3">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="summary-icon">
                            <i class="bi bi-tag-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h1 class="h6 fw-bold mb-1 text-white">Iuran Keanggotaan Mingguan</h1>
                            <p class="mb-3 small text-white-50">Kontribusi rutin mingguan untuk kas bersama Panitia Akhir
                                Zaman. Dana digunakan untuk kegiatan, konsumsi rapat, dan operasional kelompok.</p>
                            <div class="d-flex flex-wrap align-items-end gap-2">
                                <div class="display-6 fw-bold mb-0">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
                                <div class="fw-semibold text-white-50 mb-1">saldo kas saat ini</div>
                            </div>

                            <div class="stat-grid">
                                <div class="stat-card">
                                    <div class="stat-label">Total masuk</div>
                                    <div class="stat-value">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                                    <div class="stat-meta">Pembayaran yang sudah dikonfirmasi</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">Total keluar</div>
                                    <div class="stat-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                                    <div class="stat-meta">Pengeluaran kas terbaru</div>
                                </div>
                                {{-- <div class="stat-card">
                                    <div class="stat-label">Aktivitas terbaru</div>
                                    <div class="stat-value">{{ count($activityLogs) }} entri</div>
                                    <div class="stat-meta">Masuk dan keluar terakhir</div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card grubkas-card rounded-4 mb-3">
                <div class="card-body p-0">
                    <div class="px-3 px-md-4 py-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div class="section-title">Bayar Iuran Minggu Ini</div>
                        <div class="section-subtitle">Pilih nama kamu lalu lanjut ke pembayaran</div>
                    </div>

                    <div class="px-3 px-md-4 py-3 border-bottom muted-divider">
                        <div class="text-uppercase small fw-bold text-white-50 mb-3">Pilih nama anggota</div>

                        <form action="{{ route('grubkas.detail') }}" method="POST" id="formBayar">
                            @csrf

                            <div class="row g-2 g-md-3">
                                @foreach ($datauser as $item)
                                    @php
                                        $tagihan = max(0, (int) $item->Utang_Anggota);
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
                                            <span class="d-flex align-items-center">
                                                <span
                                                    class="badge-initial">{{ strtoupper(substr($item->datasikad->nama, 0, 2)) }}</span>
                                                <span>
                                                    <span class="member-name d-block"
                                                        name='nama'>{{ $item->datasikad->nama }}</span>
                                                    <span class="member-tag">Tagihan: Rp
                                                        {{ number_format($tagihan, 0, ',', '.') }}</span>
                                                    <span class="member-status {{ $statusClass }}"><i
                                                            class="bi bi-flag-fill"></i>{{ $statusLabel }}</span>
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="nama" id="nama">
                            <input type="hidden" name="nim" id="nim">
                            <input type="hidden" name="tagihan" id="tagihan">

                            <div class="px-0 pt-3">
                                <div class="invoice-row">
                                    <span>Produk</span>
                                    <strong>Iuran Keanggotaan Mingguan</strong>
                                </div>
                                <div class="invoice-row">
                                    <span>Atas nama</span>
                                    <strong id="previewNama">— belum dipilih —</strong>
                                </div>
                                <div class="invoice-row">
                                    <span>NIM</span>
                                    <strong id="previewNim">—</strong>
                                </div>
                                <div class="invoice-row invoice-total mb-3">
                                    <span>Total Bayar</span>
                                    <strong id="previewTagihan">Rp 0</strong>
                                </div>

                                <div id="previewStatus" class="small text-white-50 mb-3">Pilih anggota untuk melihat status
                                    pembayaran.</div>

                                <button class="btn pay-button mb-2" type="submit" id="btnBayar" disabled>
                                    Detail pembayaran
                                </button>
                                <div class="security-note">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Detail pembayaran aman - SSL Encrypted
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card grubkas-card rounded-4 mb-3">
                <div class="card-body p-0">
                    <div class="px-3 px-md-4 py-3 border-bottom d-flex justify-content-between align-items-center"
                        style="border-color: rgba(255,255,255,0.08) !important;">
                        <div>
                            <div class="section-title mb-1">Aktivitas terbaru</div>
                        </div>
                        <span class="badge rounded-pill text-bg-secondary">Minggu ini</span>
                    </div>

                    <div class="px-3 px-md-4 py-2">
                        @forelse ($activityLogs as $activity)
                            <div class="activity-item">
                                <div class="activity-left">
                                    <div class="activity-icon {{ $activity['type'] === 'out' ? 'down' : 'up' }}"><i
                                            class="bi {{ $activity['type'] === 'out' ? 'bi-arrow-down-short' : 'bi-arrow-up-short' }} fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-meta">{{ $activity['detail'] ?: 'Aktivitas kas terbaru' }} ·
                                            {{ $activity['time'] }}</div>
                                    </div>
                                </div>
                                <div
                                    class="activity-amount {{ $activity['type'] === 'out' ? 'amount-negative' : 'amount-positive' }}">
                                    {{ $activity['amount'] }}</div>
                            </div>
                        @empty
                            <div class="text-center text-white-50 py-3">Belum ada aktivitas kas yang tercatat.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card grubkas-card send-banner rounded-4">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="send-icon">
                                <i class="bi bi-currency-dollar fs-5"></i>
                            </div>
                            <div>
                                <div class="section-title">Kirim Dana</div>
                                <div class="section-subtitle">Bukan anggota? Kirim dana bebas untuk keperluan apa saja
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('grubkas.kirim-dana.page') }}" class="btn btn-send px-3 py-2">
                            Kirim Dana <i class="bi bi-arrow-right-short fs-5 align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                '' : ' · tidak ada tagihan yang bisa dibayar.');

            document.getElementById('btnBayar').disabled = Number(tagihan) < 1;
        }
    </script>
@endsection
