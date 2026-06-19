@extends('makam.layout')
@section('title', 'Tambah Berita')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tambah Berita</h2>
            <p class="text-muted mb-0">Buat berita atau artikel baru</p>
        </div>
        <a href="{{ route('makam.news.index') }}" class="btn btn-makam-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('makam.news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Berita <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                placeholder="Masukkan judul berita" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="author" class="form-label fw-semibold">Penulis</label>
                                <input type="text" id="author" name="author"
                                    class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}"
                                    placeholder="Nama penulis">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="published_at" class="form-label fw-semibold">Tanggal Terbit</label>
                                <input type="date" id="published_at" name="published_at"
                                    class="form-control @error('published_at') is-invalid @enderror"
                                    value="{{ old('published_at', date('Y-m-d')) }}">
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">Konten Berita <span
                                    class="text-danger">*</span></label>
                            <div class="mb-2 d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="wrapText('**', '**')" title="Bold">
                                    <i class="bi bi-type-bold"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="wrapText('_', '_')"
                                    title="Italic">
                                    <i class="bi bi-type-italic"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertBullet()"
                                    title="Bullet List">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertNumbered()"
                                    title="Numbered List">
                                    <i class="bi bi-list-ol"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertQuote()"
                                    title="Quote">
                                    <i class="bi bi-quote"></i>
                                </button>
                            </div>
                            <textarea id="content" name="content" rows="14" class="form-control @error('content') is-invalid @enderror"
                                placeholder="Tulis konten berita di sini..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle"></i> Gunakan **teks** untuk bold, _teks_ untuk italic
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Gambar Berita</label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                onchange="previewImage(event)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle"></i> Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB.
                            </small>
                            <div id="imagePreview" class="mt-2 d-none">
                                <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-makam">
                                <i class="bi bi-check-lg"></i> Simpan Berita
                            </button>
                            <a href="{{ route('makam.news.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-soft py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-eye"></i> Pratinjau</h5>
                </div>
                <div class="card-body">
                    <div id="previewArea">
                        <p class="text-muted text-center py-4 mb-0">
                            <i class="bi bi-arrow-left"></i> Isi judul dan konten untuk melihat pratinjau
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const previewArea = document.getElementById('previewArea');

        function updatePreview() {
            const title = titleInput.value || 'Judul Berita';
            const content = contentInput.value || 'Konten berita akan tampil di sini...';

            let formattedContent = content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/_(.*?)_/g, '<em>$1</em>')
                .replace(/^> (.*?)$/gm,
                    '<blockquote class="border-start border-3 ps-3 my-2" style="border-color: var(--accent) !important; color: var(--text-muted);">$1</blockquote>'
                    )
                .replace(/^- (.*?)$/gm, '<li class="ms-3">$1</li>')
                .replace(/^\d+\. (.*?)$/gm, '<li class="ms-3" style="list-style: decimal;">$1</li>')
                .replace(/\n\n/g, '</p><p>')
                .replace(/\n/g, '<br>');

            previewArea.innerHTML = `
            <h5 class="fw-bold mb-2" style="color: var(--text-main);">${title}</h5>
            <hr style="border-color: var(--border-soft);">
            <div style="color: var(--text-main); line-height: 1.7;">
                <p>${formattedContent}</p>
            </div>
        `;
        }

        titleInput.addEventListener('input', updatePreview);
        contentInput.addEventListener('input', updatePreview);

        function wrapText(before, after) {
            const textarea = contentInput;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selected = textarea.value.substring(start, end);
            const replacement = before + selected + after;
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.selectionStart = start + before.length;
            textarea.selectionEnd = start + replacement.length - after.length;
            textarea.focus();
            updatePreview();
        }

        function insertBullet() {
            const textarea = contentInput;
            const start = textarea.selectionStart;
            textarea.value = textarea.value.substring(0, start) + '\n- ' + textarea.value.substring(start);
            textarea.focus();
            updatePreview();
        }

        function insertNumbered() {
            const textarea = contentInput;
            const start = textarea.selectionStart;
            textarea.value = textarea.value.substring(0, start) + '\n1. ' + textarea.value.substring(start);
            textarea.focus();
            updatePreview();
        }

        function insertQuote() {
            const textarea = contentInput;
            const start = textarea.selectionStart;
            textarea.value = textarea.value.substring(0, start) + '\n> ' + textarea.value.substring(start);
            textarea.focus();
            updatePreview();
        }

        // Initial preview
        updatePreview();

        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('img');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
                img.src = '';
            }
        }
    </script>
@endpush
