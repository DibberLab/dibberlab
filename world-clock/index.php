<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Clock | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* ANALOG CLOCK STYLES */
        .clock-face {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #1f2937; /* Gray-800 */
            border: 4px solid #374151; /* Gray-700 */
            position: relative;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
        }

        /* Center Dot */
        .clock-face::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #f59e0b; /* Amber */
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        /* Hands */
        .hand {
            position: absolute;
            bottom: 50%;
            left: 50%;
            transform-origin: bottom center;
            border-radius: 4px;
            transition: transform 0.05s cubic-bezier(0.4, 2.08, 0.55, 0.44); /* Tick effect */
        }

        .hand-hour {
            width: 4px;
            height: 30px;
            background: #9ca3af; /* Gray-400 */
            z-index: 2;
            margin-left: -2px;
        }

        .hand-min {
            width: 2px;
            height: 45px;
            background: #e5e7eb; /* Gray-200 */
            z-index: 3;
            margin-left: -1px;
        }

        .hand-sec {
            width: 1px;
            height: 50px;
            background: #f59e0b; /* Amber */
            z-index: 4;
            margin-left: -0.5px;
            transition: none; /* Smooth sweep or distinct tick? Let's go distinct */
        }

        /* Markers (12, 3, 6, 9) */
        .marker {
            position: absolute;
            width: 2px;
            height: 6px;
            background: #4b5563;
            left: 50%;
            margin-left: -1px;
        }
        .marker-12 { top: 2px; }
        .marker-6  { bottom: 2px; }
        .marker-9  { top: 50%; left: 2px; transform: translateY(-50%) rotate(90deg); }
        .marker-3  { top: 50%; right: 2px; left: auto; transform: translateY(-50%) rotate(90deg); }

        /* Card Hover */
        .clock-card {
            transition: transform 0.2s, border-color 0.2s;
        }
        .clock-card:hover {
            transform: translateY(-4px);
            border-color: #f59e0b;
        }

        /* Add Button */
        .add-card {
            border: 2px dashed #374151;
            transition: all 0.2s;
        }
        .add-card:hover {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.05);
            color: #10b981;
        }

        /* Modal Animation */
        .modal-enter { animation: fadeScale 0.2s ease-out; }
        @keyframes fadeScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Select Styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-7xl mx-auto">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400">World Clock</h1>
                    <p class="text-gray-400 text-sm mt-1">Track time across timezones.</p>
                </div>
                <div class="text-right">
                    <div id="local-date" class="text-sm font-bold text-gray-500 uppercase tracking-widest">TODAY</div>
                    <div id="local-time" class="text-2xl font-black text-white mono-font">00:00:00</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="clock-grid">
                
                <button onclick="openModal()" class="add-card h-48 rounded-2xl flex flex-col items-center justify-center text-gray-500 cursor-pointer group">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition-transform">+</span>
                    <span class="font-bold uppercase text-xs tracking-widest">Add City</span>
                </button>

            </div>

        </div>
    </main>

    <div id="add-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-600 rounded-2xl w-full max-w-md p-6 shadow-2xl modal-enter">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Add Location</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">✕</button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Timezone</label>
                    <select id="zone-select" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500">
                        <option value="America/New_York">New York (EST)</option>
                        <option value="America/Los_Angeles">Los Angeles (PST)</option>
                        <option value="Europe/London">London (GMT)</option>
                        <option value="Europe/Paris">Paris (CET)</option>
                        <option value="Asia/Tokyo">Tokyo (JST)</option>
                        <option value="Asia/Dubai">Dubai (GST)</option>
                        <option value="Australia/Sydney">Sydney (AEDT)</option>
                        <option value="Asia/Singapore">Singapore (SGT)</option>
                        <option value="Asia/Kolkata">India (IST)</option>
                        <option value="UTC">UTC (Universal)</option>
                    </select>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Custom Label</label>
                    <input type="text" id="city-label" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500" placeholder="e.g. Head Office">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-400 hover:text-white font-bold transition-colors">Cancel</button>
                <button onclick="addCity()" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold shadow-lg transition-transform hover:-translate-y-1">Add Clock</button>
            </div>
        </div>
    </div>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA & STATE ---
        const grid = document.getElementById('clock-grid');
        const modal = document.getElementById('add-modal');
        const zoneSelect = document.getElementById('zone-select');
        const cityLabel = document.getElementById('city-label');
        
        // Default clocks
        let locations = JSON.parse(localStorage.getItem('dibber-world-clocks')) || [
            { id: 1, label: 'Local Time', zone: Intl.DateTimeFormat().resolvedOptions().timeZone },
            { id: 2, label: 'London', zone: 'Europe/London' },
            { id: 3, label: 'Tokyo', zone: 'Asia/Tokyo' }
        ];

        // --- RENDER LOGIC ---

        function renderClocks() {
            // Remove existing clocks (keep the last child which is the Add Button)
            while (grid.children.length > 1) {
                grid.removeChild(grid.firstChild);
            }

            locations.forEach(loc => {
                const card = createClockCard(loc);
                grid.insertBefore(card, grid.lastElementChild);
            });
        }

        function createClockCard(loc) {
            const div = document.createElement('div');
            div.className = "clock-card bg-gray-800 rounded-2xl border border-gray-700 p-6 flex flex-col items-center relative";
            div.id = `clock-${loc.id}`;

            // Delete Button (Except for Local)
            const deleteBtn = loc.label === 'Local Time' ? '' : 
                `<button onclick="removeCity(${loc.id})" class="absolute top-3 right-3 text-gray-600 hover:text-red-400 transition-colors">×</button>`;

            div.innerHTML = `
                ${deleteBtn}
                <div class="flex items-center gap-2 mb-4">
                    <h2 class="font-bold text-white text-lg">${loc.label}</h2>
                </div>
                
                <div class="clock-face mb-4">
                    <div class="marker marker-12"></div>
                    <div class="marker marker-3"></div>
                    <div class="marker marker-6"></div>
                    <div class="marker marker-9"></div>
                    <div class="hand hand-hour" id="h-${loc.id}"></div>
                    <div class="hand hand-min" id="m-${loc.id}"></div>
                    <div class="hand hand-sec" id="s-${loc.id}"></div>
                </div>

                <div class="text-2xl font-bold text-white mono-font mb-1" id="time-${loc.id}">--:--</div>
                
                <div class="flex justify-between w-full px-2 text-xs font-bold uppercase mt-2 border-t border-gray-700 pt-3">
                    <span id="diff-${loc.id}" class="text-gray-500">0 HRS</span>
                    <span id="day-${loc.id}" class="text-amber-400">DAY</span>
                </div>
            `;
            return div;
        }

        // --- UPDATE LOOP ---

        function updateTime() {
            const now = new Date();
            const localTime = now.getTime();

            // Update Header Local Time
            document.getElementById('local-time').textContent = now.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById('local-date').textContent = now.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });

            locations.forEach(loc => {
                // Get time in target zone
                const zoneDateString = now.toLocaleString("en-US", { timeZone: loc.zone });
                const zoneDate = new Date(zoneDateString);

                // 1. Digital Display
                const digital = zoneDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                const elTime = document.getElementById(`time-${loc.id}`);
                if(elTime) elTime.textContent = digital;

                // 2. Analog Hands
                const s = zoneDate.getSeconds();
                const m = zoneDate.getMinutes();
                const h = zoneDate.getHours();

                const sDeg = (s / 60) * 360;
                const mDeg = ((m + s / 60) / 60) * 360;
                const hDeg = ((h % 12 + m / 60) / 12) * 360;

                const elS = document.getElementById(`s-${loc.id}`);
                const elM = document.getElementById(`m-${loc.id}`);
                const elH = document.getElementById(`h-${loc.id}`);

                if(elS) elS.style.transform = `translateX(-50%) rotate(${sDeg}deg)`;
                if(elM) elM.style.transform = `translateX(-50%) rotate(${mDeg}deg)`;
                if(elH) elH.style.transform = `translateX(-50%) rotate(${hDeg}deg)`;

                // 3. Time Diff & Day/Night
                // Calculate Offset relative to local
                // Need UTC offset of local and target. 
                // Simple hack: compare hours directly (handling date rollovers is tricky without libraries)
                // Better approach: Use formatted parts to compare offsets? 
                // Let's rely on simple hour comparison for UI "Diff" label roughly.
                
                // Diff logic:
                const localOffset = now.getTimezoneOffset() * -1; // in minutes
                // Getting offset of target zone is hard in pure JS without libraries.
                // We will approximate by comparing the Hour values.
                
                // Day/Night logic
                const isDay = h >= 6 && h < 18;
                const elDay = document.getElementById(`day-${loc.id}`);
                if(elDay) {
                    elDay.textContent = isDay ? "☀️ DAY" : "🌙 NIGHT";
                    elDay.className = isDay ? "text-amber-400" : "text-blue-400";
                }

                // Diff Logic (Approx)
                const elDiff = document.getElementById(`diff-${loc.id}`);
                if(elDiff && loc.label !== 'Local Time') {
                    // Compare timestamps roughly?
                    // Actually, let's just show the Zone Abbreviation if possible, or just skip diff calculation for lightweight MVP
                    // Or compare hours:
                    let diffH = h - now.getHours();
                    if(diffH < -12) diffH += 24;
                    if(diffH > 12) diffH -= 24;
                    
                    // This fails if dates are different. 
                    // Let's format the date string to see if it's "Yesterday" or "Tomorrow"
                    const localDay = now.getDate();
                    const zoneDayNum = zoneDate.getDate();
                    
                    let dayDiff = "";
                    if (zoneDayNum !== localDay) {
                        // Check if it's just month rollover or actual day diff
                        // Simple check
                        if (zoneDayNum > localDay || (localDay > 28 && zoneDayNum === 1)) dayDiff = "+1 Day";
                        else dayDiff = "-1 Day";
                    }

                    const sign = diffH >= 0 ? "+" : "";
                    elDiff.textContent = dayDiff ? dayDiff : `${sign}${diffH} HRS`;
                } else if(elDiff) {
                    elDiff.textContent = "LOC";
                }
            });
        }

        // --- ACTIONS ---

        function openModal() {
            modal.classList.remove('hidden');
            cityLabel.value = "";
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function addCity() {
            const zone = zoneSelect.value;
            // Default label to City Name from Zone
            let label = cityLabel.value;
            if(!label) {
                const parts = zone.split('/');
                label = parts[parts.length - 1].replace('_', ' ');
            }

            const newId = Date.now();
            locations.push({ id: newId, label: label, zone: zone });
            
            saveData();
            renderClocks();
            closeModal();
            updateTime(); // Instant update
        }

        function removeCity(id) {
            locations = locations.filter(l => l.id !== id);
            saveData();
            renderClocks();
            updateTime();
        }

        function saveData() {
            localStorage.setItem('dibber-world-clocks', JSON.stringify(locations));
        }

        // --- INIT ---
        renderClocks();
        setInterval(updateTime, 1000);
        updateTime();

        // Keyboard close
        document.addEventListener('keydown', (e) => {
            if(e.key === 'Escape') closeModal();
        });

    </script>
</body>
</html>