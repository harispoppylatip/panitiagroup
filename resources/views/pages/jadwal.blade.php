@extends('layout.master')
@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="mb-4 text-center text-md-start">
                <h1 class="display-5 fw-bold mb-2">Jadwal Matkul</h1>
                <p class="text-muted fs-5 mb-0">Data sementara untuk integrasi backend. Silakan Anda sambungkan ke
                    database/API nanti.</p>
            </div>

            <div class="card border-0 shadow-sm mb-4 filter-panel">
                <div class="card-body d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">
                    <div class="btn-group flex-wrap gap-2" role="group" aria-label="Filter Hari">
                        <button type="button" class="btn btn-primary btn-sm filter-btn active" data-day="Semua">Semua
                            Hari</button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn"
                            data-day="Senin">Senin</button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn"
                            data-day="Selasa">Selasa</button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn"
                            data-day="Rabu">Rabu</button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn"
                            data-day="Kamis">Kamis</button>
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn"
                            data-day="Jumat">Jumat</button>
                    </div>
                </div>
            </div>

            <div class="schedule-list" id="scheduleGrid">
                @forelse ($jadwal as $hari => $items)
                    <div class="card border-0 shadow-lg schedule-group mb-4" data-day="{{ $hari }}">
                        <div class="card-body">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                                <div>
                                    <span
                                        class="badge text-bg-primary rounded-pill px-3 py-2 mb-2">{{ $hari }}</span>

                                </div>
                                <small class="text-muted fw-semibold">{{ $items->count() }} mata kuliah</small>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0 schedule-table">
                                    <thead>
                                        <tr>
                                            <th class="text-muted fw-semibold">Jam</th>
                                            <th class="text-muted fw-semibold">Mata Kuliah</th>
                                            <th class="text-muted fw-semibold">Dosen</th>
                                            <th class="text-muted fw-semibold">Ruang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td class="fw-semibold">{{ $item['jamberangkat'] }} -
                                                    {{ $item['jampulang'] }}</td>
                                                <td>{{ $item['matkul'] }}</td>
                                                <td>{{ $item['dosen'] }}</td>
                                                <td>{{ $item['ruang'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">Belum ada data jadwal.</div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .filter-panel {
            backdrop-filter: blur(4px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(18, 38, 63, 0.08);
        }

        .schedule-group {
            border-radius: 18px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: rgba(255, 255, 255, 0.96);
        }

        .schedule-group:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 30px rgba(18, 38, 63, 0.18) !important;
        }

        .schedule-table thead th {
            border-bottom: 1px solid rgba(18, 38, 63, 0.12);
            padding-top: 0.25rem;
        }

        .schedule-table tbody tr {
            border-top: 1px solid rgba(18, 38, 63, 0.08);
        }

        .schedule-table tbody tr:first-child {
            border-top: 0;
        }

        .schedule-table td {
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        .schedule-list {
            animation: slideUp 0.5s ease both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        (function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const scheduleItems = document.querySelectorAll('.schedule-group');
            let activeDay = 'Semua';

            function applyFilter() {
                scheduleItems.forEach(item => {
                    const itemDay = item.dataset.day;
                    const dayMatch = activeDay === 'Semua' || itemDay === activeDay;
                    item.style.display = dayMatch ? 'block' : 'none';
                });
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    activeDay = this.dataset.day;

                    filterButtons.forEach(b => {
                        b.classList.remove('btn-primary');
                        b.classList.add('btn-outline-primary');
                    });

                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');

                    applyFilter();
                });
            });

            applyFilter();
        })();
    </script>
@endsection
