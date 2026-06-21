@extends('makam.layout')
@section('title', 'Pesanan Makam')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Management Pesanan</h2>
            <p class="text-muted mb-0">Lihat dan kelola pesanan makam dari customer</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light" style="background: var(--surface);">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Kode Pesanan</th>
                                <th>Customer</th>
                                <th>Jenis Makam</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $orders->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-semibold" style="font-family: monospace; font-size: 0.85rem;">
                                            {{ $order->kode_pesanan }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $order->nama_customer }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-whatsapp"></i> {{ $order->no_wa_customer }}
                                        </small>
                                    </td>
                                    <td>{{ $order->makamType->nama ?? '-' }}</td>
                                    <td>{{ $order->jumlah }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($order->status) {
                                                'baru' => 'bg-primary',
                                                'diproses' => 'bg-warning',
                                                'selesai' => 'bg-success',
                                                'dibatalkan' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} bg-opacity-10 text-white"
                                            style="color: var(--bs-{{ $order->status === 'baru' ? 'primary' : ($order->status === 'diproses' ? 'warning' : ($order->status === 'selesai' ? 'success' : 'danger')) }}) !important;
                                            background: rgba(var(--bs-{{ $order->status === 'baru' ? 'primary' : ($order->status === 'diproses' ? 'warning' : ($order->status === 'selesai' ? 'success' : 'danger')) }}-rgb), 0.1) !important;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('makam.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-makam-outline me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('makam.orders.destroy', $order->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-top" style="border-color: var(--border-soft);">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                            {{ $orders->total() }} pesanan
                        </small>
                        <nav>
                            {{ $orders->links() }}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-cart" style="font-size: 3.5rem; color: var(--text-muted);"></i>
                    <h5 class="mt-3 fw-semibold" style="color: var(--text-main);">Belum Ada Pesanan</h5>
                    <p class="text-muted mb-0">Pesanan dari customer akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
@endsection
