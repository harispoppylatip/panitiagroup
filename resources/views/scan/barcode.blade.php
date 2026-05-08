@extends('layout.master')
@section('konten')
    <style>
        .scan-page .scan-topbar {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(18, 38, 63, 0.1);
            border-radius: 14px;
            backdrop-filter: blur(8px);
        }

        .scan-page .scan-title {
            font-weight: 800;
            color: #12263f;
        }

        .scan-page .card {
            border: 1px solid rgba(18, 38, 63, 0.1);
            border-radius: 16px;
            box-shadow: 0 14px 30px rgba(18, 38, 63, 0.09);
        }

        .scan-page .btn-brand {
            background: linear-gradient(135deg, #2e5b87, #1f3b5c);
            border: none;
            color: #fff;
        }

        .scan-page .btn-brand:hover {
            background: linear-gradient(135deg, #1f3b5c, #12263f);
            color: #fff;
        }
    </style>

    <!-- MAIN APP -->
    <div id="app" class="scan-page d-none">
        <!-- TOPBAR -->
        <nav class="scan-topbar mb-4 px-3 py-2">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <span class="scan-title mb-0 h5">Scan Absensi</span>
                <a href="/sesi/logout" type="button" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </nav>

        <div class="container-fluid mb-4">
            <div class="row g-4">
                <!-- Scanner -->
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title mb-3">📷 Scan Absensi</h5>

                            <label class="form-label">Pilih Kamera</label>
                            <select id="cameraSelect" class="form-select mb-3"></select>

                            <div class="d-grid gap-2 d-md-flex mb-3">
                                <button id="startBtn" class="btn btn-brand">Nyalakan</button>
                                <button id="stopBtn" class="btn btn-danger" disabled>⏹️ Stop</button>
                            </div>

                            <video id="video" class="w-100 rounded mb-3"
                                style="aspect-ratio: 16/9; object-fit: cover; display: none;" autoplay muted
                                playsinline></video>
                            <canvas id="canvas" style="display:none"></canvas>

                            <div id="status" class="mb-3">
                                Status: <span class="badge bg-info">Menunggu…</span>
                            </div>

                            <h6 class="mt-4 mb-3">Response Presensi</h6>
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active tab" data-tab="summary" type="button">Ringkas</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab" data-tab="json" type="button">JSON</button>
                                </li>
                            </ul>

                            <div id="respSummary" class="tab-content"></div>
                            <div id="respJson" class="tab-content" style="display:none;">
                                <pre class="bg-light p-3 rounded small"><code>-</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User List -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title mb-3">👥 User</h5>

                            <div class="btn-group w-100 mb-3" role="group">
                                <button id="reloadBtn" class="btn btn-sm btn-outline-secondary">🔄 Muat Ulang</button>
                                <button id="selectAllBtn" class="btn btn-sm btn-outline-success">✅ On</button>
                                <button id="unselectAllBtn" class="btn btn-sm btn-outline-danger">🚫 Off</button>
                            </div>

                            <div id="userList" class="list-group" style="max-height: 500px; overflow-y: auto;">
                                <div class="list-group-item">Memuat data...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        ;
        (() => {
            /**
             * 📸 ALUR BARCODE SCANNER
             * =====================================================
             * 1. User klik "Nyalakan" → startScan() membuka camera
             * 2. Loop pembacaan frame → loop() scan QR di setiap frame
             * 3. QR terdeteksi → handleDecoded() parsing & kirim ke n8n
             * 4. Response diterima → renderN8NResponse() tampilkan hasil
             * =====================================================
             */

            const app = document.getElementById('app');
            const logoutBtn = document.getElementById('logoutBtn');

            // Show app (auth already checked via middleware)
            app.classList.remove('d-none');

            // 🔗 API ENDPOINTS LOKAL
            const LIST_URL = "{{ route('scan.users') }}";
            const UPDATE_URL_BASE = "{{ url('/scan/users') }}";
            const SCAN_SUBMIT_URL = "{{ route('scan.submit') }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d', {
                willReadFrequently: true
            });
            const cameraSelect = document.getElementById('cameraSelect');
            const startBtn = document.getElementById('startBtn');
            const stopBtn = document.getElementById('stopBtn');
            const statusEl = document.getElementById('status');
            const respSummary = document.getElementById('respSummary');
            const respJson = document.getElementById('respJson');
            const userList = document.getElementById('userList');
            const reloadBtn = document.getElementById('reloadBtn');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const unselectAllBtn = document.getElementById('unselectAllBtn');

            let stream = null,
                scanning = false,
                rafId = null,
                detector = null;
            const hasBarcodeDetector = 'BarcodeDetector' in window;

            function setStatus(html) {
                statusEl.innerHTML = 'Status: ' + html;
            }

            function syntaxJSON(obj) {
                const text = typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2);
                return text.replace(/(&|<|>)/g, (s) => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;'
                    } [s]))
                    .replace(/"(\\u[a-fA-F0-9]{4}|\\[^u]|[^"\\])*"(?=\\s*:)/g, '<span style="color:#d63384;">$&</span>')
                    .replace(/"(\\u[a-fA-F0-9]{4}|\\[^u]|[^"\\])*"/g, '<span style="color:#0dcaf0;">$&</span>')
                    .replace(/\\b(true|false|null)\\b/g, '<span style="color:#f768a1;">$1</span>')
                    .replace(/-?\\b\\d+(\\.\\d+)?\\b/g, '<span style="color:#ffc107;">$&</span>');
            }

            function showJSON(el, data) {
                el.innerHTML = '<pre class="bg-light p-3 rounded small"><code>' + syntaxJSON(data) + '</code></pre>';
            }

            function beep() {
                try {
                    new Audio('data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAESsAACJWAAACABYAAAABAAACAgAA')
                        .play();
                } catch {}
            }

            function vibrate() {
                if (navigator.vibrate) navigator.vibrate(80);
            }

            function tryParseJSON(text) {
                try {
                    return JSON.parse(text);
                } catch {
                    return null;
                }
            }

            function setActiveTab(which) {
                document.querySelectorAll('.nav-link.tab').forEach((btn) => {
                    btn.classList.toggle('active', btn.dataset.tab === which);
                });
                respSummary.style.display = (which === 'summary') ? '' : 'none';
                respJson.style.display = (which === 'json') ? '' : 'none';
            }
            document.addEventListener('click', (e) => {
                const tab = e.target.closest('.tab');
                if (!tab) return;
                setActiveTab(tab.dataset.tab);
            });

            function isSuccessStatus(txt) {
                return /berhasil/i.test(String(txt || ''));
            }

            function extractErrorText(errObj) {
                if (!errObj || typeof errObj !== 'object') return '';
                const parts = [];
                Object.keys(errObj).forEach((k) => {
                    const v = errObj[k];
                    if (Array.isArray(v) && v.length) {
                        parts.push(v[0]);
                    } else if (typeof v === 'string' && v) {
                        parts.push(v);
                    }
                });
                return parts.join(' | ');
            }

            function normalizeKeys(obj) {
                const out = {};
                for (const k in (obj || {})) {
                    const nk = k.replace(/^[^a-zA-Z0-9]+|[^a-zA-Z0-9_]+$/g, '');
                    out[nk || k] = obj[k];
                }
                return out;
            }

            // ========== FUNGSI 4: MENAMPILKAN HASIL DATA RESPONSE ==========
            function renderN8NResponse(data) {
                // Tampilkan JSON format di tab JSON
                showJSON(respJson, data);
                respSummary.innerHTML = '';

                // Parse data response menjadi array items
                let items = [];
                if (Array.isArray(data)) {
                    items = data;
                } else if (data && typeof data === 'object' && Array.isArray(data.results)) {
                    items = data.results;
                } else if (data && typeof data === 'object') {
                    items = [data];
                } else {
                    respSummary.innerHTML = `<div class="alert alert-info mb-0">ℹ️ ${String(data)}</div>`;
                    return;
                }

                const summaryMessage = data && typeof data === 'object' && data.summary && data.summary.message ?
                    String(data.summary.message) :
                    '';
                const topLevelMessage = data && typeof data === 'object' && typeof data.message === 'string' ?
                    data.message :
                    '';

                // Hitung jumlah sukses dan gagal
                let ok = 0,
                    err = 0;
                const hasSummary = data && typeof data === 'object' && data.summary;
                if (hasSummary) {
                    ok = Number(data.summary.success || 0);
                    err = Number(data.summary.failed || 0);
                }
                let html =
                    '<div class="row g-2 mb-3"><div class="col-6"><div class="alert alert-success mb-0">✓ Berhasil: <strong id="okCount">0</strong></div></div><div class="col-6"><div class="alert alert-danger mb-0">✗ Gagal: <strong id="errCount">0</strong></div></div></div>';

                // 📊 RENDER: Buat list untuk setiap item/user yang diproses
                items.forEach((raw) => {
                    const item = normalizeKeys(raw);
                    const nama = item.nama || item.user || item.Nim || 'Tanpa nama';
                    const status = item.api_message || item.status || item.message || extractErrorText(item
                            .errors) ||
                        'Tidak ada detail';
                    const success = typeof item.success === 'boolean' ? item.success : isSuccessStatus(item
                        .status ||
                        status);

                    // Hitung statistik
                    if (!hasSummary) {
                        if (success) ok++;
                        else err++;
                    }

                    // Buat row untuk menampilkan hasil per user
                    html +=
                        `<div class="list-group-item"><div class="d-flex gap-2"><span>${success ? '✅' : '❌'}</span><div style="flex:1"><strong>${nama}</strong><br><small class="text-muted">${status}</small></div><span class="badge ${success ? 'bg-success' : 'bg-danger'}">${success ? 'BERHASIL' : 'GAGAL'}</span></div></div>`;
                });

                // Update UI dengan hasil akhir
                respSummary.innerHTML = html;
                if (!items.length) {
                    const emptyMessage = summaryMessage || topLevelMessage || 'Tidak ada detail respons dari server.';
                    respSummary.innerHTML = `<div class="alert alert-info mb-3">ℹ️ ${emptyMessage}</div>` + respSummary
                        .innerHTML;
                }
                if (document.getElementById('okCount')) {
                    document.getElementById('okCount').textContent = ok;
                    document.getElementById('errCount').textContent = err;
                }
            }

            async function requestFirstStreamForLabels() {
                try {
                    const tmp = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    tmp.getTracks().forEach(t => t.stop());
                } catch {}
            }
            async function listCameras() {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const cams = devices.filter(d => d.kind === 'videoinput');
                cameraSelect.innerHTML = '';
                cams.forEach((c, i) => {
                    const o = document.createElement('option');
                    o.value = c.deviceId;
                    o.textContent = c.label || `Kamera ${i+1}`;
                    cameraSelect.appendChild(o);
                });
                const back = cams.find(c => /back|rear|belakang/i.test(c.label));
                if (back) cameraSelect.value = back.deviceId;
            }
            // ========== FUNGSI 1: MEMULAI SCANNING CAMERA ==========
            async function startScan() {
                setStatus('<span class="badge bg-info">Meminta izin kamera…</span>');

                // Gunakan BarcodeDetector API jika tersedia (lebih akurat untuk QR)
                if (hasBarcodeDetector) {
                    try {
                        detector = new BarcodeDetector({
                            formats: ['qr_code']
                        });
                    } catch {
                        detector = new BarcodeDetector();
                    }
                }

                // Dapatkan akses ke camera device yang dipilih
                const deviceId = cameraSelect.value || undefined;
                stream = await navigator.mediaDevices.getUserMedia({
                    video: deviceId ? {
                        deviceId: {
                            exact: deviceId
                        }
                    } : {
                        facingMode: 'environment'
                    }
                });

                // Tampilkan video stream di element <video>
                video.srcObject = stream;
                video.style.display = 'block';
                await video.play();

                // Set flag scanning dan update UI
                scanning = true;
                startBtn.disabled = true;
                stopBtn.disabled = false;
                setStatus('<span class="badge bg-info">Arahkan kamera ke QR…</span>');

                // Mulai loop pembacaan frame
                loop();
            }

            function stopScan() {
                scanning = false;
                startBtn.disabled = false;
                stopBtn.disabled = true;
                if (rafId) cancelAnimationFrame(rafId);
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                video.style.display = 'none';
                setStatus('<span class="badge bg-secondary">Berhenti</span>');
            }
            // ========== FUNGSI 2: LOOP PEMBACAAN FRAME CAMERA ==========
            async function loop() {
                if (!scanning) return;

                // Tunggu sampai video siap untuk dibaca
                if (video.readyState >= 2) {
                    // Siapkan canvas dengan ukuran sama dengan video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Gambar frame video ke canvas untuk diproses
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    try {
                        let decoded = null;

                        // PILIHAN 1: Gunakan BarcodeDetector API (native & akurat)
                        if (detector) {
                            const bmp = await createImageBitmap(canvas);
                            const codes = await detector.detect(bmp);
                            if (codes && codes.length) decoded = codes[0].rawValue;
                        }
                        // PILIHAN 2: Gunakan library jsQR (fallback)
                        else {
                            const img = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const qr = jsQR(img.data, canvas.width, canvas.height);
                            if (qr) decoded = qr.data;
                        }

                        // Jika barcode/QR terdeteksi, proses data
                        if (decoded) {
                            handleDecoded(decoded);
                            stopScan();
                        }
                    } catch {}
                }

                // Lanjut scanning di frame berikutnya
                rafId = requestAnimationFrame(loop);
            }
            // ========== FUNGSI 3: MEMPROSES DATA BARCODE YANG TERDETEKSI ==========
            async function handleDecoded(text) {
                // Beri feedback audio & vibration ke user
                beep();
                vibrate();

                // Coba parse qr text sebagai JSON
                const json = tryParseJSON(text);
                if (!json) {
                    setStatus('<span class="badge bg-danger">QR bukan JSON</span>');
                    renderN8NResponse('QR bukan JSON');
                    return;
                }

                // Status: sedang mengirim ke server
                setStatus('<span class="badge bg-info">Mengirim presensi…</span>');

                try {
                    // 📤 SEND: Kirim data JSON ke endpoint Laravel
                    const res = await fetch(SCAN_SUBMIT_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                        body: JSON.stringify(json)
                    });

                    // 📥 RECEIVE: Terima response dari server
                    const t = await res.text();
                    const j = tryParseJSON(t) ?? t;

                    // Update status berdasarkan response server
                    setStatus(res.ok ? '<span class="badge bg-success">Terkirim (' + res.status + ')</span>' :
                        '<span class="badge bg-danger">Ditolak (' + res.status + ')</span>');

                    // Tampilkan hasil response di UI
                    renderN8NResponse(j);
                    setActiveTab('summary');
                } catch (e) {
                    setStatus('<span class="badge bg-danger">Gagal kirim</span>');
                    renderN8NResponse(String(e));
                    setActiveTab('summary');
                }
            }

            async function loadUsers() {
                userList.innerHTML = '<div class="list-group-item">Memuat data...</div>';
                try {
                    const res = await fetch(LIST_URL);
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const users = await res.json();
                    renderUsers(Array.isArray(users) ? users : []);
                } catch (e) {
                    userList.innerHTML = '<div class="list-group-item text-danger">Gagal load user: ' + e +
                        '</div>';
                }
            }

            function renderUsers(users) {
                userList.innerHTML = '';
                if (!users.length) {
                    userList.innerHTML = '<div class="list-group-item text-muted">(Kosong)</div>';
                    return;
                }
                users.forEach(u => {
                    const row = document.createElement('div');
                    row.className = 'list-group-item';
                    const checked = u.status_onoff === 'on';
                    row.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>${u.nama||'(tanpa nama)'}</strong>
            <br>
                            <small class="text-muted">NIM ${u.Nim||'-'} • id=${u.id}</small>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input user-toggle" type="checkbox" data-uid="${u.id}" ${checked?'checked':''}>
          </div>
        </div>
      `;
                    userList.appendChild(row);
                });
                document.querySelectorAll('.user-toggle').forEach(input => {
                    input.addEventListener('change', () => updateUser(input.dataset.uid, input.checked ? 'on' :
                        'off',
                        input));
                });
            }
            async function updateUser(id, status, inputEl) {
                try {
                    const r = await fetch(`${UPDATE_URL_BASE}/${id}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                        body: JSON.stringify({
                            id,
                            status
                        })
                    });
                    if (!r.ok) {
                        inputEl.checked = !inputEl.checked;
                        alert('Gagal update (' + r.status + ')');
                    }
                } catch (e) {
                    inputEl.checked = !inputEl.checked;
                    alert('Gagal update: ' + e);
                }
            }
            async function bulkSet(status) {
                const boxes = userList.querySelectorAll('input[type="checkbox"]');
                const tasks = [];
                boxes.forEach(b => {
                    if ((status === 'on' && !b.checked) || (status === 'off' && b.checked)) {
                        b.checked = (status === 'on');
                        tasks.push(fetch(`${UPDATE_URL_BASE}/${b.dataset.uid}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({
                                id: Number(b.dataset.uid),
                                status
                            })
                        }));
                    }
                });
                if (tasks.length) {
                    try {
                        await Promise.all(tasks);
                    } catch (e) {
                        alert('Sebagian update gagal: ' + e);
                    }
                }
            }

            async function initApp() {
                renderN8NResponse([{
                    nama: '—',
                    status: 'Belum ada respons'
                }]);
                setActiveTab('summary');
                await requestFirstStreamForLabels();
                await listCameras();
                await loadUsers();
            }

            document.getElementById('cameraSelect').addEventListener('change', () => {
                if (stream) {
                    stopScan();
                    startScan();
                }
            });
            document.getElementById('startBtn').addEventListener('click', startScan);
            document.getElementById('stopBtn').addEventListener('click', stopScan);
            document.getElementById('reloadBtn').addEventListener('click', loadUsers);
            document.getElementById('selectAllBtn').addEventListener('click', () => bulkSet('on'));
            document.getElementById('unselectAllBtn').addEventListener('click', () => bulkSet('off'));

            // Initialize app
            initApp();
        })();
    </script>
@endsection
