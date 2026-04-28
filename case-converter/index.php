<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Editor Font */
        textarea {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        /* Button Styling */
        .case-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .case-btn:hover {
            background-color: #374151;
            border-color: #4b5563;
            transform: translateY(-1px);
        }
        .case-btn:active {
            transform: translateY(0);
            background-color: #f59e0b;
            color: #111827;
            border-color: #f59e0b;
        }

        /* Stats Bar */
        .stat-item {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
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
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Case Converter</h1>
                <p class="text-center text-gray-400">Transform text between Uppercase, CamelCase, Snake_Case & more.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-8 flex flex-col h-[500px]">
                    
                    <div class="flex justify-between items-center mb-2 px-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <span>Input Text</span>
                        <div class="flex gap-4">
                            <span class="stat-item">Words: <span id="word-count" class="text-white">0</span></span>
                            <span class="stat-item">Chars: <span id="char-count" class="text-white">0</span></span>
                        </div>
                    </div>

                    <div class="relative flex-grow">
                        <textarea id="text-input" class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-6 text-gray-300 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar text-lg" placeholder="Type or paste your content here..."></textarea>
                        
                        <div class="absolute top-4 right-4 flex gap-2">
                            <button id="copy-btn" class="bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-lg flex items-center gap-2">
                                <span>📋</span> Copy
                            </button>
                            <button id="clear-btn" class="bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-lg">
                                Clear
                            </button>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-4 flex flex-col gap-6">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-3 block">Standard</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('upper')">UPPERCASE</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('lower')">lowercase</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('sentence')">Sentence case</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('title')">Title Case</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('inverse')">iNVERSE cASE</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-white" onclick="convert('alternating')">aLtErNaTiNg</button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-3 block">Developer / Code</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('camel')">camelCase</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('pascal')">PascalCase</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('snake')">snake_case</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('kebab')">kebab-case</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('constant')">CONST_CASE</button>
                            <button class="case-btn bg-gray-700 p-3 rounded-lg text-sm font-bold text-emerald-300 font-mono" onclick="convert('dot')">dot.case</button>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <button id="download-btn" class="w-full py-3 rounded-xl font-bold bg-amber-600 hover:bg-amber-500 text-white shadow-lg transition-transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>⬇</span> Download Text
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
        const input = document.getElementById('text-input');
        const wordCount = document.getElementById('word-count');
        const charCount = document.getElementById('char-count');
        const copyBtn = document.getElementById('copy-btn');
        const clearBtn = document.getElementById('clear-btn');
        const downloadBtn = document.getElementById('download-btn');

        // --- STATS LOGIC ---
        function updateStats() {
            const text = input.value;
            charCount.textContent = text.length.toLocaleString();
            
            // Basic word count (split by whitespace)
            const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
            wordCount.textContent = words.toLocaleString();
        }

        // --- CONVERSION LOGIC ---
        function convert(type) {
            let text = input.value;
            if(!text) return;

            switch(type) {
                case 'upper':
                    text = text.toUpperCase();
                    break;
                case 'lower':
                    text = text.toLowerCase();
                    break;
                case 'sentence':
                    // Split by sentence delimiters (. ! ?)
                    text = text.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, c => c.toUpperCase());
                    break;
                case 'title':
                    // Capitalize first letter of every word
                    text = text.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
                    break;
                case 'inverse':
                    text = text.split('').map(c => 
                        c === c.toUpperCase() ? c.toLowerCase() : c.toUpperCase()
                    ).join('');
                    break;
                case 'alternating':
                    text = text.split('').map((c, i) => 
                        i % 2 === 0 ? c.toLowerCase() : c.toUpperCase()
                    ).join('');
                    break;
                
                // --- CODE CASES ---
                // These require stripping special chars first
                case 'camel':
                    text = toWords(text).map((w, i) => 
                        i === 0 ? w.toLowerCase() : w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()
                    ).join('');
                    break;
                case 'pascal':
                    text = toWords(text).map(w => 
                        w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()
                    ).join('');
                    break;
                case 'snake':
                    text = toWords(text).join('_').toLowerCase();
                    break;
                case 'kebab':
                    text = toWords(text).join('-').toLowerCase();
                    break;
                case 'constant':
                    text = toWords(text).join('_').toUpperCase();
                    break;
                case 'dot':
                    text = toWords(text).join('.').toLowerCase();
                    break;
            }

            input.value = text;
            updateStats();
        }

        // Helper to split string into words, removing punctuation
        function toWords(str) {
            // Regex matches alphanumeric sequences
            return str.match(/[A-Za-z0-9]+/g) || [];
        }

        // --- LISTENERS ---

        input.addEventListener('input', updateStats);

        clearBtn.addEventListener('click', () => {
            input.value = '';
            updateStats();
            input.focus();
        });

        copyBtn.addEventListener('click', () => {
            if(!input.value) return;
            input.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(input.value);
            
            const orig = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span>✅</span> Copied!`;
            copyBtn.classList.replace('bg-gray-800', 'bg-emerald-600');
            copyBtn.classList.replace('border-gray-600', 'border-emerald-500');
            
            setTimeout(() => {
                copyBtn.innerHTML = orig;
                copyBtn.classList.replace('bg-emerald-600', 'bg-gray-800');
                copyBtn.classList.replace('border-emerald-500', 'border-gray-600');
            }, 1500);
        });

        downloadBtn.addEventListener('click', () => {
            if(!input.value) return;
            const blob = new Blob([input.value], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'converted-text.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

        // Initialize sample text if empty
        if(input.value === '') {
            input.value = "Welcome to Dibber Lab case converter. Try clicking the buttons!";
            updateStats();
        }

    </script>
</body>
</html>