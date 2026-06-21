@extends('makam.layout')
@section('title', 'Dashboard')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang di Makam Admin Panel</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('makam.types.create') }}" class="btn btn-makam">
                <i class="bi bi-plus-lg"></i> Tambah Jenis Makam
            </a>
            <a href="{{ route('makam.news.create') }}" class="btn btn-makam-outline">
                <i class="bi bi-plus-lg"></i> Tambah Berita
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: rgba(64, 145, 108, 0.12); color: var(--accent);">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Jenis Makam</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $totalTypes }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: rgba(45, 106, 79, 0.12); color: var(--brand-500);">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Pesanan</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: rgba(255, 193, 7, 0.12); color: #ffc107;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Pesanan Baru</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $pendingOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background: rgba(233, 69, 96, 0.12); color: var(--accent);">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Berita</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $totalNews }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-soft py-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history"></i> Berita Terbaru</h5>
        </div>
        <div class="card-body p-0">
            @if ($latestNews->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach ($latestNews as $item)
                        <div class="list-group-item px-4 py-3"
                            style="background: transparent; border-color: var(--border-soft);">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1" style="color: var(--text-main);">{{ $item->title }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> {{ $item->author ?? 'Anonim' }} &middot;
                                        <i class="bi bi-calendar"></i>
                                        {{ $item->published_at ? $item->published_at->format('d M Y') : 'Belum terbit' }}
                                    </small>
                                </div>
                                <a href="{{ route('makam.news.edit', $item->id) }}" class="btn btn-sm btn-makam-outline">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-newspaper" style="font-size: 3rem; color: var(--text-muted);"></i>
                    <p class="text-muted mt-2 mb-0">Belum ada berita. <a href="{{ route('makam.news.create') }}"
                            style="color: var(--accent);">Buat berita pertama</a></p>
                </div>
            @endif
        </div>
    </div>
@endsection
