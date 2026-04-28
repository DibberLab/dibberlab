<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Gradient Generator | Dibber Lab</title>
    
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
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -6px; 
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
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
            width: 32px;
            height: 32px;
            border-radius: 6px;
            padding: 0;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid #4b5563;
        }
        input[type=color]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type=color]::-webkit-color-swatch { border: none; }

        /* Code Block */
        .code-block {
            font-family: 'JetBrains Mono', monospace;
            background-image: linear-gradient(to bottom, #1f2937, #111827);
        }

        /* Checkboard Pattern for Transparency (Visual Polish) */
        .checkboard {
            background-color: #ffffff;
            background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%), 
                              linear-gradient(-45deg, #e5e7eb 25%, transparent 25%), 
                              linear-gradient(45deg, transparent 75%, #e5e7eb 75%), 
                              linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">CSS Gradient Generator</h1>
            <p class="text-center text-gray-400 mb-8">Design linear and radial gradients visually.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-6">
                    <div class="w-full aspect-video rounded-xl border border-gray-600 shadow-lg checkboard relative overflow-hidden group">
                        <div id="preview-box" class="absolute inset-0 w-full h-full transition-all duration-75"></div>
                    </div>

                    <div class="relative">
                        <div class="absolute top-0 left-0 bg-gray-700 text-xs text-gray-300 px-3 py-1 rounded-br-lg rounded-tl-lg font-bold">CSS Code</div>
                        <textarea id="code-output" readonly class="code-block w-full h-24 p-4 pt-8 rounded-xl text-emerald-400 text-sm focus:outline-none resize-none"></textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-gray-700 hover:bg-gray-600 text-white p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="bg-gray-900 p-6 rounded-xl border border-gray-700 space-y-6">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                            <select id="type-select" class="w-full bg-gray-800 border border-gray-600 rounded-lg py-2 px-3 text-white focus:border-amber-500">
                                <option value="linear">Linear</option>
                                <option value="radial">Radial</option>
                            </select>
                        </div>
                        <div id="angle-container">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Angle <span id="angle-val" class="text-white ml-1">90°</span></label>
                            <input type="range" id="angle-input" min="0" max="360" value="90" class="w-full h-2">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-xs font-bold text-gray-500 uppercase">Color Stops</label>
                            <button id="add-stop-btn" class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1 rounded font-bold transition-colors">
                                + ADD COLOR
                            </button>
                        </div>
                        
                        <div id="stops-container" class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                            </div>
                    </div>
                    
                    <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Quick Presets</label>
                         <div class="flex gap-2">
                             <button class="preset-btn w-8 h-8 rounded-full border border-gray-600" style="background: linear-gradient(90deg, #3b82f6, #8b5cf6);" data-colors="#3b82f6,#8b5cf6"></button>
                             <button class="preset-btn w-8 h-8 rounded-full border border-gray-600" style="background: linear-gradient(90deg, #f59e0b, #ef4444);" data-colors="#f59e0b,#ef4444"></button>
                             <button class="preset-btn w-8 h-8 rounded-full border border-gray-600" style="background: linear-gradient(90deg, #10b981, #3b82f6);" data-colors="#10b981,#3b82f6"></button>
                             <button class="preset-btn w-8 h-8 rounded-full border border-gray-600" style="background: linear-gradient(90deg, #ec4899, #f43f5e, #fbbf24);" data-colors="#ec4899,#f43f5e,#fbbf24"></button>
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
        const previewBox = document.getElementById('preview-box');
        const codeOutput = document.getElementById('code-output');
        const typeSelect = document.getElementById('type-select');
        const angleInput = document.getElementById('angle-input');
        const angleVal = document.getElementById('angle-val');
        const angleContainer = document.getElementById('angle-container');
        const stopsContainer = document.getElementById('stops-container');
        const addStopBtn = document.getElementById('add-stop-btn');
        const copyBtn = document.getElementById('copy-btn');
        const presetBtns = document.querySelectorAll('.preset-btn');

        // State
        let state = {
            type: 'linear',
            angle: 90,
            stops: [
                { color: '#3b82f6', pos: 0 },
                { color: '#8b5cf6', pos: 100 }
            ]
        };

        // --- CORE FUNCTIONS ---

        function renderStops() {
            stopsContainer.innerHTML = '';
            
            state.stops.forEach((stop, index) => {
                const row = document.createElement('div');
                row.className = "flex items-center gap-3 bg-gray-800 p-2 rounded-lg border border-gray-700";
                
                // Color Picker
                const colorInput = document.createElement('input');
                colorInput.type = 'color';
                colorInput.value = stop.color;
                colorInput.addEventListener('input', (e) => updateStop(index, 'color', e.target.value));
                
                // Position Slider
                const sliderContainer = document.createElement('div');
                sliderContainer.className = "flex-grow";
                const slider = document.createElement('input');
                slider.type = 'range';
                slider.min = 0; 
                slider.max = 100;
                slider.value = stop.pos;
                slider.className = "w-full h-1 block";
                slider.addEventListener('input', (e) => updateStop(index, 'pos', parseInt(e.target.value)));
                
                const posLabel = document.createElement('div');
                posLabel.className = "text-xs text-gray-500 text-right mt-1 font-mono";
                posLabel.textContent = stop.pos + '%';
                
                sliderContainer.appendChild(slider);
                sliderContainer.appendChild(posLabel);

                // Delete Button
                const delBtn = document.createElement('button');
                delBtn.innerHTML = "&times;";
                delBtn.className = "text-gray-500 hover:text-red-400 font-bold text-xl px-2 disabled:opacity-30 disabled:hover:text-gray-500";
                delBtn.disabled = state.stops.length <= 2; // Min 2 stops
                delBtn.onclick = () => removeStop(index);

                row.appendChild(colorInput);
                row.appendChild(sliderContainer);
                row.appendChild(delBtn);
                stopsContainer.appendChild(row);
            });
            
            updateGradient();
        }

        function updateStop(index, field, value) {
            state.stops[index][field] = value;
            
            // If dragging position, just update label and CSS, don't re-render entire DOM (prevents focus loss)
            if (field === 'pos') {
                updateGradient();
                // Find label and update text manually
                const rows = stopsContainer.children;
                if(rows[index]) {
                    rows[index].querySelector('.font-mono').textContent = value + '%';
                }
            } else {
                updateGradient();
            }
        }

        function addStop() {
            // Find a nice middle color/position logic later, for now just clone last color
            const last = state.stops[state.stops.length - 1];
            state.stops.push({ color: last.color, pos: 100 });
            
            // Redistribute positions evenly? Optional. Let's just update render.
            // Better UX: add it halfway? Let's just stick to pushing to end.
            renderStops();
        }

        function removeStop(index) {
            if (state.stops.length <= 2) return;
            state.stops.splice(index, 1);
            renderStops();
        }

        function updateGradient() {
            // Sort stops by position for valid CSS logic
            const sortedStops = [...state.stops].sort((a, b) => a.pos - b.pos);
            
            const stopStr = sortedStops.map(s => `${s.color} ${s.pos}%`).join(', ');
            
            let css = '';
            if (state.type === 'linear') {
                css = `linear-gradient(${state.angle}deg, ${stopStr})`;
                angleContainer.style.opacity = '1';
                angleContainer.style.pointerEvents = 'auto';
            } else {
                css = `radial-gradient(circle, ${stopStr})`;
                angleContainer.style.opacity = '0.3';
                angleContainer.style.pointerEvents = 'none';
            }

            previewBox.style.background = css;
            codeOutput.value = `background: ${css};`;
        }

        // --- LISTENERS ---

        typeSelect.addEventListener('change', (e) => {
            state.type = e.target.value;
            updateGradient();
        });

        angleInput.addEventListener('input', (e) => {
            state.angle = e.target.value;
            angleVal.textContent = state.angle + '°';
            updateGradient();
        });

        addStopBtn.addEventListener('click', addStop);

        // Presets
        presetBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const colors = btn.dataset.colors.split(',');
                // Reset state to these colors evenly spaced
                state.stops = colors.map((c, i) => ({
                    color: c,
                    pos: Math.round((i / (colors.length - 1)) * 100)
                }));
                renderStops();
            });
        });

        // Copy
        copyBtn.addEventListener('click', () => {
            codeOutput.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(codeOutput.value);
            
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">COPIED</span>`;
            setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
        });

        // Init
        renderStops();

    </script>
</body>
</html>