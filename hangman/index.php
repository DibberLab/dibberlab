<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Keyboard Button Styles */
        .key-btn {
            transition: all 0.1s;
            box-shadow: 0 4px 0 rgba(0,0,0,0.3);
        }
        .key-btn:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 transparent;
        }
        .key-btn:disabled {
            opacity: 0.3;
            transform: translateY(4px);
            box-shadow: 0 0 0 transparent;
            cursor: not-allowed;
            background-color: #1f2937;
            color: #4b5563;
        }

        /* SVG Line Animation */
        .draw-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: draw 0.5s ease-out forwards;
        }
        @keyframes draw {
            to { stroke-dashoffset: 0; }
        }

        /* Shake animation for wrong guess */
        .shake {
            animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            
            <div class="flex flex-col items-center justify-center bg-gray-800 rounded-3xl p-8 border border-gray-700 shadow-2xl relative overflow-hidden h-[400px]">
                
                <div class="absolute top-4 left-4 bg-gray-900 border border-gray-700 px-3 py-1 rounded-full text-xs font-bold text-amber-500 uppercase tracking-wider">
                    Hint: <span id="category-text">...</span>
                </div>

                <svg width="240" height="300" viewBox="0 0 240 300" class="stroke-white stroke-[4] fill-none stroke-linecap-round stroke-linejoin-round drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                    <path d="M20 280 H220 M60 280 V20 H180 V50" class="stroke-gray-600" />
                    
                    <g id="figure-parts">
                        <circle id="part-1" cx="180" cy="80" r="30" class="hidden" />
                        <line id="part-2" x1="180" y1="110" x2="180" y2="200" class="hidden" />
                        <line id="part-3" x1="180" y1="130" x2="140" y2="170" class="hidden" />
                        <line id="part-4" x1="180" y1="130" x2="220" y2="170" class="hidden" />
                        <line id="part-5" x1="180" y1="200" x2="150" y2="250" class="hidden" />
                        <line id="part-6" x1="180" y1="200" x2="210" y2="250" class="hidden" />
                    </g>
                </svg>

                <div id="game-over-overlay" class="absolute inset-0 bg-gray-900/90 backdrop-blur-sm flex flex-col items-center justify-center hidden z-20">
                    <h2 id="end-title" class="text-4xl font-black mb-2 text-white">GAME OVER</h2>
                    <p class="text-gray-400 mb-6">The word was: <span id="reveal-word" class="text-amber-400 font-bold">...</span></p>
                    <button onclick="initGame()" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-gray-900 font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-1">Play Again</button>
                </div>

            </div>

            <div class="flex flex-col gap-8">
                
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-black text-white italic">HANGMAN</h1>
                    <p class="text-gray-500 text-sm font-mono">Save him before it's too late.</p>
                </div>

                <div id="word-container" class="flex flex-wrap gap-2 justify-center lg:justify-start min-h-[60px]">
                    </div>

                <div id="keyboard" class="grid grid-cols-7 gap-2">
                    </div>

                <div class="flex justify-between items-center text-sm font-bold bg-gray-800 p-4 rounded-xl border border-gray-700">
                    <span class="text-gray-400">Mistakes</span>
                    <span class="text-rose-500"><span id="mistake-count">0</span> / 6</span>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // WORD BANK
        const DATA = [
            { word: "JAVASCRIPT", cat: "Coding" },
            { word: "PYTHON", cat: "Coding" },
            { word: "ALGORITHM", cat: "Coding" },
            { word: "DATABASE", cat: "Coding" },
            { word: "VARIABLE", cat: "Coding" },
            { word: "BROWSER", cat: "Tech" },
            { word: "KEYBOARD", cat: "Tech" },
            { word: "MONITOR", cat: "Tech" },
            { word: "INTERNET", cat: "Tech" },
            { word: "WIRELESS", cat: "Tech" },
            { word: "ELEPHANT", cat: "Animals" },
            { word: "GIRAFFE", cat: "Animals" },
            { word: "DOLPHIN", cat: "Animals" },
            { word: "PENGUIN", cat: "Animals" },
            { word: "KANGAROO", cat: "Animals" },
            { word: "GUITAR", cat: "Instruments" },
            { word: "PIANO", cat: "Instruments" },
            { word: "TRUMPET", cat: "Instruments" },
            { word: "VIOLIN", cat: "Instruments" },
            { word: "DRUMS", cat: "Instruments" },
            { word: "PIZZA", cat: "Food" },
            { word: "BURGER", cat: "Food" },
            { word: "SPAGHETTI", cat: "Food" },
            { word: "SUSHI", cat: "Food" },
            { word: "CHOCOLATE", cat: "Food" }
        ];

        // DOM Elements
        const wordContainer = document.getElementById('word-container');
        const keyboard = document.getElementById('keyboard');
        const mistakeCountEl = document.getElementById('mistake-count');
        const categoryText = document.getElementById('category-text');
        const overlay = document.getElementById('game-over-overlay');
        const endTitle = document.getElementById('end-title');
        const revealWord = document.getElementById('reveal-word');

        // State
        let currentWord = "";
        let currentCategory = "";
        let guessedLetters = [];
        let mistakes = 0;
        const maxMistakes = 6;
        let gameStatus = 'playing'; // 'playing', 'won', 'lost'

        // --- GAME LOGIC ---

        function initGame() {
            // 1. Reset State
            const randIndex = Math.floor(Math.random() * DATA.length);
            currentWord = DATA[randIndex].word;
            currentCategory = DATA[randIndex].cat;
            guessedLetters = [];
            mistakes = 0;
            gameStatus = 'playing';

            // 2. Reset UI
            mistakeCountEl.textContent = 0;
            categoryText.textContent = currentCategory;
            overlay.classList.add('hidden');
            
            // Hide SVG parts
            for(let i=1; i<=6; i++) {
                const el = document.getElementById(`part-${i}`);
                el.classList.add('hidden');
                el.classList.remove('draw-path');
            }

            renderWord();
            renderKeyboard();
        }

        function renderWord() {
            wordContainer.innerHTML = '';
            const letters = currentWord.split('');
            
            letters.forEach(letter => {
                const isGuessed = guessedLetters.includes(letter);
                const span = document.createElement('span');
                span.className = `w-10 h-12 flex items-center justify-center border-b-4 ${isGuessed ? 'border-emerald-500 text-white' : 'border-gray-600 text-transparent'} text-3xl font-bold mono-font transition-all`;
                span.textContent = letter;
                wordContainer.appendChild(span);
            });
        }

        function renderKeyboard() {
            keyboard.innerHTML = '';
            // A-Z ASCII codes
            for (let i = 65; i <= 90; i++) {
                const letter = String.fromCharCode(i);
                const btn = document.createElement('button');
                btn.className = "key-btn w-full aspect-square bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg text-lg disabled:bg-gray-800 disabled:text-gray-600";
                btn.textContent = letter;
                
                // Check if already used
                if (guessedLetters.includes(letter)) {
                    btn.disabled = true;
                    // Color code correct/incorrect
                    if (currentWord.includes(letter)) {
                        btn.classList.add('bg-emerald-600', 'text-white');
                        btn.classList.remove('bg-gray-700');
                    }
                }

                btn.onclick = () => handleGuess(letter);
                keyboard.appendChild(btn);
            }
        }

        function handleGuess(letter) {
            if (gameStatus !== 'playing' || guessedLetters.includes(letter)) return;

            guessedLetters.push(letter);

            if (currentWord.includes(letter)) {
                // Correct Guess
                renderWord();
                checkWin();
                updateKeyStyle(letter, true);
            } else {
                // Wrong Guess
                mistakes++;
                mistakeCountEl.textContent = mistakes;
                drawNextPart();
                updateKeyStyle(letter, false);
                checkLoss();
            }
        }

        function updateKeyStyle(letter, isCorrect) {
            // Find button and disable it visually
            const buttons = keyboard.getElementsByTagName('button');
            for(let btn of buttons) {
                if(btn.textContent === letter) {
                    btn.disabled = true;
                    if(isCorrect) {
                        btn.classList.remove('bg-gray-700');
                        btn.classList.add('bg-emerald-600'); // Green for correct
                    }
                    break;
                }
            }
        }

        function drawNextPart() {
            const part = document.getElementById(`part-${mistakes}`);
            if (part) {
                part.classList.remove('hidden');
                part.classList.add('draw-path');
                
                // Shake effect on container
                const container = document.querySelector('svg').parentElement;
                container.classList.remove('shake');
                void container.offsetWidth; // trigger reflow
                container.classList.add('shake');
            }
        }

        function checkWin() {
            const isComplete = currentWord.split('').every(l => guessedLetters.includes(l));
            if (isComplete) {
                gameStatus = 'won';
                endTitle.textContent = "YOU WON!";
                endTitle.className = "text-4xl font-black mb-2 text-emerald-400";
                revealWord.textContent = currentWord;
                setTimeout(() => overlay.classList.remove('hidden'), 500);
            }
        }

        function checkLoss() {
            if (mistakes >= maxMistakes) {
                gameStatus = 'lost';
                endTitle.textContent = "GAME OVER";
                endTitle.className = "text-4xl font-black mb-2 text-rose-500";
                revealWord.textContent = currentWord;
                setTimeout(() => overlay.classList.remove('hidden'), 500);
            }
        }

        // --- LISTENERS ---

        // Physical Keyboard Support
        document.addEventListener('keydown', (e) => {
            if (gameStatus !== 'playing') return;
            const letter = e.key.toUpperCase();
            if (letter.length === 1 && letter >= 'A' && letter <= 'Z') {
                handleGuess(letter);
            }
        });

        // Init
        initGame();

    </script>
</body>
</html>