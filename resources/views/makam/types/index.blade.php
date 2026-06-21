@extends('makam.layout')
@section('title', 'Jenis Makam')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Management Jenis Makam</h2>
            <p class="text-muted mb-0">Kelola jenis dan blok makam yang dijual</p>
        </div>
        <a href="{{ route('makam.types.create') }}" class="btn btn-makam">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Makam
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($types->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light" style="background: var(--surface);">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Nama</th>
                                <th>Blok</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $index => $type)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $types->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width: 4px; height: 4px; border-radius: 50%; background: var(--accent); flex-shrink: 0;">
                                            </div>
                                            <span class="fw-medium">{{ $type->nama }}</span>
                                        </div>
                                        @if ($type->deskripsi)
                                            <small
                                                class="text-muted d-block ms-3">{{ Str::limit($type->deskripsi, 60) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $type->blok ?? '-' }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($type->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($type->stok_tersedia > 0)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success">{{ $type->stok_tersedia }}</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($type->is_active)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="bi bi-x-circle"></i> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('makam.types.edit', $type->id) }}"
                                            class="btn btn-sm btn-makam-outline me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('makam.types.destroy', $type->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus jenis makam ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-top" style="border-color: var(--border-soft);">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Menampilkan {{ $types->firstItem() }} - {{ $types->lastItem() }} dari {{ $types->total() }}
                            jenis
                        </small>
                        <nav>
                            {{ $types->links() }}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box-seam" style="font-size: 3.5rem; color: var(--text-muted);"></i>
                    <h5 class="mt-3 fw-semibold" style="color: var(--text-main);">Belum Ada Jenis Makam</h5>
                    <p class="text-muted mb-3">Mulai dengan menambahkan jenis makam yang tersedia</p>
                    <a href="{{ route('makam.types.create') }}" class="btn btn-makam">
                        <i class="bi bi-plus-lg"></i> Tambah Jenis Makam
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
