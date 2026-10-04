@extends('layout.master')

@section('head')
    <style>
        .tugas-page {
            max-width: 860px;
        }

        .tugas-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 0.75rem;
        }

        .tugas-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .tugas-item {
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
            padding: 1.25rem;
            background: var(--surface-container-lowest);
            border: 1px solid var(--border-soft);
            border-radius: 14px;
        }

        .tugas-date {
            flex: 0 0 64px;
            text-align: center;
            padding: 0.55rem 0;
            border-radius: 10px;
            background: var(--surface-container-low);
            color: var(--text-main);
            line-height: 1.1;
        }

        .tugas-date .tgl {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .tugas-date .bln {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .tugas-body {
            flex: 1;
            min-width: 0;
        }

        .tugas-head {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 0.75rem;
        }

        .tugas-judul {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            overflow-wrap: anywhere;
        }

        .tugas-sisa {
            flex-shrink: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .tugas-sisa.mendesak {
            color: #c2410c;
        }

        body[data-theme='dark'] .tugas-sisa.mendesak {
            color: #fdba74;
        }

        .tugas-matkul {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0.15rem 0 0;
        }

        .tugas-desk {
            margin: 0.6rem 0 0;
            color: var(--text-main);
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .tugas-lewat .tugas-item {
            background: transparent;
        }

        .tugas-lewat .tugas-judul,
        .tugas-lewat .tugas-desk,
        .tugas-lewat .tugas-date {
            opacity: 0.6;
        }

        .tugas-kosong {
            padding: 2.5rem 1.25rem;
            text-align: center;
            color: var(--text-muted);
            border: 1px dashed var(--border-soft);
            border-radius: 14px;
        }

        @media (max-width: 575.98px) {
            .tugas-item {
                gap: 0.9rem;
                padding: 1rem;
            }

            .tugas-date {
                flex-basis: 52px;
            }

            .tugas-date .tgl {
                font-size: 1.25rem;
            }

            .tugas-head {
                flex-direction: column;
                gap: 0.15rem;
            }
        }
    </style>
@endsection

@section('konten')
    @php
        $hariIni = today();

        // [label sisa waktu, mendesak?] dari deadline_tanggal, fallback teks deadline
        $sisaWaktu = function ($item) use ($hariIni) {
            if (! $item->deadline_tanggal) {
                return [$item->deadline ?: 'Tanpa tanggal', false];
            }

            $selisih = (int) $hariIni->diffInDays($item->deadline_tanggal->copy()->startOfDay(), false);

            return [match (true) {
                $selisih === 0 => 'Hari ini',
                $selisih === 1 => 'Besok',
                $selisih > 1 => $selisih . ' hari lagi',
                default => 'Lewat ' . abs($selisih) . ' hari',
            }, $selisih >= 0 && $selisih <= 2];
        };

        $bagian = [
            ['judul' => 'Mendatang', 'items' => $mendatang, 'class' => ''],
            ['judul' => 'Sudah lewat', 'items' => $lewat, 'class' => 'tugas-lewat mt-5'],
        ];
    @endphp

    <section class="py-5">
        <div class="container tugas-page">
            <div class="mb-4">
                <h1 class="display-5 fw-bold mb-2">Tugas Kuliah</h1>
                <p class="text-muted mb-0">
                    {{ $mendatang->count() ? $mendatang->count() . ' tugas belum lewat deadline' : 'Belum ada tugas mendatang' }}
                </p>
            </div>

            @foreach ($bagian as $b)
                @continue($b['items']->isEmpty() && $b['judul'] !== 'Mendatang')
                <div class="{{ $b['class'] }}">
                    <h2 class="tugas-section-title">{{ $b['judul'] }}</h2>
                    @if ($b['items']->isEmpty())
                        <div class="tugas-kosong">Tidak ada tugas dengan deadline mendatang.</div>
                    @else
                        <div class="tugas-list">
                            @foreach ($b['items'] as $item)
                                @php
                                    $tgl = $item->deadline_tanggal?->copy()->locale('id');
                                    [$sisa, $mendesak] = $sisaWaktu($item);
                                @endphp
                                <article class="tugas-item">
                                    <div class="tugas-date" title="{{ $tgl?->translatedFormat('l, d F Y') }}">
                                        <span class="tgl">{{ $tgl?->format('d') ?? '-' }}</span>
                                        <span class="bln">{{ $tgl?->translatedFormat('M') ?? '' }}</span>
                                    </div>
                                    <div class="tugas-body">
                                        <div class="tugas-head">
                                            <h3 class="tugas-judul">{{ $item->namatugas }}</h3>
                                            <span class="tugas-sisa {{ $mendesak ? 'mendesak' : '' }}">{{ $sisa }}</span>
                                        </div>
                                        @if ($item->deadline && $item->deadline_tanggal)
                                            <p class="tugas-matkul">Deadline: {{ $item->deadline }}</p>
                                        @endif
                                        @if ($item->penjelasan)
                                            <p class="tugas-desk">{{ $item->penjelasan }}</p>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endsection
