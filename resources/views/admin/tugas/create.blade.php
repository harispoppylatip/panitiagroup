@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Tambah Tugas</h2>
                <p class="text-muted mb-0">Kolom sama dengan Data Table n8n.</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.tugas.createnew') }}" method="POST">
                    @csrf
                    @include('admin.tugas._form', ['tugas' => null])

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-brand">Simpan</button>
                        <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
