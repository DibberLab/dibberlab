<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Number Input (Hide Spinners) */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }

        /* Toggle Switch */
        .unit-toggle {
            background-color: #1f2937;
            border-radius: 99px;
            padding: 4px;
            display: inline-flex;
            position: relative;
            cursor: pointer;
            border: 1px solid #374151;
        }
        .unit-option {
            z-index: 10;
            padding: 6px 24px;
            border-radius: 99px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.2s;
            user-select: none;
        }
        .toggle-pill {
            position: absolute;
            top: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: 50%;
            background-color: #f59e0b; /* Amber */
            border-radius: 99px;
            transition: transform 0.2s ease;
        }
        .unit-toggle.imperial .toggle-pill { transform: translateX(96%); } /* Adjust based on width */

        /* Gauge Bar */
        .gauge-container {
            height: 12px;
            border-radius: 99px;
            background: linear-gradient(to right, 
                #3b82f6 0%, #3b82f6 18.5%,  /* Underweight (Blue) */
                #10b981 18.5%, #10b981 25%, /* Normal (Emerald) */
                #f59e0b 25%, #f59e0b 30%,   /* Overweight (Amber) */
                #ef4444 30%, #ef4444 100%   /* Obese (Red) */
            );
            position: relative;
            overflow: visible;
        }
        .gauge-marker {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 4px;
            height: 24px;
            background-color: white;
            border: 1px solid #111827;
            border-radius: 2px;
            transition: left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">BMI Calculator</h1>
                <p class="text-center text-gray-400">Body Mass Index & Healthy Weight Range.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                
                <div class="flex flex-col">
                    
                    <div class="flex justify-center mb-8">
                        <div class="unit-toggle" id="unit-toggle">
                            <div class="toggle-pill"></div>
                            <div class="unit-option text-gray-900" id="opt-metric">Metric</div>
                            <div class="unit-option text-gray-400" id="opt-imperial">Imperial</div>
                        </div>
                    </div>

                    <div id="inputs-metric" class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Height (cm)</label>
                            <input type="number" id="m-height" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-xl font-bold focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-700" placeholder="180">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Weight (kg)</label>
                            <input type="number" id="m-weight" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-xl font-bold focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-700" placeholder="75">
                        </div>
                    </div>

                    <div id="inputs-imperial" class="space-y-6 hidden">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Height</label>
                            <div class="flex gap-4">
                                <div class="relative flex-1">
                                    <input type="number" id="i-feet" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-xl font-bold focus:outline-none focus:border-amber-500" placeholder="5">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">ft</span>
                                </div>
                                <div class="relative flex-1">
                                    <input type="number" id="i-inches" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-xl font-bold focus:outline-none focus:border-amber-500" placeholder="10">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">in</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Weight (lbs)</label>
                            <input type="number" id="i-weight" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-xl font-bold focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-700" placeholder="165">
                        </div>
                    </div>

                </div>

                <div class="flex flex-col justify-center">
                    
                    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-8 text-center shadow-lg relative overflow-hidden mb-6">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your BMI</span>
                        <div class="text-6xl font-black text-white mt-2 mb-2 mono-font" id="res-bmi">--.-</div>
                        <div class="text-lg font-bold" id="res-category" style="color: #4b5563;">Enter Details</div>
                    </div>

                    <div class="mb-8 px-2">
                        <div class="gauge-container mb-2">
                            <div id="gauge-marker" class="gauge-marker" style="left: 0%;"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-gray-500 font-bold uppercase">
                            <span>15</span>
                            <span>18.5</span>
                            <span>25</span>
                            <span>30</span>
                            <span>40</span>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-500 uppercase">Healthy Weight Range</span>
                            <span class="text-[10px] text-gray-600">Based on your height</span>
                        </div>
                        <div class="text-xl font-bold text-emerald-400" id="res-range">-- - --</div>
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
        const toggle = document.getElementById('unit-toggle');
        const optMetric = document.getElementById('opt-metric');
        const optImperial = document.getElementById('opt-imperial');
        
        const inputsMetric = document.getElementById('inputs-metric');
        const inputsImperial = document.getElementById('inputs-imperial');
        
        // Inputs
        const mHeight = document.getElementById('m-height');
        const mWeight = document.getElementById('m-weight');
        const iFeet = document.getElementById('i-feet');
        const iInches = document.getElementById('i-inches');
        const iWeight = document.getElementById('i-weight');

        // Outputs
        const resBmi = document.getElementById('res-bmi');
        const resCategory = document.getElementById('res-category');
        const resRange = document.getElementById('res-range');
        const gaugeMarker = document.getElementById('gauge-marker');

        let isMetric = true;

        // --- CALCULATION LOGIC ---

        function calculate() {
            let heightM = 0; // Height in Meters
            let weightKg = 0; // Weight in KG

            if (isMetric) {
                const cm = parseFloat(mHeight.value);
                const kg = parseFloat(mWeight.value);
                if (!cm || !kg) return resetUI();
                
                heightM = cm / 100;
                weightKg = kg;
            } else {
                const ft = parseFloat(iFeet.value);
                const inc = parseFloat(iInches.value) || 0; // Inches optional
                const lbs = parseFloat(iWeight.value);
                if (!ft || !lbs) return resetUI();

                // Convert Imperial to Metric for formula
                const totalInches = (ft * 12) + inc;
                heightM = totalInches * 0.0254;
                weightKg = lbs * 0.453592;
            }

            if (heightM <= 0 || weightKg <= 0) return resetUI();

            // BMI Formula: kg / m^2
            const bmi = weightKg / (heightM * heightM);
            updateUI(bmi, heightM);
        }

        function updateUI(bmi, heightM) {
            // 1. BMI Value
            resBmi.textContent = bmi.toFixed(1);

            // 2. Category & Color
            let category = "";
            let color = "";
            
            if (bmi < 18.5) {
                category = "Underweight";
                color = "#3b82f6"; // Blue
            } else if (bmi < 25) {
                category = "Normal Weight";
                color = "#10b981"; // Emerald
            } else if (bmi < 30) {
                category = "Overweight";
                color = "#f59e0b"; // Amber
            } else {
                category = "Obese";
                color = "#ef4444"; // Red
            }

            resCategory.textContent = category;
            resCategory.style.color = color;

            // 3. Gauge Position
            // Gauge Range: 15 to 40 (span of 25 units)
            // Percentage = (bmi - 15) / 25 * 100
            let percentage = ((bmi - 15) / 25) * 100;
            percentage = Math.max(0, Math.min(100, percentage)); // Clamp 0-100
            gaugeMarker.style.left = `${percentage}%`;

            // 4. Healthy Range Calculation
            // Reverse BMI: weight = bmi * h^2
            // Normal range is 18.5 to 24.9
            const minWeightKg = 18.5 * (heightM * heightM);
            const maxWeightKg = 24.9 * (heightM * heightM);

            if (isMetric) {
                resRange.textContent = `${minWeightKg.toFixed(1)} - ${maxWeightKg.toFixed(1)} kg`;
            } else {
                // Convert back to lbs
                const minLbs = minWeightKg * 2.20462;
                const maxLbs = maxWeightKg * 2.20462;
                resRange.textContent = `${minLbs.toFixed(0)} - ${maxLbs.toFixed(0)} lbs`;
            }
        }

        function resetUI() {
            resBmi.textContent = "--.-";
            resCategory.textContent = "Enter Details";
            resCategory.style.color = "#4b5563";
            resRange.textContent = "-- - --";
            gaugeMarker.style.left = "0%";
        }

        // --- TOGGLE SYSTEM ---

        toggle.addEventListener('click', () => {
            isMetric = !isMetric;
            
            // Toggle Visuals
            toggle.classList.toggle('imperial');
            if (isMetric) {
                optMetric.className = "unit-option text-gray-900";
                optImperial.className = "unit-option text-gray-400";
                inputsMetric.classList.remove('hidden');
                inputsImperial.classList.add('hidden');
                // Attempt to convert current values? 
                // For simplicity, let's clear inputs to avoid confusion, or keep logic separated.
                // Let's reset.
            } else {
                optMetric.className = "unit-option text-gray-400";
                optImperial.className = "unit-option text-gray-900";
                inputsMetric.classList.add('hidden');
                inputsImperial.classList.remove('hidden');
            }
            
            resetUI();
        });

        // --- LISTENERS ---
        [mHeight, mWeight, iFeet, iInches, iWeight].forEach(input => {
            input.addEventListener('input', calculate);
        });

    </script>
</body>
</html>