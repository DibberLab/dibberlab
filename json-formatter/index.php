<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON Formatter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Editor Areas */
        .code-editor {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            line-height: 1.5;
            tab-size: 2;
        }

        /* Syntax Highlighting Colors */
        .json-key { color: #93c5fd; }    /* blue-300 */
        .json-string { color: #a7f3d0; } /* emerald-200 */
        .json-number { color: #fcd34d; } /* amber-300 */
        .json-boolean { color: #f472b6; }/* pink-400 */
        .json-null { color: #9ca3af; }   /* gray-400 */

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center">
        <div class="w-full max-w-6xl mx-auto">
            
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-amber-400">JSON Formatter</h1>
                <p class="text-gray-400">Validate, beautify, and minify your JSON data.</p>
            </div>

            <div class="flex flex-wrap gap-3 mb-4 justify-between items-center bg-gray-800 p-3 rounded-xl border border-gray-700">
                <div class="flex gap-2">
                    <button id="format-btn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2">
                        <span>✨</span> Beautify
                    </button>
                    <button id="minify-btn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-bold text-sm transition-colors">
                        Compress
                    </button>
                    <button id="clear-btn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-bold text-sm transition-colors">
                        Clear
                    </button>
                </div>

                <div class="flex gap-2">
                    <button id="sample-btn" class="text-xs text-gray-500 hover:text-gray-300 underline">Load Sample</button>
                    <div class="w-px h-4 bg-gray-600 my-auto"></div>
                    <button id="copy-btn" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2">
                        <span>📋</span> Copy Result
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-[600px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2 px-1">
                        <span>Raw JSON</span>
                        <span id="char-count">0 chars</span>
                    </div>
                    <textarea id="json-input" class="code-editor w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none" placeholder="Paste your messy JSON here..."></textarea>
                </div>

                <div class="flex flex-col h-full relative">
                    <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2 px-1">
                        <span>Formatted Result</span>
                        <span id="status-msg" class="text-emerald-400 opacity-0 transition-opacity">Valid JSON</span>
                    </div>
                    
                    <div class="relative w-full h-full">
                        <pre id="json-output" class="code-editor w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 overflow-auto whitespace-pre-wrap break-all"></pre>
                        
                        <div id="error-overlay" class="absolute inset-0 bg-gray-900/90 rounded-xl flex items-center justify-center hidden backdrop-blur-sm">
                            <div class="bg-red-900/50 border border-red-500 p-6 rounded-lg max-w-sm text-center">
                                <div class="text-2xl mb-2">🚫</div>
                                <h3 class="font-bold text-red-200 mb-2">Invalid JSON</h3>
                                <p id="error-msg" class="text-sm text-red-300 font-mono"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const input = document.getElementById('json-input');
        const output = document.getElementById('json-output');
        const errorOverlay = document.getElementById('error-overlay');
        const errorMsg = document.getElementById('error-msg');
        const statusMsg = document.getElementById('status-msg');
        const charCount = document.getElementById('char-count');
        const copyBtn = document.getElementById('copy-btn');

        // Sample Data
        const sampleData = {
            "project": "Dibber Lab",
            "active": true,
            "tools_count": 100,
            "tags": ["dev", "music", "utility"],
            "meta": {
                "created": 2024,
                "author": null
            }
        };

        // --- CORE FUNCTIONS ---

        function processJSON(minify = false) {
            const raw = input.value.trim();
            
            // Update Char Count
            charCount.textContent = `${raw.length.toLocaleString()} chars`;

            if (!raw) {
                output.textContent = '';
                hideError();
                return;
            }

            try {
                // 1. Parse
                const parsed = JSON.parse(raw);
                
                // 2. Stringify (Pretty or Minified)
                let result = '';
                if (minify) {
                    result = JSON.stringify(parsed);
                    // No syntax highlighting for minified (performance)
                    output.textContent = result;
                    // Reset colors
                    output.className = "code-editor w-full h-full bg-gray-800 text-gray-400 p-4 rounded-xl border border-gray-700 overflow-auto break-all";
                } else {
                    result = JSON.stringify(parsed, null, 2); // 2 spaces indent
                    // Apply Syntax Highlighting
                    output.innerHTML = syntaxHighlight(result);
                    // Reset class
                    output.className = "code-editor w-full h-full bg-gray-800 text-gray-300 p-4 rounded-xl border border-gray-700 overflow-auto whitespace-pre-wrap break-all";
                }

                showValidStatus();
                hideError();

            } catch (e) {
                showError(e.message);
            }
        }

        // Regex Syntax Highlighter
        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                let cls = 'json-number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'json-key';
                    } else {
                        cls = 'json-string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'json-boolean';
                } else if (/null/.test(match)) {
                    cls = 'json-null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        // --- UI HANDLERS ---

        function showError(msg) {
            errorMsg.textContent = msg;
            errorOverlay.classList.remove('hidden');
            statusMsg.classList.remove('opacity-100');
            statusMsg.classList.add('opacity-0');
        }

        function hideError() {
            errorOverlay.classList.add('hidden');
        }

        function showValidStatus() {
            statusMsg.textContent = "Valid JSON";
            statusMsg.className = "text-emerald-400 font-bold uppercase transition-opacity opacity-100";
            setTimeout(() => {
                statusMsg.classList.remove('opacity-100');
                statusMsg.classList.add('opacity-0');
            }, 2000);
        }

        // --- EVENT LISTENERS ---

        document.getElementById('format-btn').addEventListener('click', () => processJSON(false));
        document.getElementById('minify-btn').addEventListener('click', () => processJSON(true));
        
        document.getElementById('clear-btn').addEventListener('click', () => {
            input.value = '';
            output.textContent = '';
            charCount.textContent = '0 chars';
            hideError();
            input.focus();
        });

        document.getElementById('sample-btn').addEventListener('click', () => {
            input.value = JSON.stringify(sampleData);
            processJSON(false);
        });

        copyBtn.addEventListener('click', () => {
            // Get text content (removes HTML tags from syntax highlight)
            const textToCopy = output.textContent;
            if(!textToCopy) return;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = "<span>✅</span> Copied!";
                copyBtn.classList.replace('bg-amber-600', 'bg-emerald-600');
                copyBtn.classList.replace('hover:bg-amber-500', 'hover:bg-emerald-500');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.replace('bg-emerald-600', 'bg-amber-600');
                    copyBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-amber-500');
                }, 2000);
            });
        });

        // Auto-format on paste (optional quality of life)
        input.addEventListener('paste', () => {
            setTimeout(() => processJSON(false), 50);
        });

        // Initialize empty
        processJSON(false);

    </script>
</body>
</html>