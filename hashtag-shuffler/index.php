<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hashtag Shuffler | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input Areas */
        .tag-input {
            background: #1f2937;
            border: 2px solid #374151;
            transition: all 0.2s;
            resize: none;
            color: #d1d5db;
        }
        .tag-input:focus {
            outline: none;
            border-color: #ec4899; /* Pink-500 */
            background: #111827;
        }

        /* Tag Chips */
        .tag-chip {
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-block;
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Shuffle Animation Class */
        .shaking {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Gradient Text */
        .insta-grad {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <div class="flex flex-col gap-6">
                
                <div>
                    <h1 class="text-3xl font-black mb-2 insta-grad inline-block">Hashtag Mixer</h1>
                    <p class="text-gray-400 text-sm">Avoid shadowbans by randomizing your tag sets.</p>
                </div>

                <div class="bg-gray-800 p-1 rounded-2xl border border-gray-700 shadow-lg">
                    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Master Tag Bank</label>
                            <span class="text-xs text-gray-600">Paste your full list here</span>
                        </div>
                        <textarea id="input-tags" class="tag-input w-full h-64 p-4 rounded-lg font-mono text-sm leading-relaxed custom-scrollbar" placeholder="#photography #art #design #travel..."></textarea>
                    </div>
                    
                    <div class="p-4 flex flex-col gap-4">
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-xs font-bold text-gray-400 uppercase">Output Limit</label>
                                <span id="limit-val" class="text-xs font-bold text-pink-500">30 Tags</span>
                            </div>
                            <input type="range" id="limit-slider" min="1" max="30" value="30" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-pink-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="clearInput()" class="py-3 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold rounded-xl text-sm transition-colors">
                                Clear Bank
                            </button>
                            <button onclick="shuffleTags()" id="shuffle-btn" class="py-3 bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-500 hover:to-purple-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-pink-500/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Shuffle & Pick
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 text-xs font-bold text-gray-500 bg-gray-800/50 p-3 rounded-xl border border-gray-700/50">
                    <span>Total in Bank: <span id="count-total" class="text-white">0</span></span>
                    <span>Selection: <span id="count-selected" class="text-white">0</span></span>
                </div>

            </div>

            <div class="flex flex-col gap-6">
                
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden flex flex-col h-full min-h-[400px]">
                    
                    <div class="p-4 bg-gray-900/50 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-gray-300 text-sm">Generated Set</h3>
                        <button onclick="copyOutput()" id="copy-btn" class="text-xs font-bold text-pink-500 hover:text-white uppercase transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            Copy to Clipboard
                        </button>
                    </div>

                    <div id="output-container" class="p-6 flex-grow flex flex-wrap content-start gap-2 overflow-y-auto custom-scrollbar bg-gray-800">
                        <div class="text-gray-500 text-sm italic w-full text-center mt-10 opacity-50">
                            Waiting for the shuffle...
                        </div>
                    </div>
                    
                    <textarea id="hidden-copy" class="hidden"></textarea>

                </div>

                <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700/50">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-1">Pro Tip</h4>
                    <p class="text-xs text-gray-500">
                        Instagram allows up to 30 tags. Using a different random combination from a larger bank of 50-60 tags helps keep your content fresh in the algorithm.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const inputTags = document.getElementById('input-tags');
        const outputContainer = document.getElementById('output-container');
        const limitSlider = document.getElementById('limit-slider');
        const limitVal = document.getElementById('limit-val');
        const countTotal = document.getElementById('count-total');
        const countSelected = document.getElementById('count-selected');
        const hiddenCopy = document.getElementById('hidden-copy');
        const copyBtn = document.getElementById('copy-btn');
        const shuffleBtn = document.getElementById('shuffle-btn');

        // Logic
        function parseTags(text) {
            // Match words that start with # or just split by space and add # if missing
            // Let's be robust: Split by spaces/newlines/commas, filter empty
            const raw = text.split(/[\s,]+/);
            const valid = raw.filter(t => t.trim().length > 0).map(t => {
                let clean = t.trim();
                if (!clean.startsWith('#')) clean = '#' + clean;
                return clean;
            });
            // Remove duplicates
            return [...new Set(valid)];
        }

        function shuffleArray(array) {
            // Fisher-Yates Shuffle
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function shuffleTags() {
            const text = inputTags.value;
            if (!text) return;

            // 1. Parse
            const tags = parseTags(text);
            countTotal.textContent = tags.length;

            // 2. Shuffle
            const shuffled = shuffleArray([...tags]); // Copy array before shuffling

            // 3. Slice
            const limit = parseInt(limitSlider.value);
            const selected = shuffled.slice(0, limit);
            countSelected.textContent = selected.length;

            // 4. Render
            renderOutput(selected);

            // 5. Animation
            outputContainer.classList.add('shaking');
            setTimeout(() => outputContainer.classList.remove('shaking'), 500);
        }

        function renderOutput(tags) {
            outputContainer.innerHTML = '';
            
            // Build string for clipboard
            const textStr = tags.join(' ');
            hiddenCopy.value = textStr;

            // Build chips for UI
            tags.forEach((tag, index) => {
                const chip = document.createElement('span');
                chip.className = "tag-chip px-3 py-1 bg-gray-900 border border-gray-700 rounded-full text-sm text-pink-400 font-mono mb-1";
                chip.style.animationDelay = `${index * 0.03}s`; // Stagger animation
                chip.textContent = tag;
                outputContainer.appendChild(chip);
            });
        }

        function copyOutput() {
            if (!hiddenCopy.value) return;
            
            navigator.clipboard.writeText(hiddenCopy.value).then(() => {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = `<span class="text-white">Copied!</span>`;
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                }, 1500);
            });
        }

        function clearInput() {
            inputTags.value = "";
            outputContainer.innerHTML = '<div class="text-gray-500 text-sm italic w-full text-center mt-10 opacity-50">Waiting for the shuffle...</div>';
            countTotal.textContent = "0";
            countSelected.textContent = "0";
            hiddenCopy.value = "";
        }

        // Listeners
        limitSlider.addEventListener('input', (e) => {
            limitVal.textContent = e.target.value + " Tags";
            // Auto re-shuffle if we have tags
            if (hiddenCopy.value) shuffleTags();
        });

        inputTags.addEventListener('input', () => {
            const tags = parseTags(inputTags.value);
            countTotal.textContent = tags.length;
        });

    </script>
</body>
</html>