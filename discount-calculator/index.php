<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }

        /* Preset Buttons */
        .preset-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .preset-btn:hover { background-color: #374151; transform: translateY(-1px); }
        .preset-btn:active { transform: translateY(0); background-color: #f59e0b; color: #111827; border-color: #f59e0b; }

        /* Savings Tag Animation */
        .tag-pulse { animation: pulse-green 2s infinite; }
        
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Split Bar */
        .split-bar { transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Discount Calculator</h1>
                <p class="text-center text-gray-400">Calculate sale prices, double discounts, and tax.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-8">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Original Price</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-xl group-focus-within:text-white transition-colors">$</span>
                            <input type="number" id="price-input" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 pl-10 text-white text-2xl font-bold focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-700" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Discount</label>
                            <span id="discount-val" class="text-lg font-bold text-emerald-400">0%</span>
                        </div>
                        <input type="range" id="discount-slider" min="0" max="99" value="0" class="w-full mb-4 accent-emerald-500 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer">
                        
                        <div class="grid grid-cols-5 gap-2">
                            <button class="preset-btn bg-gray-900 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="setDiscount(10)">10%</button>
                            <button class="preset-btn bg-gray-900 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="setDiscount(20)">20%</button>
                            <button class="preset-btn bg-gray-900 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="setDiscount(30)">30%</button>
                            <button class="preset-btn bg-gray-900 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="setDiscount(50)">50%</button>
                            <button class="preset-btn bg-gray-900 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="setDiscount(75)">75%</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Extra "Stack" Off</label>
                            <div class="relative">
                                <input type="number" id="extra-input" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white font-bold focus:outline-none focus:border-emerald-500 placeholder-gray-600" placeholder="0">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Sales Tax</label>
                            <div class="relative">
                                <input type="number" id="tax-input" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white font-bold focus:outline-none focus:border-red-500 placeholder-gray-600" placeholder="0">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="flex flex-col gap-6">
                    
                    <div class="bg-gray-900 p-8 rounded-2xl border border-gray-700 text-center relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 to-amber-500"></div>
                        
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Final Price</span>
                        <div class="text-6xl font-black text-white mt-2 mb-2 mono-font tracking-tight" id="res-total">$0.00</div>
                        
                        <div class="inline-flex items-center gap-2 bg-emerald-900/30 border border-emerald-500/30 px-4 py-1 rounded-full tag-pulse">
                            <span class="text-xs font-bold text-emerald-400 uppercase">You Save</span>
                            <span class="text-sm font-bold text-emerald-300 mono-font" id="res-saved">$0.00</span>
                        </div>
                    </div>

                    <div class="w-full h-4 bg-gray-700 rounded-full overflow-hidden flex">
                        <div id="bar-pay" class="h-full bg-gray-500 split-bar" style="width: 100%"></div>
                        <div id="bar-save" class="h-full bg-emerald-500 split-bar" style="width: 0%"></div>
                        <div id="bar-tax" class="h-full bg-red-500 split-bar" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-500 font-bold uppercase px-1">
                        <span>Pay</span>
                        <span>Save</span>
                        <span>Tax</span>
                    </div>

                    <div class="bg-gray-900 rounded-xl border border-gray-700 p-6 flex-grow flex flex-col justify-center gap-3 font-mono text-sm">
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Original Price</span>
                            <span class="text-gray-300 line-through decoration-red-500 decoration-2" id="rec-original">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-emerald-400">
                            <span>Discount <span id="rec-percent" class="text-xs opacity-75"></span></span>
                            <span id="rec-discount">-$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-red-400 border-b border-gray-800 pb-3 mb-1">
                            <span>Tax</span>
                            <span id="rec-tax">+$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold text-white">
                            <span>Total</span>
                            <span id="rec-total">$0.00</span>
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
        const priceInput = document.getElementById('price-input');
        const discountSlider = document.getElementById('discount-slider');
        const discountVal = document.getElementById('discount-val');
        const extraInput = document.getElementById('extra-input');
        const taxInput = document.getElementById('tax-input');

        // Outputs
        const resTotal = document.getElementById('res-total');
        const resSaved = document.getElementById('res-saved');
        const recOriginal = document.getElementById('rec-original');
        const recPercent = document.getElementById('rec-percent');
        const recDiscount = document.getElementById('rec-discount');
        const recTax = document.getElementById('rec-tax');
        const recTotal = document.getElementById('rec-total');
        
        // Bars
        const barPay = document.getElementById('bar-pay');
        const barSave = document.getElementById('bar-save');
        const barTax = document.getElementById('bar-tax');

        // Utils
        const currency = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

        function calculate() {
            const original = parseFloat(priceInput.value) || 0;
            const disc1 = parseFloat(discountSlider.value) || 0;
            const disc2 = parseFloat(extraInput.value) || 0;
            const taxRate = parseFloat(taxInput.value) || 0;

            // 1. Calculate Discount
            // Logic: Price * (1 - d1) * (1 - d2)
            const priceAfterDisc1 = original * (1 - (disc1 / 100));
            const priceAfterDisc2 = priceAfterDisc1 * (1 - (disc2 / 100));
            
            const totalSaved = original - priceAfterDisc2;
            
            // 2. Calculate Tax (Tax is usually on the discounted price)
            const taxAmount = priceAfterDisc2 * (taxRate / 100);
            
            const finalTotal = priceAfterDisc2 + taxAmount;

            // 3. Update UI
            resTotal.textContent = currency.format(finalTotal);
            resSaved.textContent = currency.format(totalSaved);
            
            recOriginal.textContent = currency.format(original);
            recDiscount.textContent = "-" + currency.format(totalSaved);
            recTax.textContent = "+" + currency.format(taxAmount);
            recTotal.textContent = currency.format(finalTotal);

            // Discount Label logic
            if (disc2 > 0) {
                recPercent.textContent = `(${disc1}% + ${disc2}%)`;
            } else {
                recPercent.textContent = `(${disc1}%)`;
            }

            // 4. Update Visual Bars
            // Base is Max(Original, Final Total) to handle massive tax cases visually
            const visualBase = Math.max(original, finalTotal) || 1; 
            
            const payPercent = (priceAfterDisc2 / visualBase) * 100;
            const savePercent = (totalSaved / visualBase) * 100;
            const taxPercent = (taxAmount / visualBase) * 100;

            barPay.style.width = `${payPercent}%`;
            barSave.style.width = `${savePercent}%`;
            barTax.style.width = `${taxPercent}%`;
        }

        // --- ACTIONS ---

        function setDiscount(val) {
            discountSlider.value = val;
            discountVal.textContent = val + '%';
            calculate();
        }

        // --- LISTENERS ---

        priceInput.addEventListener('input', calculate);
        extraInput.addEventListener('input', calculate);
        taxInput.addEventListener('input', calculate);

        discountSlider.addEventListener('input', (e) => {
            discountVal.textContent = e.target.value + '%';
            calculate();
        });

        // Init
        // (Optional: Set default)
        
    </script>
</body>
</html>