<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diff Checker | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsdiff/5.1.0/diff.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace for inputs/output */
        .mono-font {
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.6;
        }

        /* Diff Colors */
        ins {
            text-decoration: none;
            background-color: rgba(16, 185, 129, 0.3); /* Emerald tint */
            color: #a7f3d0;
            border-radius: 2px;
            padding: 0 2px;
        }
        
        del {
            text-decoration: none;
            background-color: rgba(239, 68, 68, 0.3); /* Red tint */
            color: #fca5a5;
            border-radius: 2px;
            padding: 0 2px;
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-7xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-4 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Diff Checker</h1>
                <p class="text-center text-gray-400">Compare two blocks of text to find differences.</p>
            </div>

            <div class="flex flex-wrap justify-between items-end mb-4 gap-4">
                
                <div class="flex border-b border-gray-600">
                    <button class="mode-btn px-4 py-2 font-bold text-sm text-gray-400" data-mode="chars">Chars</button>
                    <button class="mode-btn active px-4 py-2 font-bold text-sm text-gray-400" data-mode="words">Words</button>
                    <button class="mode-btn px-4 py-2 font-bold text-sm text-gray-400" data-mode="lines">Lines</button>
                    <button class="mode-btn px-4 py-2 font-bold text-sm text-gray-400" data-mode="json">JSON</button>
                </div>

                <div class="flex gap-2">
                    <button id="sample-btn" class="text-xs text-gray-500 hover:text-white underline mr-2">Load Sample</button>
                    <button id="swap-btn" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold text-gray-300 transition-colors" title="Swap Inputs">
                        ⇄ Swap
                    </button>
                    <button id="clear-btn" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold text-gray-300 transition-colors">
                        Clear
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[600px]">
                
                <div class="flex flex-col gap-4 h-full">
                    
                    <div class="flex-1 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 px-1">Original Text</label>
                        <textarea id="input-original" class="mono-font flex-grow bg-gray-900 border border-gray-600 rounded-xl p-4 text-sm text-gray-300 focus:outline-none focus:border-amber-500 resize-none" placeholder="Paste original text here..."></textarea>
                    </div>

                    <div class="flex-1 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 px-1">Modified Text</label>
                        <textarea id="input-modified" class="mono-font flex-grow bg-gray-900 border border-gray-600 rounded-xl p-4 text-sm text-gray-300 focus:outline-none focus:border-amber-500 resize-none" placeholder="Paste modified text here..."></textarea>
                    </div>

                </div>

                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Comparison Result</label>
                        <div class="flex gap-4 text-xs font-bold">
                            <span class="flex items-center gap-1 text-red-300"><span class="w-2 h-2 rounded-full bg-red-500"></span> Removed</span>
                            <span class="flex items-center gap-1 text-emerald-300"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Added</span>
                        </div>
                    </div>
                    
                    <div class="relative flex-grow bg-gray-900 border border-gray-600 rounded-xl overflow-hidden">
                        <div id="diff-output" class="mono-font absolute inset-0 w-full h-full p-6 text-sm text-gray-400 overflow-auto whitespace-pre-wrap break-all leading-relaxed"></div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const originalInput = document.getElementById('input-original');
        const modifiedInput = document.getElementById('input-modified');
        const output = document.getElementById('diff-output');
        const modeBtns = document.querySelectorAll('.mode-btn');
        const swapBtn = document.getElementById('swap-btn');
        const clearBtn = document.getElementById('clear-btn');
        const sampleBtn = document.getElementById('sample-btn');

        let currentMode = 'words';

        // --- SAMPLE DATA ---
        const sampleOriginal = `The quick brown fox jumps over the lazy dog.
This is a simple diff checker tool.
It is very useful for developers.`;

        const sampleModified = `The quick red fox jumped over the lazy dog.
This is an advanced diff checker tool.
It is extremely useful for everyone.`;

        // --- CORE LOGIC ---

        function computeDiff() {
            const one = originalInput.value;
            const two = modifiedInput.value;

            if (!one && !two) {
                output.innerHTML = '';
                return;
            }

            let diff;
            
            // JSDiff library methods
            // Note: Diff is available globally via the CDN script
            if (currentMode === 'chars') {
                diff = Diff.diffChars(one, two);
            } else if (currentMode === 'words') {
                diff = Diff.diffWords(one, two);
            } else if (currentMode === 'lines') {
                diff = Diff.diffLines(one, two);
            } else if (currentMode === 'json') {
                diff = Diff.diffJson(
                    safeJsonParse(one) || one, 
                    safeJsonParse(two) || two
                );
            }

            const fragment = document.createDocumentFragment();

            diff.forEach((part) => {
                // Determine element type
                let span;
                if (part.added) {
                    span = document.createElement('ins');
                } else if (part.removed) {
                    span = document.createElement('del');
                } else {
                    span = document.createElement('span');
                }

                span.appendChild(document.createTextNode(part.value));
                fragment.appendChild(span);
            });

            output.innerHTML = "";
            output.appendChild(fragment);
        }

        function safeJsonParse(str) {
            try {
                return JSON.parse(str);
            } catch (e) {
                return null;
            }
        }

        // --- EVENT LISTENERS ---

        // Real-time diffing
        originalInput.addEventListener('input', computeDiff);
        modifiedInput.addEventListener('input', computeDiff);

        // Mode Switching
        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentMode = btn.dataset.mode;
                computeDiff();
            });
        });

        // Swap
        swapBtn.addEventListener('click', () => {
            const temp = originalInput.value;
            originalInput.value = modifiedInput.value;
            modifiedInput.value = temp;
            computeDiff();
        });

        // Clear
        clearBtn.addEventListener('click', () => {
            originalInput.value = '';
            modifiedInput.value = '';
            output.innerHTML = '';
            originalInput.focus();
        });

        // Load Sample
        sampleBtn.addEventListener('click', () => {
            originalInput.value = sampleOriginal;
            modifiedInput.value = sampleModified;
            computeDiff();
        });

    </script>
</body>
</html>