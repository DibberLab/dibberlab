<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Typing Area */
        #typing-area {
            user-select: none;
            position: relative;
            line-height: 1.8;
            font-size: 1.5rem;
            min-height: 200px;
            transition: opacity 0.2s;
        }
        
        #typing-area.blur-mode {
            filter: blur(4px);
            opacity: 0.5;
        }

        /* Character States */
        .char {
            border-bottom: 2px solid transparent;
            transition: color 0.1s, background-color 0.1s;
        }
        .char.correct { color: #10b981; /* Emerald-500 */ }
        .char.incorrect { 
            color: #ef4444; /* Red-500 */ 
            background-color: rgba(239, 68, 68, 0.2); 
            border-radius: 2px;
        }
        
        /* The Blinking Cursor */
        .char.active {
            border-left: 2px solid #f59e0b; /* Amber-500 */
            animation: blink 1s infinite;
        }
        @keyframes blink {
            50% { border-color: transparent; }
        }

        /* Stat Card */
        .stat-box {
            transition: transform 0.2s;
        }
        .stat-box:hover {
            transform: translateY(-2px);
            border-color: #f59e0b;
        }

        /* Hidden Input */
        #input-field {
            position: absolute;
            z-index: -10;
            opacity: 0;
        }
        
        /* Focus Overlay */
        #focus-overlay {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Typing Speed Test</h1>
                <p class="text-center text-gray-400">Test your WPM and accuracy with 60-second sprints.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                
                <div class="stat-box bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Time Left</span>
                    <div class="text-3xl font-bold text-white mt-1 mono-font"><span id="timer">60</span>s</div>
                </div>

                <div class="stat-box bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">WPM</span>
                    <div class="text-3xl font-bold text-emerald-400 mt-1 mono-font" id="wpm">0</div>
                </div>

                <div class="stat-box bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Accuracy</span>
                    <div class="text-3xl font-bold text-blue-400 mt-1 mono-font"><span id="accuracy">100</span>%</div>
                </div>

                <div class="stat-box bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">CPM</span>
                    <div class="text-3xl font-bold text-gray-400 mt-1 mono-font" id="cpm">0</div>
                </div>

            </div>

            <div class="relative bg-gray-800 border border-gray-700 rounded-2xl p-8 lg:p-12 shadow-2xl overflow-hidden group">
                
                <div id="typing-area" class="mono-font text-gray-500 text-justify break-words blur-mode">
                    </div>

                <input type="text" id="input-field">

                <div id="focus-overlay" class="absolute inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm z-10 transition-opacity">
                    <div class="text-center animate-bounce">
                        <span class="text-4xl block mb-2">⌨️</span>
                        <p class="text-xl font-bold text-white">Click here to start typing</p>
                    </div>
                </div>

                <button onclick="resetGame()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors bg-gray-900/50 p-2 rounded-lg hover:bg-gray-700 z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>

            </div>

            <div class="text-center mt-8 text-xs text-gray-600">
                <p>Pro Tip: Use <strong>Tab</strong> to quickly restart the test.</p>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // Sample Paragraphs
        const paragraphs = [
            "The quick brown fox jumps over the lazy dog. This is a classic pangram used to test typewriters and computer keyboards. It contains every letter of the English alphabet at least once.",
            "Technology is best when it brings people together. It is the art of arranging the world so that we do not have to experience it. The real problem is not whether machines think but whether men do.",
            "Design is not just what it looks like and feels like. Design is how it works. Innovation distinguishes between a leader and a follower. Simplicity is the ultimate sophistication.",
            "To be yourself in a world that is constantly trying to make you something else is the greatest accomplishment. Do not go where the path may lead, go instead where there is no path and leave a trail.",
            "Success is not final, failure is not fatal: it is the courage to continue that counts. You miss 100% of the shots you don't take. Believe you can and you're halfway there."
        ];

        // DOM Elements
        const typingArea = document.getElementById('typing-area');
        const inputField = document.getElementById('input-field');
        const focusOverlay = document.getElementById('focus-overlay');
        
        const elTimer = document.getElementById('timer');
        const elWpm = document.getElementById('wpm');
        const elCpm = document.getElementById('cpm');
        const elAcc = document.getElementById('accuracy');

        // State
        let timer = 60;
        let maxTime = 60;
        let isRunning = false;
        let timerInterval = null;
        let charIndex = 0;
        let mistakes = 0;
        let isFocused = false;

        // --- GAME LOGIC ---

        function loadParagraph() {
            const randIndex = Math.floor(Math.random() * paragraphs.length);
            const text = paragraphs[randIndex];
            
            typingArea.innerHTML = "";
            text.split("").forEach(char => {
                const span = document.createElement("span");
                span.innerText = char;
                span.classList.add("char");
                typingArea.appendChild(span);
            });
            
            // Set first char active
            typingArea.querySelectorAll("span")[0].classList.add("active");
            
            // Focus input
            document.addEventListener('keydown', () => inputField.focus());
            typingArea.addEventListener('click', () => inputField.focus());
        }

        function initTyping() {
            const chars = typingArea.querySelectorAll("span");
            let typedChar = inputField.value.split("")[charIndex];

            // Start Timer on first keystroke
            if (!isRunning && inputField.value.length > 0) {
                isRunning = true;
                timerInterval = setInterval(updateTimer, 1000);
            }

            // Handle Backspace
            if (typedChar == null) {
                if (charIndex > 0) {
                    charIndex--;
                    if (chars[charIndex].classList.contains("incorrect")) {
                        mistakes--;
                    }
                    chars[charIndex].classList.remove("correct", "incorrect");
                    chars[charIndex + 1].classList.remove("active"); // remove from next
                    chars[charIndex].classList.add("active"); // add to current
                }
                updateStats();
                return;
            }

            // Handle Character Check
            if (chars[charIndex].innerText === typedChar) {
                chars[charIndex].classList.add("correct");
            } else {
                mistakes++;
                chars[charIndex].classList.add("incorrect");
            }

            // Move Active Cursor
            chars[charIndex].classList.remove("active");
            charIndex++;
            
            if (charIndex < chars.length) {
                chars[charIndex].classList.add("active");
            } else {
                // Completed paragraph
                clearInterval(timerInterval);
                inputField.value = ""; // Prevent more typing
            }

            updateStats();
        }

        function updateStats() {
            // CPM (Correct chars only? or total? Usually total - mistakes)
            // Let's do Correct Chars
            const correctChars = charIndex - mistakes;
            
            // WPM = (CorrectChars / 5) / TimeElapsed
            // TimeElapsed = (maxTime - timer)
            let timeElapsed = maxTime - timer;
            if (timeElapsed < 1) timeElapsed = 1; // Prevent infinity

            const wpm = Math.round(((correctChars / 5) / timeElapsed) * 60);
            // If wpm < 0 or weird due to start, set 0
            
            // Accuracy
            let accuracy = 0;
            if (charIndex > 0) {
                accuracy = ((correctChars / charIndex) * 100).toFixed(0);
            } else {
                accuracy = 100;
            }

            elWpm.innerText = (wpm < 0 || !isFinite(wpm)) ? 0 : wpm;
            elCpm.innerText = correctChars; // CPM usually just count of chars typed correctly
            elAcc.innerText = accuracy;
        }

        function updateTimer() {
            if (timer > 0) {
                timer--;
                elTimer.innerText = timer;
                updateStats(); // Update live WPM as time ticks
            } else {
                clearInterval(timerInterval);
                inputField.disabled = true;
                typingArea.classList.add('blur-mode');
                focusOverlay.innerHTML = `
                    <div class="text-center">
                        <span class="text-4xl block mb-2">🏁</span>
                        <p class="text-xl font-bold text-white mb-2">Time's Up!</p>
                        <p class="text-emerald-400 font-bold text-2xl">${elWpm.innerText} WPM</p>
                        <button onclick="resetGame()" class="mt-4 bg-amber-500 hover:bg-amber-400 text-black font-bold py-2 px-6 rounded-full transition-colors">Try Again</button>
                    </div>
                `;
                focusOverlay.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        function resetGame() {
            loadParagraph();
            clearInterval(timerInterval);
            timer = maxTime;
            charIndex = 0;
            mistakes = 0;
            isRunning = false;
            
            inputField.value = "";
            inputField.disabled = false;
            
            elTimer.innerText = timer;
            elWpm.innerText = 0;
            elCpm.innerText = 0;
            elAcc.innerText = 100;

            // Reset Overlay to "Click to start"
            typingArea.classList.add('blur-mode');
            focusOverlay.classList.remove('opacity-0', 'pointer-events-none');
            focusOverlay.innerHTML = `
                <div class="text-center animate-bounce">
                    <span class="text-4xl block mb-2">⌨️</span>
                    <p class="text-xl font-bold text-white">Click here to start typing</p>
                </div>
            `;
        }

        // --- LISTENERS ---

        inputField.addEventListener("input", initTyping);

        // Handle overlay click to focus
        focusOverlay.addEventListener("click", () => {
            inputField.focus();
            typingArea.classList.remove('blur-mode');
            focusOverlay.classList.add('opacity-0', 'pointer-events-none');
        });

        // Tab to restart
        document.addEventListener('keydown', (e) => {
            if(e.key === 'Tab') {
                e.preventDefault();
                resetGame();
            }
        });

        // Init
        loadParagraph();

    </script>
</body>
</html>