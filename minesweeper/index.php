<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=VT323&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .digital-font { font-family: 'VT323', monospace; }

        /* Board Container */
        .game-bezel {
            background: #bdbdbd;
            border-top: 4px solid #fcfcfc;
            border-left: 4px solid #fcfcfc;
            border-right: 4px solid #7b7b7b;
            border-bottom: 4px solid #7b7b7b;
            padding: 8px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        /* Inset Panel (Header & Grid) */
        .inset-panel {
            border-top: 3px solid #7b7b7b;
            border-left: 3px solid #7b7b7b;
            border-right: 3px solid #fcfcfc;
            border-bottom: 3px solid #fcfcfc;
            background: #c0c0c0;
        }

        /* Digital Display */
        .led-display {
            background: #000;
            color: #ff0000;
            font-size: 2rem;
            line-height: 1;
            padding: 2px 4px;
            border-top: 2px solid #7b7b7b;
            border-left: 2px solid #7b7b7b;
            border-right: 2px solid #fcfcfc;
            border-bottom: 2px solid #fcfcfc;
            width: 60px;
            text-align: center;
            text-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
        }

        /* Reset Face Button */
        .face-btn {
            width: 40px; height: 40px;
            font-size: 24px;
            display: flex; align-items: center; justify-content: center;
            border-top: 3px solid #fcfcfc;
            border-left: 3px solid #fcfcfc;
            border-right: 3px solid #7b7b7b;
            border-bottom: 3px solid #7b7b7b;
            background: #c0c0c0;
            cursor: pointer;
        }
        .face-btn:active {
            border-top: 3px solid #7b7b7b;
            border-left: 3px solid #7b7b7b;
            border-right: 3px solid #fcfcfc;
            border-bottom: 3px solid #fcfcfc;
            transform: translateY(1px);
        }

        /* The Grid */
        #mine-grid {
            display: grid;
            gap: 0;
            user-select: none;
        }

        /* Individual Cell */
        .cell {
            width: 30px; height: 30px;
            background: #c0c0c0;
            border-top: 3px solid #fcfcfc;
            border-left: 3px solid #fcfcfc;
            border-right: 3px solid #7b7b7b;
            border-bottom: 3px solid #7b7b7b;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900;
            font-size: 18px;
            cursor: default;
        }
        
        /* Cell States */
        .cell.revealed {
            border: 1px solid #999; /* Flat look */
            background: #dcdcdc;
        }
        .cell.mine {
            background: #ef4444; /* Red background for exploded mine */
            border: 1px solid #999;
        }
        .cell.flagged {
            color: #ef4444;
        }

        /* Number Colors */
        .num-1 { color: #0000ff; }
        .num-2 { color: #008000; }
        .num-3 { color: #ff0000; }
        .num-4 { color: #000080; }
        .num-5 { color: #800000; }
        .num-6 { color: #008080; }
        .num-7 { color: #000000; }
        .num-8 { color: #808080; }

    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center justify-center py-8">
        
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-200">Minesweeper</h1>
            <p class="text-gray-500 text-sm mt-1">Right-click (or long press) to flag.</p>
        </div>

        <div class="mb-6 flex gap-2">
            <button onclick="setLevel('easy')" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 rounded border border-gray-600 text-xs font-bold uppercase transition-colors">Easy</button>
            <button onclick="setLevel('medium')" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 rounded border border-gray-600 text-xs font-bold uppercase transition-colors">Medium</button>
            <button onclick="setLevel('hard')" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 rounded border border-gray-600 text-xs font-bold uppercase transition-colors">Hard</button>
        </div>

        <div class="game-bezel">
            
            <div class="inset-panel p-2 flex justify-between items-center mb-3">
                
                <div id="bomb-counter" class="led-display digital-font">010</div>

                <button id="face-btn" class="face-btn" onclick="startGame()">🙂</button>

                <div id="timer" class="led-display digital-font">000</div>

            </div>

            <div class="inset-panel">
                <div id="mine-grid">
                    </div>
            </div>

        </div>

        <div id="game-msg" class="h-6 mt-4 font-bold text-emerald-400 opacity-0 transition-opacity">
            MISSION ACCOMPLISHED!
        </div>

    </main>

    <script>
        // DOM
        const gridEl = document.getElementById('mine-grid');
        const bombCounterEl = document.getElementById('bomb-counter');
        const timerEl = document.getElementById('timer');
        const faceBtn = document.getElementById('face-btn');
        const gameMsg = document.getElementById('game-msg');

        // Configs
        const LEVELS = {
            easy: { rows: 9, cols: 9, mines: 10 },
            medium: { rows: 16, cols: 16, mines: 40 },
            hard: { rows: 16, cols: 30, mines: 99 }
        };

        // State
        let currentLevel = LEVELS.easy;
        let board = []; // 2D array of state
        let gameOver = false;
        let timerInterval;
        let time = 0;
        let flags = 0;
        let cellsRevealed = 0;
        let firstClick = true;

        // --- CORE GAME ---

        function setLevel(lvl) {
            currentLevel = LEVELS[lvl];
            startGame();
        }

        function startGame() {
            // Reset State
            gameOver = false;
            time = 0;
            flags = 0;
            cellsRevealed = 0;
            firstClick = true;
            board = [];
            
            // Reset UI
            stopTimer();
            timerEl.innerText = "000";
            bombCounterEl.innerText = formatNum(currentLevel.mines);
            faceBtn.innerText = "🙂";
            gameMsg.style.opacity = "0";

            // Setup CSS Grid Dimensions
            gridEl.style.gridTemplateColumns = `repeat(${currentLevel.cols}, 30px)`;
            gridEl.style.gridTemplateRows = `repeat(${currentLevel.rows}, 30px)`;

            createBoard();
        }

        function createBoard() {
            gridEl.innerHTML = '';
            
            for (let r = 0; r < currentLevel.rows; r++) {
                const row = [];
                for (let c = 0; c < currentLevel.cols; c++) {
                    const cell = {
                        r, c,
                        isMine: false,
                        isRevealed: false,
                        isFlagged: false,
                        neighborMines: 0,
                        element: null
                    };

                    // Create DOM Element
                    const div = document.createElement('div');
                    div.className = 'cell';
                    div.dataset.r = r;
                    div.dataset.c = c;
                    
                    // Mouse Events
                    div.addEventListener('click', () => handleClick(cell));
                    div.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                        handleRightClick(cell);
                    });
                    
                    // Touch Events (Long press simulation)
                    let pressTimer;
                    div.addEventListener('touchstart', () => {
                        pressTimer = setTimeout(() => handleRightClick(cell), 500);
                    });
                    div.addEventListener('touchend', () => clearTimeout(pressTimer));

                    cell.element = div;
                    row.push(cell);
                    gridEl.appendChild(div);
                }
                board.push(row);
            }
        }

        function placeMines(excludeR, excludeC) {
            let minesPlaced = 0;
            while (minesPlaced < currentLevel.mines) {
                const r = Math.floor(Math.random() * currentLevel.rows);
                const c = Math.floor(Math.random() * currentLevel.cols);

                // Don't place on existing mine OR the first clicked cell
                if (!board[r][c].isMine && (r !== excludeR || c !== excludeC)) {
                    board[r][c].isMine = true;
                    minesPlaced++;
                }
            }
            
            // Calculate Numbers
            for (let r = 0; r < currentLevel.rows; r++) {
                for (let c = 0; c < currentLevel.cols; c++) {
                    if (!board[r][c].isMine) {
                        board[r][c].neighborMines = countNeighbors(r, c);
                    }
                }
            }
        }

        function countNeighbors(r, c) {
            let count = 0;
            for (let i = -1; i <= 1; i++) {
                for (let j = -1; j <= 1; j++) {
                    const nr = r + i;
                    const nc = c + j;
                    if (nr >= 0 && nr < currentLevel.rows && nc >= 0 && nc < currentLevel.cols) {
                        if (board[nr][nc].isMine) count++;
                    }
                }
            }
            return count;
        }

        // --- INTERACTIONS ---

        function handleClick(cell) {
            if (gameOver || cell.isFlagged || cell.isRevealed) return;

            // First click safety: Generate mines AFTER first click
            if (firstClick) {
                firstClick = false;
                placeMines(cell.r, cell.c);
                startTimer();
            }

            if (cell.isMine) {
                triggerGameOver(false);
            } else {
                revealCell(cell);
                checkWin();
            }
        }

        function handleRightClick(cell) {
            if (gameOver || cell.isRevealed) return;

            if (!cell.isFlagged) {
                cell.isFlagged = true;
                cell.element.classList.add('flagged');
                cell.element.innerText = "🚩";
                flags++;
            } else {
                cell.isFlagged = false;
                cell.element.classList.remove('flagged');
                cell.element.innerText = "";
                flags--;
            }
            
            bombCounterEl.innerText = formatNum(currentLevel.mines - flags);
        }

        function revealCell(cell) {
            if (cell.isRevealed || cell.isFlagged) return;

            cell.isRevealed = true;
            cellsRevealed++;
            cell.element.classList.add('revealed');

            if (cell.neighborMines > 0) {
                cell.element.innerText = cell.neighborMines;
                cell.element.classList.add(`num-${cell.neighborMines}`);
            } else {
                // Flood Fill
                // If 0 neighbors, recursively reveal surroundings
                for (let i = -1; i <= 1; i++) {
                    for (let j = -1; j <= 1; j++) {
                        const nr = cell.r + i;
                        const nc = cell.c + j;
                        if (nr >= 0 && nr < currentLevel.rows && nc >= 0 && nc < currentLevel.cols) {
                            // Recursion happen here
                            revealCell(board[nr][nc]);
                        }
                    }
                }
            }
        }

        function triggerGameOver(win) {
            gameOver = true;
            stopTimer();

            if (win) {
                faceBtn.innerText = "😎";
                gameMsg.innerText = "MISSION ACCOMPLISHED!";
                gameMsg.classList.add("text-emerald-400");
                gameMsg.classList.remove("text-red-500");
                gameMsg.style.opacity = "1";
            } else {
                faceBtn.innerText = "😵";
                gameMsg.innerText = "GAME OVER";
                gameMsg.classList.add("text-red-500");
                gameMsg.classList.remove("text-emerald-400");
                gameMsg.style.opacity = "1";

                // Reveal all mines
                board.flat().forEach(c => {
                    if (c.isMine) {
                        c.element.classList.add('mine');
                        c.element.innerText = "💣";
                        
                    }
                });
            }
        }

        function checkWin() {
            const safeCells = (currentLevel.rows * currentLevel.cols) - currentLevel.mines;
            if (cellsRevealed === safeCells) {
                triggerGameOver(true);
            }
        }

        // --- TIMER UTILS ---

        function startTimer() {
            timerInterval = setInterval(() => {
                time++;
                if (time > 999) time = 999;
                timerEl.innerText = formatNum(time);
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
        }

        function formatNum(num) {
            return num.toString().padStart(3, '0');
        }

        // Init
        startGame();

    </script>
</body>
</html>