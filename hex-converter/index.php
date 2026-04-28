<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hex Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Inputs */
        .cyber-input {
            background: #111827;
            border: 2px solid #374151;
            transition: all 0.2s;
            text-transform: uppercase;
        }
        .cyber-input:focus {
            outline: none;
            border-color: #a855f7; /* Purple */
            background: #1f2937;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.2);
        }

        /* Color Preview Card */
        .color-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .color-card.active {
            transform: translateY(0);
            opacity: 1;
        }
        .color-card.hidden-card {
            transform: translateY(20px);
            opacity: 0;
            pointer-events: none;
        }

        /* Copy Icon */
        .copy-icon {
            opacity: 0;
            transform: translateX(-5px);
            transition: all 0.2s;
        }
        .group:hover .copy-icon {
            opacity: 1;
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            
            <div class="flex flex-col gap-8">
                
                <div>
                    <h1 class="text-3xl font-bold text-purple-400 mb-2">Hex Converter</h1>
                    <p class="text-gray-400 text-sm">Base-10 to Base-16 translation.</p>
                </div>

                <div class="group cursor-pointer" onclick="copyValue('dec-input')">
                    <label class="text-xs font-bold text-gray-500 uppercase flex justify-between">
                        <span>Decimal (Base 10)</span>
                        <span class="copy-icon text-purple-400">Copy</span>
                    </label>
                    <input type="number" id="dec-input" class="cyber-input w-full rounded-xl p-4 text-3xl font-bold font-mono text-white mt-2" placeholder="255">
                </div>

                <div class="flex justify-center -my-2 opacity-50">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                </div>

                <div class="group cursor-pointer" onclick="copyValue('hex-input')">
                    <label class="text-xs font-bold text-gray-500 uppercase flex justify-between">
                        <span>Hexadecimal (Base 16)</span>
                        <span class="copy-icon text-purple-400">Copy</span>
                    </label>
                    <div class="relative mt-2">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-mono text-xl">0x</span>
                        <input type="text" id="hex-input" class="cyber-input w-full rounded-xl p-4 pl-12 text-3xl font-bold font-mono text-white" placeholder="FF">
                    </div>
                </div>

                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                    <label class="text-[10px] font-bold text-gray-500 uppercase">Binary Representation</label>
                    <div id="bin-display" class="font-mono text-emerald-400 break-all mt-1">0000 0000</div>
                </div>

            </div>

            <div class="relative min-h-[300px]">
                
                <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 border-2 border-dashed border-gray-800 rounded-3xl">
                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    <p class="text-sm font-bold uppercase tracking-widest">Color Preview</p>
                    <p class="text-xs mt-2 text-gray-500">Enter a valid 3 or 6 digit hex.</p>
                </div>

                <div id="color-card" class="color-card hidden-card bg-gray-800 rounded-3xl border border-gray-700 overflow-hidden shadow-2xl absolute inset-0">
                    
                    <div id="color-swatch" class="h-40 w-full transition-colors duration-300 relative group cursor-pointer" onclick="copyColor()">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/20 transition-opacity">
                            <span class="text-white font-bold text-sm bg-black/50 px-3 py-1 rounded-full backdrop-blur">Copy CSS</span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        
                        <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                            <span class="text-xs font-bold text-gray-500">HEX</span>
                            <span id="preview-hex" class="font-mono text-xl font-bold text-white">#FFFFFF</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500">RGB</span>
                            <span id="preview-rgb" class="font-mono text-sm text-gray-300">rgb(255, 255, 255)</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500">HSL</span>
                            <span id="preview-hsl" class="font-mono text-sm text-gray-300">hsl(0, 0%, 100%)</span>
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
        // DOM Elements
        const decInput = document.getElementById('dec-input');
        const hexInput = document.getElementById('hex-input');
        const binDisplay = document.getElementById('bin-display');
        
        const placeholder = document.getElementById('placeholder');
        const colorCard = document.getElementById('color-card');
        const colorSwatch = document.getElementById('color-swatch');
        const previewHex = document.getElementById('preview-hex');
        const previewRgb = document.getElementById('preview-rgb');
        const previewHsl = document.getElementById('preview-hsl');

        // --- CONVERSION LOGIC ---

        function updateFromDec() {
            let val = decInput.value;
            
            if (val === '') {
                hexInput.value = '';
                binDisplay.innerText = '0000 0000';
                hideColor();
                return;
            }

            // Cap for color safety mostly
            let intVal = parseInt(val);
            if (isNaN(intVal)) return;

            hexInput.value = intVal.toString(16).toUpperCase();
            updateBin(intVal);
            checkColor(hexInput.value);
        }

        function updateFromHex() {
            let val = hexInput.value.replace(/[^0-9A-Fa-f]/g, ''); // Clean
            
            if (val === '') {
                decInput.value = '';
                binDisplay.innerText = '0000 0000';
                hideColor();
                return;
            }

            let intVal = parseInt(val, 16);
            if (isNaN(intVal)) return;

            decInput.value = intVal;
            updateBin(intVal);
            checkColor(val);
        }

        function updateBin(intVal) {
            let bin = intVal.toString(2);
            // Add spaces every 4 bits for readability
            // Pad to at least 8 bits if small
            if(bin.length < 8) bin = bin.padStart(8, '0');
            
            // Regex to add space every 4 chars from the end
            bin = bin.replace(/(.{4})/g, '$1 ').trim();
            
            binDisplay.innerText = bin;
        }

        // --- COLOR ENGINE ---

        function checkColor(hexStr) {
            // Valid color hex lengths: 3 (FFF) or 6 (FFFFFF)
            if (hexStr.length === 3 || hexStr.length === 6) {
                showColor(hexStr);
            } else {
                hideColor();
            }
        }

        function showColor(hex) {
            // Expand short hex
            if (hex.length === 3) {
                hex = hex.split('').map(char => char + char).join('');
            }
            
            const r = parseInt(hex.substring(0,2), 16);
            const g = parseInt(hex.substring(2,4), 16);
            const b = parseInt(hex.substring(4,6), 16);

            // RGB String
            const rgbStr = `rgb(${r}, ${g}, ${b})`;
            
            // Calculate HSL
            const hslStr = rgbToHsl(r, g, b);

            // Update UI
            colorSwatch.style.backgroundColor = rgbStr;
            previewHex.innerText = '#' + hex.toUpperCase();
            previewRgb.innerText = rgbStr;
            previewHsl.innerText = hslStr;

            placeholder.style.opacity = '0';
            colorCard.classList.remove('hidden-card');
            colorCard.classList.add('active');
        }

        function hideColor() {
            placeholder.style.opacity = '1';
            colorCard.classList.remove('active');
            colorCard.classList.add('hidden-card');
        }

        // --- UTILS ---

        function rgbToHsl(r, g, b) {
            r /= 255, g /= 255, b /= 255;
            const max = Math.max(r, g, b), min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;

            if (max === min) {
                h = s = 0; // achromatic
            } else {
                const d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                    case g: h = (b - r) / d + 2; break;
                    case b: h = (r - g) / d + 4; break;
                }
                h /= 6;
            }
            return `hsl(${(h * 360).toFixed(0)}, ${(s * 100).toFixed(0)}%, ${(l * 100).toFixed(0)}%)`;
        }

        function copyValue(id) {
            const el = document.getElementById(id);
            if (!el.value) return;
            navigator.clipboard.writeText(el.value);
            // Visual feedback could be added here
            const originalBorder = el.style.borderColor;
            el.style.borderColor = "#10b981";
            setTimeout(() => el.style.borderColor = "", 200);
        }

        function copyColor() {
            navigator.clipboard.writeText(previewHex.innerText);
            alert("Color copied!");
        }

        // Listeners
        decInput.addEventListener('input', updateFromDec);
        hexInput.addEventListener('input', updateFromHex);

    </script>
</body>
</html>