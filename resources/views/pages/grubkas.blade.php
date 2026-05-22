@extends('layout.master')

@section('konten')
    <style>
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
                                <div class="display-6 fw-bold mb-0">Rp 10.000</div>
                                <div class="fw-semibold text-white-50 mb-1">/minggu per anggota</div>
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
                                    <div class="col-md-6">
                                        <button type="button" class="member-card" data-nama="{{ $item->datasikad->nama }}"
                                            data-nim="{{ $item->datasikad->Nim }}" data-tagihan="10000"
                                            onclick="pilihMember(this)">
                                            <span class="d-flex align-items-center">
                                                <span
                                                    class="badge-initial">{{ strtoupper(substr($item->datasikad->nama, 0, 2)) }}</span>
                                                <span>
                                                    <span class="member-name d-block"
                                                        name='nama'>{{ $item->datasikad->nama }}</span>
                                                    <span class="member-tag">Tagihan: Rp
                                                        {{ number_format($item->Utang_Anggota, 0, ',', '.') }}</span>
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

                                <button class="btn pay-button mb-2" type="submit" id="btnBayar" disabled>
                                    Bayar via Midtrans
                                </button>
                                <div class="security-note">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Secured by Midtrans - SSL Encrypted
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
                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon up"><i class="bi bi-arrow-up-short fs-4"></i></div>
                                <div>
                                    <div class="activity-title">Lisa Kurnia</div>
                                    <div class="activity-meta">Kamis, 09 Mei - 07:55</div>
                                </div>
                            </div>
                            <div class="activity-amount amount-positive">+Rp 10.000</div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon up"><i class="bi bi-arrow-up-short fs-4"></i></div>
                                <div>
                                    <div class="activity-title">Andi Reza</div>
                                    <div class="activity-meta">Rabu, 08 Mei - 16:20</div>
                                </div>
                            </div>
                            <div class="activity-amount amount-positive">+Rp 10.000</div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon down"><i class="bi bi-arrow-down-short fs-4"></i></div>
                                <div>
                                    <div class="activity-title">Pengeluaran Kas</div>
                                    <div class="activity-meta">Minggu, 05 Mei - 19:00</div>
                                </div>
                            </div>
                            <div class="activity-amount amount-negative">-Rp150.000</div>
                        </div>
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
            const nama = button.dataset.nama;
            const nim = button.dataset.nim;
            const tagihan = button.dataset.tagihan;

            document.querySelectorAll('.member-card').forEach((item) => item.classList.remove('is-selected'));
            button.classList.add('is-selected');

            document.getElementById('nama').value = nama;
            document.getElementById('nim').value = nim;
            document.getElementById('tagihan').value = tagihan;

            document.getElementById('previewNama').innerText = nama;
            document.getElementById('previewNim').innerText = nim;
            document.getElementById('previewTagihan').innerText = 'Rp ' + Number(tagihan).toLocaleString('id-ID');

            document.getElementById('btnBayar').disabled = false;
        }
    </script>
@endsection
