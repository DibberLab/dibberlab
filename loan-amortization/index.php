<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Number Input */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }

        /* Pie Chart (CSS Conic Gradient) */
        .pie-chart {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: conic-gradient(#10b981 0% 70%, #f59e0b 70% 100%);
            position: relative;
            transition: background 0.5s ease;
        }
        .pie-hole {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 100px; height: 100px;
            background-color: #111827; /* Gray-900 match */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 0.8rem;
        }

        /* Table Styling */
        .amort-table th { position: sticky; top: 0; background: #1f2937; z-index: 10; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Loan Calculator</h1>
                <p class="text-center text-gray-400">Calculate payments, interest, and amortization schedules.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Loan Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                            <input type="number" id="inp-amount" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 pl-8 text-white text-lg font-bold focus:outline-none focus:border-emerald-500 transition-colors placeholder-gray-700" value="250000">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Interest Rate (%)</label>
                        <div class="relative">
                            <input type="number" id="inp-rate" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg font-bold focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-700" value="5.5" step="0.1">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Loan Term (Years)</label>
                        <div class="relative">
                            <input type="number" id="inp-years" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg font-bold focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-700" value="30">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">Years</span>
                        </div>
                    </div>

                    <button id="calc-btn" class="w-full py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg transition-all transform hover:-translate-y-1 mt-2">
                        Calculate
                    </button>

                </div>

                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-900 rounded-xl border border-gray-700 p-6 flex flex-col justify-center items-center text-center relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-amber-500"></div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Monthly Payment</span>
                            <div class="text-5xl font-mono font-bold text-white mb-1" id="res-monthly">$0</div>
                        </div>

                        <div class="bg-gray-900 rounded-xl border border-gray-700 p-6 flex items-center justify-between gap-4">
                            <div class="flex flex-col justify-center gap-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                        <span class="text-xs font-bold text-gray-400 uppercase">Principal</span>
                                    </div>
                                    <div class="text-lg font-bold text-white font-mono" id="res-principal">$0</div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                        <span class="text-xs font-bold text-gray-400 uppercase">Total Interest</span>
                                    </div>
                                    <div class="text-lg font-bold text-white font-mono" id="res-interest">$0</div>
                                </div>
                            </div>
                            
                            <div class="pie-chart" id="chart-pie">
                                <div class="pie-hole">Total Cost</div>
                            </div>
                        </div>

                    </div>

                    <div class="flex-grow bg-gray-900 rounded-xl border border-gray-700 flex flex-col overflow-hidden min-h-[300px]">
                        <div class="p-4 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase">Amortization Schedule</span>
                            <span class="text-[10px] text-gray-600 bg-gray-900 px-2 py-1 rounded border border-gray-700">Yearly breakdown available in CSV</span>
                        </div>
                        
                        <div class="overflow-y-auto custom-scrollbar flex-grow">
                            <table class="w-full text-left border-collapse amort-table">
                                <thead>
                                    <tr class="text-xs text-gray-500 border-b border-gray-700">
                                        <th class="p-3 pl-6 font-bold">#</th>
                                        <th class="p-3 font-bold">Payment</th>
                                        <th class="p-3 font-bold">Principal</th>
                                        <th class="p-3 font-bold text-amber-500">Interest</th>
                                        <th class="p-3 pr-6 font-bold text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="schedule-body" class="text-sm font-mono text-gray-300">
                                    </tbody>
                            </table>
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
        const inpAmount = document.getElementById('inp-amount');
        const inpRate = document.getElementById('inp-rate');
        const inpYears = document.getElementById('inp-years');
        const calcBtn = document.getElementById('calc-btn');

        // Outputs
        const resMonthly = document.getElementById('res-monthly');
        const resPrincipal = document.getElementById('res-principal');
        const resInterest = document.getElementById('res-interest');
        const chartPie = document.getElementById('chart-pie');
        const scheduleBody = document.getElementById('schedule-body');

        // Utils
        const currency = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

        function calculate() {
            const principal = parseFloat(inpAmount.value);
            const rate = parseFloat(inpRate.value);
            const years = parseFloat(inpYears.value);

            if (!principal || !rate || !years) return;

            const monthlyRate = rate / 100 / 12;
            const payments = years * 12;

            // Formula: M = P [ i(1 + i)^n ] / [ (1 + i)^n – 1 ]
            const x = Math.pow(1 + monthlyRate, payments);
            const monthly = (principal * x * monthlyRate) / (x - 1);

            if (!isFinite(monthly)) return;

            const totalPayable = monthly * payments;
            const totalInterest = totalPayable - principal;

            // Update Summary
            resMonthly.textContent = currency.format(monthly);
            resPrincipal.textContent = currency.format(principal);
            resInterest.textContent = currency.format(totalInterest);

            // Update Chart (Conic Gradient)
            // Percentage of Total Cost that is Principal
            const principalPercent = (principal / totalPayable) * 100;
            // CSS: Emerald (Principal) then Amber (Interest)
            chartPie.style.background = `conic-gradient(#10b981 0% ${principalPercent}%, #f59e0b ${principalPercent}% 100%)`;

            // Generate Schedule
            generateSchedule(principal, monthlyRate, monthly, payments);
        }

        function generateSchedule(principal, monthlyRate, monthlyPayment, totalPayments) {
            let html = "";
            let balance = principal;
            let totalInterest = 0;

            // Performance: DocumentFragment is faster, but string concat is fine for < 500 rows
            // We'll limit visible rows if it's huge, but 30 years = 360 rows, which is fine.
            
            for (let i = 1; i <= totalPayments; i++) {
                const interestPayment = balance * monthlyRate;
                const principalPayment = monthlyPayment - interestPayment;
                balance -= principalPayment;
                
                // Fix floating point drift at the end
                if (balance < 0) balance = 0;

                // Only render every year (12th month) AND the first few months? 
                // Let's render ALL, user can scroll.
                
                html += `
                    <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition-colors">
                        <td class="p-3 pl-6 text-gray-500">${i}</td>
                        <td class="p-3">${currency.format(monthlyPayment)}</td>
                        <td class="p-3 text-emerald-400">${currency.format(principalPayment)}</td>
                        <td class="p-3 text-amber-400">${currency.format(interestPayment)}</td>
                        <td class="p-3 pr-6 text-right text-gray-400">${currency.format(balance)}</td>
                    </tr>
                `;
            }

            scheduleBody.innerHTML = html;
        }

        // Listeners
        calcBtn.addEventListener('click', calculate);
        
        // Auto-calc on Enter
        [inpAmount, inpRate, inpYears].forEach(el => {
            el.addEventListener('keydown', (e) => {
                if(e.key === 'Enter') calculate();
            });
        });

        // Init
        calculate();

    </script>
</body>
</html>