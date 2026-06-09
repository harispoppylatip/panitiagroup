@extends('layout.master')

@section('head')
    <title>Haris Motor Monitor | Pemuda Akhir Zaman</title>
    <style>
        .mqtt-page {
            position: relative;
            overflow: hidden;
            padding: 1.5rem 0 2.5rem;
        }

        .mqtt-orb {
            position: absolute;
            border-radius: 999px;
            filter: blur(6px);
            pointer-events: none;
            z-index: 0;
        }

        .mqtt-orb.one {
            width: 320px;
            height: 320px;
            top: -80px;
            right: -90px;
            background: radial-gradient(circle at center, rgba(46, 91, 135, 0.18), rgba(46, 91, 135, 0));
        }

        .mqtt-orb.two {
            width: 240px;
            height: 240px;
            bottom: 10px;
            left: -80px;
            background: radial-gradient(circle at center, rgba(195, 143, 60, 0.16), rgba(195, 143, 60, 0));
        }

        .mqtt-shell {
            position: relative;
            z-index: 1;
        }

        .mqtt-hero {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(244, 247, 251, 0.92));
            border: 1px solid var(--border-soft);
            border-radius: 28px;
            box-shadow: 0 26px 50px rgba(18, 38, 63, 0.12);
            padding: 1.5rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: rgba(18, 38, 63, 0.08);
            color: var(--brand-700);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        body[data-theme='dark'] .mqtt-page {
            background: linear-gradient(180deg, rgba(15, 23, 36, 0.16), rgba(15, 23, 36, 0));
        }

        body[data-theme='dark'] .mqtt-hero,
        body[data-theme='dark'] .stat-card,
        body[data-theme='dark'] .panel-card,
        body[data-theme='dark'] .meta-item,
        body[data-theme='dark'] .field-card,
        body[data-theme='dark'] .cell-card {
            background: rgba(17, 24, 39, 0.94);
            border-color: rgba(148, 163, 184, 0.18);
            box-shadow: 0 20px 36px rgba(0, 0, 0, 0.28);
        }

        body[data-theme='dark'] .mqtt-hero {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.96), rgba(15, 23, 36, 0.92));
        }

        body[data-theme='dark'] .eyebrow {
            background: rgba(148, 163, 184, 0.12);
            color: #dbeafe;
        }

        .mqtt-title {
            font-size: clamp(2rem, 3vw, 3.25rem);
            line-height: 1.08;
            letter-spacing: -0.03em;
            color: var(--text-main);
            margin-bottom: 0.75rem;
        }

        .mqtt-lead {
            color: var(--text-muted);
            max-width: 56ch;
            font-size: 1.03rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.48rem 0.85rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.1);
            color: #0f766e;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .status-chip.is-stale {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
        }

        .status-chip.is-empty {
            background: rgba(107, 114, 128, 0.12);
            color: #6b7280;
        }

        .stat-card,
        .panel-card {
            background: var(--surface-elevated);
            border: 1px solid var(--border-soft);
            border-radius: 22px;
            box-shadow: 0 18px 36px rgba(18, 38, 63, 0.08);
        }

        .stat-card {
            padding: 1rem 1.1rem;
            height: 100%;
        }

        .stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-weight: 800;
            color: var(--text-muted);
        }

        .stat-value {
            font-size: 1.8rem;
            line-height: 1.05;
            font-weight: 800;
            color: var(--text-main);
            margin-top: 0.35rem;
        }

        .stat-value.is-highlight {
            font-size: clamp(2rem, 3.2vw, 2.75rem);
            letter-spacing: -0.04em;
        }

        .stat-note {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.35rem;
        }

        .icon-pill {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(46, 91, 135, 0.1);
            color: var(--brand-700);
            flex: 0 0 44px;
        }

        .section-head {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.95rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.1rem;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .json-frame {
            max-height: 520px;
            overflow: auto;
            border-radius: 18px;
            background: #0b1220;
            color: #dbe7ff;
            padding: 1rem;
            font-size: 0.88rem;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .meta-item {
            padding: 0.85rem 0.95rem;
            border-radius: 16px;
            background: rgba(18, 38, 63, 0.04);
            border: 1px solid rgba(18, 38, 63, 0.06);
        }

        .meta-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 800;
            color: var(--text-muted);
        }

        .meta-value {
            margin-top: 0.2rem;
            font-size: 0.94rem;
            font-weight: 700;
            color: var(--text-main);
            word-break: break-word;
        }

        .field-grid,
        .cell-grid {
            display: grid;
            gap: 0.75rem;
        }

        .field-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .cell-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .field-card,
        .cell-card {
            padding: 0.85rem 0.95rem;
            border-radius: 16px;
            background: rgba(18, 38, 63, 0.04);
            border: 1px solid rgba(18, 38, 63, 0.06);
        }

        .field-label,
        .cell-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 800;
            color: var(--text-muted);
        }

        .field-value,
        .cell-value {
            margin-top: 0.25rem;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-main);
            word-break: break-word;
        }

        .field-subvalue {
            margin-top: 0.15rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .section-block {
            margin-top: 1rem;
        }

        .pulse-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 0.4rem rgba(15, 118, 110, 0.12);
            animation: pulse 1.8s ease-in-out infinite;
        }

        .pulse-dot.is-stale {
            box-shadow: 0 0 0 0.4rem rgba(245, 158, 11, 0.12);
        }

        .pulse-dot.is-empty {
            box-shadow: 0 0 0 0.4rem rgba(107, 114, 128, 0.12);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.85;
            }

            50% {
                transform: scale(1.15);
                opacity: 1;
            }
        }

        @media (max-width: 991.98px) {
            .mqtt-hero {
                padding: 1.2rem;
            }

            .meta-grid,
            .field-grid,
            .cell-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('konten')
    <section class="mqtt-page">
        <div class="mqtt-orb one"></div>
        <div class="mqtt-orb two"></div>

        <div class="container mqtt-shell">
            <div class="mqtt-hero mb-4">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <div class="eyebrow mb-3">
                            <i class="bi bi-broadcast-pin"></i>
                            JK-BMS MQTT Monitor
                        </div>
                        <h1 class="mqtt-title">Panel pemantauan data daya motor</h1>
                        <p class="mqtt-lead mb-4">
                            Data terakhir dari broker akan tampil otomatis setiap 2 detik
                        </p>

                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span id="connectionStatus" class="status-chip is-empty">
                                <span class="pulse-dot is-empty"></span>
                                Menunggu data...
                            </span>
                            <button type="button" class="btn btn-brand" id="refreshButton">
                                <i class="bi bi-arrow-clockwise"></i> Refresh sekarang
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-card p-3">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-pill">
                                    <i class="bi bi-cpu-fill"></i>
                                </div>
                                <div>
                                    <div class="section-title mb-0">Ringkasan Data</div>
                                    {{-- <div class="section-subtitle">Update realtime dari cache Laravel</div> --}}
                                </div>
                            </div>

                            <div class="meta-grid">
                                <div class="meta-item">
                                    <div class="meta-label">Pembaruan terakhir</div>
                                    <div class="meta-value" id="summaryUpdated">-</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Interval</div>
                                    <div class="meta-value">2 detik</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="stat-card p-3 p-md-4">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="stat-label">Delta Cell Voltage</div>
                                <div class="stat-value is-highlight" id="statDelta">-</div>
                                <div class="stat-note" id="statDeltaNote">Cell difference paling penting</div>
                            </div>
                            <div class="icon-pill"><i class="bi bi-arrows-angle-expand"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="stat-label">Voltage</div>
                                <div class="stat-value" id="statVoltage">-</div>
                                <div class="stat-note" id="statVoltageNote">Belum ada data</div>
                            </div>
                            <div class="icon-pill"><i class="bi bi-lightning-charge-fill"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="stat-label">Current</div>
                                <div class="stat-value" id="statCurrent">-</div>
                                <div class="stat-note" id="statCurrentNote">Belum ada data</div>
                            </div>
                            <div class="icon-pill"><i class="bi bi-activity"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="stat-label">Battery</div>
                                <div class="stat-value" id="statSoc">-</div>
                                <div class="stat-note" id="statSocNote">Belum ada data</div>
                            </div>
                            <div class="icon-pill"><i class="bi bi-battery-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="stat-label">Power</div>
                                <div class="stat-value" id="statPower">-</div>
                                <div class="stat-note" id="statPowerNote">Belum ada data</div>
                            </div>
                            <div class="icon-pill"><i class="bi bi-plug-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-7">
                    <div class="panel-card p-3 p-md-4 h-100">
                        <div class="section-head">
                            <div>
                                <div class="section-title">Detail Telemetry</div>
                                <div class="section-subtitle">Semua field utama dari payload MQTT terakhir</div>
                            </div>
                            <span class="badge rounded-pill text-bg-primary-subtle text-primary-emphasis px-3 py-2"
                                id="detailBadge">Menunggu update</span>
                        </div>

                        <div class="field-grid" id="detailGrid">
                            <div class="field-card">
                                <div class="field-label">Status</div>
                                <div class="field-value">Menunggu update</div>
                                <div class="field-subvalue">Data belum diterima.</div>
                            </div>
                        </div>

                        <div class="section-block">
                            <div class="section-head mb-2">
                                <div>
                                    <div class="section-title">Cell Voltages</div>
                                    <div class="section-subtitle">24 cell ditampilkan satu per satu</div>
                                </div>
                            </div>
                            <div class="cell-grid" id="cellsGrid">
                                <div class="cell-card">
                                    <div class="cell-label">Cell</div>
                                    <div class="cell-value">Menunggu data</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="panel-card p-3 p-md-4 h-100">
                        <div class="section-head">
                            <div>
                                <div class="section-title">Raw JSON</div>
                                <div class="section-subtitle">Fallback untuk melihat payload asli tanpa diubah</div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="copyButton">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>

                        <pre class="json-frame" id="data">Menunggu data...</pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pollInterval = 2000;
            const dataFrame = document.getElementById('data');
            const refreshButton = document.getElementById('refreshButton');
            const copyButton = document.getElementById('copyButton');
            const connectionStatus = document.getElementById('connectionStatus');
            const summaryUpdated = document.getElementById('summaryUpdated');
            const detailGrid = document.getElementById('detailGrid');
            const cellsGrid = document.getElementById('cellsGrid');
            const detailBadge = document.getElementById('detailBadge');

            const statDelta = document.getElementById('statDelta');
            const statDeltaNote = document.getElementById('statDeltaNote');
            const statVoltage = document.getElementById('statVoltage');
            const statVoltageNote = document.getElementById('statVoltageNote');
            const statCurrent = document.getElementById('statCurrent');
            const statCurrentNote = document.getElementById('statCurrentNote');
            const statSoc = document.getElementById('statSoc');
            const statSocNote = document.getElementById('statSocNote');
            const statPower = document.getElementById('statPower');
            const statPowerNote = document.getElementById('statPowerNote');

            let lastPayloadText = '';
            let lastUpdatedAt = null;

            function formatValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '-';
                }

                if (typeof value === 'number') {
                    return Number.isInteger(value) ? value.toString() : value.toFixed(3);
                }

                if (typeof value === 'boolean') {
                    return value ? 'Ya' : 'Tidak';
                }

                if (Array.isArray(value)) {
                    return `${value.length} item`;
                }

                if (typeof value === 'object') {
                    return JSON.stringify(value);
                }

                return String(value);
            }

            function findField(source, keys) {
                if (!source || typeof source !== 'object') {
                    return undefined;
                }

                for (const key of keys) {
                    if (Object.prototype.hasOwnProperty.call(source, key) && source[key] !== undefined && source[
                            key] !== null) {
                        return source[key];
                    }
                }

                const normalizedKeys = Object.keys(source).reduce((map, key) => {
                    map[key.toLowerCase().replace(/[^a-z0-9]/g, '')] = key;
                    return map;
                }, {});

                for (const key of keys) {
                    const lookupKey = key.toLowerCase().replace(/[^a-z0-9]/g, '');
                    const actualKey = normalizedKeys[lookupKey];

                    if (actualKey && source[actualKey] !== undefined && source[actualKey] !== null) {
                        return source[actualKey];
                    }
                }

                return undefined;
            }

            function escapeHtml(value) {
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function formatTelemetryValue(value, unit = '') {
                const formatted = formatValue(value);
                return unit ? `${formatted} ${unit}` : formatted;
            }

            function buildFieldCards(source) {
                const fields = [
                    ['total_voltage', 'Total Voltage', 'V'],
                    ['current', 'Current', 'A'],
                    ['power', 'Power', 'W'],
                    ['soc', 'Battery', '%'],
                    ['battery_temp_1', 'Battery Temp 1', '°C'],
                    ['battery_temp_2', 'Battery Temp 2', '°C'],
                    ['mosfet_temperature', 'MOSFET Temp', '°C'],
                    ['min_cell_voltage', 'Min Cell Voltage', 'V'],
                    ['max_cell_voltage', 'Max Cell Voltage', 'V'],
                    ['delta_cell_voltage', 'Delta Cell Voltage', 'V'],
                ];

                return fields.map(([key, label, unit]) => {
                    const value = source ? source[key] : undefined;

                    return `
                        <div class="field-card">
                            <div class="field-label">${escapeHtml(label)}</div>
                            <div class="field-value">${escapeHtml(formatTelemetryValue(value, value !== undefined ? unit : ''))}</div>
                            <div class="field-subvalue">${escapeHtml(key)}</div>
                        </div>
                    `;
                }).join('');
            }

            function buildCellCards(cells) {
                if (!cells || typeof cells !== 'object' || Array.isArray(cells)) {
                    return `
                        <div class="cell-card">
                            <div class="cell-label">Cell</div>
                            <div class="cell-value">Belum ada data cell</div>
                        </div>
                    `;
                }

                return Object.entries(cells)
                    .sort((left, right) => Number(left[0]) - Number(right[0]))
                    .map(([cellNumber, value]) => `
                        <div class="cell-card">
                            <div class="cell-label">Cell ${escapeHtml(cellNumber)}</div>
                            <div class="cell-value">${escapeHtml(formatValue(value))} V</div>
                        </div>
                    `)
                    .join('');
            }

            function setStatus(type, text) {
                connectionStatus.classList.remove('is-stale', 'is-empty');

                if (type === 'stale') {
                    connectionStatus.classList.add('is-stale');
                } else if (type === 'empty') {
                    connectionStatus.classList.add('is-empty');
                }

                connectionStatus.innerHTML =
                    `<span class="pulse-dot ${type === 'stale' ? 'is-stale' : type === 'empty' ? 'is-empty' : ''}"></span>${escapeHtml(text)}`;
            }

            function setStat(target, noteTarget, value, note) {
                target.textContent = value;
                noteTarget.textContent = note;
            }

            function markEmpty(message) {
                dataFrame.textContent = message;
                detailGrid.innerHTML = `
                    <div class="field-card">
                        <div class="field-label">Status</div>
                        <div class="field-value">Tidak ada data</div>
                        <div class="field-subvalue">${escapeHtml(message)}</div>
                    </div>
                `;
                cellsGrid.innerHTML = `
                    <div class="cell-card">
                        <div class="cell-label">Cell</div>
                        <div class="cell-value">Menunggu data</div>
                    </div>
                `;
                detailBadge.textContent = 'Menunggu update';
                summaryUpdated.textContent = '-';
                setStat(statDelta, statDeltaNote, '-', 'Cell difference paling penting');
                setStat(statVoltage, statVoltageNote, '-', 'Belum ada data');
                setStat(statCurrent, statCurrentNote, '-', 'Belum ada data');
                setStat(statSoc, statSocNote, '-', 'Belum ada data');
                setStat(statPower, statPowerNote, '-', 'Belum ada data');
                setStatus('empty', 'Menunggu data...');
            }

            function updateDashboard(data) {
                lastPayloadText = JSON.stringify(data, null, 2);
                lastUpdatedAt = new Date();

                dataFrame.textContent = lastPayloadText;
                summaryUpdated.textContent = lastUpdatedAt.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                });

                const voltage = findField(data, ['total_voltage', 'voltage', 'packVoltage', 'batteryVoltage',
                    'volt', 'vbat'
                ]);
                const current = findField(data, ['current', 'packCurrent', 'batteryCurrent', 'ampere', 'amps',
                    'a'
                ]);
                const power = findField(data, ['power', 'watt', 'loadPower']);
                const soc = findField(data, ['soc', 'stateOfCharge', 'batterySoc', 'charge', 'batteryLevel']);
                const deltaCellVoltage = findField(data, ['delta_cell_voltage', 'deltaCellVoltage']);
                const cells = data && typeof data === 'object' ? data.cells : undefined;

                setStat(statDelta, statDeltaNote, deltaCellVoltage !== undefined ?
                    `${formatValue(deltaCellVoltage)} V` : '-', deltaCellVoltage !== undefined ?
                    'Selisih cell terbaca' : 'Belum ada field delta_cell_voltage');
                setStat(statVoltage, statVoltageNote, voltage !== undefined ? `${formatValue(voltage)} V` : '-',
                    voltage !== undefined ? 'Nilai tegangan terdeteksi' : 'Belum ada field voltage');
                setStat(statCurrent, statCurrentNote, current !== undefined ? `${formatValue(current)} A` : '-',
                    current !== undefined ? 'Arus terdeteksi di payload' : 'Belum ada field current');
                setStat(statSoc, statSocNote, soc !== undefined ? `${formatValue(soc)}%` : '-', soc !== undefined ?
                    'Battery terbaca' : 'Belum ada field SOC');
                setStat(statPower, statPowerNote, power !== undefined ? `${formatValue(power)} W` : '-', power !==
                    undefined ? 'Daya terdeteksi' : 'Belum ada field power');

                detailGrid.innerHTML = buildFieldCards(data);
                cellsGrid.innerHTML = buildCellCards(cells);
                detailBadge.textContent = 'Update terbaru';
                setStatus('live',
                    `Data masuk ${lastUpdatedAt.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}`
                );
            }

            async function loadData() {
                try {
                    const response = await fetch('/mqtt-data', {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    const data = await response.json();

                    if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) {
                        markEmpty('Belum ada payload MQTT yang diterima dari cache.');
                        return;
                    }

                    updateDashboard(data);
                } catch (error) {
                    markEmpty(`Gagal memuat data MQTT: ${error.message}`);
                    detailBadge.textContent = 'Error';
                    connectionStatus.classList.remove('is-empty');
                    connectionStatus.classList.add('is-stale');
                    connectionStatus.innerHTML = '<span class="pulse-dot is-stale"></span>Gagal mengambil data';
                }
            }

            refreshButton.addEventListener('click', loadData);

            copyButton.addEventListener('click', async () => {
                if (!lastPayloadText) {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(lastPayloadText);
                    copyButton.innerHTML = '<i class="bi bi-check2"></i> Copied';
                    window.setTimeout(() => {
                        copyButton.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
                    }, 1500);
                } catch (error) {
                    copyButton.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Gagal';
                    window.setTimeout(() => {
                        copyButton.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
                    }, 1500);
                }
            });

            loadData();
            window.setInterval(loadData, pollInterval);
        });
    </script>
@endsection
