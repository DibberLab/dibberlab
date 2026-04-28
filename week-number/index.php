<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week Number | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Year Grid */
        .week-block {
            aspect-ratio: 1;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .week-past { background-color: #374151; /* Gray-700 */ }
        .week-current { 
            background-color: #10b981; /* Emerald-500 */ 
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
            animation: pulse-block 2s infinite;
        }
        .week-future { background-color: #1f2937; /* Gray-800 */ border: 1px solid #374151; }

        @keyframes pulse-block {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Progress Bar */
        .progress-container {
            background: #1f2937;
            height: 12px;
            border-radius: 99px;
            overflow: hidden;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            width: 0%;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Stat Card */
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b; /* Amber */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Week Number</h1>
                <p class="text-center text-gray-400">Track current progress through the year.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 p-8 text-center relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-blue-500"></div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Current Week</span>
                        <div class="text-8xl font-black text-white mt-2 mb-2 tracking-tighter" id="week-val">0</div>
                        <div class="text-emerald-400 font-bold" id="week-range">Oct 23 - Oct 29</div>
                    </div>

                    <div class="bg-gray-900 rounded-xl border border-gray-700 p-6">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-bold text-gray-500 uppercase">Year Progress</span>
                            <span class="text-xl font-bold text-white mono-font" id="percent-val">0%</span>
                        </div>
                        <div class="progress-container mb-2">
                            <div id="progress-bar" class="progress-fill"></div>
                        </div>
                        <p class="text-[10px] text-gray-500 text-right"><span id="days-left">0</span> days remaining in <span id="current-year">2023</span></p>
                    </div>

                </div>

                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Day of Year</span>
                            <div class="text-2xl font-bold text-white mono-font mt-1"><span id="day-ordinal">0</span><span class="text-gray-500 text-sm"> / <span id="days-total">365</span></span></div>
                        </div>
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Quarter</span>
                            <div class="text-2xl font-bold text-blue-400 mono-font mt-1">Q<span id="quarter-val">1</span></div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 flex-grow flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase">Year Map</span>
                            <div class="flex gap-3 text-[10px] uppercase font-bold text-gray-500">
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded bg-gray-700"></span> Past</span>
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded bg-emerald-500"></span> Now</span>
                            </div>
                        </div>
                        
                        <div id="year-grid" class="grid grid-cols-13 gap-1.5 md:gap-2">
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
        // DOM Elements
        const elWeek = document.getElementById('week-val');
        const elRange = document.getElementById('week-range');
        const elPercent = document.getElementById('percent-val');
        const elBar = document.getElementById('progress-bar');
        const elDaysLeft = document.getElementById('days-left');
        const elYear = document.getElementById('current-year');
        const elOrdinal = document.getElementById('day-ordinal');
        const elTotalDays = document.getElementById('days-total');
        const elQuarter = document.getElementById('quarter-val');
        const grid = document.getElementById('year-grid');

        function init() {
            const now = new Date();
            const year = now.getFullYear();
            elYear.textContent = year;

            // 1. Calculate Week Number (ISO 8601)
            const weekNum = getISOWeek(now);
            elWeek.textContent = weekNum;

            // 2. Week Range
            const range = getWeekRange(now);
            elRange.textContent = `${range.start} - ${range.end}`;

            // 3. Day of Year
            const startOfYear = new Date(year, 0, 0);
            const diff = now - startOfYear;
            const oneDay = 1000 * 60 * 60 * 24;
            const dayOfYear = Math.floor(diff / oneDay);
            elOrdinal.textContent = dayOfYear;

            // Leap Year Check
            const isLeap = ((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0);
            const daysInYear = isLeap ? 366 : 365;
            elTotalDays.textContent = daysInYear;

            // 4. Progress
            const percent = (dayOfYear / daysInYear) * 100;
            elPercent.textContent = percent.toFixed(1) + "%";
            elBar.style.width = percent + "%";
            elDaysLeft.textContent = daysInYear - dayOfYear;

            // 5. Quarter
            const month = now.getMonth(); // 0-11
            const q = Math.floor(month / 3) + 1;
            elQuarter.textContent = q;

            // 6. Render Map
            renderMap(year, weekNum);
        }

        // ISO 8601 Week Number
        function getISOWeek(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            return Math.ceil((((d - yearStart) / 86400000) + 1)/7);
        }

        function getWeekRange(date) {
            const d = new Date(date);
            const day = d.getDay(); 
            const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is sunday
            
            const start = new Date(d.setDate(diff));
            const end = new Date(d.setDate(start.getDate() + 6));

            const format = (date) => date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            return { start: format(start), end: format(end) };
        }

        function renderMap(year, currentWeek) {
            // Determine total weeks (52 or 53)
            // Dec 28th is always in the last week of the year
            const lastDay = new Date(year, 11, 28);
            const totalWeeks = getISOWeek(lastDay);

            grid.innerHTML = "";
            
            // Adjust grid columns based on screen size via CSS classes
            // Default Tailwind grid-cols-13 fits 52 weeks perfectly (4 rows of 13)
            
            for(let i = 1; i <= totalWeeks; i++) {
                const div = document.createElement('div');
                div.className = "week-block";
                
                if (i < currentWeek) {
                    div.classList.add('week-past');
                    div.title = `Week ${i} (Passed)`;
                } else if (i === currentWeek) {
                    div.classList.add('week-current');
                    div.title = `Week ${i} (Current)`;
                } else {
                    div.classList.add('week-future');
                    div.title = `Week ${i}`;
                }
                
                grid.appendChild(div);
            }
        }

        init();

    </script>
</body>
</html>