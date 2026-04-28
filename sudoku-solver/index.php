<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudoku Solver | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Hide Number Spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }

        /* The Grid */
        #sudoku-board {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 1px;
            background-color: #374151; /* Gray-700 (Border color) */
            border: 2px solid #374151;
        }

        .cell {
            background-color: #1f2937; /* Gray-800 */
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            width: 100%;
            aspect-ratio: 1;
            cursor: pointer;
            transition: background-color 0.1s;
            caret-color: transparent; /* Hide cursor line */
        }

        .cell:focus {
            background-color: #374151;
            outline: none;
            color: #fbbf24; /* Amber focus */
        }

        /* Generated Solution Text */
        .cell.solved {
            color: #10b981; /* Emerald-500 */
        }

        /* 3x3 Subgrid Borders */
        .border-r-thick { border-right: 2px solid #6b7280; } /* Gray-500 */
        .border-b-thick { border-bottom: 2px solid #6b7280; }

        /* Invalid Input Highlight */
        .cell.invalid {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* Button Hover Animation */
        .action-btn { transition: transform 0.1s; }
        .action-btn:active { transform: scale(0.95); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-lg mx-auto flex flex-col gap-6">
            
            <div class="text-center mb-2">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Sudoku Solver</h1>
                <p class="text-center text-gray-400 text-sm">Enter numbers and let the algorithm solve it.</p>
            </div>

            <div id="status-bar" class="bg-gray-800 border border-gray-700 rounded-lg p-3 text-center text-sm font-bold text-gray-400">
                Ready to solve
            </div>

            <div class="relative">
                <div id="sudoku-board" class="shadow-2xl rounded-sm overflow-hidden">
                    </div>
                
                <div id="error-overlay" class="absolute inset-0 bg-black/80 flex items-center justify-center hidden backdrop-blur-sm z-10 rounded-sm">
                    <div class="text-center">
                        <div class="text-4xl mb-2">🚫</div>
                        <h3 class="text-xl font-bold text-red-500">Unsolvable Board</h3>
                        <p class="text-gray-400 text-sm mb-4">Check your inputs for duplicates.</p>
                        <button onclick="hideError()" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-white text-xs font-bold">Dismiss</button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button onclick="solveBoard()" class="action-btn col-span-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl shadow-lg flex items-center justify-center gap-2">
                    <span>⚡</span> SOLVE PUZZLE
                </button>
                <button onclick="loadSample()" class="action-btn bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 rounded-xl border border-gray-700">
                    Load Sample
                </button>
                <button onclick="clearBoard()" class="action-btn bg-gray-800 hover:bg-red-900/50 text-red-300 font-bold py-3 rounded-xl border border-gray-700 hover:border-red-800">
                    Clear
                </button>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const boardEl = document.getElementById('sudoku-board');
        const statusBar = document.getElementById('status-bar');
        const errorOverlay = document.getElementById('error-overlay');

        // State
        let cells = [];

        // --- INIT BOARD ---
        function init() {
            boardEl.innerHTML = '';
            cells = [];

            for (let i = 0; i < 81; i++) {
                const input = document.createElement('input');
                input.type = 'number';
                input.className = 'cell mono-font';
                input.min = 1;
                input.max = 9;
                
                // Add borders for 3x3 grid visibility
                const col = i % 9;
                const row = Math.floor(i / 9);
                if (col === 2 || col === 5) input.classList.add('border-r-thick');
                if (row === 2 || row === 5) input.classList.add('border-b-thick');

                // Event Listeners
                input.addEventListener('input', (e) => handleInput(e, i));
                input.addEventListener('keydown', (e) => handleNav(e, i));
                input.addEventListener('focus', () => input.select());

                boardEl.appendChild(input);
                cells.push(input);
            }
        }

        // --- INPUT HANDLING ---
        function handleInput(e, index) {
            const val = e.target.value;
            
            // Allow only 1-9
            if (val.length > 1) e.target.value = val.slice(-1); // Take last char
            if (val === '0') e.target.value = '';
            
            // Remove previous styling
            e.target.classList.remove('solved', 'invalid');
            statusBar.textContent = "Ready to solve";
            statusBar.className = "bg-gray-800 border border-gray-700 rounded-lg p-3 text-center text-sm font-bold text-gray-400";
        }

        function handleNav(e, index) {
            if (e.key === 'ArrowRight') focusCell(index + 1);
            if (e.key === 'ArrowLeft') focusCell(index - 1);
            if (e.key === 'ArrowUp') focusCell(index - 9);
            if (e.key === 'ArrowDown') focusCell(index + 9);
        }

        function focusCell(index) {
            if (index >= 0 && index < 81) cells[index].focus();
        }

        // --- SOLVER ALGORITHM (Backtracking) ---

        function solveBoard() {
            // 1. Parse Board
            const board = [];
            for (let r = 0; r < 9; r++) {
                const row = [];
                for (let c = 0; c < 9; c++) {
                    const val = cells[r * 9 + c].value;
                    row.push(val === '' ? 0 : parseInt(val));
                }
                board.push(row);
            }

            // 2. Validate Input first
            if (!isValidBoard(board)) {
                statusBar.textContent = "Invalid Board Configuration";
                statusBar.className = "bg-red-900/50 border border-red-700 rounded-lg p-3 text-center text-sm font-bold text-red-300";
                return;
            }

            // 3. Solve
            const startTime = performance.now();
            if (solve(board)) {
                const time = (performance.now() - startTime).toFixed(2);
                populateBoard(board);
                statusBar.textContent = `Solved in ${time}ms`;
                statusBar.className = "bg-emerald-900/50 border border-emerald-700 rounded-lg p-3 text-center text-sm font-bold text-emerald-300";
            } else {
                errorOverlay.classList.remove('hidden');
            }
        }

        function solve(board) {
            for (let r = 0; r < 9; r++) {
                for (let c = 0; c < 9; c++) {
                    if (board[r][c] === 0) {
                        for (let num = 1; num <= 9; num++) {
                            if (isValid(board, r, c, num)) {
                                board[r][c] = num;
                                if (solve(board)) return true;
                                board[r][c] = 0; // Backtrack
                            }
                        }
                        return false;
                    }
                }
            }
            return true;
        }

        function isValid(board, row, col, num) {
            // Check Row & Col
            for (let i = 0; i < 9; i++) {
                if (board[row][i] === num && i !== col) return false;
                if (board[i][col] === num && i !== row) return false;
            }

            // Check 3x3 Box
            const startRow = Math.floor(row / 3) * 3;
            const startCol = Math.floor(col / 3) * 3;
            for (let i = 0; i < 3; i++) {
                for (let j = 0; j < 3; j++) {
                    if (board[startRow + i][startCol + j] === num && (startRow+i !== row || startCol+j !== col)) return false;
                }
            }
            return true;
        }

        // Check if the user's initial input is valid (no duplicates)
        function isValidBoard(board) {
            for (let r = 0; r < 9; r++) {
                for (let c = 0; c < 9; c++) {
                    if (board[r][c] !== 0) {
                        // Temporarily clear cell to check validity
                        const temp = board[r][c];
                        board[r][c] = 0;
                        if (!isValid(board, r, c, temp)) return false;
                        board[r][c] = temp;
                    }
                }
            }
            return true;
        }

        function populateBoard(board) {
            for (let r = 0; r < 9; r++) {
                for (let c = 0; c < 9; c++) {
                    const index = r * 9 + c;
                    const input = cells[index];
                    
                    // Only update and style empty cells
                    if (input.value === '') {
                        input.value = board[r][c];
                        input.classList.add('solved');
                    }
                }
            }
        }

        // --- ACTIONS ---

        function loadSample() {
            clearBoard();
            // A "Hard" Puzzle
            const puzzle = [
                [0,0,0, 2,6,0, 7,0,1],
                [6,8,0, 0,7,0, 0,9,0],
                [1,9,0, 0,0,4, 5,0,0],
                
                [8,2,0, 1,0,0, 0,4,0],
                [0,0,4, 6,0,2, 9,0,0],
                [0,5,0, 0,0,3, 0,2,8],
                
                [0,0,9, 3,0,0, 0,7,4],
                [0,4,0, 0,5,0, 0,3,6],
                [7,0,3, 0,1,8, 0,0,0]
            ];

            for (let r = 0; r < 9; r++) {
                for (let c = 0; c < 9; c++) {
                    if (puzzle[r][c] !== 0) {
                        cells[r * 9 + c].value = puzzle[r][c];
                    }
                }
            }
        }

        function clearBoard() {
            cells.forEach(c => {
                c.value = '';
                c.classList.remove('solved', 'invalid');
            });
            statusBar.textContent = "Ready to solve";
            statusBar.className = "bg-gray-800 border border-gray-700 rounded-lg p-3 text-center text-sm font-bold text-gray-400";
        }

        function hideError() {
            errorOverlay.classList.add('hidden');
        }

        // --- INIT ---
        init();

    </script>
</body>
</html>