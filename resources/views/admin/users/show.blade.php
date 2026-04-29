@extends('layout.masteradmin')

@section('konten')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Detail User</h2>
                <p class="text-muted mb-0">Informasi lengkap user</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Nama Lengkap</h6>
                        <p class="fw-medium">{{ $user->name }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Username</h6>
                        <p class="fw-medium"><code>{{ $user->username }}</code></p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Email</h6>
                        <p class="fw-medium">{{ $user->email }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Role</h6>
                        <p>
                            @if ($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif ($user->role === 'akuntan')
                                <span class="badge bg-warning text-dark">Akuntan</span>
                            @else
                                <span class="badge bg-info">Anggota</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Dibuat Pada</h6>
                        <p class="fw-medium">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Terakhir Update</h6>
                        <p class="fw-medium">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @if ($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
