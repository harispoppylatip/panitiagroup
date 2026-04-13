@extends('layout.master')
@section('konten')
    <section class="home-hero py-5">
        <div class="container py-md-4">
            <div class="text-center mb-5">
                <p class="section-tag mb-3">HALAMAN DEPAN</p>
                <h1 class="display-5 fw-bold mb-3 text-dark">Tim Profesional dengan Kolaborasi Solid</h1>
                <p class="mx-auto hero-subtext text-muted">
                    Kami berfokus pada kualitas kerja, ketepatan eksekusi, dan komunikasi yang rapi dalam setiap proyek.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <a href="{{ route('scan.login') }}" class="btn btn-primary px-4">Masuk Scan Absen</a>
                    <a href="{{ route('gambare') }}" class="btn btn-outline-secondary px-4">Lihat Galeri</a>
                </div>
            </div>

            <div class="row g-4 team-grid">
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim1/700/500" alt="Foto tim 1" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Ari Pratama</h3>
                            <p class="team-role mb-0">Project Coordinator</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim2/700/500" alt="Foto tim 2" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Nadia Putri</h3>
                            <p class="team-role mb-0">UI/UX Designer</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim3/700/500" alt="Foto tim 3" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Rizki Fadillah</h3>
                            <p class="team-role mb-0">Frontend Developer</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim4/700/500" alt="Foto tim 4" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Dina Rahma</h3>
                            <p class="team-role mb-0">Backend Developer</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim5/700/500" alt="Foto tim 5" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Fajar Maulana</h3>
                            <p class="team-role mb-0">Quality Assurance</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="team-card">
                        <img src="https://picsum.photos/seed/tim6/700/500" alt="Foto tim 6" class="team-photo">
                        <div class="team-card-body">
                            <h3 class="team-name">Salsa Dewi</h3>
                            <p class="team-role mb-0">Data Analyst</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <style>
        .home-hero {
            position: relative;
        }

        .section-tag {
            display: inline-block;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            background: rgba(46, 91, 135, 0.11);
            color: #2e5b87;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 0.75rem;
        }

        .hero-subtext {
            max-width: 720px;
            font-size: 1.1rem;
        }

        .team-card {
            height: 100%;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 16px 32px rgba(19, 39, 62, 0.08);
            border: 1px solid rgba(18, 38, 63, 0.09);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: cardReveal 0.65s ease both;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 45px rgba(19, 39, 62, 0.14);
        }

        .team-photo {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .team-card-body {
            padding: 1rem 1rem 1.25rem;
            text-align: center;
        }

        .team-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1f3b5c;
            margin-bottom: 0.25rem;
        }

        .team-role {
            color: #5f6f84;
            font-weight: 500;
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

        @media (max-width: 576px) {
            .team-photo {
                height: 220px;
            }
        }
    </style>
@endsection
