@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Management Tugas</h2>
                <p class="text-muted mb-0">Contoh halaman CRUD dengan data dummy.</p>
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
