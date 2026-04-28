<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scale Finder | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Select Styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        /* PIANO CSS */
        .piano-container {
            position: relative;
            height: 160px;
            user-select: none;
        }
        
        .white-key {
            height: 100%;
            background: #e5e7eb; /* gray-200 */
            border-radius: 0 0 6px 6px;
            border: 1px solid #d1d5db;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
        }
        
        .black-key {
            height: 60%;
            background: #1f2937; /* gray-800 */
            border-radius: 0 0 4px 4px;
            position: absolute;
            z-index: 10;
            top: 0;
            transition: all 0.2s;
            border: 1px solid #111827;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        /* Active States (When note is in scale) */
        .white-key.in-scale {
            background: #10b981 !important; /* emerald-500 */
            color: white;
            box-shadow: inset 0 -4px 0 rgba(0,0,0,0.2);
            transform: translateY(2px);
            border-color: #059669;
        }
        
        .black-key.in-scale {
            background: #f59e0b !important; /* amber-500 */
            transform: translateY(2px);
            box-shadow: inset 0 -3px 0 rgba(0,0,0,0.2);
            border-color: #d97706;
        }

        /* Root Note Distinction */
        .white-key.is-root { background: #3b82f6 !important; /* Blue */ }
        .black-key.is-root { background: #3b82f6 !important; /* Blue */ }

    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4">
        <div class="w-full max-w-2xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Scale Finder</h1>
            <p class="text-center text-gray-400 mb-8">Visualize notes for any musical scale.</p>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Root Note</label>
                    <select id="root-select" class="w-full bg-gray-900 border border-gray-600 rounded-lg py-3 px-4 text-white focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                        </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Scale Type</label>
                    <select id="scale-select" class="w-full bg-gray-900 border border-gray-600 rounded-lg py-3 px-4 text-white focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                        </select>
                </div>
            </div>

            <div class="bg-gray-900 rounded-xl p-6 mb-8 border border-gray-700 text-center">
                <p class="text-sm text-gray-500 mb-2 font-mono">NOTES IN SCALE</p>
                <div id="notes-display" class="flex flex-wrap justify-center gap-2 md:gap-4 text-xl md:text-3xl font-bold text-white">
                    </div>
            </div>

            <div class="relative w-full h-40 bg-gray-900 rounded-lg border border-gray-600 overflow-hidden shadow-inner">
                <div id="piano-wrapper" class="w-full h-full flex">
                    </div>
            </div>
            
            <p class="text-center text-xs text-gray-500 mt-4">
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-1 align-middle"></span> Root
                <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full ml-3 mr-1 align-middle"></span> Scale Note
            </p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA ---
        const chromaticNotes = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
        
        // Scale formulas (intervals in semitones from root)
        const scales = {
            "Major (Ionian)": [0, 2, 4, 5, 7, 9, 11],
            "Minor (Natural)": [0, 2, 3, 5, 7, 8, 10],
            "Harmonic Minor": [0, 2, 3, 5, 7, 8, 11],
            "Major Pentatonic": [0, 2, 4, 7, 9],
            "Minor Pentatonic": [0, 3, 5, 7, 10],
            "Blues": [0, 3, 5, 6, 7, 10],
            "Dorian": [0, 2, 3, 5, 7, 9, 10],
            "Phrygian": [0, 1, 3, 5, 7, 8, 10],
            "Lydian": [0, 2, 4, 6, 7, 9, 11],
            "Mixolydian": [0, 2, 4, 5, 7, 9, 10],
            "Locrian": [0, 1, 3, 5, 6, 8, 10],
            "Whole Tone": [0, 2, 4, 6, 8, 10]
        };

        const rootSelect = document.getElementById('root-select');
        const scaleSelect = document.getElementById('scale-select');
        const notesDisplay = document.getElementById('notes-display');
        const pianoWrapper = document.getElementById('piano-wrapper');

        // --- INIT ---
        function init() {
            // Populate Root Select
            chromaticNotes.forEach((note, index) => {
                const opt = document.createElement('option');
                opt.value = index;
                opt.textContent = note;
                if(note === 'C') opt.selected = true;
                rootSelect.appendChild(opt);
            });

            // Populate Scale Select
            Object.keys(scales).forEach(scaleName => {
                const opt = document.createElement('option');
                opt.value = scaleName;
                opt.textContent = scaleName;
                scaleSelect.appendChild(opt);
            });

            // Build Piano HTML
            buildPiano();

            // Calculate Initial
            calculateScale();

            // Listeners
            rootSelect.addEventListener('change', calculateScale);
            scaleSelect.addEventListener('change', calculateScale);
        }

        // --- PIANO BUILDER ---
        // We render 1.5 octaves (C to G) so scales have room to breathe
        function buildPiano() {
            // Pattern: W = White, B = Black
            // We'll generate indices 0-19 (C3 to G4 approx)
            // C, C#, D, D#, E, F, F#, G, G#, A, A#, B ...
            
            const totalKeys = 20; 
            let html = '';
            
            // Calculate widths
            // 12 semitones = 7 white keys. 
            // 20 semitones approx 12 white keys.
            // Using Flexbox for white keys is easiest.
            
            // Generate White Keys first
            let whiteKeyIndex = 0;
            for(let i=0; i<totalKeys; i++) {
                const noteName = chromaticNotes[i % 12];
                if(!noteName.includes('#')) {
                    html += `<div class="white-key flex-1" data-note="${i % 12}" id="key-${i}">${noteName}</div>`;
                    whiteKeyIndex++;
                }
            }
            
            pianoWrapper.innerHTML = html;

            // Generate Black Keys (Absolute Positioned)
            // We need to find the "Left" percentage based on white keys
            let whiteCount = 0;
            const whiteWidthPercent = 100 / 12; // roughly 12 white keys in our range

            for(let i=0; i<totalKeys; i++) {
                const noteName = chromaticNotes[i % 12];
                
                if(noteName.includes('#')) {
                    // It's a black key. It sits between current whiteCount and whiteCount+1
                    // 6% width, offset roughly
                    const leftPos = (whiteCount * whiteWidthPercent) - (whiteWidthPercent / 3);
                    
                    const blackKey = document.createElement('div');
                    blackKey.className = 'black-key';
                    blackKey.style.width = `${whiteWidthPercent * 0.7}%`;
                    blackKey.style.left = `${leftPos}%`;
                    blackKey.dataset.note = i % 12;
                    blackKey.id = `key-${i}`; // Unique ID for highlighting
                    
                    pianoWrapper.appendChild(blackKey);
                } else {
                    whiteCount++;
                }
            }
        }

        // --- CALCULATION LOGIC ---
        function calculateScale() {
            const rootIndex = parseInt(rootSelect.value);
            const scaleType = scaleSelect.value;
            const intervals = scales[scaleType];

            // 1. Calculate Notes
            const activeNotes = []; // Indices 0-11
            const fullNoteNames = [];

            intervals.forEach(interval => {
                const noteIndex = (rootIndex + interval) % 12;
                activeNotes.push(noteIndex);
                fullNoteNames.push(chromaticNotes[noteIndex]);
            });

            // 2. Update Text Display
            notesDisplay.innerHTML = fullNoteNames.map((note, i) => {
                const colorClass = i === 0 ? "text-blue-400" : "text-emerald-400";
                return `<span class="${colorClass}">${note}</span>`;
            }).join('<span class="text-gray-600 mx-2">•</span>');

            // 3. Update Piano
            // Clear all active classes
            document.querySelectorAll('.white-key, .black-key').forEach(el => {
                el.classList.remove('in-scale', 'is-root');
            });

            // Highlight new keys
            // We iterate over all physical keys on the board (0 to 19)
            // and check if their note index matches the scale
            const allKeys = document.querySelectorAll('[id^="key-"]');
            
            allKeys.forEach(key => {
                const noteVal = parseInt(key.dataset.note);
                
                if (activeNotes.includes(noteVal)) {
                    key.classList.add('in-scale');
                    
                    // Specific highlight for Root note
                    if (noteVal === rootIndex) {
                        key.classList.add('is-root');
                    }
                }
            });
        }

        init();
    </script>
</body>
</html>