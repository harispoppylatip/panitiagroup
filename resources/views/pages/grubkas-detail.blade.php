@extends('layout.master')
@section('head')
    <script type="text/javascript"
        src="{{ config('midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.Client_Key') }}"></script>
@endsection

@section('konten')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-4">
                                <h1 class="h3 fw-bold mb-2 text-dark">Detail Pembayaran Anggota Grub</h1>
                                <p class="text-muted mb-0">Atur nominal dan keterangan pembayaran sebelum melanjutkan proses
                                    bayar.</p>
                            </div>

                            <form method="POST" action="{{ url('/bayar') }}">
                                @csrf
                                <input type="hidden" name="nim" value="{{ $nimuser }}">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Anggota</label>
                                        <input type="text" class="form-control" value="{{ $namauser }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="custom_amount" class="form-label fw-semibold">Jumlah yang ingin
                                            dibayar</label>
                                        <input type="number" min="1" step="1" class="form-control"
                                            id="custom_amount" name="custom_amount"
                                            value="{{ old('custom_amount', $jumlah) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="custom_description" class="form-label fw-semibold">Keterangan</label>
                                        <input type="text" class="form-control" id="custom_description"
                                            name="custom_description"
                                            value="{{ old('custom_description', $keterangan ?? 'Pembayaran grubkas') }}"
                                            maxlength="255" required>
                                        <small class="text-muted d-block mt-2">Default: Pembayaran grubkas. Bisa diganti
                                            sesuai kebutuhan.</small>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-4 gap-2">
                                        <a href="{{ route('grubkas') }}" class="btn btn-outline-secondary">Kembali</a>
                                        <button type="submit" class="btn btn-brand px-4">Bayar Sekarang</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script type="text/javascript">
        @if (!empty($snapToken) && !empty($openSnapOnLoad))
            window.addEventListener('load', function() {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = '{{ route('grubkas') }}';
                    }
                });
            });
        @endif
    </script>
@endsection
