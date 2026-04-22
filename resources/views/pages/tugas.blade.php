@extends('layout.master')

@section('konten')
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fw-bold mb-2">Daftar Tugas Kuliah</h1>
                        <p class="text-muted mb-0">Halaman ini hanya tampilan contoh dengan data dummy. Backend penyimpanan
                            bisa kamu atur nanti.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach ($tugas as $item)
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-2">
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $item['judul'] }}</h4>
                                    <p class="mb-0 text-muted">{{ $item['mata_kuliah'] }}</p>
                                </div>
                                <div class="text-md-end">
                                    <span class="badge text-bg-secondary mb-2">Prioritas: {{ $item['prioritas'] }}</span>
                                    <p class="small text-muted mb-0">Deadline:
                                        {{ \Carbon\Carbon::parse($item['deadline'])->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                            <p class="mb-3">{{ $item['deskripsi'] }}</p>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span
                                    class="badge {{ $item['status'] === 'Selesai' ? 'text-bg-success' : ($item['status'] === 'Proses' ? 'text-bg-warning' : 'text-bg-danger') }}">
                                    Status: {{ $item['status'] }}
                                </span>
                                <a href="{{ route('tugas') }}" class="btn btn-sm btn-outline-secondary">Lihat Detail
                                    (dummy)</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
