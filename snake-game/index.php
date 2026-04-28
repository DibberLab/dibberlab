<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .retro-font { font-family: 'Press Start 2P', cursive; }

        /* Game Container Glow */
        .game-border {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.2), inset 0 0 20px rgba(16, 185, 129, 0.1);
            border: 4px solid #374151;
        }

        /* D-Pad Buttons */
        .d-btn {
            background: #1f2937;
            border: 2px solid #374151;
            border-radius: 8px;
            color: #9ca3af;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.1s;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .d-btn:active {
            background: #10b981;
            color: black;
            border-color: #10b981;
            transform: scale(0.95);
        }

        /* Scanline Effect */
        .scanlines {
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0) 50%,
                rgba(0,0,0,0.2) 50%,
                rgba(0,0,0,0.2)
            );
            background-size: 100% 4px;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center justify-center py-4">
        
        <div class="flex justify-between items-end w-full max-w-[400px] mb-4">
            <div>
                <h1 class="text-2xl text-emerald-500 retro-font leading-relaxed">SNAKE</h1>
                <p class="text-[10px] text-gray-500 font-mono">USE ARROW KEYS</p>
            </div>
            <div class="text-right">
                <div class="text-[10px] text-gray-500 font-mono">HIGH SCORE</div>
                <div id="high-score" class="text-lg text-amber-400 retro-font">0</div>
            </div>
        </div>

        <div class="relative w-full max-w-[400px] aspect-square">
            
            <canvas id="game-canvas" width="400" height="400" class="w-full h-full bg-black game-border rounded-xl block"></canvas>
            
            <div class="absolute inset-0 scanlines rounded-xl z-10"></div>

            <div id="overlay" class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center z-20 rounded-xl backdrop-blur-sm">
                <h2 id="overlay-title" class="text-3xl text-white retro-font mb-6 text-center leading-relaxed">READY?</h2>
                <div id="current-score-display" class="hidden text-center mb-6">
                    <p class="text-xs text-gray-400 font-mono mb-2">SCORE</p>
                    <p id="final-score" class="text-2xl text-emerald-400 retro-font">0</p>
                </div>
                <button onclick="startGame()" class="px-6 py-4 bg-emerald-600 hover:bg-emerald-500 text-white retro-font text-xs rounded shadow-[0_4px_0_#064e3b] active:translate-y-1 active:shadow-none transition-all">
                    START GAME
                </button>
            </div>

            <div class="absolute top-4 right-4 z-10 bg-black/50 px-2 py-1 rounded border border-gray-800 backdrop-blur">
                <span id="score" class="text-white retro-font text-xs">0</span>
            </div>

        </div>

        <div class="mt-8 grid grid-cols-3 gap-2 md:hidden">
            <div></div> <button class="d-btn" ontouchstart="handleMobileInput('up')" onclick="handleMobileInput('up')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
            </button>
            <div></div> <button class="d-btn" ontouchstart="handleMobileInput('left')" onclick="handleMobileInput('left')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </button>
            
            <button class="d-btn" ontouchstart="handleMobileInput('down')" onclick="handleMobileInput('down')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </button>
            
            <button class="d-btn" ontouchstart="handleMobileInput('right')" onclick="handleMobileInput('right')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

    </main>

    <script>
        const canvas = document.getElementById('game-canvas');
        const ctx = canvas.getContext('2d');
        const scoreEl = document.getElementById('score');
        const highScoreEl = document.getElementById('high-score');
        const overlay = document.getElementById('overlay');
        const overlayTitle = document.getElementById('overlay-title');
        const currentScoreDisplay = document.getElementById('current-score-display');
        const finalScoreEl = document.getElementById('final-score');

        // Game Config
        const GRID_SIZE = 20;
        const TILE_COUNT = canvas.width / GRID_SIZE;
        const GAME_SPEED = 100; // ms per frame (lower is faster)

        // Game State
        let snake = [];
        let food = {x: 15, y: 15};
        let dx = 0;
        let dy = 0;
        let score = 0;
        let highScore = localStorage.getItem('snake-highscore') || 0;
        let gameInterval;
        let isGameRunning = false;
        let nextDirection = { x: 0, y: 0 }; // Buffer to prevent self-collision on quick turns

        // Initialize High Score
        highScoreEl.textContent = highScore;

        // --- CORE GAME LOOP ---

        function startGame() {
            // Reset variables
            snake = [
                {x: 10, y: 10},
                {x: 10, y: 11},
                {x: 10, y: 12}
            ];
            dx = 0;
            dy = -1; // Start moving up
            nextDirection = { x: 0, y: -1 };
            score = 0;
            scoreEl.textContent = 0;
            spawnFood();
            
            // UI
            overlay.classList.add('hidden');
            currentScoreDisplay.classList.add('hidden');
            isGameRunning = true;

            // Start Loop
            if (gameInterval) clearInterval(gameInterval);
            gameInterval = setInterval(gameLoop, GAME_SPEED);
        }

        function gameLoop() {
            if (!isGameRunning) return;

            update();
            draw();
        }

        function update() {
            // Apply buffered direction
            dx = nextDirection.x;
            dy = nextDirection.y;

            // Calculate new head position
            const head = { x: snake[0].x + dx, y: snake[0].y + dy };

            // 1. Check Wall Collision (Game Over)
            if (head.x < 0 || head.x >= TILE_COUNT || head.y < 0 || head.y >= TILE_COUNT) {
                gameOver();
                return;
            }

            // 2. Check Self Collision
            for (let i = 0; i < snake.length; i++) {
                if (head.x === snake[i].x && head.y === snake[i].y) {
                    gameOver();
                    return;
                }
            }

            // Add new head
            snake.unshift(head);

            // 3. Check Food
            if (head.x === food.x && head.y === food.y) {
                score += 10;
                scoreEl.textContent = score;
                spawnFood();
                // Don't pop tail (grow)
            } else {
                snake.pop(); // Remove tail (move)
            }
        }

        function draw() {
            // Clear Screen
            ctx.fillStyle = '#000000';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw Snake
            ctx.fillStyle = '#10b981'; // Emerald 500
            ctx.shadowBlur = 10;
            ctx.shadowColor = '#10b981';
            
            snake.forEach((part, index) => {
                // Head is slightly brighter
                if (index === 0) ctx.fillStyle = '#34d399'; // Emerald 400
                else ctx.fillStyle = '#10b981';

                ctx.fillRect(part.x * GRID_SIZE, part.y * GRID_SIZE, GRID_SIZE - 2, GRID_SIZE - 2);
            });

            // Draw Food
            ctx.fillStyle = '#ef4444'; // Red 500
            ctx.shadowBlur = 15;
            ctx.shadowColor = '#ef4444';
            ctx.fillRect(food.x * GRID_SIZE, food.y * GRID_SIZE, GRID_SIZE - 2, GRID_SIZE - 2);
            
            // Reset Shadow
            ctx.shadowBlur = 0;
        }

        function spawnFood() {
            // Random position
            food = {
                x: Math.floor(Math.random() * TILE_COUNT),
                y: Math.floor(Math.random() * TILE_COUNT)
            };
            
            // Don't spawn on snake body
            for (let part of snake) {
                if (part.x === food.x && part.y === food.y) {
                    spawnFood(); // Try again
                }
            }
        }

        function gameOver() {
            isGameRunning = false;
            clearInterval(gameInterval);
            
            // Update High Score
            if (score > highScore) {
                highScore = score;
                localStorage.setItem('snake-highscore', highScore);
                highScoreEl.textContent = highScore;
            }

            // Show Overlay
            overlayTitle.textContent = "GAME OVER";
            finalScoreEl.textContent = score;
            currentScoreDisplay.classList.remove('hidden');
            overlay.classList.remove('hidden');
        }

        // --- INPUT HANDLING ---

        document.addEventListener('keydown', (e) => {
            if (!isGameRunning) return;

            // Prevent browser scrolling
            if(["ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].indexOf(e.code) > -1) {
                e.preventDefault();
            }

            switch(e.key) {
                case 'ArrowUp':
                    if (dy === 0) nextDirection = {x: 0, y: -1};
                    break;
                case 'ArrowDown':
                    if (dy === 0) nextDirection = {x: 0, y: 1};
                    break;
                case 'ArrowLeft':
                    if (dx === 0) nextDirection = {x: -1, y: 0};
                    break;
                case 'ArrowRight':
                    if (dx === 0) nextDirection = {x: 1, y: 0};
                    break;
            }
        });

        // Mobile Handlers
        function handleMobileInput(dir) {
            if (!isGameRunning) return;
            // Add haptics if available
            if (navigator.vibrate) navigator.vibrate(5); 

            switch(dir) {
                case 'up':
                    if (dy === 0) nextDirection = {x: 0, y: -1};
                    break;
                case 'down':
                    if (dy === 0) nextDirection = {x: 0, y: 1};
                    break;
                case 'left':
                    if (dx === 0) nextDirection = {x: -1, y: 0};
                    break;
                case 'right':
                    if (dx === 0) nextDirection = {x: 1, y: 0};
                    break;
            }
        }

        // Initial Draw (Empty Grid)
        ctx.fillStyle = '#000000';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

    </script>
</body>
</html>