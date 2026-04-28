<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flip Text | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace Editors for alignment */
        textarea {
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.6;
            font-size: 16px;
        }

        /* Mode Buttons */
        .mode-btn {
            transition: all 0.2s;
            border-bottom: 2px solid transparent;
        }
        .mode-btn:hover { color: white; }
        .mode-btn.active {
            color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            background-color: rgba(245, 158, 11, 0.1);
        }

        /* Output Animation */
        #output-area {
            transition: border-color 0.2s;
        }
        #output-area.flash {
            border-color: #34d399; /* Emerald */
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Upside Down Text</h1>
                <p class="text-center text-gray-400">Flip text, reverse strings, and confuse your friends.</p>
            </div>

            <div class="flex justify-center mb-6">
                <div class="inline-flex bg-gray-800 rounded-lg p-1 border border-gray-700">
                    <button class="mode-btn active px-6 py-2 rounded-md font-bold text-sm text-gray-400" data-mode="flip">Upside Down (Flip)</button>
                    <button class="mode-btn px-6 py-2 rounded-md font-bold text-sm text-gray-400" data-mode="reverse">Reverse Text (Mirror)</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[500px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input</label>
                        <button id="clear-btn" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                    </div>
                    <textarea id="input-area" class="w-full h-full bg-gray-800 text-gray-300 p-6 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Type something normal here..."></textarea>
                </div>

                <div class="flex flex-col h-full relative">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Result</label>
                    </div>
                    
                    <div class="relative flex-grow">
                        <textarea id="output-area" readonly class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-6 text-emerald-400 focus:outline-none resize-none shadow-inner custom-scrollbar" placeholder="...ǝɹǝɥ ɹɐǝddɐ llᴉʍ ʇlnsǝɹ ǝɥ┴"></textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-amber-600 hover:bg-amber-500 text-white p-2 px-4 rounded-lg font-bold text-sm shadow-lg transition-colors flex items-center gap-2">
                            <span>📋</span> Copy
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const input = document.getElementById('input-area');
        const output = document.getElementById('output-area');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        const modeBtns = document.querySelectorAll('.mode-btn');

        let currentMode = 'flip';

        // --- MAPPING DATA ---
        // Mapping normal chars to upside down unicode chars
        const flipMap = {
            'a': 'ɐ', 'b': 'q', 'c': 'ɔ', 'd': 'p', 'e': 'ǝ', 'f': 'ɟ', 'g': 'ƃ', 'h': 'ɥ', 'i': 'ᴉ', 
            'j': 'ɾ', 'k': 'ʞ', 'l': 'l', 'm': 'ɯ', 'n': 'u', 'o': 'o', 'p': 'd', 'q': 'b', 'r': 'ɹ', 
            's': 's', 't': 'ʇ', 'u': 'n', 'v': 'ʌ', 'w': 'ʍ', 'x': 'x', 'y': 'ʎ', 'z': 'z',
            'A': '∀', 'B': '𐐒', 'C': 'Ɔ', 'D': 'p', 'E': 'Ǝ', 'F': 'Ⅎ', 'G': 'פ', 'H': 'H', 'I': 'I', 
            'J': 'ſ', 'K': 'ʞ', 'L': '˥', 'M': 'W', 'N': 'N', 'O': 'O', 'P': 'd', 'Q': 'b', 'R': 'ɹ', 
            'S': 'S', 'T': '┴', 'U': '∩', 'V': 'Λ', 'W': 'M', 'X': 'X', 'Y': '⅄', 'Z': 'Z',
            '1': 'Ɩ', '2': 'ᄅ', '3': 'Ɛ', '4': 'ㄣ', '5': 'ϛ', '6': '9', '7': 'ㄥ', '8': '8', '9': '6', '0': '0',
            '.': '˙', ',': "'", "'": ',', '"': ',,', '`': ',', '?': '¿', '!': '¡', '[': ']', ']': '[', 
            '(': ')', ')': '(', '{': '}', '}': '{', '<': '>', '>': '<', '&': '⅋', '_': '‾'
        };

        // --- LOGIC ---

        function processText() {
            const text = input.value;
            
            if (currentMode === 'reverse') {
                // Simple reverse
                output.value = text.split('').reverse().join('');
            } else {
                // Upside Down (Flip + Reverse)
                let result = '';
                // We iterate backwards to reverse the string order as we map
                for (let i = text.length - 1; i >= 0; i--) {
                    const char = text[i];
                    result += flipMap[char] || char; // Use map or original if not found
                }
                output.value = result;
            }
        }

        // --- LISTENERS ---

        input.addEventListener('input', processText);

        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentMode = btn.dataset.mode;
                processText();
            });
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            output.value = '';
            input.focus();
        });

        copyBtn.addEventListener('click', () => {
            if(!output.value) return;
            output.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(output.value);
            
            const orig = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span>✅</span> Copied!`;
            copyBtn.classList.replace('bg-amber-600', 'bg-emerald-600');
            copyBtn.classList.replace('hover:bg-amber-500', 'hover:bg-emerald-500');
            
            // Flash border effect
            output.classList.add('flash');
            
            setTimeout(() => {
                copyBtn.innerHTML = orig;
                copyBtn.classList.replace('bg-emerald-600', 'bg-amber-600');
                copyBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-amber-500');
                output.classList.remove('flash');
            }, 1500);
        });

        // Initialize sample text
        if (!input.value) {
            input.value = "Hello World!";
            processText();
        }

    </script>
</body>
</html>