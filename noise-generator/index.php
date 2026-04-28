<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noise Generator | Dibber Lab</title>
    
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

        /* Color Buttons */
        .color-btn {
            transition: all 0.2s;
        }
        .color-btn.active {
            transform: scale(1.05);
            border-color: white;
            box-shadow: 0 0 15px rgba(255,255,255,0.2);
        }

        /* Static Visualizer */
        #static-canvas {
            width: 100%;
            height: 100%;
            opacity: 0.3;
            mix-blend-mode: overlay;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Noise Generator</h1>
            <p class="text-center text-gray-400 mb-8">Focus, relax, or sleep with colored noise.</p>

            <div class="relative w-full h-48 bg-gray-900 rounded-xl border border-gray-700 mb-8 overflow-hidden flex items-center justify-center">
                <canvas id="static-canvas"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span id="status-text" class="text-2xl font-bold text-gray-600 tracking-widest uppercase">OFF</span>
                </div>
            </div>

            <div class="space-y-8">
                
                <div class="grid grid-cols-3 gap-4">
                    <button class="color-btn active p-4 rounded-xl border-2 border-gray-600 bg-gray-700 hover:bg-gray-600 flex flex-col items-center gap-2" data-type="white">
                        <div class="w-8 h-8 rounded-full bg-white border border-gray-400 shadow-sm"></div>
                        <span class="font-bold text-sm">White</span>
                    </button>
                    
                    <button class="color-btn p-4 rounded-xl border-2 border-gray-600 bg-gray-700 hover:bg-gray-600 flex flex-col items-center gap-2" data-type="pink">
                        <div class="w-8 h-8 rounded-full bg-pink-400 border border-pink-600 shadow-sm"></div>
                        <span class="font-bold text-sm">Pink</span>
                    </button>
                    
                    <button class="color-btn p-4 rounded-xl border-2 border-gray-600 bg-gray-700 hover:bg-gray-600 flex flex-col items-center gap-2" data-type="brown">
                        <div class="w-8 h-8 rounded-full bg-yellow-900 border border-yellow-950 shadow-sm"></div>
                        <span class="font-bold text-sm">Brown</span>
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
                    PLAY NOISE
                </button>
            </div>

            <p id="noise-desc" class="text-center text-xs text-gray-500 mt-6 italic">
                White noise contains all frequencies at equal intensity.
            </p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const playBtn = document.getElementById('play-btn');
        const volSlider = document.getElementById('vol-slider');
        const volDisplay = document.getElementById('vol-display');
        const colorBtns = document.querySelectorAll('.color-btn');
        const noiseDesc = document.getElementById('noise-desc');
        const canvas = document.getElementById('static-canvas');
        const ctx = canvas.getContext('2d');
        const statusText = document.getElementById('status-text');

        let audioCtx;
        let noiseSource;
        let gainNode;
        let isPlaying = false;
        let currentType = 'white';
        let currentVol = 0.5;
        let animationId;

        const descriptions = {
            white: "White noise contains all frequencies at equal intensity. Good for blocking loud distractions.",
            pink: "Pink noise is deeper than white noise (balanced for human hearing). Great for focus and melting away background chatter.",
            brown: "Brown noise is deep and rumbling (like heavy rain or thunder). Excellent for sleep and relaxation."
        };

        // --- AUDIO GENERATION LOGIC ---
        // We generate a 5-second buffer and loop it. This saves CPU compared to generating noise in real-time script processors.
        function createNoiseBuffer(type) {
            const bufferSize = audioCtx.sampleRate * 5; // 5 seconds
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);

            if (type === 'white') {
                for (let i = 0; i < bufferSize; i++) {
                    data[i] = Math.random() * 2 - 1;
                }
            } 
            else if (type === 'pink') {
                // Paul Kellett's refined method for Pink Noise generation
                let b0, b1, b2, b3, b4, b5, b6;
                b0 = b1 = b2 = b3 = b4 = b5 = b6 = 0.0;
                for (let i = 0; i < bufferSize; i++) {
                    const white = Math.random() * 2 - 1;
                    b0 = 0.99886 * b0 + white * 0.0555179;
                    b1 = 0.99332 * b1 + white * 0.0750759;
                    b2 = 0.96900 * b2 + white * 0.1538520;
                    b3 = 0.86650 * b3 + white * 0.3104856;
                    b4 = 0.55000 * b4 + white * 0.5329522;
                    b5 = -0.7616 * b5 - white * 0.0168980;
                    data[i] = b0 + b1 + b2 + b3 + b4 + b5 + b6 + white * 0.5362;
                    data[i] *= 0.11; // compensate for gain
                    b6 = white * 0.115926;
                }
            } 
            else if (type === 'brown') {
                let lastOut = 0;
                for (let i = 0; i < bufferSize; i++) {
                    const white = Math.random() * 2 - 1;
                    // Leaky integrator
                    lastOut = (lastOut + (0.02 * white)) / 1.02;
                    data[i] = lastOut * 3.5; // compensate for gain
                }
            }

            return buffer;
        }

        async function startNoise() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            stopNoise(); // Stop any existing source

            // Create Graph
            noiseSource = audioCtx.createBufferSource();
            gainNode = audioCtx.createGain();

            // Generate Buffer based on selected type
            noiseSource.buffer = createNoiseBuffer(currentType);
            noiseSource.loop = true;

            // Apply Volume with ramp
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(currentVol, audioCtx.currentTime + 0.5);

            // Connect
            noiseSource.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            noiseSource.start();
            isPlaying = true;
            updateUIState();
            startVisualizer();
        }

        function stopNoise() {
            if (noiseSource && isPlaying) {
                // Fade out
                gainNode.gain.setValueAtTime(gainNode.gain.value, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.5);
                
                noiseSource.stop(audioCtx.currentTime + 0.5);
                isPlaying = false;
                updateUIState();
                stopVisualizer();
            }
        }

        function updateUIState() {
            if (isPlaying) {
                playBtn.textContent = "STOP NOISE";
                playBtn.classList.replace('bg-emerald-600', 'bg-red-600');
                playBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
                playBtn.classList.replace('shadow-emerald-900/50', 'shadow-red-900/50');
                statusText.textContent = "ON AIR";
                statusText.className = "text-2xl font-bold text-emerald-500 tracking-widest uppercase animate-pulse";
            } else {
                playBtn.textContent = "PLAY NOISE";
                playBtn.classList.replace('bg-red-600', 'bg-emerald-600');
                playBtn.classList.replace('hover:bg-red-500', 'hover:bg-emerald-500');
                playBtn.classList.replace('shadow-red-900/50', 'shadow-emerald-900/50');
                statusText.textContent = "OFF";
                statusText.className = "text-2xl font-bold text-gray-600 tracking-widest uppercase";
            }
        }

        // --- CANVAS VISUALIZER ---
        // Simulates TV Static
        function startVisualizer() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
            
            function draw() {
                const w = canvas.width;
                const h = canvas.height;
                const idata = ctx.createImageData(w, h);
                const buffer32 = new Uint32Array(idata.data.buffer);
                const len = buffer32.length;

                for (let i = 0; i < len; i++) {
                    if (Math.random() < 0.1) { // 10% chance to draw a pixel
                         // Random gray/white pixel
                         buffer32[i] = ((255 * Math.random()) | 0) << 24; 
                    }
                }

                ctx.putImageData(idata, 0, 0);
                animationId = requestAnimationFrame(draw);
            }
            draw();
        }

        function stopVisualizer() {
            cancelAnimationFrame(animationId);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        // --- LISTENERS ---
        
        // 1. Color Select
        colorBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update UI
                colorBtns.forEach(b => {
                    b.classList.remove('active');
                    b.classList.replace('bg-gray-600', 'bg-gray-700'); // reset bg
                });
                btn.classList.add('active');
                
                currentType = btn.dataset.type;
                noiseDesc.textContent = descriptions[currentType];

                // If playing, seamlessly switch
                if (isPlaying) {
                    startNoise();
                }
            });
        });

        // 2. Volume
        volSlider.addEventListener('input', (e) => {
            currentVol = parseFloat(e.target.value);
            volDisplay.textContent = Math.round(currentVol * 100) + '%';
            if (isPlaying && gainNode) {
                gainNode.gain.setTargetAtTime(currentVol, audioCtx.currentTime, 0.1);
            }
        });

        // 3. Play
        playBtn.addEventListener('click', () => {
            if (isPlaying) stopNoise();
            else startNoise();
        });

        // Resize canvas on window resize
        window.addEventListener('resize', () => {
             if(isPlaying) {
                canvas.width = canvas.parentElement.offsetWidth;
                canvas.height = canvas.parentElement.offsetHeight;
             }
        });

    </script>
</body>
</html>