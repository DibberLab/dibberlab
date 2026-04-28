<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none; background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 20px; width: 20px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }

        /* Toggle Checkbox */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }

        /* Strength Meter Transitions */
        #strength-bar { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s; }
        
        /* History Items */
        .history-item {
            transition: background-color 0.1s;
        }
        .history-item:hover {
            background-color: #374151;
        }
        .history-item:active {
            transform: scale(0.99);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Password Generator</h1>
                <p class="text-center text-gray-400">Generate cryptographically secure, random passwords.</p>
            </div>

            <div class="mb-8">
                <div class="relative">
                    <input type="text" id="password-output" readonly class="w-full bg-gray-900 border border-gray-600 rounded-xl p-5 pr-14 text-white text-xl md:text-2xl font-bold mono-font focus:outline-none focus:border-amber-500 shadow-inner truncate" value="Generating...">
                    
                    <button id="copy-btn" class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 hover:text-white p-2 transition-colors" title="Copy to Clipboard">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    </button>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <div class="flex-grow h-2 bg-gray-700 rounded-full overflow-hidden">
                        <div id="strength-bar" class="h-full w-0 bg-red-500"></div>
                    </div>
                    <span id="strength-text" class="text-xs font-bold uppercase text-gray-500 w-20 text-right">Weak</span>
                </div>
            </div>

            <div class="space-y-6">
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Length</label>
                        <span id="length-val" class="text-xl font-bold text-white mono-font">16</span>
                    </div>
                    <input type="range" id="length-slider" min="6" max="64" value="16" class="w-full">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-900 p-4 rounded-xl border border-gray-700">
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-300">ABC (Uppercase)</span>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-upper" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-upper" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-300">abc (Lowercase)</span>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-lower" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-lower" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-300">123 (Numbers)</span>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-numbers" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-numbers" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-300">!@# (Symbols)</span>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-symbols" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-symbols" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between col-span-1 sm:col-span-2 border-t border-gray-700 pt-3 mt-1">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-300">Easy to Read</span>
                            <span class="text-[10px] text-gray-500">Avoid ambiguous characters (1, l, I, 0, O)</span>
                        </div>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="opt-ambiguous" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="opt-ambiguous" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>

                </div>

                <button id="generate-btn" class="w-full py-4 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                    GENERATE NEW
                </button>

            </div>

            <div class="mt-8 border-t border-gray-700 pt-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase">Recent Passwords</span>
                    <button id="clear-history" class="text-xs text-red-400 hover:text-red-300 underline">Clear</button>
                </div>
                <div id="history-list" class="space-y-2">
                    </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const output = document.getElementById('password-output');
        const lengthSlider = document.getElementById('length-slider');
        const lengthVal = document.getElementById('length-val');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        const copyBtn = document.getElementById('copy-btn');
        const generateBtn = document.getElementById('generate-btn');
        
        // Options
        const optUpper = document.getElementById('opt-upper');
        const optLower = document.getElementById('opt-lower');
        const optNumbers = document.getElementById('opt-numbers');
        const optSymbols = document.getElementById('opt-symbols');
        const optAmbiguous = document.getElementById('opt-ambiguous');
        
        const historyList = document.getElementById('history-list');
        const clearHistoryBtn = document.getElementById('clear-history');

        // Character Sets
        const CHARS = {
            upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            lower: 'abcdefghijklmnopqrstuvwxyz',
            numbers: '0123456789',
            symbols: '!@#$%^&*()_+~`|}{[]:;?><,./-=',
            ambiguous: 'Il1O0'
        };

        // --- CORE LOGIC ---

        function generate() {
            const length = parseInt(lengthSlider.value);
            let charPool = '';
            
            // Build Pool
            if (optUpper.checked) charPool += CHARS.upper;
            if (optLower.checked) charPool += CHARS.lower;
            if (optNumbers.checked) charPool += CHARS.numbers;
            if (optSymbols.checked) charPool += CHARS.symbols;

            // Remove Ambiguous
            if (optAmbiguous.checked) {
                // Regex to remove characters present in CHARS.ambiguous
                const escapeRegExp = (string) => string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const pattern = new RegExp(`[${escapeRegExp(CHARS.ambiguous)}]`, 'g');
                charPool = charPool.replace(pattern, '');
            }

            // Fallback if nothing selected
            if (charPool === '') {
                output.value = "Select options!";
                updateStrength(0);
                return;
            }

            // Generate Password (Cryptographically Secure)
            let password = '';
            const randomValues = new Uint32Array(length);
            crypto.getRandomValues(randomValues);

            for (let i = 0; i < length; i++) {
                password += charPool[randomValues[i] % charPool.length];
            }

            output.value = password;
            updateStrength(password);
            addToHistory(password);
        }

        function updateStrength(password) {
            if (!password || password.length === 0) {
                setStrength(0, 'bg-gray-700', '');
                return;
            }

            // Calculate Entropy (bits) = length * log2(poolSize)
            // Estimation logic for UI:
            let score = 0;
            if (password.length > 8) score += 20;
            if (password.length > 12) score += 20;
            if (password.length >= 16) score += 20;
            if (/[A-Z]/.test(password)) score += 10;
            if (/[a-z]/.test(password)) score += 10;
            if (/[0-9]/.test(password)) score += 10;
            if (/[^A-Za-z0-9]/.test(password)) score += 10;

            if (score < 40) setStrength(20, 'bg-red-500', 'Weak');
            else if (score < 70) setStrength(50, 'bg-amber-500', 'Medium');
            else if (score < 90) setStrength(80, 'bg-blue-500', 'Strong');
            else setStrength(100, 'bg-emerald-500', 'Secure');
        }

        function setStrength(width, colorClass, text) {
            strengthBar.style.width = `${width}%`;
            strengthBar.className = `h-full ${colorClass}`;
            strengthText.textContent = text;
            // Match text color to bar
            strengthText.className = `text-xs font-bold uppercase w-20 text-right ${colorClass.replace('bg-', 'text-')}`;
        }

        // --- HISTORY ---
        
        function addToHistory(pass) {
            const item = document.createElement('div');
            item.className = "history-item bg-gray-800 p-3 rounded-lg border border-gray-700 flex justify-between items-center cursor-pointer group";
            
            // Masked view vs Reveal on hover logic is annoying, let's just show it but monospaced
            // Truncate if too long
            item.innerHTML = `
                <span class="mono-font text-gray-300 text-sm truncate mr-4 select-all">${pass}</span>
                <span class="text-xs text-emerald-500 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Copy</span>
            `;

            item.addEventListener('click', () => {
                navigator.clipboard.writeText(pass);
                // Visual feedback
                const label = item.querySelector('span:last-child');
                label.textContent = "Copied!";
                label.style.opacity = '1';
                setTimeout(() => {
                    label.textContent = "Copy";
                    label.style.opacity = '';
                }, 1000);
            });

            // Insert at top
            historyList.prepend(item);

            // Limit history
            if (historyList.children.length > 5) {
                historyList.lastElementChild.remove();
            }
        }

        // --- LISTENERS ---

        lengthSlider.addEventListener('input', (e) => {
            lengthVal.textContent = e.target.value;
            generate();
        });

        [optUpper, optLower, optNumbers, optSymbols, optAmbiguous].forEach(el => {
            el.addEventListener('change', generate);
        });

        generateBtn.addEventListener('click', generate);

        copyBtn.addEventListener('click', () => {
            const pass = output.value;
            if(!pass) return;
            output.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(pass);
            
            const orig = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">OK</span>`;
            setTimeout(() => copyBtn.innerHTML = orig, 1000);
        });

        clearHistoryBtn.addEventListener('click', () => {
            historyList.innerHTML = '';
        });

        // Init
        generate();

    </script>
</body>
</html>