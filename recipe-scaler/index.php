<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Scaler | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Scrollbar */
        textarea::-webkit-scrollbar, div::-webkit-scrollbar { width: 8px; }
        textarea::-webkit-scrollbar-track, div::-webkit-scrollbar-track { background: #1f2937; }
        textarea::-webkit-scrollbar-thumb, div::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        textarea::-webkit-scrollbar-thumb:hover, div::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Multiplier Button */
        .scale-btn {
            transition: all 0.2s;
            border: 1px solid #374151;
        }
        .scale-btn:hover { background-color: #374151; color: white; border-color: #4b5563; }
        .scale-btn.active {
            background-color: #f59e0b; /* Amber */
            color: #111827;
            font-weight: bold;
            border-color: #f59e0b;
        }

        /* Scaled Number Highlight */
        .scaled-num {
            color: #10b981; /* Emerald-400 */
            font-weight: bold;
            background: rgba(16, 185, 129, 0.1);
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto h-[calc(100vh-140px)] min-h-[600px] flex flex-col">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400">Recipe Scaler</h1>
                    <p class="text-gray-400 text-sm">Paste ingredients, choose a multiplier, cook.</p>
                </div>

                <div class="flex items-center gap-2 bg-gray-800 p-1 rounded-lg border border-gray-700">
                    <button class="scale-btn rounded px-3 py-2 text-sm text-gray-400" onclick="setScale(0.5)">½x</button>
                    <button class="scale-btn active rounded px-3 py-2 text-sm text-gray-400" onclick="setScale(1)">1x</button>
                    <button class="scale-btn rounded px-3 py-2 text-sm text-gray-400" onclick="setScale(2)">2x</button>
                    <button class="scale-btn rounded px-3 py-2 text-sm text-gray-400" onclick="setScale(3)">3x</button>
                    <div class="w-px h-6 bg-gray-700 mx-1"></div>
                    <span class="text-xs font-bold text-gray-500 pl-2">Custom:</span>
                    <input type="number" id="custom-scale" class="w-16 bg-gray-900 border border-gray-600 rounded px-2 py-1 text-white text-sm focus:outline-none focus:border-amber-500" placeholder="#" oninput="handleCustomInput(this)">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-grow overflow-hidden">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Original Ingredients</label>
                        <button onclick="clearInput()" class="text-xs text-red-400 hover:text-white underline">Clear</button>
                    </div>
                    <textarea id="input-text" class="w-full h-full bg-gray-800 border border-gray-700 rounded-2xl p-6 text-gray-300 mono-font text-sm leading-relaxed focus:outline-none focus:border-amber-500 resize-none transition-colors" placeholder="Paste your recipe here...
Example:
2 cups flour
1/2 tsp salt
1.5 lbs beef
3 eggs"></textarea>
                </div>

                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-emerald-500 uppercase">Scaled Result</label>
                        <button onclick="copyOutput()" id="copy-btn" class="text-xs text-gray-400 hover:text-white font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            Copy
                        </button>
                    </div>
                    <div id="output-box" class="w-full h-full bg-gray-900 border border-gray-700 rounded-2xl p-6 text-gray-300 mono-font text-sm leading-relaxed overflow-y-auto whitespace-pre-wrap">
                        </div>
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
        const outputBox = document.getElementById('output-box');
        const customInput = document.getElementById('custom-scale');
        const scaleBtns = document.querySelectorAll('.scale-btn');
        const copyBtn = document.getElementById('copy-btn');

        // State
        let currentMultiplier = 1;

        // Default Placeholder Text
        const DEFAULT_RECIPE = `2 1/2 cups all-purpose flour
1 tsp baking soda
1/2 tsp salt
1 cup butter, softened
3/4 cup granulated sugar
3/4 cup packed brown sugar
2 large eggs
1 tsp vanilla extract
2 cups chocolate chips`;

        // --- MATH ENGINE ---

        // Regex to find numbers: 
        // Matches "1", "1.5", ".5", "1/2", "1 1/2"
        const REGEX_NUM = /((\d+\s+)?\d+\/\d+)|(\d*\.?\d+)/g;

        function convertToDecimal(str) {
            if (str.includes('/')) {
                const parts = str.split(' ');
                if (parts.length === 2) {
                    // "1 1/2" -> 1 + 0.5
                    return parseFloat(parts[0]) + eval(parts[1]);
                } else {
                    // "1/2" -> 0.5
                    return eval(str);
                }
            }
            return parseFloat(str);
        }

        function formatNumber(num) {
            // Check for common cooking fractions within a small tolerance
            const tolerance = 0.01;
            const whole = Math.floor(num);
            const decimal = num - whole;

            let frac = "";

            if (Math.abs(decimal - 0.25) < tolerance) frac = "1/4";
            else if (Math.abs(decimal - 0.33) < tolerance) frac = "1/3";
            else if (Math.abs(decimal - 0.50) < tolerance) frac = "1/2";
            else if (Math.abs(decimal - 0.66) < tolerance) frac = "2/3";
            else if (Math.abs(decimal - 0.75) < tolerance) frac = "3/4";

            if (frac) {
                return whole > 0 ? `${whole} ${frac}` : frac;
            }

            // Otherwise, round to 2 decimals if needed, or int
            return parseFloat(num.toFixed(2));
        }

        function processText() {
            const raw = inputText.value;
            if (!raw) {
                outputBox.innerHTML = '<span class="text-gray-600 italic">Start typing...</span>';
                return;
            }

            // We process line by line to keep formatting
            const lines = raw.split('\n');
            let resultHTML = "";

            lines.forEach(line => {
                // Find numbers
                // We use a replacer function
                const scaledLine = line.replace(REGEX_NUM, (match) => {
                    // Hack: Avoid scaling temperatures if possible (simple heuristic)
                    // If the number is followed immediately by "F" or "C" or " degrees", skip?
                    // Regex lookahead is hard in replace, so we handle basic scaling.
                    
                    // Filter out non-numeric matches that might slip in (rare with this regex)
                    if(!match.match(/\d/)) return match;

                    const val = convertToDecimal(match);
                    const scaled = val * currentMultiplier;
                    
                    // Highlight changed numbers
                    if (currentMultiplier !== 1) {
                        return `<span class="scaled-num">${formatNumber(scaled)}</span>`;
                    }
                    return formatNumber(scaled);
                });

                resultHTML += scaledLine + "<br>";
            });

            outputBox.innerHTML = resultHTML;
        }

        // --- ACTIONS ---

        function setScale(val) {
            currentMultiplier = val;
            
            // Update UI
            scaleBtns.forEach(btn => {
                const txt = btn.textContent.replace('x','');
                if (parseFloat(txt) === val || (txt === '½' && val === 0.5)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // Reset custom input if using preset
            if (![0.5, 1, 2, 3].includes(val)) {
               // Keep custom input value
            } else {
                customInput.value = '';
            }

            processText();
        }

        function handleCustomInput(el) {
            const val = parseFloat(el.value);
            if (val > 0) {
                scaleBtns.forEach(b => b.classList.remove('active'));
                currentMultiplier = val;
                processText();
            }
        }

        function clearInput() {
            inputText.value = "";
            processText();
            inputText.focus();
        }

        function copyOutput() {
            const text = outputBox.innerText; // Get plain text without HTML tags
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="text-emerald-400">Copied!</span>';
                setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
            });
        }

        // --- LISTENERS ---
        inputText.addEventListener('input', processText);

        // Init
        inputText.value = DEFAULT_RECIPE;
        processText();

    </script>
</body>
</html>