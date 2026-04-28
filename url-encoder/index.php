<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Encoder/Decoder | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Output Area Focus State */
        textarea:focus {
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.5); /* Amber glow */
        }

        /* Mode Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10b981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981;
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
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">URL Encoder / Decoder</h1>
                <p class="text-center text-gray-400">Safely encode or decode URL strings and parameters.</p>
            </div>

            <div class="flex flex-wrap justify-between items-end mb-4 gap-4">
                
                <div class="flex gap-2">
                    <button id="encode-btn" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors shadow-lg flex items-center gap-2">
                        <span>🔒</span> Encode
                    </button>
                    <button id="decode-btn" class="px-6 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-bold text-sm transition-colors shadow-lg flex items-center gap-2">
                        <span>🔓</span> Decode
                    </button>
                </div>

                <div class="flex items-center gap-6 bg-gray-900 px-4 py-2 rounded-lg border border-gray-700">
                    <div class="flex items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase mr-3">Live Mode</span>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="live-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                            <label for="live-toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                        </div>
                    </div>
                    
                    <button id="clear-btn" class="text-xs text-gray-500 hover:text-white underline">Clear All</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[450px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Input String</span>
                    </div>
                    <textarea id="url-input" class="mono-font w-full h-full bg-gray-900 text-gray-300 p-4 rounded-xl border border-gray-600 focus:outline-none resize-none placeholder-gray-700 leading-relaxed" placeholder="Paste URL here (e.g. https://example.com?q=hello world)"></textarea>
                </div>

                <div class="flex flex-col h-full relative">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Result</span>
                        <span id="status-msg" class="text-xs text-red-400 font-bold opacity-0 transition-opacity">Error: Malformed URI</span>
                    </div>
                    
                    <div class="relative w-full h-full">
                        <textarea id="url-output" readonly class="mono-font w-full h-full bg-gray-900 text-emerald-400 p-4 rounded-xl border border-gray-600 focus:outline-none resize-none leading-relaxed"></textarea>
                        
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
        const input = document.getElementById('url-input');
        const output = document.getElementById('url-output');
        const encodeBtn = document.getElementById('encode-btn');
        const decodeBtn = document.getElementById('decode-btn');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        const liveToggle = document.getElementById('live-toggle');
        const statusMsg = document.getElementById('status-msg');

        let lastAction = 'encode'; // State memory for live mode

        // --- CORE LOGIC ---

        function processURL(action) {
            const val = input.value;
            lastAction = action;

            if (!val) {
                output.value = '';
                hideError();
                return;
            }

            try {
                let result = '';
                if (action === 'encode') {
                    result = encodeURIComponent(val);
                    // Optional: Make it more readable by NOT encoding common URL chars? 
                    // Usually developers want full encoding, but strict encodeURIComponent 
                    // encodes : / ? etc. which breaks full URLs.
                    // Let's stick to standard strict encoding for safety.
                } else {
                    result = decodeURIComponent(val);
                }
                
                output.value = result;
                output.classList.replace('text-red-400', 'text-emerald-400');
                hideError();
            } catch (e) {
                showError();
                output.value = "Error: Malformed URI sequence (usually a % symbol followed by invalid hex).";
                output.classList.replace('text-emerald-400', 'text-red-400');
            }
        }

        // --- UI HELPERS ---

        function showError() {
            statusMsg.classList.remove('opacity-0');
        }

        function hideError() {
            statusMsg.classList.add('opacity-0');
        }

        // --- EVENT LISTENERS ---

        encodeBtn.addEventListener('click', () => processURL('encode'));
        decodeBtn.addEventListener('click', () => processURL('decode'));

        // Live Mode Logic
        input.addEventListener('input', () => {
            if (liveToggle.checked) {
                // Auto-detect? It's hard. 
                // Easiest to just perform the LAST user action (encode or decode).
                processURL(lastAction);
            }
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            output.value = '';
            hideError();
            input.focus();
        });

        copyBtn.addEventListener('click', () => {
            output.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(output.value);
            
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">OK</span>`;
            setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
        });

        // Initialize focus
        input.focus();

    </script>
</body>
</html>