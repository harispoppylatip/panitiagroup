@extends('layout.master')
@section('konten')
    <section class="home-hero">
        <div class="hero-bg">
            <img src="{{ $heroImages->get('main')?->image_url ?? 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1300&q=80' }}"
                alt="{{ $heroImages->get('main')?->alt_text ?? 'Foto utama tim' }}" class="hero-bg-img">
            <div class="hero-scrim"></div>
        </div>

        <div class="container hero-container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="hero-copy">
                        <p class="hero-tag mb-3">BERANDA RESMI</p>
                        <h1 class="hero-title mb-3">Pemuda Akhir Zaman</h1>
                        <p class="hero-lead mb-4">
                            Kami merupakan kelompok mahasiswa Universitas Muhammadiyah Kalimantan Timur dari jurusan IT
                            Internasional yang dipersatukan oleh minat yang sama dalam dunia teknologi. Website ini kami
                            hadirkan sebagai solusi untuk mempermudah pengelolaan tim, komunikasi, serta produktivitas
                            kerja bersama.
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                            <a href="{{ route('scan.login') }}" class="btn btn-brand px-4 py-2">Masuk Scan Absen</a>
                            <a href="{{ route('galeri.index') }}" class="hero-link">Jelajahi Galeri</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-mosaic">
                        <figure class="mosaic-item">
                            <img src="{{ $heroImages->get('side1')?->image_url ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80' }}"
                                alt="{{ $heroImages->get('side1')?->alt_text ?? 'Aktivitas tim 1' }}">
                        </figure>
                        <figure class="mosaic-item">
                            <img src="{{ $heroImages->get('side2')?->image_url ?? 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=900&q=80' }}"
                                alt="{{ $heroImages->get('side2')?->alt_text ?? 'Aktivitas tim 2' }}">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <div class="team-head mb-4">
                <p class="team-head-tag mb-1">TIM KAMI</p>
                <h2 class="team-head-title mb-1">Anggota Tim</h2>
                <p class="team-head-sub mb-0">Orang-orang di balik Pemuda Akhir Zaman.</p>
            </div>

            <div class="row g-4 team-grid">
                @forelse ($teamMembers as $member)
                    <div class="col-12 col-md-6 col-lg-4">
                        <article class="team-card">
                            <div class="team-photo-wrap">
                                @if ($member->image_url)
                                    <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="team-photo">
                                @else
                                    <div class="team-photo team-photo-placeholder">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
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
        /* ===== HERO full-bleed cinematic ===== */
        .home-hero {
            position: relative;
            margin-top: -3rem;
            margin-bottom: 3.5rem;
            min-height: clamp(520px, 82vh, 760px);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            animation: kenburns 26s ease-in-out infinite alternate;
        }

        .hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg, rgba(9, 16, 28, 0.92) 0%, rgba(9, 16, 28, 0.66) 45%, rgba(9, 16, 28, 0.30) 100%);
        }

        .hero-container {
            position: relative;
            z-index: 1;
            padding: 4.5rem 0;
        }

        .hero-copy {
            animation: fadeUp 0.6s ease both;
        }

        .hero-tag {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.78);
        }

        .hero-title {
            font-size: clamp(2rem, 4.2vw, 3.4rem);
            line-height: 1.08;
            letter-spacing: -0.025em;
            color: #ffffff;
            max-width: 14ch;
        }

        .hero-lead {
            color: rgba(255, 255, 255, 0.86);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 54ch;
        }

        .hero-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .hero-link:hover {
            color: #ffffff;
        }

        /* ===== HERO MOSAIC (gambar responsif) ===== */
        .hero-mosaic {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .mosaic-item {
            margin: 0;
            position: relative;
            aspect-ratio: 4/3;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
            animation: fadeUp 0.6s ease both;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .mosaic-item:nth-child(2) {
            animation-delay: 0.15s;
        }

        .mosaic-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .mosaic-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.42);
        }

        .mosaic-item:hover img {
            transform: scale(1.06);
        }

        /* ===== TEAM ===== */
        .team-section {
            padding: 1rem 0 2.5rem;
        }

        .team-head-tag {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .team-head-title {
            font-size: clamp(1.5rem, 2.4vw, 2rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--brand-900);
        }

        .team-head-sub {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .team-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: var(--surface-container-lowest);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px var(--shadow-soft);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .team-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px var(--shadow-soft);
        }

        .team-photo-wrap {
            overflow: hidden;
        }

        .team-photo {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
            transition: transform 0.55s ease;
        }

        .team-photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-container-low);
            color: var(--brand-700);
            font-size: 2.6rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .team-card:hover .team-photo {
            transform: scale(1.05);
        }

        .team-card-body {
            padding: 1rem 1.1rem 1.2rem;
        }

        .team-name {
            font-size: 1.06rem;
            font-weight: 700;
            color: var(--brand-900);
        }

        .team-role {
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.02em;
            font-size: 0.84rem;
            text-transform: uppercase;
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

        @keyframes kenburns {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.08);
            }
        }

        body[data-theme='dark'] .hero-scrim {
            background: linear-gradient(100deg, rgba(7, 11, 18, 0.95) 0%, rgba(7, 11, 18, 0.75) 45%, rgba(7, 11, 18, 0.45) 100%);
        }

        body[data-theme='dark'] .team-card {
            background: var(--surface-container-lowest);
            border-color: var(--border-soft);
        }

        body[data-theme='dark'] .team-name {
            color: var(--brand-900);
        }

        body[data-theme='dark'] .team-role {
            color: var(--text-muted);
        }

        @media (max-width: 991.98px) {
            .hero-title {
                max-width: none;
            }

            .hero-mosaic {
                max-width: 480px;
                margin-inline: auto;
            }
        }

        @media (max-width: 767.98px) {
            .home-hero {
                margin: 0 0.75rem 2rem;
                min-height: 0;
                border-radius: 1.25rem;
                border: 1px solid rgba(255, 255, 255, 0.14);
            }

            .hero-container {
                padding: 3rem 1.25rem;
            }

            .hero-mosaic {
                gap: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.75rem;
            }

            .hero-lead {
                font-size: 0.95rem;
            }

            .hero-mosaic {
                grid-template-columns: 1fr;
                max-width: none;
            }

            .mosaic-item {
                aspect-ratio: 16/9;
            }

            .team-card-body {
                padding: 0.75rem 0.85rem 0.9rem;
            }

            .team-name {
                font-size: 0.95rem;
            }

            .team-role {
                font-size: 0.75rem;
            }
        }
    </style>
@endsection
