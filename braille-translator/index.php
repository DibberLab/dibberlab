<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Braille Translator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Braille Output Styling */
        #braille-output {
            line-height: 1.5;
            word-wrap: break-word;
            font-size: 2.5rem; /* Large for visibility */
            letter-spacing: 2px;
        }

        /* Bump Effect for Dots */
        .braille-dot {
            color: #f59e0b; /* Amber */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5), inset 1px 1px 2px rgba(255,255,255,0.2);
            font-weight: bold;
        }

        /* Reference Grid */
        .ref-cell {
            transition: all 0.2s;
        }
        .ref-cell:hover {
            background-color: #374151;
            transform: translateY(-2px);
        }

        /* Custom Scrollbar */
        textarea::-webkit-scrollbar, div::-webkit-scrollbar { width: 8px; }
        textarea::-webkit-scrollbar-track, div::-webkit-scrollbar-track { background: #1f2937; }
        textarea::-webkit-scrollbar-thumb, div::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="flex flex-col h-[600px]">
                
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-amber-400">Braille Translator</h1>
                    <p class="text-gray-400 text-sm">Convert text to Grade 1 Braille (Unicode).</p>
                </div>

                <div class="flex-grow flex flex-col mb-4">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input Text</label>
                        <button onclick="clearAll()" class="text-xs text-red-400 hover:text-white underline">Clear</button>
                    </div>
                    <textarea id="input-text" class="w-full flex-grow bg-gray-800 border border-gray-700 rounded-2xl p-6 text-gray-300 mono-font text-lg focus:outline-none focus:border-amber-500 resize-none transition-colors placeholder-gray-600" placeholder="Type here to translate..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button onclick="copyBraille()" id="copy-btn" class="flex-grow py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        Copy Braille
                    </button>
                </div>

            </div>

            <div class="flex flex-col h-[600px] gap-6">
                
                <div class="h-1/2 bg-gray-900 border-2 border-gray-700 rounded-2xl p-6 relative overflow-hidden flex flex-col">
                    <label class="text-xs font-bold text-amber-500 uppercase mb-2 block">Braille Output</label>
                    
                    <div id="braille-output" class="flex-grow overflow-y-auto custom-scrollbar text-amber-500 break-words">
                        <span class="text-gray-700 text-lg select-none">Waiting for input...</span>
                    </div>
                </div>

                <div class="h-1/2 bg-gray-800 rounded-2xl border border-gray-700 p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-bold text-gray-500 uppercase">Alphabet Reference</label>
                        

[Image of braille alphabet chart]

                    </div>
                    
                    <div class="flex-grow overflow-y-auto custom-scrollbar grid grid-cols-4 sm:grid-cols-6 gap-2 content-start" id="ref-grid">
                        </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const inputText = document.getElementById('input-text');
        const outputText = document.getElementById('braille-output');
        const refGrid = document.getElementById('ref-grid');
        const copyBtn = document.getElementById('copy-btn');

        // Braille Mapping (Unicode Pattern)
        // Note: Number indicator is usually ⠼ followed by letters a-j. Simplistic mapping used here.
        const MAP = {
            'a': '⠁', 'b': '⠃', 'c': '⠉', 'd': '⠙', 'e': '⠑', 
            'f': '⠋', 'g': '⠛', 'h': '⠓', 'i': '⠊', 'j': '⠚', 
            'k': '⠅', 'l': '⠇', 'm': '⠍', 'n': '⠝', 'o': '⠕', 
            'p': '⠏', 'q': '⠟', 'r': '⠗', 's': '⠎', 't': '⠞', 
            'u': '⠥', 'v': '⠧', 'w': '⠺', 'x': '⠭', 'y': '⠽', 'z': '⠵',
            '1': '⠼⠁', '2': '⠼⠃', '3': '⠼⠉', '4': '⠼⠙', '5': '⠼⠑',
            '6': '⠼⠋', '7': '⠼⠛', '8': '⠼⠓', '9': '⠼⠊', '0': '⠼⠚',
            ',': '⠂', ';': '⠆', ':': '⠒', '.': '⠲', '!': '⠖', 
            '(': '⠦', ')': '⠴', '?': '⠦', '"': '⠶', "'": '⠠',
            ' ': '  ', // Double space for visibility
            '\n': '<br>'
        };

        // --- CORE LOGIC ---

        function translate() {
            const raw = inputText.value.toLowerCase();
            
            if (!raw) {
                outputText.innerHTML = '<span class="text-gray-700 text-lg select-none">Waiting for input...</span>';
                return;
            }

            let resultHTML = "";

            for (let char of raw) {
                if (MAP[char]) {
                    if (char === '\n') {
                        resultHTML += '<br>';
                    } else if (char === ' ') {
                        resultHTML += '&nbsp;&nbsp;&nbsp;';
                    } else {
                        // Special handling for numbers: The mapping includes the number sign ⠼
                        // But strictly in grade 1, numbers are NumberSign + Letter a-j.
                        // My map handles this directly.
                        resultHTML += `<span title="${char}">${MAP[char]}</span>`;
                    }
                } else {
                    // Unknown char? Just show a placeholder or skip
                    resultHTML += `<span class="text-gray-600">?</span>`;
                }
            }

            outputText.innerHTML = resultHTML;
        }

        function clearAll() {
            inputText.value = "";
            translate();
            inputText.focus();
        }

        function copyBraille() {
            // Get text content (stripping HTML tags)
            const text = outputText.innerText;
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="text-white">Copied!</span>';
                setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
            });
        }

        // --- REFERENCE GRID ---
        function initReference() {
            // Just A-Z and 0-9
            const chars = "abcdefghijklmnopqrstuvwxyz1234567890".split('');
            
            chars.forEach(char => {
                const braille = MAP[char];
                const div = document.createElement('div');
                div.className = "ref-cell bg-gray-900 rounded-lg p-2 flex flex-col items-center justify-center border border-gray-700 cursor-pointer";
                div.onclick = () => {
                    inputText.value += char;
                    translate();
                };

                div.innerHTML = `
                    <span class="text-xl text-amber-500 mb-1">${braille}</span>
                    <span class="text-xs font-bold text-gray-500 uppercase">${char}</span>
                `;
                refGrid.appendChild(div);
            });
        }

        // --- LISTENERS ---
        inputText.addEventListener('input', translate);

        // Init
        initReference();

    </script>
</body>
</html>