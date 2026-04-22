@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Tambah Tugas</h2>
                <p class="text-muted mb-0">Form ini bersifat tampilan dummy.</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.tugas.createnew') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Judul Tugas</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Laporan UTS"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Kuliah</label>
                            <input type="text" name="matkul" class="form-control" placeholder="Contoh: Basis Data"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deadline</label>
                            <input type="date" name="deadline" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prioritas</label>
                            <select class="form-select" name="prioritas" required>
                                <option selected disabled value="">Pilih prioritas</option>
                                <option>Tinggi</option>
                                <option>Sedang</option>
                                <option>Rendah</option>
                            </select>
                        </div>
                        < <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deks" rows="4" placeholder="Tuliskan deskripsi tugas..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option>Belum Dikerjakan</option>
                            <option>Proses</option>
                            <option>Selesai</option>
                        </select>
                    </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-brand">Simpan</button>
                <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
