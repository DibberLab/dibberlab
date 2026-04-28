<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dough Math | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .serif-font { font-family: 'Playfair Display', serif; }

        /* Warm Flour Texture Background */
        .flour-bg {
            background-color: #fdfbf7;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Paper Receipt Style Card */
        .recipe-card {
            background: #fff;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(0,0,0,0.05); /* Subtle border */
            position: relative;
        }
        
        /* Zigzag bottom edge for receipt look */
        .recipe-card::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(45deg, transparent 33.333%, #fff 33.333%, #fff 66.667%, transparent 66.667%), linear-gradient(-45deg, transparent 33.333%, #fff 33.333%, #fff 66.667%, transparent 66.667%);
            background-size: 20px 20px;
            background-position: 0 -10px; 
            filter: drop-shadow(0 2px 1px rgba(0,0,0,0.1));
        }

        /* Custom Range Sliders */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #d97706; /* Amber-600 */
            cursor: pointer;
            margin-top: -8px;
            box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.2);
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.1); }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #e5e7eb;
            border-radius: 2px;
        }

        /* Ingredient Row Animation */
        .ing-row { transition: background-color 0.2s; }
        .ing-row:hover { background-color: #fffbeb; }

        /* Measurement Highlight */
        .measure-val { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="flour-bg text-gray-800 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-12">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <div class="flex flex-col gap-8">
                
                <div>
                    <span class="text-amber-600 font-bold tracking-widest text-xs uppercase mb-2 block">Baker's Math Calculator</span>
                    <h1 class="text-5xl font-bold serif-font text-gray-900 mb-4">Perfect Dough</h1>
                    <p class="text-gray-500 leading-relaxed">
                        Calculate recipes based on hydration percentages. Adjust the sliders to find your perfect crust style, from Neapolitan to New York.
                    </p>
                </div>

                <div class="space-y-8 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                    
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label class="text-sm font-bold text-gray-900 uppercase">Dough Balls</label>
                            <span class="text-2xl font-bold text-amber-600 measure-val" id="count-val">2</span>
                        </div>
                        <input type="range" id="count-slider" min="1" max="10" value="2" step="1">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>1 Pizza</span>
                            <span>Party (10)</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label class="text-sm font-bold text-gray-900 uppercase">Weight per Ball</label>
                            <span class="text-2xl font-bold text-amber-600 measure-val"><span id="weight-val">250</span>g</span>
                        </div>
                        <input type="range" id="weight-slider" min="150" max="500" value="250" step="10">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>Small (150g)</span>
                            <span>Large (500g)</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label class="text-sm font-bold text-gray-900 uppercase">Water Hydration</label>
                            <span class="text-2xl font-bold text-blue-500 measure-val"><span id="hydro-val">65</span>%</span>
                        </div>
                        <input type="range" id="hydro-slider" min="50" max="85" value="65" step="1">
                        
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100 flex items-start gap-3">
                            <span class="text-xl">💧</span>
                            <div>
                                <h4 class="text-xs font-bold text-blue-800 uppercase" id="hydro-title">Standard Style</h4>
                                <p class="text-xs text-blue-600 mt-1" id="hydro-desc">Easy to handle. Good for home ovens.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="flex justify-center lg:justify-end">
                <div class="recipe-card w-full max-w-md p-8 pb-12">
                    
                    <div class="text-center mb-8 border-b-2 border-gray-900 pb-6">
                        <h2 class="text-3xl font-bold serif-font text-gray-900">The Recipe</h2>
                        <p class="text-sm text-gray-500 mt-1 italic">Total Dough Weight: <span id="total-weight" class="font-bold text-gray-900">500</span>g</p>
                    </div>

                    <div class="space-y-1">
                        
                        <div class="ing-row flex justify-between items-center py-3 px-2 border-b border-gray-100">
                            <div>
                                <div class="font-bold text-gray-800">Flour (00 or Bread)</div>
                                <div class="text-xs text-gray-400">100%</div>
                            </div>
                            <div class="text-xl font-bold text-gray-900 measure-val"><span id="out-flour">0</span>g</div>
                        </div>

                        <div class="ing-row flex justify-between items-center py-3 px-2 border-b border-gray-100">
                            <div>
                                <div class="font-bold text-blue-600">Water</div>
                                <div class="text-xs text-gray-400" id="out-hydro-pct">65%</div>
                            </div>
                            <div class="text-xl font-bold text-blue-600 measure-val"><span id="out-water">0</span>g</div>
                        </div>

                        <div class="ing-row flex justify-between items-center py-3 px-2 border-b border-gray-100">
                            <div>
                                <div class="font-bold text-gray-800">Fine Salt</div>
                                <div class="text-xs text-gray-400">3%</div>
                            </div>
                            <div class="text-xl font-bold text-gray-900 measure-val"><span id="out-salt">0</span>g</div>
                        </div>

                        <div class="ing-row flex justify-between items-center py-3 px-2 border-b border-gray-100">
                            <div>
                                <div class="font-bold text-gray-800">Instant Yeast</div>
                                <div class="text-xs text-gray-400">0.5%</div>
                            </div>
                            <div class="text-xl font-bold text-gray-900 measure-val"><span id="out-yeast">0</span>g</div>
                        </div>

                    </div>

                    <div class="mt-8 text-center">
                        <button onclick="window.print()" class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-gray-900 transition-colors">
                            Print Recipe
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-400 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const countSlider = document.getElementById('count-slider');
        const countVal = document.getElementById('count-val');
        
        const weightSlider = document.getElementById('weight-slider');
        const weightVal = document.getElementById('weight-val');
        
        const hydroSlider = document.getElementById('hydro-slider');
        const hydroVal = document.getElementById('hydro-val');
        const hydroTitle = document.getElementById('hydro-title');
        const hydroDesc = document.getElementById('hydro-desc');

        const outFlour = document.getElementById('out-flour');
        const outWater = document.getElementById('out-water');
        const outSalt = document.getElementById('out-salt');
        const outYeast = document.getElementById('out-yeast');
        const totalWeightEl = document.getElementById('total-weight');
        const outHydroPct = document.getElementById('out-hydro-pct');

        // Constants (Baker's Percentages)
        const SALT_PCT = 0.03;  // 3%
        const YEAST_PCT = 0.005; // 0.5%

        // --- LOGIC ---

        function calculate() {
            // 1. Get Inputs
            const count = parseInt(countSlider.value);
            const weightPerBall = parseInt(weightSlider.value);
            const hydration = parseInt(hydroSlider.value) / 100;

            // 2. Update Labels
            countVal.textContent = count;
            weightVal.textContent = weightPerBall;
            hydroVal.textContent = (hydration * 100).toFixed(0);
            outHydroPct.textContent = (hydration * 100).toFixed(0) + "%";

            // 3. Update Hydration Guide Text
            updateGuide(hydration * 100);

            // 4. Baker's Math
            // Total Weight = Flour + Water + Salt + Yeast
            // Total Weight = F + (F * Hydration) + (F * 0.03) + (F * 0.005)
            // Total Weight = F * (1 + Hydration + 0.03 + 0.005)
            
            const totalTargetWeight = count * weightPerBall;
            const totalPercentage = 1 + hydration + SALT_PCT + YEAST_PCT;
            
            const flourWeight = totalTargetWeight / totalPercentage;
            
            const waterWeight = flourWeight * hydration;
            const saltWeight = flourWeight * SALT_PCT;
            const yeastWeight = flourWeight * YEAST_PCT;

            // 5. Render Output (Rounded)
            outFlour.textContent = Math.round(flourWeight);
            outWater.textContent = Math.round(waterWeight);
            outSalt.textContent = Math.round(saltWeight);
            outYeast.textContent = yeastWeight.toFixed(1); // Precision for yeast
            totalWeightEl.textContent = totalTargetWeight;
        }

        function updateGuide(pct) {
            if (pct < 60) {
                hydroTitle.textContent = "Very Stiff / Bagel";
                hydroDesc.textContent = "Extremely dense. Difficult to knead by hand.";
            } else if (pct < 65) {
                hydroTitle.textContent = "New York Style";
                hydroDesc.textContent = "Firm, crispy, easy to handle. Good for deck ovens.";
            } else if (pct < 70) {
                hydroTitle.textContent = "Neapolitan / Standard";
                hydroDesc.textContent = "The sweet spot. Puffy crust, soft interior. Needs high heat.";
            } else if (pct < 75) {
                hydroTitle.textContent = "High Hydration / Artisan";
                hydroDesc.textContent = "Very airy and light. Sticky to handle. Requires technique.";
            } else {
                hydroTitle.textContent = "Pan Pizza / Focaccia";
                hydroDesc.textContent = "Basically a batter. Do not roll; pour into a pan.";
            }
        }

        // Listeners
        [countSlider, weightSlider, hydroSlider].forEach(el => {
            el.addEventListener('input', calculate);
        });

        // Init
        calculate();

    </script>
</body>
</html>