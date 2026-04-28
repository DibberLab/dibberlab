<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle Clone | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #121213; color: white; }

        /* --- THE GRID --- */
        .board-container {
            display: grid;
            grid-template-rows: repeat(6, 1fr);
            grid-gap: 5px;
            padding: 10px;
            box-sizing: border-box;
            width: 350px;
            height: 420px;
            margin: 0 auto;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 5px;
        }

        .tile {
            width: 100%;
            height: 100%;
            border: 2px solid #3a3a3c;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            font-weight: 700;
            text-transform: uppercase;
            user-select: none;
            line-height: 1;
            color: white; /* Default text color */
        }

        /* --- STATES --- */
        .tile[data-state="active"] {
            border-color: #565758;
            animation: pop 0.1s;
        }
        
        .tile[data-state="correct"] {
            background-color: #538d4e;
            border-color: #538d4e;
        }
        
        .tile[data-state="present"] {
            background-color: #b59f3b;
            border-color: #b59f3b;
        }
        
        .tile[data-state="absent"] {
            background-color: #3a3a3c;
            border-color: #3a3a3c;
        }

        /* --- ANIMATIONS --- */
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .tile.flip {
            animation: flip 0.6s ease forwards;
        }

        @keyframes flip {
            0% { transform: rotateX(0); }
            50% { transform: rotateX(90deg); }
            100% { transform: rotateX(0); }
        }

        .row.shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* --- KEYBOARD --- */
        .keyboard {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 0 8px;
            user-select: none;
        }
        .kb-row {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
            gap: 6px;
        }
        .key {
            font-family: inherit;
            font-weight: bold;
            border: 0;
            padding: 0;
            height: 58px;
            border-radius: 4px;
            cursor: pointer;
            background-color: #818384;
            color: white;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            text-transform: uppercase;
            font-size: 1.25rem;
            transition: background-color 0.2s;
            -webkit-tap-highlight-color: transparent;
        }
        .key.large { flex: 1.5; font-size: 0.75rem; }
        
        .key[data-state="correct"] { background-color: #538d4e; }
        .key[data-state="present"] { background-color: #b59f3b; }
        .key[data-state="absent"] { background-color: #3a3a3c; }

        /* Toast */
        #toast {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: black;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 50;
        }
        #toast.show { opacity: 1; }

    </style>
</head>
<body class="flex flex-col h-screen">

    <header class="flex items-center justify-between px-4 py-2 border-b border-gray-700 h-[50px] shrink-0">
        <div class="w-6"></div> <h1 class="text-2xl font-bold tracking-wider">WORDLE</h1>
        <button onclick="initGame()" class="w-6 h-6 text-gray-400 hover:text-white">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>
    </header>

    <div id="toast"></div>

    <main class="flex-grow flex flex-col justify-center items-center overflow-hidden">
        <div id="board" class="board-container">
            </div>
    </main>

    <div class="keyboard pb-8 shrink-0">
        <div class="kb-row">
            <button class="key" data-key="Q">Q</button><button class="key" data-key="W">W</button><button class="key" data-key="E">E</button><button class="key" data-key="R">R</button><button class="key" data-key="T">T</button><button class="key" data-key="Y">Y</button><button class="key" data-key="U">U</button><button class="key" data-key="I">I</button><button class="key" data-key="O">O</button><button class="key" data-key="P">P</button>
        </div>
        <div class="kb-row">
            <button class="key" data-key="A">A</button><button class="key" data-key="S">S</button><button class="key" data-key="D">D</button><button class="key" data-key="F">F</button><button class="key" data-key="G">G</button><button class="key" data-key="H">H</button><button class="key" data-key="J">J</button><button class="key" data-key="K">K</button><button class="key" data-key="L">L</button>
        </div>
        <div class="kb-row">
            <button class="key large" data-key="ENTER">ENTER</button>
            <button class="key" data-key="Z">Z</button><button class="key" data-key="X">X</button><button class="key" data-key="C">C</button><button class="key" data-key="V">V</button><button class="key" data-key="B">B</button><button class="key" data-key="N">N</button><button class="key" data-key="M">M</button>
            <button class="key large" data-key="BACKSPACE">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path></svg>
            </button>
        </div>
    </div>

    <script>
        // --- DATA ---
        const WORDS = ["APPLE","BEACH","BRAIN","BREAD","BRUSH","CHAIR","CHEST","CHORD","CLICK","CLOCK","CLOUD","DANCE","DIARY","DRINK","DRIVE","EARTH","FEAST","FIELD","FRUIT","GLASS","GRAPE","GREEN","GHOST","GROSS","HEART","HOUSE","IMAGE","JUICE","LIGHT","LEMON","MELON","MONEY","MONTH","MUSIC","NIGHT","OCEAN","PARTY","PHONE","PHOTO","PIZZA","PLANE","PLANT","RADIO","RIVER","SALAD","SHEEP","SHIRT","SHOES","SMILE","SNAKE","SPACE","SPOON","STORM","TABLE","TOAST","TIGER","TOOTH","TOUCH","TRAIN","TRUCK","UNCLE","VIDEO","VOICE","WATER","WATCH","WHALE","WORLD","WRITE","YOUTH","ZEBRA","ABUSE","ADULT","AGENT","ANGER","AWARD","BASIS","BEACH","BIRTH","BLOCK","BLOOD","BOARD","BRAIN","BREAD","BREAK","BROWN","BUYER","CAUSE","CHAIN","CHAIR","CHEST","CHIEF","CHILD","CHINA","CLAIM","CLASS","CLOCK","COACH","COAST","COURT","COVER","CREAM","CRIME","CROSS","CROWD","CROWN","CYCLE","DANCE","DEATH","DEPTH","DOUBT","DRAFT","DRAMA","DREAM","DRESS","DRINK","DRIVE","EARTH","ENEMY","ENTRY","ERROR","EVENT","FAITH","FAULT","FIELD","FINAL","FLOOR","FOCUS","FORCE","FRAME","FRANK","FRONT","FRUIT","GLASS","GRANT","GRASS","GREEN","GROUP","GUIDE","HEART","HENRY","HORSE","HOTEL","HOUSE","IMAGE","INDEX","INPUT","ISSUE","JAPAN","JONES","JUDGE","KNIFE","LAURA","LAYER","LEVEL","LEWIS","LIGHT","LIMIT","LUNCH","MAJOR","MARCH","MATCH","METAL","MODEL","MONEY","MONTH","MOTOR","MOUTH","MUSIC","NIGHT","NOISE","NORTH","NOVEL","NURSE","OFFER","ORDER","OTHER","OWNER","PANEL","PAPER","PARTY","PEACE","PETER","PHASE","PHONE","PIECE","PILOT","PITCH","PLACE","PLANE","PLANT","PLATE","POINT","POUND","POWER","PRESS","PRICE","PRIDE","PRIZE","PROOF","QUEEN","RADIO","RANGE","RATIO","REPLY","RIGHT","RIVER","ROUND","ROUTE","RUGBY","SCALE","SCENE","SCOPE","SCORE","SENSE","SHAPE","SHARE","SHEEP","SHEET","SHIFT","SHIRT","SHOCK","SIGHT","SIMON","SKILL","SLEEP","SMILE","SMITH","SMOKE","SOUND","SOUTH","SPACE","SPEED","SPITE","SPORT","SQUAD","STAFF","STAGE","START","STATE","STEAM","STEEL","STOCK","STONE","STORE","STUDY","STUFF","STYLE","SUGAR","TABLE","TASTE","TERRY","THEME","THING","TITLE","TOTAL","TOUCH","TOWER","TRACK","TRADE","TRAIN","TREND","TRIAL","TRUST","TRUTH","UNCLE","UNION","UNITY","VALUE","VIDEO","VISIT","VOICE","WASTE","WATCH","WATER","WHILE","WHITE","WHOLE","WOMAN","WORLD","YOUTH"];

        // --- STATE ---
        let currentRow = 0;
        let currentGuess = "";
        let targetWord = "";
        let isGameOver = false;
        let isAnimating = false;

        const board = document.getElementById('board');
        const toast = document.getElementById('toast');

        // --- INIT ---
        function initGame() {
            board.innerHTML = '';
            currentRow = 0;
            currentGuess = "";
            isGameOver = false;
            isAnimating = false;
            
            // Pick word and FORCE UPPERCASE just in case
            targetWord = WORDS[Math.floor(Math.random() * WORDS.length)].toUpperCase();
            console.log("Target:", targetWord); 

            // Build Grid (6 rows, 5 cols)
            for (let r = 0; r < 6; r++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'row';
                rowDiv.id = `row-${r}`;
                for (let c = 0; c < 5; c++) {
                    const tileDiv = document.createElement('div');
                    tileDiv.className = 'tile';
                    tileDiv.id = `tile-${r}-${c}`;
                    rowDiv.appendChild(tileDiv);
                }
                board.appendChild(rowDiv);
            }

            // Reset Keyboard
            document.querySelectorAll('.key').forEach(k => {
                k.removeAttribute('data-state');
            });
        }

        // --- LOGIC ---
        function handleInput(key) {
            if (isGameOver || isAnimating) return;
            
            // Normalize key to Uppercase
            key = key.toUpperCase();

            if (key === 'ENTER') {
                submitGuess();
            } else if (key === 'BACKSPACE') {
                deleteLetter();
            } else if (/^[A-Z]$/.test(key)) {
                addLetter(key);
            }
        }

        function addLetter(letter) {
            if (currentGuess.length < 5) {
                const tile = document.getElementById(`tile-${currentRow}-${currentGuess.length}`);
                tile.textContent = letter;
                tile.setAttribute('data-state', 'active');
                currentGuess += letter;
            }
        }

        function deleteLetter() {
            if (currentGuess.length > 0) {
                currentGuess = currentGuess.slice(0, -1);
                const tile = document.getElementById(`tile-${currentRow}-${currentGuess.length}`);
                tile.textContent = '';
                tile.removeAttribute('data-state');
            }
        }

        function submitGuess() {
            if (currentGuess.length !== 5) {
                showToast("Not enough letters");
                shakeRow();
                return;
            }

            isAnimating = true;
            
            const guess = currentGuess; // already uppercase
            const target = targetWord; // already uppercase
            
            const guessArr = guess.split('');
            const targetArr = target.split('');
            const result = new Array(5).fill('absent');

            // 1. Pass: Find Greens (Correct)
            // We do this first to "consume" letters from the target
            for (let i = 0; i < 5; i++) {
                if (guessArr[i] === targetArr[i]) {
                    result[i] = 'correct';
                    targetArr[i] = null; // Mark this letter as matched in target
                    guessArr[i] = null;  // Mark this letter as matched in guess
                }
            }

            // 2. Pass: Find Yellows (Present)
            for (let i = 0; i < 5; i++) {
                // If this position wasn't already marked green...
                if (guessArr[i] !== null) { 
                    const letter = guessArr[i];
                    const indexInTarget = targetArr.indexOf(letter);
                    
                    if (indexInTarget !== -1) {
                        result[i] = 'present';
                        targetArr[indexInTarget] = null; // Consume the yellow letter
                    }
                }
            }

            // 3. Animate Results
            animateReveal(currentGuess, result);
        }

        function animateReveal(guessString, resultArr) {
            const rowId = currentRow;
            
            for (let i = 0; i < 5; i++) {
                setTimeout(() => {
                    const tile = document.getElementById(`tile-${rowId}-${i}`);
                    tile.classList.add('flip');
                    
                    // Update color halfway through flip (300ms is half of 600ms anim)
                    setTimeout(() => {
                        tile.setAttribute('data-state', resultArr[i]);
                        updateKeyboard(guessString[i], resultArr[i]);
                    }, 300);

                }, i * 300); // Stagger tiles by 300ms
            }

            // After animations finish
            setTimeout(() => {
                isAnimating = false;
                
                if (guessString === targetWord) {
                    showToast("Splendid! 🏆");
                    isGameOver = true;
                } else if (currentRow === 5) {
                    showToast(targetWord);
                    isGameOver = true;
                } else {
                    currentRow++;
                    currentGuess = "";
                }
            }, 5 * 300 + 300);
        }

        function updateKeyboard(char, state) {
            const btn = document.querySelector(`.key[data-key="${char}"]`);
            if (!btn) return;
            
            const current = btn.getAttribute('data-state');
            
            if (state === 'correct') {
                btn.setAttribute('data-state', 'correct');
            } else if (state === 'present' && current !== 'correct') {
                btn.setAttribute('data-state', 'present');
            } else if (state === 'absent' && current !== 'correct' && current !== 'present') {
                btn.setAttribute('data-state', 'absent');
            }
        }

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2000);
        }

        function shakeRow() {
            const row = document.getElementById(`row-${currentRow}`);
            row.classList.add('shake');
            setTimeout(() => row.classList.remove('shake'), 500);
        }

        // --- LISTENERS ---
        document.addEventListener('keydown', (e) => {
            const key = e.key.toUpperCase();
            if (key === 'ENTER' || key === 'BACKSPACE' || /^[A-Z]$/.test(key)) {
                handleInput(key);
            }
        });

        document.querySelectorAll('.key').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); // Stop focus zoom
                const key = e.target.closest('.key').dataset.key;
                handleInput(key);
            });
        });

        // Start
        initGame();

    </script>
</body>
</html>