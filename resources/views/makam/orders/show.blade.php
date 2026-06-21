@extends('makam.layout')
@section('title', 'Detail Pesanan')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Detail Pesanan</h2>
            <p class="text-muted mb-0">Informasi lengkap pesanan makam</p>
        </div>
        <a href="{{ route('makam.orders.index') }}" class="btn btn-makam-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-soft py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-receipt"></i> Informasi Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Pesanan</label>
                            <p class="fw-semibold mb-0" style="font-family: monospace; font-size: 1.1rem;">
                                {{ $order->kode_pesanan }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @php
                                    $badgeClass = match ($order->status) {
                                        'baru' => 'bg-primary',
                                        'diproses' => 'bg-warning',
                                        'selesai' => 'bg-success',
                                        'dibatalkan' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Jenis Makam</label>
                            <p class="fw-semibold mb-0">{{ $order->makamType->nama ?? '-' }}</p>
                            @if ($order->makamType && $order->makamType->blok)
                                <small class="text-muted">Blok: {{ $order->makamType->blok }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Jumlah</label>
                            <p class="fw-semibold mb-0">{{ $order->jumlah }} unit</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Harga Satuan</label>
                            <p class="fw-semibold mb-0">Rp {{ number_format($order->makamType->harga ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Total Harga</label>
                            <p class="fw-bold mb-0" style="font-size: 1.2rem; color: var(--accent);">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Tanggal Pesan</label>
                            <p class="mb-0">{{ $order->created_at->format('d F Y H:i') }}</p>
                        </div>
                        @if ($order->catatan)
                            <div class="col-12">
                                <label class="text-muted small">Catatan</label>
                                <p class="mb-0">{{ $order->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-soft py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person"></i> Data Customer
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small">Nama</label>
                        <p class="fw-semibold mb-0">{{ $order->nama_customer }}</p>
                    </div>
                    @if ($order->email_customer)
                        <div class="mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">{{ $order->email_customer }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="text-muted small">No. WhatsApp</label>
                        <p class="mb-0">
                            <a href="https://wa.me/{{ $order->no_wa_customer }}" target="_blank"
                                style="color: var(--accent);">
                                <i class="bi bi-whatsapp"></i> {{ $order->no_wa_customer }}
                            </a>
                        </p>
                    </div>
                    @if ($order->alamat_customer)
                        <div class="mb-3">
                            <label class="text-muted small">Alamat</label>
                            <p class="mb-0">{{ $order->alamat_customer }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-soft py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-gear"></i> Update Status
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('makam.orders.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="baru" {{ $order->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-makam w-100">
                            <i class="bi bi-arrow-repeat"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
