<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Wheel | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The Spinning Canvas */
        #wheel-canvas {
            transition: transform 4s cubic-bezier(0.1, 0.7, 0.1, 1);
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));
        }

        /* Pointer Triangle */
        .pointer {
            width: 0; 
            height: 0; 
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 30px solid white;
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        /* Textarea Styling */
        textarea {
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.5;
        }

        /* Preset Buttons */
        .preset-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .preset-btn:hover { background-color: #374151; border-color: #4b5563; }
        
        /* Modal Animation */
        .modal-enter { animation: popIn 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28); }
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col overflow-x-hidden">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-xs font-bold text-gray-500 uppercase">Options (One per line)</label>
                        <button onclick="clearList()" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                    </div>
                    
                    <textarea id="input-area" class="w-full h-64 bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-sm focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Pizza
Burgers
Sushi
Tacos
Salad"></textarea>
                    
                    <button id="update-btn" class="w-full mt-4 py-3 rounded-xl font-bold bg-gray-700 hover:bg-gray-600 text-white transition-colors border border-gray-600">
                        Update Wheel
                    </button>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-3 block">Quick Presets</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="preset-btn bg-gray-800 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="loadPreset('yesno')">Yes / No</button>
                        <button class="preset-btn bg-gray-800 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="loadPreset('food')">What to Eat?</button>
                        <button class="preset-btn bg-gray-800 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="loadPreset('dice')">Dice (1-6)</button>
                        <button class="preset-btn bg-gray-800 rounded-lg py-2 text-xs font-bold text-gray-400" onclick="loadPreset('week')">Day of Week</button>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-8 flex flex-col items-center justify-center min-h-[500px]">
                
                <div class="relative w-full max-w-[500px] aspect-square">
                    <div class="pointer"></div>
                    
                    <canvas id="wheel-canvas" width="1000" height="1000" class="w-full h-full rounded-full"></canvas>
                    
                    <button id="spin-btn" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-white rounded-full shadow-2xl border-4 border-gray-200 flex items-center justify-center z-20 hover:scale-105 active:scale-95 transition-transform group">
                        <span class="text-gray-900 font-black text-sm uppercase tracking-widest group-hover:text-amber-500 transition-colors">SPIN</span>
                    </button>
                </div>

            </div>

        </div>
    </main>

    <div id="winner-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closeModal()">
        <div class="bg-gray-800 border-2 border-amber-500 rounded-2xl p-8 max-w-md w-full text-center shadow-2xl modal-enter relative overflow-hidden" onclick="event.stopPropagation()">
            
            <canvas id="confetti-canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

            <div class="relative z-10">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-gray-400 font-bold uppercase text-sm tracking-widest mb-2">The Winner Is</h2>
                <div id="winner-text" class="text-4xl md:text-5xl font-black text-white mb-8 break-words leading-tight">
                    Pizza
                </div>
                <button onclick="closeModal()" class="px-8 py-3 bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold rounded-full shadow-lg transition-transform hover:-translate-y-1">
                    Awesome!
                </button>
            </div>
        </div>
    </div>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const canvas = document.getElementById('wheel-canvas');
        const ctx = canvas.getContext('2d');
        const inputArea = document.getElementById('input-area');
        const spinBtn = document.getElementById('spin-btn');
        const updateBtn = document.getElementById('update-btn');
        const modal = document.getElementById('winner-modal');
        const winnerText = document.getElementById('winner-text');

        // Config
        const COLORS = [
            '#F59E0B', // Amber
            '#10B981', // Emerald
            '#3B82F6', // Blue
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#6366F1'  // Indigo
        ];

        // State
        let items = [];
        let currentRotation = 0;
        let isSpinning = false;

        // --- CORE FUNCTIONS ---

        function parseInput() {
            const raw = inputArea.value;
            items = raw.split('\n').map(line => line.trim()).filter(line => line !== "");
            if(items.length === 0) items = ["Add Options", "To Input"];
            drawWheel();
        }

        function drawWheel() {
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = canvas.width / 2;
            const sliceAngle = (2 * Math.PI) / items.length;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            items.forEach((item, i) => {
                const startAngle = i * sliceAngle - (Math.PI / 2); // Start at top (-90deg)
                const endAngle = startAngle + sliceAngle;

                // 1. Draw Slice
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.closePath();
                ctx.fillStyle = COLORS[i % COLORS.length];
                ctx.fill();
                ctx.stroke();

                // 2. Draw Text
                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(startAngle + sliceAngle / 2);
                ctx.textAlign = "right";
                ctx.fillStyle = "white";
                ctx.font = "bold 40px Inter"; // High res font for canvas size
                ctx.shadowColor = "rgba(0,0,0,0.5)";
                ctx.shadowBlur = 4;
                ctx.fillText(item, radius - 40, 10);
                ctx.restore();
            });
        }

        function spin() {
            if (isSpinning) return;
            isSpinning = true;
            spinBtn.disabled = true;

            // Calculate random spin
            // Minimum 5 full rotations (360 * 5) + random offset
            const extraSpins = 360 * 5; 
            const randomOffset = Math.floor(Math.random() * 360);
            const totalRotate = extraSpins + randomOffset;
            
            // Add to current rotation so it spins forward from where it is
            currentRotation += totalRotate;

            // Apply CSS Transform
            canvas.style.transform = `rotate(${currentRotation}deg)`;

            // Wait for transition end (4s matched in CSS)
            setTimeout(() => {
                isSpinning = false;
                spinBtn.disabled = false;
                determineWinner(currentRotation);
            }, 4000);
        }

        function determineWinner(rotation) {
            // Normalize rotation to 0-360
            const actualRotation = rotation % 360;
            
            // Pointer is at Top (0 visual degrees, but rotation moves the wheel clockwise)
            // If wheel rotates 10 degrees clockwise, the slice at 350 degrees is now at top.
            // Formula: (360 - rotation) % 360 = The angle of the slice at the top index 0
            const degreeAtPointer = (360 - actualRotation) % 360;
            
            const sliceSize = 360 / items.length;
            const winningIndex = Math.floor(degreeAtPointer / sliceSize);
            
            const winner = items[winningIndex];
            showWinner(winner);
        }

        // --- MODAL & CONFETTI ---

        function showWinner(text) {
            winnerText.textContent = text;
            modal.classList.remove('hidden');
            startConfetti();
        }

        function closeModal() {
            modal.classList.add('hidden');
            stopConfetti();
        }

        // --- PRESETS ---

        function loadPreset(type) {
            let list = "";
            switch(type) {
                case 'yesno': list = "Yes\nNo"; break;
                case 'food': list = "Pizza\nBurgers\nSushi\nTacos\nSalad\nThai\nIndian"; break;
                case 'dice': list = "1\n2\n3\n4\n5\n6"; break;
                case 'week': list = "Monday\nTuesday\nWednesday\nThursday\nFriday\nSaturday\nSunday"; break;
            }
            inputArea.value = list;
            parseInput();
        }

        function clearList() {
            inputArea.value = "";
            parseInput();
        }

        // --- LISTENERS ---

        updateBtn.addEventListener('click', parseInput);
        
        spinBtn.addEventListener('click', spin);
        
        // Auto update on type (debounced slightly)
        let debounce;
        inputArea.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(parseInput, 500);
        });

        // --- SIMPLE CONFETTI ENGINE ---
        let confettiCtx = document.getElementById('confetti-canvas').getContext('2d');
        let particles = [];
        let animationId;

        function startConfetti() {
            const canvas = document.getElementById('confetti-canvas');
            canvas.width = canvas.clientWidth;
            canvas.height = canvas.clientHeight;
            particles = [];
            
            for(let i=0; i<100; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    color: COLORS[Math.floor(Math.random() * COLORS.length)],
                    size: Math.random() * 10 + 5,
                    speedY: Math.random() * 3 + 2,
                    speedX: Math.random() * 2 - 1
                });
            }
            loopConfetti();
        }

        function loopConfetti() {
            const canvas = document.getElementById('confetti-canvas');
            confettiCtx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(p => {
                p.y += p.speedY;
                p.x += p.speedX;
                if(p.y > canvas.height) p.y = -20;
                
                confettiCtx.fillStyle = p.color;
                confettiCtx.fillRect(p.x, p.y, p.size, p.size);
            });
            
            animationId = requestAnimationFrame(loopConfetti);
        }

        function stopConfetti() {
            cancelAnimationFrame(animationId);
            confettiCtx.clearRect(0,0, 1000, 1000);
        }

        // Init
        parseInput();

    </script>
</body>
</html>