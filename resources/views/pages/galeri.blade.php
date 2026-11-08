@extends('layout.master')
@section('konten')
    <section class="gallery-section">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Galeri Dokumentasi</h1>
                <p class="page-subtitle">Dokumentasi kegiatan dan kebersamaan Pemuda Akhir Zaman.</p>
            </div>

            @if ($files->count() > 0)
                @php
                    $pageFiles = $files->getCollection()->all();
                    $images = array_values(
                        array_filter($pageFiles, fn($f) => str_starts_with($f['mime_type'], 'image/')),
                    );
                    $videos = array_values(
                        array_filter($pageFiles, fn($f) => str_starts_with($f['mime_type'], 'video/')),
                    );
                @endphp

                <div class="row g-3 photo-grid" id="photoGrid">
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
                    <div class="video-section mt-5">
                        <h2 class="gallery-section-heading mb-3">Video</h2>
                        <div class="row g-3 video-grid" id="videoGrid">
                            @foreach ($videos as $file)
                                <div class="col-6 col-md-4 col-lg-3">
                                    @php $siap = $file['ready'] ?? true; @endphp
                                    <figure class="photo-item video-item {{ $siap ? '' : 'is-processing' }}"
                                        data-name="{{ $file['name'] }}" data-video-id="{{ $file['id'] }}"
                                        data-mime="{{ $file['mime_type'] }}" data-ready="{{ $siap ? '1' : '0' }}">
                                        <img src="{{ $file['thumb'] ?? '' }}" alt="{{ $file['name'] }}" loading="lazy"
                                            class="photo-img">
                                        <div class="video-overlay">
                                            @if ($siap)
                                                <i class="bi bi-play-circle-fill"></i>
                                            @else
                                                <i class="bi bi-hourglass-split"></i>
                                                <span class="video-processing-text">Sedang diproses</span>
                                            @endif
                                        </div>
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Inline player modal --}}
                    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body p-0">
                                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
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

                <div class="gallery-pagination mt-4 pt-2">
                    {{ $files->links('pagination.gallery') }}
                </div>
            @else
                <div class="gallery-empty">
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
        <span class="lightbox-counter tabular-nums" id="lightboxCounter"></span>
    </div>

    <style>
        .gallery-section-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brand-900);
        }

        .gallery-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            border: 1px dashed var(--border-soft);
            border-radius: var(--radius-lg);
        }

        .gallery-pagination {
            display: flex;
            justify-content: center;
        }

        .gallery-pagination .pagination {
            gap: 0.35rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 0;
        }

        .photo-item {
            position: relative;
            margin: 0;
            border-radius: var(--radius-lg);
            overflow: hidden;
            aspect-ratio: 4 / 3;
            cursor: pointer;
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-card);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .photo-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card-hover);
        }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .photo-item:hover .photo-img {
            transform: scale(1.03);
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
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.32));
            font-size: 2.15rem;
            pointer-events: none;
        }

        .video-item.is-processing .video-overlay {
            flex-direction: column;
            gap: 0.35rem;
            font-size: 1.65rem;
            background: rgba(8, 14, 24, 0.6);
        }

        .video-processing-text {
            font-size: 0.8125rem;
            font-weight: 600;
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
            font-size: 0.95rem;
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
            backdrop-filter: blur(8px);
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
            border-radius: var(--radius-md);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
        }

        .lightbox-caption {
            color: #dbe4ef;
            font-size: 0.9rem;
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
            font-size: 0.875rem;
        }

        .lightbox-close {
            position: absolute;
            top: 1rem;
            right: 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.15s ease;
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
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: background 0.15s ease;
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
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }

            .lightbox-close {
                width: 38px;
                height: 38px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('photoGrid');
            const items = grid ? Array.from(grid.querySelectorAll('.photo-item')) : [];
            const videoItems = Array.from(document.querySelectorAll('.video-item'));
            if (items.length === 0 && videoItems.length === 0) return;

            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightboxImg');
            const lightboxCaption = document.getElementById('lightboxCaption');
            const lightboxCounter = document.getElementById('lightboxCounter');
            let currentIndex = 0;

            if (items.length > 0) {
                items.forEach(function(item, index) {
                    item.addEventListener('click', function() {
                        show(index);
                    });
                });
            }

            function show(index) {
                if (items.length === 0) return;

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
                if (!lightbox) return;

                lightbox.hidden = true;
                lightboxImg.src = '';
                document.body.style.overflow = '';
            }

            // Video items open inline player (MP4 disiapkan galeri:sync terjadwal)
            videoItems.forEach(function(vi) {
                vi.addEventListener('click', function() {
                    const id = vi.dataset.videoId;
                    if (!id) return;

                    const player = document.getElementById('playerVideo');
                    const source = player.querySelector('source');
                    const loading = document.getElementById('videoLoading');
                    const modalEl = document.getElementById('videoModal');

                    if (vi.dataset.ready === '0') {
                        loading.querySelector('span').textContent =
                            'Video sedang diproses, coba lagi beberapa menit lagi.';
                        loading.querySelector('.spinner-border').style.display = 'none';
                        loading.hidden = false;
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        return;
                    }

                    const url = '{{ url('/galeri/video') }}/' + encodeURIComponent(id);

                    source.type = 'video/mp4';
                    source.src = url;

                    loading.hidden = false;
                    player.load();

                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                });
            });

            const playerEl = document.getElementById('playerVideo');
            if (playerEl) {
                ['playing', 'canplay', 'loadeddata'].forEach(function(evt) {
                    playerEl.addEventListener(evt, function() {
                        document.getElementById('videoLoading').hidden = true;
                    });
                });

                const tampilkanGagal = function() {
                    const loading = document.getElementById('videoLoading');
                    loading.querySelector('span').textContent =
                        'Video belum siap atau gagal dimuat. Coba lagi beberapa menit lagi.';
                    loading.querySelector('.spinner-border').style.display = 'none';
                    loading.hidden = false;
                };
                playerEl.addEventListener('error', tampilkanGagal);
                playerEl.querySelector('source')?.addEventListener('error', tampilkanGagal);

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
                    loading.querySelector('.spinner-border').style.display = '';
                });
            }

            const lightboxClose = document.getElementById('lightboxClose');
            const lightboxPrev = document.getElementById('lightboxPrev');
            const lightboxNext = document.getElementById('lightboxNext');

            if (items.length > 0) {
                lightboxClose.addEventListener('click', close);
                lightboxPrev.addEventListener('click', function() {
                    show(currentIndex - 1);
                });
                lightboxNext.addEventListener('click', function() {
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
            }
        });
    </script>
@endsection
