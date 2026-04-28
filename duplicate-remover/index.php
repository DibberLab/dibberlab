<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duplicate Line Remover | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Editors */
        textarea {
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.6;
            font-size: 14px;
        }

        /* Stats Badge */
        .stat-badge {
            background-color: #374151;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            gap: 6px;
            border: 1px solid #4b5563;
        }

        /* Custom Checkbox */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }

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
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Duplicate Line Remover</h1>
                <p class="text-center text-gray-400">Clean up lists, remove repeats, and sort data.</p>
            </div>

            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6 flex flex-wrap gap-6 items-center justify-between shadow-lg">
                
                <div class="flex flex-wrap gap-6">
                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-trim" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-trim" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Trim Whitespace</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-case" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-case" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Case Sensitive</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-empty" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-empty" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                        <span class="text-sm font-bold text-gray-300">Remove Empty Lines</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button id="sort-btn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-bold text-sm transition-colors border border-gray-600">
                        Sort A-Z
                    </button>
                    <button id="process-btn" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors shadow-lg flex items-center gap-2">
                        <span>✨</span> Remove Duplicates
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[500px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Original List</label>
                        <div class="stat-badge">
                            <span class="text-gray-400">Lines:</span>
                            <span id="count-in" class="text-white">0</span>
                        </div>
                    </div>
                    <div class="relative flex-grow">
                        <textarea id="input-area" class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-gray-300 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar" placeholder="Paste your list here...
Apple
Banana
Apple
Orange
Banana"></textarea>
                        <button id="clear-btn" class="absolute top-2 right-2 text-xs bg-gray-800 hover:bg-red-900/50 hover:text-red-300 text-gray-400 px-2 py-1 rounded border border-gray-600 transition-colors">Clear</button>
                    </div>
                </div>

                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Cleaned List</label>
                        <div class="flex gap-2">
                            <div class="stat-badge border-red-900/50 bg-red-900/20">
                                <span class="text-red-300">Removed:</span>
                                <span id="count-removed" class="text-red-400">0</span>
                            </div>
                            <div class="stat-badge border-emerald-900/50 bg-emerald-900/20">
                                <span class="text-emerald-300">Result:</span>
                                <span id="count-out" class="text-emerald-400">0</span>
                            </div>
                        </div>
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
        // Elements
        const inputArea = document.getElementById('input-area');
        const outputArea = document.getElementById('output-area');
        const processBtn = document.getElementById('process-btn');
        const sortBtn = document.getElementById('sort-btn');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        
        const countIn = document.getElementById('count-in');
        const countOut = document.getElementById('count-out');
        const countRemoved = document.getElementById('count-removed');

        // Options
        const optTrim = document.getElementById('opt-trim');
        const optCase = document.getElementById('opt-case');
        const optEmpty = document.getElementById('opt-empty');

        // --- CORE LOGIC ---

        function processList(sort = false) {
            const raw = inputArea.value;
            if (!raw) {
                resetStats();
                return;
            }

            // Split lines (handle Windows \r\n and Unix \n)
            let lines = raw.split(/\r?\n/);
            const totalLines = lines.length;

            // Settings
            const shouldTrim = optTrim.checked;
            const isCaseSensitive = optCase.checked;
            const removeEmpty = optEmpty.checked;

            // Using a Map to preserve original formatting of the *first* occurrence
            // Key = Normalized String (for comparison)
            // Value = Original String (for output)
            const uniqueMap = new Map();

            lines.forEach(line => {
                let comparisonKey = line;

                // Normalize for comparison
                if (shouldTrim) comparisonKey = comparisonKey.trim();
                if (!isCaseSensitive) comparisonKey = comparisonKey.toLowerCase();

                // Empty check
                if (removeEmpty && comparisonKey === '') return;

                // Add if not exists
                if (!uniqueMap.has(comparisonKey)) {
                    // We store the version we want to output. 
                    // Usually we output the trimmed version if trim is on, else original.
                    uniqueMap.set(comparisonKey, shouldTrim ? line.trim() : line);
                }
            });

            let resultLines = Array.from(uniqueMap.values());

            // Sort if requested
            if (sort) {
                resultLines.sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' }));
            }

            // Update UI
            outputArea.value = resultLines.join('\n');
            
            // Update Stats
            countIn.textContent = totalLines.toLocaleString();
            countOut.textContent = resultLines.length.toLocaleString();
            countRemoved.textContent = (totalLines - resultLines.length).toLocaleString();
        }

        function resetStats() {
            outputArea.value = '';
            countIn.textContent = '0';
            countOut.textContent = '0';
            countRemoved.textContent = '0';
        }

        // --- LISTENERS ---

        processBtn.addEventListener('click', () => processList(false));
        sortBtn.addEventListener('click', () => processList(true));

        // Auto-update stats on input? Optional. 
        // Let's just update the Input Counter on typing.
        inputArea.addEventListener('input', () => {
            const lines = inputArea.value.split(/\r?\n/);
            countIn.textContent = inputArea.value ? lines.length.toLocaleString() : '0';
        });

        clearBtn.addEventListener('click', () => {
            inputArea.value = '';
            resetStats();
            inputArea.focus();
        });

        copyBtn.addEventListener('click', () => {
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

        // Init
        // processList();

    </script>
</body>
</html>