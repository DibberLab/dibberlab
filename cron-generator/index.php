<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cron Job Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Tab Buttons */
        .tab-btn {
            transition: all 0.2s;
            border-bottom: 2px solid transparent;
        }
        .tab-btn:hover { color: white; }
        .tab-btn.active {
            color: #f59e0b;
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }

        /* Selection Grid Items */
        .select-item {
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }
        .select-item:hover { background-color: #4b5563; }
        .select-item.selected {
            background-color: #f59e0b;
            color: #111827;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.4);
        }

        /* Radio Labels */
        .radio-label { cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .radio-input { accent-color: #f59e0b; width: 16px; height: 16px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Cron Job Generator</h1>
                <p class="text-center text-gray-400">Build schedule expressions for Linux crontab.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-7 flex flex-col">
                    
                    <div class="flex border-b border-gray-700 mb-4 overflow-x-auto">
                        <button class="tab-btn active px-4 py-3 font-bold text-sm text-gray-400 whitespace-nowrap" data-tab="minutes">Minutes</button>
                        <button class="tab-btn px-4 py-3 font-bold text-sm text-gray-400 whitespace-nowrap" data-tab="hours">Hours</button>
                        <button class="tab-btn px-4 py-3 font-bold text-sm text-gray-400 whitespace-nowrap" data-tab="days">Day</button>
                        <button class="tab-btn px-4 py-3 font-bold text-sm text-gray-400 whitespace-nowrap" data-tab="months">Month</button>
                        <button class="tab-btn px-4 py-3 font-bold text-sm text-gray-400 whitespace-nowrap" data-tab="weekdays">Weekday</button>
                    </div>

                    <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 min-h-[300px]">
                        
                        <div id="tab-minutes" class="tab-content">
                            <div class="space-y-4">
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="every" checked class="radio-input">
                                    <span>Every minute (*)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="even" class="radio-input">
                                    <span>Even minutes (*/2)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="odd" class="radio-input">
                                    <span>Odd minutes (1-59/2)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="every-5" class="radio-input">
                                    <span>Every 5 minutes (*/5)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="every-15" class="radio-input">
                                    <span>Every 15 minutes (*/15)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="min-type" value="specific" class="radio-input">
                                    <span>Specific minutes</span>
                                </label>
                                
                                <div id="min-grid" class="grid grid-cols-10 gap-1 mt-2 opacity-50 pointer-events-none transition-opacity">
                                    </div>
                            </div>
                        </div>

                        <div id="tab-hours" class="tab-content hidden">
                            <div class="space-y-4">
                                <label class="radio-label">
                                    <input type="radio" name="hour-type" value="every" checked class="radio-input">
                                    <span>Every hour (*)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="hour-type" value="even" class="radio-input">
                                    <span>Even hours (*/2)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="hour-type" value="every-6" class="radio-input">
                                    <span>Every 6 hours (*/6)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="hour-type" value="specific" class="radio-input">
                                    <span>Specific hours</span>
                                </label>
                                <div id="hour-grid" class="grid grid-cols-6 gap-2 mt-2 opacity-50 pointer-events-none transition-opacity">
                                    </div>
                            </div>
                        </div>

                        <div id="tab-days" class="tab-content hidden">
                            <div class="space-y-4">
                                <label class="radio-label">
                                    <input type="radio" name="day-type" value="every" checked class="radio-input">
                                    <span>Every day (*)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="day-type" value="specific" class="radio-input">
                                    <span>Specific days</span>
                                </label>
                                <div id="day-grid" class="grid grid-cols-7 gap-2 mt-2 opacity-50 pointer-events-none transition-opacity">
                                    </div>
                            </div>
                        </div>

                        <div id="tab-months" class="tab-content hidden">
                            <div class="space-y-4">
                                <label class="radio-label">
                                    <input type="radio" name="month-type" value="every" checked class="radio-input">
                                    <span>Every month (*)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="month-type" value="specific" class="radio-input">
                                    <span>Specific months</span>
                                </label>
                                <div id="month-grid" class="grid grid-cols-3 gap-2 mt-2 opacity-50 pointer-events-none transition-opacity">
                                    </div>
                            </div>
                        </div>

                        <div id="tab-weekdays" class="tab-content hidden">
                            <div class="space-y-4">
                                <label class="radio-label">
                                    <input type="radio" name="week-type" value="every" checked class="radio-input">
                                    <span>Every weekday (*)</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="week-type" value="specific" class="radio-input">
                                    <span>Specific days of the week</span>
                                </label>
                                <div id="week-grid" class="grid grid-cols-2 gap-2 mt-2 opacity-50 pointer-events-none transition-opacity">
                                    </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <div class="bg-gray-900 rounded-xl border border-gray-600 p-6 flex flex-col items-center justify-center text-center shadow-lg relative">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-4">Cron Expression</div>
                        
                        <div class="flex gap-2 font-mono text-4xl md:text-5xl font-bold text-emerald-400 mb-6 flex-wrap justify-center">
                            <span id="res-min">*</span>
                            <span id="res-hour">*</span>
                            <span id="res-day">*</span>
                            <span id="res-month">*</span>
                            <span id="res-week">*</span>
                        </div>

                        <p id="human-readable" class="text-gray-300 text-sm leading-relaxed border-t border-gray-700 pt-4 w-full">
                            Run every minute.
                        </p>

                        <div class="grid grid-cols-5 w-full text-[10px] text-gray-600 uppercase font-bold mt-4 text-center">
                            <div>Min</div>
                            <div>Hour</div>
                            <div>Day</div>
                            <div>Mon</div>
                            <div>Week</div>
                        </div>

                        <button id="copy-btn" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-3 block">Quick Presets</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="* * * * *">Every Minute</button>
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="*/5 * * * *">Every 5 Mins</button>
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="0 * * * *">Hourly</button>
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="0 0 * * *">Daily (Midnight)</button>
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="0 0 * * 0">Weekly (Sun)</button>
                            <button class="preset-btn bg-gray-700 hover:bg-gray-600 border border-gray-600 p-2 rounded text-xs text-left transition-colors" data-val="0 0 1 * *">Monthly (1st)</button>
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
        // --- DATA ---
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        // --- STATE ---
        let state = {
            min: '*', hour: '*', day: '*', month: '*', week: '*'
        };

        // --- UI BUILDER ---
        
        // 1. Minute Grid (0-59)
        const minGrid = document.getElementById('min-grid');
        for(let i=0; i<60; i++) {
            const div = document.createElement('div');
            div.className = 'select-item text-xs text-center py-1 rounded bg-gray-700 text-gray-300';
            div.textContent = i;
            div.dataset.val = i;
            div.onclick = () => toggleSelection(div, 'min');
            minGrid.appendChild(div);
        }

        // 2. Hour Grid (0-23)
        const hourGrid = document.getElementById('hour-grid');
        for(let i=0; i<24; i++) {
            const div = document.createElement('div');
            div.className = 'select-item text-xs text-center py-2 rounded bg-gray-700 text-gray-300';
            div.textContent = i.toString().padStart(2, '0');
            div.dataset.val = i;
            div.onclick = () => toggleSelection(div, 'hour');
            hourGrid.appendChild(div);
        }

        // 3. Day Grid (1-31)
        const dayGrid = document.getElementById('day-grid');
        for(let i=1; i<=31; i++) {
            const div = document.createElement('div');
            div.className = 'select-item text-xs text-center py-2 rounded bg-gray-700 text-gray-300';
            div.textContent = i;
            div.dataset.val = i;
            div.onclick = () => toggleSelection(div, 'day');
            dayGrid.appendChild(div);
        }

        // 4. Month Grid
        const monthGrid = document.getElementById('month-grid');
        months.forEach((m, i) => {
            const div = document.createElement('div');
            div.className = 'select-item text-sm text-center py-2 rounded bg-gray-700 text-gray-300';
            div.textContent = m;
            div.dataset.val = i + 1; // 1-12
            div.onclick = () => toggleSelection(div, 'month');
            monthGrid.appendChild(div);
        });

        // 5. Week Grid
        const weekGrid = document.getElementById('week-grid');
        days.forEach((d, i) => {
            const div = document.createElement('div');
            div.className = 'select-item text-sm text-center py-2 rounded bg-gray-700 text-gray-300';
            div.textContent = d;
            div.dataset.val = i; // 0-6
            div.onclick = () => toggleSelection(div, 'week');
            weekGrid.appendChild(div);
        });

        // --- CORE LOGIC ---

        function toggleSelection(el, type) {
            el.classList.toggle('selected');
            
            // Gather all selected
            const parent = el.parentElement;
            const selected = Array.from(parent.querySelectorAll('.selected')).map(e => e.dataset.val);
            
            if (selected.length === 0) {
                state[type] = '*'; // Default back to every if none selected
                // But for specific mode, we usually want at least one. 
                // Let's visual logic handle the '*' part in updateState.
            } else {
                // Sort numbers numerically
                selected.sort((a,b) => parseInt(a) - parseInt(b));
                state[type] = selected.join(',');
            }
            updateDisplay();
        }

        function updateStateFromRadio(name, type, gridId) {
            const radios = document.getElementsByName(name);
            let val = '';
            
            // Enable/Disable Grid
            const grid = document.getElementById(gridId);
            let isSpecific = false;

            for(const r of radios) {
                if(r.checked) {
                    if(r.value === 'every') val = '*';
                    else if(r.value === 'even') val = type === 'min' || type === 'hour' ? '*/2' : '*/2'; // simplified
                    else if(r.value === 'odd') val = '1-59/2'; 
                    else if(r.value === 'every-5') val = '*/5';
                    else if(r.value === 'every-6') val = '*/6';
                    else if(r.value === 'every-15') val = '*/15';
                    else if(r.value === 'specific') {
                        isSpecific = true;
                        // Calculate from grid
                        const selected = Array.from(grid.querySelectorAll('.selected')).map(e => e.dataset.val);
                        val = selected.length > 0 ? selected.sort((a,b) => a-b).join(',') : '*';
                    }
                }
            }

            if(isSpecific) {
                grid.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                grid.classList.add('opacity-50', 'pointer-events-none');
            }

            state[type] = val;
            updateDisplay();
        }

        function updateDisplay() {
            document.getElementById('res-min').textContent = state.min;
            document.getElementById('res-hour').textContent = state.hour;
            document.getElementById('res-day').textContent = state.day;
            document.getElementById('res-month').textContent = state.month;
            document.getElementById('res-week').textContent = state.week;

            generateHumanReadable();
        }

        function generateHumanReadable() {
            // Simple translator
            let s = "Run ";
            
            // Time
            if(state.min === '*' && state.hour === '*') s += "every minute";
            else if(state.min !== '*' && state.hour === '*') s += `at minute ${state.min} of every hour`;
            else if(state.min === '0' && state.hour !== '*') s += `at minute 0 past hour ${state.hour}`;
            else if(state.min !== '*' && state.hour !== '*') s += `at ${state.hour}:${state.min}`;
            
            // Date
            if(state.day !== '*') s += ` on day-of-month ${state.day}`;
            if(state.month !== '*') s += ` in month ${state.month}`;
            if(state.week !== '*') s += ` and on day-of-week ${state.week}`;
            
            document.getElementById('human-readable').textContent = s + ".";
        }

        // --- LISTENERS ---

        // Radio Changes
        document.querySelectorAll('input[type=radio]').forEach(r => {
            r.addEventListener('change', (e) => {
                const name = e.target.name;
                if(name === 'min-type') updateStateFromRadio('min-type', 'min', 'min-grid');
                if(name === 'hour-type') updateStateFromRadio('hour-type', 'hour', 'hour-grid');
                if(name === 'day-type') updateStateFromRadio('day-type', 'day', 'day-grid');
                if(name === 'month-type') updateStateFromRadio('month-type', 'month', 'month-grid');
                if(name === 'week-type') updateStateFromRadio('week-type', 'week', 'week-grid');
            });
        });

        // Tabs
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // UI Toggle
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Content Toggle
                tabContents.forEach(c => c.classList.add('hidden'));
                document.getElementById(`tab-${btn.dataset.tab}`).classList.remove('hidden');
            });
        });

        // Copy
        document.getElementById('copy-btn').addEventListener('click', function() {
            const cron = `${state.min} ${state.hour} ${state.day} ${state.month} ${state.week}`;
            navigator.clipboard.writeText(cron).then(() => {
                const icon = this.innerHTML;
                this.innerHTML = `<span class="text-emerald-400 font-bold text-xs">OK</span>`;
                setTimeout(() => this.innerHTML = icon, 1500);
            });
        });

        // Presets
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const parts = btn.dataset.val.split(' ');
                state.min = parts[0];
                state.hour = parts[1];
                state.day = parts[2];
                state.month = parts[3];
                state.week = parts[4];
                
                // Note: Updating UI elements (radios/grids) to match presets is complex logic.
                // For this MVP, we update the output directly.
                updateDisplay();
            });
        });

        // Init
        updateDisplay();

    </script>
</body>
</html>