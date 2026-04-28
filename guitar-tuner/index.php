<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Tuner | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        #tuning-meter {
            position: relative;
            height: 28px;
            background-color: #1f2937;
            border-radius: 9999px;
            overflow: hidden;
            border: 2px solid #374151;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
        }
        #tuning-needle {
            position: absolute;
            top: 0;
            left: 50%;
            width: 4px;
            height: 100%;
            background-color: #f59e0b; /* Default Amber */
            transform: translateX(-50%);
            border-radius: 2px;
            z-index: 10;
            transition: left 0.1s linear, background-color 0.2s;
        }
        #center-line {
            position: absolute;
            left: 50%;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #4b5563;
            transform: translateX(-50%);
            z-index: 1;
        }
        
        /* Volume Meter */
        #volume-meter-container {
            width: 100%;
            height: 4px;
            background-color: #111827;
            border-radius: 999px;
            overflow: hidden;
            margin-top: 12px;
        }
        #volume-bar {
            height: 100%;
            width: 0%;
            background-color: #3b82f6;
            transition: width 0.05s linear;
        }

        /* Status Styles */
        .status-perfect { color: #34d399; text-shadow: 0 0 15px rgba(52, 211, 153, 0.6); }
        .status-flat { color: #fbbf24; }
        .status-sharp { color: #f87171; } /* Red for sharp */

        .string-btn { transition: all 0.2s ease-in-out; }
        .string-btn.active {
            background-color: #f59e0b;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
        }

        /* Glow Effect for Container */
        .glow-success {
             border-color: #34d399 !important;
             box-shadow: 0 0 40px rgba(52, 211, 153, 0.2), inset 0 0 20px rgba(52, 211, 153, 0.1);
             background-color: rgba(6, 78, 59, 0.4);
        }
        .glow-warn {
             border-color: #fbbf24 !important;
             box-shadow: 0 0 20px rgba(251, 191, 36, 0.1);
        }
        
        #tuner-display { transition: all 0.3s ease; }
        
        /* Slider */
        input[type=range] {
            -webkit-appearance: none; background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 16px; width: 16px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -6px; 
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-sm mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Guitar Tuner</h1>
            <p id="sub-heading" class="text-center text-gray-400 mb-6 text-sm">Standard Tuning (EADGBe)</p>

            <div id="string-select" class="grid grid-cols-6 gap-2 mb-6 opacity-50 transition-opacity duration-300">
                </div>

            <div id="tuner-display" class="bg-gray-900 rounded-xl p-6 mb-6 text-center border border-gray-700 relative">
                
                <div class="flex items-center justify-center h-24">
                     <p class="text-8xl font-mono font-bold text-gray-200" id="note-name">--</p>
                     <p class="text-3xl font-mono font-semibold text-gray-500 ml-2 pt-6" id="octave"></p>
                </div>
                
                <p class="text-sm text-gray-500 font-mono mt-2 mb-4" id="note-freq">0.00 Hz</p>
                
                <div id="tuning-meter" class="w-full">
                    <div id="center-line"></div>
                    <div id="tuning-needle"></div>
                </div>

                <div id="volume-meter-container">
                    <div id="volume-bar"></div>
                </div>
                
                <div class="h-8 mt-6 flex items-center justify-center">
                    <p class="text-xl font-bold tracking-wider uppercase" id="tuning-status">&nbsp;</p>
                </div>
            </div>

            <div class="mb-8 px-2">
                <div class="flex justify-between text-xs text-gray-400 mb-2">
                    <span>Mic Boost</span>
                    <span id="gain-val">100%</span>
                </div>
                <input type="range" id="gain-slider" min="1" max="5" step="0.1" value="1" class="w-full">
            </div>

            <button id="toggle-tuner-btn" class="w-full py-4 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                START LISTENING
            </button>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('toggle-tuner-btn');
        const tunerDisplay = document.getElementById('tuner-display');
        const noteNameDisplay = document.getElementById('note-name');
        const octaveDisplay = document.getElementById('octave');
        const noteFreqDisplay = document.getElementById('note-freq');
        const tuningNeedle = document.getElementById('tuning-needle');
        const tuningStatus = document.getElementById('tuning-status');
        const subHeading = document.getElementById('sub-heading');
        const stringSelectContainer = document.getElementById('string-select');
        const volumeBar = document.getElementById('volume-bar');
        const gainSlider = document.getElementById('gain-slider');
        const gainValText = document.getElementById('gain-val');

        let audioCtx;
        let analyser;
        let gainNode;
        let mediaStreamSource;
        let isTunerOn = false;
        let animationFrameId;
        
        let smoothedCents = 0;
        
        // --- LOCKING VARIABLES ---
        let uiLockedUntil = 0; // Timestamp for when the UI can update again
        const LOCK_DURATION_PERFECT = 2500; // 2.5 seconds
        const LOCK_DURATION_WARN = 1000;    // 1.0 second (Updated)
        const CONFIDENCE_THRESHOLD = 0.65;  // 65% Certainty (Updated)

        // Meter Settings
        const METER_RANGE_CENTS = 30; // Zoomed in

        const noteStrings = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
        const standardTuning = [
            { note: 'E', octave: 2, freq: 82.41, displayName: 'E' },
            { note: 'A', octave: 2, freq: 110.00, displayName: 'A' },
            { note: 'D', octave: 3, freq: 146.83, displayName: 'D' },
            { note: 'G', octave: 3, freq: 196.00, displayName: 'G' },
            { note: 'B', octave: 3, freq: 246.94, displayName: 'B' },
            { note: 'E', octave: 4, freq: 329.63, displayName: 'e' }
        ];

        // --- SETUP ---
        gainSlider.addEventListener('input', (e) => {
            const val = parseFloat(e.target.value);
            gainValText.textContent = `${Math.round(val * 100)}%`;
            if (gainNode) gainNode.gain.value = val;
        });

        function setupStringButtons() {
            stringSelectContainer.innerHTML = ''; 
            standardTuning.forEach(target => {
                const button = document.createElement('button');
                button.className = 'string-btn p-2 text-lg font-bold rounded bg-gray-700 text-gray-300';
                button.textContent = target.displayName;
                stringSelectContainer.appendChild(button);
            });
        }
        
        function highlightActiveString(activeTarget) {
            const buttons = document.querySelectorAll('.string-btn');
            buttons.forEach(button => {
                if (activeTarget && button.textContent === activeTarget.displayName) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }

        // --- PITCH DETECTION ALGORITHM ---
        function findPitch(buf, sampleRate) {
            const bufferSize = buf.length;
            let rms = 0;
            buf.forEach(val => rms += val * val);
            rms = Math.sqrt(rms / bufferSize);

            // Volume Visual
            const volPercent = Math.min(100, (rms * 5) * 100); 
            volumeBar.style.width = `${volPercent}%`;
            
            // 1. RMS Threshold (Noise Gate)
            if (rms < 0.01) return -1; 

            // 2. Autocorrelation
            let r = new Float32Array(bufferSize).map((_, i) => {
                let sum = 0;
                for (let j = 0; j < bufferSize - i; j++) sum += buf[j] * buf[j + i];
                return sum;
            });

            // Find Peak
            let d = 0;
            while (r[d] > r[d + 1]) d++;
            let maxval = -1, maxpos = -1;
            for (let i = d; i < bufferSize; i++) {
                if (r[i] > maxval) { maxval = r[i]; maxpos = i; }
            }
            let T0 = maxpos;
            
            // 3. CONFIDENCE CHECK
            // r[0] is the maximum possible correlation (perfect match at 0 lag)
            const confidence = maxval / r[0];
            
            if (confidence < CONFIDENCE_THRESHOLD) return -1;

            // Parabolic Interpolation for precision
            const [x1, x2, x3] = [r[T0 - 1], r[T0], r[T0 + 1]];
            const a = (x1 + x3 - 2 * x2) / 2;
            const b = (x3 - x1) / 2;
            if (a) T0 = T0 - b / (2 * a);
            
            return sampleRate / T0;
        }
        
        function frequencyToNote(frequency) {
            const noteNum = 12 * (Math.log(frequency / 440) / Math.log(2));
            return Math.round(noteNum) + 69;
        }

        function lerp(start, end, amt) {
            return (1 - amt) * start + amt * end;
        }

        // --- UPDATE LOOP ---
        function updateTuner() {
            if (!analyser) return;

            const buffer = new Float32Array(analyser.fftSize);
            analyser.getFloatTimeDomainData(buffer);
            const pitch = findPitch(buffer, audioCtx.sampleRate);
            
            const now = Date.now();
            const isLocked = now < uiLockedUntil;

            // --- 1. HANDLE SILENCE / LOW CONFIDENCE ---
            if (pitch === -1) {
                // If the UI is NOT locked, we can clear the display
                if (!isLocked) {
                    smoothedCents = lerp(smoothedCents, 0, 0.05); 
                    tuningNeedle.style.left = `50%`;
                    
                    noteNameDisplay.textContent = "--";
                    octaveDisplay.textContent = '';
                    noteFreqDisplay.textContent = "Listening...";
                    tuningStatus.innerHTML = "&nbsp;";
                    
                    tunerDisplay.classList.remove('glow-success', 'glow-warn');
                    tuningNeedle.style.backgroundColor = '#f59e0b';
                    highlightActiveString(null);
                    if (isTunerOn) subHeading.textContent = "Play a string...";
                }
                // If locked, we do nothing (keep showing the result)
                animationFrameId = requestAnimationFrame(updateTuner);
                return;
            }

            // --- 2. HANDLE NOTE DETECTION ---
            
            // Find closest string
            let closestTarget = null;
            let smallestCentsDiff = Infinity;
            let currentRawCents = 0;

            standardTuning.forEach(targetNote => {
                const centsDiff = 1200 * Math.log2(pitch / targetNote.freq);
                if (Math.abs(centsDiff) < Math.abs(smallestCentsDiff)) {
                    smallestCentsDiff = centsDiff;
                    closestTarget = targetNote;
                    currentRawCents = centsDiff;
                }
            });

            // Note Info
            const detectedNoteNum = frequencyToNote(pitch);
            const detectedNoteName = noteStrings[detectedNoteNum % 12];
            const detectedOctave = Math.floor(detectedNoteNum / 12) - 1;

            // --- 3. UI UPDATES ---
            
            // Always update the needle & note name (Live Feedback)
            // Even if text is locked, seeing the needle move is vital for tuning
            if (!isLocked) {
                noteNameDisplay.textContent = detectedNoteName;
                octaveDisplay.textContent = detectedOctave;
                noteFreqDisplay.textContent = `${pitch.toFixed(1)} Hz`;
            }

            if (closestTarget && Math.abs(currentRawCents) < 100) {
                
                highlightActiveString(closestTarget);
                smoothedCents = lerp(smoothedCents, currentRawCents, 0.2); // Smooth movement

                // Map cents to needle %
                const clamp = (val, min, max) => Math.min(Math.max(val, min), max);
                const visualCents = clamp(smoothedCents, -METER_RANGE_CENTS, METER_RANGE_CENTS);
                const percentOffset = (visualCents / METER_RANGE_CENTS) * 50; 
                tuningNeedle.style.left = `${50 + percentOffset}%`;

                // --- 4. STATUS LOCKING LOGIC ---
                // Only change status text if we aren't locked
                if (!isLocked) {
                    
                    if (Math.abs(smoothedCents) < 5) {
                        // PERFECT
                        tuningStatus.textContent = "PERFECT";
                        tuningStatus.className = 'text-2xl font-bold tracking-widest status-perfect';
                        
                        tuningNeedle.style.backgroundColor = '#34d399'; 
                        tunerDisplay.classList.add('glow-success');
                        tunerDisplay.classList.remove('glow-warn');
                        
                        // LOCK FOR 2.5s
                        uiLockedUntil = now + LOCK_DURATION_PERFECT;

                    } else {
                        // OFF PITCH
                        tuningNeedle.style.backgroundColor = '#f59e0b';
                        tunerDisplay.classList.remove('glow-success');
                        
                        if(smoothedCents < 0) {
                             tuningStatus.textContent = "TOO LOW";
                             tuningStatus.className = 'text-xl font-bold status-flat';
                             tunerDisplay.classList.add('glow-warn');
                             // LOCK FOR 1.0s
                             uiLockedUntil = now + LOCK_DURATION_WARN;
                        } else {
                             tuningStatus.textContent = "TOO HIGH";
                             tuningStatus.className = 'text-xl font-bold status-sharp';
                             tunerDisplay.classList.add('glow-warn');
                             // LOCK FOR 1.0s
                             uiLockedUntil = now + LOCK_DURATION_WARN;
                        }
                    }
                }

            } else {
                // Not close to any string
                if(!isLocked) {
                    highlightActiveString(null);
                    tuningNeedle.style.left = `50%`;
                    tuningNeedle.style.backgroundColor = '#4b5563'; 
                    tuningStatus.innerHTML = "&nbsp;";
                    tunerDisplay.classList.remove('glow-success', 'glow-warn');
                }
            }

            animationFrameId = requestAnimationFrame(updateTuner);
        }

        // --- CONTROL ---
        async function startTuner() {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();

                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: {
                        echoCancellation: false,
                        autoGainControl: false,
                        noiseSuppression: false, // We handle noise manually
                        latency: 0
                    }
                });

                if (audioCtx.state === 'suspended') {
                    await audioCtx.resume();
                }

                // Gain Node
                gainNode = audioCtx.createGain();
                gainNode.gain.value = parseFloat(gainSlider.value); 

                analyser = audioCtx.createAnalyser();
                analyser.fftSize = 4096; 
                
                mediaStreamSource = audioCtx.createMediaStreamSource(stream);
                mediaStreamSource.connect(gainNode);
                gainNode.connect(analyser);

                isTunerOn = true;
                toggleButton.textContent = "STOP LISTENING";
                toggleButton.classList.replace('bg-emerald-600', 'bg-red-600');
                toggleButton.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
                toggleButton.classList.replace('shadow-emerald-900/50', 'shadow-red-900/50');
                
                subHeading.textContent = "Play a string to begin tuning...";
                stringSelectContainer.classList.remove('opacity-50');
                
                updateTuner();
            } catch (err) {
                console.error(err);
                alert("Microphone access failed. Please ensure you are using HTTPS or localhost.");
            }
        }

        function stopTuner() {
            if (mediaStreamSource) mediaStreamSource.mediaStream.getTracks().forEach(track => track.stop());
            if (audioCtx) audioCtx.close();
            
            cancelAnimationFrame(animationFrameId);
            isTunerOn = false;
            
            toggleButton.textContent = "START LISTENING";
            toggleButton.classList.replace('bg-red-600', 'bg-emerald-600');
            toggleButton.classList.replace('hover:bg-red-500', 'hover:bg-emerald-500');
            toggleButton.classList.replace('shadow-red-900/50', 'shadow-emerald-900/50');
            
            subHeading.textContent = "Standard Tuning (EADGBe)";
            stringSelectContainer.classList.add('opacity-50');
            highlightActiveString(null);
            
            tunerDisplay.classList.remove('glow-success', 'glow-warn');
            noteNameDisplay.textContent = "--";
            noteFreqDisplay.textContent = "0.00 Hz";
            tuningStatus.innerHTML = "&nbsp;";
            tuningNeedle.style.left = `50%`;
            volumeBar.style.width = '0%';
        }

        toggleButton.addEventListener('click', () => isTunerOn ? stopTuner() : startTuner());
        setupStringButtons();
    });
    </script>
</body>
</html>