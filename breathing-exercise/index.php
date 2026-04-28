<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4-7-8 Breathing | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* The Breathing Sphere */
        .breath-sphere {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background-color: #374151; /* Gray-700 (Idle state) */
            box-shadow: 0 0 30px rgba(0,0,0,0.2);
            /* Default transition for smooth return to idle */
            transition: all 0.5s ease-out;
            will-change: transform, background-color;
        }

        /* --- Phase States --- */
        /* Use data-attributes to trigger CSS transitions */

        /* Inhale: Grow over 4 seconds */
        .breath-sphere[data-phase="inhale"] {
            transform: scale(2.2);
            background-color: #06b6d4; /* Cyan-500 */
            box-shadow: 0 0 60px rgba(6, 182, 212, 0.4);
            transition: all 4s ease-in-out;
        }

        /* Hold: Stay large, change color quickly */
        .breath-sphere[data-phase="hold"] {
            transform: scale(2.2);
            background-color: #8b5cf6; /* Purple-500 */
            box-shadow: 0 0 60px rgba(139, 92, 246, 0.4);
            transition: background-color 0.5s ease; /* Quick color change, keep scale */
        }

        /* Exhale: Shrink over 8 seconds */
        .breath-sphere[data-phase="exhale"] {
            transform: scale(1);
            background-color: #10b981; /* Emerald-500 */
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.2);
            transition: all 8s ease-in-out;
        }

        /* Text fade effect */
        .phase-text {
            transition: opacity 0.3s;
        }
        .phase-text.fade-out { opacity: 0; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center overflow-hidden">
        <div class="w-full max-w-2xl mx-auto flex flex-col items-center justify-center py-12">
            
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">4-7-8 Breathing</h1>
                <p class="text-gray-400">Inhale for 4s, hold for 7s, exhale for 8s. Relax.</p>
            </div>

            <div class="relative w-96 h-96 flex items-center justify-center mb-8">
                
                <div id="sphere" class="breath-sphere absolute"></div>

                <div class="relative z-10 text-center flex flex-col items-center">
                    <h2 id="instruction-text" class="text-3xl font-bold text-white mb-2 phase-text" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Ready to begin?</h2>
                    <div id="timer-text" class="text-6xl font-black text-white/90 phase-text" style="text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                        </div>
                </div>
            </div>

            <div class="flex gap-4 z-20">
                <button id="start-btn" class="bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-4 rounded-full text-xl font-bold shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                   <span>▶</span> Start Session
                </button>
                <button id="stop-btn" class="hidden bg-gray-800 hover:bg-gray-700 text-red-400 px-8 py-4 rounded-full text-xl font-bold shadow-lg border border-gray-700 transition-all active:scale-95">
                    Stop
                </button>
            </div>
            
            <p id="cycle-count" class="text-gray-500 mt-6 opacity-0 transition-opacity">Cycles completed: 0</p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // Elements
        const sphere = document.getElementById('sphere');
        const instructionText = document.getElementById('instruction-text');
        const timerText = document.getElementById('timer-text');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const cycleCountLabel = document.getElementById('cycle-count');

        // Configuration
        const PHASES = [
            { name: 'inhale', duration: 4, text: "Inhale deeply..." },
            { name: 'hold', duration: 7, text: "Hold breath..." },
            { name: 'exhale', duration: 8, text: "Exhale slowly..." }
        ];

        // State
        let isRunning = false;
        let currentPhaseIndex = 0;
        let timeLeft = 0;
        let cycles = 0;
        
        // Timers references (to allow stopping)
        let phaseTimeout = null;
        let countdownInterval = null;

        // --- CORE LOGIC ---

        function startSession() {
            if (isRunning) return;
            isRunning = true;
            cycles = 0;
            currentPhaseIndex = -1; // Will increment to 0 immediately

            // UI updates
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            cycleCountLabel.classList.add('opacity-0'); // Hide count during warmup

            // Initial warm-up feel before the first inhale
            updateUI("Get ready...", "");
            setTimeout(nextPhase, 1500);
        }

        function stopSession() {
            isRunning = false;
            clear Timers();

            // Reset UI
            sphere.removeAttribute('data-phase');
            updateUI("Session ended.", "");
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
            
            if (cycles > 0) {
               cycleCountLabel.textContent = `Cycles completed: ${cycles}`;
               cycleCountLabel.classList.remove('opacity-0');
            }
        }

        function nextPhase() {
            if (!isRunning) return;

            currentPhaseIndex++;

            // Check if cycle completed
            if (currentPhaseIndex >= PHASES.length) {
                currentPhaseIndex = 0;
                cycles++;
            }

            const phase = PHASES[currentPhaseIndex];
            runPhase(phase);
        }

        function runPhase(phase) {
            // 1. Trigger CSS Transition
            sphere.setAttribute('data-phase', phase.name);
            
            // 2. Set timers
            timeLeft = phase.duration;
            updateUI(phase.text, timeLeft);

            // Start countdown ticker
            countdownInterval = setInterval(() => {
                timeLeft--;
                if (timeLeft > 0) {
                    timerText.textContent = timeLeft;
                } else {
                    clearInterval(countdownInterval);
                    timerText.textContent = ""; // Clear timer right before switch
                }
            }, 1000);

            // Schedule next phase
            phaseTimeout = setTimeout(() => {
                clearInterval(countdownInterval);
                nextPhase();
            }, phase.duration * 1000);
        }

        function updateUI(text, time) {
            // Simple fade effect for smoother text transitions
            instructionText.classList.add('fade-out');
            timerText.classList.add('fade-out');
            
            setTimeout(() => {
                instructionText.textContent = text;
                timerText.textContent = time;
                instructionText.classList.remove('fade-out');
                timerText.classList.remove('fade-out');
            }, 150); // short delay to allow fade out
        }

        function clearTimers() {
            clearTimeout(phaseTimeout);
            clearInterval(countdownInterval);
        }

        // --- LISTENERS ---
        startBtn.addEventListener('click', startSession);
        stopBtn.addEventListener('click', stopSession);

    </script>
</body>
</html>