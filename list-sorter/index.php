<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Sorter | Dibber Lab</title>
    
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

        /* Sort Buttons */
        .sort-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .sort-btn:hover {
            background-color: #374151;
            border-color: #4b5563;
            transform: translateY(-1px);
        }
        .sort-btn:active {
            transform: translateY(0);
            background-color: #f59e0b;
            color: #111827;
            border-color: #f59e0b;
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
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">List Sorter</h1>
                <p class="text-center text-gray-400">Alphabetize, randomize, and organize lists instantly.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-auto lg:h-[600px]">
                
                <div class="lg:col-span-4 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input List</label>
                        <button id="clear-btn" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                    </div>
                    <textarea id="input-area" class="w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none shadow-inner custom-scrollbar" placeholder="Apple
10 Bananas
2 Bananas
Zebra
Orange"></textarea>
                </div>

                <div class="lg:col-span-4 flex flex-col justify-center gap-6 py-4">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block text-center">Alphabetical</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-white" onclick="sortList('az')">
                                <span>A ➝ Z</span>
                            </button>
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-white" onclick="sortList('za')">
                                <span>Z ➝ A</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block text-center">Natural / Numeric</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-emerald-400" onclick="sortList('num-asc')">
                                <span>1 ➝ 9</span>
                            </button>
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-emerald-400" onclick="sortList('num-desc')">
                                <span>9 ➝ 1</span>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-500 text-center mt-1">Handles "Item 2" before "Item 10"</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block text-center">By Length</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-blue-400" onclick="sortList('len-asc')">
                                <span>Shortest</span>
                            </button>
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-blue-400" onclick="sortList('len-desc')">
                                <span>Longest</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block text-center">Misc</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-purple-400" onclick="sortList('reverse')">
                                <span>Reverse</span>
                            </button>
                            <button class="sort-btn bg-gray-800 p-3 rounded-lg text-sm font-bold text-purple-400" onclick="sortList('random')">
                                <span>Shuffle</span>
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-3 rounded-xl border border-gray-700">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="opt-number" class="w-4 h-4 text-amber-500 rounded bg-gray-700 border-gray-500 focus:ring-amber-500">
                            <span class="text-sm font-bold text-gray-300">Add Numbering (1. 2. 3.)</span>
                        </label>
                    </div>

                </div>

                <div class="lg:col-span-4 flex flex-col h-full relative">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Sorted Result</label>
                        <span id="count-display" class="text-xs font-mono text-emerald-400">0 items</span>
                    </div>
                    
                    <div class="relative flex-grow">
                        <textarea id="output-area" readonly class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-emerald-400 focus:outline-none resize-none shadow-inner custom-scrollbar" placeholder="Result will appear here..."></textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-white p-2 rounded-lg transition-colors shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
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
        const countDisplay = document.getElementById('count-display');
        const optNumber = document.getElementById('opt-number');

        // --- SORT LOGIC ---

        function getLines() {
            const raw = input.value;
            // Split, trim lines? Usually sorting implies keeping line integrity but maybe ignoring empty?
            // Let's filter empty lines for clean sorting.
            return raw.split(/\r?\n/).filter(line => line.trim() !== '');
        }

        function sortList(type) {
            let lines = getLines();
            if (lines.length === 0) return;

            switch (type) {
                case 'az':
                    lines.sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
                    break;
                case 'za':
                    lines.sort((a, b) => b.localeCompare(a, undefined, { sensitivity: 'base' }));
                    break;
                case 'num-asc':
                    // Numeric sort (Natural Sort)
                    lines.sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' }));
                    break;
                case 'num-desc':
                    lines.sort((a, b) => b.localeCompare(a, undefined, { numeric: true, sensitivity: 'base' }));
                    break;
                case 'len-asc':
                    lines.sort((a, b) => a.length - b.length || a.localeCompare(b));
                    break;
                case 'len-desc':
                    lines.sort((a, b) => b.length - a.length || a.localeCompare(b));
                    break;
                case 'reverse':
                    lines.reverse();
                    break;
                case 'random':
                    // Fisher-Yates Shuffle
                    for (let i = lines.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [lines[i], lines[j]] = [lines[j], lines[i]];
                    }
                    break;
            }

            // Apply Formatting (Numbering)
            if (optNumber.checked) {
                // If text already starts with numbers "1. ", strip them first to avoid double numbering?
                // Simple regex check: ^\d+\.\s
                const hasNumbering = lines.every(l => /^\d+\.\s/.test(l));
                if (hasNumbering) {
                    lines = lines.map(l => l.replace(/^\d+\.\s/, ''));
                }
                
                lines = lines.map((l, i) => `${i + 1}. ${l}`);
            } else {
                // If unchecked, should we strip existing numbering? 
                // Maybe safer not to modify content destructively unless asked.
                // But let's provide a "clean" option logic if the user unchecks it. 
                // For now, simple append logic is safer.
            }

            output.value = lines.join('\n');
            countDisplay.textContent = `${lines.length} items`;
        }

        // --- LISTENERS ---

        clearBtn.addEventListener('click', () => {
            input.value = '';
            output.value = '';
            countDisplay.textContent = '0 items';
            input.focus();
        });

        copyBtn.addEventListener('click', () => {
            if(!output.value) return;
            output.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(output.value);
            
            const orig = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">OK</span>`;
            setTimeout(() => copyBtn.innerHTML = orig, 1500);
        });

        // Trigger sort immediately on option change if output exists
        optNumber.addEventListener('change', () => {
            if(output.value) sortList('az'); // Default re-sort or just re-render? 
            // Better to re-run last sort? We don't track state. 
            // Let's just re-run standard sort as a fallback or do nothing until button click.
            // User likely expects immediate feedback:
            if(input.value) sortList('az'); 
        });

    </script>
</body>
</html>