@extends('layout.masteradmin')
@section('konten')
    <style>
        :root {
            --brand-900: #12263f;
            --brand-700: #1f3b5c;
            --brand-500: #2e5b87;
            --accent: #c38f3c;
            --surface: #f4f7fb;
            --surface-elevated: rgba(255, 255, 255, 0.92);
            --text-main: #1f2a37;
            --text-muted: #5f6f84;
            --border-soft: rgba(18, 38, 63, 0.1);
        }

        body[data-theme='dark'] {
            --brand-900: #e5eef9;
            --brand-700: #b7c7dc;
            --brand-500: #87a9cc;
            --accent: #d6ad62;
            --surface: #0f1724;
            --surface-elevated: rgba(17, 24, 39, 0.92);
            --text-main: #e5eef9;
            --text-muted: #a7b4c5;
            --border-soft: rgba(148, 163, 184, 0.18);
        }

        .token-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title h1 {
            color: var(--brand-900);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .header-title p {
            color: var(--text-muted);
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-brand {
            background: var(--brand-500);
            border: 1px solid var(--brand-500);
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.65rem 1.2rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.2s ease;
        }

        .btn-brand:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        .btn-refresh {
            background: #10b981;
            border: 1px solid #10b981;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.65rem 1.2rem;
        }

        .btn-refresh:hover {
            background: #059669;
            border-color: #059669;
            color: #fff;
        }

        .alert-refresh {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .alert-refresh.success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .table-wrapper {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--surface-container-low);
            color: var(--text-main);
            font-weight: 700;
            padding: 1.2rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-soft);
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: var(--surface);
        }

        .table tbody td {
            padding: 1rem 1.2rem;
            vertical-align: middle;
            color: var(--text-main);
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-on {
            background: #dcfce7;
            color: #166534;
        }

        .badge-off {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        body[data-theme='dark'] .badge-warning {
            background: rgba(234, 179, 8, 0.14);
            color: #fde68a;
        }

        .token-preview {
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            background: var(--surface);
            padding: 0.5rem;
            border-radius: 4px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--text-muted);
        }

        .timestamp {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-family: 'Courier New', monospace;
        }

        .action-cell {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.2s ease;
        }

        .btn-action-edit {
            background: #2563eb;
            color: #fff;
        }

        .btn-action-edit:hover {
            background: #1d4ed8;
        }

        .btn-action-delete {
            background: #dc2626;
            color: #fff;
            padding: 0.5rem;
            border-radius: 4px;
        }

        .btn-action-delete:hover {
            background: #b91c1c;
        }

        body[data-theme='dark'] .header-title h1,
        body[data-theme='dark'] .header-title p,
        body[data-theme='dark'] .no-data h5,
        body[data-theme='dark'] .no-data p,
        body[data-theme='dark'] .timestamp,
        body[data-theme='dark'] .refresh-item-status {
            color: var(--text-muted);
        }

        body[data-theme='dark'] .table-wrapper {
            background: var(--surface-elevated);
            border-color: var(--border-soft);
            box-shadow: 0 4px 12px var(--shadow-soft);
        }

        body[data-theme='dark'] .table thead th {
            background: var(--surface-container-high);
            color: var(--brand-900);
        }

        body[data-theme='dark'] .table tbody td {
            color: #e5eef9;
            background: rgba(17, 24, 39, 0.9);
        }

        body[data-theme='dark'] .table tbody tr:hover {
            background-color: rgba(46, 91, 135, 0.14);
        }

        body[data-theme='dark'] .token-preview,
        body[data-theme='dark'] .table tbody td code {
            background: rgba(15, 23, 36, 0.95);
            color: #d7e5f7;
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .badge-on {
            background: rgba(34, 197, 94, 0.14);
            color: #86efac;
        }

        body[data-theme='dark'] .badge-off {
            background: rgba(239, 68, 68, 0.14);
            color: #fca5a5;
        }

        body[data-theme='dark'] .alert-refresh {
            background: rgba(37, 99, 235, 0.14);
            border-color: rgba(96, 165, 250, 0.28);
            color: #dbeafe;
        }

        body[data-theme='dark'] .alert-refresh.success {
            background: rgba(34, 197, 94, 0.14);
            border-color: rgba(74, 222, 128, 0.28);
            color: #dcfce7;
        }

        body[data-theme='dark'] .refresh-item {
            background: var(--surface-elevated);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .btn-action-edit {
            background: #2563eb;
        }

        body[data-theme='dark'] .btn-action-delete {
            background: #dc2626;
        }

        body[data-theme='dark'] .btn-brand,
        body[data-theme='dark'] .btn-refresh {
            color: #fff;
        }

        body[data-theme='dark'] .btn-brand:hover {
            box-shadow: 0 8px 16px rgba(46, 91, 135, 0.28);
        }

        body[data-theme='dark'] .btn-refresh:hover {
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.24);
        }

        body[data-theme='dark'] .no-data a {
            color: #87a9cc;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .no-data-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .refresh-results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .refresh-item {
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-soft);
            background: var(--surface-container-lowest);
        }

        .refresh-item.failed {
            background: rgba(220, 38, 38, 0.06);
            border-color: rgba(220, 38, 38, 0.18);
        }

        body[data-theme='dark'] .refresh-item.failed {
            background: rgba(220, 38, 38, 0.12);
            border-color: rgba(220, 38, 38, 0.28);
        }

        .refresh-item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .refresh-item-status {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons .btn-brand {
                flex: 1;
                justify-content: center;
            }

            .table {
                font-size: 0.9rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
            }

            .token-preview {
                max-width: 100px;
            }

            .action-cell {
                gap: 0.25rem;
            }

            .btn-action {
                padding: 0.4rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="token-container">
        <!-- Header -->
        <div class="header-section">
            <div class="header-title">
                <h1>Management Token</h1>
                <p>Kelola semua token akses dan refresh untuk integrasi presensi</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.inserttoken.form') }}" class="btn-brand">
                    <i class="bi bi-plus-circle"></i> Tambah Token
                </a>
                <form action="{{ route('admin.token.refresh-all') }}" method="POST" style="display: contents;">
                    @csrf
                    <button type="submit" class="btn-refresh"
                        onclick="return confirm('Refresh semua token? Proses ini akan memperbarui access token dan refresh token untuk semua user.')">
                        <i class="bi bi-arrow-clockwise"></i> Refresh Semua
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert & Messages -->
        @if (session('success'))
            <div class="alert-refresh success alert-dismissible fade show" role="alert">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong><i class="bi bi-check-circle"></i> {{ session('success') }}</strong>
                    </div>
                    <button type="button" class="alert-close" data-bs-dismiss="alert">×</button>
                </div>
            </div>
        @endif

        <!-- Refresh Results (jika ada) -->
        @if (session('hasil_refresh'))
            <div class="alert-refresh alert-dismissible fade show" role="alert">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <strong>Hasil Refresh Token</strong>
                        <div style="font-size: 0.9rem; margin-top: 0.25rem;">
                            Berhasil: <strong>{{ session('success_count') }}</strong>
                            Gagal: <strong>{{ session('failed_count') }}</strong>
                        </div>
                    </div>
                    <button type="button" class="alert-close" data-bs-dismiss="alert">×</button>
                </div>
                <div class="refresh-results">
                    @foreach (session('hasil_refresh') as $hasil)
                        <div class="refresh-item {{ $hasil['status'] === 'gagal' ? 'failed' : '' }}">
                            <div class="refresh-item-name">{{ $hasil['nama'] }}</div>
                            <div class="refresh-item-status">{{ ucfirst($hasil['status']) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="table-wrapper">
            @if ($data->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 12%;">Nama</th>
                                <th style="width: 10%;">NIM</th>
                                <th style="width: 15%;">Access Token</th>
                                <th style="width: 15%;">Refresh Token</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 10%;">Pembayaran</th>
                                <th style="width: 10%;">Dibuat</th>
                                <th style="width: 10%;">Diupdate</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                @php
                                    $pay = $statusPembayaran[$item->Nim] ?? null;
                                    $payStatus = $pay ? (int) $pay->Status_Pembayaran : 0;
                                    $payLabel = match ($payStatus) {
                                        1 => 'Belum Bayar',
                                        2 => 'Menunggu',
                                        3 => 'Lunas',
                                        4 => 'Ditolak',
                                        default => 'Tidak ada',
                                    };
                                    $payBadge = match ($payStatus) {
                                        1 => 'badge-off',
                                        2 => 'badge-warning',
                                        3 => 'badge-on',
                                        4 => 'badge-off',
                                        default => 'badge-off',
                                    };
                                @endphp
                                <tr>
                                    <td><strong>#{{ $item->id }}</strong></td>
                                    <td>{{ $item->nama }}</td>
                                    <td><code
                                            style="background: var(--surface); padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $item->Nim }}</code>
                                    </td>
                                    <td>
                                        <div class="token-preview" title="{{ $item->access_token }}">
                                            {{ $item->access_token }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="token-preview" title="{{ $item->refresh_token }}">
                                            {{ $item->refresh_token }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->status_onoff === 'on' ? 'badge-on' : 'badge-off' }}">
                                            {{ $item->status_onoff === 'on' ? 'ON' : 'OFF' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $payBadge }}" style="font-size: 0.75rem;">
                                            {{ $payLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="timestamp">
                                            {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="timestamp">
                                            {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="action-cell">
                                            <a href="{{ route('admin.token.edit', $item->id) }}"
                                                class="btn-action btn-action-edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.token.destroy', $item->id) }}" method="POST"
                                                style="display: contents;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn-action btn-action-delete"
                                                    onclick="return confirm('Hapus token ini? Data tidak bisa dikembalikan.')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    <h5>Belum Ada Token</h5>
                    <p>Tidak ada data token yang ditambahkan. <a href="{{ route('admin.inserttoken.form') }}"
                            style="color: var(--brand-500);">Tambah token baru</a></p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-hide alerts after 6 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 6000);
            });
        });
    </script>
@endsection
