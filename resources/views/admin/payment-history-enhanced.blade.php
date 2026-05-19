@extends('layout.masteradmin')
@section('konten')
    <div class="container payment-history-dashboard">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">History Pembayaran Terverifikasi</h2>
                <p class="text-muted mb-0">Riwayat pembayaran yang telah dikonfirmasi dan diverifikasi. Klik tombol "Lihat Bukti" untuk preview gambar.</p>
            </div>
            <a href="{{ route('admin.payment.verification.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Verifikasi
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($paidPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="bg-light border-bottom">
                                    <th style="width: 50px;">No.</th>
                                    <th style="width: 160px;">Nama / NIM</th>
                                    <th style="width: 120px; text-align: center;">Nominal</th>
                                    <th style="width: 150px;">Tanggal Pembayaran</th>
                                    <th style="width: 100px;">Order ID</th>
                                    <th style="width: 120px; text-align: center;">Bukti</th>
                                    <th style="width: 200px;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paidPayments as $index => $payment)
                                    <tr class="border-bottom">
                                        <td>
                                            <span class="badge bg-primary">{{ $paidPayments->total() - (($paidPayments->currentPage() - 1) * $paidPayments->perPage()) - $loop->index }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $payment->user_name ?? 'Non-Anggota' }}</div>
                                            @if ($payment->user_nim)
                                                <small class="text-muted">NIM: {{ $payment->user_nim }}</small>
                                            @else
                                                <small class="badge bg-secondary">Manual</small>
                                            @endif
                                        </td>
                                        <td class="text-center text-success fw-bold">
                                            Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <small>
                                                {{ $payment->occurred_at?->format('d M Y') ?? '-' }}
                                                <br>
                                                <span class="text-muted">{{ $payment->occurred_at?->format('H:i') ?? '-' }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <code style="font-size: 0.75rem;">{{ substr($payment->order_id, 0, 12) }}...</code>
                                        </td>
                                        <td class="text-center">
                                            @if ($payment->proof_path)
                                                <button class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#proofModal{{ $payment->id }}"
                                                        title="Preview bukti pembayaran">
                                                    <i class="bi bi-file-image"></i> Lihat
                                                </button>
                                            @else
                                                <span class="badge bg-secondary">Tanpa Bukti</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ substr($payment->description ?? $payment->title ?? '-', 0, 50) }}{{ strlen($payment->description ?? $payment->title ?? '-') > 50 ? '...' : '' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Belum ada pembayaran yang terverifikasi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($paidPayments->hasPages())
                        <div class="mt-4">
                            {{ $paidPayments->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada pembayaran yang terverifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Preview Bukti untuk setiap pembayaran -->
    @foreach ($paidPayments as $payment)
        @if ($payment->proof_path)
            <div class="modal fade" id="proofModal{{ $payment->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">Bukti Pembayaran</h5>
                                <small class="text-muted">{{ $payment->user_name ?? 'Non-Anggota' }} - {{ $payment->occurred_at?->format('d M Y H:i') }}</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <img src="{{ Storage::url($payment->proof_path) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="img-fluid rounded" 
                                     style="max-height: 600px; object-fit: contain;">
                            </div>
                            <div class="alert alert-info">
                                <strong>Detail Pembayaran:</strong>
                                <table class="table table-sm mt-2 mb-0">
                                    <tr>
                                        <td class="text-start">Order ID</td>
                                        <td class="text-end"><code>{{ $payment->order_id }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Nama Anggota</td>
                                        <td class="text-end">{{ $payment->user_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Nominal</td>
                                        <td class="text-end"><strong class="text-success">Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Tanggal Verifikasi</td>
                                        <td class="text-end">{{ $payment->occurred_at?->format('d M Y H:i') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">File Bukti</td>
                                        <td class="text-end">{{ $payment->proof_name ?? basename($payment->proof_path) }}</td>
                                    </tr>
                                    @if ($payment->description)
                                        <tr>
                                            <td class="text-start">Keterangan</td>
                                            <td class="text-end">{{ $payment->description }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ Storage::url($payment->proof_path) }}" 
                               download="{{ $payment->proof_name ?? 'bukti.png' }}"
                               class="btn btn-outline-primary">
                                <i class="bi bi-download"></i> Download Bukti
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
