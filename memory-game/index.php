<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* --- 3D CARD FLIP CSS --- */
        .card-scene {
            perspective: 1000px;
            cursor: pointer;
            user-select: none;
            aspect-ratio: 1; /* Keep cards square */
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1);
            transform-style: preserve-3d;
        }

        .card-scene.flipped .card-inner {
            transform: rotateY(180deg);
        }

        .card-face {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; /* Safari */
            backface-visibility: hidden;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            border: 2px solid #374151;
        }

        /* Front (Face Down) */
        .card-front {
            background-color: #1f2937; /* Gray-800 */
            background-image: radial-gradient(#374151 15%, transparent 16%), radial-gradient(#374151 15%, transparent 16%);
            background-size: 10px 10px;
            background-position: 0 0, 5px 5px;
            transform: rotateY(0deg); /* Explicit for Safari */
        }
        .card-front::after {
            content: '?';
            font-weight: 900;
            color: #4b5563;
            font-size: 1.5rem;
        }

        /* Back (Face Up) */
        .card-back {
            background-color: #111827; /* Gray-900 */
            transform: rotateY(180deg);
            border-color: #f59e0b; /* Amber border when revealed */
            font-size: 2.5rem;
        }

        /* Match Animation */
        .matched .card-back {
            background-color: #064e3b; /* Emerald-900 */
            border-color: #10b981;
            animation: pulse-green 0.5s;
        }
        @keyframes pulse-green {
            0% { transform: rotateY(180deg) scale(1); }
            50% { transform: rotateY(180deg) scale(1.1); }
            100% { transform: rotateY(180deg) scale(1); }
        }

        /* Mode Buttons */
        .mode-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .mode-btn:hover { background-color: #374151; }
        .mode-btn.active {
            background-color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            color: #111827;
            font-weight: bold;
        }

        /* Win Modal */
        .modal-enter { animation: popIn 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28); }
        @keyframes popIn { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400">Memory</h1>
                    <p class="text-gray-400 text-sm">Find matching pairs.</p>
                </div>

                <div class="flex gap-4">
                    <div class="bg-gray-800 px-4 py-2 rounded-xl border border-gray-700 text-center min-w-[80px]">
                        <div class="text-[10px] text-gray-500 uppercase font-bold">Time</div>
                        <div class="text-xl font-bold text-white mono-font" id="stat-time">00:00</div>
                    </div>
                    <div class="bg-gray-800 px-4 py-2 rounded-xl border border-gray-700 text-center min-w-[80px]">
                        <div class="text-[10px] text-gray-500 uppercase font-bold">Moves</div>
                        <div class="text-xl font-bold text-white mono-font" id="stat-moves">0</div>
                    </div>
                    <div class="bg-gray-800 px-4 py-2 rounded-xl border border-gray-700 text-center min-w-[80px]">
                        <div class="text-[10px] text-gray-500 uppercase font-bold">Best</div>
                        <div class="text-xl font-bold text-emerald-400 mono-font" id="stat-best">--</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-2">
                    <button class="mode-btn px-4 py-1 rounded-lg text-xs text-gray-400 active" onclick="setDifficulty('easy')">Easy (4x3)</button>
                    <button class="mode-btn px-4 py-1 rounded-lg text-xs text-gray-400" onclick="setDifficulty('medium')">Medium (4x4)</button>
                    <button class="mode-btn px-4 py-1 rounded-lg text-xs text-gray-400" onclick="setDifficulty('hard')">Hard (6x6)</button>
                </div>
                <button onclick="resetGame()" class="text-xs font-bold text-red-400 hover:text-red-300 underline">Restart</button>
            </div>

            <div id="game-grid" class="grid gap-3 mx-auto transition-all duration-300">
                </div>

        </div>
    </main>

    <div id="win-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 border-2 border-emerald-500 rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl modal-enter">
            <div class="text-6xl mb-4">🏆</div>
            <h2 class="text-2xl font-bold text-white mb-2">You Won!</h2>
            <p class="text-gray-400 text-sm mb-6">Great memory skills.</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-900 p-3 rounded-lg">
                    <div class="text-xs text-gray-500 uppercase">Time</div>
                    <div class="text-xl font-bold text-white" id="modal-time">00:00</div>
                </div>
                <div class="bg-gray-900 p-3 rounded-lg">
                    <div class="text-xs text-gray-500 uppercase">Moves</div>
                    <div class="text-xl font-bold text-white" id="modal-moves">0</div>
                </div>
            </div>

            <button onclick="closeModal()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-1">
                Play Again
            </button>
        </div>
    </div>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- CONFIG ---
        const EMOJIS = [
            '🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', 
            '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔', '🐧', '🐦'
        ];

        const SETTINGS = {
            easy: { rows: 3, cols: 4, pairs: 6 },
            medium: { rows: 4, cols: 4, pairs: 8 },
            hard: { rows: 6, cols: 6, pairs: 18 }
        };

        // --- STATE ---
        let currentDiff = 'easy';
        let cards = []; // { id, emoji, isFlipped, isMatched }
        let flippedCards = []; // Tracks currently flipped [card1, card2]
        let moves = 0;
        let isLocked = false; // Prevents clicking during animation
        let timer = 0;
        let timerInterval = null;
        let gameStarted = false;

        // --- DOM ---
        const gridEl = document.getElementById('game-grid');
        const statMoves = document.getElementById('stat-moves');
        const statTime = document.getElementById('stat-time');
        const statBest = document.getElementById('stat-best');
        const modeBtns = document.querySelectorAll('.mode-btn');
        const modal = document.getElementById('win-modal');
        const modalTime = document.getElementById('modal-time');
        const modalMoves = document.getElementById('modal-moves');

        // --- GAME LOGIC ---

        function setDifficulty(diff) {
            currentDiff = diff;
            
            // Update UI Buttons
            modeBtns.forEach(btn => {
                btn.classList.remove('active');
                if(btn.textContent.toLowerCase().includes(diff)) btn.classList.add('active');
            });

            resetGame();
        }

        function initGrid() {
            const config = SETTINGS[currentDiff];
            
            // Set Grid Columns CSS
            gridEl.className = `grid gap-3 mx-auto`;
            gridEl.style.gridTemplateColumns = `repeat(${config.cols}, minmax(0, 1fr))`;
            gridEl.style.maxWidth = `${config.cols * 80}px`; // Limit width so cards stay nice size

            // Generate Pairs
            let gameEmojis = EMOJIS.slice(0, config.pairs);
            let deck = [...gameEmojis, ...gameEmojis]; // Duplicate
            deck = shuffle(deck); // Shuffle

            // Create State
            cards = deck.map((emoji, index) => ({
                id: index,
                emoji: emoji,
                isFlipped: false,
                isMatched: false
            }));

            // Render
            gridEl.innerHTML = '';
            cards.forEach(card => {
                const cardEl = document.createElement('div');
                cardEl.className = 'card-scene';
                cardEl.dataset.id = card.id;
                cardEl.onclick = () => handleCardClick(card.id);

                cardEl.innerHTML = `
                    <div class="card-inner">
                        <div class="card-face card-front"></div>
                        <div class="card-face card-back">${card.emoji}</div>
                    </div>
                `;
                gridEl.appendChild(cardEl);
            });
        }

        function handleCardClick(id) {
            if (isLocked) return;
            const card = cards[id];

            // Ignore if already flipped or matched
            if (card.isFlipped || card.isMatched) return;

            // Start Timer on first click
            if (!gameStarted) {
                gameStarted = true;
                startTimer();
            }

            // Flip Card
            flipCardVisual(id, true);
            card.isFlipped = true;
            flippedCards.push(id);

            // Check Match logic
            if (flippedCards.length === 2) {
                moves++;
                statMoves.textContent = moves;
                checkMatch();
            }
        }

        function checkMatch() {
            isLocked = true;
            const [id1, id2] = flippedCards;
            const card1 = cards[id1];
            const card2 = cards[id2];

            if (card1.emoji === card2.emoji) {
                // Match!
                card1.isMatched = true;
                card2.isMatched = true;
                markMatchedVisual(id1, id2);
                flippedCards = [];
                isLocked = false;
                checkWin();
            } else {
                // No Match
                setTimeout(() => {
                    flipCardVisual(id1, false);
                    flipCardVisual(id2, false);
                    card1.isFlipped = false;
                    card2.isFlipped = false;
                    flippedCards = [];
                    isLocked = false;
                }, 1000); // 1s delay to see cards
            }
        }

        function checkWin() {
            const allMatched = cards.every(c => c.isMatched);
            if (allMatched) {
                stopTimer();
                saveBestScore();
                setTimeout(showWin, 500);
            }
        }

        // --- VISUALS ---

        function flipCardVisual(id, isFaceUp) {
            const el = document.querySelector(`.card-scene[data-id='${id}']`);
            if (isFaceUp) el.classList.add('flipped');
            else el.classList.remove('flipped');
        }

        function markMatchedVisual(id1, id2) {
            const el1 = document.querySelector(`.card-scene[data-id='${id1}']`);
            const el2 = document.querySelector(`.card-scene[data-id='${id2}']`);
            el1.classList.add('matched');
            el2.classList.add('matched');
        }

        function showWin() {
            modalTime.textContent = formatTime(timer);
            modalMoves.textContent = moves;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            resetGame();
        }

        // --- UTILS ---

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function startTimer() {
            clearInterval(timerInterval);
            timer = 0;
            timerInterval = setInterval(() => {
                timer++;
                statTime.textContent = formatTime(timer);
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
        }

        function formatTime(s) {
            const mins = Math.floor(s / 60).toString().padStart(2, '0');
            const secs = (s % 60).toString().padStart(2, '0');
            return `${mins}:${secs}`;
        }

        function resetGame() {
            stopTimer();
            timer = 0;
            moves = 0;
            gameStarted = false;
            flippedCards = [];
            isLocked = false;
            
            statTime.textContent = "00:00";
            statMoves.textContent = "0";
            
            loadBestScore();
            initGrid();
        }

        // --- LOCAL STORAGE ---

        function saveBestScore() {
            const key = `dibber-memory-best-${currentDiff}`;
            const currentBest = localStorage.getItem(key);
            
            // Score = lower moves is better? Or lower time? 
            // Let's use Moves as primary metric
            if (!currentBest || moves < parseInt(currentBest)) {
                localStorage.setItem(key, moves);
                statBest.textContent = moves;
            }
        }

        function loadBestScore() {
            const key = `dibber-memory-best-${currentDiff}`;
            const best = localStorage.getItem(key);
            statBest.textContent = best ? best : '--';
        }

        // --- INIT ---
        resetGame();

    </script>
</body>
</html>