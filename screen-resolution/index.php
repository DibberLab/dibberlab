<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screen Resolution Tester | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Dynamic Visualizer Box */
        #visual-box {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-image: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            border: 2px solid #4b5563;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        /* Stat Card Hover */
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Screen Resolution Tester</h1>
                <p class="text-center text-gray-400">View your real-time viewport dimensions and device specs.</p>
            </div>

            <div class="flex flex-col items-center justify-center mb-10">
                <div class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-2">Current Viewport</div>
                <div class="flex items-baseline gap-1 text-7xl md:text-9xl font-mono font-bold text-white tracking-tighter">
                    <span id="width-val" class="text-emerald-400">1920</span>
                    <span class="text-gray-600 text-4xl mx-2">x</span>
                    <span id="height-val" class="text-blue-400">1080</span>
                </div>
                <div class="text-gray-400 mt-2 font-mono">
                    Aspect Ratio: <span id="aspect-val" class="text-white font-bold">16:9</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-gray-900 rounded-xl border border-gray-700 p-8 flex items-center justify-center h-64 overflow-hidden relative">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 20px 20px;"></div>
                    
                    <div id="visual-box" class="flex items-center justify-center relative">
                        <span class="text-xs font-bold text-gray-400 absolute top-2 left-2">Screen</span>
                        <div class="w-full h-px bg-red-500/50 absolute top-1/2 left-0"></div>
                        <div class="h-full w-px bg-red-500/50 absolute top-0 left-1/2"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="stat-card bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Device Monitor</div>
                        <div class="text-xl font-mono font-bold text-white" id="monitor-val">1920 x 1080</div>
                        <div class="text-[10px] text-gray-600">Physical Hardware</div>
                    </div>

                    <div class="stat-card bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Pixel Ratio (DPR)</div>
                        <div class="text-xl font-mono font-bold text-amber-400" id="dpr-val">2.0</div>
                        <div class="text-[10px] text-gray-600">Retina / Scaling</div>
                    </div>

                    <div class="stat-card bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Color Depth</div>
                        <div class="text-xl font-mono font-bold text-white" id="color-val">24-bit</div>
                    </div>

                    <div class="stat-card bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Orientation</div>
                        <div class="text-xl font-mono font-bold text-white" id="orient-val">Landscape</div>
                    </div>

                    <div class="col-span-2">
                        <button id="copy-btn" class="w-full py-3 rounded-lg border border-gray-600 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold transition-all flex items-center justify-center gap-2">
                            <span>📋</span> Copy Specs to Clipboard
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const widthVal = document.getElementById('width-val');
        const heightVal = document.getElementById('height-val');
        const aspectVal = document.getElementById('aspect-val');
        const monitorVal = document.getElementById('monitor-val');
        const dprVal = document.getElementById('dpr-val');
        const colorVal = document.getElementById('color-val');
        const orientVal = document.getElementById('orient-val');
        const visualBox = document.getElementById('visual-box');
        const copyBtn = document.getElementById('copy-btn');

        // --- CORE LOGIC ---

        function getGCD(a, b) {
            return b == 0 ? a : getGCD(b, a % b);
        }

        function getAspectRatio(w, h) {
            const divisor = getGCD(w, h);
            return `${w / divisor}:${h / divisor}`;
        }

        function updateStats() {
            // 1. Viewport
            const w = window.innerWidth;
            const h = window.innerHeight;
            
            widthVal.textContent = w;
            heightVal.textContent = h;
            
            // 2. Aspect Ratio
            // Sometimes aspect ratios are weird (like 123:54), so we simplify or detect standards
            let ar = getAspectRatio(w, h);
            // Detect common approx
            const ratio = w / h;
            if (Math.abs(ratio - 1.777) < 0.05) ar = "16:9 (Approx)";
            if (Math.abs(ratio - 1.333) < 0.05) ar = "4:3 (Approx)";
            if (Math.abs(ratio - 2.333) < 0.05) ar = "21:9 (Approx)";
            
            aspectVal.textContent = ar;

            // 3. Monitor Specs
            monitorVal.textContent = `${window.screen.width} x ${window.screen.height}`;
            
            // 4. DPR
            const dpr = window.devicePixelRatio || 1;
            dprVal.textContent = dpr.toFixed(1) + (dpr > 1 ? "x" : "");

            // 5. Color Depth
            colorVal.textContent = `${window.screen.colorDepth}-bit`;

            // 6. Orientation
            const orientation = w >= h ? "Landscape" : "Portrait";
            orientVal.textContent = orientation;

            // 7. Visualizer
            updateVisualizer(w, h);
        }

        function updateVisualizer(w, h) {
            // We want to fit the box inside a 200px height container but maintain ratio
            const MAX_H = 150;
            const MAX_W = 250;
            
            let boxW, boxH;

            if (w > h) {
                // Landscape logic
                boxW = MAX_W;
                boxH = (h / w) * MAX_W;
                
                // If height exceeds max, scale down by height instead
                if (boxH > MAX_H) {
                    boxH = MAX_H;
                    boxW = (w / h) * MAX_H;
                }
            } else {
                // Portrait logic
                boxH = MAX_H;
                boxW = (w / h) * MAX_H;
            }

            visualBox.style.width = `${boxW}px`;
            visualBox.style.height = `${boxH}px`;
        }

        // --- LISTENERS ---

        // Real-time resize listener
        window.addEventListener('resize', updateStats);

        // Copy
        copyBtn.addEventListener('click', () => {
            const w = window.innerWidth;
            const h = window.innerHeight;
            const specs = `Viewport: ${w}x${h}\nScreen: ${window.screen.width}x${window.screen.height}\nDPR: ${window.devicePixelRatio}\nUserAgent: ${navigator.userAgent}`;
            
            navigator.clipboard.writeText(specs).then(() => {
                const original = copyBtn.innerHTML;
                copyBtn.innerHTML = `<span class="text-emerald-400">✅ Copied!</span>`;
                setTimeout(() => copyBtn.innerHTML = original, 1500);
            });
        });

        // Init
        updateStats();

    </script>
</body>
</html>