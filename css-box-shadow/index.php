<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box Shadow Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none; 
            background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }

        /* Color Input */
        input[type=color] {
            -webkit-appearance: none;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
            cursor: pointer;
        }
        input[type=color]::-webkit-color-swatch-wrapper {
            padding: 0; 
        }
        input[type=color]::-webkit-color-swatch {
            border: none;
        }

        /* Checkbox */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10b981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981;
        }

        /* Code Block */
        .code-block {
            font-family: 'JetBrains Mono', monospace;
            background-image: linear-gradient(to bottom, #1f2937, #111827);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">CSS Box Shadow Generator</h1>
            <p class="text-center text-gray-400 mb-8">Visually design shadows and copy the code.</p>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                <div class="md:col-span-5 space-y-6">
                    
                    <div class="bg-gray-900 p-5 rounded-xl border border-gray-700 space-y-5">
                        
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Horizontal Offset</span>
                                <span id="val-x">0px</span>
                            </div>
                            <input type="range" id="ctrl-x" min="-100" max="100" value="10" class="w-full">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Vertical Offset</span>
                                <span id="val-y">0px</span>
                            </div>
                            <input type="range" id="ctrl-y" min="-100" max="100" value="10" class="w-full">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Blur Radius</span>
                                <span id="val-blur">0px</span>
                            </div>
                            <input type="range" id="ctrl-blur" min="0" max="100" value="20" class="w-full">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Spread Radius</span>
                                <span id="val-spread">0px</span>
                            </div>
                            <input type="range" id="ctrl-spread" min="-50" max="50" value="0" class="w-full">
                        </div>
                    </div>

                    <div class="bg-gray-900 p-5 rounded-xl border border-gray-700 grid grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Shadow Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="ctrl-color" value="#000000">
                                <span class="text-sm font-mono text-gray-300" id="hex-display">#000000</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-bold uppercase mb-2">Opacity</label>
                            <input type="range" id="ctrl-opacity" min="0" max="1" step="0.01" value="0.5" class="w-full h-8">
                        </div>
                    </div>

                    <div class="flex items-center justify-between bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <span class="text-sm font-bold text-gray-300">Inset Shadow</span>
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="toggle" id="ctrl-inset" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300"/>
                            <label for="ctrl-inset" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-600 cursor-pointer"></label>
                        </div>
                    </div>

                </div>

                <div class="md:col-span-7 flex flex-col gap-6">
                    
                    <div class="flex-grow bg-white rounded-xl flex items-center justify-center min-h-[300px] border border-gray-600 relative overflow-hidden" id="preview-canvas">
                        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                        
                        <div id="demo-box" class="w-48 h-48 bg-amber-500 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-2xl transition-all duration-75">
                            Preview
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute top-0 left-0 bg-gray-700 text-xs text-gray-300 px-3 py-1 rounded-br-lg rounded-tl-lg font-bold">CSS Code</div>
                        <textarea id="code-output" readonly class="code-block w-full h-32 p-4 pt-8 rounded-xl text-emerald-400 text-sm focus:outline-none resize-none">box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.5);</textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-gray-700 hover:bg-gray-600 text-white p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
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
        const demoBox = document.getElementById('demo-box');
        const codeOutput = document.getElementById('code-output');
        const copyBtn = document.getElementById('copy-btn');
        const hexDisplay = document.getElementById('hex-display');

        // Inputs
        const inputs = {
            x: document.getElementById('ctrl-x'),
            y: document.getElementById('ctrl-y'),
            blur: document.getElementById('ctrl-blur'),
            spread: document.getElementById('ctrl-spread'),
            color: document.getElementById('ctrl-color'),
            opacity: document.getElementById('ctrl-opacity'),
            inset: document.getElementById('ctrl-inset')
        };

        // Value Displays
        const displays = {
            x: document.getElementById('val-x'),
            y: document.getElementById('val-y'),
            blur: document.getElementById('val-blur'),
            spread: document.getElementById('val-spread')
        };

        // --- CORE LOGIC ---
        function updateShadow() {
            // 1. Get Values
            const x = inputs.x.value;
            const y = inputs.y.value;
            const blur = inputs.blur.value;
            const spread = inputs.spread.value;
            const inset = inputs.inset.checked ? 'inset ' : '';
            
            // 2. Color Conversion (Hex to RGBA)
            const hex = inputs.color.value;
            const r = parseInt(hex.substring(1, 3), 16);
            const g = parseInt(hex.substring(3, 5), 16);
            const b = parseInt(hex.substring(5, 7), 16);
            const a = inputs.opacity.value;
            
            const rgba = `rgba(${r}, ${g}, ${b}, ${a})`;

            // 3. Construct CSS String
            const cssValue = `${inset}${x}px ${y}px ${blur}px ${spread}px ${rgba}`;
            const fullCss = `box-shadow: ${cssValue};`;

            // 4. Update DOM
            demoBox.style.boxShadow = cssValue;
            codeOutput.value = fullCss;
            hexDisplay.textContent = hex.toUpperCase();

            // 5. Update Text Labels
            displays.x.textContent = `${x}px`;
            displays.y.textContent = `${y}px`;
            displays.blur.textContent = `${blur}px`;
            displays.spread.textContent = `${spread}px`;
        }

        // --- LISTENERS ---
        Object.values(inputs).forEach(input => {
            input.addEventListener('input', updateShadow);
        });

        // Copy Function
        copyBtn.addEventListener('click', () => {
            codeOutput.select();
            document.execCommand('copy'); // Legacy support
            if (navigator.clipboard) {
                navigator.clipboard.writeText(codeOutput.value);
            }
            
            // Visual feedback
            const originalIcon = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">COPIED</span>`;
            setTimeout(() => {
                copyBtn.innerHTML = originalIcon;
            }, 1500);
        });

        // Initialize
        updateShadow();

    </script>
</body>
</html>