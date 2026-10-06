@extends('layout.masteradmin')

@section('konten')
    <style>
        .detail-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .detail-header {
            margin-bottom: 2rem;
        }

        .detail-header h1 {
            color: var(--brand-900);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .detail-header p {
            color: var(--text-muted);
            margin: 0;
        }

        .member-detail {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 1rem;
            box-shadow: 0 4px 12px var(--shadow-soft);
            margin-bottom: 1.25rem;
            padding: 1.5rem;
            scroll-margin-top: 1.5rem;
        }

        .member-heading {
            align-items: center;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
        }

        .member-heading h2 {
            color: var(--text-main);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .member-nim {
            color: var(--text-muted);
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .detail-field {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .detail-label {
            color: var(--text-muted);
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .detail-value {
            color: var(--text-main);
            font-size: 1rem;
            overflow-wrap: anywhere;
        }

        .detail-error {
            color: #b91c1c;
            margin: 0;
        }

        body[data-theme='dark'] .detail-error {
            color: #fca5a5;
        }

        @media (max-width: 575.98px) {

            .member-heading,
            .detail-grid {
                display: block;
            }

            .member-nim {
                display: block;
                margin-top: 0.4rem;
            }

            .detail-field+.detail-field {
                margin-top: 0.75rem;
            }
        }
    </style>

    <div class="detail-container">
        <div class="detail-header">
            <h1>Detail Tim</h1>
            <p>Data biodata anggota yang diambil dari layanan mahasiswa UMKT.</p>
        </div>

        @forelse ($anggota as $item)
            @php($biodata = $details[$item->id] ?? [])
            <section class="member-detail" id="anggota-{{ $item->id }}">
                <div class="member-heading">
                    <h2>{{ $item->nama }}</h2>
                    <span class="member-nim">NIM {{ $item->Nim }}</span>
                </div>

                @if (!empty($biodata['error']))
                    <p class="detail-error">{{ $biodata['error'] }}</p>
                @else
                    <div class="detail-grid">
                        <div class="detail-field">
                            <span class="detail-label">Nama</span>
                            <span class="detail-value">{{ $biodata['nama'] ?? $item->nama }}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-label">Umur</span>
                            <span class="detail-value">{{ $biodata['umur'] ?? '-' }}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $biodata['email'] ?? '-' }}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-label">Ponsel</span>
                            <span class="detail-value">{{ $biodata['ponsel'] ?? '-' }}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-label">Nama Ayah</span>
                            <span class="detail-value">{{ $biodata['namaAyah'] ?? '-' }}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-label">Tanggal Lahir</span>
                            <span class="detail-value">{{ $biodata['tanggalLahir'] ?? '-' }}</span>
                        </div>
                    </div>
                @endif
            </section>
        @empty
            <div class="member-detail">
                <p class="detail-error">Belum ada anggota di Management Tim.</p>
            </div>
        @endforelse
    </div>
@endsection
