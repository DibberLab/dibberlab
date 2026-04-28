<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Color Input Styling */
        input[type="color"] {
            -webkit-appearance: none;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: 2px solid #374151;
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; }

        /* Select Styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }

        /* Preview Area */
        #preview-box {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }
        
        #error-msg {
            animation: slideUp 0.3s ease-out forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 space-y-6">
                
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-amber-400">Barcode Maker</h1>
                    <p class="text-gray-400 text-sm">Generate scan-ready barcodes instantly.</p>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Value</label>
                        <input type="text" id="barcode-value" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white font-mono focus:border-amber-500 outline-none placeholder-gray-600" value="Dibber-1234">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Format</label>
                        <select id="barcode-format" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white text-sm focus:border-amber-500 outline-none cursor-pointer">
                            <option value="CODE128">Code 128 (Default)</option>
                            <option value="EAN13">EAN-13 (Retail)</option>
                            <option value="UPC">UPC (US Retail)</option>
                            <option value="CODE39">Code 39</option>
                            <option value="ITF14">ITF-14</option>
                            <option value="MSI">MSI</option>
                            <option value="pharmacode">Pharmacode</option>
                        </select>
                    </div>

                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                    <h3 class="text-sm font-bold text-white uppercase mb-4 border-b border-gray-700 pb-1">Visual Settings</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Line Color</label>
                            <div class="flex items-center gap-3 bg-gray-900 p-2 rounded-lg">
                                <input type="color" id="color-line" value="#000000">
                                <span class="text-xs text-gray-400 font-mono" id="hex-line">#000000</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Background</label>
                            <div class="flex items-center gap-3 bg-gray-900 p-2 rounded-lg">
                                <input type="color" id="color-bg" value="#ffffff">
                                <span class="text-xs text-gray-400 font-mono" id="hex-bg">#FFFFFF</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-center">
                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-gray-900 rounded-lg">
                            <input type="checkbox" id="show-text" checked class="w-4 h-4 rounded bg-gray-700 text-amber-500 focus:ring-amber-500 border-gray-600">
                            <span class="text-sm font-bold text-gray-300">Show Text</span>
                        </label>
                        
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Bar Height</label>
                            <input type="range" id="bar-height" min="10" max="150" value="100" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-amber-500">
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-7 flex flex-col items-center justify-start lg:sticky lg:top-8">
                
                <div id="preview-box" class="bg-white w-full h-80 rounded-[2.5rem] mb-6 flex items-center justify-center relative overflow-hidden p-8">
                    
                    <svg id="barcode"></svg>

                    <div id="error-msg" class="absolute inset-0 bg-red-500/90 flex flex-col items-center justify-center text-white hidden z-10 p-6 text-center">
                        <div class="text-4xl mb-2">⚠️</div>
                        <h3 class="font-bold text-lg">Invalid Input</h3>
                        <p class="text-sm opacity-90 mt-1" id="error-text">This value doesn't match the selected format.</p>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-4 w-full max-w-md">
                    <button onclick="downloadBarcode('png')" class="py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-lg rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PNG
                    </button>
                    <button onclick="downloadBarcode('svg')" class="py-4 bg-gray-800 hover:bg-gray-700 text-white font-bold text-lg rounded-xl border border-gray-600 transition-all transform hover:-translate-y-1">
                        Download SVG
                    </button>
                </div>

                <div class="mt-6 text-xs text-gray-500 text-center max-w-sm">
                    <p>Tip: EAN-13 requires exactly 12 or 13 digits. Code 128 accepts all characters.</p>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const valueInput = document.getElementById('barcode-value');
        const formatSelect = document.getElementById('barcode-format');
        const colorLine = document.getElementById('color-line');
        const colorBg = document.getElementById('color-bg');
        const showText = document.getElementById('show-text');
        const barHeight = document.getElementById('bar-height');
        
        const previewBox = document.getElementById('preview-box');
        const svgEl = document.getElementById('barcode');
        const errorMsg = document.getElementById('error-msg');
        const errorText = document.getElementById('error-text');

        // --- CORE GENERATOR ---

        function generate() {
            const val = valueInput.value;
            const format = formatSelect.value;

            // Reset Error
            errorMsg.classList.add('hidden');
            previewBox.style.backgroundColor = colorBg.value;

            // Basic Validation before trying
            if (!val) return;

            try {
                JsBarcode("#barcode", val, {
                    format: format,
                    lineColor: colorLine.value,
                    background: colorBg.value, // Used inside the SVG rect
                    width: 2,
                    height: parseInt(barHeight.value),
                    displayValue: showText.checked,
                    fontOptions: "bold",
                    font: "monospace",
                    fontSize: 20,
                    margin: 10,
                    valid: function(valid) {
                        if (!valid) {
                            throw new Error("Invalid format");
                        }
                    }
                });
            } catch (e) {
                showError(e);
            }
        }

        function showError(e) {
            errorMsg.classList.remove('hidden');
            
            // Helpful messages based on format
            const format = formatSelect.value;
            if (format === 'EAN13') errorText.textContent = "EAN-13 requires numeric digits only.";
            else if (format === 'UPC') errorText.textContent = "UPC requires numeric digits only.";
            else errorText.textContent = "Input contains characters not supported by this format.";
        }

        // --- DOWNLOAD ---

        function downloadBarcode(type) {
            if (!valueInput.value || !errorMsg.classList.contains('hidden')) return alert("Cannot download invalid barcode.");

            if (type === 'svg') {
                const svgData = new XMLSerializer().serializeToString(svgEl);
                const blob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
                const url = URL.createObjectURL(blob);
                triggerDownload(url, 'barcode.svg');
            } else {
                // PNG requires drawing SVG to Canvas first
                const svgData = new XMLSerializer().serializeToString(svgEl);
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");
                const img = new Image();

                // Get SVG Dimensions
                const svgSize = svgEl.getBoundingClientRect();
                // Scale up for high res
                const scale = 2;
                canvas.width = svgSize.width * scale;
                canvas.height = svgSize.height * scale;

                img.onload = () => {
                    ctx.fillStyle = colorBg.value;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    triggerDownload(canvas.toDataURL("image/png"), 'barcode.png');
                };

                img.src = "data:image/svg+xml;base64," + btoa(svgData);
            }
        }

        function triggerDownload(url, name) {
            const a = document.createElement('a');
            a.href = url;
            a.download = name;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // --- LISTENERS ---

        [valueInput, formatSelect, showText, barHeight].forEach(el => {
            el.addEventListener('input', generate);
        });

        // Color inputs need specific hex label updates
        colorLine.addEventListener('input', (e) => {
            document.getElementById('hex-line').textContent = e.target.value.toUpperCase();
            generate();
        });
        
        colorBg.addEventListener('input', (e) => {
            document.getElementById('hex-bg').textContent = e.target.value.toUpperCase();
            generate();
        });

        // Init
        generate();

    </script>
</body>
</html>