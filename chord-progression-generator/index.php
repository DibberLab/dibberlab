<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chord Progression Generator | Dibber Lab</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        .chord-chip {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .chord-chip.pop {
            animation: pop 0.3s ease;
        }
        @keyframes pop {
            0% { transform: scale(0.85); opacity: 0.4; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-12">
        <div class="w-full max-w-2xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-10">

            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Chord Progression Generator</h1>
            <p class="text-center text-gray-400 mb-8">Pick a key, then roll a random progression for songwriting inspiration.</p>

            <div class="flex flex-col sm:flex-row gap-3 items-center justify-center mb-10">
                <label for="key-select" class="text-sm text-gray-400 font-medium">Key</label>
                <select id="key-select" class="bg-gray-700 border border-gray-600 text-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                </select>

                <button id="generate-btn" class="w-full sm:w-auto px-6 py-2 rounded-lg bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold tracking-wide transition-colors">
                    🎲 Generate
                </button>
            </div>

            <div id="chords-display" class="flex flex-wrap justify-center gap-4 min-h-[6rem] mb-4">
                <span class="text-gray-500 text-lg self-center">Press Generate to roll a progression.</span>
            </div>

            <p id="roman-display" class="text-center text-gray-500 font-mono text-sm tracking-widest h-5"></p>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const NOTES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
        const MAJOR_SCALE_STEPS = [0, 2, 4, 5, 7, 9, 11]; // I ii iii IV V vi vii°
        const DEGREE_QUALITY = ['', 'm', 'm', '', '', 'm', 'dim']; // chord suffix per scale degree
        const DEGREE_ROMAN = ['I', 'ii', 'iii', 'IV', 'V', 'vi', 'vii°'];

        // Common progressions expressed as scale-degree indices (0 = I, 5 = vi, etc.)
        const PROGRESSIONS = [
            [0, 4, 5, 3], // I V vi IV
            [5, 3, 0, 4], // vi IV I V
            [0, 5, 3, 4], // I vi IV V
            [1, 4, 0],    // ii V I
            [0, 3, 4],    // I IV V
            [5, 1, 4, 0], // vi ii V I
            [0, 4, 3],    // I V IV
            [0, 2, 3, 4], // I iii IV V
            [3, 0, 4, 5], // IV I V vi
            [0, 3, 5, 4], // I IV vi V
        ];

        const keySelect = document.getElementById('key-select');
        const generateBtn = document.getElementById('generate-btn');
        const chordsDisplay = document.getElementById('chords-display');
        const romanDisplay = document.getElementById('roman-display');

        // Populate key dropdown
        NOTES.forEach((note, i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = `${note} Major`;
            keySelect.appendChild(opt);
        });
        keySelect.value = 0; // Default to C Major

        function chordName(keyIndex, degree) {
            const noteIndex = (keyIndex + MAJOR_SCALE_STEPS[degree]) % 12;
            return NOTES[noteIndex] + DEGREE_QUALITY[degree];
        }

        function generateProgression() {
            const keyIndex = parseInt(keySelect.value, 10);
            const progression = PROGRESSIONS[Math.floor(Math.random() * PROGRESSIONS.length)];

            chordsDisplay.innerHTML = '';
            progression.forEach((degree, i) => {
                const chip = document.createElement('div');
                chip.className = 'chord-chip pop bg-gray-700 border border-gray-600 rounded-xl px-5 py-4 text-center min-w-[5rem]';
                chip.style.animationDelay = `${i * 60}ms`;
                chip.innerHTML = `<div class="text-2xl font-bold text-gray-100">${chordName(keyIndex, degree)}</div>`;
                chordsDisplay.appendChild(chip);
            });

            romanDisplay.textContent = progression.map(d => DEGREE_ROMAN[d]).join(' – ');
        }

        generateBtn.addEventListener('click', generateProgression);
        keySelect.addEventListener('change', () => {
            // Re-render current roman progression in the new key, if one has been rolled
            if (romanDisplay.textContent) {
                const keyIndex = parseInt(keySelect.value, 10);
                const romanToDegree = Object.fromEntries(DEGREE_ROMAN.map((r, idx) => [r, idx]));
                const currentDegrees = romanDisplay.textContent.split(' – ').map(r => romanToDegree[r]);
                chordsDisplay.innerHTML = '';
                currentDegrees.forEach((degree, i) => {
                    const chip = document.createElement('div');
                    chip.className = 'chord-chip bg-gray-700 border border-gray-600 rounded-xl px-5 py-4 text-center min-w-[5rem]';
                    chip.innerHTML = `<div class="text-2xl font-bold text-gray-100">${chordName(keyIndex, degree)}</div>`;
                    chordsDisplay.appendChild(chip);
                });
            }
        });
    </script>
</body>
</html>
