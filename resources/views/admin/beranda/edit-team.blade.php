@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Kelola Anggota Tim</h2>
                <p class="text-muted mb-0">Tambah, edit, atau hapus anggota tim di beranda</p>
            </div>
            <a href="{{ route('admin.beranda.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Tambah Anggota -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3">
                    <i class="bi bi-plus-circle"></i> Tambah Anggota Tim Baru
                </h5>

                <form action="{{ route('admin.beranda.store-team') }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Posisi/Role</label>
                            <input type="text" class="form-control @error('role') is-invalid @enderror" name="role"
                                value="{{ old('role') }}" placeholder="contoh: Frontend Developer" required>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" class="form-control @error('photo_image') is-invalid @enderror"
                                name="photo_image" accept="image/*">
                            <small class="text-muted">Max 5MB (JPG, PNG, GIF, WebP)</small>
                            @error('photo_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Atau URL Foto</label>
                            <input type="url" class="form-control @error('photo_image_url') is-invalid @enderror"
                                name="photo_image_url" value="{{ old('photo_image_url') }}"
                                placeholder="https://example.com/image.jpg">
                            @error('photo_image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Urutan (Order)</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" name="order"
                                value="{{ old('order', $teamMembers->max('order') + 1 ?? 0) }}" min="0" required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nomor urutan tampilan (0 akan muncul paling awal)</small>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-brand">
                                <i class="bi bi-plus-circle"></i> Tambah Anggota
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Anggota Tim -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3">
                    <i class="bi bi-people"></i> Daftar Anggota Tim
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Order</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teamMembers as $member)
                                <tr>
                                    <td>
                                        <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td class="fw-medium">{{ $member->name }}</td>
                                    <td>{{ $member->role }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $member->order }}</span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $member->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.beranda.destroy-team', $member->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $member->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit {{ $member->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.beranda.update-team', $member->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $member->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Posisi</label>
                                                        <input type="text" class="form-control" name="role"
                                                            value="{{ $member->role }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Upload Foto Baru</label>
                                                        <input type="file" class="form-control" name="photo_image"
                                                            accept="image/*">
                                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah
                                                            foto</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Atau URL Foto</label>
                                                        <input type="url" class="form-control" name="photo_image_url"
                                                            value="{{ old('photo_image_url', $member->image_url) }}"
                                                            placeholder="https://example.com/image.jpg">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Order</label>
                                                        <input type="number" class="form-control" name="order"
                                                            value="{{ $member->order }}" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-brand">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox"></i><br>
                                        Belum ada anggota tim
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
