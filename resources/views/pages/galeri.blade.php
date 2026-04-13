@extends('layout.master')
@section('konten')
    <section class="gallery-section py-5">
        <div class="container">
            <div class="gallery-header text-center mb-5">
                <h1 class="gallery-title display-4 fw-bold text-dark">Galeri</h1>
                <p class="gallery-subtitle text-muted fs-5">Koleksi foto dan gambar kami</p>
            </div>

            <div class="row g-4">
                @foreach ($gambar as $item)
                    <!-- Single Gallery Item Template -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="gallery-card card h-100 border-0 shadow-lg">
                            <div class="gallery-image-wrapper position-relative overflow-hidden">
                                <img src="{{ asset('images/' . $item->gambar) }}" alt="{{ $item->detail }}"
                                    class="gallery-image card-img-top">
                                <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100">
                                    <a href="{{ asset('images/' . $item->gambar) }}"
                                        class="gallery-link btn btn-light rounded-circle d-flex align-items-center justify-content-center"
                                        data-lightbox="gallery" data-title="{{ $item->detail }}">
                                        <i class="bi bi-search"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="gallery-info card-body">
                                <h5 class="gallery-item-title card-title">{{ $item->detail }}</h5>
                                <form action="/galeri/hapus/{{ $item->id }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus gambar ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .gallery-section {
            background: transparent;
            padding: 60px 0;
            min-height: 80vh;
        }

        .gallery-header {
            margin-bottom: 50px;
        }

        .gallery-title {
            font-size: 3rem;
            font-weight: 700;
            color: #1f3b5c;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .gallery-subtitle {
            font-size: 1.1rem;
            color: #5f6f84;
        }

        .gallery-card {
            border-radius: 14px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(18, 38, 63, 0.09);
        }

        .gallery-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 36px rgba(19, 39, 62, 0.16) !important;
        }

        .gallery-image-wrapper {
            height: 280px;
            background: #f0f0f0;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-image-wrapper:hover .gallery-image {
            transform: scale(1.08);
        }

        .gallery-overlay {
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .gallery-image-wrapper:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-link {
            width: 50px;
            height: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #2c3e50;
            font-size: 24px;
        }

        .gallery-link:hover {
            background-color: #2e5b87 !important;
            color: white;
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(46, 91, 135, 0.3);
        }

        .gallery-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .gallery-item-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f3b5c;
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .gallery-title {
                font-size: 2rem;
            }
        }

        /* Animation */
        .gallery-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
