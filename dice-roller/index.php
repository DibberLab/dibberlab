<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dice Roller + Patterns | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Dice Tray */
        #dice-tray {
            background-image: radial-gradient(circle at center, #374151 0%, #1f2937 100%);
            box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
            min-height: 350px;
            transition: border-color 0.2s;
        }

        /* Die SVG Colors */
        .die-svg { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); transition: transform 0.2s; }
        .die-wrapper:hover .die-svg { transform: scale(1.05); }
        
        .fill-d4 { fill: #f59e0b; }   /* Amber */
        .fill-d6 { fill: #3b82f6; }   /* Blue */
        .fill-d8 { fill: #8b5cf6; }   /* Purple */
        .fill-d10 { fill: #ec4899; }  /* Pink */
        .fill-d12 { fill: #ef4444; }  /* Red */
        .fill-d20 { fill: #10b981; }  /* Emerald */

        /* Animations */
        .shake-anim { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0) rotate(-1deg); }
            20%, 80% { transform: translate3d(2px, 0, 0) rotate(1deg); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0) rotate(-2deg); }
            40%, 60% { transform: translate3d(4px, 0, 0) rotate(2deg); }
        }

        /* Critical Effects (Individual Dice) */
        .crit-high { animation: pulse-green 1s infinite; filter: drop-shadow(0 0 8px #10b981); }
        .crit-low { animation: pulse-red 1s infinite; filter: drop-shadow(0 0 8px #ef4444); }

        @keyframes pulse-green { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        @keyframes pulse-red { 0% { transform: rotate(0deg); } 25% { transform: rotate(-5deg); } 75% { transform: rotate(5deg); } }

        /* Selector Buttons */
        .type-btn {
            transition: all 0.2s;
            border: 2px solid #374151;
            opacity: 0.6;
        }
        .type-btn:hover { opacity: 1; border-color: #4b5563; background: #374151; }
        .type-btn.active {
            opacity: 1;
            border-color: #f59e0b;
            background: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        /* Pattern Badge Animation */
        .pattern-badge {
            animation: popIn 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                    
                    <label class="text-xs font-bold text-gray-500 uppercase mb-3 block">Select Die Type</label>
                    <div class="grid grid-cols-3 gap-2 mb-6">
                        <button class="type-btn rounded-lg py-3 flex flex-col items-center" onclick="selectDie(4)" id="btn-d4">
                            <span class="text-xs font-bold mb-1">D4</span>
                            <span class="text-lg">△</span>
                        </button>
                        <button class="type-btn active rounded-lg py-3 flex flex-col items-center" onclick="selectDie(6)" id="btn-d6">
                            <span class="text-xs font-bold mb-1">D6</span>
                            <span class="text-lg">□</span>
                        </button>
                        <button class="type-btn rounded-lg py-3 flex flex-col items-center" onclick="selectDie(8)" id="btn-d8">
                            <span class="text-xs font-bold mb-1">D8</span>
                            <span class="text-lg">◇</span>
                        </button>
                        <button class="type-btn rounded-lg py-3 flex flex-col items-center" onclick="selectDie(10)" id="btn-d10">
                            <span class="text-xs font-bold mb-1">D10</span>
                            <span class="text-lg">▽</span>
                        </button>
                        <button class="type-btn rounded-lg py-3 flex flex-col items-center" onclick="selectDie(12)" id="btn-d12">
                            <span class="text-xs font-bold mb-1">D12</span>
                            <span class="text-lg">⬡</span>
                        </button>
                        <button class="type-btn rounded-lg py-3 flex flex-col items-center" onclick="selectDie(20)" id="btn-d20">
                            <span class="text-xs font-bold mb-1">D20</span>
                            <span class="text-lg">⬢</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Quantity (Max 10)</label>
                            <div class="flex items-center bg-gray-900 rounded-lg p-1 border border-gray-600">
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded font-bold" onclick="updateCount(-1)">-</button>
                                <input type="number" id="count-input" class="w-full bg-transparent text-center text-white font-bold focus:outline-none" value="5" min="1" max="10" readonly>
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded font-bold" onclick="updateCount(1)">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Modifier</label>
                            <div class="flex items-center bg-gray-900 rounded-lg p-1 border border-gray-600">
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded font-bold" onclick="updateMod(-1)">-</button>
                                <input type="number" id="mod-input" class="w-full bg-transparent text-center text-white font-bold focus:outline-none" value="0" readonly>
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded font-bold" onclick="updateMod(1)">+</button>
                            </div>
                        </div>
                    </div>

                    <button id="roll-btn" class="w-full py-4 rounded-xl font-black text-lg bg-amber-500 hover:bg-amber-400 text-gray-900 shadow-lg transition-transform hover:-translate-y-1 flex items-center justify-center gap-2" onclick="rollAll()">
                        <span>🎲</span> ROLL DICE
                    </button>

                </div>

                <div class="bg-gray-800 rounded-2xl border border-gray-700 flex flex-col h-64 overflow-hidden shadow-lg">
                    <div class="p-3 bg-gray-900 border-b border-gray-700 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">History</span>
                        <button onclick="clearHistory()" class="text-xs text-red-400 hover:text-red-300">Clear</button>
                    </div>
                    <div id="history-list" class="flex-grow overflow-y-auto custom-scrollbar p-2 space-y-2">
                        <div id="empty-history" class="h-full flex flex-col items-center justify-center text-gray-600 opacity-50 text-xs uppercase font-bold">No rolls yet</div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-8">
                <div id="dice-tray" class="h-full rounded-2xl border-4 border-gray-700 relative overflow-hidden flex flex-col">
                    
                    <div class="absolute top-4 right-4 z-20 text-right pointer-events-none">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Total</span>
                        <span id="grand-total" class="text-6xl font-black text-white drop-shadow-lg mono-font">0</span>
                        
                        <div id="pattern-display" class="mt-2 min-h-[2rem]">
                            </div>
                        
                        <div id="math-string" class="text-xs font-mono text-gray-500 mt-1 opacity-0 transition-opacity">Details</div>
                    </div>

                    <div id="dice-grid" class="flex-grow p-8 flex flex-wrap items-center justify-center gap-6 overflow-y-auto custom-scrollbar z-10 content-center">
                        <div class="text-gray-500 font-bold text-2xl uppercase tracking-widest opacity-20 select-none">Ready to Roll</div>
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
        const diceGrid = document.getElementById('dice-grid');
        const countInput = document.getElementById('count-input');
        const modInput = document.getElementById('mod-input');
        const grandTotalEl = document.getElementById('grand-total');
        const mathStringEl = document.getElementById('math-string');
        const patternDisplay = document.getElementById('pattern-display');
        const historyList = document.getElementById('history-list');
        const emptyHistory = document.getElementById('empty-history');
        const typeBtns = document.querySelectorAll('.type-btn');

        // State
        let currentSides = 6;
        let audioContext = null;

        // SVG Shapes
        const SHAPES = {
            4:  { path: `<polygon points="50,10 90,90 10,90" class="fill-d4" stroke="white" stroke-width="3" stroke-linejoin="round"/>`, y: 65 },
            6:  { path: `<rect x="15" y="15" width="70" height="70" rx="10" class="fill-d6" stroke="white" stroke-width="3"/>`, y: 55 },
            8:  { path: `<polygon points="50,5 95,50 50,95 5,50" class="fill-d8" stroke="white" stroke-width="3" stroke-linejoin="round"/>`, y: 55 },
            10: { path: `<path d="M50 5 L90 35 L50 95 L10 35 Z" class="fill-d10" stroke="white" stroke-width="3" stroke-linejoin="round"/>`, y: 55 },
            12: { path: `<polygon points="50,5 90,30 90,70 50,95 10,70 10,30" class="fill-d12" stroke="white" stroke-width="3" stroke-linejoin="round"/>`, y: 55 },
            20: { path: `<polygon points="50,5 93,28 93,73 50,95 7,73 7,28" class="fill-d20" stroke="white" stroke-width="3" stroke-linejoin="round"/>`, y: 55 }
        };

        // --- AUDIO ---
        function playRollSound(count) {
            if (!audioContext) audioContext = new (window.AudioContext || window.webkitAudioContext)();
            if (audioContext.state === 'suspended') audioContext.resume();

            const t = audioContext.currentTime;
            const duration = 0.3 + (count * 0.05);

            const osc = audioContext.createOscillator();
            const gain = audioContext.createGain();
            
            const bufferSize = audioContext.sampleRate * duration;
            const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) data[i] = Math.random() * 2 - 1;

            const noise = audioContext.createBufferSource();
            noise.buffer = buffer;
            const noiseGain = audioContext.createGain();
            
            const filter = audioContext.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.value = 800;

            noise.connect(filter);
            filter.connect(noiseGain);
            noiseGain.connect(audioContext.destination);

            noiseGain.gain.setValueAtTime(0.5, t);
            noiseGain.gain.exponentialRampToValueAtTime(0.01, t + duration);
            noise.start();
        }

        // --- PATTERN RECOGNITION (Yahtzee Style) ---
        function analyzePattern(rolls) {
            if (rolls.length < 2) return null;

            const counts = {};
            rolls.forEach(x => { counts[x] = (counts[x] || 0) + 1; });
            const freq = Object.values(counts).sort((a,b) => b - a);

            // N of a Kind
            if (freq[0] >= 5) return { text: `${freq[0]} of a Kind!`, color: "bg-purple-600" };
            if (freq[0] === 4) return { text: "4 of a Kind", color: "bg-blue-600" };
            if (freq[0] === 3 && freq[1] >= 2) return { text: "Full House", color: "bg-emerald-600" };
            if (freq[0] === 3) return { text: "3 of a Kind", color: "bg-amber-600" };
            if (freq[0] === 2 && freq[1] === 2) return { text: "Two Pairs", color: "bg-gray-600" };
            if (freq[0] === 2) return { text: "Pair", color: "bg-gray-700" };

            // Straights (4+ dice)
            if (rolls.length >= 4) {
                const unique = [...new Set(rolls)].sort((a,b) => a-b);
                let streak = 1;
                let maxStreak = 1;
                for (let i = 0; i < unique.length - 1; i++) {
                    if (unique[i+1] === unique[i] + 1) {
                        streak++;
                        maxStreak = Math.max(maxStreak, streak);
                    } else {
                        streak = 1;
                    }
                }

                if (maxStreak >= 5) return { text: "Large Straight", color: "bg-pink-600" };
                if (maxStreak >= 4) return { text: "Small Straight", color: "bg-indigo-600" };
            }

            return null;
        }

        // --- CORE LOGIC ---

        function selectDie(sides) {
            currentSides = sides;
            typeBtns.forEach(btn => btn.classList.remove('active'));
            document.getElementById(`btn-d${sides}`).classList.add('active');
        }

        function updateCount(delta) {
            let val = parseInt(countInput.value) + delta;
            if (val < 1) val = 1;
            if (val > 10) val = 10;
            countInput.value = val;
        }

        function updateMod(delta) {
            modInput.value = parseInt(modInput.value) + delta;
        }

        function rollAll() {
            const count = parseInt(countInput.value);
            const mod = parseInt(modInput.value);
            
            playRollSound(count);
            diceGrid.innerHTML = ''; 
            patternDisplay.innerHTML = ''; // Clear old badge

            let diceTotal = 0;
            let results = [];

            // Generate Dice
            for(let i = 0; i < count; i++) {
                const roll = Math.floor(Math.random() * currentSides) + 1;
                diceTotal += roll;
                results.push(roll);

                setTimeout(() => {
                    const dieEl = createDieElement(roll, currentSides);
                    diceGrid.appendChild(dieEl);
                }, i * 50);
            }

            const grandTotal = diceTotal + mod;

            // Update UI after delay
            setTimeout(() => {
                grandTotalEl.textContent = grandTotal;
                
                // Pattern Check
                const pattern = analyzePattern(results);
                if (pattern) {
                    patternDisplay.innerHTML = `<span class="pattern-badge inline-block px-3 py-1 rounded-full text-xs font-bold text-white uppercase tracking-wider ${pattern.color} shadow-lg">${pattern.text}</span>`;
                }

                // Math String
                let mathStr = `[${results.join(', ')}]`;
                if (mod !== 0) mathStr += ` ${mod >= 0 ? '+' : '-'} ${Math.abs(mod)}`;
                mathStringEl.textContent = mathStr;
                mathStringEl.classList.remove('opacity-0');

                addToHistory(count, currentSides, results, mod, grandTotal, pattern);
            }, count * 50 + 200);
        }

        function createDieElement(val, sides) {
            const wrapper = document.createElement('div');
            wrapper.className = "die-wrapper relative w-24 h-24 flex items-center justify-center shake-anim";
            
            let critClass = "";
            if (sides === 20) {
                if (val === 20) critClass = "crit-high";
                if (val === 1) critClass = "crit-low";
            }

            const textY = SHAPES[sides].y;

            wrapper.innerHTML = `
                <svg viewBox="0 0 100 100" class="die-svg absolute inset-0 w-full h-full ${critClass}">
                    ${SHAPES[sides].path}
                </svg>
                <span class="relative z-10 font-black text-2xl text-white drop-shadow-md mono-font ${critClass}" style="transform: translateY(${textY - 50}px)">${val}</span>
            `;
            return wrapper;
        }

        function addToHistory(count, sides, results, mod, total, pattern) {
            emptyHistory.style.display = 'none';
            
            const div = document.createElement('div');
            div.className = "bg-gray-900 p-3 rounded-lg border border-gray-700 flex flex-col gap-1 animate-slide-in";
            
            const modText = mod !== 0 ? (mod > 0 ? `+${mod}` : mod) : '';
            const patternHTML = pattern ? `<span class="text-[10px] text-emerald-400 font-bold ml-2">(${pattern.text})</span>` : '';
            
            div.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400">${count}d${sides} ${modText}</span>
                    <span class="text-lg font-bold text-emerald-400 mono-font">${total}</span>
                </div>
                <div class="text-[10px] text-gray-500 font-mono break-words leading-tight">
                    [${results.join(', ')}] ${patternHTML}
                </div>
            `;
            
            historyList.prepend(div);
        }

        function clearHistory() {
            historyList.innerHTML = '';
            historyList.appendChild(emptyHistory);
            emptyHistory.style.display = 'flex';
        }

    </script>
</body>
</html>