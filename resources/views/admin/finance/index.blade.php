@extends('layout.masteradmin')
@section('konten')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Management Uang Kas</h2>
                <p class="text-muted mb-0">Kelola iuran mingguan, utang/saldo positif anggota, dan pengeluaran manual.</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Pengaturan Iuran Mingguan</h5>
                        <form action="{{ route('admin.finance.weekly-fee') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label for="weekly_fee" class="form-label">Nominal Iuran per Minggu</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" min="0" class="form-control" id="weekly_fee"
                                        name="weekly_fee" value="{{ old('weekly_fee', $setting->weekly_fee) }}" required>
                                </div>
                                <small class="text-muted">Perubahan ini langsung dipakai di halaman Kas Grub dan command
                                    mingguan.</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Nominal Iuran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Input Pengeluaran Manual</h5>
                        <form action="{{ route('admin.finance.expense.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label for="amount" class="form-label">Nominal Pengeluaran</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" min="1" class="form-control" id="amount" name="amount"
                                        value="{{ old('amount') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Keterangan Pengeluaran</label>
                                <textarea id="description" name="description" class="form-control" rows="3" required
                                    placeholder="Contoh: Makan bersama Rp 150.000">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger">Catat Pengeluaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Kelola Utang dan Saldo Positif Anggota</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Utang Saat Ini</th>
                                <th>Saldo Positif</th>
                                <th>Keterangan</th>
                                <th style="min-width: 280px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($members as $member)
                                @php
                                    $latest = $member->latestIuran;
                                @endphp
                                <tr>
                                    <td>{{ $member->nama }}</td>
                                    <td>{{ $member->Nim }}</td>
                                    <td>Rp {{ number_format((int) ($latest->Nominal ?? 0), 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format((int) ($latest->Saldo_Lebih ?? 0), 0, ',', '.') }}</td>
                                    <td>{{ $latest->Keterangan ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.finance.member.update', $member->Nim) }}"
                                            method="POST" class="row g-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-4">
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    name="nominal" value="{{ $latest->Nominal ?? 0 }}" placeholder="Utang"
                                                    required>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" min="0" class="form-control form-control-sm"
                                                    name="saldo_lebih" value="{{ $latest->Saldo_Lebih ?? 0 }}"
                                                    placeholder="Saldo" required>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-primary w-100">Update</button>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" class="form-control form-control-sm" name="keterangan"
                                                    value="{{ $latest->Keterangan ?? 'Penyesuaian manual oleh admin' }}"
                                                    placeholder="Keterangan perubahan (opsional)">
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Data anggota belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Riwayat Pengeluaran Terbaru</h5>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentExpenses as $expense)
                                <tr>
                                    <td>{{ optional($expense->occurred_at)->format('d-m-Y H:i') }}</td>
                                    <td>Rp {{ number_format((int) $expense->amount, 0, ',', '.') }}</td>
                                    <td>{{ $expense->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada pengeluaran manual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
