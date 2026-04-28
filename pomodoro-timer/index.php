<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Progress Ring */
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        /* Mode Buttons */
        .mode-btn {
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .mode-btn:hover { background-color: rgba(255, 255, 255, 0.1); }
        .mode-btn.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Theme Colors (Transitions) */
        body, .theme-bg { transition: background-color 0.5s ease, color 0.5s ease; }
        .theme-text { transition: color 0.5s ease; }
        .theme-stroke { transition: stroke 0.5s ease; }

        /* Themes */
        .mode-focus { --main-color: #f43f5e; /* Rose-500 */ --bg-color: #111827; }
        .mode-short { --main-color: #10b981; /* Emerald-500 */ --bg-color: #064e3b; }
        .mode-long { --main-color: #3b82f6; /* Blue-500 */ --bg-color: #1e3a8a; }

        /* Play Button Pulse */
        .pulse-hover:hover {
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col transition-colors duration-500" id="app-body">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto">
            
            <div class="flex justify-center bg-gray-800/50 p-1 rounded-full backdrop-blur-sm border border-gray-700/50 mb-8 max-w-xs mx-auto">
                <button class="mode-btn active flex-1 py-2 rounded-full text-sm text-gray-300" onclick="setMode('focus')">Focus</button>
                <button class="mode-btn flex-1 py-2 rounded-full text-sm text-gray-300" onclick="setMode('short')">Short Break</button>
                <button class="mode-btn flex-1 py-2 rounded-full text-sm text-gray-300" onclick="setMode('long')">Long Break</button>
            </div>

            <div class="relative w-72 h-72 mx-auto mb-10 cursor-pointer group" onclick="toggleTimer()">
                
                <svg class="w-full h-full" viewBox="0 0 120 120">
                    <circle 
                        cx="60" cy="60" r="54" 
                        fill="none" 
                        stroke="#374151" 
                        stroke-width="4" 
                    />
                    <circle 
                        id="progress-circle"
                        class="progress-ring__circle theme-stroke"
                        stroke="#f43f5e" 
                        stroke-width="4"
                        fill="none"
                        cx="60" cy="60" r="54"
                    />
                </svg>

                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <div id="timer-display" class="text-6xl font-black mono-font tracking-tight drop-shadow-xl">25:00</div>
                    <div id="status-label" class="text-sm font-bold uppercase tracking-widest opacity-60 mt-2">PAUSED</div>
                </div>

                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="bg-white/10 w-full h-full rounded-full flex items-center justify-center backdrop-blur-[2px]">
                        <span class="text-4xl" id="action-icon">▶</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-4 mb-8">
                <button id="main-btn" class="pulse-hover bg-white text-gray-900 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-lg transition-transform hover:-translate-y-1 active:scale-95" onclick="toggleTimer()">
                    ▶
                </button>
                <button class="bg-gray-800 hover:bg-gray-700 text-gray-300 w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg transition-colors border border-gray-700" onclick="resetTimer()">
                    ↺
                </button>
            </div>

            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-4 text-center">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Current Task</p>
                <input type="text" class="w-full bg-transparent text-center text-lg text-white placeholder-gray-600 focus:outline-none border-b border-transparent focus:border-gray-500 transition-colors" placeholder="What are you working on?">
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <audio id="alarm-sound" src="data:audio/wav;base64,UklGRl9vT1BXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU...."></audio> 
    <script>
        // --- CONFIG & STATE ---
        const MODES = {
            focus: { time: 25 * 60, color: '#f43f5e', bg: '#111827' }, // 25 min
            short: { time: 5 * 60, color: '#10b981', bg: '#064e3b' },  // 5 min
            long: { time: 15 * 60, color: '#3b82f6', bg: '#1e3a8a' }   // 15 min
        };

        let currentMode = 'focus';
        let timeLeft = MODES.focus.time;
        let isRunning = false;
        let timerId = null;
        let originalTitle = document.title;

        // --- DOM ELEMENTS ---
        const timerDisplay = document.getElementById('timer-display');
        const progressCircle = document.getElementById('progress-circle');
        const statusLabel = document.getElementById('status-label');
        const mainBtn = document.getElementById('main-btn');
        const actionIcon = document.getElementById('action-icon');
        const appBody = document.getElementById('app-body');
        const modeBtns = document.querySelectorAll('.mode-btn');

        // SVG Setup
        const radius = progressCircle.r.baseVal.value;
        const circumference = radius * 2 * Math.PI;
        progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
        progressCircle.style.strokeDashoffset = circumference;

        // --- CORE FUNCTIONS ---

        function setMode(mode) {
            if (isRunning) toggleTimer(); // Pause if running
            
            currentMode = mode;
            timeLeft = MODES[mode].time;
            
            // Update UI Colors
            document.documentElement.style.setProperty('--main-color', MODES[mode].color);
            progressCircle.style.stroke = MODES[mode].color;
            // Optional: Change BG drastically
            // appBody.style.backgroundColor = MODES[mode].bg; 
            
            // Update Buttons
            modeBtns.forEach(btn => {
                btn.classList.remove('active');
                if(btn.textContent.toLowerCase().includes(mode.split(' ')[0])) btn.classList.add('active');
            });

            updateDisplay();
        }

        function updateDisplay() {
            // 1. Time Text
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            const timeString = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            
            timerDisplay.textContent = timeString;
            
            // 2. Tab Title
            if (isRunning) {
                document.title = `(${timeString}) Pomodoro`;
            } else {
                document.title = originalTitle;
            }

            // 3. SVG Progress
            const totalTime = MODES[currentMode].time;
            const offset = circumference - (timeLeft / totalTime) * circumference;
            // Reverse direction visual preference: (timeLeft / totalTime) * circumference for countdown effect
            progressCircle.style.strokeDashoffset = offset * -1; // Negative to go counter-clockwise or adjust based on preference
            // Actually, standard countdown:
            // Full offset = empty. 0 offset = full.
            // We want it to be full at start, empty at end.
            // So offset should go from 0 to circumference.
            const offsetStandard = circumference - ((timeLeft / totalTime) * circumference);
            progressCircle.style.strokeDashoffset = offsetStandard;
        }

        function toggleTimer() {
            isRunning = !isRunning;
            
            if (isRunning) {
                // START
                statusLabel.textContent = "RUNNING";
                mainBtn.textContent = "⏸";
                actionIcon.textContent = "⏸";
                
                timerId = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateDisplay();
                    } else {
                        finishTimer();
                    }
                }, 1000);
            } else {
                // PAUSE
                clearInterval(timerId);
                statusLabel.textContent = "PAUSED";
                mainBtn.textContent = "▶";
                actionIcon.textContent = "▶";
                updateDisplay(); // Resets title
            }
        }

        function resetTimer() {
            if (isRunning) toggleTimer(); // Stop
            timeLeft = MODES[currentMode].time;
            updateDisplay();
        }

        function finishTimer() {
            clearInterval(timerId);
            isRunning = false;
            statusLabel.textContent = "COMPLETED";
            mainBtn.textContent = "▶";
            document.title = "🔔 Time's Up!";
            
            // Play Sound
            playSound();
            
            // Pulse Effect
            progressCircle.classList.add('animate-pulse');
            setTimeout(() => progressCircle.classList.remove('animate-pulse'), 3000);
        }

        // --- AUDIO ENGINE ---
        // Simple Beep (Synthesized to save assets)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        
        function playSound() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // High pitch
            oscillator.frequency.exponentialRampToValueAtTime(440, audioCtx.currentTime + 0.5); // Drop pitch
            
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.5);
        }

        // --- INIT ---
        setMode('focus');

    </script>
</body>
</html>