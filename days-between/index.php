<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Days Between Dates | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Date Input Styling for Dark Mode */
        input[type="date"] {
            color-scheme: dark;
            font-family: 'Inter', sans-serif;
        }
        
        /* Result Card Animation */
        .result-card {
            transition: all 0.2s;
        }
        .result-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b; /* Amber */
        }

        /* Toggle */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Date Calculator</h1>
                <p class="text-center text-gray-400">Calculate duration and business days between dates.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Start Date</label>
                        <button class="text-xs text-emerald-400 hover:text-white underline" onclick="setToday('start')">Today</button>
                    </div>
                    <input type="date" id="start-date" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">End Date</label>
                        <button class="text-xs text-emerald-400 hover:text-white underline" onclick="setToday('end')">Today</button>
                    </div>
                    <input type="date" id="end-date" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                </div>

            </div>

            <div class="flex justify-center mb-8">
                <div class="flex items-center gap-3 bg-gray-900 px-4 py-2 rounded-lg border border-gray-700">
                    <div class="relative inline-block w-10 align-middle select-none">
                        <input type="checkbox" id="opt-inclusive" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                        <label for="opt-inclusive" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                    </div>
                    <span class="text-sm font-medium text-gray-300">Include End Date (+1 Day)</span>
                </div>
            </div>

            <div id="results-area" class="space-y-4 hidden">
                
                <div class="bg-gray-900 p-6 rounded-2xl border border-gray-700 text-center relative overflow-hidden shadow-lg">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-amber-500"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Duration</span>
                    <div class="text-5xl md:text-6xl font-black text-white mt-2 mb-1" id="res-days">0</div>
                    <div class="text-lg text-gray-400 font-medium">Days</div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <div class="col-span-2 md:col-span-4 result-card bg-gray-800 p-4 rounded-xl border border-gray-700 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase">Breakdown</span>
                        <span id="res-text" class="text-lg font-bold text-emerald-400">0 Years, 0 Months, 0 Days</span>
                    </div>

                    <div class="result-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Business Days</span>
                        <div id="res-business" class="text-2xl font-bold text-white mt-1">0</div>
                        <div class="text-[10px] text-gray-500">No Weekends</div>
                    </div>

                    <div class="result-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Weeks</span>
                        <div id="res-weeks" class="text-2xl font-bold text-white mt-1">0</div>
                    </div>

                    <div class="result-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Hours</span>
                        <div id="res-hours" class="text-2xl font-bold text-white mt-1">0</div>
                    </div>

                    <div class="result-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Seconds</span>
                        <div id="res-seconds" class="text-2xl font-bold text-white mt-1">0</div>
                    </div>

                </div>
            </div>

            <div id="empty-state" class="text-center py-12 text-gray-600">
                <span class="text-4xl block mb-2">📅</span>
                Select two dates to calculate.
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const optInclusive = document.getElementById('opt-inclusive');
        
        const resultsArea = document.getElementById('results-area');
        const emptyState = document.getElementById('empty-state');
        
        // Output Elements
        const elDays = document.getElementById('res-days');
        const elText = document.getElementById('res-text');
        const elBusiness = document.getElementById('res-business');
        const elWeeks = document.getElementById('res-weeks');
        const elHours = document.getElementById('res-hours');
        const elSeconds = document.getElementById('res-seconds');

        // --- UTILS ---
        function setToday(which) {
            const today = new Date().toISOString().split('T')[0];
            if(which === 'start') startDateInput.value = today;
            if(which === 'end') endDateInput.value = today;
            calculate();
        }

        // --- CORE LOGIC ---
        function calculate() {
            if(!startDateInput.value || !endDateInput.value) {
                resultsArea.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            // Parse Dates
            let start = new Date(startDateInput.value);
            let end = new Date(endDateInput.value);

            // Handle Inverse Dates (if start > end, swap visual logic but keep absolute diff)
            // But usually we just take Math.abs
            if (start > end) {
                [start, end] = [end, start];
            }

            // Inclusive?
            if (optInclusive.checked) {
                end.setDate(end.getDate() + 1);
            }

            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

            // 1. Basic Stats
            elDays.textContent = diffDays.toLocaleString();
            elWeeks.textContent = (diffDays / 7).toFixed(1);
            elHours.textContent = (diffDays * 24).toLocaleString();
            elSeconds.textContent = (diffDays * 24 * 60 * 60).toLocaleString();

            // 2. Breakdown (Y/M/D)
            const breakdown = getPreciseDiff(start, end);
            let textParts = [];
            if(breakdown.years > 0) textParts.push(`${breakdown.years} Year${breakdown.years !== 1 ? 's' : ''}`);
            if(breakdown.months > 0) textParts.push(`${breakdown.months} Month${breakdown.months !== 1 ? 's' : ''}`);
            if(breakdown.days > 0) textParts.push(`${breakdown.days} Day${breakdown.days !== 1 ? 's' : ''}`);
            
            if(textParts.length === 0) elText.textContent = "0 Days";
            else elText.textContent = textParts.join(', ');

            // 3. Business Days
            const businessDays = getBusinessDays(new Date(startDateInput.value), new Date(endDateInput.value), optInclusive.checked);
            elBusiness.textContent = businessDays.toLocaleString();

            // Show UI
            resultsArea.classList.remove('hidden');
            emptyState.classList.add('hidden');
        }

        function getPreciseDiff(d1, d2) {
            let years = d2.getFullYear() - d1.getFullYear();
            let months = d2.getMonth() - d1.getMonth();
            let days = d2.getDate() - d1.getDate();

            if (days < 0) {
                months--;
                // Days in previous month
                const prevMonth = new Date(d2.getFullYear(), d2.getMonth(), 0);
                days += prevMonth.getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }
            return { years, months, days };
        }

        function getBusinessDays(startDate, endDate, inclusive) {
            if (startDate > endDate) [startDate, endDate] = [endDate, startDate];
            
            let count = 0;
            let cur = new Date(startDate);
            // If inclusive, we need to check the end date too
            // If we normalize loop: while cur < end (standard diff), add inclusive check later?
            // Easiest is to iterate
            
            const endLimit = new Date(endDate);
            if(inclusive) endLimit.setDate(endLimit.getDate() + 1);

            while (cur < endLimit) {
                const dayOfWeek = cur.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) { // 0=Sun, 6=Sat
                    count++;
                }
                cur.setDate(cur.getDate() + 1);
            }
            return count;
        }

        // --- LISTENERS ---
        startDateInput.addEventListener('change', calculate);
        endDateInput.addEventListener('change', calculate);
        optInclusive.addEventListener('change', calculate);

    </script>
</body>
</html>