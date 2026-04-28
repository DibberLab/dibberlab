<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Inputs */
        input[type="number"] {
            -moz-appearance: textfield;
            font-family: 'JetBrains Mono', monospace;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Category Tabs */
        .cat-btn {
            transition: all 0.2s;
            white-space: nowrap;
        }
        .cat-btn:hover { background-color: #374151; color: white; }
        .cat-btn.active {
            background-color: #f59e0b; /* Amber */
            color: #111827;
            font-weight: bold;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 2px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Select Dropdown styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Unit Converter</h1>
                <p class="text-center text-gray-400">Precision conversion for science, engineering, and daily life.</p>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-4 mb-6 custom-scrollbar justify-start md:justify-center">
                <button class="cat-btn active px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('length')">📏 Length</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('mass')">⚖️ Mass</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('volume')">🥤 Volume</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('temp')">🌡️ Temp</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('area')">📐 Area</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('time')">⏱️ Time</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('digital')">💾 Digital</button>
                <button class="cat-btn px-4 py-2 rounded-full bg-gray-900 border border-gray-700 text-gray-400 text-sm" onclick="setCategory('speed')">🚀 Speed</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-11 gap-4 items-center">
                
                <div class="lg:col-span-5 bg-gray-900 p-6 rounded-xl border border-gray-600 focus-within:border-amber-500 transition-colors">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">From</label>
                    <input type="number" id="input-from" class="w-full bg-transparent text-white text-3xl font-bold focus:outline-none mb-4" placeholder="0">
                    <select id="select-from" class="w-full bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-lg p-3 font-bold cursor-pointer hover:bg-gray-700 transition-colors">
                        </select>
                </div>

                <div class="lg:col-span-1 flex justify-center">
                    <button id="swap-btn" class="p-3 bg-gray-700 hover:bg-gray-600 rounded-full border border-gray-500 transition-transform hover:rotate-180 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </button>
                </div>

                <div class="lg:col-span-5 bg-gray-900 p-6 rounded-xl border border-gray-600">
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">To</label>
                        <button onclick="copyResult()" class="text-xs text-emerald-400 hover:text-white font-bold uppercase transition-colors" id="copy-label">Copy</button>
                    </div>
                    <input type="number" id="input-to" class="w-full bg-transparent text-emerald-400 text-3xl font-bold focus:outline-none mb-4" placeholder="0" readonly>
                    <select id="select-to" class="w-full bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-lg p-3 font-bold cursor-pointer hover:bg-gray-700 transition-colors">
                        </select>
                </div>

            </div>

            <div class="mt-8 text-center">
                <div class="inline-block bg-gray-900/50 border border-gray-700 px-6 py-3 rounded-lg">
                    <span class="text-xs text-gray-500 font-bold uppercase block mb-1">Formula</span>
                    <span id="formula-display" class="text-sm font-mono text-gray-300">Select units to see conversion logic</span>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA STRUCTURE ---
        // Factors are relative to the first item (Base Unit)
        const units = {
            length: {
                base: 'Meter',
                items: {
                    'Meter': 1, 'Kilometer': 1000, 'Centimeter': 0.01, 'Millimeter': 0.001, 'Micrometer': 1e-6,
                    'Nanometer': 1e-9, 'Mile': 1609.34, 'Yard': 0.9144, 'Foot': 0.3048, 'Inch': 0.0254, 'Nautical Mile': 1852
                }
            },
            mass: {
                base: 'Kilogram',
                items: {
                    'Kilogram': 1, 'Gram': 0.001, 'Milligram': 1e-6, 'Metric Ton': 1000,
                    'Pound': 0.453592, 'Ounce': 0.0283495, 'Stone': 6.35029
                }
            },
            volume: {
                base: 'Liter',
                items: {
                    'Liter': 1, 'Milliliter': 0.001, 'Cubic Meter': 1000, 
                    'Gallon (US)': 3.78541, 'Quart (US)': 0.946353, 'Pint (US)': 0.473176, 
                    'Cup (US)': 0.24, 'Fluid Ounce (US)': 0.0295735
                }
            },
            area: {
                base: 'Square Meter',
                items: {
                    'Square Meter': 1, 'Square Kilometer': 1e6, 'Square Mile': 2.59e6, 
                    'Square Yard': 0.836127, 'Square Foot': 0.092903, 'Acre': 4046.86, 'Hectare': 10000
                }
            },
            time: {
                base: 'Second',
                items: {
                    'Second': 1, 'Minute': 60, 'Hour': 3600, 'Day': 86400, 
                    'Week': 604800, 'Month (Avg)': 2.628e6, 'Year': 3.154e7
                }
            },
            speed: {
                base: 'Meter/Second',
                items: {
                    'Meter/Second': 1, 'Kilometer/Hour': 0.277778, 'Mile/Hour': 0.44704, 
                    'Knot': 0.514444, 'Mach': 343
                }
            },
            digital: {
                base: 'Byte',
                items: {
                    'Bit': 0.125, 'Byte': 1, 'Kilobyte': 1024, 'Megabyte': 1.049e6, 
                    'Gigabyte': 1.074e9, 'Terabyte': 1.1e12, 'Petabyte': 1.126e15
                }
            },
            temp: {
                // Temperature is special, logic handled in calc function
                items: { 'Celsius': 'C', 'Fahrenheit': 'F', 'Kelvin': 'K' }
            }
        };

        // --- STATE ---
        let currentCat = 'length';
        
        // DOM Elements
        const selectFrom = document.getElementById('select-from');
        const selectTo = document.getElementById('select-to');
        const inputFrom = document.getElementById('input-from');
        const inputTo = document.getElementById('input-to');
        const formulaDisplay = document.getElementById('formula-display');
        const swapBtn = document.getElementById('swap-btn');
        const catBtns = document.querySelectorAll('.cat-btn');

        // --- INIT ---
        function setCategory(cat) {
            currentCat = cat;
            
            // UI Toggle
            catBtns.forEach(btn => btn.classList.remove('active'));
            // Find button by onclick text hack or just iterate logic
            // Simple way: re-render
            const targetBtn = Array.from(catBtns).find(b => b.getAttribute('onclick').includes(cat));
            if(targetBtn) targetBtn.classList.add('active');

            // Populate Selects
            const options = Object.keys(units[cat].items).map(u => `<option value="${u}">${u}</option>`).join('');
            selectFrom.innerHTML = options;
            selectTo.innerHTML = options;

            // Set Defaults (To different than From)
            const keys = Object.keys(units[cat].items);
            selectFrom.value = keys[0];
            selectTo.value = keys[1] || keys[0];

            calculate();
        }

        // --- CORE LOGIC ---
        function calculate() {
            const val = parseFloat(inputFrom.value);
            const unitFrom = selectFrom.value;
            const unitTo = selectTo.value;

            if (isNaN(val)) {
                inputTo.value = '';
                formulaDisplay.textContent = '-';
                return;
            }

            let result = 0;
            let formula = '';

            // Temperature Handling (Non-linear)
            if (currentCat === 'temp') {
                if (unitFrom === unitTo) {
                    result = val;
                } else if (unitFrom === 'Celsius') {
                    if (unitTo === 'Fahrenheit') { result = (val * 9/5) + 32; formula = `(${val}°C × 9/5) + 32 = ${result.toFixed(2)}°F`; }
                    if (unitTo === 'Kelvin') { result = val + 273.15; formula = `${val}°C + 273.15 = ${result.toFixed(2)}K`; }
                } else if (unitFrom === 'Fahrenheit') {
                    if (unitTo === 'Celsius') { result = (val - 32) * 5/9; formula = `(${val}°F - 32) × 5/9 = ${result.toFixed(2)}°C`; }
                    if (unitTo === 'Kelvin') { result = ((val - 32) * 5/9) + 273.15; formula = `((${val}°F - 32) × 5/9) + 273.15 = ${result.toFixed(2)}K`; }
                } else if (unitFrom === 'Kelvin') {
                    if (unitTo === 'Celsius') { result = val - 273.15; formula = `${val}K - 273.15 = ${result.toFixed(2)}°C`; }
                    if (unitTo === 'Fahrenheit') { result = ((val - 273.15) * 9/5) + 32; formula = `((${val}K - 273.15) × 9/5) + 32 = ${result.toFixed(2)}°F`; }
                }
            } 
            // Standard Linear Conversion
            else {
                const factorFrom = units[currentCat].items[unitFrom];
                const factorTo = units[currentCat].items[unitTo];
                
                // Convert to Base, then to Target
                const baseValue = val * factorFrom;
                result = baseValue / factorTo;

                // Create nice formula text
                formula = `Multiply by ${ (factorFrom/factorTo).toPrecision(4) }`;
            }

            // Formatting: Max 6 decimals, strip trailing zeros
            inputTo.value = parseFloat(result.toPrecision(10)) / 1; // Hack to remove trailing zeros
            if (currentCat !== 'temp') formulaDisplay.textContent = formula;
        }

        // --- ACTIONS ---
        swapBtn.addEventListener('click', () => {
            const temp = selectFrom.value;
            selectFrom.value = selectTo.value;
            selectTo.value = temp;
            calculate();
        });

        function copyResult() {
            if (!inputTo.value) return;
            navigator.clipboard.writeText(inputTo.value);
            const label = document.getElementById('copy-label');
            const original = label.textContent;
            label.textContent = "COPIED!";
            setTimeout(() => label.textContent = original, 1000);
        }

        // --- LISTENERS ---
        inputFrom.addEventListener('input', calculate);
        selectFrom.addEventListener('change', calculate);
        selectTo.addEventListener('change', calculate);

        // Init
        setCategory('length');

    </script>
</body>
</html>