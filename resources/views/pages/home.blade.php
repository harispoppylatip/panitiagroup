@extends('layout.master')
@section('konten')
    <section class="home-hero">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <div class="container">
            <div class="hero-panel mb-5">
                <div class="row align-items-center g-4 g-xl-5">
                    <div class="col-xl-6">
                        <div class="hero-copy">
                            <p class="hero-tag mb-3">BERANDA RESMI</p>
                            <h1 class="hero-title mb-3">Wajah Baru Beranda Dengan Foto Tim Lebih Menarik</h1>
                            <p class="hero-lead mb-4">
                                Mengadopsi gaya visual modern: fokus pada foto utama, tipografi tegas, serta nuansa
                                profesional agar identitas tim terlihat lebih kuat.
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <a href="{{ route('scan.login') }}" class="btn btn-brand px-4 py-2">Masuk Scan Absen</a>
                            </div>

                            <div class="hero-metrics">
                                <div class="metric-item">
                                    <span class="metric-value">24+</span>
                                    <span class="metric-label">Program Kerja Aktif</span>
                                </div>
                                <div class="metric-item">
                                    <span class="metric-value">12</span>
                                    <span class="metric-label">Divisi Kolaboratif</span>
                                </div>
                                <div class="metric-item">
                                    <span class="metric-value">95%</span>
                                    <span class="metric-label">Target Tercapai</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="hero-gallery">
                            <div class="hero-main-photo">
                                <img src="{{ $heroImages->get('main')?->image_url ?? 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1300&q=80' }}"
                                    alt="{{ $heroImages->get('main')?->alt_text ?? 'Foto utama tim' }}" class="img-cover">
                                <div class="hero-main-overlay">
                                    <p class="overlay-mini mb-1">IKATAN MAHASISWA</p>
                                    <h3 class="overlay-title mb-1">Kolaborasi, Integritas, dan Dampak Nyata</h3>
                                    <p class="overlay-text mb-0">Universitas Muhammadiyah Kalimantan Timur</p>
                                </div>
                            </div>

                            <div class="hero-side-grid">
                                <div class="side-photo-card">
                                    <img src="{{ $heroImages->get('side1')?->image_url ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80' }}"
                                        alt="{{ $heroImages->get('side1')?->alt_text ?? 'Aktivitas tim 1' }}"
                                        class="img-cover">
                                </div>
                                <div class="side-photo-card">
                                    <img src="{{ $heroImages->get('side2')?->image_url ?? 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=900&q=80' }}"
                                        alt="{{ $heroImages->get('side2')?->alt_text ?? 'Aktivitas tim 2' }}"
                                        class="img-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 team-grid">
                @forelse ($teamMembers as $member)
                    <div class="col-12 col-md-6 col-lg-4">
                        <article class="team-card">
                            <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="team-photo">
                            <div class="team-card-body">
                                <p class="team-role mb-1">{{ $member->role }}</p>
                                <h3 class="team-name mb-0">{{ $member->name }}</h3>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Belum ada anggota tim</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .home-hero {
            position: relative;
            overflow: hidden;
            padding: 1rem 0 1.5rem;
        }

        .orb {
            position: absolute;
            border-radius: 999px;
            filter: blur(4px);
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            width: 320px;
            height: 320px;
            top: 30px;
            right: -80px;
            background: radial-gradient(circle at center, rgba(195, 143, 60, 0.28), rgba(195, 143, 60, 0));
        }

        .orb-2 {
            width: 260px;
            height: 260px;
            bottom: 20px;
            left: -70px;
            background: radial-gradient(circle at center, rgba(46, 91, 135, 0.22), rgba(46, 91, 135, 0));
        }

        .home-hero .container {
            position: relative;
            z-index: 1;
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(247, 250, 255, 0.86));
            border: 1px solid rgba(18, 38, 63, 0.08);
            border-radius: 24px;
            padding: 1.4rem;
            box-shadow: 0 24px 48px rgba(19, 39, 62, 0.1);
            animation: fadeUp 0.7s ease both;
        }

        .hero-tag {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            background: rgba(18, 38, 63, 0.09);
            color: #1f3b5c;
            font-weight: 700;
            letter-spacing: 0.11em;
            font-size: 0.75rem;
        }

        .hero-title {
            font-size: clamp(1.95rem, 2.8vw, 3.1rem);
            line-height: 1.12;
            letter-spacing: -0.02em;
            color: #10253f;
            max-width: 16ch;
        }

        .hero-lead {
            color: #43566f;
            font-size: 1.06rem;
            max-width: 52ch;
        }

        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            max-width: 640px;
        }

        .metric-item {
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(18, 38, 63, 0.08);
            border-radius: 14px;
            padding: 0.75rem 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .metric-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #173457;
            line-height: 1;
        }

        .metric-label {
            font-size: 0.82rem;
            color: #5b6c80;
            font-weight: 600;
        }

        .hero-gallery {
            display: grid;
            gap: 0.75rem;
        }

        .hero-main-photo,
        .side-photo-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.48);
            box-shadow: 0 18px 34px rgba(13, 28, 44, 0.2);
        }

        .hero-main-photo {
            height: 340px;
        }

        .hero-main-overlay {
            position: absolute;
            inset: auto 0 0;
            padding: 1.1rem 1.2rem;
            background: linear-gradient(to top, rgba(12, 24, 37, 0.82), rgba(12, 24, 37, 0.18));
            color: #f9fbff;
        }

        .overlay-mini {
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            font-weight: 700;
            opacity: 0.9;
        }

        .overlay-title {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .overlay-text {
            font-size: 0.9rem;
            opacity: 0.92;
        }

        .hero-side-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .side-photo-card {
            height: 150px;
        }

        .img-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .team-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(244, 248, 254, 0.95));
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(19, 39, 62, 0.09);
            border: 1px solid rgba(18, 38, 63, 0.09);
            transform: translateY(0);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            animation: cardReveal 0.7s ease both;
        }

        .team-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 38px rgba(19, 39, 62, 0.15);
        }

        .team-photo {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 576px) {
            .team-photo {
                aspect-ratio: auto;
                height: auto;
                object-fit: contain;
            }
        }

        .team-card-body {
            padding: 1rem 1.1rem 1.2rem;
        }

        @media (max-width: 576px) {
            .team-card-body {
                padding: 0.75rem 0.85rem 0.9rem;
            }
        }

        .team-name {
            font-size: 1.06rem;
            font-weight: 700;
            color: #1f3b5c;
        }

        .team-role {
            color: #5a6c82;
            font-weight: 600;
            letter-spacing: 0.02em;
            font-size: 0.84rem;
            text-transform: uppercase;
        }

        @media (max-width: 576px) {
            .team-name {
                font-size: 0.95rem;
            }

            .team-role {
                font-size: 0.75rem;
            }
        }

        .team-grid .col-lg-4:nth-child(1) .team-card,
        .team-grid .col-lg-4:nth-child(4) .team-card {
            animation-delay: 0.05s;
        }

        .team-grid .col-lg-4:nth-child(2) .team-card,
        .team-grid .col-lg-4:nth-child(5) .team-card {
            animation-delay: 0.15s;
        }

        .team-grid .col-lg-4:nth-child(3) .team-card,
        .team-grid .col-lg-4:nth-child(6) .team-card {
            animation-delay: 0.25s;
        }

        @keyframes cardReveal {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body[data-theme='dark'] .hero-panel {
            background: linear-gradient(135deg, rgba(15, 23, 36, 0.9), rgba(18, 30, 45, 0.88));
            border-color: rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .hero-tag {
            background: rgba(255, 255, 255, 0.08);
            color: #d7e5f7;
        }

        body[data-theme='dark'] .hero-title {
            color: #ebf2fc;
        }

        body[data-theme='dark'] .hero-lead,
        body[data-theme='dark'] .metric-label,
        body[data-theme='dark'] .team-role {
            color: #a8b7ca;
        }

        body[data-theme='dark'] .metric-item,
        body[data-theme='dark'] .team-card {
            background: rgba(17, 24, 39, 0.72);
            border-color: rgba(148, 163, 184, 0.18);
        }

        body[data-theme='dark'] .metric-value,
        body[data-theme='dark'] .team-name {
            color: #e4edf9;
        }

        body[data-theme='dark'] .btn-outline-secondary {
            color: #d9e6f8;
            border-color: rgba(148, 163, 184, 0.32);
        }

        body[data-theme='dark'] .btn-outline-secondary:hover {
            color: #0f1724;
            background-color: #d9e6f8;
            border-color: #d9e6f8;
        }

        @media (max-width: 1199.98px) {
            .hero-title {
                max-width: none;
            }

            .hero-main-photo {
                height: 320px;
            }
        }

        @media (max-width: 767.98px) {
            .hero-panel {
                border-radius: 20px;
                padding: 1rem;
            }

            .hero-metrics {
                grid-template-columns: 1fr;
            }

            .hero-main-photo {
                height: 270px;
            }

            .side-photo-card {
                height: 120px;
            }
        }

        @media (max-width: 576px) {
            .team-photo {
                height: 220px;
            }
        }
    </style>
@endsection
