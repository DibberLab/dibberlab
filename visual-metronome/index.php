<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Metronome | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The Flashing Orb */
        #beat-indicator {
            transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.1s;
            will-change: transform, background-color;
            box-shadow: 0 0 30px rgba(0,0,0,0.5);
        }
        
        /* Flash States */
        .flash-strong {
            background-color: #34d399 !important; /* Emerald */
            transform: scale(1.15);
            box-shadow: 0 0 50px rgba(52, 211, 153, 0.6) !important;
        }
        .flash-weak {
            background-color: #f59e0b !important; /* Amber */
            transform: scale(1.08);
            box-shadow: 0 0 40px rgba(245, 158, 11, 0.5) !important;
        }

        /* Custom Slider */
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
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Visual Metronome</h1>
            <p class="text-center text-gray-400 mb-8">Precise audio-visual timing.</p>

            <div class="flex justify-center mb-10">
                <div id="beat-indicator" class="w-48 h-48 rounded-full bg-gray-900 border-4 border-gray-700 flex items-center justify-center">
                    <span id="bpm-display-big" class="text-5xl font-mono font-bold text-gray-500 select-none">120</span>
                </div>
            </div>

            <div class="space-y-8">
                
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <button id="bpm-minus" class="w-12 h-12 rounded-full bg-gray-700 hover:bg-gray-600 text-xl font-bold transition-colors">-</button>
                        <div class="text-center">
                            <span class="text-sm text-gray-400 uppercase tracking-wider">Tempo</span>
                            <div class="text-xl font-bold text-white"><span id="bpm-val">120</span> BPM</div>
                        </div>
                        <button id="bpm-plus" class="w-12 h-12 rounded-full bg-gray-700 hover:bg-gray-600 text-xl font-bold transition-colors">+</button>
                    </div>
                    <input type="range" id="bpm-slider" min="30" max="250" value="120" class="w-full">
                </div>

                <div class="flex items-center justify-between bg-gray-900 p-4 rounded-lg border border-gray-700">
                    <span class="text-gray-400 font-medium">Beats per Bar</span>
                    <div class="flex items-center gap-3">
                        <button id="beats-minus" class="text-amber-400 hover:text-amber-300 text-2xl font-bold px-2">-</button>
                        <span id="beats-display" class="text-xl font-mono font-bold w-8 text-center">4</span>
                        <button id="beats-plus" class="text-amber-400 hover:text-amber-300 text-2xl font-bold px-2">+</button>
                    </div>
                </div>

                <button id="play-btn" class="w-full py-5 rounded-xl text-xl font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                    START
                </button>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- LOGIC ---
        const playBtn = document.getElementById('play-btn');
        const bpmSlider = document.getElementById('bpm-slider');
        const bpmVal = document.getElementById('bpm-val');
        const bpmDisplayBig = document.getElementById('bpm-display-big');
        const beatsDisplay = document.getElementById('beats-display');
        const beatIndicator = document.getElementById('beat-indicator');

        // Audio Context & Timing
        let audioCtx = null;
        let isPlaying = false;
        let timerID;
        let nextNoteTime = 0.0; // When the next note is due
        let current16thNote = 0; // Current position in the loop
        let tempo = 120.0;
        let lookahead = 25.0; // How frequently to call scheduling function (in milliseconds)
        let scheduleAheadTime = 0.1; // How far ahead to schedule audio (sec)
        
        // Settings
        let beatsPerMeasure = 4;

        // UI Listeners
        bpmSlider.addEventListener('input', (e) => {
            tempo = parseInt(e.target.value);
            updateBpmDisplays();
        });

        document.getElementById('bpm-minus').addEventListener('click', () => {
            bpmSlider.value = parseInt(bpmSlider.value) - 1;
            tempo = parseInt(bpmSlider.value);
            updateBpmDisplays();
        });

        document.getElementById('bpm-plus').addEventListener('click', () => {
            bpmSlider.value = parseInt(bpmSlider.value) + 1;
            tempo = parseInt(bpmSlider.value);
            updateBpmDisplays();
        });

        document.getElementById('beats-minus').addEventListener('click', () => {
            if(beatsPerMeasure > 1) beatsPerMeasure--;
            beatsDisplay.textContent = beatsPerMeasure;
        });

        document.getElementById('beats-plus').addEventListener('click', () => {
            if(beatsPerMeasure < 12) beatsPerMeasure++;
            beatsDisplay.textContent = beatsPerMeasure;
        });

        playBtn.addEventListener('click', () => {
            if (isPlaying) {
                stopMetronome();
            } else {
                startMetronome();
            }
        });

        function updateBpmDisplays() {
            bpmVal.textContent = tempo;
            bpmDisplayBig.textContent = tempo;
        }

        // --- CORE AUDIO SCHEDULING ---
        // Based on the robust "Tale of Two Clocks" method by Chris Wilson

        function nextNote() {
            const secondsPerBeat = 60.0 / tempo;
            nextNoteTime += secondsPerBeat; // Add beat length to last beat time
            current16thNote++;    // Advance the beat number
            if (current16thNote >= beatsPerMeasure) {
                current16thNote = 0;
            }
        }

        function scheduleNote(beatNumber, time) {
            // 1. Create Audio Oscillator
            const osc = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            osc.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            // 2. Pitch Logic (High pitch on Beat 1, Low on others)
            if (beatNumber === 0) {
                osc.frequency.value = 1000; // High click (1kHz)
            } else {
                osc.frequency.value = 800;  // Lower click (800Hz)
            }

            // 3. Envelope (Short, percussive beep)
            gainNode.gain.setValueAtTime(1, time);
            gainNode.gain.exponentialRampToValueAtTime(0.001, time + 0.1);

            osc.start(time);
            osc.stop(time + 0.1);

            // 4. Schedule Visuals
            // We draw the visual at the exact time the audio is scheduled to play
            // We use standard setTimeout, adjusting for the difference between AudioTime and SystemTime
            const timeDifference = time - audioCtx.currentTime;
            
            setTimeout(() => {
                triggerVisual(beatNumber);
            }, timeDifference * 1000);
        }

        function scheduler() {
            // While there are notes that will need to play before the next interval, 
            // schedule them and advance the pointer.
            while (nextNoteTime < audioCtx.currentTime + scheduleAheadTime) {
                scheduleNote(current16thNote, nextNoteTime);
                nextNote();
            }
            timerID = window.setTimeout(scheduler, lookahead);
        }

        async function startMetronome() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            current16thNote = 0;
            nextNoteTime = audioCtx.currentTime + 0.05;
            
            scheduler(); // Start the loop
            
            isPlaying = true;
            playBtn.textContent = "STOP";
            playBtn.classList.replace('bg-emerald-600', 'bg-red-600');
            playBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
            playBtn.classList.replace('shadow-emerald-900/50', 'shadow-red-900/50');
            
            // Dim display slightly to make flash pop
            bpmDisplayBig.classList.replace('text-gray-500', 'text-gray-700');
        }

        function stopMetronome() {
            window.clearTimeout(timerID);
            isPlaying = false;
            
            playBtn.textContent = "START";
            playBtn.classList.replace('bg-red-600', 'bg-emerald-600');
            playBtn.classList.replace('hover:bg-red-500', 'hover:bg-emerald-500');
            playBtn.classList.replace('shadow-red-900/50', 'shadow-emerald-900/50');
            
            bpmDisplayBig.classList.replace('text-gray-700', 'text-gray-500');
        }

        // --- VISUAL FX ---
        function triggerVisual(beatNumber) {
            // Remove previous classes to allow re-triggering
            beatIndicator.classList.remove('flash-strong', 'flash-weak');
            
            // Force Reflow (Magic trick to restart CSS animation instantly)
            void beatIndicator.offsetWidth;

            if (beatNumber === 0) {
                beatIndicator.classList.add('flash-strong');
                bpmDisplayBig.style.color = '#fff'; // White text on downbeat
            } else {
                beatIndicator.classList.add('flash-weak');
                bpmDisplayBig.style.color = '#e5e7eb'; // Light gray on offbeats
            }

            // Cleanup text color after flash
            setTimeout(() => {
                 if(isPlaying) bpmDisplayBig.style.color = '#374151'; // Return to dark gray
            }, 100);
        }
    </script>
</body>
</html>