<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find & Replace | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace Editors */
        textarea {
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

        /* Stats Badge */
        .match-badge {
            transition: all 0.3s;
        }
        .match-badge.has-matches {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border-color: #059669;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Find & Replace</h1>
                <p class="text-center text-gray-400">Bulk text replacement with advanced filtering.</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 mb-6 shadow-lg">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Find</label>
                            <span id="match-count" class="text-xs font-mono text-gray-500 border border-gray-600 px-2 rounded-full match-badge">0 matches</span>
                        </div>
                        <input type="text" id="find-input" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500 font-mono" placeholder="Text to find...">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Replace With</label>
                        <input type="text" id="replace-input" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 font-mono" placeholder="Replacement text...">
                    </div>
                </div>

                <div class="flex flex-wrap gap-6 items-center border-t border-gray-700 pt-4">
                    
                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-case" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-case" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Case Sensitive</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-whole" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-whole" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Whole Word</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-regex" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-regex" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300">Regex Mode</span>
                            <span id="regex-error" class="text-[10px] text-red-400 hidden">Invalid Regex</span>
                        </div>
                    </div>

                    <button id="swap-btn" class="ml-auto text-xs font-bold text-gray-400 hover:text-white flex items-center gap-1 transition-colors">
                        <span>⇄</span> Swap Input/Output
                    </button>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[500px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Original Text</label>
                        <button id="clear-btn" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                    </div>
                    <textarea id="input-area" class="w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Paste content here..."></textarea>
                </div>

                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Result</label>
                        <span id="char-diff" class="text-xs font-mono text-gray-500">0 chars</span>
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
        const findInput = document.getElementById('find-input');
        const replaceInput = document.getElementById('replace-input');
        
        const optCase = document.getElementById('opt-case');
        const optWhole = document.getElementById('opt-whole');
        const optRegex = document.getElementById('opt-regex');
        
        const matchCount = document.getElementById('match-count');
        const regexError = document.getElementById('regex-error');
        const charDiff = document.getElementById('char-diff');
        
        const swapBtn = document.getElementById('swap-btn');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');

        // --- CORE LOGIC ---

        function processText() {
            const original = inputArea.value;
            const findTerm = findInput.value;
            const replaceTerm = replaceInput.value;

            if (!original) {
                outputArea.value = '';
                updateStats(0, 0);
                return;
            }

            if (!findTerm) {
                outputArea.value = original;
                updateStats(0, 0);
                return;
            }

            let regex;
            let flags = 'g'; // Always global replace for this tool
            if (!optCase.checked) flags += 'i';

            try {
                if (optRegex.checked) {
                    regex = new RegExp(findTerm, flags);
                    regexError.classList.add('hidden');
                    findInput.classList.remove('text-red-400', 'border-red-500');
                } else {
                    // Escape special regex chars if normal mode
                    let pattern = findTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    
                    if (optWhole.checked) {
                        pattern = `\\b${pattern}\\b`;
                    }
                    
                    regex = new RegExp(pattern, flags);
                }
            } catch (e) {
                // Invalid Regex
                if (optRegex.checked) {
                    regexError.classList.remove('hidden');
                    findInput.classList.add('text-red-400', 'border-red-500');
                }
                return;
            }

            // Count matches
            const matches = original.match(regex);
            const count = matches ? matches.length : 0;

            // Perform Replace
            const result = original.replace(regex, replaceTerm);
            
            outputArea.value = result;
            
            // Calc diff
            const diff = result.length - original.length;
            
            updateStats(count, diff);
        }

        function updateStats(matches, diff) {
            // Match Badge
            matchCount.textContent = `${matches} match${matches !== 1 ? 'es' : ''}`;
            if (matches > 0) {
                matchCount.classList.add('has-matches', 'border-emerald-500');
                matchCount.classList.remove('border-gray-600', 'text-gray-500');
            } else {
                matchCount.classList.remove('has-matches', 'border-emerald-500');
                matchCount.classList.add('border-gray-600', 'text-gray-500');
            }

            // Char Diff
            if (diff > 0) {
                charDiff.textContent = `+${diff} chars`;
                charDiff.className = "text-xs font-mono text-red-400";
            } else if (diff < 0) {
                charDiff.textContent = `${diff} chars`;
                charDiff.className = "text-xs font-mono text-emerald-400";
            } else {
                charDiff.textContent = "0 chars change";
                charDiff.className = "text-xs font-mono text-gray-500";
            }
        }

        // --- LISTENERS ---

        [inputArea, findInput, replaceInput].forEach(el => {
            el.addEventListener('input', processText);
        });

        [optCase, optWhole, optRegex].forEach(el => {
            el.addEventListener('change', processText);
        });

        // Swap
        swapBtn.addEventListener('click', () => {
            inputArea.value = outputArea.value;
            processText(); // Re-run
        });

        // Clear
        clearBtn.addEventListener('click', () => {
            inputArea.value = '';
            findInput.value = '';
            replaceInput.value = '';
            processText();
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