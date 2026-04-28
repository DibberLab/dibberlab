<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Counter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Stats Cards */
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b; /* Amber */
        }

        /* Keyword Badge */
        .keyword-badge {
            background-color: #374151;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
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
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Word Counter</h1>
                <p class="text-center text-gray-400">Analyze your text for length, readability, and keywords.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Words</span>
                    <div id="stat-words" class="text-3xl font-bold text-white mt-1">0</div>
                </div>
                <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Characters</span>
                    <div id="stat-chars" class="text-3xl font-bold text-emerald-400 mt-1">0</div>
                </div>
                <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Sentences</span>
                    <div id="stat-sentences" class="text-3xl font-bold text-blue-400 mt-1">0</div>
                </div>
                <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Paragraphs</span>
                    <div id="stat-paragraphs" class="text-3xl font-bold text-purple-400 mt-1">0</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[600px] lg:h-[500px]">
                
                <div class="lg:col-span-8 flex flex-col h-full relative">
                    <div class="absolute top-0 right-0 p-2 flex gap-2 z-10">
                        <button id="copy-btn" class="bg-gray-700 hover:bg-gray-600 text-gray-300 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors border border-gray-600">
                            Copy Text
                        </button>
                        <button id="clear-btn" class="bg-gray-700 hover:bg-red-900/50 hover:text-red-300 text-gray-300 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors border border-gray-600">
                            Clear
                        </button>
                    </div>
                    <textarea id="text-input" class="w-full h-full bg-gray-800 text-gray-200 p-6 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none leading-relaxed text-lg shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Start typing or paste your text here..."></textarea>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-6 h-full overflow-y-auto custom-scrollbar pr-2">
                    
                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <span>⏱️</span> Time Estimates
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Reading Time</span>
                                <span id="time-read" class="font-mono text-emerald-400">0 min</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Speaking Time</span>
                                <span id="time-speak" class="font-mono text-blue-400">0 min</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <span>📊</span> Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Avg Word Length</span>
                                <span id="avg-word" class="font-mono text-gray-200">0 chars</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Longest Word</span>
                                <span id="longest-word" class="font-mono text-gray-200 truncate max-w-[120px]">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 flex-grow">
                        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <span>🔑</span> Top Keywords
                        </h3>
                        <div id="keywords-list" class="space-y-1">
                            <p class="text-xs text-gray-500 italic">Start typing to see density.</p>
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
        const input = document.getElementById('text-input');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        
        // Stats Elements
        const elWords = document.getElementById('stat-words');
        const elChars = document.getElementById('stat-chars');
        const elSentences = document.getElementById('stat-sentences');
        const elParagraphs = document.getElementById('stat-paragraphs');
        const elReadTime = document.getElementById('time-read');
        const elSpeakTime = document.getElementById('time-speak');
        const elAvgWord = document.getElementById('avg-word');
        const elLongest = document.getElementById('longest-word');
        const keywordsList = document.getElementById('keywords-list');

        // Constants
        const WPM_READ = 225; // Average reading speed
        const WPM_SPEAK = 130; // Average speaking speed

        function analyzeText() {
            const text = input.value;
            
            // 1. Basic Counts
            const chars = text.length;
            
            // Words: Split by whitespace, filter empty
            const wordsArr = text.trim().split(/\s+/).filter(w => w.length > 0);
            const words = wordsArr.length;

            // Sentences: Split by . ! ? 
            // Filter empty to avoid counting trailing punctuation as new sentences
            const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0).length;

            // Paragraphs: Split by double newline
            const paragraphs = text.split(/\n\n+/).filter(p => p.trim().length > 0).length;

            // 2. Update Basic Stats
            elChars.textContent = chars.toLocaleString();
            elWords.textContent = words.toLocaleString();
            elSentences.textContent = sentences.toLocaleString();
            elParagraphs.textContent = paragraphs.toLocaleString();

            // 3. Time Estimates
            const readTime = Math.ceil(words / WPM_READ);
            const speakTime = Math.ceil(words / WPM_SPEAK);
            
            elReadTime.textContent = readTime < 1 ? "< 1 min" : `~${readTime} min`;
            elSpeakTime.textContent = speakTime < 1 ? "< 1 min" : `~${speakTime} min`;

            // 4. Details
            if (words > 0) {
                // Average Word Length
                const totalLen = wordsArr.reduce((acc, w) => acc + w.length, 0);
                elAvgWord.textContent = (totalLen / words).toFixed(1) + " chars";

                // Longest Word
                const longest = wordsArr.reduce((a, b) => a.length > b.length ? a : b, "");
                // Strip punctuation for display
                const cleanLongest = longest.replace(/[^\w]/g, '');
                elLongest.textContent = cleanLongest.length > 15 ? cleanLongest.substring(0,12)+"..." : cleanLongest;
                elLongest.title = cleanLongest;
            } else {
                elAvgWord.textContent = "0 chars";
                elLongest.textContent = "-";
            }

            // 5. Keyword Density
            analyzeKeywords(wordsArr);
        }

        function analyzeKeywords(wordsArr) {
            if (wordsArr.length === 0) {
                keywordsList.innerHTML = '<p class="text-xs text-gray-500 italic">Start typing to see density.</p>';
                return;
            }

            // Frequency Map
            const map = {};
            const stopWords = ["the", "and", "a", "an", "in", "on", "to", "of", "is", "it", "that", "this", "for", "with", "as", "are", "was", "at", "be"];
            
            wordsArr.forEach(w => {
                const clean = w.toLowerCase().replace(/[^a-z0-9]/g, '');
                if (clean.length > 2 && !stopWords.includes(clean)) {
                    map[clean] = (map[clean] || 0) + 1;
                }
            });

            // Sort by frequency
            const sorted = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 5);

            // Render
            if (sorted.length === 0) {
                keywordsList.innerHTML = '<p class="text-xs text-gray-500 italic">No significant keywords yet.</p>';
                return;
            }

            keywordsList.innerHTML = sorted.map(([word, count]) => `
                <div class="keyword-badge">
                    <span class="text-gray-300">${word}</span>
                    <span class="text-emerald-400 font-bold">${count}</span>
                </div>
            `).join('');
        }

        // --- LISTENERS ---
        input.addEventListener('input', analyzeText);

        clearBtn.addEventListener('click', () => {
            input.value = '';
            analyzeText();
            input.focus();
        });

        copyBtn.addEventListener('click', () => {
            if(!input.value) return;
            input.select();
            document.execCommand('copy'); // Legacy fallback
            if(navigator.clipboard) navigator.clipboard.writeText(input.value);
            
            const orig = copyBtn.innerText;
            copyBtn.innerText = "Copied!";
            copyBtn.classList.replace('bg-gray-700', 'bg-emerald-600');
            setTimeout(() => {
                copyBtn.innerText = orig;
                copyBtn.classList.replace('bg-emerald-600', 'bg-gray-700');
            }, 1500);
        });

        // Initialize
        analyzeText();

    </script>
</body>
</html>