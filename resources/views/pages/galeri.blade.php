@extends('layout.master')
@section('konten')
    <section class="gallery-section">
        <div class="container">
            <div class="gallery-header text-center mb-5">
                <p class="gallery-eyebrow mb-2">DOKUMENTASI KEGIATAN</p>
                <h1 class="gallery-title mb-3">Galeri Pemuda Akhir Zaman</h1>
                <p class="gallery-subtitle mb-0">
                    Dokumentasi kegiatan kami, diperbarui langsung dari Google Drive.
                </p>
            </div>

            @if (count($files) > 0)
                @php
                    $images = array_values(array_filter($files, fn($f) => str_starts_with($f['mime_type'], 'image/')));
                    $videos = array_values(array_filter($files, fn($f) => str_starts_with($f['mime_type'], 'video/')));
                @endphp

                <div class="row g-3 g-md-4 photo-grid" id="photoGrid">
                    @foreach ($images as $file)
                        <div class="col-6 col-md-4 col-lg-3">
                            <figure class="photo-item" data-name="{{ $file['name'] }}">
                                <img src="{{ route('galeri.photo', $file['id']) }}" alt="{{ $file['name'] }}" loading="lazy"
                                    class="photo-img">
                            </figure>
                        </div>
                    @endforeach
                </div>

                @if (count($videos) > 0)
                    <hr class="my-4">
                    <h3 class="mb-3">Video</h3>
                    <div class="row g-3 g-md-4 video-grid" id="videoGrid">
                        @foreach ($videos as $file)
                            <div class="col-6 col-md-4 col-lg-3">
                                <figure class="photo-item video-item" data-name="{{ $file['name'] }}"
                                    data-video-id="{{ $file['id'] }}" data-mime="{{ $file['mime_type'] }}">
                                    <img src="{{ $file['thumb'] ?? '' }}" alt="{{ $file['name'] }}" loading="lazy"
                                        class="photo-img">
                                    <div class="video-overlay">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </div>
                                </figure>
                            </div>
                        @endforeach
                    </div>

                    {{-- Inline player modal --}}
                    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body p-0">
                                    <div class="ratio ratio-16x9">
                                        <div class="video-loading" id="videoLoading" hidden>
                                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                            </div>
                                            <span>Memuat video...</span>
                                        </div>
                                        <video id="playerVideo" controls playsinline style="width:100%;height:100%">
                                            <source src="" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">Belum ada foto. Dokumentasi akan segera hadir.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Lightbox --}}
    <div class="lightbox" id="lightbox" hidden>
        <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
        <button type="button" class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Sebelumnya">
            <i class="bi bi-chevron-left"></i>
        </button>
        <figure class="lightbox-figure">
            <img src="" alt="" class="lightbox-img" id="lightboxImg">
            <figcaption class="lightbox-caption" id="lightboxCaption"></figcaption>
        </figure>
        <button type="button" class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Berikutnya">
            <i class="bi bi-chevron-right"></i>
        </button>
        <span class="lightbox-counter" id="lightboxCounter"></span>
    </div>

    <style>
        .gallery-section {
            padding-top: 0.5rem;
        }

        .gallery-eyebrow {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            color: #5f6f84;
            text-transform: uppercase;
        }

        .gallery-title {
            font-size: clamp(1.9rem, 3vw, 2.7rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #10253f;
        }

        .gallery-subtitle {
            font-size: 1.05rem;
            color: #43566f;
            max-width: 52ch;
            margin-left: auto;
            margin-right: auto;
        }

        body[data-theme='dark'] .gallery-eyebrow {
            color: #a7b4c5;
        }

        body[data-theme='dark'] .gallery-title {
            color: #e5eef9;
        }

        body[data-theme='dark'] .gallery-subtitle {
            color: #a7b4c5;
        }

        .photo-item {
            position: relative;
            margin: 0;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 4 / 3;
            cursor: pointer;
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .photo-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 36px var(--shadow-soft);
        }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .photo-item:hover .photo-img {
            transform: scale(1.04);
        }

        /* Video item overlay */
        .video-item {
            position: relative;
        }

        .video-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.95);
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.28));
            font-size: 2.25rem;
            pointer-events: none;
        }

        /* Loading overlay di dalam modal player */
        .video-loading {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(8, 14, 24, 0.85);
            color: #dbe4ef;
            font-weight: 600;
            font-size: 1rem;
        }

        .video-loading[hidden] {
            display: none;
        }

        /* Lightbox */
        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(8, 14, 24, 0.92);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox[hidden] {
            display: none;
        }

        .lightbox-figure {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            max-width: min(92vw, 1100px);
            max-height: 88vh;
        }

        .lightbox-img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
        }

        .lightbox-caption {
            color: #dbe4ef;
            font-size: 0.95rem;
            text-align: center;
            word-break: break-word;
        }

        .lightbox-counter {
            position: absolute;
            top: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            color: #a7b4c5;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .lightbox-close {
            position: absolute;
            top: 1rem;
            right: 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: background 0.2s ease;
        }

        .lightbox-close:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: background 0.2s ease;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.24);
        }

        .lightbox-prev {
            left: 1rem;
        }

        .lightbox-next {
            right: 1rem;
        }

        @media (max-width: 575.98px) {
            .lightbox-nav {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .lightbox-close {
                width: 40px;
                height: 40px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('photoGrid');
            if (!grid) return;

            const items = Array.from(grid.querySelectorAll('.photo-item'));
            const videoItems = Array.from(document.querySelectorAll('.video-item'));
            if (items.length === 0 && videoItems.length === 0) return;

            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightboxImg');
            const lightboxCaption = document.getElementById('lightboxCaption');
            const lightboxCounter = document.getElementById('lightboxCounter');
            let currentIndex = 0;

            function show(index) {
                currentIndex = (index + items.length) % items.length;
                const item = items[currentIndex];
                const img = item.querySelector('img');

                lightboxImg.src = img.src;
                lightboxImg.alt = img.alt;
                lightboxCaption.textContent = item.dataset.name || '';
                lightboxCounter.textContent = (currentIndex + 1) + ' / ' + items.length;
                lightbox.hidden = false;
                document.body.style.overflow = 'hidden';
            }

            function close() {
                lightbox.hidden = true;
                lightboxImg.src = '';
                document.body.style.overflow = '';
            }

            items.forEach(function(item, index) {
                item.addEventListener('click', function() {
                    show(index);
                });
            });

            // Video items open inline player (server mengonversi ke MP4 bila perlu)
            videoItems.forEach(function(vi) {
                vi.addEventListener('click', function() {
                    const id = vi.dataset.videoId;
                    if (!id) return;

                    // Route internal yang menyajikan MP4 yang siap diputar
                    const url = '{{ url('/galeri/video') }}/' + encodeURIComponent(id);
                    const player = document.getElementById('playerVideo');
                    const source = player.querySelector('source');
                    const loading = document.getElementById('videoLoading');

                    // Hasil akhir selalu MP4 (server mengonversi otomatis)
                    source.type = 'video/mp4';
                    source.src = url;

                    loading.hidden = false;
                    player.load();

                    // Tampilkan modal Bootstrap
                    const modalEl = document.getElementById('videoModal');
                    const bsModal = new bootstrap.Modal(modalEl);
                    bsModal.show();
                });
            });

            // Sembunyikan indikator loading begitu video siap diputar
            const playerEl = document.getElementById('playerVideo');
            ['playing', 'canplay', 'loadeddata'].forEach(function(evt) {
                playerEl.addEventListener(evt, function() {
                    document.getElementById('videoLoading').hidden = true;
                });
            });

            playerEl.addEventListener('error', function() {
                const loading = document.getElementById('videoLoading');
                loading.hidden = true;
                loading.querySelector('span').textContent = 'Gagal memuat video.';
            });

            // Pause/stop video saat modal ditutup
            const videoModalEl = document.getElementById('videoModal');
            videoModalEl.addEventListener('hidden.bs.modal', function() {
                const player = document.getElementById('playerVideo');
                player.pause();
                player.currentTime = 0;
                const source = player.querySelector('source');
                source.src = '';
                player.load();

                const loading = document.getElementById('videoLoading');
                loading.hidden = true;
                loading.querySelector('span').textContent = 'Memuat video...';
            });

            document.getElementById('lightboxClose').addEventListener('click', close);
            document.getElementById('lightboxPrev').addEventListener('click', function() {
                show(currentIndex - 1);
            });
            document.getElementById('lightboxNext').addEventListener('click', function() {
                show(currentIndex + 1);
            });

            lightbox.addEventListener('click', function(event) {
                if (event.target === lightbox) close();
            });

            document.addEventListener('keydown', function(event) {
                if (lightbox.hidden) return;

                if (event.key === 'Escape') close();
                if (event.key === 'ArrowLeft') show(currentIndex - 1);
                if (event.key === 'ArrowRight') show(currentIndex + 1);
            });
        });
    </script>
@endsection
