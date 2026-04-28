<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Chord Library | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        #chord-container {
            transition: transform 0.1s;
            cursor: pointer;
            user-select: none;
        }
        #chord-container:active {
            transform: scale(0.98);
        }

        /* SVG Styles */
        .chord-grid { stroke: #4b5563; stroke-width: 2; }
        .chord-nut { stroke: #9ca3af; stroke-width: 4; }
        .chord-dot { fill: #f59e0b; }
        .chord-barre { fill: #f59e0b; rx: 10; }
        .chord-text { font-family: 'Inter', monospace; fill: #9ca3af; font-size: 14px; text-anchor: middle; }
        .finger-text { fill: #111827; font-size: 12px; font-weight: bold; text-anchor: middle; dominant-baseline: middle; }

        /* Button Styles */
        .selector-btn {
            transition: all 0.2s ease;
        }
        .selector-btn.active {
            background-color: #f59e0b; /* Amber 500 */
            color: #111827; /* Gray 900 */
            font-weight: 700;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
            border-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Chord Library</h1>
            <p class="text-center text-gray-400 mb-8">Tap a note and type to view.</p>

            <div class="mb-6">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Root Note</p>
                <div class="flex flex-wrap gap-2 justify-center" id="root-container">
                    </div>
            </div>

            <div class="mb-8">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Chord Quality</p>
                <div class="flex flex-wrap gap-2 justify-center" id="quality-container">
                    </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="bg-gray-900 rounded-xl p-4 border border-gray-700 flex flex-col items-center justify-center relative min-h-[280px]">
                    <h2 id="chord-title" class="text-2xl font-bold text-white mb-2">C Major</h2>
                    <div id="chord-container" class="w-40 h-48 bg-white rounded-lg p-2 shadow-lg">
                        </div>
                    <p class="text-xs text-gray-500 mt-3">Tap diagram to strum</p>
                </div>

                <div class="flex flex-col justify-center h-full space-y-4">
                    <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700/50 text-sm text-gray-400 text-center">
                        <p>Currently Viewing:</p>
                        <p id="selection-summary" class="text-white font-mono font-bold text-lg mt-1">C - Major</p>
                    </div>
                    
                    <button id="play-btn" class="w-full py-5 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <span>🔊</span> STRUM CHORD
                    </button>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA ---
        // Format: [E, A, D, G, B, e]
        // -1 = Muted (X), 0 = Open
        const roots = ["C", "C#", "D", "Eb", "E", "F", "F#", "G", "Ab", "A", "Bb", "B"];
        const qualities = [
            { id: "maj", name: "Major" },
            { id: "min", name: "Minor" },
            { id: "7", name: "Dom 7" },
            { id: "maj7", name: "Maj 7" },
            { id: "min7", name: "Min 7" },
            { id: "sus4", name: "Sus4" }
        ];

        // Database
        const chordDb = {
            "C_maj": [-1, 3, 2, 0, 1, 0],
            "C_min": [-1, 3, 5, 5, 4, 3],
            "C_7":   [-1, 3, 2, 3, 1, 0],
            "C_maj7":[-1, 3, 2, 0, 0, 0],
            "C_min7":[-1, 3, 5, 3, 4, 3],
            "C_sus4":[-1, 3, 3, 0, 1, 1],

            "C#_maj": [-1, 4, 6, 6, 6, 4],
            "C#_min": [-1, 4, 6, 6, 5, 4], 
            "C#_7":   [-1, 4, 6, 4, 6, 4],
            "C#_maj7":[-1, 4, 6, 5, 6, 4],
            "C#_min7":[-1, 4, 6, 4, 5, 4],
            "C#_sus4":[-1, 4, 6, 6, 7, 4],

            "D_maj": [-1, -1, 0, 2, 3, 2],
            "D_min": [-1, -1, 0, 2, 3, 1],
            "D_7":   [-1, -1, 0, 2, 1, 2],
            "D_maj7":[-1, -1, 0, 2, 2, 2],
            "D_min7":[-1, -1, 0, 2, 1, 1],
            "D_sus4":[-1, -1, 0, 2, 3, 3],

            "Eb_maj": [-1, 6, 8, 8, 8, 6],
            "Eb_min": [-1, 6, 8, 8, 7, 6],
            "Eb_7":   [-1, 6, 8, 6, 8, 6],
            "Eb_maj7":[-1, 6, 8, 7, 8, 6],
            "Eb_min7":[-1, 6, 8, 6, 7, 6],
            "Eb_sus4":[-1, 6, 8, 8, 9, 6],

            "E_maj": [0, 2, 2, 1, 0, 0],
            "E_min": [0, 2, 2, 0, 0, 0],
            "E_7":   [0, 2, 0, 1, 0, 0],
            "E_maj7":[0, 2, 1, 1, 0, 0],
            "E_min7":[0, 2, 0, 0, 0, 0],
            "E_sus4":[0, 2, 2, 2, 0, 0],

            "F_maj": [1, 3, 3, 2, 1, 1],
            "F_min": [1, 3, 3, 1, 1, 1],
            "F_7":   [1, 3, 1, 2, 1, 1],
            "F_maj7":[1, 3, 2, 2, 1, 1],
            "F_min7":[1, 3, 1, 1, 1, 1],
            "F_sus4":[1, 3, 3, 3, 1, 1],

            "F#_maj": [2, 4, 4, 3, 2, 2],
            "F#_min": [2, 4, 4, 2, 2, 2],
            "F#_7":   [2, 4, 2, 3, 2, 2],
            "F#_maj7":[2, 4, 3, 3, 2, 2],
            "F#_min7":[2, 4, 2, 2, 2, 2],
            "F#_sus4":[2, 4, 4, 4, 2, 2],

            "G_maj": [3, 2, 0, 0, 0, 3],
            "G_min": [3, 5, 5, 3, 3, 3],
            "G_7":   [3, 2, 0, 0, 0, 1],
            "G_maj7":[3, 2, 0, 0, 0, 2],
            "G_min7":[3, 5, 3, 3, 3, 3],
            "G_sus4":[3, 5, 5, 5, 3, 3],

            "Ab_maj": [4, 6, 6, 5, 4, 4],
            "Ab_min": [4, 6, 6, 4, 4, 4],
            "Ab_7":   [4, 6, 4, 5, 4, 4],
            "Ab_maj7":[4, 6, 5, 5, 4, 4],
            "Ab_min7":[4, 6, 4, 4, 4, 4],
            "Ab_sus4":[4, 6, 6, 6, 4, 4],

            "A_maj": [-1, 0, 2, 2, 2, 0],
            "A_min": [-1, 0, 2, 2, 1, 0],
            "A_7":   [-1, 0, 2, 0, 2, 0],
            "A_maj7":[-1, 0, 2, 1, 2, 0],
            "A_min7":[-1, 0, 2, 0, 1, 0],
            "A_sus4":[-1, 0, 2, 2, 3, 0],

            "Bb_maj": [-1, 1, 3, 3, 3, 1],
            "Bb_min": [-1, 1, 3, 3, 2, 1],
            "Bb_7":   [-1, 1, 3, 1, 3, 1],
            "Bb_maj7":[-1, 1, 3, 2, 3, 1],
            "Bb_min7":[-1, 1, 3, 1, 2, 1],
            "Bb_sus4":[-1, 1, 3, 3, 4, 1],

            "B_maj": [-1, 2, 4, 4, 4, 2],
            "B_min": [-1, 2, 4, 4, 3, 2],
            "B_7":   [-1, 2, 1, 2, 0, 2],
            "B_maj7":[-1, 2, 4, 3, 4, 2],
            "B_min7":[-1, 2, 4, 2, 3, 2],
            "B_sus4":[-1, 2, 4, 4, 5, 2]
        };

        const openStringFreqs = [82.41, 110.00, 146.83, 196.00, 246.94, 329.63];

        const rootContainer = document.getElementById('root-container');
        const qualityContainer = document.getElementById('quality-container');
        const container = document.getElementById('chord-container');
        const chordTitle = document.getElementById('chord-title');
        const playBtn = document.getElementById('play-btn');
        const selectionSummary = document.getElementById('selection-summary');

        let activeRoot = "C";
        let activeQuality = "maj";
        let audioCtx;

        function init() {
            // Build Root Buttons
            roots.forEach(r => {
                const btn = document.createElement('button');
                btn.className = `selector-btn px-3 py-2 rounded-lg border border-gray-600 bg-gray-700 text-gray-300 hover:bg-gray-600`;
                btn.textContent = r;
                if(r === activeRoot) btn.classList.add('active');
                
                btn.addEventListener('click', () => {
                    activeRoot = r;
                    updateRootUI();
                    renderChord();
                });
                rootContainer.appendChild(btn);
            });
            
            // Build Quality Buttons
            qualities.forEach(q => {
                const btn = document.createElement('button');
                btn.className = `selector-btn px-4 py-2 rounded-full border border-gray-600 bg-gray-700 text-gray-300 hover:bg-gray-600 text-sm`;
                btn.textContent = q.name;
                if(q.id === activeQuality) btn.classList.add('active');
                
                btn.addEventListener('click', () => {
                    activeQuality = q.id;
                    updateQualityUI();
                    renderChord();
                });
                qualityContainer.appendChild(btn);
            });

            // Listeners
            playBtn.addEventListener('click', () => playChord(getCurrentFingering()));
            container.addEventListener('click', () => playChord(getCurrentFingering()));

            // Initial Render
            renderChord();
        }

        function updateRootUI() {
            Array.from(rootContainer.children).forEach(btn => {
                if(btn.textContent === activeRoot) btn.classList.add('active');
                else btn.classList.remove('active');
            });
        }

        function updateQualityUI() {
            // Map the displayed text back to IDs isn't straightforward if we rely on textContent alone
            // Easier to rebuild or store ID in dataset. Let's rely on index logic or rebuild UI:
            // Actually, simplest is to iterate logic again:
            Array.from(qualityContainer.children).forEach((btn, index) => {
                if(qualities[index].id === activeQuality) btn.classList.add('active');
                else btn.classList.remove('active');
            });
        }

        function getCurrentFingering() {
            const key = `${activeRoot}_${activeQuality}`;
            return chordDb[key] || null;
        }

        function renderChord() {
            const fingering = getCurrentFingering();
            const qualName = qualities.find(q => q.id === activeQuality).name;
            
            chordTitle.textContent = `${activeRoot} ${qualName}`;
            selectionSummary.textContent = `${activeRoot} - ${qualName}`;

            if (!fingering) {
                container.innerHTML = `<div class="w-full h-full flex items-center justify-center text-gray-400 text-sm text-center">Diagram<br>unavailable</div>`;
                return;
            }

            // Draw SVG
            const width = 160;
            const height = 190;
            const marginX = 20;
            const marginY = 25;
            const stringGap = (width - (2 * marginX)) / 5;
            const fretGap = 32;

            const frettedNotes = fingering.filter(f => f > 0);
            let minFret = Math.min(...frettedNotes);
            if (frettedNotes.length === 0) minFret = 0; 
            
            let startFret = 1;
            if (minFret > 3) {
                startFret = minFret;
            }

            let svgContent = `<svg width="100%" height="100%" viewBox="0 0 ${width} ${height}">`;

            // Draw Strings
            for (let i = 0; i < 6; i++) {
                const x = marginX + (i * stringGap);
                svgContent += `<line x1="${x}" y1="${marginY}" x2="${x}" y2="${height - marginY}" class="chord-grid" />`;
            }

            // Draw Frets
            for (let i = 0; i <= 5; i++) {
                const y = marginY + (i * fretGap);
                const strokeClass = (i === 0 && startFret === 1) ? "chord-nut" : "chord-grid";
                svgContent += `<line x1="${marginX}" y1="${y}" x2="${width - marginX}" y2="${y}" class="${strokeClass}" />`;
            }

            // Draw Dots
            fingering.forEach((fret, stringIndex) => {
                const x = marginX + (stringIndex * stringGap);
                
                if (fret === -1) {
                    svgContent += `<text x="${x}" y="${marginY - 8}" class="chord-text">✕</text>`;
                } else if (fret === 0) {
                    svgContent += `<text x="${x}" y="${marginY - 8}" class="chord-text">○</text>`;
                } else {
                    const relativeFret = fret - startFret + 1;
                    const y = marginY + (relativeFret * fretGap) - (fretGap / 2);
                    svgContent += `<circle cx="${x}" cy="${y}" r="7" class="chord-dot" />`;
                }
            });

            if (startFret > 1) {
                svgContent += `<text x="0" y="${marginY + (fretGap/2) + 4}" class="finger-text" style="fill:#6b7280; font-size:12px;">${startFret}fr</text>`;
            }

            svgContent += `</svg>`;
            container.innerHTML = svgContent;
        }

        async function playChord(fingering) {
            if (!fingering) return;

            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                await audioCtx.resume();
            }

            const strumSpeed = 0.04;

            fingering.forEach((fret, stringIndex) => {
                if (fret === -1) return;

                const openFreq = openStringFreqs[stringIndex];
                const frequency = openFreq * Math.pow(2, fret / 12);
                
                const startTime = audioCtx.currentTime + (stringIndex * strumSpeed);
                playNote(frequency, startTime);
            });
        }

        function playNote(freq, time) {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.type = 'triangle'; 
            osc.frequency.value = freq;

            gain.gain.setValueAtTime(0, time);
            gain.gain.linearRampToValueAtTime(0.4, time + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, time + 1.5);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start(time);
            osc.stop(time + 2);
        }

        init();
    </script>
</body>
</html>