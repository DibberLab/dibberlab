<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tone Generator | Dibber Lab</title>
    
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
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }

        /* Waveform Button Active State */
        .wave-btn.active {
            background-color: #3b82f6; /* Blue */
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Tone Generator</h1>
            <p class="text-center text-gray-400 mb-8">Generate pure audio frequencies for testing or tuning.</p>

            <div class="bg-gray-900 rounded-xl p-6 mb-8 border border-gray-700 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent opacity-50"></div>
                
                <input type="number" id="freq-input" value="440" min="20" max="20000" 
                    class="bg-transparent text-6xl font-mono font-bold text-white text-center w-full focus:outline-none focus:border-b-2 border-gray-700 focus:border-amber-500 transition-colors">
                <p class="text-sm text-gray-500 font-bold tracking-widest mt-2">HERTZ (Hz)</p>
            </div>

            <div class="space-y-8">
                
                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-2 font-bold uppercase">
                        <span>Low (20Hz)</span>
                        <span>High (20kHz)</span>
                    </div>
                    <input type="range" id="freq-slider" min="20" max="2000" step="1" value="440" class="w-full mb-2">
                    <div class="flex justify-center gap-2">
                         <button class="preset-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs text-gray-300" data-hz="60">60Hz</button>
                         <button class="preset-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs text-gray-300" data-hz="440">440Hz</button>
                         <button class="preset-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs text-gray-300" data-hz="1000">1kHz</button>
                         <button class="preset-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs text-gray-300" data-hz="10000">10kHz</button>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <button class="wave-btn active p-3 rounded-lg border border-gray-600 bg-gray-800 hover:bg-gray-700 transition-all flex flex-col items-center gap-1" data-type="sine">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12c0-4 4-8 8-8s8 4 8 8-4 8-8 8-8-4-8-8z"></path></svg>
                        <span class="text-[10px] uppercase font-bold">Sine</span>
                    </button>
                    <button class="wave-btn p-3 rounded-lg border border-gray-600 bg-gray-800 hover:bg-gray-700 transition-all flex flex-col items-center gap-1" data-type="square">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z"></path></svg>
                        <span class="text-[10px] uppercase font-bold">Square</span>
                    </button>
                    <button class="wave-btn p-3 rounded-lg border border-gray-600 bg-gray-800 hover:bg-gray-700 transition-all flex flex-col items-center gap-1" data-type="sawtooth">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20L20 4v16H4z"></path></svg>
                        <span class="text-[10px] uppercase font-bold">Saw</span>
                    </button>
                    <button class="wave-btn p-3 rounded-lg border border-gray-600 bg-gray-800 hover:bg-gray-700 transition-all flex flex-col items-center gap-1" data-type="triangle">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 16h16L12 4z"></path></svg>
                        <span class="text-[10px] uppercase font-bold">Tri</span>
                    </button>
                </div>

                <div>
                    <div class="flex justify-between text-xs text-gray-400 mb-1 font-bold">
                        <span>Volume</span>
                        <span id="vol-display">50%</span>
                    </div>
                    <input type="range" id="vol-slider" min="0" max="1" step="0.01" value="0.5" class="w-full">
                </div>

                <button id="play-btn" class="w-full py-5 rounded-xl text-xl font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                    PLAY TONE
                </button>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const playBtn = document.getElementById('play-btn');
        const freqSlider = document.getElementById('freq-slider');
        const freqInput = document.getElementById('freq-input');
        const volSlider = document.getElementById('vol-slider');
        const volDisplay = document.getElementById('vol-display');
        const waveBtns = document.querySelectorAll('.wave-btn');
        const presetBtns = document.querySelectorAll('.preset-btn');

        let audioCtx;
        let oscillator;
        let gainNode;
        let isPlaying = false;
        
        let currentFreq = 440;
        let currentVol = 0.5;
        let currentWave = 'sine';

        // --- CORE AUDIO LOGIC ---
        async function startTone() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            oscillator = audioCtx.createOscillator();
            gainNode = audioCtx.createGain();

            // Set Params
            oscillator.type = currentWave;
            oscillator.frequency.value = currentFreq;
            
            // Volume Ramp (Prevent popping)
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(currentVol, audioCtx.currentTime + 0.1);

            // Connect
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.start();
            isPlaying = true;
            updateUIState();
        }

        function stopTone() {
            if (oscillator && isPlaying) {
                // Ramp down volume to avoid "click"
                gainNode.gain.setValueAtTime(gainNode.gain.value, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.1);
                
                oscillator.stop(audioCtx.currentTime + 0.1);
                isPlaying = false;
                updateUIState();
            }
        }

        function updateUIState() {
            if (isPlaying) {
                playBtn.textContent = "STOP TONE";
                playBtn.classList.replace('bg-emerald-600', 'bg-red-600');
                playBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
                playBtn.classList.replace('shadow-emerald-900/50', 'shadow-red-900/50');
            } else {
                playBtn.textContent = "PLAY TONE";
                playBtn.classList.replace('bg-red-600', 'bg-emerald-600');
                playBtn.classList.replace('hover:bg-red-500', 'hover:bg-emerald-500');
                playBtn.classList.replace('shadow-red-900/50', 'shadow-emerald-900/50');
            }
        }

        // --- INPUT LISTENERS ---
        
        // 1. Frequency Control
        function setFrequency(val) {
            currentFreq = parseInt(val);
            // Limit checks
            if (currentFreq < 20) currentFreq = 20;
            if (currentFreq > 20000) currentFreq = 20000;

            freqInput.value = currentFreq;
            freqSlider.value = currentFreq; // Note: For higher Hz, a log slider is better, keeping linear for simplicity here.
            
            if (isPlaying && oscillator) {
                oscillator.frequency.value = currentFreq;
            }
        }

        freqSlider.addEventListener('input', (e) => setFrequency(e.target.value));
        freqInput.addEventListener('input', (e) => setFrequency(e.target.value));

        // Presets
        presetBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                setFrequency(btn.dataset.hz);
            });
        });

        // 2. Waveform Control
        waveBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update UI
                waveBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Update Audio
                currentWave = btn.dataset.type;
                if (isPlaying && oscillator) {
                    oscillator.type = currentWave;
                }
            });
        });

        // 3. Volume Control
        volSlider.addEventListener('input', (e) => {
            currentVol = parseFloat(e.target.value);
            volDisplay.textContent = Math.round(currentVol * 100) + '%';
            
            if (isPlaying && gainNode) {
                // Instant update for volume is okay, use small time constant for smoothness
                gainNode.gain.setTargetAtTime(currentVol, audioCtx.currentTime, 0.05);
            }
        });

        // 4. Play Button
        playBtn.addEventListener('click', () => {
            if (isPlaying) stopTone();
            else startTone();
        });

    </script>
</body>
</html>