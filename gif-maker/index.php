<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIF Maker | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Drop Zone */
        .drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }
        .drop-zone:hover, .drop-zone.dragover {
            background-color: rgba(31, 41, 55, 0.5);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%23F59E0BFF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transform: scale(0.99);
        }

        /* Frame thumbnail styles */
        .frame-thumb {
            position: relative;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        .frame-thumb:hover { border-color: #f59e0b; }
        .remove-btn {
            position: absolute;
            top: -8px; right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold;
            cursor: pointer;
            opacity: 0; transition: opacity 0.2s;
        }
        .frame-thumb:hover .remove-btn { opacity: 1; }

        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px; width: 20px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -8px;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px;
            cursor: pointer;
            background: #374151;
            border-radius: 2px;
        }

        /* Progress Bar */
        .progress-bar-wrap {
            height: 6px;
            background: #374151;
            border-radius: 3px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: #f59e0b;
            width: 0%;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 py-8 flex justify-center">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 space-y-6">
                
                <div>
                    <h1 class="text-3xl font-bold text-amber-400 mb-2">GIF Maker</h1>
                    <p class="text-gray-400 text-sm">Stitch images into an animated GIF.</p>
                </div>

                <label class="drop-zone w-full h-48 flex flex-col items-center justify-center cursor-pointer rounded-3xl group relative overflow-hidden bg-gray-800/50">
                    <div class="z-10 flex flex-col items-center pointer-events-none">
                        <svg class="w-12 h-12 text-gray-500 group-hover:text-amber-400 mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-lg font-bold text-gray-300 group-hover:text-white transition-colors">Drop Images Here</p>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG (Select Multiple)</p>
                    </div>
                    <input type="file" id="file-input" class="hidden" accept="image/jpeg, image/png" multiple>
                </label>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 space-y-6">
                    
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Frame Speed (Delay)</label>
                            <span class="text-xs font-bold text-amber-400 mono-font"><span id="delay-val">200</span>ms</span>
                        </div>
                        <input type="range" id="delay-input" min="50" max="1000" step="10" value="200">
                        <div class="flex justify-between text-[10px] text-gray-600 mt-1">
                            <span>Fast (50ms)</span>
                            <span>Slow (1s)</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-3">Frames (<span id="frame-count">0</span>)</label>
                        <div id="frames-container" class="grid grid-cols-4 gap-3 p-3 bg-gray-900/50 rounded-xl min-h-[80px] max-h-[200px] overflow-y-auto custom-scrollbar">
                            <span class="text-gray-600 text-xs col-span-4 text-center italic py-4">No images added yet.</span>
                        </div>
                    </div>

                </div>

                 <div class="space-y-4">
                    <button onclick="createGIF()" id="generate-btn" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none disabled:grayscale">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span id="btn-text">Create GIF</span>
                    </button>

                    <div id="progress-container" class="hidden">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Rendering...</span>
                            <span id="progress-txt">0%</span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div id="progress-fill" class="progress-bar-fill"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-7 flex items-start justify-center">
                <div class="w-full bg-gray-800 rounded-2xl p-6 border-2 border-gray-700 shadow-2xl min-h-[500px] flex flex-col items-center justify-center relative">
                    
                    <div id="placeholder-text" class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                        <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-lg font-bold uppercase tracking-widest opacity-50">Preview Area</p>
                        <p class="text-sm opacity-30">Result will appear here</p>
                    </div>
                    
                    <img id="result-gif" class="max-w-full max-h-[60vh] object-contain hidden rounded-lg shadow-xl mb-6">
                    
                    <a id="download-link" class="hidden px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download GIF
                    </a>

                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <canvas id="temp-canvas" class="hidden"></canvas>

    <script>
        // DOM Elements
        const fileInput = document.getElementById('file-input');
        const dropZone = document.querySelector('.drop-zone');
        const framesContainer = document.getElementById('frames-container');
        const frameCountEl = document.getElementById('frame-count');
        const delayInput = document.getElementById('delay-input');
        const delayVal = document.getElementById('delay-val');
        const generateBtn = document.getElementById('generate-btn');
        const btnText = document.getElementById('btn-text');
        const progressContainer = document.getElementById('progress-container');
        const progressFill = document.getElementById('progress-fill');
        const progressTxt = document.getElementById('progress-txt');
        const resultGif = document.getElementById('result-gif');
        const downloadLink = document.getElementById('download-link');
        const placeholderText = document.getElementById('placeholder-text');
        const tempCanvas = document.getElementById('temp-canvas');
        const tempCtx = tempCanvas.getContext('2d');

        // State
        let loadedImages = []; // Array of HTMLImageElements

        // --- DRAG & DROP ---
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault(); e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files));
        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));


        // --- FILE HANDLING ---

        function handleFiles(files) {
            if (files.length === 0) return;
            
            // Convert FileList to Array and filter images
            const fileArray = Array.from(files).filter(f => f.type.startsWith('image/'));

            let processedCount = 0;

            fileArray.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        loadedImages.push(img);
                        processedCount++;
                        if(processedCount === fileArray.length) {
                             updateFrameUI();
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFrameUI() {
            framesContainer.innerHTML = '';
            frameCountEl.textContent = loadedImages.length;
            generateBtn.disabled = loadedImages.length < 2; // Need at least 2 frames

            if (loadedImages.length === 0) {
                framesContainer.innerHTML = '<span class="text-gray-600 text-xs col-span-4 text-center italic py-4">No images added yet.</span>';
                return;
            }

            loadedImages.forEach((img, index) => {
                const thumb = document.createElement('div');
                thumb.className = "frame-thumb aspect-square rounded-lg bg-gray-800 overflow-hidden relative";
                thumb.innerHTML = `
                    <img src="${img.src}" class="w-full h-full object-cover">
                    <button class="remove-btn" onclick="removeFrame(${index})">×</button>
                `;
                framesContainer.appendChild(thumb);
            });
        }

        function removeFrame(index) {
            loadedImages.splice(index, 1);
            updateFrameUI();
        }

        // --- GIF GENERATION ---

        function createGIF() {
            if (loadedImages.length < 2 || generateBtn.disabled) return;

            // UI State: Rendering
            generateBtn.disabled = true;
            btnText.textContent = "Processing...";
            progressContainer.classList.remove('hidden');
            resultGif.classList.add('hidden');
            downloadLink.classList.add('hidden');
            placeholderText.classList.remove('hidden');
            resetProgress();

            // 1. Initialize GIF instance
            // IMPORTANT: We need to point to the external CDN worker script
            const gif = new GIF({
                workers: 2,
                quality: 10,
                workerScript: 'https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.worker.js'
            });

            // 2. Determine dimensions based on first image
            // We will center subsequent images on this canvas size
            const baseWidth = loadedImages[0].width;
            const baseHeight = loadedImages[0].height;
            tempCanvas.width = baseWidth;
            tempCanvas.height = baseHeight;
            
            const delay = parseInt(delayInput.value);

            // 3. Add Frames
            loadedImages.forEach(img => {
                // Clear canvas
                tempCtx.fillStyle = '#000000'; // Black background for transparent PNGs or mismatched sizes
                tempCtx.fillRect(0, 0, baseWidth, baseHeight);

                // Center image on canvas if dimensions differ
                const x = (baseWidth - img.width) / 2;
                const y = (baseHeight - img.height) / 2;
                tempCtx.drawImage(img, x, y);
                
                // Add canvas frame to GIF engine
                gif.addFrame(tempCanvas, {copy: true, delay: delay});
            });

            // 4. Event Handlers
            gif.on('progress', (p) => {
                const pct = Math.round(p * 100) + '%';
                progressFill.style.width = pct;
                progressTxt.textContent = pct;
            });

            gif.on('finished', (blob) => {
                // UI State: Finished
                generateBtn.disabled = false;
                btnText.textContent = "Create GIF";
                progressContainer.classList.add('hidden');
                
                // Display Result
                const url = URL.createObjectURL(blob);
                resultGif.src = url;
                resultGif.classList.remove('hidden');
                placeholderText.classList.add('hidden');

                // Setup Download
                downloadLink.href = url;
                downloadLink.download = `dibber_gif_${Date.now()}.gif`;
                downloadLink.classList.remove('hidden');
                downloadLink.classList.add('flex');
            });

            // 5. Start render
            gif.render();
        }

        function resetProgress() {
            progressFill.style.width = '0%';
            progressTxt.textContent = '0%';
        }

        // --- LISTENERS ---
        delayInput.addEventListener('input', (e) => delayVal.textContent = e.target.value);

        // Init State
        generateBtn.disabled = true;

    </script>
</body>
</html>