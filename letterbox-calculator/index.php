<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letterbox Calc | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input */
        .tech-input {
            background: #111827;
            border: 2px solid #374151;
            color: #e5e7eb;
            transition: all 0.2s;
        }
        .tech-input:focus {
            outline: none;
            border-color: #3b82f6; /* Blue-500 */
            background: #1f2937;
        }

        /* Screen Preview */
        .monitor-frame {
            background: #000;
            border: 4px solid #374151;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .active-area {
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%233b82f6' fill-opacity='0.1' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            background-color: #1e3a8a; /* Blue-900 */
            width: 100%;
            height: 100%;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Overlay text in preview */
        .preview-label {
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }

        /* Preset Buttons */
        .preset-btn {
            transition: all 0.2s;
        }
        .preset-btn:hover {
            background: #374151;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center py-8">
        <div class="w-full max-w-5xl">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-blue-400 mb-2">Aspect Ratio Calculator</h1>
                    <p class="text-gray-400 text-sm">Calculate letterbox mattes and active pixel dimensions.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-4 space-y-8">
                    
                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-3">Container Resolution</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="container-w" value="1920" class="tech-input w-full p-2 rounded text-center font-bold font-mono" placeholder="W">
                            <span class="text-gray-500 font-bold">x</span>
                            <input type="number" id="container-h" value="1080" class="tech-input w-full p-2 rounded text-center font-bold font-mono" placeholder="H">
                        </div>
                        
                        <div class="flex gap-2 mt-3">
                            <button onclick="setContainer(1920, 1080)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">HD</button>
                            <button onclick="setContainer(3840, 2160)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">UHD</button>
                            <button onclick="setContainer(4096, 2160)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">4K DCI</button>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-3">Target Aspect Ratio</label>
                        <input type="number" id="target-ratio" value="2.39" step="0.01" class="tech-input w-full p-3 rounded text-lg font-bold font-mono text-blue-400">
                        
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            <button onclick="setRatio(1.85)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">1.85:1 (Flat)</button>
                            <button onclick="setRatio(2.35)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">2.35:1 (Scope)</button>
                            <button onclick="setRatio(2.39)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">2.39:1 (Modern)</button>
                            <button onclick="setRatio(1.33)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">4:3 (TV)</button>
                            <button onclick="setRatio(1)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">1:1 (Square)</button>
                            <button onclick="setRatio(0.5625)" class="preset-btn px-2 py-1 bg-gray-700 rounded text-[10px] font-bold text-gray-300">9:16 (Vertical)</button>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="w-full aspect-video monitor-frame flex items-center justify-center bg-black">
                         

[Image of various video aspect ratios]

                        <div id="active-zone" class="active-area">
                            <div class="preview-label text-center">
                                <div class="text-2xl font-bold text-white mono-font" id="prev-dim">1920 x 803</div>
                                <div class="text-xs text-blue-200 uppercase font-bold tracking-widest mt-1">Active Area</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Active Resolution</span>
                            <div id="res-result" class="text-xl font-bold text-white mono-font mt-1">-- x --</div>
                        </div>

                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Bar Height (Top/Btm)</span>
                            <div id="bar-height" class="text-xl font-bold text-amber-500 mono-font mt-1">-- px</div>
                            <div class="text-[10px] text-gray-500">Total Crop: <span id="total-crop">--</span> px</div>
                        </div>
                        
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Status</span>
                            <div id="status-result" class="text-xl font-bold text-emerald-500 uppercase mt-1">Letterbox</div>
                        </div>

                    </div>

                    <div class="bg-black/30 p-4 rounded-xl border border-gray-700 font-mono text-xs text-gray-400">
                        <div class="flex justify-between mb-2">
                            <span class="font-bold text-gray-500 uppercase">FFmpeg Crop Filter</span>
                            <button onclick="copySnippet()" class="text-blue-400 hover:text-white">Copy</button>
                        </div>
                        <div id="ffmpeg-snippet" class="select-all text-emerald-400">crop=1920:803:0:138</div>
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
        const containerW = document.getElementById('container-w');
        const containerH = document.getElementById('container-h');
        const targetRatio = document.getElementById('target-ratio');
        
        const activeZone = document.getElementById('active-zone');
        const prevDim = document.getElementById('prev-dim');
        
        const resResult = document.getElementById('res-result');
        const barHeightResult = document.getElementById('bar-height');
        const totalCropResult = document.getElementById('total-crop');
        const statusResult = document.getElementById('status-result');
        const ffmpegSnippet = document.getElementById('ffmpeg-snippet');

        // --- CORE CALCULATION ---

        function calculate() {
            const w = parseInt(containerW.value);
            const h = parseInt(containerH.value);
            const ratio = parseFloat(targetRatio.value);

            if (!w || !h || !ratio) return;

            const containerRatio = w / h;
            
            let newW, newH, barSize;
            let type = "";

            if (ratio > containerRatio) {
                // LETTERBOX (Bars on Top/Bottom) - Width stays same, Height shrinks
                type = "Letterbox";
                newW = w;
                newH = Math.round(w / ratio);
                
                // Ensure even numbers for video codecs
                if (newH % 2 !== 0) newH--;

                const totalCrop = h - newH;
                barSize = totalCrop / 2;

                // Update Visualizer
                // Width 100%, Height reduces
                const visualH = (newH / h) * 100;
                activeZone.style.width = '100%';
                activeZone.style.height = `${visualH}%`;

                // Update Stats
                resResult.textContent = `${newW} x ${newH}`;
                barHeightResult.textContent = `${barSize} px`;
                totalCropResult.textContent = totalCrop;
                statusResult.textContent = "Letterbox";
                statusResult.className = "text-xl font-bold text-amber-500 uppercase mt-1";

                // FFmpeg: crop=w:h:x:y
                ffmpegSnippet.textContent = `crop=${newW}:${newH}:0:${barSize}`;

            } else if (ratio < containerRatio) {
                // PILLARBOX (Bars on Left/Right) - Height stays same, Width shrinks
                type = "Pillarbox";
                newH = h;
                newW = Math.round(h * ratio);
                
                if (newW % 2 !== 0) newW--;

                const totalCrop = w - newW;
                barSize = totalCrop / 2;

                // Update Visualizer
                const visualW = (newW / w) * 100;
                activeZone.style.width = `${visualW}%`;
                activeZone.style.height = '100%';

                // Update Stats
                resResult.textContent = `${newW} x ${newH}`;
                barHeightResult.textContent = `${barSize} px (Side)`;
                totalCropResult.textContent = totalCrop;
                statusResult.textContent = "Pillarbox";
                statusResult.className = "text-xl font-bold text-blue-500 uppercase mt-1";

                ffmpegSnippet.textContent = `crop=${newW}:${newH}:${barSize}:0`;

            } else {
                // MATCH
                type = "Match";
                newW = w;
                newH = h;
                barSize = 0;

                activeZone.style.width = '100%';
                activeZone.style.height = '100%';

                resResult.textContent = `${newW} x ${newH}`;
                barHeightResult.textContent = `0 px`;
                totalCropResult.textContent = 0;
                statusResult.textContent = "Full Frame";
                statusResult.className = "text-xl font-bold text-emerald-500 uppercase mt-1";
                
                ffmpegSnippet.textContent = `crop=${newW}:${newH}:0:0`;
            }

            prevDim.textContent = `${newW} x ${newH}`;
        }

        // --- UTILS ---

        function setContainer(w, h) {
            containerW.value = w;
            containerH.value = h;
            calculate();
        }

        function setRatio(r) {
            targetRatio.value = r;
            calculate();
        }

        function copySnippet() {
            navigator.clipboard.writeText(ffmpegSnippet.textContent);
            alert("FFmpeg snippet copied!");
        }

        // Listeners
        [containerW, containerH, targetRatio].forEach(el => {
            el.addEventListener('input', calculate);
        });

        // Init
        calculate();

    </script>
</body>
</html>