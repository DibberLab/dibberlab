<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegEx Tester | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace font for editors */
        .mono-font {
            font-family: 'JetBrains Mono', monospace;
        }

        /* The Output/Highlight Layer */
        #result-layer {
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #9ca3af; /* Default gray text */
        }

        /* Highlighting Styles */
        .match {
            background-color: rgba(16, 185, 129, 0.2); /* Emerald Tint */
            border-bottom: 2px solid #10b981;
            color: white;
            border-radius: 2px;
        }
        .match-group {
            background-color: rgba(245, 158, 11, 0.2); /* Amber Tint for groups/alternates if we expanded logic */
        }

        /* Flag Buttons */
        .flag-btn {
            transition: all 0.2s;
            border: 1px solid #4b5563;
        }
        .flag-btn.active {
            background-color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            color: #111827;
            font-weight: bold;
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
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-4 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">RegEx Tester</h1>
                <p class="text-center text-gray-400">Test and debug regular expressions in real-time.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-gray-900 p-4 rounded-xl border border-gray-600">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Expression</label>
                            <span id="error-msg" class="text-xs text-red-400 font-mono hidden">Invalid Regex</span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-gray-500 font-mono text-xl">/</span>
                            <input type="text" id="regex-input" class="mono-font flex-grow bg-transparent text-xl text-white focus:outline-none placeholder-gray-700" placeholder="[a-z]+">
                            <span class="text-gray-500 font-mono text-xl">/</span>
                            
                            <div class="flex gap-1">
                                <button class="flag-btn active px-2 py-1 rounded text-xs text-gray-400" data-flag="g" title="Global match">g</button>
                                <button class="flag-btn active px-2 py-1 rounded text-xs text-gray-400" data-flag="i" title="Case insensitive">i</button>
                                <button class="flag-btn px-2 py-1 rounded text-xs text-gray-400" data-flag="m" title="Multiline">m</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col h-[400px]">
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Test String</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-500 uppercase">Matches:</span>
                                <span id="match-count" class="text-xs font-bold text-emerald-400 bg-emerald-900/50 px-2 py-1 rounded">0</span>
                            </div>
                        </div>

                        <div class="relative flex-grow bg-gray-900 rounded-xl border border-gray-600 overflow-hidden">
                            <div id="result-layer" class="mono-font absolute inset-0 p-4 w-full h-full pointer-events-none overflow-auto z-0 text-sm md:text-base leading-relaxed"></div>
                            
                            <textarea id="test-string" class="mono-font absolute inset-0 w-full h-full p-4 bg-transparent text-transparent caret-white resize-none focus:outline-none z-10 text-sm md:text-base leading-relaxed" spellcheck="false">Welcome to Dibber Lab. 
This is a sample text to test your regex.
Try finding numbers like 123 or words like "test".
Contact: hello@dibberlab.me</textarea>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-900 rounded-xl border border-gray-700 h-full max-h-[600px] flex flex-col">
                        <div class="p-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                            <h3 class="font-bold text-gray-300">Cheatsheet</h3>
                        </div>
                        <div class="overflow-y-auto p-4 space-y-4 custom-scrollbar flex-grow">
                            
                            <div>
                                <h4 class="text-xs font-bold text-amber-500 uppercase mb-2">Character Classes</h4>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between"><code class="text-blue-300">.</code> <span class="text-gray-500">Any character</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">\w</code> <span class="text-gray-500">Word char (a-z, 0-9, _)</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">\d</code> <span class="text-gray-500">Digit (0-9)</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">\s</code> <span class="text-gray-500">Whitespace</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">[abc]</code> <span class="text-gray-500">Any of a, b, or c</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">[^abc]</code> <span class="text-gray-500">Not a, b, or c</span></div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-amber-500 uppercase mb-2">Quantifiers</h4>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between"><code class="text-blue-300">*</code> <span class="text-gray-500">0 or more</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">+</code> <span class="text-gray-500">1 or more</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">?</code> <span class="text-gray-500">0 or 1</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">{3}</code> <span class="text-gray-500">Exactly 3</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">{3,}</code> <span class="text-gray-500">3 or more</span></div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-amber-500 uppercase mb-2">Anchors</h4>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between"><code class="text-blue-300">^</code> <span class="text-gray-500">Start of line</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">$</code> <span class="text-gray-500">End of line</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">\b</code> <span class="text-gray-500">Word boundary</span></div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-amber-500 uppercase mb-2">Groups</h4>
                                <div class="space-y-1 text-sm">
                                    <div class="flex justify-between"><code class="text-blue-300">(...)</code> <span class="text-gray-500">Capture group</span></div>
                                    <div class="flex justify-between"><code class="text-blue-300">(a|b)</code> <span class="text-gray-500">Alternate (OR)</span></div>
                                </div>
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
        const regexInput = document.getElementById('regex-input');
        const testString = document.getElementById('test-string');
        const resultLayer = document.getElementById('result-layer');
        const errorMsg = document.getElementById('error-msg');
        const matchCount = document.getElementById('match-count');
        const flagBtns = document.querySelectorAll('.flag-btn');

        // State
        let flags = {
            g: true,
            i: true,
            m: false
        };

        // --- CORE LOGIC ---

        function update() {
            const pattern = regexInput.value;
            const text = testString.value;
            
            // Build Flags String
            let flagsStr = '';
            if (flags.g) flagsStr += 'g';
            if (flags.i) flagsStr += 'i';
            if (flags.m) flagsStr += 'm';

            // 1. Validate Regex
            let regex;
            try {
                regex = new RegExp(pattern, flagsStr);
                errorMsg.classList.add('hidden');
                regexInput.classList.remove('text-red-400');
            } catch (e) {
                errorMsg.classList.remove('hidden');
                regexInput.classList.add('text-red-400');
                matchCount.textContent = "Error";
                resultLayer.textContent = text; // Just show plain text
                return;
            }

            if (!pattern) {
                resultLayer.textContent = text;
                matchCount.textContent = "0";
                return;
            }

            // 2. Find Matches
            // We use a safe replace method to insert spans without breaking HTML
            let matchCountNum = 0;
            
            // Escape HTML in the source text first to prevent XSS in the preview
            // But we need to keep the structure so regex still matches positions correctly... 
            // Actually, best approach for visualizer is:
            // 1. Find all matches (indices) on raw text
            // 2. Rebuild string with escaped segments + wrapped segments
            
            let matches = [];
            try {
                // If global flag is not set, match/exec behaves differently. 
                // For highlighting, we force Global internally to find all, or just find one?
                // Let's iterate using exec
                
                // Note: If user turned off 'g', we only highlight the first match
                if (!flags.g) {
                    const m = regex.exec(text);
                    if (m) matches.push({ index: m.index, length: m[0].length, content: m[0] });
                } else {
                    let match;
                    // Prevent infinite loops with zero-length matches (like ^ or $ or *)
                    while ((match = regex.exec(text)) !== null) {
                        matches.push({ index: match.index, length: match[0].length, content: match[0] });
                        if (match.index === regex.lastIndex) {
                            regex.lastIndex++;
                        }
                    }
                }
            } catch (e) {
                // Regex runtime error (e.g. infinite lookbehind)
                return;
            }

            matchCountNum = matches.length;
            matchCount.textContent = matchCountNum;

            // 3. Reconstruct String with Highlights
            let formattedHtml = '';
            let lastIndex = 0;

            matches.forEach(m => {
                // Append text before match (escaped)
                formattedHtml += escapeHtml(text.substring(lastIndex, m.index));
                
                // Append match (wrapped)
                // If length is 0 (anchor match), we show a thin bar? 
                // Visually, 0 width chars are hard. We might skip them or add a cursor marker.
                // For simplicity, we only wrap content > 0 length.
                if (m.length > 0) {
                    formattedHtml += `<span class="match">${escapeHtml(m.content)}</span>`;
                } else {
                    // Zero width match visualization (optional)
                    // formattedHtml += `<span class="border-l-2 border-amber-500 mx-[1px]"></span>`;
                }

                lastIndex = m.index + m.length;
            });

            // Append remaining text
            formattedHtml += escapeHtml(text.substring(lastIndex));

            // Fix for trailing newline visualization
            if (formattedHtml.endsWith('\n')) {
                formattedHtml += '<br>'; 
            }

            resultLayer.innerHTML = formattedHtml;
        }

        // Helper: Escape HTML characters
        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // --- SCROLL SYNC ---
        // Keeps the textarea and the highlight layer strictly aligned
        const syncScroll = () => {
            resultLayer.scrollTop = testString.scrollTop;
            resultLayer.scrollLeft = testString.scrollLeft;
        };

        // --- LISTENERS ---

        testString.addEventListener('input', update);
        testString.addEventListener('scroll', syncScroll);
        regexInput.addEventListener('input', update);

        flagBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const flag = btn.dataset.flag;
                flags[flag] = !flags[flag];
                
                if (flags[flag]) btn.classList.add('active');
                else btn.classList.remove('active');
                
                update();
            });
        });

        // Initialize
        update();

    </script>
</body>
</html>