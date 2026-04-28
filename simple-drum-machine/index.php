<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drum Machine | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none; 
            background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }

        /* Step Button Styling */
        .step-btn {
            aspect-ratio: 1/1;
            border-radius: 4px;
            background-color: #374151; /* gray-700 */
            border: 1px solid #4b5563;
            transition: all 0.1s;
        }
        .step-btn:hover {
            background-color: #4b5563;
        }
        
        /* Active (Note ON) States */
        .step-btn.active-kick { background-color: #ef4444 !important; border-color: #ef4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }
        .step-btn.active-snare { background-color: #3b82f6 !important; border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.4); }
        .step-btn.active-hihat { background-color: #f59e0b !important; border-color: #f59e0b; box-shadow: 0 0 10px rgba(245, 158, 11, 0.4); }
        .step-btn.active-clap { background-color: #10b981 !important; border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }

        /* Playhead Highlight */
        .step-col.playing .step-btn {
            border-color: #fff;
            filter: brightness(1.2);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-2 md:px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-4 md:p-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400">Drum Machine</h1>
                    <p class="text-gray-400 text-sm">16-Step Sequencer (Synthesized Audio)</p>
                </div>
                
                <div class="flex items-center gap-4 bg-gray-900 p-3 rounded-xl border border-gray-700">
                    <button id="bpm-minus" class="w-8 h-8 rounded bg-gray-700 hover:bg-gray-600 font-bold">-</button>
                    <div class="text-center w-20">
                        <div class="text-xl font-bold font-mono" id="bpm-display">120</div>
                        <div class="text-[10px] text-gray-500 font-bold tracking-widest">BPM</div>
                    </div>
                    <button id="bpm-plus" class="w-8 h-8 rounded bg-gray-700 hover:bg-gray-600 font-bold">+</button>
                </div>
            </div>

            <div class="flex gap-4 mb-6">
                <button id="play-btn" class="flex-1 py-3 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                    PLAY
                </button>
                <button id="clear-btn" class="px-6 rounded-xl font-bold bg-gray-700 hover:bg-gray-600 text-gray-300 border border-gray-600 transition-colors">
                    CLEAR
                </button>
            </div>

            <div class="overflow-x-auto pb-2">
                <div class="min-w-[600px] select-none" id="sequencer-grid">
                    </div>
            </div>
            
            <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                <span>1</span>
                <span>5</span>
                <span>9</span>
                <span>13</span>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const playBtn = document.getElementById('play-btn');
        const clearBtn = document.getElementById('clear-btn');
        const bpmDisplay = document.getElementById('bpm-display');
        const sequencerGrid = document.getElementById('sequencer-grid');

        // Audio Context
        let audioCtx;
        let isPlaying = false;
        let currentStep = 0;
        let nextStepTime = 0.0;
        let timerID;
        let tempo = 120.0;
        const lookahead = 25.0; // ms
        const scheduleAheadTime = 0.1; // s

        // Instruments Configuration
        const instruments = [
            { name: "Kick",  color: "active-kick",  label: "🥁" },
            { name: "Snare", color: "active-snare", label: "💥" },
            { name: "HiHat", color: "active-hihat", label: "✨" },
            { name: "Clap",  color: "active-clap",  label: "👏" }
        ];

        // 16 steps, 4 instruments
        // Initialize Grid Data (0 = off, 1 = on)
        let gridData = Array(4).fill().map(() => Array(16).fill(0));

        // Basic Preset
        function loadPreset() {
            // Kick on 1, 5, 9, 13
            gridData[0][0] = 1; gridData[0][4] = 1; gridData[0][8] = 1; gridData[0][12] = 1;
            // Snare on 5, 13
            gridData[1][4] = 1; gridData[1][12] = 1;
            // HiHat every other
            for(let i=0; i<16; i+=2) gridData[2][i] = 1;
        }

        // --- UI BUILDER ---
        function buildGrid() {
            sequencerGrid.innerHTML = '';

            instruments.forEach((inst, rowIdx) => {
                const row = document.createElement('div');
                row.className = "flex items-center gap-2 mb-2";

                // Label
                const label = document.createElement('div');
                label.className = "w-20 text-sm font-bold text-gray-400 flex items-center gap-2";
                label.innerHTML = `<span>${inst.label}</span> ${inst.name}`;
                row.appendChild(label);

                // Steps
                for (let colIdx = 0; colIdx < 16; colIdx++) {
                    const step = document.createElement('button');
                    // Add column class for playhead tracking
                    step.dataset.col = colIdx; 
                    step.className = `step-btn flex-1 ${gridData[rowIdx][colIdx] ? inst.color : ''}`;
                    
                    // Beat markers (every 4th beat has slight margin visual)
                    if (colIdx % 4 === 3 && colIdx !== 15) {
                        step.style.marginRight = "4px";
                    }

                    step.addEventListener('click', () => toggleStep(rowIdx, colIdx, step, inst.color));
                    row.appendChild(step);
                }
                sequencerGrid.appendChild(row);
            });
        }

        function toggleStep(row, col, btn, colorClass) {
            gridData[row][col] = !gridData[row][col];
            if (gridData[row][col]) {
                btn.classList.add(colorClass);
                // Preview sound on click
                if (!isPlaying) playSound(row, audioCtx ? audioCtx.currentTime : 0);
            } else {
                btn.classList.remove(colorClass);
            }
        }

        // --- SYNTHESIS ENGINE ---
        // Generates drum sounds using Web Audio API Oscillators and Noise Buffers
        
        // 1. Noise Buffer Generator (for Snare/HiHat)
        let noiseBuffer;
        function setupNoiseBuffer() {
            const bufferSize = audioCtx.sampleRate * 2; // 2 sec buffer
            noiseBuffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const output = noiseBuffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                output[i] = Math.random() * 2 - 1;
            }
        }

        function playSound(instrumentIndex, time) {
            if (!audioCtx) return; // Safety

            // Ensure time is in future
            if (time < audioCtx.currentTime) time = audioCtx.currentTime;

            if (instrumentIndex === 0) playKick(time);
            else if (instrumentIndex === 1) playSnare(time);
            else if (instrumentIndex === 2) playHiHat(time);
            else if (instrumentIndex === 3) playClap(time);
        }

        function playKick(time) {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.frequency.setValueAtTime(150, time);
            osc.frequency.exponentialRampToValueAtTime(0.01, time + 0.5);

            gain.gain.setValueAtTime(1, time);
            gain.gain.exponentialRampToValueAtTime(0.01, time + 0.5);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start(time);
            osc.stop(time + 0.5);
        }

        function playSnare(time) {
            // Noise part
            const noise = audioCtx.createBufferSource();
            noise.buffer = noiseBuffer;
            const noiseFilter = audioCtx.createBiquadFilter();
            noiseFilter.type = 'highpass';
            noiseFilter.frequency.value = 1000;
            const noiseGain = audioCtx.createGain();
            
            noise.connect(noiseFilter);
            noiseFilter.connect(noiseGain);
            noiseGain.connect(audioCtx.destination);
            
            noiseGain.gain.setValueAtTime(1, time);
            noiseGain.gain.exponentialRampToValueAtTime(0.01, time + 0.2);
            noise.start(time);

            // Tonal part (Triangle wave snap)
            const osc = audioCtx.createOscillator();
            osc.type = 'triangle';
            const oscGain = audioCtx.createGain();
            osc.connect(oscGain);
            oscGain.connect(audioCtx.destination);
            
            osc.frequency.setValueAtTime(100, time);
            oscGain.gain.setValueAtTime(0.7, time);
            oscGain.gain.exponentialRampToValueAtTime(0.01, time + 0.1);
            
            osc.start(time);
            osc.stop(time + 0.2);
        }

        function playHiHat(time) {
            // High frequency noise
            const source = audioCtx.createBufferSource();
            source.buffer = noiseBuffer;
            
            const bandpass = audioCtx.createBiquadFilter();
            bandpass.type = 'bandpass';
            bandpass.frequency.value = 10000;

            const highpass = audioCtx.createBiquadFilter();
            highpass.type = 'highpass';
            highpass.frequency.value = 7000;

            const gain = audioCtx.createGain();
            
            source.connect(bandpass);
            bandpass.connect(highpass);
            highpass.connect(gain);
            gain.connect(audioCtx.destination);

            // Short envelope
            gain.gain.setValueAtTime(0.6, time);
            gain.gain.exponentialRampToValueAtTime(0.01, time + 0.05);

            source.start(time);
        }

        function playClap(time) {
            // Claps are noise with multiple rapid envelope triggers
            const source = audioCtx.createBufferSource();
            source.buffer = noiseBuffer;
            const filter = audioCtx.createBiquadFilter();
            filter.type = 'bandpass';
            filter.frequency.value = 1500;
            const gain = audioCtx.createGain();

            source.connect(filter);
            filter.connect(gain);
            gain.connect(audioCtx.destination);

            const t = time;
            // 3 rapid pulses
            gain.gain.setValueAtTime(0, t);
            gain.gain.linearRampToValueAtTime(0.8, t + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.1, t + 0.04);

            gain.gain.setValueAtTime(0.1, t + 0.04);
            gain.gain.linearRampToValueAtTime(0.6, t + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.1, t + 0.08);

            gain.gain.setValueAtTime(0.1, t + 0.08);
            gain.gain.linearRampToValueAtTime(0.4, t + 0.09);
            gain.gain.exponentialRampToValueAtTime(0.001, t + 0.15);

            source.start(time);
        }

        // --- SCHEDULER ---
        function nextNote() {
            const secondsPerBeat = 60.0 / tempo;
            const secondsPer16th = secondsPerBeat / 4; // 16th notes
            
            nextStepTime += secondsPer16th;
            currentStep = (currentStep + 1) % 16;
        }

        function scheduleNote(stepNumber, time) {
            // Audio Scheduling
            for (let i = 0; i < 4; i++) {
                if (gridData[i][stepNumber]) {
                    playSound(i, time);
                }
            }

            // Visual Scheduling (Draw UI)
            const drawTime = (time - audioCtx.currentTime) * 1000;
            setTimeout(() => {
                drawPlayhead(stepNumber);
            }, Math.max(0, drawTime));
        }

        function scheduler() {
            while (nextStepTime < audioCtx.currentTime + scheduleAheadTime) {
                scheduleNote(currentStep, nextStepTime);
                nextNote();
            }
            timerID = window.setTimeout(scheduler, lookahead);
        }

        function drawPlayhead(step) {
            // Remove previous highlights
            document.querySelectorAll('.step-btn').forEach(btn => {
                btn.classList.remove('border-white', 'brightness-125');
                btn.style.filter = '';
                btn.style.borderColor = '';
            });

            // Highlight current column
            const btns = document.querySelectorAll(`button[data-col="${step}"]`);
            btns.forEach(btn => {
                btn.style.borderColor = '#fff';
                btn.style.filter = 'brightness(1.5)';
            });
        }

        // --- CONTROL LOGIC ---
        async function startSequencer() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                setupNoiseBuffer();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            currentStep = 0;
            nextStepTime = audioCtx.currentTime + 0.1;
            scheduler();
            
            isPlaying = true;
            playBtn.textContent = "STOP";
            playBtn.classList.replace('bg-emerald-600', 'bg-red-600');
            playBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
            playBtn.classList.replace('shadow-emerald-900/50', 'shadow-red-900/50');
        }

        function stopSequencer() {
            window.clearTimeout(timerID);
            isPlaying = false;
            playBtn.textContent = "PLAY";
            playBtn.classList.replace('bg-red-600', 'bg-emerald-600');
            playBtn.classList.replace('hover:bg-red-500', 'hover:bg-emerald-500');
            playBtn.classList.replace('shadow-red-900/50', 'shadow-emerald-900/50');
            
            // Clear playhead
            document.querySelectorAll('.step-btn').forEach(btn => {
                btn.style.borderColor = '';
                btn.style.filter = '';
            });
        }

        // Listeners
        playBtn.addEventListener('click', () => isPlaying ? stopSequencer() : startSequencer());
        
        clearBtn.addEventListener('click', () => {
            gridData = gridData.map(row => row.fill(0));
            buildGrid();
        });

        document.getElementById('bpm-minus').addEventListener('click', () => {
            tempo = Math.max(40, tempo - 5);
            bpmDisplay.textContent = tempo;
        });
        document.getElementById('bpm-plus').addEventListener('click', () => {
            tempo = Math.min(240, tempo + 5);
            bpmDisplay.textContent = tempo;
        });

        // Initialize
        loadPreset();
        buildGrid();

    </script>
</body>
</html>