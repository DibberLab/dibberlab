<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Duration Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Row Animation */
        .time-row {
            transition: all 0.2s;
            opacity: 0;
            transform: translateX(-10px);
            animation: slideIn 0.2s forwards;
        }
        @keyframes slideIn {
            to { opacity: 1; transform: translateX(0); }
        }

        /* Result Card */
        .result-card {
            transition: border-color 0.2s;
        }
        .result-card:hover {
            border-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8 flex flex-col md:flex-row gap-8 min-h-[600px]">
            
            <div class="flex-grow flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-bold text-amber-400">Time Adder</h1>
                    <button id="clear-btn" class="text-xs text-red-400 hover:text-red-300 underline">Clear All</button>
                </div>

                <div class="bg-gray-900 rounded-xl border border-gray-700 flex-grow flex flex-col relative overflow-hidden">
                    <div class="grid grid-cols-12 gap-2 px-4 py-2 bg-gray-800 border-b border-gray-700 text-xs font-bold text-gray-500 uppercase">
                        <div class="col-span-1 text-center">#</div>
                        <div class="col-span-7">Duration</div>
                        <div class="col-span-4 text-right">Interpreted</div>
                    </div>

                    <div id="rows-container" class="overflow-y-auto custom-scrollbar flex-grow p-2 space-y-2">
                        </div>

                    <div class="p-4 border-t border-gray-700 bg-gray-800">
                        <button id="add-row-btn" class="w-full py-3 rounded-lg border-2 border-dashed border-gray-600 text-gray-400 font-bold hover:border-emerald-500 hover:text-emerald-400 hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                            <span>+</span> Add Time Entry
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-80 flex-shrink-0 flex flex-col gap-6">
                
                <div class="bg-gray-900 p-6 rounded-2xl border border-gray-700 text-center shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-amber-500"></div>
                    
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Time</span>
                    <div id="res-time" class="text-5xl font-mono font-bold text-white mt-2 mb-1">00:00</div>
                    <div id="res-breakdown" class="text-sm text-gray-400">0h 0m</div>
                </div>

                <div class="result-card bg-gray-900 p-4 rounded-xl border border-gray-700 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-500 uppercase">Decimal Hours</span>
                    <div class="text-right">
                        <div id="res-decimal" class="text-xl font-mono font-bold text-emerald-400">0.00</div>
                        <div class="text-[10px] text-gray-600">hrs</div>
                    </div>
                </div>

                <div class="result-card bg-gray-900 p-4 rounded-xl border border-gray-700 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-500 uppercase">Total Minutes</span>
                    <div class="text-right">
                        <div id="res-minutes" class="text-xl font-mono font-bold text-blue-400">0</div>
                        <div class="text-[10px] text-gray-600">mins</div>
                    </div>
                </div>

                <div class="bg-gray-800 p-4 rounded-xl text-xs text-gray-400 leading-relaxed border border-gray-700 mt-auto">
                    <p class="font-bold text-gray-300 mb-2">Supported Formats:</p>
                    <ul class="space-y-1 list-disc pl-4">
                        <li><span class="text-emerald-400">1:30</span> (1 hr 30 min)</li>
                        <li><span class="text-emerald-400">1.5</span> (1.5 hours)</li>
                        <li><span class="text-emerald-400">90m</span> (90 minutes)</li>
                        <li><span class="text-emerald-400">45</span> (45 minutes)</li>
                    </ul>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const container = document.getElementById('rows-container');
        const addBtn = document.getElementById('add-row-btn');
        const clearBtn = document.getElementById('clear-btn');
        
        // Output Elements
        const elTime = document.getElementById('res-time');
        const elBreakdown = document.getElementById('res-breakdown');
        const elDecimal = document.getElementById('res-decimal');
        const elMinutes = document.getElementById('res-minutes');

        // State
        let rowIdCounter = 0;

        // --- PARSING LOGIC ---
        function parseInput(val) {
            val = val.toLowerCase().trim();
            if (!val) return 0;

            let minutes = 0;

            // 1. Colon Format (1:30)
            if (val.includes(':')) {
                const parts = val.split(':');
                const h = parseInt(parts[0]) || 0;
                const m = parseInt(parts[1]) || 0;
                minutes = (h * 60) + m;
            }
            // 2. Explicit Units (1h 30m or 90m)
            else if (val.includes('h') || val.includes('m')) {
                // Regex for "1h" or "1.5h"
                const hoursMatch = val.match(/(\d*\.?\d+)\s*h/);
                if (hoursMatch) minutes += parseFloat(hoursMatch[1]) * 60;

                // Regex for "30m"
                const minsMatch = val.match(/(\d*\.?\d+)\s*m/);
                if (minsMatch) minutes += parseFloat(minsMatch[1]);
            }
            // 3. Decimal/Integer (1.5 or 90)
            else {
                const num = parseFloat(val);
                if (!isNaN(num)) {
                    // Heuristic: If < 8, assume hours (e.g. "1.5" = 1.5 hrs). 
                    // If >= 8, assume minutes? Or just stick to one rule?
                    // Standard timesheet app rule: Integers = minutes? Decimals = hours?
                    // Let's assume DECIMAL = HOURS, INTEGER > 0 = MINUTES to be safe?
                    // Actually, simple rule: If it contains a decimal, treat as hours. If integer, treat as minutes.
                    if (val.includes('.')) {
                        minutes = num * 60;
                    } else {
                        // User typed "90". Assume minutes.
                        minutes = num;
                    }
                }
            }

            return Math.round(minutes);
        }

        // --- FORMATTING LOGIC ---
        function formatMinutes(totalMins) {
            const h = Math.floor(totalMins / 60);
            const m = totalMins % 60;
            return {
                hhmm: `${h}:${m.toString().padStart(2, '0')}`,
                text: `${h}h ${m}m`
            };
        }

        // --- UI LOGIC ---
        function addRow() {
            rowIdCounter++;
            const div = document.createElement('div');
            div.className = "time-row grid grid-cols-12 gap-2 items-center bg-gray-800 p-2 rounded-lg border border-gray-700 hover:border-gray-500";
            
            div.innerHTML = `
                <div class="col-span-1 text-center text-gray-600 font-mono text-xs select-none">${rowIdCounter}</div>
                <div class="col-span-7">
                    <input type="text" class="w-full bg-transparent text-white font-mono placeholder-gray-600 focus:outline-none" placeholder="e.g. 1:30" oninput="calculate()">
                </div>
                <div class="col-span-3 text-right">
                    <span class="preview-val text-xs text-emerald-500 font-mono opacity-0 transition-opacity">0m</span>
                </div>
                <div class="col-span-1 text-right">
                    <button class="text-gray-600 hover:text-red-400 transition-colors" onclick="removeRow(this)">×</button>
                </div>
            `;
            container.appendChild(div);
            
            // Auto focus new row
            const input = div.querySelector('input');
            input.focus();
            
            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        window.removeRow = function(btn) {
            const row = btn.closest('.time-row');
            row.remove();
            calculate();
        }

        window.calculate = function() {
            let totalMinutes = 0;
            const rows = container.querySelectorAll('.time-row');

            rows.forEach(row => {
                const input = row.querySelector('input');
                const preview = row.querySelector('.preview-val');
                
                const mins = parseInput(input.value);
                
                if (input.value.trim() !== "") {
                    totalMinutes += mins;
                    
                    // Update row preview
                    const formatted = formatMinutes(mins);
                    preview.textContent = formatted.text;
                    preview.classList.remove('opacity-0');
                } else {
                    preview.classList.add('opacity-0');
                }
            });

            // Update Totals
            const formattedTotal = formatMinutes(totalMinutes);
            
            elTime.textContent = formattedTotal.hhmm;
            elBreakdown.textContent = formattedTotal.text;
            
            elMinutes.textContent = totalMinutes.toLocaleString();
            elDecimal.textContent = (totalMinutes / 60).toFixed(2);
        }

        // Listeners
        addBtn.addEventListener('click', addRow);
        
        clearBtn.addEventListener('click', () => {
            container.innerHTML = '';
            rowIdCounter = 0;
            addRow(); // Always keep one
            calculate();
        });

        // Enter key to add new row
        container.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRow();
            }
        });

        // Init
        addRow();

    </script>
</body>
</html>