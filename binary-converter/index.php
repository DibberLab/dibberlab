<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binary Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Main Display Input */
        .big-input {
            background: transparent;
            border: none;
            width: 100%;
            text-align: center;
            font-weight: 900;
            color: #f3f4f6;
            transition: all 0.2s;
        }
        .big-input:focus {
            outline: none;
            text-shadow: 0 0 20px rgba(255,255,255,0.2);
        }

        /* Bit Switch Styling */
        .bit-switch {
            width: 100%;
            aspect-ratio: 0.6;
            background: #1f2937;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            border: 2px solid #374151;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding-bottom: 8px;
        }

        .bit-switch:hover {
            border-color: #6b7280;
            transform: translateY(-2px);
        }

        /* Active State (1) */
        .bit-switch.active {
            background: #10b981; /* Emerald */
            border-color: #34d399;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }
        .bit-switch.active .bit-val { color: #064e3b; }

        /* The "Light" Indicator inside the switch */
        .bit-led {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #374151;
            margin-bottom: auto; /* Push to top */
            margin-top: 10px;
            transition: 0.2s;
        }
        .bit-switch.active .bit-led {
            background: #ecfdf5;
            box-shadow: 0 0 5px white;
        }

        .bit-label {
            font-size: 0.6rem;
            color: #6b7280;
            margin-top: 4px;
            font-family: 'JetBrains Mono', monospace;
        }

        /* Hex/Octal Cards */
        .conv-card {
            transition: border-color 0.2s;
        }
        .conv-card:focus-within { border-color: #f59e0b; }

    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-5xl mx-auto flex flex-col items-center">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Bitwise</h1>
                <p class="text-gray-400 text-sm">Interactive Decimal to Binary converter.</p>
            </div>

            <div class="w-full max-w-md bg-gray-800 border-2 border-gray-700 rounded-2xl p-6 mb-12 shadow-2xl relative group">
                <label class="absolute top-4 left-0 w-full text-center text-xs font-bold text-gray-500 uppercase tracking-widest group-focus-within:text-amber-500 transition-colors">Decimal</label>
                <input type="number" id="dec-input" class="big-input text-6xl md:text-7xl pt-6 mono-font" value="0" min="0" max="255">
            </div>

            <div class="w-full bg-gray-800/50 rounded-2xl p-6 border border-gray-700 mb-12">
                
                <div class="flex justify-between items-end mb-4 px-2">
                    <h3 class="text-sm font-bold text-gray-400 uppercase">8-Bit Visualizer</h3>
                    <div class="font-mono text-emerald-400 text-xl font-bold tracking-widest" id="bin-string">00000000</div>
                </div>

                <div class="grid grid-cols-8 gap-2 md:gap-4">
                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-7" onclick="toggleBit(7)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">128</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-6" onclick="toggleBit(6)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">64</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-5" onclick="toggleBit(5)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">32</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-4" onclick="toggleBit(4)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">16</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-3" onclick="toggleBit(3)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">8</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-2" onclick="toggleBit(2)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">4</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-1" onclick="toggleBit(1)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">2</span>
                    </div>

                    <div class="flex flex-col items-center gap-1">
                        <button class="bit-switch" id="bit-0" onclick="toggleBit(0)">
                            <div class="bit-led"></div>
                            <span class="text-xl font-black bit-val">0</span>
                        </button>
                        <span class="bit-label">1</span>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl">
                
                <div class="conv-card bg-gray-800 p-4 rounded-xl border border-gray-700 flex flex-col">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1">Hexadecimal (Base 16)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 font-mono text-xl">0x</span>
                        <input type="text" id="hex-input" class="bg-transparent text-white font-mono font-bold text-2xl w-full focus:outline-none uppercase" value="00">
                    </div>
                </div>

                <div class="conv-card bg-gray-800 p-4 rounded-xl border border-gray-700 flex flex-col">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1">Octal (Base 8)</label>
                    <input type="text" id="oct-input" class="bg-transparent text-white font-mono font-bold text-2xl w-full focus:outline-none" value="0">
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
        const octInput = document.getElementById('oct-input');
        const binString = document.getElementById('bin-string');
        const switches = [];

        // Cache switch elements (0 to 7)
        for(let i=0; i<8; i++) {
            switches.push(document.getElementById(`bit-${i}`));
        }

        // --- CORE LOGIC ---

        function updateAll(sourceType) {
            let val = 0;

            // 1. Get Value based on source
            if (sourceType === 'dec') {
                val = parseInt(decInput.value) || 0;
            } else if (sourceType === 'hex') {
                val = parseInt(hexInput.value, 16) || 0;
            } else if (sourceType === 'oct') {
                val = parseInt(octInput.value, 8) || 0;
            }

            // Cap at 255 (8-bit) for the visualizer, 
            // though logic works for larger, visualizer only shows first 8
            // Let's cap input for simplicity in this demo
            if (val > 255 && sourceType === 'dec') {
                val = 255; 
                decInput.value = 255;
            }
            if (val < 0) val = 0;

            // 2. Update Inputs (if they weren't the source)
            if (sourceType !== 'dec') decInput.value = val;
            if (sourceType !== 'hex') hexInput.value = val.toString(16).toUpperCase();
            if (sourceType !== 'oct') octInput.value = val.toString(8);

            // 3. Update Visualizer
            updateVisualizer(val);
        }

        function updateVisualizer(val) {
            // Update Binary String
            const bin = val.toString(2).padStart(8, '0');
            binString.textContent = bin;

            // Update Switches
            for(let i=0; i<8; i++) {
                // Check if bit 'i' is set
                // (val >> i) & 1
                const isSet = (val >> i) & 1;
                const el = switches[i];
                const valSpan = el.querySelector('.bit-val');

                if (isSet) {
                    el.classList.add('active');
                    valSpan.textContent = '1';
                } else {
                    el.classList.remove('active');
                    valSpan.textContent = '0';
                }
            }
        }

        function toggleBit(bitIndex) {
            let currentVal = parseInt(decInput.value) || 0;
            
            // XOR to toggle specific bit
            // 1 << bitIndex creates a mask (e.g., 00000100)
            const newVal = currentVal ^ (1 << bitIndex);
            
            decInput.value = newVal;
            updateAll('dec');
        }

        // --- LISTENERS ---

        decInput.addEventListener('input', () => updateAll('dec'));
        
        hexInput.addEventListener('input', () => updateAll('hex'));
        
        octInput.addEventListener('input', () => updateAll('oct'));

        // Init
        updateAll('dec');

    </script>
</body>
</html>