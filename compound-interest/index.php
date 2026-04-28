<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compound Interest Calc | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input Styling */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }

        /* Range Slider */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 16px; width: 16px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -6px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }

        /* The Chart Container */
        .chart-container {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 200px;
            padding-top: 20px;
        }
        
        .chart-bar {
            flex: 1;
            display: flex;
            flex-direction: column-reverse;
            transition: height 0.3s ease;
            position: relative;
            border-radius: 2px 2px 0 0;
            overflow: hidden;
        }
        
        /* Bar Segments */
        .bar-principal { background-color: #10b981; transition: height 0.3s; } /* Emerald */
        .bar-interest { background-color: #f59e0b; transition: height 0.3s; } /* Amber */

        /* Tooltip on hover */
        .chart-bar:hover::after {
            content: attr(data-val);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
            margin-bottom: 4px;
            border: 1px solid #374151;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Compound Interest</h1>
                <p class="text-center text-gray-400">Visualize the snowball effect of your investments.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Initial Deposit</label>
                        <div class="relative mb-2">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                            <input type="number" id="inp-initial" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 pl-8 text-white font-bold focus:outline-none focus:border-emerald-500" value="5000">
                        </div>
                        <input type="range" id="range-initial" min="0" max="100000" step="100" value="5000" class="w-full">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Monthly Contribution</label>
                        <div class="relative mb-2">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                            <input type="number" id="inp-monthly" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 pl-8 text-white font-bold focus:outline-none focus:border-emerald-500" value="200">
                        </div>
                        <input type="range" id="range-monthly" min="0" max="5000" step="50" value="200" class="w-full">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Interest Rate (%)</label>
                        <div class="relative mb-2">
                            <input type="number" id="inp-rate" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white font-bold focus:outline-none focus:border-amber-500" value="7" step="0.1">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                        </div>
                        <input type="range" id="range-rate" min="0.1" max="15" step="0.1" value="7" class="w-full">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Time Period (Years)</label>
                        <div class="relative mb-2">
                            <input type="number" id="inp-years" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-3 text-white font-bold focus:outline-none focus:border-blue-500" value="30">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-xs">YRS</span>
                        </div>
                        <input type="range" id="range-years" min="1" max="50" value="30" class="w-full">
                    </div>

                </div>

                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-6 shadow-lg relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Future Balance</span>
                                <div class="text-5xl font-mono font-bold text-white mt-1" id="res-total">$0</div>
                            </div>
                            <div class="text-right hidden md:block">
                                <span class="text-xs font-bold text-gray-500 uppercase">Profit Multiplier</span>
                                <div class="text-2xl font-bold text-amber-400" id="res-multiplier">0x</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-xl border border-gray-700 p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-bold text-gray-400 uppercase">Growth Over Time</span>
                            <div class="flex gap-4 text-[10px] font-bold uppercase">
                                <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Principal</div>
                                <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-amber-500"></div> Interest</div>
                            </div>
                        </div>
                        
                        <div class="chart-container" id="growth-chart">
                            </div>
                        
                        <div class="flex justify-between text-[10px] text-gray-500 mt-2 font-mono">
                            <span>Year 0</span>
                            <span id="mid-year">Year 15</span>
                            <span id="end-year">Year 30</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Total Principal</span>
                            <div class="text-xl font-bold text-emerald-400 mono-font" id="res-principal">$0</div>
                        </div>
                        <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Total Interest Earned</span>
                            <div class="text-xl font-bold text-amber-400 mono-font" id="res-interest">$0</div>
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
        // Inputs
        const inputs = {
            initial: document.getElementById('inp-initial'),
            monthly: document.getElementById('inp-monthly'),
            rate: document.getElementById('inp-rate'),
            years: document.getElementById('inp-years'),
        };

        const ranges = {
            initial: document.getElementById('range-initial'),
            monthly: document.getElementById('range-monthly'),
            rate: document.getElementById('range-rate'),
            years: document.getElementById('range-years'),
        };

        // Outputs
        const resTotal = document.getElementById('res-total');
        const resPrincipal = document.getElementById('res-principal');
        const resInterest = document.getElementById('res-interest');
        const resMultiplier = document.getElementById('res-multiplier');
        const chartContainer = document.getElementById('growth-chart');
        
        // Axis Labels
        const lblMid = document.getElementById('mid-year');
        const lblEnd = document.getElementById('end-year');

        const currency = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 });

        // --- SYNC INPUTS & RANGES ---
        function syncInputs(source, key) {
            const val = source.value;
            if(source.type === 'range') inputs[key].value = val;
            else ranges[key].value = val;
            calculate();
        }

        Object.keys(inputs).forEach(key => {
            inputs[key].addEventListener('input', (e) => syncInputs(e.target, key));
            ranges[key].addEventListener('input', (e) => syncInputs(e.target, key));
        });

        // --- CALCULATION LOGIC ---

        function calculate() {
            const P = parseFloat(inputs.initial.value) || 0;
            const PMT = parseFloat(inputs.monthly.value) || 0;
            const r = (parseFloat(inputs.rate.value) || 0) / 100;
            const t = parseInt(inputs.years.value) || 1;
            const n = 12; // Monthly compounding standard for this simplified tool

            // Generate Year-by-Year Data
            const data = [];
            let currentBalance = P;
            let totalContributed = P;

            for (let year = 1; year <= t; year++) {
                // Compound for 12 months
                for (let m = 0; m < 12; m++) {
                    currentBalance = (currentBalance + PMT) * (1 + r/n);
                    totalContributed += PMT;
                }
                
                data.push({
                    year: year,
                    principal: totalContributed,
                    interest: currentBalance - totalContributed,
                    total: currentBalance
                });
            }

            const final = data[data.length - 1];

            // Update Text Results
            resTotal.textContent = currency.format(final.total);
            resPrincipal.textContent = currency.format(final.principal);
            resInterest.textContent = currency.format(final.interest);
            
            // Multiplier (How many times did your money multiply?)
            const mult = final.total / final.principal;
            resMultiplier.textContent = mult.toFixed(2) + "x";

            // Update Chart
            renderChart(data);
            
            // Update Axis
            lblMid.textContent = `Year ${Math.round(t/2)}`;
            lblEnd.textContent = `Year ${t}`;
        }

        function renderChart(data) {
            chartContainer.innerHTML = '';
            
            const maxVal = data[data.length - 1].total;
            // Limit number of bars to prevent overcrowding (max 30 bars visually)
            // If years > 30, sample data
            const step = Math.ceil(data.length / 30);
            
            for (let i = 0; i < data.length; i += step) {
                const point = data[i];
                // Always include the very last year if skipped
                // (Logic simplified here for visual smoothness)
                
                const bar = document.createElement('div');
                bar.className = 'chart-bar';
                
                // Calculate percentages
                const totalH = (point.total / maxVal) * 100;
                const principalH = (point.principal / point.total) * 100;
                const interestH = (point.interest / point.total) * 100;

                bar.style.height = `${totalH}%`;
                bar.dataset.val = `Y${point.year}: ${currency.format(point.total)}`; // Tooltip content

                // Segments
                // Flex-direction is column-reverse, so bottom is first child
                const pDiv = document.createElement('div');
                pDiv.className = 'bar-principal';
                pDiv.style.flexBasis = `${principalH}%`; // Use flex-basis for ratio

                const iDiv = document.createElement('div');
                iDiv.className = 'bar-interest';
                iDiv.style.flexBasis = `${interestH}%`;

                bar.appendChild(pDiv); // Bottom (Principal)
                bar.appendChild(iDiv); // Top (Interest)

                chartContainer.appendChild(bar);
            }
        }

        // Init
        calculate();

    </script>
</body>
</html>