<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Board Styling */
        .game-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            width: 100%;
            max-width: 400px;
            aspect-ratio: 1;
        }

        .cell {
            background-color: #1f2937; /* Gray-800 */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            user-select: none;
        }

        .cell:hover:not(.taken) {
            background-color: #374151; /* Gray-700 */
            transform: scale(0.98);
        }

        .cell.taken { cursor: default; }

        /* Player Colors */
        .text-x { color: #3b82f6; text-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        .text-o { color: #f43f5e; text-shadow: 0 0 20px rgba(244, 63, 94, 0.5); }

        /* Winning Line Highlight */
        .cell.win {
            background-color: #f59e0b; /* Amber */
            color: #111827;
            text-shadow: none;
            animation: pulse-win 1s infinite;
        }

        @keyframes pulse-win {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Mode Switcher */
        .mode-btn {
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .mode-btn.active {
            background-color: #374151;
            border-color: #4b5563;
            color: white;
        }
        .mode-btn:hover:not(.active) {
            background-color: rgba(55, 65, 81, 0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-lg mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Tic Tac Toe</h1>
                <div class="flex justify-center gap-4 text-sm font-bold">
                    <span class="text-blue-400">X = You</span>
                    <span class="text-rose-400" id="opponent-label">O = CPU</span>
                </div>
            </div>

            <div class="w-full bg-gray-800 p-2 rounded-xl border border-gray-700 mb-6 flex flex-col gap-2">
                <div class="grid grid-cols-2 gap-2 bg-gray-900 p-1 rounded-lg">
                    <button class="mode-btn active rounded-md py-2 text-xs font-bold text-gray-400" onclick="setMode('pvc')" id="btn-pvc">vs Computer</button>
                    <button class="mode-btn rounded-md py-2 text-xs font-bold text-gray-400" onclick="setMode('pvp')" id="btn-pvp">2 Players</button>
                </div>
                
                <div id="diff-container" class="flex justify-center gap-4 text-xs font-bold text-gray-500 pt-1 pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="difficulty" value="easy" class="accent-amber-500" onclick="setDifficulty('easy')">
                        <span>Easy (Random)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="difficulty" value="hard" checked class="accent-amber-500" onclick="setDifficulty('hard')">
                        <span>Impossible (AI)</span>
                    </label>
                </div>
            </div>

            <div class="mb-4 h-8">
                <div id="status-msg" class="text-xl font-bold text-white tracking-wide">Your Turn (X)</div>
            </div>

            <div class="game-grid mb-8" id="board">
                </div>

            <div class="grid grid-cols-3 gap-4 w-full text-center">
                <div class="bg-gray-800 rounded-lg p-3 border-b-4 border-blue-500">
                    <div class="text-[10px] text-gray-500 uppercase font-bold">Player X</div>
                    <div class="text-2xl font-black text-white" id="score-x">0</div>
                </div>
                <div class="bg-gray-800 rounded-lg p-3 border-b-4 border-gray-500">
                    <div class="text-[10px] text-gray-500 uppercase font-bold">Ties</div>
                    <div class="text-2xl font-black text-white" id="score-tie">0</div>
                </div>
                <div class="bg-gray-800 rounded-lg p-3 border-b-4 border-rose-500">
                    <div class="text-[10px] text-gray-500 uppercase font-bold" id="score-o-label">CPU O</div>
                    <div class="text-2xl font-black text-white" id="score-o">0</div>
                </div>
            </div>

            <button onclick="resetGame()" class="mt-8 text-gray-500 hover:text-white text-sm font-bold underline">Reset Board</button>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- CONFIG & STATE ---
        const boardEl = document.getElementById('board');
        const statusMsg = document.getElementById('status-msg');
        const diffContainer = document.getElementById('diff-container');
        const opponentLabel = document.getElementById('opponent-label');
        const scoreOLabel = document.getElementById('score-o-label');
        
        const scores = { x: 0, o: 0, tie: 0 };
        
        let gameState = ["", "", "", "", "", "", "", "", ""];
        let gameActive = true;
        let currentPlayer = "X"; // X always goes first
        let mode = 'pvc'; // 'pvc' or 'pvp'
        let difficulty = 'hard'; // 'easy' or 'hard'

        // --- INIT ---
        function initBoard() {
            boardEl.innerHTML = '';
            gameState.forEach((cell, index) => {
                const cellEl = document.createElement('div');
                cellEl.classList.add('cell');
                cellEl.setAttribute('data-index', index);
                cellEl.addEventListener('click', handleCellClick);
                boardEl.appendChild(cellEl);
            });
            updateStatus();
        }

        // --- GAMEPLAY LOGIC ---

        function handleCellClick(e) {
            const clickedCell = e.target;
            const clickedCellIndex = parseInt(clickedCell.getAttribute('data-index'));

            // Validation
            if (gameState[clickedCellIndex] !== "" || !gameActive) return;
            if (mode === 'pvc' && currentPlayer === 'O') return; // Prevent clicking during AI turn

            // Execute Move
            makeMove(clickedCellIndex, currentPlayer);
            
            // Post-Move Logic
            if (gameActive) {
                if (mode === 'pvp') {
                    // Switch Player
                    currentPlayer = currentPlayer === "X" ? "O" : "X";
                    updateStatus();
                } else {
                    // AI Turn
                    currentPlayer = "O";
                    updateStatus();
                    setTimeout(makeAIMove, 500); // Slight delay for realism
                }
            }
        }

        function makeMove(index, player) {
            gameState[index] = player;
            const cell = document.querySelector(`.cell[data-index='${index}']`);
            cell.textContent = player;
            cell.classList.add('taken', player === 'X' ? 'text-x' : 'text-o');
            
            checkResult();
        }

        function checkResult() {
            const winningConditions = [
                [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
                [0, 3, 6], [1, 4, 7], [2, 5, 8], // Cols
                [0, 4, 8], [2, 4, 6]             // Diagonals
            ];

            let roundWon = false;
            let winCombo = [];

            for (let i = 0; i < winningConditions.length; i++) {
                const [a, b, c] = winningConditions[i];
                if (gameState[a] === "" || gameState[b] === "" || gameState[c] === "") continue;
                if (gameState[a] === gameState[b] && gameState[b] === gameState[c]) {
                    roundWon = true;
                    winCombo = [a, b, c];
                    break;
                }
            }

            if (roundWon) {
                endGame(false, winCombo);
                return;
            }

            if (!gameState.includes("")) {
                endGame(true);
                return;
            }
        }

        function endGame(draw, winCombo = []) {
            gameActive = false;
            if (draw) {
                statusMsg.textContent = "It's a Draw!";
                statusMsg.className = "text-xl font-bold text-gray-400";
                scores.tie++;
                document.getElementById('score-tie').textContent = scores.tie;
            } else {
                statusMsg.textContent = `${currentPlayer} Wins!`;
                statusMsg.className = currentPlayer === 'X' ? "text-xl font-bold text-blue-400" : "text-xl font-bold text-rose-400";
                
                // Highlight winning cells
                winCombo.forEach(index => {
                    document.querySelector(`.cell[data-index='${index}']`).classList.add('win');
                });

                if (currentPlayer === 'X') {
                    scores.x++;
                    document.getElementById('score-x').textContent = scores.x;
                } else {
                    scores.o++;
                    document.getElementById('score-o').textContent = scores.o;
                }
            }
        }

        function updateStatus() {
            if (!gameActive) return;
            if (mode === 'pvc' && currentPlayer === 'O') {
                statusMsg.textContent = "Computer is thinking...";
                statusMsg.className = "text-xl font-bold text-gray-400 animate-pulse";
            } else {
                statusMsg.textContent = `${currentPlayer}'s Turn`;
                statusMsg.className = `text-xl font-bold ${currentPlayer === 'X' ? 'text-blue-400' : 'text-rose-400'}`;
            }
        }

        function resetGame() {
            gameActive = true;
            currentPlayer = "X";
            gameState = ["", "", "", "", "", "", "", "", ""];
            statusMsg.className = "text-xl font-bold text-white";
            initBoard();
        }

        // --- AI ENGINE ---

        function makeAIMove() {
            if (!gameActive) return;

            let moveIndex;

            if (difficulty === 'easy') {
                // Random available move
                const available = gameState.map((val, idx) => val === "" ? idx : null).filter(val => val !== null);
                moveIndex = available[Math.floor(Math.random() * available.length)];
            } else {
                // Minimax (Hard)
                moveIndex = getBestMove(gameState);
            }

            makeMove(moveIndex, 'O');
            
            if (gameActive) {
                currentPlayer = 'X';
                updateStatus();
            }
        }

        // Minimax Algorithm
        // Returns the index of the best move
        function getBestMove(board) {
            let bestScore = -Infinity;
            let move;
            
            for (let i = 0; i < 9; i++) {
                if (board[i] === "") {
                    board[i] = "O";
                    let score = minimax(board, 0, false);
                    board[i] = "";
                    if (score > bestScore) {
                        bestScore = score;
                        move = i;
                    }
                }
            }
            return move;
        }

        const scoresMap = {
            O: 10,
            X: -10,
            tie: 0
        };

        function minimax(board, depth, isMaximizing) {
            let result = checkWinner(board);
            if (result !== null) {
                return scoresMap[result];
            }

            if (isMaximizing) {
                let bestScore = -Infinity;
                for (let i = 0; i < 9; i++) {
                    if (board[i] === "") {
                        board[i] = "O";
                        let score = minimax(board, depth + 1, false);
                        board[i] = "";
                        bestScore = Math.max(score, bestScore);
                    }
                }
                return bestScore;
            } else {
                let bestScore = Infinity;
                for (let i = 0; i < 9; i++) {
                    if (board[i] === "") {
                        board[i] = "X";
                        let score = minimax(board, depth + 1, true);
                        board[i] = "";
                        bestScore = Math.min(score, bestScore);
                    }
                }
                return bestScore;
            }
        }

        // Helper check for Minimax
        function checkWinner(board) {
            const wins = [
                [0,1,2],[3,4,5],[6,7,8],
                [0,3,6],[1,4,7],[2,5,8],
                [0,4,8],[2,4,6]
            ];
            for (let i = 0; i < wins.length; i++) {
                const [a, b, c] = wins[i];
                if (board[a] && board[a] === board[b] && board[a] === board[c]) {
                    return board[a];
                }
            }
            if (!board.includes("")) return "tie";
            return null;
        }

        // --- SETTINGS ---

        function setMode(m) {
            mode = m;
            document.getElementById('btn-pvc').className = `mode-btn rounded-md py-2 text-xs font-bold ${m==='pvc' ? 'active text-white' : 'text-gray-400'}`;
            document.getElementById('btn-pvp').className = `mode-btn rounded-md py-2 text-xs font-bold ${m==='pvp' ? 'active text-white' : 'text-gray-400'}`;
            
            if (m === 'pvp') {
                diffContainer.classList.add('hidden');
                opponentLabel.textContent = "O = Player 2";
                scoreOLabel.textContent = "Player O";
            } else {
                diffContainer.classList.remove('hidden');
                opponentLabel.textContent = "O = CPU";
                scoreOLabel.textContent = "CPU O";
            }
            resetGame();
        }

        function setDifficulty(d) {
            difficulty = d;
            resetGame();
        }

        // Init
        initBoard();

    </script>
</body>
</html>