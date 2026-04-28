<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&family=Patrick+Hand&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .hand-font { font-family: 'Patrick Hand', cursive; }

        /* The Paper Card */
        #bingo-card {
            aspect-ratio: 8.5/11; /* Letter ratio */
            background-color: white;
            color: black;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Grid */
        .bingo-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            flex-grow: 1;
            border-top: 4px solid #000;
            border-left: 4px solid #000;
        }

        .bingo-cell {
            border-right: 4px solid #000;
            border-bottom: 4px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8px;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.2;
            word-wrap: break-word;
            hyphens: auto;
        }

        .bingo-header-cell {
            background-color: #1f2937; /* Dark header for visual contrast on screen */
            color: white;
            font-weight: 900;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 2px solid rgba(255,255,255,0.2);
        }
        .bingo-header-cell:last-child { border-right: none; }

        /* Free Space Star */
        .star-shape {
            font-size: 3rem;
            color: #f59e0b; /* Amber */
            text-shadow: 2px 2px 0 #000;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }

        /* --- PRINT STYLES --- */
        @media print {
            body { 
                background-color: white; 
                color: black; 
                display: block; /* Reset flex */
            }
            header, footer, #controls-panel { display: none !important; }
            main { padding: 0; margin: 0; width: 100%; display: block; }
            
            #preview-container {
                padding: 0;
                width: 100%;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #bingo-card {
                width: 100%;
                max-width: 800px; /* Limit size for A4 */
                box-shadow: none;
                border: 4px solid #000;
                aspect-ratio: auto;
                height: 95vh;
            }

            .bingo-header-cell {
                background-color: #000 !important; /* Force black ink */
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Hide URL headers/footers in modern browsers if possible via margins */
            @page { margin: 0.5cm; }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow flex flex-col lg:flex-row h-[calc(100vh-80px)] overflow-hidden">
        
        <aside id="controls-panel" class="w-full lg:w-96 bg-gray-900 border-r border-gray-700 flex flex-col overflow-y-auto custom-scrollbar p-6 z-10 shadow-xl">
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-amber-400 mb-1">Bingo Generator</h1>
                <p class="text-xs text-gray-400">Create printable cards for events.</p>
            </div>

            <div class="space-y-6 flex-grow">
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Card Title</label>
                    <input type="text" id="card-title" class="w-full bg-gray-800 border border-gray-600 rounded-lg p-3 text-white focus:border-amber-500 outline-none" value="OFFICE BINGO">
                </div>

                <div class="flex-grow flex flex-col">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Words (One per line)</label>
                        <button onclick="loadPreset()" class="text-xs text-emerald-400 hover:text-white underline">Load Sample</button>
                    </div>
                    <textarea id="word-list" class="w-full h-48 bg-gray-800 border border-gray-600 rounded-lg p-3 text-sm text-white focus:border-amber-500 outline-none resize-none custom-scrollbar placeholder-gray-600" placeholder="Enter at least 24 items..."></textarea>
                    <p class="text-[10px] text-gray-500 text-right mt-1"><span id="word-count">0</span> items (Need 24)</p>
                </div>

                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                    <label class="flex items-center gap-2 mb-3 cursor-pointer">
                        <input type="checkbox" id="use-free" checked class="w-4 h-4 rounded bg-gray-700 text-amber-500 border-gray-500 focus:ring-amber-500">
                        <span class="text-sm font-bold text-gray-300">Include Free Space</span>
                    </label>
                    <input type="text" id="free-text" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-2 text-white text-sm" value="FREE SPACE">
                </div>

            </div>

            <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-gray-700">
                <button onclick="generateCard()" class="py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition-colors">
                    🔄 Shuffle
                </button>
                <button onclick="printCard()" class="py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg flex items-center justify-center gap-2 transition-colors">
                    <span>🖨️</span> Print
                </button>
            </div>

        </aside>

        <div id="preview-container" class="flex-grow bg-gray-800 flex items-center justify-center p-4 lg:p-8 overflow-y-auto custom-scrollbar">
            
            <div id="bingo-card" class="w-full max-w-[500px]">
                
                <div class="grid grid-cols-5 h-20 bg-gray-900 border-b-4 border-black">
                    <div class="bingo-header-cell">B</div>
                    <div class="bingo-header-cell">I</div>
                    <div class="bingo-header-cell">N</div>
                    <div class="bingo-header-cell">G</div>
                    <div class="bingo-header-cell">O</div>
                </div>

                <div class="text-center py-2 border-b-4 border-black bg-gray-100">
                    <h2 id="display-title" class="text-2xl font-bold uppercase tracking-widest text-gray-900">OFFICE BINGO</h2>
                </div>

                <div class="bingo-grid" id="grid-container">
                    </div>

                <div class="text-center py-2 text-[10px] uppercase font-bold text-gray-400 bg-white border-t-2 border-black">
                    Generated by Dibber Lab
                </div>

            </div>

        </div>

    </main>

    <script>
        // DOM Elements
        const titleInput = document.getElementById('card-title');
        const displayTitle = document.getElementById('display-title');
        const wordList = document.getElementById('word-list');
        const wordCountEl = document.getElementById('word-count');
        const gridContainer = document.getElementById('grid-container');
        const useFree = document.getElementById('use-free');
        const freeText = document.getElementById('free-text');

        // Sample Data
        const PRESET_WORDS = [
            "Coffee Spill", "Reply All", "Meeting Overrun", "Can't Hear You", 
            "You're Muted", "Screen Frozen", "Awkward Silence", "Bad Pun", 
            "Printer Jam", "Wi-Fi Down", "Forgot Attachment", "Urgent Email", 
            "Lunch Theft", "Fire Drill", "Update Computer", "Lost Pen", 
            "Synergy", "Touch Base", "Circle Back", "Low Hanging Fruit", 
            "Deep Dive", "Ping Me", "Take Offline", "Bandwidth", "Hard Stop"
        ];

        // --- CORE LOGIC ---

        function generateCard() {
            // 1. Update Title
            displayTitle.textContent = titleInput.value || "BINGO";

            // 2. Parse List
            let words = wordList.value.split('\n').map(w => w.trim()).filter(w => w !== "");
            
            // Check count
            wordCountEl.textContent = words.length;
            
            // If empty, use placeholder numbers
            if (words.length === 0) {
                words = Array.from({length: 25}, (_, i) => `${i+1}`);
            }

            // Shuffle (Fisher-Yates)
            for (let i = words.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [words[i], words[j]] = [words[j], words[i]];
            }

            // 3. Fill Grid
            gridContainer.innerHTML = '';
            
            // We need 25 slots (0 to 24)
            // Center is index 12
            
            let wordIndex = 0;

            for (let i = 0; i < 25; i++) {
                const cell = document.createElement('div');
                cell.className = "bingo-cell";

                // Free Space Logic
                if (i === 12 && useFree.checked) {
                    cell.innerHTML = `
                        <div class="flex flex-col items-center">
                            <span class="star-shape">★</span>
                            <span class="text-sm font-black uppercase tracking-tight">${freeText.value}</span>
                        </div>
                    `;
                    cell.style.backgroundColor = "#f3f4f6"; // Slight gray
                } else {
                    // Get word, loop if run out
                    const text = words[wordIndex % words.length];
                    cell.textContent = text;
                    
                    // Add handwriting font randomly for "human" feel? No, keep clean.
                    wordIndex++;
                }

                gridContainer.appendChild(cell);
            }
        }

        function loadPreset() {
            wordList.value = PRESET_WORDS.join('\n');
            generateCard();
        }

        function printCard() {
            window.print();
        }

        // --- LISTENERS ---

        titleInput.addEventListener('input', () => {
            displayTitle.textContent = titleInput.value;
        });

        wordList.addEventListener('input', () => {
            const count = wordList.value.split('\n').filter(w => w.trim() !== "").length;
            wordCountEl.textContent = count;
            if (count >= 24) wordCountEl.className = "text-emerald-400 font-bold";
            else wordCountEl.className = "text-red-400 font-bold";
        });

        useFree.addEventListener('change', generateCard);
        freeText.addEventListener('input', generateCard);

        // Init
        loadPreset();

    </script>
</body>
</html>