<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer's Studio | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Color+Emoji&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }
        .emoji-font { font-family: 'Noto Color Emoji', sans-serif; }

        /* Sidebar Navigation */
        .nav-btn {
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .nav-btn:hover { background-color: #374151; }
        .nav-btn.active {
            background-color: #1f2937;
            border-left-color: #f59e0b; /* Amber */
            color: white;
        }
        .nav-btn.active .icon { color: #f59e0b; }

        /* Tool Views */
        .tool-view { display: none; animation: fadeIn 0.3s ease; }
        .tool-view.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- GRAMMAR CHECKER STYLES --- */
        .grammar-container {
            position: relative;
            font-family: 'Inter', sans-serif;
            font-size: 1.125rem; /* text-lg */
            line-height: 1.75;
        }

        /* The backdrop renders the highlights */
        #gc-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Let clicks pass to textarea, but... */
            white-space: pre-wrap;
            word-wrap: break-word;
            color: transparent;
            z-index: 1;
            padding: 1.5rem; /* Match textarea padding */
        }

        /* The actual input */
        #gc-input {
            position: relative;
            z-index: 2;
            background: transparent;
            color: #e5e7eb; /* Gray-200 */
            caret-color: white;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Error Underlines */
        .err {
            position: relative;
            cursor: pointer;
            pointer-events: auto; /* Re-enable clicking on the error span */
            border-bottom-width: 2px;
            border-bottom-style: solid;
            padding-bottom: 1px;
        }
        .err:hover { opacity: 0.8; }

        .err-spelling { border-bottom-color: #ef4444; background: rgba(239, 68, 68, 0.2); } /* Red */
        .err-grammar { border-bottom-color: #f59e0b; background: rgba(245, 158, 11, 0.2); } /* Amber */
        .err-style { border-bottom-color: #3b82f6; background: rgba(59, 130, 246, 0.2); } /* Blue */

        /* The Suggestion Tooltip */
        #gc-tooltip {
            position: absolute;
            z-index: 50;
            background: #1f2937;
            border: 1px solid #4b5563;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            display: none;
            min-width: 150px;
        }
        .suggestion-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            color: #10b981;
            transition: bg 0.2s;
        }
        .suggestion-btn:hover { background: #374151; }
        .suggestion-desc {
            font-size: 0.75rem;
            color: #9ca3af;
            padding: 4px 12px;
            border-top: 1px solid #374151;
            margin-top: 4px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Emoji Grid */
        .emoji-btn:hover { transform: scale(1.2); z-index: 10; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <div class="flex-grow flex flex-col md:flex-row max-w-7xl mx-auto w-full h-[calc(100vh-100px)] overflow-hidden rounded-xl border border-gray-700 bg-gray-800 shadow-2xl mb-8">
        
        <aside class="w-full md:w-64 bg-gray-900 flex-shrink-0 border-r border-gray-700 overflow-y-auto custom-scrollbar flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Toolkit</h2>
            </div>
            
            <nav class="flex-grow py-2 space-y-1">
                <div class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-600 uppercase">Analysis</div>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="word-counter">
                    <span class="icon text-lg">📊</span> Word Counter
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="grammar-check">
                    <span class="icon text-lg">📝</span> Grammar & Style
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="readability">
                    <span class="icon text-lg">👓</span> Readability
                </button>

                <div class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-600 uppercase">Transform</div>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="case-converter">
                    <span class="icon text-lg">🔠</span> Case Converter
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="find-replace">
                    <span class="icon text-lg">🔍</span> Find & Replace
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="slug-gen">
                    <span class="icon text-lg">🐌</span> Slug Generator
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="flip-text">
                    <span class="icon text-lg">🙃</span> Flip Text
                </button>

                <div class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-600 uppercase">Lists</div>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="dedupe">
                    <span class="icon text-lg">✂️</span> Dedupe List
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="sorter">
                    <span class="icon text-lg">🔤</span> List Sorter
                </button>

                <div class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-600 uppercase">Generate</div>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="password-gen">
                    <span class="icon text-lg">🔒</span> Passwords
                </button>
                <button class="nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-3" data-target="emoji-picker">
                    <span class="icon text-lg">😀</span> Emoji Picker
                </button>
            </nav>
        </aside>

        <div class="flex-grow bg-gray-800 p-6 md:p-8 overflow-y-auto custom-scrollbar relative">
            
            <div id="word-counter" class="tool-view active">
                <h2 class="text-2xl font-bold text-white mb-4">Word Counter</h2>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 text-center"><div class="text-xs text-gray-500 uppercase">Words</div><div id="wc-words" class="text-2xl font-bold text-emerald-400">0</div></div>
                    <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 text-center"><div class="text-xs text-gray-500 uppercase">Chars</div><div id="wc-chars" class="text-2xl font-bold text-blue-400">0</div></div>
                    <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 text-center"><div class="text-xs text-gray-500 uppercase">Sentences</div><div id="wc-sent" class="text-2xl font-bold text-amber-400">0</div></div>
                    <div class="bg-gray-900 p-3 rounded-lg border border-gray-700 text-center"><div class="text-xs text-gray-500 uppercase">Paragraphs</div><div id="wc-para" class="text-2xl font-bold text-purple-400">0</div></div>
                </div>
                <textarea id="wc-input" class="w-full h-96 bg-gray-900 border border-gray-600 rounded-xl p-6 text-gray-300 focus:outline-none focus:border-emerald-500 resize-none leading-relaxed shadow-inner text-lg" placeholder="Start typing..."></textarea>
            </div>

            <div id="grammar-check" class="tool-view">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-white">Grammar & Style</h2>
                    <div class="flex gap-4 text-xs font-bold">
                        <span class="flex items-center gap-1 text-red-400"><span class="w-2 h-2 rounded-full bg-red-500"></span> Error</span>
                        <span class="flex items-center gap-1 text-amber-400"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Grammar</span>
                        <span class="flex items-center gap-1 text-blue-400"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Style</span>
                    </div>
                </div>
                
                <div class="grammar-container w-full h-[500px] bg-gray-900 border border-gray-600 rounded-xl shadow-inner overflow-hidden relative">
                    <div id="gc-backdrop"></div>
                    <textarea id="gc-input" spellcheck="false" class="absolute inset-0 w-full h-full p-6 focus:outline-none resize-none" placeholder="Type here. Try 'Can you recieve there new phone for them.'"></textarea>
                    <div id="gc-tooltip"></div>
                </div>
                
                <div class="flex justify-between mt-4">
                    <p class="text-xs text-gray-500">Click highlights to fix. Dictionary is client-side (English).</p>
                    <button id="gc-fix-all" class="text-xs text-emerald-400 font-bold hover:underline hidden">Fix All</button>
                </div>
            </div>

            <div id="readability" class="tool-view">
                <h2 class="text-2xl font-bold text-white mb-4">Readability Analyzer</h2>
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-700 text-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">Flesch-Kincaid Grade</span>
                        <div id="rb-grade" class="text-5xl font-black text-white my-2">0.0</div>
                        <div id="rb-label" class="text-sm text-emerald-400 font-bold">No Text</div>
                    </div>
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-700 flex flex-col justify-center">
                        <div class="flex justify-between mb-2"><span class="text-gray-400">Reading Ease</span><span id="rb-ease" class="text-white font-bold">0</span></div>
                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden"><div id="rb-bar" class="h-full bg-blue-500 w-0 transition-all duration-500"></div></div>
                        <div class="flex justify-between mt-4 mb-2"><span class="text-gray-400">Complex Words</span><span id="rb-complex" class="text-amber-400 font-bold">0</span></div>
                    </div>
                </div>
                <textarea id="rb-input" class="w-full h-64 bg-gray-900 border border-gray-600 rounded-xl p-6 text-gray-300 focus:outline-none focus:border-blue-500 resize-none leading-relaxed text-lg" placeholder="Paste text to analyze..."></textarea>
            </div>

            <div id="case-converter" class="tool-view">
                <h2 class="text-2xl font-bold text-white mb-4">Case Converter</h2>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-sm font-bold" onclick="convertCase('upper')">UPPER</button>
                    <button class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-sm font-bold" onclick="convertCase('lower')">lower</button>
                    <button class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-sm font-bold" onclick="convertCase('title')">Title Case</button>
                </div>
                <textarea id="cc-input" class="w-full h-96 bg-gray-900 border border-gray-600 rounded-xl p-6 text-gray-300 focus:outline-none focus:border-emerald-500 resize-none text-lg"></textarea>
            </div>

            </div>
    </div>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- ROUTER ---
        const navBtns = document.querySelectorAll('.nav-btn');
        const views = document.querySelectorAll('.tool-view');
        const lastTool = localStorage.getItem('dibber-writer-tool') || 'grammar-check'; // Default to new tool
        switchTool(lastTool);

        navBtns.forEach(btn => btn.addEventListener('click', () => switchTool(btn.dataset.target)));

        function switchTool(targetId) {
            navBtns.forEach(b => {
                if(b.dataset.target === targetId) b.classList.add('active');
                else b.classList.remove('active');
            });
            views.forEach(v => {
                if(v.id === targetId) v.classList.add('active');
                else v.classList.remove('active');
            });
            localStorage.setItem('dibber-writer-tool', targetId);
        }

        // ================= GRAMMAR CHECKER ENGINE =================
        const gcInput = document.getElementById('gc-input');
        const gcBackdrop = document.getElementById('gc-backdrop');
        const gcTooltip = document.getElementById('gc-tooltip');

        // Rules Database
        const rules = [
            // 1. Common Typos (Spelling) - Type: err-spelling
            { regex: /\brecieve\b/gi, fix: "receive", type: "err-spelling", desc: "Spelling error" },
            { regex: /\bseperate\b/gi, fix: "separate", type: "err-spelling", desc: "Spelling error" },
            { regex: /\bdefinately\b/gi, fix: "definitely", type: "err-spelling", desc: "Spelling error" },
            { regex: /\boccured\b/gi, fix: "occurred", type: "err-spelling", desc: "Spelling error" },
            { regex: /\btruely\b/gi, fix: "truly", type: "err-spelling", desc: "Spelling error" },
            { regex: /\balot\b/gi, fix: "a lot", type: "err-spelling", desc: "Spelling error" },
            
            // 2. Homophones / Grammar (Heuristic) - Type: err-grammar
            // "There" followed by a word ending in 's' (plural noun?) or generic noun heuristics is hard.
            // Let's use simple phrase matching for high accuracy.
            { regex: /\bthere\s+(?:new|old|big|small|own)\b/gi, fix: "their", type: "err-grammar", desc: "Possessive form needed?" }, 
            { regex: /\byour\s+(?:welcome|going|doing)\b/gi, fix: "you're", type: "err-grammar", desc: "You are" },
            { regex: /\bshould\s+of\b/gi, fix: "should have", type: "err-grammar", desc: "Grammar error" },
            { regex: /\birregardless\b/gi, fix: "regardless", type: "err-grammar", desc: "Non-standard word" },

            // 3. Punctuation - Type: err-grammar
            { regex: /\s+,/g, fix: ",", type: "err-grammar", desc: "Remove space before comma" },
            { regex: /\s+\./g, fix: ".", type: "err-grammar", desc: "Remove space before period" },
            { regex: /\.\./g, fix: ".", type: "err-grammar", desc: "Double period" },
            { regex: /,\s+(?:and|or)\b/gi, fix: (m)=> m.replace(',',''), type: "err-spelling", desc: "Oxford Comma detected" }, // Oxford Comma killer (Red)
            
            // 4. Style (Weasel Words) - Type: err-style
            { regex: /\b(very|really|just|quite|literally|basically)\b/gi, fix: "", type: "err-style", desc: "Weasel word (omit?)" },
            
            // 5. Passive Voice (Simple Heuristic: was/were + ed)
            { regex: /\b(was|were|is|are|been)\s+(\w+ed)\b/gi, fix: "active voice", type: "err-style", desc: "Passive voice detected" },

            // 6. Sentence Ends
            // Questions starting with Can/Who/What ending in period
            { regex: /\b(Can|Could|Who|What|Where|When|How|Why)\s+.*?\./g, fix: "?", type: "err-grammar", desc: "Question mark needed?" }
        ];

        // Core Checker
        function checkGrammar() {
            let text = gcInput.value;
            let html = text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");

            // We need to apply highlights without breaking HTML structure.
            // We'll iterate rules. Note: Multiple overlapping rules will break this simple regex replace approach.
            // For a robust solution, we'd tokenize. For this MVP, we process sequentially carefully.
            
            // Sort rules? No, apply specific matches first.
            
            rules.forEach(rule => {
                // If fix is function, run it, else use string
                // We wrap the match in a span with data attributes for the fix
                html = html.replace(rule.regex, (match) => {
                    let replacement = typeof rule.fix === 'function' ? rule.fix(match) : rule.fix;
                    // If the fix is "active voice", we don't have a direct string replacement, just a warning
                    let dataFix = replacement === "active voice" ? "" : replacement;
                    
                    return `<span class="err ${rule.type}" onclick="applyFix(this, '${dataFix}')" data-desc="${rule.desc}">${match}</span>`;
                });
            });

            // Handle trailing newlines
            if (html.endsWith("\n")) html += "<br>";
            
            gcBackdrop.innerHTML = html;
        }

        // Apply Fix
        window.applyFix = function(el, fix) {
            if(!fix) return; // Informational only
            
            // This is tricky: transforming the HTML click back to Text Input position.
            // Simplest hack: Use string replacement on the input value.
            // Limitation: If the word appears twice, might replace wrong one.
            // Robust solution requires cursor tracking.
            
            // Let's implement a simple replace for the FIRST occurrence relative to approximate cursor? 
            // Or just Global replace of that exact context?
            
            const wrong = el.innerText;
            const currentVal = gcInput.value;
            
            // replace ONE instance (simplistic)
            // Better: find the text index based on span visual logic? Hard.
            // Let's just do a replace.
            const newVal = currentVal.replace(wrong, fix);
            
            gcInput.value = newVal;
            checkGrammar();
            hideTooltip();
        };

        // Tooltip Logic
        document.addEventListener('mouseover', (e) => {
            if (e.target.classList.contains('err')) {
                const rect = e.target.getBoundingClientRect();
                const parentRect = gcInput.parentElement.getBoundingClientRect();
                
                const fix = e.target.getAttribute('onclick').match(/'([^']+)'/)[1];
                const desc = e.target.getAttribute('data-desc');

                gcTooltip.innerHTML = `
                    ${fix ? `<button class="suggestion-btn" onclick="document.elementFromPoint(${e.clientX}, ${e.clientY}).click()">Fix: ${fix}</button>` : ''}
                    <div class="suggestion-desc">${desc}</div>
                `;
                
                gcTooltip.style.top = (rect.bottom - parentRect.top) + 'px';
                gcTooltip.style.left = (rect.left - parentRect.left) + 'px';
                gcTooltip.style.display = 'block';
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.classList.contains('err') && !e.target.closest('#gc-tooltip')) {
                hideTooltip();
            }
        });

        function hideTooltip() {
            gcTooltip.style.display = 'none';
        }

        // Listeners
        gcInput.addEventListener('input', checkGrammar);
        gcInput.addEventListener('scroll', () => {
            gcBackdrop.scrollTop = gcInput.scrollTop;
            gcBackdrop.scrollLeft = gcInput.scrollLeft;
            hideTooltip();
        });

        // 1. WORD COUNTER Logic (Ported)
        const wcInput = document.getElementById('wc-input');
        wcInput.addEventListener('input', () => {
            const text = wcInput.value;
            document.getElementById('wc-chars').textContent = text.length;
            document.getElementById('wc-words').textContent = text.trim().split(/\s+/).filter(n => n).length;
            document.getElementById('wc-sent').textContent = text.split(/[.!?]+/).filter(n => n.trim()).length;
            document.getElementById('wc-para').textContent = text.split(/\n\n+/).filter(n => n.trim()).length;
        });

        // 2. READABILITY (Ported)
        document.getElementById('rb-input').addEventListener('input', (e) => {
            const text = e.target.value;
            const words = text.trim().split(/\s+/).filter(n => n).length || 1;
            const sentences = Math.max(1, text.split(/[.!?]+/).filter(n => n.trim()).length);
            const syllables = text.length / 3; 
            const grade = Math.max(0, (0.39 * (words/sentences) + 11.8 * (syllables/words) - 15.59)).toFixed(1);
            document.getElementById('rb-grade').textContent = grade;
            let ease = Math.min(100, Math.max(0, 206.835 - 1.015 * (words/sentences) - 84.6 * (syllables/words)));
            document.getElementById('rb-ease').textContent = Math.round(ease);
            document.getElementById('rb-bar').style.width = `${ease}%`;
        });

        // 3. CASE CONVERTER (Ported)
        function convertCase(type) {
            const el = document.getElementById('cc-input');
            let text = el.value;
            if (type === 'upper') text = text.toUpperCase();
            if (type === 'lower') text = text.toLowerCase();
            if (type === 'title') text = text.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
            el.value = text;
        }

    </script>
</body>
</html>