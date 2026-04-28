<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tweet Spacer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Textarea */
        .tweet-input {
            background: #1f2937;
            border: 2px solid #374151;
            transition: all 0.2s;
            resize: none;
            color: white;
            line-height: 1.5;
        }
        .tweet-input:focus {
            outline: none;
            border-color: #1d9bf0; /* Twitter Blue */
            background: #111827;
        }

        /* Character Counter Progress */
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        /* Success Animation */
        .success-flash {
            animation: flash 0.5s ease-out;
        }
        @keyframes flash {
            0% { background-color: rgba(16, 185, 129, 0.2); }
            100% { background-color: transparent; }
        }

        /* Invisible Char Highlight (for debugging visuals) */
        .highlight-spaces .invisible-char {
            background-color: rgba(245, 158, 11, 0.2);
            border: 1px dashed #f59e0b;
            border-radius: 2px;
            color: #f59e0b;
            font-size: 0.7em;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-2xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-sky-500">Tweet Spacer</h1>
                <p class="text-gray-400 text-sm">Force vertical spacing in your posts.</p>
            </div>

            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden relative">
                
                <div class="flex justify-between items-center px-4 py-3 bg-gray-900/50 border-b border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="relative w-8 h-8 flex items-center justify-center">
                            <svg class="w-8 h-8" viewBox="0 0 100 100">
                                <circle class="text-gray-700 stroke-current" stroke-width="8" cx="50" cy="50" r="40" fill="transparent"></circle>
                                <circle id="progress-ring" class="text-sky-500 progress-ring__circle stroke-current" stroke-width="8" stroke-linecap="round" cx="50" cy="50" r="40" fill="transparent" stroke-dasharray="251.2" stroke-dashoffset="251.2"></circle>
                            </svg>
                            <span id="char-count" class="absolute text-[10px] font-bold text-gray-400">0</span>
                        </div>
                    </div>

                    <button onclick="clearText()" class="text-xs font-bold text-gray-500 hover:text-red-400 transition-colors">Clear</button>
                </div>

                <textarea id="input-text" class="tweet-input w-full h-64 p-4 text-lg" placeholder="Type your tweet here...

Leave blank lines.

We will fill them with invisible magic so Twitter doesn't collapse them."></textarea>

                <div class="p-4 bg-gray-900 border-t border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between">
                    
                    <div class="flex items-center gap-2">
                        <label class="flex items-center cursor-pointer relative">
                            <input type="checkbox" id="convert-bold" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sky-500"></div>
                            <span class="ml-3 text-xs font-bold text-gray-400 uppercase">Bold Text</span>
                        </label>
                    </div>

                    <button onclick="processAndCopy()" id="copy-btn" class="w-full md:w-auto px-8 py-3 bg-sky-500 hover:bg-sky-400 text-white font-bold rounded-full transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        <span>Convert & Copy</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 flex gap-4 text-xs text-gray-500 bg-gray-800/50 p-4 rounded-xl border border-gray-700/50">
                <div class="min-w-[40px] text-2xl">ℹ️</div>
                <div>
                    <h4 class="font-bold text-gray-300 mb-1">How it works</h4>
                    <p>Twitter ignores empty lines. This tool replaces empty lines with a special invisible character (Braille Pattern Blank U+2800) so your formatting stays exactly how you typed it.</p>
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
        const charCount = document.getElementById('char-count');
        const progressRing = document.getElementById('progress-ring');
        const copyBtn = document.getElementById('copy-btn');
        const convertBold = document.getElementById('convert-bold');

        // Config
        const MAX_CHARS = 280;
        const CIRCLE_CIRCUMFERENCE = 251.2; // 2 * PI * 40
        const INVISIBLE_CHAR = "⠀"; // U+2800 Braille Pattern Blank

        // Font Map for Bold (Mathematical Sans-Serif Bold)
        const BOLD_MAP = {
            'A': '𝗔', 'B': '𝗕', 'C': '𝗖', 'D': '𝗗', 'E': '𝗘', 'F': '𝗙', 'G': '𝗚', 'H': '𝗛', 'I': '𝗜', 'J': '𝗝', 'K': '𝗞', 'L': '𝗟', 'M': '𝗠', 'N': '𝗡', 'O': '𝗢', 'P': '𝗣', 'Q': '𝗤', 'R': '𝗥', 'S': '𝗦', 'T': '𝗧', 'U': '𝗨', 'V': '𝗩', 'W': '𝗪', 'X': '𝗫', 'Y': '𝗬', 'Z': '𝗭',
            'a': '𝗮', 'b': '𝗯', 'c': '𝗰', 'd': '𝗱', 'e': '𝗲', 'f': '𝗳', 'g': '𝗴', 'h': '𝗵', 'i': '𝗶', 'j': '𝗷', 'k': '𝗸', 'l': '𝗹', 'm': '𝗺', 'n': '𝗻', 'o': '𝗼', 'p': '𝗽', 'q': '𝗾', 'r': '𝗿', 's': '𝘀', 't': '𝘁', 'u': '𝘂', 'v': '𝘃', 'w': '𝘄', 'x': '𝘅', 'y': '𝘆', 'z': '𝘇',
            '0': '𝟬', '1': '𝟭', '2': '𝟮', '3': '𝟯', '4': '𝟰', '5': '𝟱', '6': '𝟲', '7': '𝟳', '8': '𝟴', '9': '𝟵'
        };

        // --- LOGIC ---

        function updateCounter() {
            const len = inputText.value.length;
            charCount.textContent = len;

            // Calculate Progress Ring
            const offset = CIRCLE_CIRCUMFERENCE - (len / MAX_CHARS) * CIRCLE_CIRCUMFERENCE;
            progressRing.style.strokeDashoffset = offset;

            // Color Logic
            if (len > MAX_CHARS) {
                progressRing.classList.remove('text-sky-500', 'text-yellow-500');
                progressRing.classList.add('text-red-500');
                charCount.classList.add('text-red-500');
            } else if (len > MAX_CHARS - 20) {
                progressRing.classList.remove('text-sky-500', 'text-red-500');
                progressRing.classList.add('text-yellow-500');
                charCount.classList.remove('text-red-500');
            } else {
                progressRing.classList.add('text-sky-500');
                progressRing.classList.remove('text-red-500', 'text-yellow-500');
                charCount.classList.remove('text-red-500');
            }
        }

        function convertToBold(str) {
            return str.split('').map(char => BOLD_MAP[char] || char).join('');
        }

        function processAndCopy() {
            let raw = inputText.value;

            if (!raw) return;

            // 1. Convert to Bold if checked
            if (convertBold.checked) {
                raw = convertToBold(raw);
            }

            // 2. Process Line Breaks
            // Replace lines that contain only whitespace (or are empty) with the invisible char
            // Split by newline
            const lines = raw.split('\n');
            const processedLines = lines.map(line => {
                // If line is empty or just whitespace
                if (line.trim().length === 0) {
                    return INVISIBLE_CHAR; 
                }
                return line;
            });

            const result = processedLines.join('\n');

            // 3. Copy
            navigator.clipboard.writeText(result).then(() => {
                // Visual Feedback
                const btnContent = copyBtn.innerHTML;
                copyBtn.innerHTML = `<span class="font-bold">Copied!</span>`;
                copyBtn.classList.add('bg-emerald-500');
                copyBtn.classList.remove('bg-sky-500');
                
                setTimeout(() => {
                    copyBtn.innerHTML = btnContent;
                    copyBtn.classList.remove('bg-emerald-500');
                    copyBtn.classList.add('bg-sky-500');
                }, 1500);
            });
        }

        function clearText() {
            inputText.value = "";
            updateCounter();
        }

        // --- LISTENERS ---
        inputText.addEventListener('input', updateCounter);

    </script>
</body>
</html>