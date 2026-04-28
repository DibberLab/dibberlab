<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delay Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Slider */
        input[type=range] {
            -webkit-appearance: none; 
            background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }

        /* Result Cards */
        .result-card {
            transition: all 0.2s;
            cursor: pointer;
            user-select: none;
        }
        .result-card:hover {
            transform: translateY(-2px);
            background-color: #374151; /* gray-700 */
            border-color: #f59e0b;
        }
        .result-card:active {
            transform: scale(0.98);
        }

        /* Tap Button Active State */
        #tap-btn:active {
            transform: scale(0.95);
            background-color: #f59e0b;
            color: #111827;
        }
        
        /* Toast Notification */
        #toast {
            opacity: 0;
            transition: opacity 0.3s;
        }
        #toast.show {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Delay Calculator</h1>
            <p class="text-center text-gray-400 mb-8">Convert BPM to Milliseconds & Hertz.</p>

            <div class="bg-gray-900 rounded-xl p-6 mb-8 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-400 text-sm font-bold uppercase tracking-wider">Tempo</div>
                    
                    <button id="tap-btn" class="px-4 py-1 rounded bg-gray-700 hover:bg-gray-600 border border-gray-600 text-xs font-bold transition-all">
                        TAP BPM
                    </button>
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <button id="bpm-minus" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-gray-600 font-bold text-xl">-</button>
                    <div class="flex-grow text-center">
                        <input type="number" id="bpm-input" value="120" min="20" max="300" 
                            class="bg-transparent text-5xl font-mono font-bold text-white text-center w-full focus:outline-none focus:text-amber-400">
                        <span class="text-sm text-gray-500 font-bold">BPM</span>
                    </div>
                    <button id="bpm-plus" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-gray-600 font-bold text-xl">+</button>
                </div>

                <input type="range" id="bpm-slider" min="40" max="220" value="120" class="w-full">
            </div>

            <p class="text-xs text-gray-500 text-center mb-4">Click any value to copy</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3" id="results-grid">
                </div>

        </div>
    </main>

    <div id="toast" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg font-bold text-sm pointer-events-none">
        Copied!
    </div>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const bpmInput = document.getElementById('bpm-input');
        const bpmSlider = document.getElementById('bpm-slider');
        const resultsGrid = document.getElementById('results-grid');
        const tapBtn = document.getElementById('tap-btn');
        const toast = document.getElementById('toast');

        // Note Types Configuration
        // Multiplier is relative to a Quarter note (1 beat)
        const noteTypes = [
            { label: "Whole",   icon: "1/1",  multiplier: 4.0 },
            { label: "Half",    icon: "1/2",  multiplier: 2.0 },
            { label: "Quarter", icon: "1/4",  multiplier: 1.0 },
            { label: "Eighth",  icon: "1/8",  multiplier: 0.5 },
            { label: "1/8 Dot", icon: "1/8.", multiplier: 0.75 },
            { label: "1/8 Trip",icon: "1/8T", multiplier: 0.3333 },
            { label: "16th",    icon: "1/16", multiplier: 0.25 },
            { label: "16th Dot",icon: "1/16.",multiplier: 0.375 },
        ];

        // --- CALCULATION ---
        function calculate() {
            let bpm = parseFloat(bpmInput.value);
            if (!bpm || bpm <= 0) bpm = 120;

            // Quarter note in ms = 60,000 / BPM
            const quarterNoteMs = 60000 / bpm;

            resultsGrid.innerHTML = '';

            noteTypes.forEach(type => {
                const ms = quarterNoteMs * type.multiplier;
                const hz = 1000 / ms;

                const card = document.createElement('div');
                card.className = "result-card bg-gray-800 border border-gray-600 rounded-lg p-3 text-center flex flex-col justify-between";
                card.onclick = () => copyToClipboard(ms.toFixed(1));
                
                card.innerHTML = `
                    <div class="text-xs text-gray-400 font-bold uppercase mb-1">${type.label}</div>
                    <div class="text-2xl font-mono font-bold text-amber-400 mb-1">${ms.toFixed(0)}<span class="text-sm text-amber-600">ms</span></div>
                    <div class="text-xs text-gray-500">${hz.toFixed(2)} Hz</div>
                `;
                resultsGrid.appendChild(card);
            });
        }

        // --- COPY FUNCTION ---
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 1500);
            });
        }

        // --- INPUT LISTENERS ---
        function updateBpm(val) {
            bpmInput.value = val;
            bpmSlider.value = val;
            calculate();
        }

        bpmInput.addEventListener('input', (e) => {
            bpmSlider.value = e.target.value;
            calculate();
        });

        bpmSlider.addEventListener('input', (e) => {
            bpmInput.value = e.target.value;
            calculate();
        });

        document.getElementById('bpm-minus').addEventListener('click', () => updateBpm(parseInt(bpmInput.value) - 1));
        document.getElementById('bpm-plus').addEventListener('click', () => updateBpm(parseInt(bpmInput.value) + 1));

        // --- TAP TEMPO LOGIC ---
        let tapTimes = [];
        tapBtn.addEventListener('click', () => {
            const now = Date.now();
            
            // Reset if paused too long (2s)
            if (tapTimes.length > 0 && now - tapTimes[tapTimes.length - 1] > 2000) {
                tapTimes = [];
            }

            tapTimes.push(now);
            if (tapTimes.length > 4) tapTimes.shift(); // Keep last 4

            if (tapTimes.length > 1) {
                // Calculate intervals
                let intervals = [];
                for (let i = 0; i < tapTimes.length - 1; i++) {
                    intervals.push(tapTimes[i+1] - tapTimes[i]);
                }
                const avg = intervals.reduce((a, b) => a + b) / intervals.length;
                const bpm = Math.round(60000 / avg);
                updateBpm(bpm);
            }
        });

        // Init
        calculate();

    </script>
</body>
</html>