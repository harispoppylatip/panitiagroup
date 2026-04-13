@extends('layout.master')
@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="mb-5">
                <h1 class="display-4 fw-bold text-dark">Jadwal Kuliah Tim Kami</h1>
                <p class="text-muted fs-5">Berikut adalah jadwal kuliah tim kami yang terbaru.</p>
            </div>

            <!-- Filter Buttons Navigation -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="btn-group flex-wrap gap-2" role="group">
                        <button type="button" class="btn btn-primary btn-sm filter-btn active" data-day="semua">
                            Semua Hari
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-day="senin">
                            Senin
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-day="selasa">
                            Selasa
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-day="rabu">
                            Rabu
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-day="kamis">
                            Kamis
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-day="jumat">
                            Jumat
                        </button>
                    </div>
                </div>
            </div>

            <!-- Column Layout -->
            <div class="row g-4 schedule-columns">
                <!-- Senin Column -->
                <div class="col-lg-4 col-md-6 col-sm-12 schedule-item" data-day="senin">
                    <div class="card h-100 shadow-lg">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #2e5b87 0%, #1f3b5c 100%); color: white;">
                            <h5 class="mb-1 fw-bold">Senin</h5>
                            <small>2 Mata Kuliah</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="border-start border-4 border-primary p-3 mb-3 bg-light rounded">
                                <span class="badge bg-primary mb-2">09:00 - 10:30</span>
                                <h6 class="fw-bold text-dark mb-2 small">Pemrograman Web Lanjut</h6>
                                <small class="text-muted d-block mb-1">Dosen: Roflide Hasudungan S.Kom., M.Sc</small>
                                <small class="text-muted">Ruang: GE-317</small>
                            </div>
                            <div class="border-start border-4 border-primary p-3 bg-light rounded">
                                <span class="badge bg-primary mb-2">10:40 - 12:10</span>
                                <h6 class="fw-bold text-dark mb-2 small">Keamanan Komputer dan Jaringan</h6>
                                <small class="text-muted d-block mb-1">Dosen: Achmad Jaya Adhi Nugraha</small>
                                <small class="text-muted">Ruang: GE-317</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selasa Column -->
                <div class="col-lg-4 col-md-6 col-sm-12 schedule-item" data-day="selasa">
                    <div class="card h-100 shadow-lg">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #3e6f9f 0%, #2e5b87 100%); color: white;">
                            <h5 class="mb-1 fw-bold">Selasa</h5>
                            <small>2 Mata Kuliah</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="border-start border-4 border-danger p-3 mb-3 bg-light rounded">
                                <span class="badge bg-danger mb-2">07:30 - 10:20</span>
                                <h6 class="fw-bold text-dark mb-2 small">Pemrograman Web Lanjut</h6>
                                <small class="text-muted d-block mb-1">Dosen: Roflide Hasudungan S.Kom., M.Sc</small>
                                <small class="text-muted">Ruang: Lab Hardware</small>
                            </div>
                            <div class="border-start border-4 border-danger p-3 bg-light rounded">
                                <span class="badge bg-danger mb-2">13:30 - 14:00</span>
                                <h6 class="fw-bold text-dark mb-2 small">Kesemitaan Muhammadiyahan</h6>
                                <small class="text-muted d-block mb-1">Dosen: Prof Dr. MIFEDWIL JANDRA Mag</small>
                                <small class="text-muted">Ruang: QC-201</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rabu Column -->
                <div class="col-lg-4 col-md-6 col-sm-12 schedule-item" data-day="rabu">
                    <div class="card h-100 shadow-lg">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #5a7ea8 0%, #2e5b87 100%); color: white;">
                            <h5 class="mb-1 fw-bold">Rabu</h5>
                            <small>2 Mata Kuliah</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="border-start border-4 border-info p-3 mb-3 bg-light rounded">
                                <span class="badge bg-info mb-2">07:20 - 08:50</span>
                                <h6 class="fw-bold text-dark mb-2 small">Rekayasa Perangkat Lunak</h6>
                                <small class="text-muted d-block mb-1">Dosen: Roflide Hasudungan S.Kom., M.Sc</small>
                                <small class="text-muted">Ruang: Lab Hardware</small>
                            </div>
                            <div class="border-start border-4 border-info p-3 bg-light rounded">
                                <span class="badge bg-info mb-2">13:30 - 14:00</span>
                                <h6 class="fw-bold text-dark mb-2 small">Kunmuhammaldiaham</h6>
                                <small class="text-muted d-block mb-1">Dosen: Prof Dr. MIFEDWIL JANDRA MAg</small>
                                <small class="text-muted">Ruang: QC-201</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kamis Column -->
                <div class="col-lg-4 col-md-6 col-sm-12 schedule-item" data-day="kamis">
                    <div class="card h-100 shadow-lg">
                        <div class="card-header bg-warning-gradient"
                            style="background: linear-gradient(135deg, #7f98b8 0%, #2e5b87 100%); color: white;">
                            <h5 class="mb-1 fw-bold">Kamis</h5>
                            <small>Tidak ada jadwal</small>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 200px;">
                            <p class="text-muted text-center mb-0">Tidak ada jadwal kuliah</p>
                        </div>
                    </div>
                </div>

                <!-- Jumat Column -->
                <div class="col-lg-4 col-md-6 col-sm-12 schedule-item" data-day="jumat">
                    <div class="card h-100 shadow-lg">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #486f9d 0%, #1f3b5c 100%); color: white;">
                            <h5 class="mb-1 fw-bold">Jumat</h5>
                            <small>1 Mata Kuliah</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="border-start border-4 border-success p-3 bg-light rounded">
                                <span class="badge bg-success mb-2">14:00 - 15:30</span>
                                <h6 class="fw-bold text-dark mb-2 small">Etika Dan Hukum Profesi</h6>
                                <small class="text-muted d-block mb-1">Dosen: Wawan Joko Pranoto S.Kom, M.TI</small>
                                <small class="text-muted">Ruang: R-317</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const selectedDay = this.dataset.day;

                // Update active button styling
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-primary');
                });
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

                // Show/Hide columns
                document.querySelectorAll('.schedule-item').forEach(col => {
                    if (selectedDay === 'semua' || col.dataset.day === selectedDay) {
                        col.style.display = 'block';
                    } else {
                        col.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
