<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Tuner | Dibber Lab</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="/dibber-header.js"></script>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0b0b10;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .tuner-card {
            background: #111118;
            border: 1px solid #1c1c28;
            border-radius: 28px;
            padding: 32px 28px 28px;
            width: 100%;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 30px 90px rgba(0,0,0,0.7);
            transition: border-color 0.5s ease, box-shadow 0.5s ease;
        }
        .tuner-card.in-tune {
            border-color: rgba(16, 185, 129, 0.35);
            box-shadow: 0 30px 90px rgba(0,0,0,0.7), 0 0 70px rgba(16, 185, 129, 0.1);
        }

        /* --- Header --- */
        .tuner-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .tuner-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #3a3a50;
        }

        /* --- String Buttons --- */
        .strings-row {
            display: flex;
            gap: 7px;
            justify-content: center;
            margin-bottom: 24px;
        }
        .string-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid #222232;
            background: #16161f;
            color: #4b5563;
            font-weight: 700;
            font-size: 15px;
            cursor: default;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.3s ease, color 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
        }
        .string-btn.active {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            box-shadow: 0 0 18px rgba(245, 158, 11, 0.2);
        }
        .string-btn.in-tune {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            box-shadow: 0 0 22px rgba(16, 185, 129, 0.3);
        }

        /* --- Dial Canvas --- */
        #dial-canvas {
            display: block;
            margin: 0 auto -10px;
            max-width: 100%;
        }

        /* --- Note Display --- */
        .note-display {
            text-align: center;
            margin: 12px 0 6px;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
        }
        .note-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 78px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -3px;
            transition: color 0.35s ease;
            color: #2a2a38;
        }
        .note-name.active  { color: #e2e8f0; }
        .note-name.in-tune { color: #10b981; }
        .octave-sup {
            font-family: 'JetBrains Mono', monospace;
            font-size: 22px;
            color: #374151;
            align-self: flex-start;
            margin-top: 14px;
            transition: color 0.35s ease;
        }
        .octave-sup.active  { color: #6b7280; }
        .octave-sup.in-tune { color: #10b981; }

        /* --- Status Row --- */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 4px;
            min-height: 28px;
            margin-bottom: 22px;
        }
        .meta-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #2e2e40;
            transition: color 0.3s ease;
            min-width: 70px;
        }
        .meta-label.active { color: #4b5563; }
        .tuning-status-pill {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 999px;
            border: 1.5px solid transparent;
            transition: all 0.3s ease;
            color: transparent;
            border-color: transparent;
        }
        .tuning-status-pill.flat    { color: #fbbf24; border-color: rgba(251,191,36,0.3); background: rgba(251,191,36,0.08); }
        .tuning-status-pill.sharp   { color: #f87171; border-color: rgba(248,113,113,0.3); background: rgba(248,113,113,0.08); }
        .tuning-status-pill.in-tune { color: #10b981; border-color: rgba(16,185,129,0.35); background: rgba(16,185,129,0.1); }

        /* --- Input Level --- */
        .level-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .level-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #252535;
            white-space: nowrap;
        }
        .level-track {
            flex: 1;
            height: 3px;
            background: #1a1a26;
            border-radius: 2px;
            overflow: hidden;
        }
        .level-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 2px;
            transition: width 0.05s linear;
        }

        /* --- Gain Row --- */
        .gain-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }
        .gain-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #252535;
            white-space: nowrap;
        }
        .gain-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #374151;
            min-width: 28px;
            text-align: right;
        }
        input[type=range] { -webkit-appearance: none; background: transparent; flex: 1; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 13px; width: 13px;
            border-radius: 50%; background: #374151; cursor: pointer; margin-top: -5px;
        }
        input[type=range]::-webkit-slider-runnable-track {
            height: 3px; background: #1a1a26; border-radius: 2px;
        }

        /* --- Main Button --- */
        .main-btn {
            width: 100%;
            padding: 15px;
            border-radius: 14px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .main-btn.start {
            background: #10b981;
            color: #fff;
            box-shadow: 0 6px 28px rgba(16, 185, 129, 0.28);
        }
        .main-btn.start:hover { box-shadow: 0 6px 36px rgba(16, 185, 129, 0.44); transform: translateY(-1px); }
        .main-btn.stop {
            background: #1c1c28;
            color: #6b7280;
            border: 1.5px solid #252535;
            box-shadow: none;
        }
        .main-btn.stop:hover { color: #ef4444; border-color: #ef4444; }
    </style>
</head>
<body>

<main style="flex:1; display:flex; align-items:center; justify-content:center; padding: 48px 16px;">
    <div class="tuner-card" id="tuner-card">

        <div class="tuner-header">
            <p class="tuner-title">Guitar Tuner &mdash; Standard EADGBe</p>
        </div>

        <div class="strings-row" id="strings-row"></div>

        <canvas id="dial-canvas" width="340" height="185"></canvas>

        <div class="note-display">
            <span class="note-name" id="note-name">--</span><span class="octave-sup" id="octave-el"></span>
        </div>

        <div class="status-row">
            <span class="meta-label" id="cents-label">-- ¢</span>
            <span class="tuning-status-pill" id="status-pill"></span>
            <span class="meta-label" id="freq-label" style="text-align:right">--- Hz</span>
        </div>

        <div class="level-row">
            <span class="level-label">Level</span>
            <div class="level-track"><div class="level-fill" id="level-fill"></div></div>
        </div>

        <div class="gain-row">
            <span class="gain-label">Boost</span>
            <input type="range" id="gain-slider" min="1" max="5" step="0.1" value="1">
            <span class="gain-val" id="gain-val">1×</span>
        </div>

        <button class="main-btn start" id="toggle-btn">Start Tuner</button>

    </div>
</main>

<footer style="padding: 24px; text-align:center; color:#252535; font-size:12px;">
    &copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- DOM ---
    const canvas     = document.getElementById('dial-canvas');
    const dctx       = canvas.getContext('2d');
    const tunerCard  = document.getElementById('tuner-card');
    const noteNameEl = document.getElementById('note-name');
    const octaveEl   = document.getElementById('octave-el');
    const centsLabel = document.getElementById('cents-label');
    const freqLabel  = document.getElementById('freq-label');
    const statusPill = document.getElementById('status-pill');
    const levelFill  = document.getElementById('level-fill');
    const gainSlider = document.getElementById('gain-slider');
    const gainValEl  = document.getElementById('gain-val');
    const toggleBtn  = document.getElementById('toggle-btn');
    const stringsRow = document.getElementById('strings-row');

    // --- STATE ---
    let audioCtx, analyser, gainNode, streamSource;
    let isOn = false, rafId;
    let smoothCents = 0, drawCents = 0;
    let lockedUntil  = 0;
    let lastGoodTime = 0;   // timestamp of last valid pitch read
    let holdState    = 'idle'; // dial state to show during hold

    const LOCK_IN_TUNE = 2400;
    const LOCK_WARN    = 500;
    const HOLD_MS      = 1400; // how long to hold last reading after signal drops
    const CONF_MIN     = 0.65;
    const METER_RANGE  = 50;

    // --- TUNING DATA ---
    const STRINGS = [
        { note:'E', octave:2, freq:82.41  },
        { note:'A', octave:2, freq:110.00 },
        { note:'D', octave:3, freq:146.83 },
        { note:'G', octave:3, freq:196.00 },
        { note:'B', octave:3, freq:246.94 },
        { note:'e', octave:4, freq:329.63 }
    ];
    const NOTE_NAMES = ["C","C#","D","D#","E","F","F#","G","G#","A","A#","B"];

    // --- CANVAS SETUP (responsive + HiDPI) ---
    const DPR    = Math.min(window.devicePixelRatio || 1, 2);
    const BASE_W = 340, BASE_H = 185;

    // Arc geometry defined in BASE_W × BASE_H coordinate space
    const CX      = BASE_W / 2;
    const CY      = BASE_H + 30;
    const R       = 172;
    const R_TRACK = R - 10;
    const A_START = Math.PI * 1.12;
    const A_END   = Math.PI * 1.88;
    const A_SPAN  = A_END - A_START;

    function sizeCanvas() {
        const innerW  = tunerCard.clientWidth - 56; // 28px padding × 2
        const dispW   = Math.min(BASE_W, Math.max(200, innerW));
        const dispH   = Math.round(BASE_H * dispW / BASE_W);
        const sc      = dispW / BASE_W;

        canvas.width        = Math.round(dispW * DPR);
        canvas.height       = Math.round(dispH * DPR);
        canvas.style.width  = dispW + 'px';
        canvas.style.height = dispH + 'px';
        dctx.setTransform(DPR * sc, 0, 0, DPR * sc, 0, 0);
    }

    if (window.ResizeObserver) {
        new ResizeObserver(() => {
            sizeCanvas();
            drawDial(drawCents, isOn ? holdState : 'idle');
        }).observe(tunerCard);
    }

    function centsToAngle(c) {
        const t = Math.max(-1, Math.min(1, c / METER_RANGE));
        return A_START + A_SPAN * (t + 1) / 2;
    }

    const COL = {
        trackBg:    '#161622',
        zoneFlat:   'rgba(251,191,36,0.18)',
        zoneSharp:  'rgba(248,113,113,0.18)',
        zoneGreen:  'rgba(16,185,129,0.18)',
        zoneGreenOn:'rgba(16,185,129,0.55)',
        tick:       '#252535',
        amber:      '#f59e0b',
        red:        '#f87171',
        green:      '#10b981',
    };

    function drawDial(cents, state) {
        dctx.clearRect(0, 0, BASE_W, BASE_H);

        const TW = 18;

        // Background track
        dctx.beginPath();
        dctx.arc(CX, CY, R_TRACK, A_START, A_END);
        dctx.strokeStyle = COL.trackBg;
        dctx.lineWidth   = TW;
        dctx.lineCap     = 'butt';
        dctx.stroke();

        // Flat zone
        dctx.beginPath();
        dctx.arc(CX, CY, R_TRACK, A_START, A_START + A_SPAN * 0.40);
        dctx.strokeStyle = COL.zoneFlat;
        dctx.lineWidth   = TW;
        dctx.stroke();

        // Sharp zone
        dctx.beginPath();
        dctx.arc(CX, CY, R_TRACK, A_START + A_SPAN * 0.60, A_END);
        dctx.strokeStyle = COL.zoneSharp;
        dctx.lineWidth   = TW;
        dctx.stroke();

        // Center zone
        dctx.beginPath();
        dctx.arc(CX, CY, R_TRACK, A_START + A_SPAN * 0.40, A_START + A_SPAN * 0.60);
        dctx.strokeStyle = state === 'in-tune' ? COL.zoneGreenOn : COL.zoneGreen;
        dctx.lineWidth   = TW;
        dctx.stroke();

        // Tick marks
        [-50,-40,-30,-20,-10,0,10,20,30,40,50].forEach(c => {
            const a    = centsToAngle(c);
            const isMaj = c === 0 || Math.abs(c) % 20 === 0;
            const rOut = isMaj ? R + 4  : R - 2;
            const rIn  = isMaj ? R - 20 : R - 18;
            dctx.beginPath();
            dctx.moveTo(CX + Math.cos(a) * rOut, CY + Math.sin(a) * rOut);
            dctx.lineTo(CX + Math.cos(a) * rIn,  CY + Math.sin(a) * rIn);
            dctx.strokeStyle = c === 0 ? '#2a3a3a' : COL.tick;
            dctx.lineWidth   = c === 0 ? 2.5 : 1.5;
            dctx.lineCap     = 'round';
            dctx.stroke();
        });

        // Needle
        if (state !== 'idle') {
            const na = centsToAngle(cents);
            const nx = CX + Math.cos(na) * (R - 12);
            const ny = CY + Math.sin(na) * (R - 12);
            const nc = state === 'in-tune' ? COL.green
                     : state === 'flat'    ? COL.amber
                     :                       COL.red;

            if (state === 'in-tune') { dctx.shadowColor = COL.green; dctx.shadowBlur = 14; }

            dctx.beginPath();
            dctx.moveTo(CX, CY);
            dctx.lineTo(nx, ny);
            dctx.strokeStyle = nc;
            dctx.lineWidth   = 2.5;
            dctx.lineCap     = 'round';
            dctx.stroke();

            dctx.beginPath();
            dctx.arc(nx, ny, 4, 0, Math.PI * 2);
            dctx.fillStyle = nc;
            dctx.fill();

            dctx.shadowBlur = 0;
        }

        // Pivot dot
        dctx.beginPath();
        dctx.arc(CX, CY, 5, 0, Math.PI * 2);
        dctx.fillStyle = state === 'in-tune' ? COL.green
                       : state === 'flat'    ? COL.amber
                       : state === 'sharp'   ? COL.red
                       : '#1e1e2c';
        dctx.fill();

        // Cent labels
        dctx.font         = `10px 'JetBrains Mono', monospace`;
        dctx.textAlign    = 'center';
        dctx.textBaseline = 'middle';
        [-40,-20,0,20,40].forEach(c => {
            const a  = centsToAngle(c);
            const lr = R - 32;
            dctx.fillStyle = c === 0 ? '#2a3a3a' : '#1e1e2c';
            dctx.fillText(c === 0 ? '0' : (c > 0 ? `+${c}` : `${c}`),
                CX + Math.cos(a) * lr, CY + Math.sin(a) * lr);
        });
    }

    sizeCanvas();
    drawDial(0, 'idle');

    // --- STRING BUTTONS ---
    STRINGS.forEach((s, i) => {
        const btn = document.createElement('button');
        btn.className   = 'string-btn';
        btn.textContent = s.note;
        btn.dataset.i   = i;
        stringsRow.appendChild(btn);
    });

    function setActiveString(target, state) {
        document.querySelectorAll('.string-btn').forEach((btn, i) => {
            btn.classList.remove('active','in-tune');
            if (target && STRINGS[i].freq === target.freq && state) {
                btn.classList.add(state);
            }
        });
    }

    // --- PITCH DETECTION ---
    function detectPitch(buf, sampleRate) {
        const n = buf.length;
        let rms = 0;
        for (let i = 0; i < n; i++) rms += buf[i] * buf[i];
        rms = Math.sqrt(rms / n);

        levelFill.style.width = `${Math.min(100, rms * 350)}%`;
        if (rms < 0.008) return -1;

        const r = new Float32Array(n);
        for (let lag = 0; lag < n; lag++) {
            let s = 0;
            for (let j = 0; j < n - lag; j++) s += buf[j] * buf[j + lag];
            r[lag] = s;
        }

        let d = 0;
        while (d < n - 1 && r[d] > r[d + 1]) d++;

        let maxVal = -1, maxPos = -1;
        for (let i = d; i < n; i++) {
            if (r[i] > maxVal) { maxVal = r[i]; maxPos = i; }
        }

        if (maxPos < 2 || r[0] === 0 || maxVal / r[0] < CONF_MIN) return -1;

        let T = maxPos;
        const a = (r[T-1] + r[T+1] - 2*r[T]) / 2;
        const b = (r[T+1] - r[T-1]) / 2;
        if (a !== 0) T = T - b / (2 * a);

        return sampleRate / T;
    }

    function lerp(a, b, t) { return a + (b - a) * t; }

    // --- UPDATE LOOP ---
    function update() {
        if (!analyser) return;
        const buf = new Float32Array(analyser.fftSize);
        analyser.getFloatTimeDomainData(buf);
        const pitch = detectPitch(buf, audioCtx.sampleRate);

        const now    = Date.now();
        const locked = now < lockedUntil;
        const inHold = (now - lastGoodTime) < HOLD_MS;

        if (pitch === -1) {
            if (inHold || locked) {
                // Signal dropped but we're within the hold window —
                // keep the last reading visible and drift the needle slowly back
                smoothCents = lerp(smoothCents, 0, 0.025);
                drawCents   = lerp(drawCents, smoothCents, 0.08);
                drawDial(drawCents, holdState);
            } else {
                // Silence confirmed — reset to idle
                smoothCents = lerp(smoothCents, 0, 0.1);
                drawCents   = lerp(drawCents, smoothCents, 0.15);
                drawDial(drawCents, 'idle');
                noteNameEl.textContent = '--';
                noteNameEl.className   = 'note-name';
                octaveEl.textContent   = '';
                octaveEl.className     = 'octave-sup';
                centsLabel.textContent = '-- ¢';
                centsLabel.className   = 'meta-label';
                freqLabel.textContent  = '--- Hz';
                freqLabel.className    = 'meta-label';
                statusPill.textContent = '';
                statusPill.className   = 'tuning-status-pill';
                tunerCard.classList.remove('in-tune');
                setActiveString(null, null);
            }
            rafId = requestAnimationFrame(update);
            return;
        }

        // Valid pitch detected
        lastGoodTime = now;

        let closest = null, minDiff = Infinity;
        STRINGS.forEach(s => {
            const d = Math.abs(1200 * Math.log2(pitch / s.freq));
            if (d < minDiff) { minDiff = d; closest = s; }
        });

        const rawCents  = closest ? 1200 * Math.log2(pitch / closest.freq) : 0;
        const midiNote  = Math.round(12 * Math.log2(pitch / 440)) + 69;
        const detNote   = NOTE_NAMES[((midiNote % 12) + 12) % 12];
        const detOctave = Math.floor(midiNote / 12) - 1;

        if (closest && Math.abs(rawCents) < 90) {
            smoothCents = lerp(smoothCents, rawCents, 0.22);
            drawCents   = lerp(drawCents, smoothCents, 0.18);

            if (!locked) {
                noteNameEl.textContent = detNote;
                noteNameEl.className   = 'note-name active';
                octaveEl.textContent   = detOctave;
                octaveEl.className     = 'octave-sup active';
                freqLabel.textContent  = `${pitch.toFixed(1)} Hz`;
                freqLabel.className    = 'meta-label active';
                const sign = smoothCents >= 0 ? '+' : '';
                centsLabel.textContent = `${sign}${smoothCents.toFixed(1)} ¢`;
                centsLabel.className   = 'meta-label active';

                const absCents = Math.abs(smoothCents);

                if (absCents < 5) {
                    holdState = 'in-tune';
                    drawDial(drawCents, 'in-tune');
                    noteNameEl.className   = 'note-name in-tune';
                    octaveEl.className     = 'octave-sup in-tune';
                    statusPill.textContent = 'In Tune';
                    statusPill.className   = 'tuning-status-pill in-tune';
                    tunerCard.classList.add('in-tune');
                    setActiveString(closest, 'in-tune');
                    lockedUntil = now + LOCK_IN_TUNE;
                } else {
                    holdState = smoothCents < 0 ? 'flat' : 'sharp';
                    tunerCard.classList.remove('in-tune');
                    setActiveString(closest, 'active');

                    if (smoothCents < 0) {
                        drawDial(drawCents, 'flat');
                        statusPill.textContent = 'Too Low';
                        statusPill.className   = 'tuning-status-pill flat';
                    } else {
                        drawDial(drawCents, 'sharp');
                        statusPill.textContent = 'Too High';
                        statusPill.className   = 'tuning-status-pill sharp';
                    }
                    lockedUntil = now + LOCK_WARN;
                }
            } else {
                // Locked — keep needle animated
                const dialSt = Math.abs(smoothCents) < 5 ? 'in-tune'
                             : smoothCents < 0 ? 'flat' : 'sharp';
                drawDial(drawCents, dialSt);
            }
        }

        rafId = requestAnimationFrame(update);
    }

    // --- AUDIO ---
    async function start() {
        try {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: { echoCancellation: false, autoGainControl: false, noiseSuppression: false, latency: 0 }
            });
            if (audioCtx.state === 'suspended') await audioCtx.resume();

            gainNode = audioCtx.createGain();
            gainNode.gain.value = parseFloat(gainSlider.value);
            analyser = audioCtx.createAnalyser();
            analyser.fftSize = 4096;
            streamSource = audioCtx.createMediaStreamSource(stream);
            streamSource.connect(gainNode);
            gainNode.connect(analyser);

            isOn = true;
            toggleBtn.textContent = 'Stop';
            toggleBtn.className   = 'main-btn stop';
            update();
        } catch (e) {
            alert('Microphone access denied. Please allow mic access and try again.');
        }
    }

    function resetUI() {
        smoothCents = 0; drawCents = 0;
        lastGoodTime = 0; holdState = 'idle';
        drawDial(0, 'idle');
        noteNameEl.textContent = '--';
        noteNameEl.className   = 'note-name';
        octaveEl.textContent   = '';
        octaveEl.className     = 'octave-sup';
        centsLabel.textContent = '-- ¢';
        centsLabel.className   = 'meta-label';
        freqLabel.textContent  = '--- Hz';
        freqLabel.className    = 'meta-label';
        statusPill.textContent = '';
        statusPill.className   = 'tuning-status-pill';
        tunerCard.classList.remove('in-tune');
        levelFill.style.width = '0%';
        setActiveString(null, null);
    }

    function stop() {
        if (streamSource) streamSource.mediaStream.getTracks().forEach(t => t.stop());
        if (audioCtx)     audioCtx.close();
        cancelAnimationFrame(rafId);
        isOn = false;
        toggleBtn.textContent = 'Start Tuner';
        toggleBtn.className   = 'main-btn start';
        resetUI();
    }

    toggleBtn.addEventListener('click', () => isOn ? stop() : start());

    gainSlider.addEventListener('input', e => {
        const v = parseFloat(e.target.value);
        gainValEl.textContent = `${v.toFixed(1)}×`;
        if (gainNode) gainNode.gain.value = v;
    });
});
</script>
</body>
</html>
