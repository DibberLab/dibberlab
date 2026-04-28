<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sight Reading Trainer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Music&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The Music Staff Container */
        #staff-container {
            background-color: #fff;
            position: relative;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
            cursor: default;
            user-select: none;
        }

        /* Note Head Animation */
        .note-head {
            transition: fill 0.2s, transform 0.2s;
        }
        .note-correct {
            fill: #10b981 !important; /* Emerald */
            transform: scale(1.2);
        }
        .note-wrong {
            fill: #ef4444 !important; /* Red */
            animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Button Styling */
        .note-btn {
            transition: all 0.1s;
        }
        .note-btn:active {
            transform: scale(0.95);
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Sight Reader</h1>
            <p class="text-center text-gray-400 mb-6">Identify the note displayed on the staff.</p>

            <div class="flex justify-between items-center mb-4 px-2">
                <div class="flex bg-gray-700 rounded-lg p-1">
                    <button id="clef-treble" class="px-3 py-1 rounded-md text-sm font-bold bg-gray-600 text-white shadow-sm transition-all">Treble</button>
                    <button id="clef-bass" class="px-3 py-1 rounded-md text-sm font-bold text-gray-400 hover:text-white transition-all">Bass</button>
                </div>

                <div class="text-right">
                    <span class="text-xs text-gray-500 font-bold uppercase">Streak</span>
                    <div class="text-xl font-mono font-bold text-emerald-400" id="streak-display">0</div>
                </div>
            </div>

            <div id="staff-container" class="w-full h-48 rounded-xl border-4 border-gray-700 mb-8 flex items-center justify-center relative overflow-hidden">
                <svg id="music-svg" width="100%" height="100%" viewBox="0 0 300 150">
                    <g id="staff-lines" stroke="#1f2937" stroke-width="2"></g>
                    <text id="clef-symbol" x="20" y="105" font-size="90" fill="#1f2937" style="font-family: serif;">🎼</text>
                    <g id="active-note"></g>
                </svg>
            </div>

            <div class="grid grid-cols-7 gap-2">
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="C">C</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="D">D</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="E">E</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="F">F</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="G">G</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="A">A</button>
                <button class="note-btn py-4 rounded-lg bg-gray-700 hover:bg-gray-600 border-b-4 border-gray-900 font-bold text-lg" data-note="B">B</button>
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                Turn on volume to hear the notes!
            </p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA ---
        // Range: Relative to center line of staff (0). 
        // Steps are half-lines. +1 is space above center, +2 is line above center.
        
        // Treble Clef: Center Line is B4
        // Lines: E4(-4), G4(-2), B4(0), D5(2), F5(4)
        const trebleNotes = [
            { id: "C", val: -6, freq: 261.63 }, // Middle C (Ledger)
            { id: "D", val: -5, freq: 293.66 },
            { id: "E", val: -4, freq: 329.63 }, // Line
            { id: "F", val: -3, freq: 349.23 },
            { id: "G", val: -2, freq: 392.00 }, // Line
            { id: "A", val: -1, freq: 440.00 },
            { id: "B", val:  0, freq: 493.88 }, // Center Line
            { id: "C", val:  1, freq: 523.25 },
            { id: "D", val:  2, freq: 587.33 }, // Line
            { id: "E", val:  3, freq: 659.25 },
            { id: "F", val:  4, freq: 698.46 }, // Line
            { id: "G", val:  5, freq: 783.99 },
            { id: "A", val:  6, freq: 880.00 }  // Ledger
        ];

        // Bass Clef: Center Line is D3
        // Lines: G2(-4), B2(-2), D3(0), F3(2), A3(4)
        const bassNotes = [
            { id: "E", val: -6, freq: 82.41 },  // Ledger
            { id: "F", val: -5, freq: 87.31 },
            { id: "G", val: -4, freq: 98.00 },  // Line
            { id: "A", val: -3, freq: 110.00 },
            { id: "B", val: -2, freq: 123.47 }, // Line
            { id: "C", val: -1, freq: 130.81 },
            { id: "D", val:  0, freq: 146.83 }, // Center Line
            { id: "E", val:  1, freq: 164.81 },
            { id: "F", val:  2, freq: 174.61 }, // Line
            { id: "G", val:  3, freq: 196.00 },
            { id: "A", val:  4, freq: 220.00 }, // Line
            { id: "B", val:  5, freq: 246.94 },
            { id: "C", val:  6, freq: 261.63 }  // Middle C (Ledger)
        ];

        // DOM Elements
        const staffLinesGroup = document.getElementById('staff-lines');
        const activeNoteGroup = document.getElementById('active-note');
        const clefSymbol = document.getElementById('clef-symbol');
        const btnTreble = document.getElementById('clef-treble');
        const btnBass = document.getElementById('clef-bass');
        const streakDisplay = document.getElementById('streak-display');
        const noteBtns = document.querySelectorAll('.note-btn');

        // State
        let currentClef = 'treble'; // or 'bass'
        let currentNoteData = null;
        let streak = 0;
        let audioCtx;

        // Constants for Drawing
        const centerY = 75; // SVG height is 150, center is 75
        const stepHeight = 10; // Distance between half-steps (line to space)

        function init() {
            drawStaffLines();
            newNote();

            // Event Listeners
            btnTreble.addEventListener('click', () => setClef('treble'));
            btnBass.addEventListener('click', () => setClef('bass'));

            noteBtns.forEach(btn => {
                btn.addEventListener('click', () => checkAnswer(btn.dataset.note));
            });
        }

        function setClef(clef) {
            currentClef = clef;
            
            // Toggle UI
            if (clef === 'treble') {
                btnTreble.className = "px-3 py-1 rounded-md text-sm font-bold bg-gray-600 text-white shadow-sm transition-all";
                btnBass.className = "px-3 py-1 rounded-md text-sm font-bold text-gray-400 hover:text-white transition-all";
                clefSymbol.textContent = "🎼"; // Ideally a proper path, but emoji works for fallback
                clefSymbol.setAttribute('y', '105');
            } else {
                btnBass.className = "px-3 py-1 rounded-md text-sm font-bold bg-gray-600 text-white shadow-sm transition-all";
                btnTreble.className = "px-3 py-1 rounded-md text-sm font-bold text-gray-400 hover:text-white transition-all";
                clefSymbol.textContent = "𝄢"; // Bass Clef unicode
                clefSymbol.setAttribute('y', '95');
            }
            
            streak = 0;
            updateScore();
            newNote();
        }

        function drawStaffLines() {
            staffLinesGroup.innerHTML = '';
            // 5 lines. Center is 0. 
            // Offsets: -2, -1, 0, 1, 2 (times line gap)
            // Line gap is 2 * stepHeight = 20
            
            for (let i = -2; i <= 2; i++) {
                const y = centerY + (i * 20);
                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                line.setAttribute("x1", "0");
                line.setAttribute("y1", y);
                line.setAttribute("x2", "300");
                line.setAttribute("y2", y);
                staffLinesGroup.appendChild(line);
            }
        }

        function newNote() {
            const pool = currentClef === 'treble' ? trebleNotes : bassNotes;
            // Prevent same note twice in a row for better variety
            let next;
            do {
                next = pool[Math.floor(Math.random() * pool.length)];
            } while (currentNoteData && next === currentNoteData);
            
            currentNoteData = next;
            drawNote(currentNoteData.val);
        }

        function drawNote(val) {
            activeNoteGroup.innerHTML = '';
            
            // Note Y Position
            // Positive val = higher pitch = lower Y value
            const cy = centerY - (val * stepHeight);

            // 1. Draw Ledger Lines if needed
            // Staff lines are at -4, -2, 0, 2, 4 (relative steps)
            // If val is > 5 or < -5, we need lines.
            // Actually, any even number outside range needs a line?
            // Top line is val +4. Bottom is val -4.
            
            // Logic: Draw lines for every even number starting from nearest staff line
            if (val >= 6) { // High notes
                 for (let i = 6; i <= val; i += 2) {
                     drawLedger(centerY - (i * stepHeight));
                 }
            } else if (val <= -6) { // Low notes
                 for (let i = -6; i >= val; i -= 2) {
                     drawLedger(centerY - (i * stepHeight));
                 }
            }
            
            // Middle C (val -6 in Treble) usually gets a specific line
            
            // 2. Draw Note Head
            const noteHead = document.createElementNS("http://www.w3.org/2000/svg", "ellipse");
            noteHead.setAttribute("cx", "150");
            noteHead.setAttribute("cy", cy);
            noteHead.setAttribute("rx", "14");
            noteHead.setAttribute("ry", "10");
            noteHead.setAttribute("fill", "#111827"); // Black-ish
            noteHead.setAttribute("transform", `rotate(-20, 150, ${cy})`); // Stylistic tilt
            noteHead.classList.add("note-head");
            
            // 3. Draw Stem
            // If val >= 0 (high on staff), stem goes down. Else up.
            const stem = document.createElementNS("http://www.w3.org/2000/svg", "line");
            stem.setAttribute("stroke", "#111827");
            stem.setAttribute("stroke-width", "2");
            
            if (val >= 0) {
                // Stem Down (left side)
                stem.setAttribute("x1", "137");
                stem.setAttribute("y1", cy + 5);
                stem.setAttribute("x2", "137");
                stem.setAttribute("y2", cy + 60);
            } else {
                // Stem Up (right side)
                stem.setAttribute("x1", "163");
                stem.setAttribute("y1", cy - 5);
                stem.setAttribute("x2", "163");
                stem.setAttribute("y2", cy - 60);
            }

            activeNoteGroup.appendChild(stem);
            activeNoteGroup.appendChild(noteHead);
        }

        function drawLedger(y) {
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute("x1", "130");
            line.setAttribute("y1", y);
            line.setAttribute("x2", "170");
            line.setAttribute("y2", y);
            line.setAttribute("stroke", "#1f2937");
            line.setAttribute("stroke-width", "2");
            activeNoteGroup.appendChild(line);
        }

        async function playTone(freq) {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.type = 'triangle';
            osc.frequency.value = freq;

            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.3, audioCtx.currentTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start();
            osc.stop(audioCtx.currentTime + 1);
        }

        function checkAnswer(guess) {
            const noteHead = activeNoteGroup.querySelector('ellipse');
            
            if (guess === currentNoteData.id) {
                // Correct
                noteHead.classList.add('note-correct');
                playTone(currentNoteData.freq);
                streak++;
                updateScore();
                
                setTimeout(() => {
                    newNote();
                }, 400); // Slight delay to see the green
            } else {
                // Wrong
                noteHead.classList.add('note-wrong');
                playTone(100); // Low error buzz (optional, using low sine)
                
                streak = 0;
                updateScore();
                
                setTimeout(() => {
                    noteHead.classList.remove('note-wrong');
                }, 400);
            }
        }

        function updateScore() {
            streakDisplay.textContent = streak;
        }

        init();
    </script>
</body>
</html>