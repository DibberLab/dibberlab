<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Master | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Fredoka', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Grid Layout */
        .math-grid {
            display: grid;
            /* Columns set by JS */
            gap: 2px;
            user-select: none;
        }

        /* Cells */
        .cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.1s;
            cursor: default;
        }

        /* Base Colors */
        .cell-header { background-color: #374151; color: #fbbf24; font-size: 1.1rem; }
        .cell-body { background-color: #1f2937; color: #d1d5db; }

        /* Interactive Highlights */
        .cell-body:hover { transform: scale(1.1); z-index: 10; background-color: white; color: black; box-shadow: 0 0 10px rgba(255,255,255,0.5); }
        
        .highlight-row { background-color: #3b82f6 !important; color: white !important; } /* Blue */
        .highlight-col { background-color: #ef4444 !important; color: white !important; } /* Red */
        .highlight-target { background-color: #10b981 !important; color: white !important; transform: scale(1.2); box-shadow: 0 0 15px #10b981; z-index: 20; }

        /* Mode Toggle */
        .mode-btn {
            transition: all 0.2s;
            border-bottom: 4px solid rgba(0,0,0,0.2);
        }
        .mode-btn:active {
            transform: translateY(2px);
            border-bottom-width: 0;
            margin-bottom: 2px; /* maintain layout */
        }
        .mode-btn.active {
            filter: brightness(1.1);
            transform: translateY(2px);
            border-bottom-width: 0;
            margin-bottom: 2px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Quiz Animation */
        .shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .pop { animation: pop 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28); }
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Custom Scrollbar for table overflow */
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #111827; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center py-8">
        <div class="w-full max-w-5xl">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-amber-400 mb-1">Math Master</h1>
                    <p class="text-gray-400">Multiplication Reference & Quiz.</p>
                </div>

                <div class="flex gap-4 bg-gray-800 p-2 rounded-xl">
                    <button onclick="setMode('table')" id="btn-table" class="mode-btn active px-6 py-2 bg-blue-600 text-white font-bold rounded-lg text-lg">
                        Table
                    </button>
                    <button onclick="setMode('quiz')" id="btn-quiz" class="mode-btn px-6 py-2 bg-emerald-600 text-white font-bold rounded-lg text-lg">
                        Quiz
                    </button>
                </div>
            </div>

            <div id="view-table" class="animate-fade-in">
                
                <div class="mb-6 flex items-center gap-4 bg-gray-800 p-4 rounded-xl border border-gray-700 max-w-md mx-auto">
                    <label class="text-sm font-bold text-gray-400 uppercase">Grid Size:</label>
                    <input type="range" id="grid-size" min="5" max="12" value="10" class="flex-grow accent-amber-500 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer">
                    <span id="size-val" class="font-bold text-amber-400 mono-font text-xl w-12 text-center">10</span>
                </div>

                <div class="overflow-x-auto pb-4">
                    <div id="grid-container" class="math-grid min-w-[600px] mx-auto">
                        </div>
                </div>

                <div class="text-center mt-4 text-sm text-gray-500">
                    Hover over a number to see the calculation.
                </div>
            </div>

            <div id="view-quiz" class="hidden max-w-md mx-auto">
                
                <div class="bg-gray-800 border-4 border-gray-700 rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden">
                    
                    <div class="absolute top-4 right-4 flex items-center gap-1 bg-gray-900 px-3 py-1 rounded-full">
                        <span class="text-amber-400 text-xl">⭐</span>
                        <span id="score" class="font-bold text-white text-lg">0</span>
                    </div>

                    <h2 class="text-gray-400 font-bold uppercase tracking-widest text-sm mb-8">Solve This</h2>

                    <div class="flex items-center justify-center gap-4 text-6xl font-bold text-white mb-8 mono-font">
                        <span id="q-a" class="text-blue-400">?</span>
                        <span class="text-gray-600">×</span>
                        <span id="q-b" class="text-red-400">?</span>
                        <span class="text-gray-600">=</span>
                    </div>

                    <input type="number" id="answer-input" class="w-full bg-gray-900 border-b-4 border-gray-600 rounded-xl p-4 text-center text-4xl font-bold text-white focus:outline-none focus:border-amber-500 mb-6 mono-font" placeholder="?">

                    <button onclick="checkAnswer()" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-black text-xl rounded-xl shadow-[0_4px_0_rgb(6,95,70)] active:shadow-none active:translate-y-1 transition-all">
                        CHECK ANSWER
                    </button>

                    <div id="feedback-msg" class="h-8 mt-4 font-bold text-lg"></div>

                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const viewTable = document.getElementById('view-table');
        const viewQuiz = document.getElementById('view-quiz');
        const btnTable = document.getElementById('btn-table');
        const btnQuiz = document.getElementById('btn-quiz');
        
        const gridContainer = document.getElementById('grid-container');
        const sizeInput = document.getElementById('grid-size');
        const sizeVal = document.getElementById('size-val');

        const qA = document.getElementById('q-a');
        const qB = document.getElementById('q-b');
        const answerInput = document.getElementById('answer-input');
        const scoreEl = document.getElementById('score');
        const feedbackMsg = document.getElementById('feedback-msg');

        // Audio Context
        let audioCtx;

        // State
        let currentSize = 10;
        let score = 0;
        let currentProblem = { a: 0, b: 0 };

        // --- MODE SWITCHING ---

        function setMode(mode) {
            if (mode === 'table') {
                viewTable.classList.remove('hidden');
                viewQuiz.classList.add('hidden');
                btnTable.classList.add('active');
                btnQuiz.classList.remove('active');
            } else {
                viewTable.classList.add('hidden');
                viewQuiz.classList.remove('hidden');
                btnTable.classList.remove('active');
                btnQuiz.classList.add('active');
                generateProblem();
                answerInput.focus();
            }
        }

        // --- TABLE LOGIC ---

        function renderGrid() {
            gridContainer.innerHTML = '';
            
            // Set Grid CSS columns (Size + 1 for header)
            gridContainer.style.gridTemplateColumns = `repeat(${currentSize + 1}, 1fr)`;

            // 1. Top Left Empty
            createCell('×', 'cell-header');

            // 2. Top Header Row
            for (let i = 1; i <= currentSize; i++) {
                createCell(i, 'cell-header', 0, i);
            }

            // 3. Rows
            for (let r = 1; r <= currentSize; r++) {
                // Row Header
                createCell(r, 'cell-header', r, 0);

                // Values
                for (let c = 1; c <= currentSize; c++) {
                    const val = r * c;
                    createCell(val, 'cell-body', r, c);
                }
            }
        }

        function createCell(content, typeClass, r, c) {
            const div = document.createElement('div');
            div.className = `cell ${typeClass}`;
            div.textContent = content;
            
            if (r !== undefined && c !== undefined) {
                div.dataset.row = r;
                div.dataset.col = c;
                
                // Interaction
                div.addEventListener('mouseenter', () => highlight(r, c));
                div.addEventListener('mouseleave', clearHighlight);
            }
            
            gridContainer.appendChild(div);
        }

        function highlight(r, c) {
            if (r === 0 || c === 0) return; // Don't highlight if hovering headers
            
            // Highlight Target
            const target = document.querySelector(`.cell[data-row="${r}"][data-col="${c}"]`);
            if(target) target.classList.add('highlight-target');

            // Highlight Row Header
            const rowHeader = document.querySelector(`.cell[data-row="${r}"][data-col="0"]`);
            if(rowHeader) rowHeader.classList.add('highlight-row');

            // Highlight Col Header
            const colHeader = document.querySelector(`.cell[data-row="0"][data-col="${c}"]`);
            if(colHeader) colHeader.classList.add('highlight-col');
        }

        function clearHighlight() {
            document.querySelectorAll('.highlight-target').forEach(el => el.classList.remove('highlight-target'));
            document.querySelectorAll('.highlight-row').forEach(el => el.classList.remove('highlight-row'));
            document.querySelectorAll('.highlight-col').forEach(el => el.classList.remove('highlight-col'));
        }

        // --- QUIZ LOGIC ---

        function generateProblem() {
            // Pick numbers based on grid size setting (so kids can practice smaller numbers)
            const max = parseInt(sizeInput.value);
            const a = Math.floor(Math.random() * max) + 1;
            const b = Math.floor(Math.random() * max) + 1;
            
            currentProblem = { a, b };
            qA.textContent = a;
            qB.textContent = b;
            answerInput.value = '';
            feedbackMsg.textContent = '';
            feedbackMsg.className = "h-8 mt-4 font-bold text-lg";
        }

        function checkAnswer() {
            const val = parseInt(answerInput.value);
            const correct = currentProblem.a * currentProblem.b;

            if (val === correct) {
                // Correct
                playSound('correct');
                score++;
                scoreEl.textContent = score;
                scoreEl.classList.remove('pop');
                void scoreEl.offsetWidth;
                scoreEl.classList.add('pop');
                
                feedbackMsg.textContent = "Awesome! 🎉";
                feedbackMsg.classList.add("text-emerald-400");
                
                setTimeout(generateProblem, 1000);
            } else {
                // Wrong
                playSound('wrong');
                answerInput.classList.add('shake');
                setTimeout(() => answerInput.classList.remove('shake'), 400);
                
                feedbackMsg.textContent = "Try again!";
                feedbackMsg.classList.add("text-red-400");
                answerInput.value = '';
                answerInput.focus();
            }
        }

        // --- AUDIO ENGINE ---
        function playSound(type) {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            const t = audioCtx.currentTime;

            if (type === 'correct') {
                // Ding (High pitch sine)
                osc.type = 'sine';
                osc.frequency.setValueAtTime(500, t);
                osc.frequency.exponentialRampToValueAtTime(1000, t + 0.1);
                gain.gain.setValueAtTime(0.1, t);
                gain.gain.exponentialRampToValueAtTime(0.01, t + 0.5);
                osc.start(t);
                osc.stop(t + 0.5);
            } else {
                // Buzz (Low square)
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(150, t);
                osc.frequency.linearRampToValueAtTime(100, t + 0.2);
                gain.gain.setValueAtTime(0.1, t);
                gain.gain.linearRampToValueAtTime(0.01, t + 0.3);
                osc.start(t);
                osc.stop(t + 0.3);
            }
        }

        // --- LISTENERS ---

        sizeInput.addEventListener('input', (e) => {
            currentSize = parseInt(e.target.value);
            sizeVal.textContent = currentSize;
            renderGrid();
        });

        answerInput.addEventListener('keydown', (e) => {
            if(e.key === 'Enter') checkAnswer();
        });

        // Init
        renderGrid();

    </script>
</body>
</html>