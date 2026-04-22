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
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">
                                <i class="bi bi-image"></i> Foto Hero
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

                            <div class="row g-2">
                                <div class="col-6">
                                    <p class="text-muted small mb-2">Foto Samping 1</p>
                                    @if ($heroImages->get('side1'))
                                        <img src="{{ $heroImages->get('side1')->image_url }}"
                                            alt="{{ $heroImages->get('side1')->alt_text }}" class="img-fluid rounded"
                                            style="max-height: 150px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="alert alert-warning mb-0">Belum ada</div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-2">Foto Samping 2</p>
                                    @if ($heroImages->get('side2'))
                                        <img src="{{ $heroImages->get('side2')->image_url }}"
                                            alt="{{ $heroImages->get('side2')->alt_text }}" class="img-fluid rounded"
                                            style="max-height: 150px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="alert alert-warning mb-0">Belum ada</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members Summary -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">
                                <i class="bi bi-people"></i> Anggota Tim ({{ $teamMembers->count() }})
                            </h5>
                            <a href="{{ route('admin.beranda.edit-team') }}" class="btn btn-sm btn-brand">
                                <i class="bi bi-pencil"></i> Kelola
                            </a>
                        </div>

                        <div class="list-group list-group-flush mt-3">
                            @forelse ($teamMembers as $member)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex gap-3">
                                        <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-medium">{{ $member->name }}</h6>
                                            <small class="text-muted">{{ $member->role }}</small>
                                        </div>
                                        <small class="text-muted">Order: {{ $member->order }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">Belum ada anggota tim</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
