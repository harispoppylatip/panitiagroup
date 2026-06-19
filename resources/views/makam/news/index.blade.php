@extends('makam.layout')
@section('title', 'Berita')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Management Berita</h2>
            <p class="text-muted mb-0">Kelola semua berita dan artikel</p>
        </div>
        <a href="{{ route('makam.news.create') }}" class="btn btn-makam">
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($news->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light" style="background: var(--surface);">
                            <tr>
                                <th class="ps-4" style="border-top-left-radius: 8px;">#</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tanggal Terbit</th>
                                <th>Dibuat</th>
                                <th class="text-end pe-4" style="border-top-right-radius: 8px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($news as $index => $item)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $news->firstItem() + $index }}</td>
                                    <td>                                        @if ($item->image_url)
                                            <img src="{{ Storage::url($item->image_url) }}" alt="Thumbnail"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <div style="width: 50px; height: 50px; border-radius: 6px; background: var(--border-soft); display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width: 4px; height: 4px; border-radius: 50%; background: var(--accent); flex-shrink: 0;">
                                            </div>
                                            <span class="fw-medium">{{ Str::limit($item->title, 50) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $item->author ?? 'Anonim' }}</td>
                                    <td>
                                        @if ($item->published_at)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle"></i>
                                                {{ $item->published_at->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="bi bi-clock"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $item->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('makam.news.edit', $item->id) }}"
                                            class="btn btn-sm btn-makam-outline me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('makam.news.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                            Menampilkan {{ $news->firstItem() }} - {{ $news->lastItem() }} dari {{ $news->total() }} berita
                        </small>
                        <nav>
                            {{ $news->links() }}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-newspaper" style="font-size: 3.5rem; color: var(--text-muted);"></i>
                    <h5 class="mt-3 fw-semibold" style="color: var(--text-main);">Belum Ada Berita</h5>
                    <p class="text-muted mb-3">Mulai dengan menambahkan berita pertama Anda</p>
                    <a href="{{ route('makam.news.create') }}" class="btn btn-makam">
                        <i class="bi bi-plus-lg"></i> Tambah Berita
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
