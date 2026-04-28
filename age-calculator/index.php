<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Date Inputs */
        input[type="date"] {
            color-scheme: dark;
            font-family: 'Inter', sans-serif;
        }

        /* Stat Card Hover */
        .stat-card {
            transition: transform 0.2s, border-color 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b;
        }

        /* Birthday Confetti Animation (Simple CSS pulse) */
        @keyframes party {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .party-mode {
            animation: party 0.5s infinite;
            color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Age Calculator</h1>
                <p class="text-center text-gray-400">Calculate age duration and fun life statistics.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-gray-700 pb-8">
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Date of Birth</label>
                    <input type="date" id="dob-input" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Calculate Age At</label>
                        <button class="text-xs text-emerald-400 hover:text-white underline" onclick="setToday()">Today</button>
                    </div>
                    <input type="date" id="target-input" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer">
                </div>

            </div>

            <div id="results-area" class="hidden space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="md:col-span-2 bg-gray-900 p-6 rounded-2xl border border-gray-700 relative overflow-hidden shadow-lg flex flex-col justify-center">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-red-500"></div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Exact Age</span>
                        
                        <div class="flex items-baseline gap-2 mb-2">
                            <span id="age-years" class="text-6xl font-black text-white">0</span>
                            <span class="text-xl text-gray-400 font-bold">Years</span>
                        </div>
                        <div class="flex gap-4 text-lg font-bold text-gray-300">
                            <span><span id="age-months" class="text-emerald-400">0</span> Months</span>
                            <span><span id="age-days" class="text-blue-400">0</span> Days</span>
                        </div>
                    </div>

                    <div class="bg-gray-900 p-6 rounded-2xl border border-gray-700 relative overflow-hidden shadow-lg flex flex-col justify-center text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Next Birthday</span>
                        <div id="bday-icon" class="text-4xl mb-2">🎂</div>
                        <div id="next-bday-val" class="text-3xl font-bold text-white mb-1">0 Days</div>
                        <div id="next-bday-date" class="text-xs text-gray-500">on a Tuesday</div>
                    </div>

                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-3">Total Life Duration</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Total Weeks</span>
                            <div id="stat-weeks" class="text-xl font-bold text-white mono-font mt-1">0</div>
                        </div>

                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Total Days</span>
                            <div id="stat-days" class="text-xl font-bold text-white mono-font mt-1">0</div>
                        </div>

                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Total Hours</span>
                            <div id="stat-hours" class="text-xl font-bold text-white mono-font mt-1">0</div>
                        </div>

                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Approx Breaths</span>
                            <div id="stat-breaths" class="text-xl font-bold text-purple-400 mono-font mt-1">0</div>
                        </div>

                    </div>
                </div>

            </div>

            <div id="empty-state" class="text-center py-12 text-gray-600">
                <span class="text-4xl block mb-2">👶</span>
                Enter your Date of Birth above.
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const dobInput = document.getElementById('dob-input');
        const targetInput = document.getElementById('target-input');
        const resultsArea = document.getElementById('results-area');
        const emptyState = document.getElementById('empty-state');

        // Outputs
        const elYears = document.getElementById('age-years');
        const elMonths = document.getElementById('age-months');
        const elDays = document.getElementById('age-days');
        
        const nextBdayVal = document.getElementById('next-bday-val');
        const nextBdayDate = document.getElementById('next-bday-date');
        const bdayIcon = document.getElementById('bday-icon');

        const statWeeks = document.getElementById('stat-weeks');
        const statDays = document.getElementById('stat-days');
        const statHours = document.getElementById('stat-hours');
        const statBreaths = document.getElementById('stat-breaths');

        // Days of week
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        function setToday() {
            targetInput.value = new Date().toISOString().split('T')[0];
            calculate();
        }

        function calculate() {
            if(!dobInput.value || !targetInput.value) {
                resultsArea.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            // Parse dates ensuring local time 00:00:00 to avoid timezone shifts
            const dobParts = dobInput.value.split('-');
            const targetParts = targetInput.value.split('-');
            
            const dob = new Date(dobParts[0], dobParts[1]-1, dobParts[2]);
            const target = new Date(targetParts[0], targetParts[1]-1, targetParts[2]);

            if (target < dob) {
                // Invalid state (Target before birth)
                alert("Date of birth cannot be in the future relative to the target date.");
                return;
            }

            // 1. Precise Age Calculation (Y/M/D)
            let years = target.getFullYear() - dob.getFullYear();
            let months = target.getMonth() - dob.getMonth();
            let days = target.getDate() - dob.getDate();

            if (days < 0) {
                months--;
                // Days in previous month
                const prevMonth = new Date(target.getFullYear(), target.getMonth(), 0);
                days += prevMonth.getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }

            elYears.textContent = years;
            elMonths.textContent = months;
            elDays.textContent = days;

            // 2. Next Birthday Calculation
            const currentYear = target.getFullYear();
            let nextBday = new Date(currentYear, dob.getMonth(), dob.getDate());
            
            // If birthday passed this year, next one is next year
            if (target > nextBday) {
                nextBday.setFullYear(currentYear + 1);
            }

            // Check if TODAY is birthday
            if (target.getDate() === dob.getDate() && target.getMonth() === dob.getMonth()) {
                nextBdayVal.textContent = "Today!";
                nextBdayVal.classList.add('party-mode');
                nextBdayDate.textContent = "Happy Birthday!";
                bdayIcon.textContent = "🎉";
            } else {
                nextBdayVal.classList.remove('party-mode');
                bdayIcon.textContent = "🎂";
                
                const diffTime = nextBday - target;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                nextBdayVal.textContent = diffDays + (diffDays === 1 ? " Day" : " Days");
                nextBdayDate.textContent = "on a " + daysOfWeek[nextBday.getDay()];
            }

            // 3. Total Stats
            const totalDiff = Math.abs(target - dob);
            const totalDays = Math.floor(totalDiff / (1000 * 60 * 60 * 24));
            
            statDays.textContent = totalDays.toLocaleString();
            statWeeks.textContent = Math.floor(totalDays / 7).toLocaleString();
            statHours.textContent = (totalDays * 24).toLocaleString();
            
            // Approx breaths (16 per min avg)
            // total minutes * 16
            const totalMins = totalDays * 24 * 60;
            const breaths = totalMins * 16;
            
            // Format large numbers (e.g. 15.4M)
            statBreaths.textContent = breaths.toLocaleString();

            // Show UI
            resultsArea.classList.remove('hidden');
            emptyState.classList.add('hidden');
        }

        // Initialize
        setToday();
        dobInput.addEventListener('change', calculate);
        targetInput.addEventListener('change', calculate);

    </script>
</body>
</html>