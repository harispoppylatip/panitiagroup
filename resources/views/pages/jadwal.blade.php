@extends('layout.master')
@section('konten')
    <section class="jadwal-section">
        <div class="container">
            <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
                <div>
                    <h1 class="page-title">Jadwal Kuliah</h1>
                    <p class="page-subtitle">Daftar jadwal perkuliahan mingguan yang aktif.</p>
                </div>

                @if (!$error)
                    <div class="day-filter" role="group" aria-label="Filter Hari">
                        <button type="button" class="day-filter-btn active" data-day="Semua">Semua</button>
                        <button type="button" class="day-filter-btn" data-day="Senin">Senin</button>
                        <button type="button" class="day-filter-btn" data-day="Selasa">Selasa</button>
                        <button type="button" class="day-filter-btn" data-day="Rabu">Rabu</button>
                        <button type="button" class="day-filter-btn" data-day="Kamis">Kamis</button>
                        <button type="button" class="day-filter-btn" data-day="Jumat">Jumat</button>
                    </div>
                @endif
            </div>

            @if ($error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ $error }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="schedule-list" id="scheduleGrid">
                    @forelse ($jadwal as $hari => $items)
                        <div class="card schedule-group" data-day="{{ $hari }}">
                            <div class="schedule-group-header">
                                <h2 class="schedule-day-title">{{ $hari }}</h2>
                                <span class="schedule-day-count tabular-nums">{{ $items->count() }} mata kuliah</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle mb-0 schedule-table">
                                    <thead>
                                        <tr>
                                            <th class="col-time">Jam</th>
                                            <th class="col-course">Mata Kuliah</th>
                                            <th class="col-lecturer">Dosen</th>
                                            <th class="col-room">Ruang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td class="col-time tabular-nums">{{ $item['jamberangkat'] }} - {{ $item['jampulang'] }}</td>
                                                <td class="col-course">{{ $item['matkul'] }}</td>
                                                <td class="col-lecturer">{{ $item['dosen'] }}</td>
                                                <td class="col-room tabular-nums">{{ $item['ruang'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="schedule-empty">
                            <p class="text-muted mb-0">Belum ada data jadwal perkuliahan.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </section>

    <style>
        .day-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem;
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-md);
            overflow-x: auto;
            max-width: 100%;
        }

        .day-filter-btn {
            border: 0;
            background: transparent;
            color: var(--text-muted);
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: calc(var(--radius-md) - 3px);
            white-space: nowrap;
            transition: background-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }

        .day-filter-btn:hover {
            color: var(--text-main);
        }

        .day-filter-btn.active {
            background: var(--surface-elevated);
            color: var(--brand-900);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .schedule-group {
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-card);
        }

        .schedule-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: var(--surface-container-low);
            border-bottom: 1px solid var(--border-soft);
        }

        .schedule-day-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand-900);
            margin: 0;
        }

        .schedule-day-count {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .schedule-table {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: var(--surface-container-low);
            --bs-table-color: var(--text-main);
            --bs-table-border-color: var(--border-soft);
            margin-bottom: 0;
        }

        .schedule-table thead th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--border-soft);
            white-space: nowrap;
        }

        .schedule-table tbody td {
            padding: 0.9rem 1.25rem;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .schedule-table .col-time {
            width: 160px;
            white-space: nowrap;
            font-weight: 600;
            color: var(--brand-900);
        }

        .schedule-table .col-course {
            font-weight: 600;
            color: var(--text-main);
            min-width: 200px;
        }

        .schedule-table .col-lecturer {
            color: var(--text-muted);
            min-width: 180px;
        }

        .schedule-table .col-room {
            width: 130px;
            white-space: nowrap;
            color: var(--text-muted);
            font-weight: 500;
        }

        .schedule-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            border: 1px dashed var(--border-soft);
            border-radius: var(--radius-lg);
        }

        @media (max-width: 767.98px) {
            .schedule-group-header {
                padding: 0.85rem 1rem;
            }

            .schedule-table thead th,
            .schedule-table tbody td {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>

    <script>
        (function() {
            const filterButtons = document.querySelectorAll('.day-filter-btn');
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
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    applyFilter();
                });
            });

            applyFilter();
        })();
    </script>
@endsection
