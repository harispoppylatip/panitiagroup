@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Detail Tugas</h2>
                <p class="text-muted mb-0">ID {{ $tugas->id }}</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <p class="text-muted mb-1">Nama Tugas</p>
                        <p class="fw-semibold mb-0">{{ $tugas->namatugas }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Deadline</p>
                        <p class="fw-semibold mb-0">{{ $tugas->deadline ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Tanggal Deadline</p>
                        <p class="fw-semibold mb-0">
                            {{ $tugas->deadline_tanggal?->locale('id')->translatedFormat('d F Y') ?? '-' }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted mb-1">Penjelasan</p>
                        <p class="mb-0" style="white-space: pre-line">{{ $tugas->penjelasan ?: '-' }}</p>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.tugas.edit', $tugas->id) }}" class="btn btn-outline-secondary">Edit</a>
                    <form action="{{ route('admin.tugas.delete', $tugas->id) }}" method="POST"
                        onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
