<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UUID Generator | Dibber Lab</title>
    
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

        /* Output Area Animation */
        #output-area { transition: border-color 0.2s; }
        #output-area:focus { border-color: #f59e0b; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">UUID Generator</h1>
                <p class="text-center text-gray-400">Generate cryptographically secure Version 4 UUIDs.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                <div class="md:col-span-5 space-y-8">
                    
                    <div class="bg-gray-900 p-4 rounded-xl border border-gray-600 relative group">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Single UUID</label>
                        <div class="flex items-center gap-2">
                            <input id="single-uuid" readonly class="mono-font text-lg text-emerald-400 bg-transparent w-full focus:outline-none truncate" value="Generating...">
                            <button onclick="copySingle()" class="text-gray-400 hover:text-white transition-colors p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            </button>
                        </div>
                        <div id="single-toast" class="absolute top-2 right-2 text-xs text-emerald-500 font-bold opacity-0 transition-opacity">Copied!</div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2">
                            <span>Bulk Quantity</span>
                            <span id="count-val" class="text-white">5</span>
                        </div>
                        <input type="range" id="count-slider" min="1" max="1000" value="5" class="w-full">
                    </div>

                    <div class="space-y-3 bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Hyphens</span>
                            <div class="relative inline-block w-10 align-middle select-none">
                                <input type="checkbox" id="opt-hyphens" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                <label for="opt-hyphens" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Uppercase</span>
                            <div class="relative inline-block w-10 align-middle select-none">
                                <input type="checkbox" id="opt-uppercase" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                <label for="opt-uppercase" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Braces { }</span>
                            <div class="relative inline-block w-10 align-middle select-none">
                                <input type="checkbox" id="opt-braces" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                <label for="opt-braces" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                            </div>
                        </div>
                    </div>

                    <button id="generate-btn" class="w-full py-3 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                        Generate UUIDs
                    </button>

                </div>

                <div class="md:col-span-7 flex flex-col h-[500px]">
                    <div class="relative flex-grow">
                        <div class="flex justify-between items-center mb-2 px-1">
                            <span class="text-xs font-bold text-gray-500 uppercase">Results</span>
                            <div class="flex gap-2 text-xs">
                                <button onclick="download('txt')" class="text-gray-400 hover:text-white underline">.txt</button>
                                <button onclick="download('json')" class="text-gray-400 hover:text-white underline">.json</button>
                                <button onclick="download('csv')" class="text-gray-400 hover:text-white underline">.csv</button>
                            </div>
                        </div>
                        
                        <textarea id="output-area" readonly class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-gray-300 focus:outline-none resize-none mono-font text-sm leading-relaxed shadow-inner"></textarea>
                        
                        <button id="copy-btn" class="absolute top-8 right-4 bg-gray-700 hover:bg-gray-600 text-white p-2 rounded-lg font-bold text-sm shadow-lg transition-colors border border-gray-500">
                            Copy All
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
        const outputArea = document.getElementById('output-area');
        const singleInput = document.getElementById('single-uuid');
        const countSlider = document.getElementById('count-slider');
        const countVal = document.getElementById('count-val');
        const generateBtn = document.getElementById('generate-btn');
        const copyBtn = document.getElementById('copy-btn');
        
        const optHyphens = document.getElementById('opt-hyphens');
        const optUppercase = document.getElementById('opt-uppercase');
        const optBraces = document.getElementById('opt-braces');

        let uuids = [];

        // --- CORE LOGIC ---

        function generateUUID() {
            // Use Crypto API for security
            if (crypto.randomUUID) {
                return crypto.randomUUID();
            } else {
                // Fallback for very old browsers (not likely needed today)
                return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
            }
        }

        function formatUUID(uuid) {
            let result = uuid;
            
            // 1. Hyphens
            if (!optHyphens.checked) {
                result = result.replace(/-/g, '');
            }

            // 2. Uppercase
            if (optUppercase.checked) {
                result = result.toUpperCase();
            }

            // 3. Braces
            if (optBraces.checked) {
                result = `{${result}}`;
            }

            return result;
        }

        function runGeneration() {
            const count = parseInt(countSlider.value);
            uuids = [];

            // Generate Batch
            for (let i = 0; i < count; i++) {
                const raw = generateUUID();
                uuids.push(formatUUID(raw));
            }

            // Update Single Hero (Just show the first one)
            singleInput.value = uuids[0];

            // Update Bulk Area
            outputArea.value = uuids.join('\n');
        }

        // --- ACTIONS ---

        function copySingle() {
            singleInput.select();
            document.execCommand('copy'); // Legacy
            if(navigator.clipboard) navigator.clipboard.writeText(singleInput.value);
            
            const toast = document.getElementById('single-toast');
            toast.classList.remove('opacity-0');
            setTimeout(() => toast.classList.add('opacity-0'), 1500);
        }

        function download(type) {
            let content = "";
            let mime = "text/plain";
            let filename = "uuids." + type;

            if (type === 'json') {
                content = JSON.stringify(uuids, null, 2);
                mime = "application/json";
            } else if (type === 'csv') {
                content = "UUID\n" + uuids.join('\n');
                mime = "text/csv";
            } else {
                content = uuids.join('\n');
            }

            const blob = new Blob([content], { type: mime });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // --- LISTENERS ---

        countSlider.addEventListener('input', (e) => {
            countVal.textContent = e.target.value;
        });

        generateBtn.addEventListener('click', runGeneration);
        
        // Auto-regenerate on setting change? Optional. Let's do it for snappy feel.
        [optHyphens, optUppercase, optBraces, countSlider].forEach(el => {
            el.addEventListener('change', runGeneration);
        });

        copyBtn.addEventListener('click', () => {
            outputArea.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(outputArea.value);
            
            const originalText = copyBtn.innerText;
            copyBtn.innerText = "COPIED!";
            copyBtn.classList.replace('bg-gray-700', 'bg-emerald-600');
            setTimeout(() => {
                copyBtn.innerText = originalText;
                copyBtn.classList.replace('bg-emerald-600', 'bg-gray-700');
            }, 1500);
        });

        // Init
        runGeneration();

    </script>
</body>
</html>