<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Select */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.2em 1.2em;
            padding-right: 2.5rem;
            appearance: none;
        }

        /* Ingredient Icons in Select (Simulated via options) */
        option { padding: 10px; }

        /* The Balance Scale Animation */
        .scale-arm {
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-origin: center;
        }
        
        /* Result Card */
        .result-card {
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mode Toggle */
        .mode-btn {
            transition: all 0.2s;
        }
        .mode-btn.active {
            background-color: #f59e0b; /* Amber */
            color: #111827;
            font-weight: bold;
        }
        .mode-btn:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-7 space-y-6">
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-amber-400">Kitchen Converter</h1>
                        <p class="text-gray-400 text-sm">Baking conversions by density.</p>
                    </div>
                    <div class="bg-gray-800 p-1 rounded-lg flex border border-gray-700">
                        <button onclick="setMode('vol-to-wgt')" id="btn-vol" class="mode-btn active px-3 py-1 text-xs rounded-md">Vol ➜ Wgt</button>
                        <button onclick="setMode('wgt-to-vol')" id="btn-wgt" class="mode-btn px-3 py-1 text-xs rounded-md text-gray-400">Wgt ➜ Vol</button>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Ingredient</label>
                    <select id="ingredient-select" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg font-bold focus:border-amber-500 outline-none cursor-pointer">
                        <optgroup label="Flours & Grains">
                            <option value="120">All-Purpose Flour (120g/cup)</option>
                            <option value="130">Bread Flour (130g/cup)</option>
                            <option value="96">Cake Flour (96g/cup)</option>
                            <option value="90">Rolled Oats (90g/cup)</option>
                        </optgroup>
                        <optgroup label="Sugars">
                            <option value="200">Granulated Sugar (200g/cup)</option>
                            <option value="220">Brown Sugar, Packed (220g/cup)</option>
                            <option value="120">Powdered Sugar (120g/cup)</option>
                            <option value="340">Honey / Syrup (340g/cup)</option>
                        </optgroup>
                        <optgroup label="Fats & Dairy">
                            <option value="227">Butter (227g/cup)</option>
                            <option value="215">Oil (Vegetable/Olive) (215g/cup)</option>
                            <option value="240">Milk / Water (240g/cup)</option>
                            <option value="230">Heavy Cream (230g/cup)</option>
                        </optgroup>
                        <optgroup label="Other">
                            <option value="150">Chocolate Chips (150g/cup)</option>
                            <option value="95">Cocoa Powder (95g/cup)</option>
                            <option value="270">Salt, Table (270g/cup)</option>
                        </optgroup>
                    </select>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 grid grid-cols-2 gap-4">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Amount</label>
                        <input type="number" id="input-amount" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white text-2xl font-bold focus:border-amber-500 outline-none placeholder-gray-600" value="1">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Unit</label>
                        <select id="input-unit" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white text-sm font-bold focus:border-amber-500 outline-none cursor-pointer h-[58px]">
                            </select>
                    </div>

                </div>

            </div>

            <div class="lg:col-span-5 flex flex-col items-center justify-start lg:sticky lg:top-8">
                
                <div class="w-full bg-white text-gray-900 rounded-[2rem] p-8 shadow-2xl relative overflow-hidden mb-6 result-card">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    
                    <div class="text-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 block">Converted To</span>
                        <div class="text-6xl font-black mono-font tracking-tight text-gray-900 mb-2" id="result-main">120</div>
                        <div class="text-xl font-bold text-amber-600 uppercase" id="result-unit">Grams</div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center text-sm font-bold text-gray-400">
                        <span>Alternate:</span>
                        <span class="text-gray-600" id="result-alt">4.2 oz</span>
                    </div>
                </div>

                <div class="w-full bg-gray-800 rounded-2xl border border-gray-700 p-8 flex flex-col items-center justify-center h-48 relative">
                    <div class="w-32 h-1 bg-gray-600 rounded scale-arm mb-2"></div>
                    <div class="w-4 h-12 bg-gray-700 rounded-b-lg"></div>
                    <div class="w-24 h-1 bg-gray-700 absolute bottom-8 rounded-full"></div>
                    
                    <div class="absolute top-4 left-4 text-xs font-bold text-gray-500">DENSITY</div>
                    <div class="absolute top-4 right-4 text-xs font-bold text-emerald-400 mono-font" id="density-display">120g/cup</div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const ingredientSelect = document.getElementById('ingredient-select');
        const inputAmount = document.getElementById('input-amount');
        const inputUnit = document.getElementById('input-unit');
        
        const resultMain = document.getElementById('result-main');
        const resultUnit = document.getElementById('result-unit');
        const resultAlt = document.getElementById('result-alt');
        const densityDisplay = document.getElementById('density-display');
        
        const btnVol = document.getElementById('btn-vol');
        const btnWgt = document.getElementById('btn-wgt');

        // Config
        // Base density is Grams per 1 Cup (US)
        let mode = 'vol-to-wgt'; // or 'wgt-to-vol'

        const UNITS = {
            vol: [
                { val: 1, label: 'Cup (US)' },
                { val: 0.0625, label: 'Tablespoon' },
                { val: 0.0208, label: 'Teaspoon' },
                { val: 1000, label: 'Milliliter (ml)' }, // special logic for ml
                { val: 1000, label: 'Liter' }
            ],
            wgt: [
                { val: 1, label: 'Grams (g)' },
                { val: 1000, label: 'Kilograms (kg)' },
                { val: 28.3495, label: 'Ounces (oz)' },
                { val: 453.592, label: 'Pounds (lb)' }
            ]
        };

        // --- SETUP ---

        function setMode(newMode) {
            mode = newMode;
            
            // Toggle Buttons
            if (mode === 'vol-to-wgt') {
                btnVol.classList.add('active', 'text-gray-900');
                btnVol.classList.remove('text-gray-400');
                btnWgt.classList.remove('active', 'text-gray-900');
                btnWgt.classList.add('text-gray-400');
            } else {
                btnWgt.classList.add('active', 'text-gray-900');
                btnWgt.classList.remove('text-gray-400');
                btnVol.classList.remove('active', 'text-gray-900');
                btnVol.classList.add('text-gray-400');
            }

            // Populate Units
            populateUnits();
            calculate();
        }

        function populateUnits() {
            inputUnit.innerHTML = '';
            const list = mode === 'vol-to-wgt' ? UNITS.vol : UNITS.wgt;
            
            list.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u.label; // Use label as ID for simplicity in logic below
                opt.text = u.label;
                inputUnit.appendChild(opt);
            });
        }

        // --- CORE LOGIC ---

        function calculate() {
            const density = parseFloat(ingredientSelect.value); // Grams per Cup
            const amount = parseFloat(inputAmount.value);
            const unitType = inputUnit.value;
            
            if (isNaN(amount)) {
                resultMain.textContent = '--';
                return;
            }

            // Update Density Display
            densityDisplay.textContent = `${density}g / cup`;

            let grams = 0;

            if (mode === 'vol-to-wgt') {
                // Converting Volume -> Weight
                // First convert input to Cups
                let cups = 0;
                
                if (unitType === 'Cup (US)') cups = amount;
                else if (unitType === 'Tablespoon') cups = amount * 0.0625;
                else if (unitType === 'Teaspoon') cups = amount * 0.0208;
                else if (unitType === 'Milliliter (ml)') cups = amount / 236.588; // 1 cup = ~236.5ml
                else if (unitType === 'Liter') cups = (amount * 1000) / 236.588;

                // Calculate Grams
                grams = cups * density;

                // Display Results
                resultMain.textContent = Math.round(grams);
                resultUnit.textContent = "Grams";
                
                // Alt: Ounces
                const oz = grams / 28.3495;
                resultAlt.textContent = `${oz.toFixed(2)} oz`;

            } else {
                // Converting Weight -> Volume
                // First convert input to Grams
                if (unitType === 'Grams (g)') grams = amount;
                else if (unitType === 'Kilograms (kg)') grams = amount * 1000;
                else if (unitType === 'Ounces (oz)') grams = amount * 28.3495;
                else if (unitType === 'Pounds (lb)') grams = amount * 453.592;

                // Calculate Cups
                const cups = grams / density;

                // Display Results (Cups + Tbsp approximation?)
                // Just Cups for simplicity, maybe ML alt
                resultMain.textContent = cups.toFixed(2);
                resultUnit.textContent = "Cups";

                // Alt: ML
                const ml = cups * 236.588;
                resultAlt.textContent = `${Math.round(ml)} ml`;
            }
        }

        // --- LISTENERS ---

        ingredientSelect.addEventListener('change', calculate);
        inputAmount.addEventListener('input', calculate);
        inputUnit.addEventListener('change', calculate);

        // Init
        setMode('vol-to-wgt');

    </script>
</body>
</html>