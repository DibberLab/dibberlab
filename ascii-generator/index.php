<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASCII Art Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The ASCII Output */
        #ascii-output {
            font-family: 'Roboto Mono', monospace;
            white-space: pre;
            line-height: 0.6; /* Critical for aspect ratio */
            letter-spacing: 0;
            overflow: auto;
            transform-origin: top left;
        }

        /* Drop Zone */
        #drop-zone {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.2s;
        }
        #drop-zone:hover { background-color: rgba(31, 41, 55, 0.5); }
        #drop-zone.drag-active {
            background-color: rgba(16, 185, 129, 0.1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%2310B981FF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }

        /* Range Slider */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 16px; width: 16px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -6px; 
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }

        /* Toggles */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-7xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">ASCII Art Generator</h1>
                <p class="text-center text-gray-400">Convert images into text characters.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 flex flex-col gap-6">
                    
                    <div id="drop-zone" class="h-40 rounded-2xl flex flex-col items-center justify-center cursor-pointer relative group">
                        <input type="file" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        <div class="text-center pointer-events-none group-hover:scale-105 transition-transform">
                            <div class="text-4xl mb-2">👾</div>
                            <p class="text-gray-300 font-bold">Upload Image</p>
                        </div>
                    </div>

                    <div id="controls-area" class="space-y-6 opacity-50 pointer-events-none transition-opacity">
                        
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Resolution (Width)</span>
                                <span id="width-val" class="text-white">100 chars</span>
                            </div>
                            <input type="range" id="width-slider" min="20" max="300" value="100" class="w-full">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Contrast</span>
                                <span id="contrast-val" class="text-white">1.0</span>
                            </div>
                            <input type="range" id="contrast-slider" min="0.5" max="3" step="0.1" value="1" class="w-full">
                        </div>

                        <div class="flex justify-between items-center bg-gray-900 p-3 rounded-xl border border-gray-700">
                            
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-400">Invert</span>
                                <div class="relative inline-block w-10 align-middle select-none">
                                    <input type="checkbox" id="invert-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                    <label for="invert-toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                                </div>
                            </div>

                            <div class="h-6 w-px bg-gray-600"></div>

                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-400">Complex</span>
                                <div class="relative inline-block w-10 align-middle select-none">
                                    <input type="checkbox" id="complex-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                    <label for="complex-toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                                </div>
                            </div>

                        </div>

                        <div class="flex gap-2">
                            <button id="copy-btn" class="flex-1 py-3 rounded-xl font-bold bg-gray-700 hover:bg-gray-600 text-white transition-colors">
                                Copy Text
                            </button>
                            <button id="download-btn" class="flex-1 py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg transition-transform hover:-translate-y-1">
                                Download .txt
                            </button>
                        </div>

                    </div>
                </div>

                <div class="lg:col-span-8 flex flex-col h-[500px] lg:h-[600px] bg-gray-900 rounded-xl border border-gray-700 relative overflow-hidden">
                    
                    <div class="absolute top-4 right-4 z-10 flex gap-2">
                        <button id="zoom-out" class="w-8 h-8 rounded bg-gray-800 hover:bg-gray-700 text-white border border-gray-600 flex items-center justify-center font-bold text-lg">-</button>
                        <button id="zoom-in" class="w-8 h-8 rounded bg-gray-800 hover:bg-gray-700 text-white border border-gray-600 flex items-center justify-center font-bold text-lg">+</button>
                    </div>

                    <div class="w-full h-full overflow-auto flex items-center justify-center p-4 custom-scrollbar bg-black" id="output-container">
                        
                        <div id="ascii-output" class="text-white text-[10px] origin-center"></div>
                        
                        <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 pointer-events-none">
                            <span class="text-4xl mb-4 opacity-30">⌨️</span>
                            <p class="font-bold">Art will appear here</p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- CHAR SETS ---
        // Sorted from darkest to lightest
        const CHARS_SIMPLE = "@%#*+=-:. ";
        const CHARS_COMPLEX = "$@B%8&WM#*oahkbdpqwmZO0QLCJUYXzcvunxrjft/\\|()1{}[]?-_+~<>i!lI;:,\"^`'. ";

        // DOM
        const fileInput = document.getElementById('file-input');
        const dropZone = document.getElementById('drop-zone');
        const controlsArea = document.getElementById('controls-area');
        const output = document.getElementById('ascii-output');
        const emptyState = document.getElementById('empty-state');
        
        const widthSlider = document.getElementById('width-slider');
        const widthVal = document.getElementById('width-val');
        const contrastSlider = document.getElementById('contrast-slider');
        const contrastVal = document.getElementById('contrast-val');
        
        const invertToggle = document.getElementById('invert-toggle');
        const complexToggle = document.getElementById('complex-toggle');
        
        const copyBtn = document.getElementById('copy-btn');
        const downloadBtn = document.getElementById('download-btn');
        const zoomIn = document.getElementById('zoom-in');
        const zoomOut = document.getElementById('zoom-out');

        // State
        let img = null;
        let zoomLevel = 10; // Font size in px

        // --- CORE LOGIC ---

        function handleFile(file) {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                img = new Image();
                img.onload = () => {
                    controlsArea.classList.remove('opacity-50', 'pointer-events-none');
                    emptyState.classList.add('hidden');
                    renderAscii();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function renderAscii() {
            if (!img) return;

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // 1. Calculate Dimensions
            const width = parseInt(widthSlider.value);
            const aspectRatio = img.height / img.width;
            // Fonts are taller than wide (approx 0.55 width/height ratio)
            // To prevent stretching, we scale height down by ~0.55
            const height = Math.floor(width * aspectRatio * 0.55);

            canvas.width = width;
            canvas.height = height;

            // 2. Draw Resized Image
            ctx.drawImage(img, 0, 0, width, height);
            
            // 3. Get Pixel Data
            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;
            
            // 4. Convert to ASCII
            const chars = complexToggle.checked ? CHARS_COMPLEX : CHARS_SIMPLE;
            const contrast = parseFloat(contrastSlider.value);
            const isInverted = invertToggle.checked;
            
            let asciiStr = "";

            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    const offset = (y * width + x) * 4;
                    const r = data[offset];
                    const g = data[offset + 1];
                    const b = data[offset + 2];
                    // Alpha handling? Usually ignore for ASCII or treat as white.
                    const a = data[offset + 3];

                    if (a < 128) {
                        // Transparent
                        asciiStr += " "; 
                        continue;
                    }

                    // Brightness (Luma)
                    let brightness = (0.2126 * r + 0.7152 * g + 0.0722 * b);
                    
                    // Apply Contrast
                    brightness = ((brightness - 128) * contrast) + 128;
                    brightness = Math.max(0, Math.min(255, brightness));

                    // Invert?
                    if (isInverted) brightness = 255 - brightness;

                    // Map to Char
                    // 255 brightness = last char (space), 0 = first char (@)
                    const charIndex = Math.floor((brightness / 255) * (chars.length - 1));
                    asciiStr += chars[charIndex];
                }
                asciiStr += "\n";
            }

            output.textContent = asciiStr;
        }

        // --- LISTENERS ---

        fileInput.addEventListener('change', (e) => {
            if(e.target.files.length > 0) handleFile(e.target.files[0]);
        });

        // Drag & Drop
        dropZone.addEventListener('dragenter', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragleave', (e) => { e.preventDefault(); dropZone.classList.remove('drag-active'); });
        dropZone.addEventListener('drop', (e) => { 
            e.preventDefault(); 
            dropZone.classList.remove('drag-active');
            if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
        });

        // Inputs
        widthSlider.addEventListener('input', (e) => {
            widthVal.textContent = e.target.value + ' chars';
            renderAscii();
        });

        contrastSlider.addEventListener('input', (e) => {
            contrastVal.textContent = e.target.value;
            renderAscii();
        });

        invertToggle.addEventListener('change', renderAscii);
        complexToggle.addEventListener('change', renderAscii);

        // Zoom
        zoomIn.addEventListener('click', () => {
            zoomLevel += 2;
            output.style.fontSize = zoomLevel + 'px';
        });
        zoomOut.addEventListener('click', () => {
            zoomLevel = Math.max(4, zoomLevel - 2);
            output.style.fontSize = zoomLevel + 'px';
        });

        // Copy
        copyBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(output.textContent).then(() => {
                const orig = copyBtn.innerText;
                copyBtn.innerText = "Copied!";
                copyBtn.classList.replace('bg-gray-700', 'bg-emerald-600');
                setTimeout(() => {
                    copyBtn.innerText = orig;
                    copyBtn.classList.replace('bg-emerald-600', 'bg-gray-700');
                }, 1500);
            });
        });

        // Download
        downloadBtn.addEventListener('click', () => {
            if(!img) return;
            const blob = new Blob([output.textContent], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'ascii-art.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

    </script>
</body>
</html>