@extends('layout.master')

@section('konten')
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fw-bold mb-2">Daftar Tugas Kuliah</h1>
                        {{-- <p class="text-muted mb-0">Halaman ini hanya tampilan contoh dengan data dummy. Backend penyimpanan
                            bisa kamu atur nanti.</p> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Filter Status</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('tugas', ['status' => 'semua']) }}"
                                class="btn btn-sm {{ !$status || $status === 'semua' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Semua Tugas
                            </a>
                            <a href="{{ route('tugas', ['status' => 'Belum Dikerjakan']) }}"
                                class="btn btn-sm {{ $status === 'Belum Dikerjakan' ? 'btn-danger' : 'btn-outline-danger' }}">
                                Belum Dikerjakan
                            </a>
                            <a href="{{ route('tugas', ['status' => 'Proses']) }}"
                                class="btn btn-sm {{ $status === 'Proses' ? 'btn-warning' : 'btn-outline-warning' }}">
                                Dalam Proses
                            </a>
                            <a href="{{ route('tugas', ['status' => 'Selesai']) }}"
                                class="btn btn-sm {{ $status === 'Selesai' ? 'btn-success' : 'btn-outline-success' }}">
                                Selesai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($tugas as $item)
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-2">
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $item->judul }}</h4>
                                    <p class="mb-0 text-muted">{{ $item->mata_kuliah }}</p>
                                </div>
                                <div class="text-md-end">
                                    <span class="badge text-bg-secondary mb-2">Prioritas: {{ $item->prioritas }}</span>
                                    <p class="small text-muted mb-0">Deadline:
                                        {{ \Carbon\Carbon::parse($item->deadline)->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                            <p class="mb-3">{{ $item->deskripsi }}</p>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span
                                    class="badge {{ $item->status === 'Selesai' ? 'text-bg-success' : ($item->status === 'Proses' ? 'text-bg-warning' : 'text-bg-danger') }}">
                                    Status: {{ $item->status }}
                                </span>
                                {{-- <a href="{{ route('tugas') }}" class="btn btn-sm btn-outline-secondary">Lihat Detail
                                    (dummy)</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <p class="text-muted mb-0">Tidak ada tugas yang ditemukan dengan filter yang dipilih.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination Section -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <nav aria-label="Page navigation">
                    {{ $tugas->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
@endsection
