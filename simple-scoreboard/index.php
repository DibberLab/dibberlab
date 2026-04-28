<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Scoreboard | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input for Player Names */
        .name-input {
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            text-align: center;
            font-weight: 700;
            color: #d1d5db; /* Gray-300 */
            transition: all 0.2s;
            width: 100%;
        }
        .name-input:focus {
            outline: none;
            border-bottom-color: #f59e0b; /* Amber */
            color: white;
        }

        /* Score Card Animations */
        .player-card {
            transition: transform 0.2s, border-color 0.2s;
        }
        .player-card.leader {
            border-color: #f59e0b;
            background-color: #2b2520; /* Very dark amber tint */
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        /* Crown Icon */
        .crown {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%) scale(0);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
            z-index: 10;
        }
        .leader .crown { transform: translateX(-50%) scale(1); }

        /* Button Press Effect */
        .score-btn:active { transform: scale(0.95); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 3px; }

        /* Add Player Card */
        .add-card {
            border: 2px dashed #374151;
            transition: all 0.2s;
        }
        .add-card:hover {
            border-color: #10b981;
            color: #10b981;
            background: rgba(16, 185, 129, 0.05);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 py-8">
        <div class="w-full max-w-6xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold text-amber-400">Scoreboard</h1>
                    <p class="text-gray-400 text-sm">Track points for any game.</p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="resetScores()" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold rounded-lg border border-gray-700 transition-colors text-sm">
                        Reset Points
                    </button>
                    <button onclick="removeAll()" class="px-4 py-2 bg-gray-800 hover:bg-red-900/30 text-red-400 font-bold rounded-lg border border-gray-700 hover:border-red-800 transition-colors text-sm">
                        Clear All
                    </button>
                </div>
            </div>

            <div id="player-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <button onclick="addPlayer()" class="add-card h-64 rounded-2xl flex flex-col items-center justify-center text-gray-500 cursor-pointer group">
                    <span class="text-5xl mb-2 group-hover:scale-110 transition-transform">+</span>
                    <span class="font-bold uppercase text-xs tracking-widest">Add Player</span>
                </button>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const grid = document.getElementById('player-grid');

        // State
        let players = JSON.parse(localStorage.getItem('dibber-scoreboard')) || [
            { id: 1, name: 'Player 1', score: 0 },
            { id: 2, name: 'Player 2', score: 0 }
        ];

        // --- RENDER LOGIC ---

        function render() {
            // Remove existing player cards (keep the Add Button which is last)
            while (grid.children.length > 1) {
                grid.removeChild(grid.firstChild);
            }

            // Find Leader score
            const maxScore = Math.max(...players.map(p => p.score));
            // Only show leader if score > 0
            const showLeader = maxScore > 0;

            players.forEach(p => {
                const isLeader = showLeader && p.score === maxScore;
                const card = createCard(p, isLeader);
                grid.insertBefore(card, grid.lastElementChild);
            });

            localStorage.setItem('dibber-scoreboard', JSON.stringify(players));
        }

        function createCard(player, isLeader) {
            const div = document.createElement('div');
            div.className = `player-card relative bg-gray-800 rounded-2xl border border-gray-700 p-6 flex flex-col items-center ${isLeader ? 'leader' : ''}`;
            div.id = `p-${player.id}`;

            div.innerHTML = `
                <div class="crown">👑</div>
                
                <button onclick="removePlayer(${player.id})" class="absolute top-3 right-3 text-gray-600 hover:text-red-400 transition-colors">×</button>

                <input type="text" value="${player.name}" class="name-input text-lg mb-4" onchange="updateName(${player.id}, this.value)" placeholder="Name">

                <div class="flex-grow flex items-center justify-center mb-4">
                    <span class="text-6xl font-black text-white mono-font tracking-tighter">${player.score}</span>
                </div>

                <div class="grid grid-cols-4 gap-2 w-full">
                    <button class="score-btn col-span-1 bg-gray-700 hover:bg-red-500/20 text-red-400 font-bold py-3 rounded-lg text-lg" onclick="changeScore(${player.id}, -1)">-</button>
                    <button class="score-btn col-span-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-lg text-2xl shadow-lg" onclick="changeScore(${player.id}, 1)">+</button>
                    <button class="score-btn col-span-1 bg-gray-700 hover:bg-blue-500/20 text-blue-400 font-bold py-3 rounded-lg text-lg" onclick="changeScore(${player.id}, 5)">+5</button>
                </div>
            `;
            return div;
        }

        // --- ACTIONS ---

        function addPlayer() {
            const newId = Date.now();
            players.push({
                id: newId,
                name: `Player ${players.length + 1}`,
                score: 0
            });
            render();
        }

        function removePlayer(id) {
            if(confirm("Remove this player?")) {
                players = players.filter(p => p.id !== id);
                render();
            }
        }

        function updateName(id, newName) {
            const p = players.find(p => p.id === id);
            if (p) {
                p.name = newName;
                render(); // Re-render to ensure state consistency
            }
        }

        function changeScore(id, delta) {
            const p = players.find(p => p.id === id);
            if (p) {
                p.score += delta;
                render();
            }
        }

        function resetScores() {
            if(confirm("Reset all scores to zero?")) {
                players.forEach(p => p.score = 0);
                render();
            }
        }

        function removeAll() {
            if(confirm("Delete all players and start over?")) {
                players = [];
                render();
            }
        }

        // --- INIT ---
        render();

    </script>
</body>
</html>