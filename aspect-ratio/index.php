<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspect Ratio Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Inputs */
        input[type="number"] { -moz-appearance: textfield; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        /* Visualizer Box */
        #visual-box {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-image: 
                linear-gradient(45deg, #374151 25%, transparent 25%), 
                linear-gradient(-45deg, #374151 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #374151 75%), 
                linear-gradient(-45deg, transparent 75%, #374151 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            background-color: #1f2937;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        /* Preset Buttons */
        .preset-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .preset-btn:hover { background-color: #374151; }
        .preset-btn:active { background-color: #f59e0b; color: #111827; border-color: #f59e0b; }

        /* Drop Zone */
        #drop-zone {
            border: 2px dashed #4b5563;
            transition: all 0.2s;
        }
        #drop-zone.drag-active {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Aspect Ratio Calculator</h1>
                <p class="text-center text-gray-400">Calculate ratios, resize dimensions, and visualize scaling.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="space-y-8">
                    
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-white uppercase flex items-center gap-2">
                                <span class="text-emerald-400">❶</span> Find Ratio
                            </h3>
                            <div class="relative">
                                <input type="file" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                <button class="text-xs text-emerald-400 hover:text-emerald-300 font-bold underline cursor-pointer">
                                    Detect from Image
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                            <input type="number" id="c1-w" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-3 text-white focus:border-emerald-500 transition-colors text-center font-mono" placeholder="Width" value="1920">
                            <span class="text-gray-500 font-bold">×</span>
                            <input type="number" id="c1-h" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-3 text-white focus:border-emerald-500 transition-colors text-center font-mono" placeholder="Height" value="1080">
                        </div>

                        <div class="text-center">
                            <span class="text-xs text-gray-500 uppercase font-bold">Result</span>
                            <div class="text-4xl font-bold text-white mono-font mt-1">
                                <span id="res-ratio">16:9</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-700">
                        <h3 class="text-sm font-bold text-white uppercase flex items-center gap-2 mb-4">
                            <span class="text-blue-400">❷</span> Calculate Dimensions
                        </h3>

                        <div class="grid grid-cols-5 gap-2 mb-4">
                            <button class="preset-btn bg-gray-800 py-1 rounded text-xs font-bold text-gray-400" onclick="setPreset(16,9)">16:9</button>
                            <button class="preset-btn bg-gray-800 py-1 rounded text-xs font-bold text-gray-400" onclick="setPreset(4,3)">4:3</button>
                            <button class="preset-btn bg-gray-800 py-1 rounded text-xs font-bold text-gray-400" onclick="setPreset(1,1)">1:1</button>
                            <button class="preset-btn bg-gray-800 py-1 rounded text-xs font-bold text-gray-400" onclick="setPreset(9,16)">9:16</button>
                            <button class="preset-btn bg-gray-800 py-1 rounded text-xs font-bold text-gray-400" onclick="setPreset(21,9)">21:9</button>
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                            <label class="text-xs font-bold text-gray-500 w-12">Ratio</label>
                            <input type="number" id="c2-r1" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-2 text-white text-center font-mono" value="16">
                            <span class="text-gray-500">:</span>
                            <input type="number" id="c2-r2" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-2 text-white text-center font-mono" value="9">
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex-1 relative">
                                <label class="absolute -top-2 left-2 text-[10px] bg-gray-900 px-1 text-gray-500 font-bold">WIDTH</label>
                                <input type="number" id="c2-w" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-3 text-emerald-400 font-bold text-center font-mono focus:border-blue-500" value="1920">
                            </div>
                            <span class="text-gray-500 font-bold">×</span>
                            <div class="flex-1 relative">
                                <label class="absolute -top-2 left-2 text-[10px] bg-gray-900 px-1 text-gray-500 font-bold">HEIGHT</label>
                                <input type="number" id="c2-h" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-3 text-blue-400 font-bold text-center font-mono focus:border-blue-500" placeholder="?">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-900 rounded-xl border border-gray-700 p-8 flex flex-col h-[500px] lg:h-auto">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Visual Preview</span>
                        <div class="text-[10px] text-gray-500 font-mono">
                            <span id="vis-w">1920</span> x <span id="vis-h">1080</span>
                        </div>
                    </div>

                    <div class="flex-grow flex items-center justify-center relative overflow-hidden bg-gray-800 rounded-lg border border-gray-700 border-dashed" id="visual-container">
                        
                        <div id="visual-box" class="flex items-center justify-center relative border-2 border-emerald-500/50">
                            <div class="w-4 h-px bg-white/30 absolute"></div>
                            <div class="h-4 w-px bg-white/30 absolute"></div>
                            
                            <span class="text-xs font-bold text-white bg-black/50 px-2 py-1 rounded backdrop-blur-sm" id="vis-label">16:9</span>
                        </div>

                    </div>
                    
                    <p class="text-center text-xs text-gray-500 mt-4">Box scales relative to container while maintaining aspect ratio.</p>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DOM ELEMENTS ---
        const c1w = document.getElementById('c1-w');
        const c1h = document.getElementById('c1-h');
        const resRatio = document.getElementById('res-ratio');
        const fileInput = document.getElementById('file-input');

        const c2r1 = document.getElementById('c2-r1');
        const c2r2 = document.getElementById('c2-r2');
        const c2w = document.getElementById('c2-w');
        const c2h = document.getElementById('c2-h');

        const visualBox = document.getElementById('visual-box');
        const visLabel = document.getElementById('vis-label');
        const visW = document.getElementById('vis-w');
        const visH = document.getElementById('vis-h');
        const visualContainer = document.getElementById('visual-container');

        // --- MATH UTILS ---
        function gcd(a, b) {
            return b == 0 ? a : gcd(b, a % b);
        }

        // --- MODE 1: FIND RATIO ---
        function calculateRatio() {
            const w = parseFloat(c1w.value);
            const h = parseFloat(c1h.value);

            if (!w || !h) return;

            const divisor = gcd(w, h);
            const r1 = w / divisor;
            const r2 = h / divisor;

            resRatio.textContent = `${r1}:${r2}`;
            
            // Sync to Mode 2 for seamless flow
            c2r1.value = r1;
            c2r2.value = r2;
            c2w.value = w;
            c2h.value = h; // Just to fill it
            
            updateVisuals(w, h, `${r1}:${r2}`);
        }

        // --- MODE 2: CALCULATE DIMS ---
        // Triggered when user changes Ratio or Width
        function calculateHeight() {
            const r1 = parseFloat(c2r1.value);
            const r2 = parseFloat(c2r2.value);
            const w = parseFloat(c2w.value);

            if (!r1 || !r2 || !w) return;

            // w / h = r1 / r2  =>  h = w * (r2 / r1)
            const h = w * (r2 / r1);
            
            // Allow decimals? Usually pixels are int, but aspect ratios can be float
            // Let's round to 2 decimals if needed, but display as int if whole
            c2h.value = Number.isInteger(h) ? h : h.toFixed(2);

            updateVisuals(w, h, `${r1}:${r2}`);
        }

        // Triggered when user changes Height
        function calculateWidth() {
            const r1 = parseFloat(c2r1.value);
            const r2 = parseFloat(c2r2.value);
            const h = parseFloat(c2h.value);

            if (!r1 || !r2 || !h) return;

            // w = h * (r1 / r2)
            const w = h * (r1 / r2);
            c2w.value = Number.isInteger(w) ? w : w.toFixed(2);

            updateVisuals(w, h, `${r1}:${r2}`);
        }

        // --- VISUALIZER ---
        function updateVisuals(w, h, ratioLabel) {
            visW.textContent = w;
            visH.textContent = h;
            visLabel.textContent = ratioLabel;

            // Fit box into container (300px max height usually, flex width)
            const containerW = visualContainer.clientWidth - 40; // padding
            const containerH = visualContainer.clientHeight - 40;

            const aspectRatio = w / h;
            
            let boxW, boxH;

            if (containerW / containerH > aspectRatio) {
                // Constrained by Height
                boxH = containerH;
                boxW = boxH * aspectRatio;
            } else {
                // Constrained by Width
                boxW = containerW;
                boxH = boxW / aspectRatio;
            }

            visualBox.style.width = `${boxW}px`;
            visualBox.style.height = `${boxH}px`;
        }

        // --- PRESETS ---
        window.setPreset = function(r1, r2) {
            c2r1.value = r1;
            c2r2.value = r2;
            calculateHeight(); // Recalculate based on current width
        }

        // --- FILE DETECTION ---
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const img = new Image();
            img.onload = function() {
                c1w.value = this.width;
                c1h.value = this.height;
                calculateRatio();
            }
            img.src = URL.createObjectURL(file);
        });

        // --- EVENT LISTENERS ---
        
        // Mode 1 Inputs
        c1w.addEventListener('input', calculateRatio);
        c1h.addEventListener('input', calculateRatio);

        // Mode 2 Inputs
        c2r1.addEventListener('input', calculateHeight);
        c2r2.addEventListener('input', calculateHeight);
        c2w.addEventListener('input', calculateHeight); // Changing width updates height
        c2h.addEventListener('input', calculateWidth);  // Changing height updates width

        // Window resize (to fix visualizer scaling)
        window.addEventListener('resize', () => {
            // Trigger a re-render using current values
            calculateHeight();
        });

        // Init
        calculateRatio();

    </script>
</body>
</html>