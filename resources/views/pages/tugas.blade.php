@extends('layout.master')

@section('head')
    <style>
        .tugas-page {
            max-width: 860px;
        }

        .tugas-section-title {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin: 0 0 0.75rem;
        }

        .tugas-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .tugas-item {
            display: flex;
            gap: 1.15rem;
            align-items: flex-start;
            padding: 1.15rem 1.25rem;
            background: var(--surface-container-lowest);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            transition: border-color 0.15s ease;
        }

        .tugas-date {
            flex: 0 0 60px;
            text-align: center;
            padding: 0.5rem 0;
            border-radius: var(--radius-md);
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
            color: var(--brand-900);
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .tugas-date .tgl {
            display: block;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .tugas-date .bln {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.15rem;
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
            font-size: 1.025rem;
            font-weight: 600;
            color: var(--brand-900);
            margin: 0;
            overflow-wrap: anywhere;
        }

        .tugas-sisa {
            flex-shrink: 0;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .tugas-sisa.mendesak {
            color: #c2410c;
        }

        body[data-theme='dark'] .tugas-sisa.mendesak {
            color: #fbbf24;
        }

        .tugas-matkul {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin: 0.2rem 0 0;
        }

        .tugas-desk {
            margin: 0.55rem 0 0;
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--text-main);
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .tugas-lewat .tugas-item {
            background: transparent;
            box-shadow: none;
        }

        .tugas-lewat .tugas-judul,
        .tugas-lewat .tugas-desk,
        .tugas-lewat .tugas-date {
            opacity: 0.6;
        }

        .tugas-attachments {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.85rem;
        }

        .tugas-btn-attachment {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            background: var(--surface-container-low);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-sm);
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
            max-width: 100%;
        }

        .tugas-btn-attachment:hover {
            background: var(--surface-container);
            border-color: var(--brand-500);
            color: var(--brand-700);
        }

        body[data-theme='dark'] .tugas-btn-attachment:hover {
            color: var(--brand-900);
        }

        .tugas-btn-attachment i {
            font-size: 0.95rem;
            color: var(--brand-500);
            flex-shrink: 0;
        }

        .tugas-btn-attachment .attachment-label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tugas-lewat .tugas-btn-attachment {
            opacity: 0.7;
        }

        .tugas-kosong {
            padding: 2.5rem 1.25rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            border: 1px dashed var(--border-soft);
            border-radius: var(--radius-lg);
        }

        @media (max-width: 575.98px) {
            .tugas-item {
                gap: 0.85rem;
                padding: 1rem;
            }

            .tugas-date {
                flex-basis: 50px;
            }

            .tugas-date .tgl {
                font-size: 1.2rem;
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
            ['judul' => 'Sudah lewat', 'items' => $lewat, 'class' => 'tugas-lewat mt-4 pt-2'],
        ];
    @endphp

    <section class="tugas-section">
        <div class="container tugas-page">
            <div class="page-header">
                <h1 class="page-title">Tugas Kuliah</h1>
                <p class="page-subtitle">
                    {{ $mendatang->count() ? $mendatang->count() . ' tugas belum lewat tenggat waktu' : 'Belum ada tugas dengan tenggat waktu mendatang' }}
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
                                        @if ($item->file_url || $item->link_materi_url)
                                            <div class="tugas-attachments">
                                                @if ($item->file_url)
                                                    <a href="{{ $item->file_url }}" target="_blank" rel="noopener noreferrer" class="tugas-btn-attachment" title="{{ $item->file_name ?: 'File Tugas' }}">
                                                        <i class="bi {{ $item->file_icon_class }}"></i>
                                                        <span class="attachment-label">{{ $item->file_name ?: 'File Tugas' }}</span>
                                                        <i class="bi bi-download ms-auto" style="font-size: 0.75rem; opacity: 0.7;"></i>
                                                    </a>
                                                @endif
                                                @if ($item->link_materi_url)
                                                    <a href="{{ $item->link_materi_url }}" target="_blank" rel="noopener noreferrer" class="tugas-btn-attachment" title="{{ $item->link_materi }}">
                                                        <i class="bi bi-link-45deg"></i>
                                                        <span class="attachment-label">Link Materi / Tugas</span>
                                                        <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.75rem; opacity: 0.7;"></i>
                                                    </a>
                                                @endif
                                            </div>
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
