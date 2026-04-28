<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2048 | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Game Board Background */
        .game-board {
            background-color: #bbada0;
            border-radius: 8px;
            position: relative;
            touch-action: none; /* Prevent scrolling on mobile while swiping */
        }

        /* Grid Cells (Empty slots) */
        .grid-cell {
            background-color: #cdc1b4;
            border-radius: 4px;
        }

        /* The Tiles */
        .tile {
            position: absolute;
            transition: all 150ms ease-in-out;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            z-index: 10;
        }

        /* Tile Colors & Font Sizes */
        .tile-2 { background: #eee4da; color: #776e65; font-size: 40px; }
        .tile-4 { background: #ede0c8; color: #776e65; font-size: 40px; }
        .tile-8 { background: #f2b179; color: #f9f6f2; font-size: 36px; }
        .tile-16 { background: #f59563; color: #f9f6f2; font-size: 32px; }
        .tile-32 { background: #f67c5f; color: #f9f6f2; font-size: 28px; }
        .tile-64 { background: #f65e3b; color: #f9f6f2; font-size: 24px; }
        .tile-128 { background: #edcf72; color: #f9f6f2; font-size: 20px; box-shadow: 0 0 10px #edcf72; }
        .tile-256 { background: #edcc61; color: #f9f6f2; font-size: 20px; box-shadow: 0 0 15px #edcc61; }
        .tile-512 { background: #edc850; color: #f9f6f2; font-size: 20px; box-shadow: 0 0 20px #edc850; }
        .tile-1024 { background: #edc53f; color: #f9f6f2; font-size: 16px; box-shadow: 0 0 25px #edc53f; }
        .tile-2048 { background: #edc22e; color: #f9f6f2; font-size: 16px; box-shadow: 0 0 30px #edc22e; }
        .tile-super { background: #3c3a32; color: #f9f6f2; font-size: 14px; }

        /* Merge Animation */
        .tile-merged {
            animation: pop 200ms ease-in-out;
            z-index: 20;
        }
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* New Tile Animation */
        .tile-new {
            animation: appear 200ms ease-in-out;
        }
        @keyframes appear {
            0% { opacity: 0; transform: scale(0); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Score Box */
        .score-box {
            background: #bbada0;
            color: #eee4da;
        }
    </style>
</head>
<body class="bg-[#faf8ef] text-[#776e65] min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center justify-center py-6">
        
        <div class="w-full max-w-[400px]">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-6xl font-bold text-[#776e65]">2048</h1>
                    <p class="text-sm font-semibold text-[#776e65]/70">Join the numbers.</p>
                </div>
                
                <div class="flex gap-2">
                    <div class="score-box px-4 py-2 rounded font-bold text-center min-w-[70px]">
                        <div class="text-xs uppercase text-[#eee4da]/70">Score</div>
                        <div id="score-val" class="text-white text-xl">0</div>
                    </div>
                    <div class="score-box px-4 py-2 rounded font-bold text-center min-w-[70px]">
                        <div class="text-xs uppercase text-[#eee4da]/70">Best</div>
                        <div id="best-val" class="text-white text-xl">0</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <p class="text-sm leading-tight max-w-[200px]">Join tiles to reach <strong>2048!</strong></p>
                <button onclick="startGame()" class="bg-[#8f7a66] hover:bg-[#7f6a56] text-white font-bold py-2 px-6 rounded text-sm transition-colors shadow-md active:translate-y-1">New Game</button>
            </div>

            <div class="relative w-[340px] h-[340px] md:w-[400px] md:h-[400px] mx-auto">
                
                <div id="game-board" class="game-board w-full h-full p-3 grid grid-cols-4 grid-rows-4 gap-3">
                    <div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div>
                    <div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div>
                    <div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div>
                    <div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div><div class="grid-cell"></div>
                </div>

                <div id="tile-container" class="absolute inset-0 p-3 pointer-events-none">
                    </div>

                <div id="game-over" class="hidden absolute inset-0 bg-[#eee4da]/70 z-30 flex flex-col items-center justify-center rounded-lg backdrop-blur-sm">
                    <h2 class="text-5xl font-bold text-[#776e65] mb-4">Game Over!</h2>
                    <button onclick="startGame()" class="bg-[#8f7a66] text-white font-bold py-3 px-6 rounded shadow-lg hover:scale-105 transition-transform">Try Again</button>
                </div>

                <div id="game-win" class="hidden absolute inset-0 bg-[#edc22e]/60 z-30 flex flex-col items-center justify-center rounded-lg backdrop-blur-sm">
                    <h2 class="text-5xl font-bold text-white mb-4 drop-shadow-md">You Win!</h2>
                    <div class="flex gap-4">
                        <button onclick="continueGame()" class="bg-white text-[#776e65] font-bold py-3 px-6 rounded shadow-lg hover:scale-105 transition-transform">Keep Going</button>
                        <button onclick="startGame()" class="bg-[#8f7a66] text-white font-bold py-3 px-6 rounded shadow-lg hover:scale-105 transition-transform">Restart</button>
                    </div>
                </div>

            </div>

            <p class="mt-8 text-xs text-center text-[#776e65]/60">
                Use <strong>Arrow Keys</strong> or <strong>Swipe</strong> to move tiles.
            </p>

        </div>
    </main>

    <script>
        // DOM Elements
        const tileContainer = document.getElementById('tile-container');
        const scoreVal = document.getElementById('score-val');
        const bestVal = document.getElementById('best-val');
        const gameOverEl = document.getElementById('game-over');
        const gameWinEl = document.getElementById('game-win');
        const boardEl = document.getElementById('game-board');

        // Config
        const SIZE = 4;
        let grid = [];
        let score = 0;
        let bestScore = localStorage.getItem('2048-best') || 0;
        let hasWon = false;
        let keepPlaying = false;

        // Initialize Best Score UI
        bestVal.textContent = bestScore;

        // --- CORE LOGIC ---

        function startGame() {
            grid = Array(SIZE).fill().map(() => Array(SIZE).fill(0));
            score = 0;
            hasWon = false;
            keepPlaying = false;
            scoreVal.textContent = 0;
            
            gameOverEl.classList.add('hidden');
            gameWinEl.classList.add('hidden');
            
            tileContainer.innerHTML = '';
            
            addNewTile();
            addNewTile();
            renderBoard();
        }

        function continueGame() {
            keepPlaying = true;
            gameWinEl.classList.add('hidden');
        }

        // --- TILE MANAGEMENT ---

        function addNewTile() {
            const emptyCells = [];
            for(let r=0; r<SIZE; r++) {
                for(let c=0; c<SIZE; c++) {
                    if(grid[r][c] === 0) emptyCells.push({r, c});
                }
            }

            if(emptyCells.length > 0) {
                const rand = emptyCells[Math.floor(Math.random() * emptyCells.length)];
                // 90% chance of 2, 10% chance of 4
                grid[rand.r][rand.c] = Math.random() < 0.9 ? 2 : 4;
                
                // Add DOM element for animation
                renderTile(rand.r, rand.c, grid[rand.r][rand.c], true);
            }
        }

        function renderBoard() {
            tileContainer.innerHTML = ''; // Clear DOM
            
            for(let r=0; r<SIZE; r++) {
                for(let c=0; c<SIZE; c++) {
                    if(grid[r][c] !== 0) {
                        renderTile(r, c, grid[r][c]);
                    }
                }
            }
        }

        function renderTile(r, c, val, isNew = false, isMerged = false) {
            const tile = document.createElement('div');
            
            // Calculate Position (Percent based on 4x4 grid + gaps)
            // Gap is 12px (0.75rem or 3 unit in tailwind spacing on p-3)
            // Actually, simplified math: 
            // Width of container is 100%. Grid has gap.
            // Let's use CSS Grid math simulation or just hardcoded percents
            // Gap = 3px approx relative to container size?
            // Easier way: The tiles are absolute. 
            // Total width/height is variable (responsive). 
            // We use percentages. 
            // 4 cells + 5 gaps. 
            // let Gap = 15px approx. Cell = (Total - 5*Gap)/4.
            // Actually, we can just use simple approximate percentages and let CSS gap handle background.
            // Position = (CellSize + Gap) * Index
            // CellSize approx 21.25% , Gap approx 3%
            
            // Refined Math for visual alignment:
            const gap = 12; // px
            const containerSize = tileContainer.clientWidth; 
            // Note: clientWidth might be 0 if hidden, but it's visible.
            // On resize this breaks. Better to use % logic.
            
            const cellPercent = 21.75; // Approx
            const gapPercent = 2.5; // Approx
            // Actually, let's use calc() for precision if we knew pixel gap, but percent is safer for responsive.
            
            // Standard 2048 CSS logic:
            const pos = (index) => `calc(${index * 25}% + 2%)`; // Rough approx
            // Better: use specific pixels if we assume container size, but let's try strict percent.
            // r * (100% / 4)
            
            // Let's rely on the exact logic used in original game CSS but adapted:
            // top = 12px + r * (cell + 12px)
            // To make it responsive, we simply calculate positions dynamically based on current DOM size of grid cells
            // But getting DOM elements is slow.
            
            // Simple approach: Use hardcoded % that align with the grid-cols-4 gap-3 layout
            // gap-3 = 0.75rem = 12px.
            // We can calculate style.left and style.top based on r/c
            // We need to know the width of a single cell in the underlying grid
            
            // Dynamic Calculation:
            const gridCells = document.querySelectorAll('.grid-cell');
            if(gridCells.length > 0) {
                const sample = gridCells[0];
                const width = sample.offsetWidth;
                const height = sample.offsetHeight;
                const gapX = 12; // Standard tailwind gap-3 is 12px
                const gapY = 12; 
                
                // Offset by container padding (12px)
                const offsetX = 12; 
                const offsetY = 12;

                const left = offsetX + c * (width + gapX);
                const top = offsetY + r * (height + gapY);
                
                tile.style.left = `${left}px`;
                tile.style.top = `${top}px`;
                tile.style.width = `${width}px`;
                tile.style.height = `${height}px`;
            }

            // Classes
            let valClass = val > 2048 ? 'tile-super' : `tile-${val}`;
            tile.className = `tile ${valClass} ${isNew ? 'tile-new' : ''} ${isMerged ? 'tile-merged' : ''}`;
            tile.textContent = val;

            tileContainer.appendChild(tile);
        }

        // --- MOVEMENT LOGIC ---

        function move(direction) {
            // 0:Up, 1:Right, 2:Down, 3:Left
            let rotatedGrid = [...grid.map(row => [...row])];
            let scoreAdd = 0;
            let moved = false;

            // Rotate grid so we always process as "Slide Left"
            // 0 (Up) -> Rotate -90 (Left)
            // 1 (Right) -> Rotate 180 (Left)
            // 2 (Down) -> Rotate 90 (Left)
            // 3 (Left) -> No rotation
            
            const rotations = {
                0: 3, // Rotate 270 deg clockwise to make UP face LEFT
                1: 2, // Rotate 180
                2: 1, // Rotate 90
                3: 0  // 0
            };
            
            // Helper to rotate grid 90 deg clockwise
            const rotate = (matrix) => matrix[0].map((val, index) => matrix.map(row => row[index]).reverse());

            for(let i=0; i<rotations[direction]; i++) {
                rotatedGrid = rotate(rotatedGrid);
            }

            // Process Slide Left on rotated grid
            for(let r=0; r<SIZE; r++) {
                let row = rotatedGrid[r].filter(val => val !== 0); // Remove zeros
                let newRow = [];
                let skip = false;

                for(let i=0; i<row.length; i++) {
                    if(skip) {
                        skip = false;
                        continue;
                    }
                    // Combine
                    if(i < row.length - 1 && row[i] === row[i+1]) {
                        const mergedVal = row[i] * 2;
                        newRow.push(mergedVal);
                        scoreAdd += mergedVal;
                        skip = true;
                        if (mergedVal === 2048 && !hasWon && !keepPlaying) hasWon = true;
                    } else {
                        newRow.push(row[i]);
                    }
                }

                // Pad with zeros
                while(newRow.length < SIZE) newRow.push(0);

                // Check if changed
                if(newRow.join(',') !== rotatedGrid[r].join(',')) {
                    moved = true;
                }
                rotatedGrid[r] = newRow;
            }

            // Rotate back
            const backRotations = (4 - rotations[direction]) % 4;
            for(let i=0; i<backRotations; i++) {
                rotatedGrid = rotate(rotatedGrid);
            }

            if(moved) {
                grid = rotatedGrid;
                score += scoreAdd;
                scoreVal.textContent = score;
                
                if(score > bestScore) {
                    bestScore = score;
                    localStorage.setItem('2048-best', bestScore);
                    bestVal.textContent = bestScore;
                }

                renderBoard(); // Render movement first
                
                setTimeout(() => {
                    addNewTile(); // Spawn new tile
                    
                    if(hasWon && !keepPlaying) {
                        gameWinEl.classList.remove('hidden');
                    } else if(checkGameOver()) {
                        gameOverEl.classList.remove('hidden');
                    }
                }, 100);
            }
        }

        function checkGameOver() {
            // Check for empty cells
            for(let r=0; r<SIZE; r++) {
                for(let c=0; c<SIZE; c++) {
                    if(grid[r][c] === 0) return false;
                }
            }
            // Check for possible merges
            for(let r=0; r<SIZE; r++) {
                for(let c=0; c<SIZE; c++) {
                    const val = grid[r][c];
                    // Check Right
                    if(c < SIZE - 1 && grid[r][c+1] === val) return false;
                    // Check Down
                    if(r < SIZE - 1 && grid[r+1][c] === val) return false;
                }
            }
            return true;
        }

        // --- INPUT HANDLING ---

        document.addEventListener('keydown', (e) => {
            if(gameOverEl.classList.contains('hidden') === false) return;
            // Prevent default scrolling for arrows
            if([37, 38, 39, 40].includes(e.keyCode)) e.preventDefault();

            switch(e.key) {
                case 'ArrowUp': move(0); break;
                case 'ArrowRight': move(1); break;
                case 'ArrowDown': move(2); break;
                case 'ArrowLeft': move(3); break;
            }
        });

        // Swipe Controls
        let touchStartX = 0;
        let touchStartY = 0;
        const boardArea = document.getElementById('game-board');

        boardArea.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, {passive: false});

        boardArea.addEventListener('touchend', (e) => {
            e.preventDefault(); // Prevent scroll/zoom
            const touchEndX = e.changedTouches[0].screenX;
            const touchEndY = e.changedTouches[0].screenY;
            
            const dx = touchEndX - touchStartX;
            const dy = touchEndY - touchStartY;

            if(Math.abs(dx) > Math.abs(dy)) {
                // Horizontal
                if(Math.abs(dx) > 30) { // Threshold
                    if(dx > 0) move(1); // Right
                    else move(3); // Left
                }
            } else {
                // Vertical
                if(Math.abs(dy) > 30) {
                    if(dy > 0) move(2); // Down
                    else move(0); // Up
                }
            }
        }, {passive: false});

        // Handle Window Resize (Recalculate positions)
        window.addEventListener('resize', renderBoard);

        // Init
        startGame();

    </script>
</body>
</html>