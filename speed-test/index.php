<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speed Test | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Gauge Arc */
        .gauge-bg {
            fill: none;
            stroke: #1f2937; /* Gray-800 */
            stroke-width: 8;
            stroke-linecap: round;
        }
        .gauge-progress {
            fill: none;
            stroke: #f59e0b; /* Amber-500 */
            stroke-width: 8;
            stroke-linecap: round;
            stroke-dasharray: 251; /* Circumference of r=40 (approx) */
            stroke-dashoffset: 251;
            transition: stroke-dashoffset 0.5s ease-out, stroke 0.3s;
            transform: rotate(135deg); /* Start at bottom left */
            transform-origin: 50% 50%;
        }

        /* Color States */
        .speed-slow { stroke: #ef4444; color: #ef4444; }
        .speed-med { stroke: #f59e0b; color: #f59e0b; }
        .speed-fast { stroke: #10b981; color: #10b981; }

        /* Button Glow */
        .start-btn {
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
            transition: all 0.2s;
        }
        .start-btn:hover {
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.4);
            transform: scale(1.05);
        }
        .start-btn:active { transform: scale(0.95); }
        .start-btn:disabled { 
            opacity: 0.5; 
            cursor: not-allowed; 
            transform: none; 
            box-shadow: none;
            filter: grayscale(1);
        }

        /* Graph Canvas */
        #graph-canvas {
            width: 100%;
            height: 100px;
        }

        /* Progress Bar for Duration */
        #duration-bar {
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-lg mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Speed Estimator</h1>
                <p class="text-center text-gray-400 text-sm">30-Second Download Stress Test</p>
            </div>

            <div class="relative w-64 h-64 mb-8">
                <svg viewBox="0 0 100 100" class="w-full h-full">
                    <circle cx="50" cy="50" r="40" class="gauge-bg" stroke-dasharray="251" stroke-dashoffset="63" transform="rotate(135 50 50)"></circle>
                    <circle cx="50" cy="50" r="40" id="gauge-bar" class="gauge-progress" stroke-dashoffset="251"></circle>
                </svg>

                <div class="absolute inset-0 flex flex-col items-center justify-center pt-4">
                    <div class="text-5xl font-black mono-font tracking-tighter" id="speed-val">0.0</div>
                    <div class="text-sm font-bold text-gray-500 uppercase mt-1">Mbps</div>
                </div>
            </div>

            <div class="w-full max-w-xs mb-8">
                <div class="h-6 flex justify-between items-end mb-2 text-xs font-bold uppercase text-gray-500">
                    <span id="status-text">Ready</span>
                    <span id="timer-text">30s</span>
                </div>
                <div class="w-full h-1 bg-gray-800 rounded-full overflow-hidden">
                    <div id="duration-bar" class="h-full bg-amber-500 w-0"></div>
                </div>
            </div>

            <button id="start-btn" onclick="startTest()" class="start-btn rounded-full w-24 h-24 bg-gray-800 border-4 border-amber-500 text-amber-500 font-black text-xl flex items-center justify-center mb-10">
                GO
            </button>

            <div class="w-full bg-gray-800 rounded-xl border border-gray-700 p-4 relative overflow-hidden">
                <div class="text-[10px] font-bold text-gray-500 uppercase absolute top-2 left-3">Live Throughput</div>
                <canvas id="graph-canvas" width="400" height="100"></canvas>
            </div>

            <div class="mt-6 text-xs text-center text-gray-600 max-w-xs">
                Note: This tool simulates traffic by downloading test packets. Accuracy depends on your browser and system resources.
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const gaugeBar = document.getElementById('gauge-bar');
        const speedVal = document.getElementById('speed-val');
        const startBtn = document.getElementById('start-btn');
        const statusText = document.getElementById('status-text');
        const timerText = document.getElementById('timer-text');
        const durationBar = document.getElementById('duration-bar');
        const canvas = document.getElementById('graph-canvas');
        const ctx = canvas.getContext('2d');

        // Config
        const TEST_FILE = "https://upload.wikimedia.org/wikipedia/commons/2/2d/Snake_River_%285mb%29.jpg"; 
        const TEST_DURATION = 30000; // 30 seconds
        const MAX_GAUGE_SPEED = 100; // Visual max for gauge (Mbps)

        // State
        let isTesting = false;
        let speedHistory = [];
        let animationId;
        let testEndTime = 0;
        let currentXhr = null; // Store reference to abort if needed

        // --- CORE LOGIC ---

        function startTest() {
            if (isTesting) return;
            isTesting = true;
            
            // Reset UI
            startBtn.disabled = true;
            statusText.textContent = "Initializing...";
            statusText.classList.add('animate-pulse');
            speedVal.textContent = "0.0";
            speedHistory = new Array(60).fill(0); // Clear graph
            setGauge(0);
            
            // Set Timer
            testEndTime = Date.now() + TEST_DURATION;
            
            // Start Loops
            drawGraph();
            updateTimerUI();
            
            // Begin Download Loop
            runDownloadLoop();
        }

        // Loop that handles fetching the file repeatedly until time is up
        function runDownloadLoop() {
            if (!isTesting) return;

            // Check if time is up
            if (Date.now() >= testEndTime) {
                finishTest();
                return;
            }

            statusText.textContent = "Downloading...";
            const uniqueUrl = `${TEST_FILE}?t=${Date.now()}`; // Cache busting
            
            currentXhr = new XMLHttpRequest();
            currentXhr.open("GET", uniqueUrl, true);
            currentXhr.responseType = "blob";

            let lastLoaded = 0;
            let lastTime = Date.now();

            currentXhr.onprogress = (event) => {
                // If time expired mid-download, abort immediately
                if (Date.now() >= testEndTime) {
                    currentXhr.abort();
                    finishTest();
                    return;
                }

                if (event.lengthComputable) {
                    const now = Date.now();
                    const duration = (now - lastTime) / 1000; // Seconds
                    
                    // Throttle updates slightly to avoid infinity spikes
                    if (duration > 0.1) {
                        const loadedChunk = event.loaded - lastLoaded;
                        const bitsLoaded = loadedChunk * 8;
                        const speedBps = bitsLoaded / duration;
                        const speedMbps = (speedBps / 1024 / 1024); // Don't round yet for graph smoothing

                        updateSpeedUI(speedMbps);
                        
                        lastLoaded = event.loaded;
                        lastTime = now;
                    }
                }
            };

            // On completion of one file, start the next one immediately (Loop)
            currentXhr.onload = () => {
                if(isTesting) runDownloadLoop();
            };

            // Handle Errors
            currentXhr.onerror = () => {
                // If error, try again after short delay unless time is up
                if(isTesting) setTimeout(runDownloadLoop, 1000);
            };

            currentXhr.send();
        }

        function updateSpeedUI(mbps) {
            // Update Text
            speedVal.textContent = mbps.toFixed(1);
            
            // Update Gauge
            let percent = mbps / MAX_GAUGE_SPEED;
            if (percent > 1) percent = 1;
            
            // Color Logic
            gaugeBar.className = "gauge-progress";
            if (mbps < 10) gaugeBar.classList.add('speed-slow');
            else if (mbps < 50) gaugeBar.classList.add('speed-med');
            else gaugeBar.classList.add('speed-fast');

            setGauge(percent);

            // Update Graph Data
            speedHistory.push(mbps);
            if (speedHistory.length > 60) speedHistory.shift();
        }

        function updateTimerUI() {
            if (!isTesting) return;

            const now = Date.now();
            const remaining = Math.max(0, testEndTime - now);
            const seconds = Math.ceil(remaining / 1000);
            
            timerText.textContent = `${seconds}s`;
            
            // Progress Bar
            const total = TEST_DURATION;
            const elapsed = total - remaining;
            const pct = (elapsed / total) * 100;
            durationBar.style.width = `${pct}%`;

            if (remaining > 0) {
                requestAnimationFrame(updateTimerUI);
            }
        }

        function setGauge(percent) {
            // 251 is full offset (empty). Range is 188.
            const offset = 251 - (188 * percent);
            gaugeBar.style.strokeDashoffset = offset;
        }

        function finishTest() {
            isTesting = false;
            
            // Calculate Average from recent history for final result
            // Filter out zeros or initial spikes
            const validHistory = speedHistory.filter(s => s > 0);
            const sum = validHistory.reduce((a, b) => a + b, 0);
            const avg = validHistory.length ? (sum / validHistory.length).toFixed(1) : "0.0";
            
            speedVal.textContent = avg;

            startBtn.disabled = false;
            startBtn.innerHTML = "↻";
            
            statusText.classList.remove('animate-pulse');
            statusText.textContent = `Done: Avg ${avg} Mbps`;
            statusText.classList.add('text-white');
            
            timerText.textContent = "0s";
            durationBar.style.width = "100%";
            
            // Stop graph
            cancelAnimationFrame(animationId);
            drawGraph();
        }

        // --- VISUALIZATION ---

        function drawGraph() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Grid lines
            ctx.strokeStyle = "#374151";
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(0, 50); ctx.lineTo(400, 50);
            ctx.stroke();

            // Data Line
            ctx.beginPath();
            ctx.lineWidth = 3;
            ctx.lineJoin = 'round';
            
            // Find scaling factor
            const maxVal = Math.max(...speedHistory, 10);
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 100);
            gradient.addColorStop(0, "rgba(245, 158, 11, 0.5)");
            gradient.addColorStop(1, "rgba(245, 158, 11, 0)");

            const step = canvas.width / (speedHistory.length - 1 || 1);
            
            speedHistory.forEach((val, index) => {
                const x = index * step;
                const y = canvas.height - ((val / maxVal) * canvas.height);
                if (index === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            });

            ctx.strokeStyle = "#f59e0b";
            ctx.stroke();

            ctx.lineTo(canvas.width, canvas.height);
            ctx.lineTo(0, canvas.height);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();

            if (isTesting) {
                animationId = requestAnimationFrame(drawGraph);
            }
        }

    </script>
</body>
</html>