@extends('layout.master')
@section('konten')
    <section class="home-hero">
        <div class="hero-bg">
            @php
                $mainHero = $heroImages->get('main');
                $desktopSrc =
                    $mainHero?->image_url ??
                    'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1300&q=80';
                $mobileSrc = $mainHero?->image_url_mobile ?? $desktopSrc;
                $altText = $mainHero?->alt_text ?? 'Foto utama tim';
            @endphp
            <picture>
                <source media="(max-width: 767.98px)" srcset="{{ $mobileSrc }}">
                <img src="{{ $desktopSrc }}" alt="{{ $altText }}" class="hero-bg-img">
            </picture>
            <div class="hero-scrim"></div>
        </div>

        <div class="container hero-container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-copy">
                        <p class="hero-eyebrow mb-2">Universitas Muhammadiyah Kalimantan Timur</p>
                        <h1 class="hero-title mb-3">Pemuda Akhir Zaman</h1>
                        <p class="hero-lead hero-lead-desktop d-none d-md-block mb-4">
                            Kelompok mahasiswa jurusan IT Internasional yang dipersatukan oleh minat dalam dunia teknologi.
                            Platform ini hadir untuk mempermudah pengelolaan tim, jadwal perkuliahan, tugas, dan transparansi kas bersama.
                        </p>
                        <p class="hero-lead hero-lead-mobile d-md-none mb-4">
                            Kelompok mahasiswa UMKT jurusan IT Internasional yang dipersatukan oleh minat pada dunia
                            teknologi.
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-3 hero-actions">
                            <a href="{{ route('galeri.index') }}" class="btn-hero-primary">
                                <span>Jelajahi Galeri</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="{{ route('jadwal') }}" class="btn-hero-secondary">
                                Lihat Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <div class="team-head d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-4 reveal">
                <div>
                    <h2 class="team-head-title mb-1">Anggota Tim</h2>
                    <p class="team-head-sub mb-0">Mahasiswa di balik kolaborasi Pemuda Akhir Zaman.</p>
                </div>
                @if ($teamMembers->count() > 0)
                    <span class="team-count tabular-nums">{{ $teamMembers->count() }} anggota</span>
                @endif
            </div>

            <div class="row g-3 g-md-4 team-grid">
                @forelse ($teamMembers as $member)
                    <div class="col-12 col-sm-6 col-lg-3 reveal" data-delay="{{ min($loop->index * 60, 240) }}">
                        <article class="team-card">
                            <div class="team-photo-wrap">
                                @if ($member->image_url)
                                    <img src="{{ $member->image_url }}" alt="{{ $member->name }}" loading="lazy" class="team-photo">
                                @else
                                    <div class="team-photo team-photo-placeholder">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="team-card-body">
                                <h3 class="team-name mb-1">{{ $member->name }}</h3>
                                <p class="team-role mb-0">{{ $member->role }}</p>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="team-empty">
                            <p class="text-muted mb-0">Belum ada anggota tim yang terdaftar.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        /* Hero Section */
        .home-hero {
            position: relative;
            margin-top: -2.5rem;
            margin-bottom: 3.5rem;
            min-height: clamp(480px, 74vh, 680px);
            display: flex;
            align-items: center;
            overflow: hidden;
            background-color: #090d16;
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
        }

        .hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(95deg, rgba(9, 13, 22, 0.90) 0%, rgba(9, 13, 22, 0.65) 48%, rgba(9, 13, 22, 0.25) 100%);
        }

        .hero-container {
            position: relative;
            z-index: 1;
            padding: 4.5rem 0;
        }

        .hero-eyebrow,
        .hero-title,
        .hero-lead,
        .hero-actions {
            animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .hero-eyebrow {
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.72);
            letter-spacing: 0.01em;
        }

        .hero-title {
            animation-delay: 0.04s;
            font-size: clamp(2.15rem, 4vw, 3.25rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #ffffff;
            text-wrap: balance;
        }

        .hero-lead {
            animation-delay: 0.1s;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1rem;
            line-height: 1.65;
            max-width: 46ch;
            text-wrap: pretty;
        }

        .hero-actions {
            animation-delay: 0.16s;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ffffff;
            color: #0f172a;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.65rem 1.2rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.15s ease;
        }

        .btn-hero-primary:hover {
            background: #f1f5f9;
            color: #0f172a;
            transform: translateY(-1px);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.88);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.65rem 1.15rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.32);
            color: #ffffff;
        }

        /* Team Section */
        .team-section {
            padding: 0.5rem 0 2rem;
        }

        .team-head-title {
            font-size: clamp(1.35rem, 2.2vw, 1.75rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--brand-900);
        }

        .team-head-sub {
            color: var(--text-muted);
            font-size: 0.9375rem;
        }

        .team-count {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .team-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: var(--surface-container-lowest);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .team-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card-hover);
        }

        .team-photo-wrap {
            overflow: hidden;
            background: var(--surface-container-low);
            border-bottom: 1px solid var(--border-soft);
        }

        .team-photo {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .team-photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-container-low);
            color: var(--text-muted);
            font-size: 2rem;
            font-weight: 700;
        }

        .team-card:hover .team-photo {
            transform: scale(1.03);
        }

        .team-card-body {
            padding: 0.95rem 1.05rem 1.05rem;
        }

        .team-name {
            font-size: 0.975rem;
            font-weight: 600;
            color: var(--brand-900);
            line-height: 1.3;
        }

        .team-role {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.8125rem;
        }

        .team-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            border: 1px dashed var(--border-soft);
            border-radius: var(--radius-lg);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body[data-theme='dark'] .hero-scrim {
            background: linear-gradient(95deg, rgba(7, 11, 18, 0.94) 0%, rgba(7, 11, 18, 0.72) 48%, rgba(7, 11, 18, 0.35) 100%);
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1), transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-eyebrow,
            .hero-title,
            .hero-lead,
            .hero-actions {
                animation: none;
            }

            .reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        @media (max-width: 767.98px) {
            main {
                padding-top: 0;
                padding-bottom: 1.5rem;
            }

            .home-hero {
                margin: 0 0 1.75rem;
                min-height: clamp(420px, 62vh, 540px);
                border-radius: 0;
                border: 0;
                align-items: flex-end;
            }

            .hero-container {
                padding: 3rem 1.25rem 1.5rem;
                max-width: none;
                width: 100%;
            }

            .hero-bg-img {
                object-position: center;
            }

            .hero-scrim {
                background: linear-gradient(180deg, rgba(9, 13, 22, 0.08) 0%, rgba(9, 13, 22, 0.4) 45%, rgba(9, 13, 22, 0.90) 100%);
            }

            body[data-theme='dark'] .hero-scrim {
                background: linear-gradient(180deg, rgba(7, 11, 18, 0.1) 0%, rgba(7, 11, 18, 0.42) 45%, rgba(7, 11, 18, 0.94) 100%);
            }

            .hero-title {
                font-size: 1.65rem;
                line-height: 1.15;
            }

            .hero-lead {
                font-size: 0.875rem;
                line-height: 1.6;
                max-width: none;
            }

            .hero-actions {
                gap: 0.65rem;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                min-height: 42px;
                padding: 0.55rem 1rem;
            }

            .team-section .container {
                max-width: none;
                padding-inline: 16px;
            }

            .team-grid {
                --bs-gutter-x: 0;
                row-gap: 0.85rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var revealEls = document.querySelectorAll('.reveal');
            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!('IntersectionObserver' in window) || reduceMotion) {
                revealEls.forEach(function(el) {
                    el.classList.add('is-visible');
                });
                return;
            }

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -30px 0px'
            });

            revealEls.forEach(function(el) {
                if (el.dataset.delay) {
                    el.style.transitionDelay = el.dataset.delay + 'ms';
                }
                observer.observe(el);
            });
        });
    </script>
@endsection
