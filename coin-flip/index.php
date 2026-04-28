<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coin Flip | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; perspective: 1200px; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* --- 3D COIN STYLES --- */
        #coin-scene {
            width: 200px;
            height: 200px;
            perspective: 1000px;
            margin-bottom: 3rem;
            cursor: pointer;
        }

        #coin {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 3s cubic-bezier(0.25, 1, 0.5, 1); /* Realistic deceleration */
        }

        .coin-face {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backface-visibility: hidden; /* Hides the back when facing away */
            box-shadow: inset 0 0 20px rgba(0,0,0,0.3);
            border: 4px solid #b45309; /* Dark Amber border */
        }

        /* HEADS (Front) */
        .face-heads {
            background: radial-gradient(circle at 30% 30%, #fcd34d, #f59e0b);
            transform: translateZ(5px); /* Push out for thickness */
        }

        /* TAILS (Back) */
        .face-tails {
            background: radial-gradient(circle at 30% 30%, #e5e7eb, #9ca3af); /* Silver/Gray */
            transform: rotateY(180deg) translateZ(5px);
        }

        /* The Edge (Fake Thickness) */
        .side {
            position: absolute;
            width: 5px; /* Thickness */
            height: 100%; /* Actually circumference segment via JS usually, but here using shadow hack */
            /* Advanced CSS 3D cylinder is heavy. We use a shadow hack on the faces. */
        }
        
        /* Shadow for depth */
        #coin::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 80%; height: 20%;
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            transform: translate(-50%, 120px) rotateX(90deg);
            filter: blur(10px);
            transition: opacity 0.5s;
            opacity: 0.5;
        }
        #coin.flipping::before { opacity: 0.2; transform: translate(-50%, 160px) rotateX(90deg) scale(0.8); }

        /* Text/Icons on Coin */
        .coin-label {
            font-weight: 900;
            font-size: 2rem;
            text-shadow: 0 2px 0 rgba(255,255,255,0.3), 0 -1px 0 rgba(0,0,0,0.2);
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .text-heads { color: #78350f; }
        .text-tails { color: #374151; }

        /* History items */
        .history-item {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-lg mx-auto flex flex-col items-center">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Coin Flip</h1>
                <p class="text-center text-gray-400">Heads or Tails? Leave it to fate.</p>
            </div>

            <div id="coin-scene" onclick="flipCoin()">
                <div id="coin">
                    
                    <div class="coin-face face-heads">
                        <div class="border-4 border-amber-600/30 rounded-full w-40 h-40 flex items-center justify-center">
                            <span class="coin-label text-heads">Heads</span>
                        </div>
                    </div>

                    <div class="coin-face face-tails">
                        <div class="border-4 border-gray-500/30 rounded-full w-40 h-40 flex items-center justify-center">
                            <span class="coin-label text-tails">Tails</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-full flex justify-center mb-10">
                <button id="flip-btn" class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-xl py-4 px-12 rounded-full shadow-lg transition-transform hover:-translate-y-1 active:scale-95 flex items-center gap-2" onclick="flipCoin()">
                    FLIP COIN
                </button>
            </div>

            <div class="w-full bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl">
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-900 rounded-xl p-4 text-center border-b-4 border-amber-500">
                        <span class="text-xs font-bold text-gray-500 uppercase">Heads</span>
                        <div id="count-heads" class="text-3xl font-black text-white mt-1">0</div>
                        <div id="pct-heads" class="text-xs text-gray-400">0%</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-4 text-center border-b-4 border-gray-400">
                        <span class="text-xs font-bold text-gray-500 uppercase">Tails</span>
                        <div id="count-tails" class="text-3xl font-black text-white mt-1">0</div>
                        <div id="pct-tails" class="text-xs text-gray-400">0%</div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-700 pt-4">
                    <span class="text-xs font-bold text-gray-500 uppercase">History</span>
                    <button onclick="resetStats()" class="text-xs text-red-400 hover:text-red-300 underline">Reset</button>
                </div>
                <div id="history-bar" class="flex gap-2 mt-3 overflow-x-auto custom-scrollbar pb-2 h-12">
                    <span class="text-xs text-gray-600 italic self-center">No flips yet...</span>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const coin = document.getElementById('coin');
        const flipBtn = document.getElementById('flip-btn');
        const elHeads = document.getElementById('count-heads');
        const elTails = document.getElementById('count-tails');
        const pctHeads = document.getElementById('pct-heads');
        const pctTails = document.getElementById('pct-tails');
        const historyBar = document.getElementById('history-bar');

        // State
        let stats = { heads: 0, tails: 0 };
        let isFlipping = false;
        let audioCtx = null;
        let currentRotation = 0; // Keep track to spin continuously in one direction

        // --- CORE LOGIC ---

        function flipCoin() {
            if (isFlipping) return;
            isFlipping = true;
            flipBtn.disabled = true;
            flipBtn.classList.add('opacity-50', 'cursor-not-allowed');

            playFlipSound();

            // Determine Result (0 or 1)
            const result = Math.random() < 0.5 ? 'heads' : 'tails';
            
            // Calculate Spin
            // Min spins: 5 (1800 deg)
            // If Heads (0 deg visual), we want a multiple of 360 (e.g. 1800, 2160)
            // If Tails (180 deg visual), we want multiple of 360 + 180 (e.g. 1980, 2340)
            
            const minSpins = 5;
            const baseRotation = 360 * minSpins; 
            
            let targetRotation = 0;
            
            if (result === 'heads') {
                targetRotation = currentRotation + baseRotation + (360 - (currentRotation % 360));
            } else {
                targetRotation = currentRotation + baseRotation + (180 + 360 - (currentRotation % 360));
                // Fix modulo math: ensuring we land exactly on 180 relative to 0
                // Simplify: just add enough full rotations to land on the correct face offset
                // Actually, CSS handles relative rotation well.
                // Just ensuring even or odd number of half-rotations.
            }

            // Adjust math to ensure specific landing
            // Current Rotation is tracked.
            // If we want heads: Result must be N * 360.
            // If we want tails: Result must be N * 360 + 180.
            
            // Add a large random amount of full rotations + the offset
            const extraSpins = Math.floor(Math.random() * 5) + 5; // 5 to 10 spins
            const outcomeOffset = result === 'heads' ? 0 : 180;
            
            // We need to move from current to next.
            // Let's reset the rotation calculation to be absolute relative to 0 for simplicity in logic,
            // but CSS transition handles the delta smoothly.
            
            // Calculate NEXT total rotation
            // We want (current + lots) such that (total % 360) == outcomeOffset
            // total = current + (360 - current%360) + (360*spins) + outcomeOffset
            
            const nextRotation = currentRotation + (360 - (currentRotation % 360)) + (360 * extraSpins) + outcomeOffset;
            
            // Add some randomness to X axis for realism (wobble)
            const tilt = Math.floor(Math.random() * 20 - 10); // -10 to 10 deg tilt

            coin.style.transform = `rotateY(${nextRotation}deg) rotateX(${tilt}deg)`;
            coin.classList.add('flipping');

            // Wait for animation (3s)
            setTimeout(() => {
                isFlipping = false;
                currentRotation = nextRotation;
                coin.classList.remove('flipping');
                flipBtn.disabled = false;
                flipBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                
                // Play landing sound
                playLandSound();
                
                // Update Data
                updateStats(result);
            }, 3000);
        }

        function updateStats(result) {
            stats[result]++;
            
            const total = stats.heads + stats.tails;
            
            elHeads.textContent = stats.heads;
            elTails.textContent = stats.tails;
            
            pctHeads.textContent = Math.round((stats.heads / total) * 100) + "%";
            pctTails.textContent = Math.round((stats.tails / total) * 100) + "%";

            addToHistory(result);
        }

        function addToHistory(result) {
            // Remove "empty" text if exists
            if (historyBar.firstElementChild && historyBar.firstElementChild.tagName === 'SPAN') {
                historyBar.innerHTML = '';
            }

            const badge = document.createElement('div');
            badge.className = `history-item w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-xs shadow-md border ${result === 'heads' ? 'bg-amber-500 border-amber-300 text-amber-900' : 'bg-gray-400 border-gray-300 text-gray-900'}`;
            badge.textContent = result === 'heads' ? 'H' : 'T';
            
            historyBar.prepend(badge);
        }

        function resetStats() {
            stats = { heads: 0, tails: 0 };
            elHeads.textContent = 0;
            elTails.textContent = 0;
            pctHeads.textContent = "0%";
            pctTails.textContent = "0%";
            historyBar.innerHTML = '<span class="text-xs text-gray-600 italic self-center">No flips yet...</span>';
        }

        // --- AUDIO ENGINE ---
        // Procedural Metallic Sounds
        
        function initAudio() {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
        }

        function playFlipSound() {
            initAudio();
            const t = audioCtx.currentTime;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            
            // Rising pitch "Zwing!"
            osc.frequency.setValueAtTime(600, t);
            osc.frequency.exponentialRampToValueAtTime(1200, t + 0.1);
            
            gain.gain.setValueAtTime(0.1, t);
            gain.gain.exponentialRampToValueAtTime(0.01, t + 0.2);
            
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(t + 0.2);
        }

        function playLandSound() {
            initAudio();
            const t = audioCtx.currentTime;
            
            // Noise burst for impact
            const bufferSize = audioCtx.sampleRate * 0.1; // 0.1s
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);
            for(let i=0; i<bufferSize; i++) data[i] = Math.random() * 2 - 1;
            
            const noise = audioCtx.createBufferSource();
            noise.buffer = buffer;
            const noiseGain = audioCtx.createGain();
            noiseGain.gain.setValueAtTime(0.3, t);
            noiseGain.gain.exponentialRampToValueAtTime(0.01, t + 0.1);
            
            noise.connect(noiseGain);
            noiseGain.connect(audioCtx.destination);
            noise.start();

            // Metallic Ring
            const osc = audioCtx.createOscillator();
            const ringGain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(1200, t);
            ringGain.gain.setValueAtTime(0.05, t);
            ringGain.gain.exponentialRampToValueAtTime(0.001, t + 0.5);
            
            osc.connect(ringGain);
            ringGain.connect(audioCtx.destination);
            osc.start();
            osc.stop(t + 0.5);
        }

        // --- KEYBOARD ---
        document.addEventListener('keydown', (e) => {
            if(e.code === 'Space') {
                e.preventDefault();
                flipCoin();
            }
        });

    </script>
</body>
</html>