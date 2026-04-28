<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Number Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Number Inputs */
        input[type="number"] { -moz-appearance: textfield; }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        /* Result Animation */
        .scramble-anim {
            animation: pulse-amber 0.1s infinite;
            color: #f59e0b;
        }

        @keyframes pulse-amber {
            0% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        /* Result Tag */
        .result-tag {
            animation: popIn 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10b981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-2xl mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">RNG</h1>
                <p class="text-center text-gray-400">Generate random integers within a range.</p>
            </div>

            <div class="bg-gray-800 p-6 md:p-8 rounded-2xl border border-gray-700 shadow-xl">
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="relative group">
                        <label class="text-[10px] font-bold text-gray-500 uppercase absolute top-3 left-4">Min</label>
                        <input type="number" id="min-val" value="1" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 pt-8 text-white text-2xl font-bold focus:outline-none focus:border-amber-500 transition-colors text-center mono-font group-hover:border-gray-500">
                    </div>
                    <div class="relative group">
                        <label class="text-[10px] font-bold text-gray-500 uppercase absolute top-3 left-4">Max</label>
                        <input type="number" id="max-val" value="100" class="w-full bg-gray-900 border border-gray-600 rounded-xl p-4 pt-8 text-white text-2xl font-bold focus:outline-none focus:border-amber-500 transition-colors text-center mono-font group-hover:border-gray-500">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-8">
                    
                    <div class="flex-grow bg-gray-900 rounded-lg p-3 border border-gray-700 flex items-center justify-between">
                        <label class="text-xs font-bold text-gray-400 uppercase">Quantity</label>
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 rounded bg-gray-800 text-gray-400 hover:text-white font-bold" onclick="updateQty(-1)">-</button>
                            <span id="qty-disp" class="w-8 text-center font-bold mono-font text-white">1</span>
                            <button class="w-8 h-8 rounded bg-gray-800 text-gray-400 hover:text-white font-bold" onclick="updateQty(1)">+</button>
                        </div>
                    </div>

                    <div class="flex-grow bg-gray-900 rounded-lg p-3 border border-gray-700 flex items-center justify-between cursor-pointer" onclick="toggleUnique()">
                        <label class="text-xs font-bold text-gray-400 uppercase cursor-pointer">Unique Only</label>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="toggle" id="unique-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 left-0 border-gray-300"/>
                            <label for="toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-700 cursor-pointer transition-colors duration-300"></label>
                        </div>
                    </div>

                </div>

                <div class="bg-black/30 rounded-xl p-6 mb-6 min-h-[120px] flex flex-col items-center justify-center relative overflow-hidden border border-gray-700/50">
                    <div id="main-result" class="text-6xl md:text-7xl font-black text-white mono-font tracking-tighter">0</div>
                    <div id="result-list" class="hidden flex-wrap gap-2 justify-center mt-2 w-full"></div>
                    
                    <button id="copy-btn" onclick="copyResults()" class="absolute top-3 right-3 text-gray-500 hover:text-white opacity-0 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    </button>
                </div>

                <button onclick="generate()" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                    GENERATE
                </button>

                <div class="flex justify-center gap-4 mt-6 text-xs font-bold text-gray-500">
                    <button onclick="sortResults('asc')" class="hover:text-white transition-colors">Sort Low-High</button>
                    <span>|</span>
                    <button onclick="sortResults('desc')" class="hover:text-white transition-colors">Sort High-Low</button>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const minInput = document.getElementById('min-val');
        const maxInput = document.getElementById('max-val');
        const qtyDisp = document.getElementById('qty-disp');
        const uniqueToggle = document.getElementById('unique-toggle');
        const mainResult = document.getElementById('main-result');
        const resultList = document.getElementById('result-list');
        const copyBtn = document.getElementById('copy-btn');

        // State
        let quantity = 1;
        let isUnique = false;
        let currentResults = [];
        let isAnimating = false;

        // --- ACTIONS ---

        function updateQty(delta) {
            quantity += delta;
            if (quantity < 1) quantity = 1;
            if (quantity > 100) quantity = 100; // Cap
            qtyDisp.innerText = quantity;
        }

        function toggleUnique() {
            isUnique = !isUnique;
            uniqueToggle.checked = isUnique;
        }

        function generate() {
            if (isAnimating) return;

            const min = parseInt(minInput.value);
            const max = parseInt(maxInput.value);

            // Validation
            if (isNaN(min) || isNaN(max)) return alert("Please enter valid numbers.");
            if (min >= max) return alert("Min must be less than Max.");
            
            // Unique Validation
            if (isUnique && (max - min + 1) < quantity) {
                return alert(`Cannot generate ${quantity} unique numbers in a range of ${max - min + 1}.`);
            }

            // Animation Phase
            isAnimating = true;
            copyBtn.classList.add('opacity-0');
            
            // If quantity is 1, use big text. If > 1, use list.
            if (quantity > 1) {
                mainResult.classList.add('hidden');
                resultList.classList.remove('hidden');
                resultList.innerHTML = '<span class="text-gray-500 animate-pulse">Computing...</span>';
            } else {
                mainResult.classList.remove('hidden');
                resultList.classList.add('hidden');
            }

            // Scramble Effect
            let scrambles = 0;
            const interval = setInterval(() => {
                scrambles++;
                
                // Show random number in big display during scramble
                if (quantity === 1) {
                    mainResult.innerText = Math.floor(Math.random() * (max - min + 1)) + min;
                    mainResult.classList.add('scramble-anim');
                }

                if (scrambles > 10) {
                    clearInterval(interval);
                    finalizeResults(min, max);
                }
            }, 50);
        }

        function finalizeResults(min, max) {
            isAnimating = false;
            currentResults = [];

            if (isUnique) {
                const set = new Set();
                while (set.size < quantity) {
                    set.add(Math.floor(Math.random() * (max - min + 1)) + min);
                }
                currentResults = Array.from(set);
            } else {
                for (let i = 0; i < quantity; i++) {
                    currentResults.push(Math.floor(Math.random() * (max - min + 1)) + min);
                }
            }

            renderResults();
            copyBtn.classList.remove('opacity-0');
            
            // Cleanup animation classes
            if (quantity === 1) mainResult.classList.remove('scramble-anim');
        }

        function renderResults() {
            if (quantity === 1) {
                mainResult.innerText = currentResults[0];
            } else {
                resultList.innerHTML = '';
                currentResults.forEach(num => {
                    const tag = document.createElement('div');
                    tag.className = "result-tag bg-gray-700 text-white font-bold mono-font px-3 py-1 rounded text-xl border border-gray-600 shadow-sm";
                    tag.innerText = num;
                    resultList.appendChild(tag);
                });
            }
        }

        function sortResults(order) {
            if (currentResults.length < 2) return;
            
            if (order === 'asc') currentResults.sort((a, b) => a - b);
            if (order === 'desc') currentResults.sort((a, b) => b - a);
            
            renderResults();
        }

        function copyResults() {
            const text = currentResults.join(', ');
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="text-xs font-bold text-emerald-400">Copied!</span>';
                setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
            });
        }

        // Init click handlers for checkbox div wrapper
        uniqueToggle.addEventListener('click', (e) => e.stopPropagation()); // Prevent double toggle logic

    </script>
</body>
</html>