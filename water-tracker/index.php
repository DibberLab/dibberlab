<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Tracker | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Water Tank */
        .water-tank {
            position: relative;
            width: 200px;
            height: 300px;
            background: rgba(31, 41, 55, 0.5); /* Gray-800/50 */
            border: 4px solid #374151; /* Gray-700 */
            border-radius: 0 0 24px 24px;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
        }

        /* The Liquid Fill */
        .liquid {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%; /* Dynamic */
            background: linear-gradient(to top, #0891b2, #22d3ee); /* Cyan-600 to Cyan-400 */
            transition: height 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
        }

        /* Wave Animation on top of liquid */
        .liquid::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 20px;
            background: #22d3ee;
            opacity: 0.5;
            bottom: 100%;
            left: 0;
            border-radius: 40%;
            animation: wave 6s linear infinite;
            transform: translateX(0);
        }
        
        .liquid::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 25px;
            background: #22d3ee;
            opacity: 0.3;
            bottom: 100%;
            left: 0;
            border-radius: 35%;
            animation: wave 9s linear infinite reverse;
            transform: translateX(0);
        }

        @keyframes wave {
            0% { transform: translateX(0) translateY(2px); }
            50% { transform: translateX(-25%) translateY(-2px); }
            100% { transform: translateX(-50%) translateY(2px); }
        }

        /* Bubbles */
        .bubble {
            position: absolute;
            bottom: -10px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: rise 4s infinite ease-in;
        }
        @keyframes rise {
            0% { bottom: -10px; transform: translateX(0); opacity: 0; }
            50% { opacity: 1; }
            100% { bottom: 100%; transform: translateX(-20px); opacity: 0; }
        }

        /* Quick Add Buttons */
        .add-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .add-btn:hover {
            transform: translateY(-2px);
            border-color: #22d3ee;
            background: rgba(34, 211, 238, 0.1);
        }
        .add-btn:active { transform: translateY(0); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        /* Glass effect for overlay text */
        .glass-text {
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-5 flex flex-col items-center justify-center">
                
                <div class="water-tank mb-6" id="tank">
                    <div class="liquid" id="liquid"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center z-20 pointer-events-none">
                        <div class="text-5xl font-black text-white glass-text mono-font"><span id="display-percent">0</span>%</div>
                        <div class="text-sm font-bold text-gray-200 glass-text mt-1 opacity-80"><span id="display-current">0</span> / <span id="display-goal">2500</span> ml</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-gray-800/50 px-4 py-2 rounded-full border border-gray-700">
                    <label class="text-xs font-bold text-gray-500 uppercase">Daily Goal</label>
                    <input type="number" id="goal-input" class="bg-transparent w-16 text-right text-white font-bold focus:outline-none border-b border-gray-600 focus:border-cyan-400 text-sm" value="2500">
                    <span class="text-xs text-gray-500 font-bold">ml</span>
                </div>

            </div>

            <div class="lg:col-span-7 flex flex-col gap-6 h-[500px]">
                
                <div class="grid grid-cols-3 gap-4">
                    <button class="add-btn bg-gray-800 p-4 rounded-2xl flex flex-col items-center gap-2 group" onclick="addWater(150)">
                        <div class="text-2xl group-hover:scale-110 transition-transform">☕</div>
                        <div class="text-xs font-bold text-gray-400 group-hover:text-white">Small</div>
                        <div class="text-xs font-mono text-cyan-400">+150ml</div>
                    </button>
                    <button class="add-btn bg-gray-800 p-4 rounded-2xl flex flex-col items-center gap-2 group" onclick="addWater(250)">
                        <div class="text-2xl group-hover:scale-110 transition-transform">🥤</div>
                        <div class="text-xs font-bold text-gray-400 group-hover:text-white">Cup</div>
                        <div class="text-xs font-mono text-cyan-400">+250ml</div>
                    </button>
                    <button class="add-btn bg-gray-800 p-4 rounded-2xl flex flex-col items-center gap-2 group" onclick="addWater(500)">
                        <div class="text-2xl group-hover:scale-110 transition-transform">💧</div>
                        <div class="text-xs font-bold text-gray-400 group-hover:text-white">Bottle</div>
                        <div class="text-xs font-mono text-cyan-400">+500ml</div>
                    </button>
                </div>

                <div class="flex gap-2">
                    <input type="number" id="custom-amount" class="flex-grow bg-gray-800 border border-gray-600 rounded-xl px-4 text-white focus:outline-none focus:border-cyan-500" placeholder="Custom amount (ml)">
                    <button class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition-colors" onclick="addCustom()">Add</button>
                </div>

                <div class="flex-grow bg-gray-900 rounded-xl border border-gray-700 flex flex-col overflow-hidden relative">
                    <div class="p-3 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase">Today's Log</span>
                        <button onclick="resetDay()" class="text-xs text-red-400 hover:text-red-300 underline">Reset Day</button>
                    </div>
                    
                    <div id="log-list" class="flex-grow overflow-y-auto custom-scrollbar p-2 space-y-1">
                        <div id="empty-msg" class="h-full flex flex-col items-center justify-center text-gray-600 text-sm">
                            <span>No water logged yet today.</span>
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
        // --- DOM Elements ---
        const liquid = document.getElementById('liquid');
        const dispPercent = document.getElementById('display-percent');
        const dispCurrent = document.getElementById('display-current');
        const dispGoal = document.getElementById('display-goal');
        const goalInput = document.getElementById('goal-input');
        const customInput = document.getElementById('custom-amount');
        const logList = document.getElementById('log-list');
        const emptyMsg = document.getElementById('empty-msg');
        const tank = document.getElementById('tank');

        // --- STATE ---
        let appState = {
            date: new Date().toLocaleDateString(),
            current: 0,
            goal: 2500,
            history: []
        };

        // --- INIT ---
        function init() {
            // Load from LocalStorage
            const saved = localStorage.getItem('dibber-water-tracker');
            if (saved) {
                const parsed = JSON.parse(saved);
                // Check date reset
                if (parsed.date === new Date().toLocaleDateString()) {
                    appState = parsed;
                } else {
                    // New day, keep goal but reset current
                    appState.goal = parsed.goal;
                    // Reset others
                }
            }
            
            // Set Inputs
            goalInput.value = appState.goal;
            updateUI();
            createBubbles();
        }

        // --- CORE LOGIC ---

        function addWater(amount) {
            appState.current += amount;
            
            // Add Log Entry
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            appState.history.unshift({
                id: Date.now(),
                amount: amount,
                time: timeStr
            });

            saveAndRender();
            triggerBubbleBurst();
        }

        function addCustom() {
            const val = parseInt(customInput.value);
            if (val > 0) {
                addWater(val);
                customInput.value = '';
            }
        }

        function removeEntry(id) {
            const entry = appState.history.find(e => e.id === id);
            if (entry) {
                appState.current -= entry.amount;
                if(appState.current < 0) appState.current = 0;
                
                appState.history = appState.history.filter(e => e.id !== id);
                saveAndRender();
            }
        }

        function resetDay() {
            if(confirm("Clear today's history?")) {
                appState.current = 0;
                appState.history = [];
                appState.date = new Date().toLocaleDateString();
                saveAndRender();
            }
        }

        function updateUI() {
            // Stats
            dispCurrent.textContent = appState.current;
            dispGoal.textContent = appState.goal;
            
            let percent = Math.round((appState.current / appState.goal) * 100);
            if (percent > 100) percent = 100; // Cap visual fill, but allow number higher?
            // Actually, keep visual capped at 100%
            liquid.style.height = `${percent}%`;
            
            // Number can go over 100%
            const realPercent = Math.round((appState.current / appState.goal) * 100);
            dispPercent.textContent = realPercent;

            // Log
            renderLog();
        }

        function renderLog() {
            if (appState.history.length === 0) {
                logList.innerHTML = '';
                logList.appendChild(emptyMsg);
                emptyMsg.style.display = 'flex';
                return;
            }
            
            emptyMsg.style.display = 'none';
            logList.innerHTML = ''; // Clear (inefficient re-render but fine for small lists)

            appState.history.forEach(item => {
                const div = document.createElement('div');
                div.className = "flex justify-between items-center bg-gray-800 p-3 rounded-lg border border-gray-700 animate-fade-in";
                div.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 font-mono">${item.time}</span>
                        <span class="text-sm font-bold text-cyan-400">+${item.amount}ml</span>
                    </div>
                    <button onclick="removeEntry(${item.id})" class="text-gray-600 hover:text-red-400 px-2 transition-colors">×</button>
                `;
                logList.appendChild(div);
            });
        }

        function saveAndRender() {
            localStorage.setItem('dibber-water-tracker', JSON.stringify(appState));
            updateUI();
        }

        // --- VISUAL FX ---

        function createBubbles() {
            // Create a few persistent bubbles
            for(let i=0; i<5; i++) {
                const b = document.createElement('div');
                b.className = 'bubble';
                b.style.left = Math.random() * 80 + 10 + '%';
                b.style.width = Math.random() * 10 + 5 + 'px';
                b.style.height = b.style.width;
                b.style.animationDuration = Math.random() * 3 + 3 + 's';
                b.style.animationDelay = Math.random() * 2 + 's';
                liquid.appendChild(b);
            }
        }

        function triggerBubbleBurst() {
            // Add a burst of bubbles on click
            for(let i=0; i<8; i++) {
                const b = document.createElement('div');
                b.className = 'bubble';
                b.style.left = Math.random() * 80 + 10 + '%';
                b.style.width = Math.random() * 8 + 4 + 'px';
                b.style.height = b.style.width;
                b.style.animationDuration = '1.5s'; // Fast
                b.style.bottom = '0px';
                liquid.appendChild(b);
                setTimeout(() => b.remove(), 1500);
            }
        }

        // --- LISTENERS ---

        goalInput.addEventListener('change', (e) => {
            const val = parseInt(e.target.value);
            if (val > 500) {
                appState.goal = val;
                saveAndRender();
            }
        });

        customInput.addEventListener('keydown', (e) => {
            if(e.key === 'Enter') addCustom();
        });

        // Init
        init();

    </script>
</body>
</html>