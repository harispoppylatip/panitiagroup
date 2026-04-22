@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Detail Tugas</h2>
                <p class="text-muted mb-0">Informasi detail berbasis data dummy.</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Judul</p>
                        <p class="fw-semibold mb-0">{{ $tugas['judul'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Mata Kuliah</p>
                        <p class="fw-semibold mb-0">{{ $tugas['mata_kuliah'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Deadline</p>
                        <p class="fw-semibold mb-0">
                            {{ \Carbon\Carbon::parse($tugas['deadline'])->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Prioritas</p>
                        <p class="fw-semibold mb-0">{{ $tugas['prioritas'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Status</p>
                        <p class="fw-semibold mb-0">{{ $tugas['status'] }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted mb-1">Deskripsi</p>
                        <p class="mb-0">{{ $tugas['deskripsi'] }}</p>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.tugas.edit', $tugas['id']) }}" class="btn btn-outline-secondary">Edit</a>
                    <button type="button" class="btn btn-outline-danger"
                        onclick="alert('Fitur hapus dummy. Backend belum diaktifkan.')">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection
