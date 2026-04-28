<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tip Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input Styling */
        input[type="number"] {
            -moz-appearance: textfield;
            font-family: 'JetBrains Mono', monospace;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Tip Buttons */
        .tip-btn {
            transition: all 0.2s;
            border: 2px solid #374151;
        }
        .tip-btn:hover {
            border-color: #4b5563;
            background-color: #374151;
        }
        .tip-btn.active {
            border-color: #f59e0b; /* Amber */
            background-color: #f59e0b;
            color: #111827;
            font-weight: bold;
        }

        /* Result Card Animation */
        .result-card {
            transition: transform 0.2s;
        }
        .result-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Tip Calculator</h1>
                <p class="text-center text-gray-400">Calculate tips and split bills instantly.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-8">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bill Amount</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-xl group-focus-within:text-emerald-400">$</span>
                            <input type="number" id="bill-input" class="w-full bg-gray-900 border border-gray-600 rounded-xl py-4 pl-10 pr-4 text-white text-2xl font-bold focus:outline-none focus:border-emerald-500 transition-colors placeholder-gray-700" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Select Tip %</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button class="tip-btn rounded-lg py-3 text-sm font-medium" data-val="10">10%</button>
                            <button class="tip-btn rounded-lg py-3 text-sm font-medium" data-val="15">15%</button>
                            <button class="tip-btn active rounded-lg py-3 text-sm font-medium" data-val="20">20%</button>
                            <button class="tip-btn rounded-lg py-3 text-sm font-medium" data-val="25">25%</button>
                            <div class="col-span-2 relative">
                                <input type="number" id="custom-tip" class="w-full h-full bg-gray-900 border border-gray-600 rounded-lg text-center text-white focus:outline-none focus:border-f59e0b placeholder-gray-500 text-sm font-bold" placeholder="Custom %">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Number of People</label>
                        <div class="flex items-center gap-4 bg-gray-900 p-2 rounded-xl border border-gray-600">
                            <button id="people-minus" class="w-12 h-12 bg-gray-800 hover:bg-gray-700 rounded-lg text-xl font-bold transition-colors text-red-400">-</button>
                            <div class="flex-grow text-center text-2xl font-bold mono-font text-white" id="people-val">1</div>
                            <button id="people-plus" class="w-12 h-12 bg-gray-800 hover:bg-gray-700 rounded-lg text-xl font-bold transition-colors text-emerald-400">+</button>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-4">
                    
                    <div class="result-card bg-emerald-900/30 border-2 border-emerald-500/50 p-8 rounded-2xl flex flex-col items-center justify-center text-center shadow-lg relative overflow-hidden group">
                        <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <span class="text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1">Total Per Person</span>
                        <div class="text-6xl font-bold text-white mono-font flex items-baseline gap-1">
                            <span class="text-3xl text-emerald-400">$</span>
                            <span id="res-total-person">0.00</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="result-card bg-gray-900 border border-gray-700 p-6 rounded-xl text-center">
                            <span class="text-xs font-bold text-gray-500 uppercase block mb-2">Tip Amount</span>
                            <div class="text-2xl font-bold text-amber-400 mono-font" id="res-tip-amount">$0.00</div>
                        </div>

                        <div class="result-card bg-gray-900 border border-gray-700 p-6 rounded-xl text-center">
                            <span class="text-xs font-bold text-gray-500 uppercase block mb-2">Total Bill</span>
                            <div class="text-2xl font-bold text-blue-400 mono-font" id="res-total-bill">$0.00</div>
                        </div>
                    </div>

                    <button id="reset-btn" class="mt-auto w-full py-3 rounded-lg text-sm font-bold text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 border border-gray-700 transition-colors">
                        Reset Calculator
                    </button>

                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // Inputs
        const billInput = document.getElementById('bill-input');
        const customTipInput = document.getElementById('custom-tip');
        const tipBtns = document.querySelectorAll('.tip-btn');
        const peopleVal = document.getElementById('people-val');
        const peopleMinus = document.getElementById('people-minus');
        const peoplePlus = document.getElementById('people-plus');
        const resetBtn = document.getElementById('reset-btn');

        // Outputs
        const resTotalPerson = document.getElementById('res-total-person');
        const resTipAmount = document.getElementById('res-tip-amount');
        const resTotalBill = document.getElementById('res-total-bill');

        // State
        let state = {
            bill: 0,
            tipPercent: 20,
            people: 1
        };

        // --- CALCULATION LOGIC ---

        function calculate() {
            const bill = parseFloat(state.bill) || 0;
            const people = parseInt(state.people) || 1;
            const tipPercent = parseFloat(state.tipPercent) || 0;

            const tipAmount = bill * (tipPercent / 100);
            const totalBill = bill + tipAmount;
            const perPerson = totalBill / people;

            // Update UI
            resTipAmount.textContent = '$' + tipAmount.toFixed(2);
            resTotalBill.textContent = '$' + totalBill.toFixed(2);
            resTotalPerson.textContent = perPerson.toFixed(2);
        }

        // --- LISTENERS ---

        // Bill Input
        billInput.addEventListener('input', (e) => {
            state.bill = e.target.value;
            calculate();
        });

        // Tip Buttons
        tipBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active from all
                tipBtns.forEach(b => b.classList.remove('active'));
                customTipInput.value = ''; // Clear custom
                
                // Set active
                btn.classList.add('active');
                state.tipPercent = btn.dataset.val;
                calculate();
            });
        });

        // Custom Tip Input
        customTipInput.addEventListener('input', (e) => {
            // Remove active from buttons
            tipBtns.forEach(b => b.classList.remove('active'));
            state.tipPercent = e.target.value;
            calculate();
        });

        // People Counter
        peopleMinus.addEventListener('click', () => {
            if (state.people > 1) {
                state.people--;
                peopleVal.textContent = state.people;
                calculate();
            }
        });

        peoplePlus.addEventListener('click', () => {
            state.people++;
            peopleVal.textContent = state.people;
            calculate();
        });

        // Reset
        resetBtn.addEventListener('click', () => {
            state = { bill: 0, tipPercent: 20, people: 1 };
            
            billInput.value = '';
            peopleVal.textContent = '1';
            customTipInput.value = '';
            
            // Reset buttons to default 20%
            tipBtns.forEach(b => b.classList.remove('active'));
            document.querySelector('[data-val="20"]').classList.add('active');
            
            calculate();
            billInput.focus();
        });

        // Init focus
        billInput.focus();

    </script>
</body>
</html>