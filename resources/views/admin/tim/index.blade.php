@extends('layout.masteradmin')
@section('konten')
    <style>
        .tim-container {
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .member-photo {
            width: 46px;
            height: 46px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--surface-container-low);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--brand-700);
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
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--text-muted);
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
            color: #fff;
        }

        .btn-action-delete {
            background: #dc2626;
            color: #fff;
            padding: 0.5rem;
        }

        .btn-action-delete:hover {
            background: #b91c1c;
            color: #fff;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
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

        body[data-theme='dark'] .header-title h1,
        body[data-theme='dark'] .header-title p,
        body[data-theme='dark'] .no-data h5,
        body[data-theme='dark'] .no-data p,
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

        body[data-theme='dark'] .token-preview {
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

        body[data-theme='dark'] .btn-brand,
        body[data-theme='dark'] .btn-refresh {
            color: #fff;
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
        }
    </style>

    <div class="tim-container">
        <!-- Header -->
        <div class="header-section">
            <div class="header-title">
                <h1>Management Tim</h1>
                <p>Kelola semua anggota: data diri, token absen, tampilan beranda, dan kas dalam satu halaman</p>
            </div>
            <div class="action-buttons">
                <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#memberModal"
                    data-mode="add">
                    <i class="bi bi-plus-circle"></i> Tambah Anggota
                </button>
                <form action="{{ route('admin.tim.refresh-all') }}" method="POST" style="display: contents;">
                    @csrf
                    <button type="submit" class="btn-refresh"
                        onclick="return confirm('Refresh semua token? Proses ini akan memperbarui access token dan refresh token untuk semua anggota.')">
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Validasi Gagal!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                <th style="width: 7%;">Foto</th>
                                <th style="width: 12%;">Nama</th>
                                <th style="width: 8%;">NIM</th>
                                <th style="width: 12%;">Posisi</th>
                                <th style="width: 7%;">Status</th>
                                <th style="width: 9%;">Kas</th>
                                <th style="width: 8%;">Order</th>
                                <th style="width: 18%;">Token</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                @php
                                    $display = $displayByNim[$item->Nim] ?? null;
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
                                    <td>
                                        @if ($display && $display->image_url)
                                            <img src="{{ $display->image_url }}" alt="{{ $item->nama }}"
                                                class="member-photo">
                                        @else
                                            <span class="member-photo">{{ strtoupper(substr($item->nama, 0, 1)) }}</span>
                                        @endif
                                    </td>
                                    <td class="fw-medium">
                                        {{ $item->nama }}
                                        @if ($display)
                                            <br><small class="text-muted">Tampil di beranda</small>
                                        @else
                                            <br><small class="text-warning">Belum tampil di beranda</small>
                                        @endif
                                    </td>
                                    <td><code
                                            style="background: var(--surface); padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $item->Nim }}</code>
                                    </td>
                                    <td>{{ $display?->role ?? '-' }}</td>
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
                                    <td>{{ $display?->order ?? '-' }}</td>
                                    <td>
                                        <div class="token-preview" title="{{ $item->access_token }}">
                                            {{ $item->access_token }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-cell">
                                            <button type="button" class="btn-action btn-action-edit" data-bs-toggle="modal"
                                                data-bs-target="#memberModal" data-mode="edit"
                                                data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                data-nim="{{ $item->Nim }}" data-role="{{ $display?->role ?? '' }}"
                                                data-order="{{ $display?->order ?? 0 }}"
                                                data-access="{{ $item->access_token }}"
                                                data-refresh="{{ $item->refresh_token }}"
                                                data-status="{{ $item->status_onoff }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.tim.destroy', $item->id) }}" method="POST"
                                                style="display: contents;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn-action btn-action-delete"
                                                    onclick="return confirm('Hapus anggota ini? Token, kartu beranda, dan data kas terkait ikut terhapus.')">
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
                    <h5>Belum Ada Anggota</h5>
                    <p>Tambahkan anggota pertama untuk mulai mengelola tim.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah / Edit Anggota -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="memberForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="memberMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memberModalLabel">Tambah Anggota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama" id="fieldNama" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" name="Nim" id="fieldNim" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Posisi/Role</label>
                                <input type="text" class="form-control" name="role" id="fieldRole"
                                    placeholder="contoh: Frontend Developer" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Urutan (Order)</label>
                                <input type="number" class="form-control" name="order" id="fieldOrder"
                                    min="0" required>
                                <small class="text-muted">Urutan tampilan di beranda (0 muncul paling awal)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Foto</label>
                                <input type="file" class="form-control" name="photo_image" accept="image/*">
                                <small class="text-muted">Max 5MB (JPG, PNG, GIF, WebP)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Atau URL Foto</label>
                                <input type="url" class="form-control" name="photo_image_url" id="fieldPhotoUrl"
                                    placeholder="https://example.com/image.jpg">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Access Token</label>
                                <textarea name="access_token" id="fieldAccess" class="form-control" rows="2"
                                    placeholder="Masukkan access token" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Refresh Token</label>
                                <textarea name="refresh_token" id="fieldRefresh" class="form-control" rows="2"
                                    placeholder="Masukkan refresh token" required></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_onoff" value="on"
                                        id="fieldStatus">
                                    <label class="form-check-label" for="fieldStatus">
                                        Aktifkan untuk scanning absen
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 6 seconds
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 6000);
            });

            // Isi modal berdasarkan tombol (tambah / edit)
            const memberModal = document.getElementById('memberModal');
            const memberForm = document.getElementById('memberForm');
            const memberMethod = document.getElementById('memberMethod');
            const memberLabel = document.getElementById('memberModalLabel');
            const fieldNama = document.getElementById('fieldNama');
            const fieldNim = document.getElementById('fieldNim');
            const fieldRole = document.getElementById('fieldRole');
            const fieldOrder = document.getElementById('fieldOrder');
            const fieldPhotoUrl = document.getElementById('fieldPhotoUrl');
            const fieldAccess = document.getElementById('fieldAccess');
            const fieldRefresh = document.getElementById('fieldRefresh');
            const fieldStatus = document.getElementById('fieldStatus');

            memberModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const mode = button.dataset.mode;

                // Reset form
                memberForm.reset();
                memberForm.querySelector('[name="photo_image"]').value = '';

                if (mode === 'edit') {
                    memberLabel.textContent = 'Edit Anggota';
                    memberMethod.value = 'PUT';
                    memberForm.action = "{{ route('admin.tim.update', ':id') }}".replace(':id', button
                        .dataset
                        .id);
                    fieldNama.value = button.dataset.nama;
                    fieldNim.value = button.dataset.nim;
                    fieldRole.value = button.dataset.role;
                    fieldOrder.value = button.dataset.order;
                    fieldPhotoUrl.value = '';
                    fieldAccess.value = button.dataset.access;
                    fieldRefresh.value = button.dataset.refresh;
                    fieldStatus.checked = button.dataset.status === 'on';
                } else {
                    memberLabel.textContent = 'Tambah Anggota';
                    memberMethod.value = 'POST';
                    memberForm.action = "{{ route('admin.tim.store') }}";
                    fieldOrder.value = 0;
                }
            });
        });
    </script>
@endsection
