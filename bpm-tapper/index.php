<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPM Tapper | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Tap Button Animation */
        #tap-btn {
            transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none; /* Prevent text highlighting on rapid tapping */
            touch-action: manipulation; /* Improves touch response on mobile */
        }
        #tap-btn:active, #tap-btn.active-tap {
            transform: scale(0.95);
            background-color: #f59e0b; /* Amber-500 */
            border-color: #f59e0b;
            color: #111827; /* Gray-900 */
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.4);
        }

        /* Pulse animation for the BPM text when a beat is confirmed */
        @keyframes pulse-text {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); color: #fff; }
            100% { transform: scale(1); }
        }
        .pulse {
            animation: pulse-text 0.1s ease-out;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">BPM Tapper</h1>
            <p class="text-center text-gray-400 mb-8">Tap a beat to calculate the tempo.</p>

            <div class="text-center mb-10">
                <div class="h-32 flex items-center justify-center">
                    <span id="bpm-display" class="text-8xl font-mono font-bold text-gray-200">--</span>
                </div>
                <p class="text-xl text-gray-500 font-medium tracking-widest mt-2">BPM</p>
                
                <div class="h-6 mt-4">
                    <p id="status-text" class="text-sm text-emerald-400 font-mono opacity-0 transition-opacity">
                        Average of last <span id="tap-count">0</span> taps
                    </p>
                </div>
            </div>

            <button id="tap-btn" class="w-full h-40 rounded-2xl border-4 border-gray-600 bg-gray-700 hover:bg-gray-600 hover:border-gray-500 flex flex-col items-center justify-center gap-2 group outline-none focus:ring-4 focus:ring-amber-500/30">
                <span class="text-4xl">👆</span>
                <span class="text-2xl font-bold tracking-wider group-hover:text-white">TAP HERE</span>
                <span class="text-xs text-gray-400">(or press Spacebar)</span>
            </button>

            <p class="text-center text-xs text-gray-500 mt-6">
                Auto-resets after 3 seconds of inactivity.
            </p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const tapBtn = document.getElementById('tap-btn');
        const bpmDisplay = document.getElementById('bpm-display');
        const statusText = document.getElementById('status-text');
        const tapCountSpan = document.getElementById('tap-count');
        
        let timestamps = [];
        let resetTimer = null;
        const RESET_DELAY = 3000; // 3 seconds to auto-reset

        // --- CORE LOGIC ---
        function handleTap() {
            const now = Date.now();
            
            // Visual Feedback
            tapBtn.classList.add('active-tap');
            setTimeout(() => tapBtn.classList.remove('active-tap'), 100);

            // If it's been a long time since last tap, reset automatically
            if (timestamps.length > 0) {
                const lastTap = timestamps[timestamps.length - 1];
                if (now - lastTap > RESET_DELAY) {
                    resetTapper();
                }
            }

            // Clear the existing reset timer (user is still tapping)
            clearTimeout(resetTimer);

            // Add timestamp
            timestamps.push(now);

            // Keep only the last 8 taps for a rolling average (keeps it responsive)
            if (timestamps.length > 8) {
                timestamps.shift();
            }

            calculateBPM();

            // Set new reset timer
            resetTimer = setTimeout(resetTapper, RESET_DELAY);
        }

        function calculateBPM() {
            // Need at least 2 taps to calculate an interval
            if (timestamps.length < 2) {
                bpmDisplay.textContent = "--";
                statusText.classList.replace('opacity-100', 'opacity-0');
                return;
            }

            // Calculate intervals between taps
            let intervals = [];
            for (let i = 0; i < timestamps.length - 1; i++) {
                intervals.push(timestamps[i+1] - timestamps[i]);
            }

            // Calculate Average Interval
            const sum = intervals.reduce((a, b) => a + b, 0);
            const avgInterval = sum / intervals.length;

            // Convert to BPM (60,000 ms in a minute)
            const bpm = Math.round(60000 / avgInterval);

            // Update UI
            bpmDisplay.textContent = bpm;
            
            // Pulse effect
            bpmDisplay.classList.remove('pulse');
            void bpmDisplay.offsetWidth; // Trigger reflow
            bpmDisplay.classList.add('pulse');

            // Update stats
            tapCountSpan.textContent = timestamps.length;
            statusText.classList.replace('opacity-0', 'opacity-100');
        }

        function resetTapper() {
            timestamps = [];
            bpmDisplay.textContent = "--";
            statusText.classList.replace('opacity-100', 'opacity-0');
        }

        // --- EVENT LISTENERS ---
        
        // Mouse/Touch
        tapBtn.addEventListener('mousedown', (e) => {
            e.preventDefault(); // Prevent focus highlight on click
            handleTap();
        });
        
        // Touch support (faster response than click)
        tapBtn.addEventListener('touchstart', (e) => {
            e.preventDefault(); // Prevent scrolling/zooming while tapping
            handleTap();
        });

        // Keyboard support (Spacebar / Enter)
        document.addEventListener('keydown', (e) => {
            if ((e.code === 'Space' || e.code === 'Enter') && !e.repeat) {
                // Only trigger if we aren't focused on an input (rare here, but good practice)
                if (document.activeElement.tagName !== 'INPUT') {
                    e.preventDefault(); // Stop page scrolling
                    handleTap();
                }
            }
        });

    </script>
</body>
</html>