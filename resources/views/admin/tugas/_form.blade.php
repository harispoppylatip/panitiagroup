@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Nama Tugas</label>
        <input type="text" name="namatugas" class="form-control" placeholder="Contoh: Laporan UTS"
            value="{{ old('namatugas', $tugas?->namatugas) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Deadline (teks)</label>
        <input type="text" name="deadline" class="form-control" placeholder="Contoh: Minggu depan"
            value="{{ old('deadline', $tugas?->deadline) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Deadline</label>
        <input type="date" name="deadline_tanggal" class="form-control"
            value="{{ old('deadline_tanggal', $tugas?->deadline_tanggal?->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Penjelasan</label>
        <textarea class="form-control" name="penjelasan" rows="4" placeholder="Tuliskan penjelasan tugas...">{{ old('penjelasan', $tugas?->penjelasan) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Link Materi / Tugas (URL)</label>
        <input type="url" name="link_materi" class="form-control" placeholder="https://..."
            value="{{ old('link_materi', $tugas?->link_materi) }}">
    </div>
    <div class="col-md-8">
        <label class="form-label">File Path / URL File Lampiran</label>
        <input type="text" name="file_path" class="form-control" placeholder="statics/media/contoh.pdf"
            value="{{ old('file_path', $tugas?->file_path) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama File Lampiran</label>
        <input type="text" name="file_name" class="form-control" placeholder="tugas.pdf"
            value="{{ old('file_name', $tugas?->file_name) }}">
    </div>
</div>
