@extends('layout.masteradmin')
@section('konten')
    <div class="container payment-history-dashboard">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">History Pembayaran Terverifikasi</h2>
                <p class="text-muted mb-0">Riwayat pembayaran yang telah dikonfirmasi dan diverifikasi.</p>
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
                                    <th style="width: 80px;">No.</th>
                                    <th style="width: 150px;">Nama / NIM</th>
                                    <th style="width: 100px; text-align: center;">Nominal</th>
                                    <th style="width: 140px;">Tanggal Verifikasi</th>
                                    <th style="width: 100px; text-align: center;">Bukti</th>
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
                                                <small class="text-muted text-secondary">Manual Entry</small>
                                            @endif
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <small>
                                                {{ $payment->occurred_at?->format('d M Y') ?? '-' }}
                                                <br>
                                                <span class="text-muted">{{ $payment->occurred_at?->format('H:i') ?? '-' }}</span>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            @if ($payment->proof_path)
                                                <a href="{{ route('admin.payment.detail', $payment->id) }}" 
                                                   class="btn btn-sm btn-outline-info" title="Lihat detail pembayaran">
                                                    <i class="bi bi-file-image"></i> Lihat
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Tanpa Bukti</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $payment->description ?? $payment->title ?? '-' }}</small>
                                        </td>
                                    </tr>

                                    <!-- Detail Link (removed modal, using detail page instead) -->
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
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
@endsection
