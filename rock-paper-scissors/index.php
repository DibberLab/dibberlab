<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* --- BATTLE HANDS --- */
        .hand {
            font-size: 8rem;
            transition: transform 0.1s;
            filter: drop-shadow(0 10px 10px rgba(0,0,0,0.5));
            user-select: none;
        }

        /* Computer hand mirrored to face player */
        #cpu-hand {
            transform: scaleX(-1) rotateY(0deg); 
        }

        /* Shake Animation (The "1, 2, 3, Shoot" motion) */
        .shaking #player-hand {
            animation: shakePlayer 1.5s ease-in-out;
            transform-origin: left center;
        }
        .shaking #cpu-hand {
            animation: shakeCpu 1.5s ease-in-out;
            transform-origin: right center;
        }

        @keyframes shakePlayer {
            0% { transform: rotate(0deg) translateY(0); }
            20% { transform: rotate(-20deg) translateY(-20px); }
            40% { transform: rotate(0deg) translateY(0); }
            60% { transform: rotate(-20deg) translateY(-20px); }
            80% { transform: rotate(0deg) translateY(0); }
            90% { transform: rotate(-20deg) translateY(-20px); }
            100% { transform: rotate(0deg) translateY(0); }
        }

        @keyframes shakeCpu {
            0% { transform: scaleX(-1) rotate(0deg) translateY(0); }
            20% { transform: scaleX(-1) rotate(-20deg) translateY(-20px); }
            40% { transform: scaleX(-1) rotate(0deg) translateY(0); }
            60% { transform: scaleX(-1) rotate(-20deg) translateY(-20px); }
            80% { transform: scaleX(-1) rotate(0deg) translateY(0); }
            90% { transform: scaleX(-1) rotate(-20deg) translateY(-20px); }
            100% { transform: scaleX(-1) rotate(0deg) translateY(0); }
        }

        /* Result States */
        .winner { filter: drop-shadow(0 0 30px #10b981); transform: scale(1.1); transition: all 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28); }
        .loser { filter: grayscale(1) opacity(0.5); transform: scale(0.9); transition: all 0.3s; }
        
        /* Selection Buttons */
        .weapon-btn {
            transition: all 0.2s;
            border-bottom: 4px solid #374151;
        }
        .weapon-btn:hover:not(:disabled) { transform: translateY(-4px); }
        .weapon-btn:active:not(:disabled) { transform: translateY(0); border-bottom-width: 0; margin-top: 4px; }
        .weapon-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* Neon Glows for Buttons */
        .btn-rock:hover { border-color: #ef4444; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }
        .btn-paper:hover { border-color: #3b82f6; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); }
        .btn-scissors:hover { border-color: #f59e0b; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); }

    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-2xl mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Rock Paper Scissors</h1>
                <p class="text-gray-400 text-sm">Best of luck against the machine.</p>
            </div>

            <div class="w-full grid grid-cols-3 gap-4 mb-10">
                <div class="bg-gray-800 rounded-xl p-4 text-center border-b-4 border-emerald-500">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">You</div>
                    <div class="text-3xl font-black text-white" id="score-player">0</div>
                </div>
                <div class="bg-gray-800 rounded-xl p-4 text-center border-b-4 border-gray-600">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Tie</div>
                    <div class="text-3xl font-black text-white" id="score-tie">0</div>
                </div>
                <div class="bg-gray-800 rounded-xl p-4 text-center border-b-4 border-red-500">
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">CPU</div>
                    <div class="text-3xl font-black text-white" id="score-cpu">0</div>
                </div>
            </div>

            <div class="relative w-full h-64 bg-gray-800 rounded-2xl border-4 border-gray-700 flex items-center justify-between px-4 md:px-16 overflow-hidden mb-8" id="arena">
                
                <div id="result-text" class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none opacity-0 transition-opacity duration-300">
                    <h2 class="text-5xl font-black text-white drop-shadow-2xl uppercase italic transform -rotate-6" id="result-label">WIN!</h2>
                </div>

                <div class="hand z-10" id="player-hand">✊</div>
                <div class="hand z-10" id="cpu-hand">✊</div>

            </div>

            <div class="grid grid-cols-3 gap-4 w-full mb-8">
                <button class="weapon-btn btn-rock bg-gray-800 p-6 rounded-2xl flex flex-col items-center" onclick="playGame('rock')" id="btn-rock">
                    <span class="text-4xl mb-2">✊</span>
                    <span class="text-xs font-bold text-gray-400 uppercase">Rock</span>
                </button>
                <button class="weapon-btn btn-paper bg-gray-800 p-6 rounded-2xl flex flex-col items-center" onclick="playGame('paper')" id="btn-paper">
                    <span class="text-4xl mb-2">✋</span>
                    <span class="text-xs font-bold text-gray-400 uppercase">Paper</span>
                </button>
                <button class="weapon-btn btn-scissors bg-gray-800 p-6 rounded-2xl flex flex-col items-center" onclick="playGame('scissors')" id="btn-scissors">
                    <span class="text-4xl mb-2">✌️</span>
                    <span class="text-xs font-bold text-gray-400 uppercase">Scissors</span>
                </button>
            </div>

            <button onclick="resetScores()" class="text-xs font-bold text-gray-500 hover:text-red-400 underline">Reset Scoreboard</button>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const playerHand = document.getElementById('player-hand');
        const cpuHand = document.getElementById('cpu-hand');
        const arena = document.getElementById('arena');
        const resultText = document.getElementById('result-text');
        const resultLabel = document.getElementById('result-label');
        
        const scorePlayerEl = document.getElementById('score-player');
        const scoreTieEl = document.getElementById('score-tie');
        const scoreCpuEl = document.getElementById('score-cpu');
        
        const buttons = document.querySelectorAll('.weapon-btn');

        // Config
        const CHOICES = ['rock', 'paper', 'scissors'];
        const ICONS = {
            rock: '✊',
            paper: '✋',
            scissors: '✌️'
        };

        // State
        let scores = { player: 0, tie: 0, cpu: 0 };
        let isPlaying = false;

        // --- CORE LOGIC ---

        function playGame(userChoice) {
            if (isPlaying) return;
            isPlaying = true;

            // 1. Reset Visuals
            resultText.classList.add('opacity-0');
            playerHand.classList.remove('winner', 'loser');
            cpuHand.classList.remove('winner', 'loser');
            
            // Set hands to Rock for the shake animation
            playerHand.textContent = ICONS['rock'];
            cpuHand.textContent = ICONS['rock'];

            // Disable buttons
            buttons.forEach(b => b.disabled = true);

            // 2. Start Animation (1.5s duration)
            arena.classList.add('shaking');

            // 3. Determine Winner after animation
            setTimeout(() => {
                const cpuChoice = CHOICES[Math.floor(Math.random() * 3)];
                resolveRound(userChoice, cpuChoice);
            }, 1500);
        }

        function resolveRound(user, cpu) {
            // Stop shake
            arena.classList.remove('shaking');

            // Update Icons
            playerHand.textContent = ICONS[user];
            cpuHand.textContent = ICONS[cpu];

            // Logic
            if (user === cpu) {
                // TIE
                scores.tie++;
                showResult("DRAW!", "text-gray-300", null);
            } else if (
                (user === 'rock' && cpu === 'scissors') ||
                (user === 'paper' && cpu === 'rock') ||
                (user === 'scissors' && cpu === 'paper')
            ) {
                // WIN
                scores.player++;
                playerHand.classList.add('winner');
                cpuHand.classList.add('loser');
                showResult("YOU WIN!", "text-emerald-400", 'player');
            } else {
                // LOSE
                scores.cpu++;
                playerHand.classList.add('loser');
                cpuHand.classList.add('winner');
                showResult("CPU WINS!", "text-red-500", 'cpu');
            }

            updateScoreboard();

            // Re-enable
            isPlaying = false;
            buttons.forEach(b => b.disabled = false);
        }

        function showResult(text, colorClass, winner) {
            resultLabel.textContent = text;
            resultLabel.className = `text-5xl font-black drop-shadow-2xl uppercase italic transform -rotate-6 ${colorClass}`;
            resultText.classList.remove('opacity-0');
        }

        function updateScoreboard() {
            scorePlayerEl.textContent = scores.player;
            scoreTieEl.textContent = scores.tie;
            scoreCpuEl.textContent = scores.cpu;
        }

        function resetScores() {
            if(confirm("Reset scoreboard?")) {
                scores = { player: 0, tie: 0, cpu: 0 };
                updateScoreboard();
                
                // Visual reset
                resultText.classList.add('opacity-0');
                playerHand.classList.remove('winner', 'loser');
                cpuHand.classList.remove('winner', 'loser');
                playerHand.textContent = ICONS['rock'];
                cpuHand.textContent = ICONS['rock'];
            }
        }

    </script>
</body>
</html>