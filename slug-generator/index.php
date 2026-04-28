<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slug Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace Editors */
        textarea, input[type="text"] {
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.6;
            font-size: 14px;
        }

        /* Toggle Checkbox */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Separator Buttons */
        .sep-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .sep-btn:hover { background-color: #374151; }
        .sep-btn.active {
            background-color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            color: #111827;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">URL Slug Generator</h1>
                <p class="text-center text-gray-400">Convert titles into SEO-friendly URLs.</p>
            </div>

            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6 flex flex-wrap gap-6 items-center justify-between shadow-lg">
                
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 uppercase">Separator</span>
                    <div class="flex bg-gray-900 rounded-lg p-1 border border-gray-600">
                        <button class="sep-btn active px-3 py-1 rounded text-xs" data-sep="-">Hyphen (-)</button>
                        <button class="sep-btn px-3 py-1 rounded text-xs" data-sep="_">Underscore (_)</button>
                        <button class="sep-btn px-3 py-1 rounded text-xs" data-sep="">None</button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-6">
                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-stopwords" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-stopwords" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300">Remove Stop Words</span>
                            <span class="text-[10px] text-gray-500">(a, the, and, of...)</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-numbers" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-numbers" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Strip Numbers</span>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[400px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input Text</label>
                        <button id="clear-btn" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                    </div>
                    <textarea id="input-area" class="w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Paste article titles or text here...
10 Ways to Cook an Egg!
Why is the sky blue?"></textarea>
                </div>

                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Generated Slugs</label>
                        <span id="count-display" class="text-xs font-mono text-emerald-400">0 slugs</span>
                    </div>
                    <div class="relative flex-grow">
                        <textarea id="output-area" readonly class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-emerald-400 focus:outline-none resize-none shadow-inner custom-scrollbar" placeholder="Result will appear here..."></textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-amber-600 hover:bg-amber-500 text-white p-2 rounded-lg font-bold text-sm shadow-lg transition-colors border border-amber-400 flex items-center gap-2">
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
        const inputArea = document.getElementById('input-area');
        const outputArea = document.getElementById('output-area');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        const countDisplay = document.getElementById('count-display');
        
        const sepBtns = document.querySelectorAll('.sep-btn');
        const optStopwords = document.getElementById('opt-stopwords');
        const optNumbers = document.getElementById('opt-numbers');

        // Common SEO Stop Words (English)
        const stopWords = new Set([
            "a", "an", "the", "and", "but", "or", "nor", "for", "so", "yet", 
            "at", "by", "in", "of", "on", "to", "up", "is", "it", "this", "that"
        ]);

        let separator = '-';

        // --- CORE LOGIC ---

        function generateSlugs() {
            const raw = inputArea.value;
            if (!raw) {
                outputArea.value = '';
                countDisplay.textContent = '0 slugs';
                return;
            }

            const lines = raw.split(/\r?\n/);
            const slugs = lines.map(line => processLine(line)).filter(s => s.length > 0);

            outputArea.value = slugs.join('\n');
            countDisplay.textContent = `${slugs.length} slug${slugs.length !== 1 ? 's' : ''}`;
        }

        function processLine(text) {
            if (!text.trim()) return '';

            // 1. Lowercase
            let slug = text.toLowerCase();

            // 2. Normalize (Handle accents: Café -> Cafe)
            slug = slug.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            // 3. Strip numbers if requested
            if (optNumbers.checked) {
                slug = slug.replace(/[0-9]/g, '');
            }

            // 4. Remove special chars (keep alphanumeric and whitespace)
            slug = slug.replace(/[^a-z0-9\s]/g, '');

            // 5. Split into words
            let words = slug.split(/\s+/);

            // 6. Filter Stop Words
            if (optStopwords.checked) {
                words = words.filter(w => !stopWords.has(w));
            }

            // 7. Join with separator
            // Filter empty strings caused by extra spaces or removed numbers
            return words.filter(w => w).join(separator);
        }

        // --- LISTENERS ---

        inputArea.addEventListener('input', generateSlugs);

        // Separator Buttons
        sepBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                sepBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                separator = btn.dataset.sep;
                generateSlugs();
            });
        });

        // Toggles
        optStopwords.addEventListener('change', generateSlugs);
        optNumbers.addEventListener('change', generateSlugs);

        // Clear
        clearBtn.addEventListener('click', () => {
            inputArea.value = '';
            generateSlugs();
            inputArea.focus();
        });

        // Copy
        copyBtn.addEventListener('click', () => {
            if(!outputArea.value) return;
            outputArea.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(outputArea.value);
            
            const orig = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span>✅</span> Copied!`;
            copyBtn.classList.replace('bg-amber-600', 'bg-emerald-600');
            copyBtn.classList.replace('border-amber-400', 'border-emerald-500');
            
            setTimeout(() => {
                copyBtn.innerHTML = orig;
                copyBtn.classList.replace('bg-emerald-600', 'bg-amber-600');
                copyBtn.classList.replace('border-emerald-500', 'border-amber-400');
            }, 1500);
        });

        // Initial Focus
        inputArea.focus();

    </script>
</body>
</html>