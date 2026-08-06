@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Management Beranda</h2>
                <p class="text-muted mb-0">Kelola foto hero dan anggota tim di halaman beranda</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Hero Images Management -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">
                                Foto Hero
                            </h5>
                            <a href="{{ route('admin.beranda.edit-hero') }}" class="btn btn-sm btn-brand">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>

                        <div class="mt-4">
                            <div class="mb-4">
                                <p class="text-muted small mb-2">Foto Utama (Main)</p>
                                @if ($heroImages->get('main'))
                                    <img src="{{ $heroImages->get('main')->image_url }}"
                                        alt="{{ $heroImages->get('main')->alt_text }}" class="img-fluid rounded"
                                        style="max-height: 250px; width: 100%; object-fit: cover;">
                                    <small class="text-muted d-block mt-2">
                                        {{ $heroImages->get('main')->alt_text ?? 'Tidak ada deskripsi' }}
                                    </small>
                                @else
                                    <div class="alert alert-warning mb-0">Belum ada foto utama</div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4 mb-0">
            <i class="bi bi-people me-2"></i>
            Kelola anggota tim (data diri, token absen, tampilan beranda, kas) di
            <a href="{{ route('admin.tim.index') }}" class="fw-semibold">Management Tim</a>.
        </div>
    </div>
@endsection
