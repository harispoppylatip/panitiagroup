@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Tugas</h2>
                <p class="text-muted mb-0">Form edit ini bersifat tampilan dummy.</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.tugas.editsend', ['id' => $id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Judul Tugas</label>
                            <input type="text" class="form-control" name="judul" value="{{ $tugas['judul'] }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Kuliah</label>
                            <input type="text" class="form-control" name="mata_kuliah"
                                value="{{ $tugas['mata_kuliah'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" name="deadline" value="{{ $tugas['deadline'] }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prioritas</label>
                            <select class="form-select" name="prioritas" required>
                                <option {{ $tugas['prioritas'] === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option {{ $tugas['prioritas'] === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option {{ $tugas['prioritas'] === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="4">{{ $tugas['deskripsi'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option {{ $tugas['status'] === 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan
                                </option>
                                <option {{ $tugas['status'] === 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option {{ $tugas['status'] === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-brand">Update</button>
                        <a href="{{ route('admin.tugas.show', $tugas['id']) }}" class="btn btn-outline-secondary">Lihat
                            Detail</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
