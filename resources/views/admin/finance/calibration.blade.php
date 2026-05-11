@extends('layout.masteradmin')
@section('konten')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Alert Danger -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start gap-3">
                        <div>
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading fw-bold mb-2">⚠️ PERINGATAN - OPERASI SENSITIF</h5>
                            <p class="mb-1">Anda akan melakukan <strong>KALIBRASI/RESET LENGKAP DATA GRUBKAS</strong>.</p>
                            <p class="mb-0">Operasi ini <strong>TIDAK DAPAT DIBATALKAN</strong> dan akan menghapus semua
                                data transaksi dan iuran member.</p>
                        </div>
                    </div>
                </div>

                <!-- Card Konfirmasi -->
                <div class="card border-danger shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Konfirmasi Kalibrasi Data Grubkas</h5>
                    </div>
                    <div class="card-body">
                        <!-- Data yang akan dihapus -->
                        <div class="mb-4 p-3 bg-warning bg-opacity-10 rounded border border-warning">
                            <h6 class="fw-bold mb-3">📊 Data yang akan dihapus:</h6>
                            <ul class="mb-0">
                                <li><strong>{{ $totalActivityLogs }}</strong> log transaksi</li>
                                <li><strong>{{ $totalIuranRecords }}</strong> data iuran member</li>
                                <li>Semua riwayat pembayaran dan pengeluaran</li>
                            </ul>
                        </div>

                        <!-- Form Konfirmasi -->
                        <form action="{{ route('admin.finance.calibration.execute') }}" method="POST">
                            @csrf
                            @method('POST')

                            <!-- Step 1: Info -->
                            <div class="mb-4 p-3 bg-light rounded">
                                <h6 class="fw-bold mb-2">📌 Informasi Penting:</h6>
                                <small class="d-block text-muted mb-2">
                                    • Operasi ini akan membuat sistem grubkas benar-benar bersih
                                </small>
                                <small class="d-block text-muted mb-2">
                                    • Semua transaksi sebelumnya akan dihapus permanent
                                </small>
                                <small class="d-block text-muted">
                                    • Hanya Admin yang dapat melakukan operasi ini
                                </small>
                            </div>

                            <!-- Step 2: Confirmation Code -->
                            <div class="mb-4">
                                <label for="confirmation_code" class="form-label fw-bold">
                                    ✓ Masukan Kode Konfirmasi
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="text" class="form-control" id="confirmation_code"
                                        name="confirmation_code" placeholder="Masukan kode yang ditampilkan di bawah"
                                        required>
                                </div>
                                <small class="d-block mt-2 p-2 bg-primary bg-opacity-10 rounded text-primary fw-bold">
                                    💡 Kode Konfirmasi: <code>RESET-{{ now()->format('Ymd') }}</code>
                                </small>
                                @error('confirmation_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Step 3: Checkbox Acknowledgement -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmation_checkbox"
                                        name="confirmation_checkbox" value="on" required>
                                    <label class="form-check-label" for="confirmation_checkbox">
                                        ✓ Saya memahami dan setuju bahwa semua data akan <strong>DIHAPUS PERMANENT</strong>
                                        dan operasi ini <strong>TIDAK DAPAT DIBATALKAN</strong>
                                    </label>
                                </div>
                                @error('confirmation_checkbox')
                                    <small class="d-block text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <strong>❌ Ada kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg fw-bold"
                                    onclick="return confirm('Anda BENAR-BENAR yakin ingin menghapus semua data grubkas? Ini TIDAK DAPAT DIBATALKAN!')">
                                    <i class="bi bi-trash"></i> LANJUTKAN KALIBRASI
                                </button>
                                <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-lg fw-bold">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>

                            <!-- Warning Footer -->
                            <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded border border-danger">
                                <small class="text-danger fw-bold d-block">
                                    ⚠️ PERHATIAN: Jangan refresh halaman setelah klik "Lanjutkan Kalibrasi"
                                </small>
                                <small class="text-muted d-block mt-1">
                                    Operasi akan disimpan dalam log sistem untuk audit trail.
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-4">
                    <a href="{{ route('admin.finance.index') }}" class="text-muted small">
                        ← Kembali ke Management Keuangan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-width: 2px;
        }

        .alert {
            border-width: 2px;
        }

        code {
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        body[data-theme='dark'] code {
            background: #333;
            color: #ffeb3b;
        }

        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endsection
