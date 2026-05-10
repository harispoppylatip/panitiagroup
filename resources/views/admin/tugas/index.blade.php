@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <style>
            /* Dark mode overrides for admin tugas page */
            body[data-theme='dark'] .card,
            body[data-theme='dark'] .table,
            body[data-theme='dark'] .table-responsive {
                background: rgba(15, 23, 36, 0.95) !important;
                border-color: rgba(148, 163, 184, 0.14) !important;
                color: #f1f5f9 !important;
            }

            body[data-theme='dark'] .card-body {
                background: rgba(15, 23, 36, 0.95) !important;
            }

            body[data-theme='dark'] .card.border-0.shadow-sm {
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.36) !important;
                background: rgba(15, 23, 36, 0.95) !important;
            }

            body[data-theme='dark'] .table {
                background: rgba(15, 23, 36, 0.95) !important;
                color: #f1f5f9 !important;
            }

            body[data-theme='dark'] .table thead th {
                background: linear-gradient(135deg, #2c5b87, #1f3b5c) !important;
                color: #fff !important;
                border-bottom: 2px solid rgba(148, 163, 184, 0.2) !important;
                font-weight: 600;
            }

            body[data-theme='dark'] .table tbody {
                background: rgba(30, 41, 59, 0.8) !important;
            }

            body[data-theme='dark'] .table tbody tr {
                background: rgba(30, 41, 59, 0.8) !important;
                border-bottom: 1px solid rgba(148, 163, 184, 0.1) !important;
            }

            body[data-theme='dark'] .table tbody td {
                color: #f1f5f9 !important;
                vertical-align: middle;
                padding: 12px 16px;
                background: rgba(30, 41, 59, 0.8) !important;
            }

            body[data-theme='dark'] .table tbody tr:hover {
                background: rgba(46, 91, 135, 0.15) !important;
            }

            body[data-theme='dark'] .table tbody tr:hover td {
                background: rgba(46, 91, 135, 0.15) !important;
            }

            body[data-theme='dark'] .fw-semibold {
                color: #fff !important;
                font-weight: 600;
            }

            body[data-theme='dark'] .btn-outline-primary,
            body[data-theme='dark'] .btn-outline-secondary,
            body[data-theme='dark'] .btn-outline-danger {
                color: #fff !important;
                background: rgba(15, 23, 36, 0.8) !important;
                border-color: rgba(148, 163, 184, 0.2) !important;
            }

            body[data-theme='dark'] .btn-outline-primary:hover {
                background: #2563eb !important;
                color: #fff !important;
                border-color: #2563eb !important;
            }

            body[data-theme='dark'] .btn-outline-secondary:hover {
                background: #64748b !important;
                color: #fff !important;
                border-color: #64748b !important;
            }

            body[data-theme='dark'] .btn-outline-danger:hover {
                background: #ef4444 !important;
                color: #fff !important;
                border-color: #ef4444 !important;
            }

            body[data-theme='dark'] .badge {
                background: rgba(255, 255, 255, 0.08) !important;
                color: #f1f5f9 !important;
                border: 1px solid rgba(148, 163, 184, 0.1) !important;
                font-weight: 500;
            }

            body[data-theme='dark'] .badge.text-bg-success {
                background: rgba(34, 197, 94, 0.15) !important;
                color: #86efac !important;
                border: 1px solid rgba(34, 197, 94, 0.3) !important;
            }

            body[data-theme='dark'] .badge.text-bg-warning {
                background: rgba(234, 179, 8, 0.15) !important;
                color: #fde047 !important;
                border: 1px solid rgba(234, 179, 8, 0.3) !important;
            }

            body[data-theme='dark'] .badge.text-bg-danger {
                background: rgba(239, 68, 68, 0.15) !important;
                color: #fca5a5 !important;
                border: 1px solid rgba(239, 68, 68, 0.3) !important;
            }

            body[data-theme='dark'] h2 {
                color: #fff !important;
            }
        </style>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Management Tugas</h2>
                {{-- <p class="text-muted mb-0">Contoh halaman CRUD dengan data dummy.</p> --}}
            </div>
            <a href="{{ route('admin.tugas.create') }}" class="btn btn-brand">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Judul</th>
                                <th>Mata Kuliah</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tugas as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item['judul'] }}</td>
                                    <td>{{ $item['mata_kuliah'] }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $item['status'] === 'Selesai' ? 'text-bg-success' : ($item['status'] === 'Proses' ? 'text-bg-warning' : 'text-bg-danger') }}">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.tugas.show', $item['id']) }}"
                                            class="btn btn-sm btn-outline-primary">Detail</a>
                                        <a href="{{ route('admin.tugas.edit', $item['id']) }}"
                                            class="btn btn-sm btn-outline-secondary">Edit</a>

                                        <form action="{{ route('admin.tugas.delete', [$item->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
