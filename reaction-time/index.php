<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reaction Time Test | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; user-select: none; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Game Area */
        #game-area {
            transition: background-color 0.1s ease; /* Fast transition, but not instant to avoid jarring flicker */
            cursor: pointer;
        }
        
        /* Instant color change for the stimulus (Green) to ensure accuracy */
        #game-area.state-ready {
            transition: none; 
        }

        /* States Colors */
        .state-idle { background-color: #1f2937; /* Gray-800 */ }
        .state-waiting { background-color: #ef4444; /* Red-500 */ }
        .state-ready { background-color: #10b981; /* Emerald-500 */ }
        .state-early { background-color: #f59e0b; /* Amber-500 */ }
        .state-result { background-color: #3b82f6; /* Blue-500 */ }

        /* Icon Animation */
        @keyframes pulse-icon {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        .pulse-anim { animation: pulse-icon 2s infinite; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow flex flex-col relative">
        
        <div id="game-area" class="flex-grow flex flex-col items-center justify-center state-idle p-4 text-center relative z-10" onmousedown="handleAction()">
            
            <div id="status-icon" class="text-6xl md:text-8xl mb-6 pulse-anim">⚡</div>
            
            <h1 id="main-text" class="text-4xl md:text-6xl font-black text-white mb-4 drop-shadow-lg">Reaction Test</h1>
            
            <p id="sub-text" class="text-xl md:text-2xl text-white/80 font-medium">Click anywhere to start</p>

            <div id="result-details" class="hidden mt-4">
                <div class="text-8xl font-black mono-font text-white drop-shadow-2xl mb-2"><span id="time-val">0</span><span class="text-4xl">ms</span></div>
                <div class="text-white/90 text-lg font-bold bg-white/20 px-4 py-1 rounded-full inline-block backdrop-blur-md" id="rank-badge">Average</div>
                <p class="mt-8 text-white/60 text-sm animate-bounce">Click to try again</p>
            </div>

        </div>

        <div class="bg-gray-800 border-t border-gray-700 p-4 z-20">
            <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                
                <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Average</div>
                    <div class="text-xl font-bold text-white mono-font" id="stat-avg">--</div>
                </div>

                <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Best</div>
                    <div class="text-xl font-bold text-emerald-400 mono-font" id="stat-best">--</div>
                </div>

                <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Tries</div>
                    <div class="text-xl font-bold text-blue-400 mono-font" id="stat-tries">0</div>
                </div>

                <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Reset</div>
                    <button onclick="resetStats(event)" class="text-xs text-red-400 hover:text-white font-bold underline mt-1">Clear History</button>
                </div>

            </div>
        </div>

    </main>

    <script>
        // DOM Elements
        const gameArea = document.getElementById('game-area');
        const mainText = document.getElementById('main-text');
        const subText = document.getElementById('sub-text');
        const statusIcon = document.getElementById('status-icon');
        const resultDetails = document.getElementById('result-details');
        const timeVal = document.getElementById('time-val');
        const rankBadge = document.getElementById('rank-badge');

        const statAvg = document.getElementById('stat-avg');
        const statBest = document.getElementById('stat-best');
        const statTries = document.getElementById('stat-tries');

        // State
        let gameState = 'idle'; // idle, waiting, ready, result, early
        let timeoutId = null;
        let startTime = 0;
        let history = [];

        // --- CORE LOGIC ---

        function handleAction() {
            switch (gameState) {
                case 'idle':
                case 'result':
                case 'early':
                    startGame();
                    break;
                case 'waiting':
                    triggerEarly();
                    break;
                case 'ready':
                    finishGame();
                    break;
            }
        }

        function startGame() {
            gameState = 'waiting';
            updateUI('waiting');
            
            // Random delay between 2s and 5s
            const delay = Math.floor(Math.random() * 3000) + 2000;
            
            timeoutId = setTimeout(() => {
                gameState = 'ready';
                startTime = performance.now();
                updateUI('ready');
            }, delay);
        }

        function triggerEarly() {
            clearTimeout(timeoutId);
            gameState = 'early';
            updateUI('early');
        }

        function finishGame() {
            const endTime = performance.now();
            const reactionTime = Math.round(endTime - startTime);
            
            // Save Data
            history.push(reactionTime);
            updateStats();

            // Show Result
            gameState = 'result';
            updateUI('result', reactionTime);
        }

        // --- UI UPDATER ---

        function updateUI(state, time = 0) {
            // Reset Classes
            gameArea.className = "flex-grow flex flex-col items-center justify-center p-4 text-center relative z-10";
            
            // Hide result overlay by default
            resultDetails.classList.add('hidden');
            mainText.classList.remove('hidden');
            subText.classList.remove('hidden');
            statusIcon.classList.remove('hidden');

            if (state === 'idle') {
                gameArea.classList.add('state-idle');
                statusIcon.textContent = "⚡";
                mainText.textContent = "Reaction Test";
                subText.textContent = "Click anywhere to start";
            } 
            else if (state === 'waiting') {
                gameArea.classList.add('state-waiting');
                statusIcon.textContent = "🛑";
                mainText.textContent = "Wait for Green...";
                subText.textContent = "Don't click yet!";
            }
            else if (state === 'ready') {
                gameArea.classList.add('state-ready');
                statusIcon.textContent = "🚀";
                mainText.textContent = "CLICK!";
                subText.textContent = "";
            }
            else if (state === 'early') {
                gameArea.classList.add('state-early');
                statusIcon.textContent = "⚠️";
                mainText.textContent = "Too Soon!";
                subText.textContent = "Click to try again";
            }
            else if (state === 'result') {
                gameArea.classList.add('state-result');
                
                // Hide standard text, show overlay
                mainText.classList.add('hidden');
                subText.classList.add('hidden');
                statusIcon.classList.add('hidden');
                resultDetails.classList.remove('hidden');

                timeVal.textContent = time;
                rankBadge.textContent = getRank(time);
            }
        }

        function updateStats() {
            if (history.length === 0) return;

            const best = Math.min(...history);
            const avg = Math.round(history.reduce((a, b) => a + b, 0) / history.length);

            statBest.textContent = best + "ms";
            statAvg.textContent = avg + "ms";
            statTries.textContent = history.length;
        }

        function resetStats(e) {
            if(e) e.stopPropagation(); // Prevent clicking the reset button from triggering the game
            history = [];
            statBest.textContent = "--";
            statAvg.textContent = "--";
            statTries.textContent = "0";
            gameState = 'idle';
            updateUI('idle');
        }

        function getRank(ms) {
            if (ms < 150) return "🤖 Cheater / Bot";
            if (ms < 200) return "⚡ Godlike";
            if (ms < 230) return "🏎️ Pro Gamer";
            if (ms < 270) return "👍 Above Average";
            if (ms < 350) return "😐 Average";
            if (ms < 500) return "🐢 Sluggish";
            return "😴 Asleep";
        }

        // --- KEYBOARD SUPPORT ---
        // Allow Spacebar to trigger click
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space') {
                e.preventDefault(); // Stop scrolling
                handleAction();
            }
        });

    </script>
</body>
</html>