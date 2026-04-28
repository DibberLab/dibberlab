<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readability Score | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Gradient Text */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(to right, #34d399, #3b82f6);
        }

        /* Score Meter */
        .meter-container {
            height: 8px;
            background: #374151;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        .meter-fill {
            height: 100%;
            transition: width 0.5s ease, background-color 0.5s ease;
        }

        /* Stat Card */
        .stat-card {
            transition: transform 0.2s, border-color 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #f59e0b;
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
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Readability Score</h1>
                <p class="text-center text-gray-400">Analyze text complexity using Flesch-Kincaid & Gunning Fog.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-auto lg:h-[600px]">
                
                <div class="lg:col-span-7 flex flex-col h-full relative">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input Text</label>
                        <button id="sample-btn" class="text-xs text-emerald-400 hover:text-emerald-300 underline">Load Sample</button>
                    </div>
                    
                    <textarea id="text-input" class="w-full h-full bg-gray-800 text-gray-200 p-6 rounded-xl border border-gray-700 focus:outline-none focus:border-amber-500 resize-none leading-relaxed text-lg shadow-inner custom-scrollbar placeholder-gray-600" placeholder="Paste your text here to analyze..."></textarea>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-6 h-full overflow-y-auto custom-scrollbar pr-2">
                    
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-blue-500"></div>
                        
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Flesch-Kincaid Grade</p>
                        <div class="text-6xl font-black text-white mb-2" id="grade-val">0.0</div>
                        <div class="inline-block px-4 py-1 rounded-full bg-gray-700 text-emerald-400 font-bold text-sm" id="grade-label">
                            No Text
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-gray-300">Reading Ease</span>
                            <span id="ease-val" class="text-2xl font-bold text-white">0</span>
                        </div>
                        <div class="meter-container mb-2">
                            <div id="ease-bar" class="meter-fill bg-gray-600" style="width: 0%"></div>
                        </div>
                        <p id="ease-desc" class="text-xs text-gray-500 text-right">0-100 Scale (Higher is easier)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-xs text-gray-500 font-bold uppercase">Sentences</span>
                            <div id="stat-sentences" class="text-2xl font-bold text-white">0</div>
                        </div>
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-xs text-gray-500 font-bold uppercase">Words</span>
                            <div id="stat-words" class="text-2xl font-bold text-white">0</div>
                        </div>
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-xs text-gray-500 font-bold uppercase">Syllables</span>
                            <div id="stat-syllables" class="text-2xl font-bold text-white">0</div>
                        </div>
                        <div class="stat-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-xs text-gray-500 font-bold uppercase">Complex Words</span>
                            <div id="stat-complex" class="text-2xl font-bold text-amber-400">0</div>
                        </div>
                    </div>

                    <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700/50">
                        <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">Other Algorithms</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Gunning Fog</span>
                                <span id="score-fog" class="font-mono text-white font-bold">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Coleman-Liau</span>
                                <span id="score-cl" class="font-mono text-white font-bold">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">SMOG Index</span>
                                <span id="score-smog" class="font-mono text-white font-bold">-</span>
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
        const input = document.getElementById('text-input');
        const sampleBtn = document.getElementById('sample-btn');
        
        // Displays
        const gradeVal = document.getElementById('grade-val');
        const gradeLabel = document.getElementById('grade-label');
        const easeVal = document.getElementById('ease-val');
        const easeBar = document.getElementById('ease-bar');
        const easeDesc = document.getElementById('ease-desc');
        
        const statSentences = document.getElementById('stat-sentences');
        const statWords = document.getElementById('stat-words');
        const statSyllables = document.getElementById('stat-syllables');
        const statComplex = document.getElementById('stat-complex');
        
        const scoreFog = document.getElementById('score-fog');
        const scoreCl = document.getElementById('score-cl');
        const scoreSmog = document.getElementById('score-smog');

        // Sample Text (Gettysburg Address)
        const sampleText = `Four score and seven years ago our fathers brought forth on this continent, a new nation, conceived in Liberty, and dedicated to the proposition that all men are created equal.

Now we are engaged in a great civil war, testing whether that nation, or any nation so conceived and so dedicated, can long endure. We are met on a great battle-field of that war. We have come to dedicate a portion of that field, as a final resting place for those who here gave their lives that that nation might live. It is altogether fitting and proper that we should do this.`;

        // --- ANALYSIS LOGIC ---

        function countSyllables(word) {
            word = word.toLowerCase().replace(/[^a-z]/g, '');
            if (word.length <= 3) return 1;
            
            // Basic heuristic rules
            word = word.replace(/(?:[^laeiouy]es|ed|[^laeiouy]e)$/, '');
            word = word.replace(/^y/, '');
            
            const matches = word.match(/[aeiouy]{1,2}/g);
            return matches ? matches.length : 1;
        }

        function analyze() {
            const text = input.value.trim();
            if (!text) return resetStats();

            // 1. Tokenize
            // Sentences: Split by . ! ?
            const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
            const sentenceCount = Math.max(1, sentences.length);

            // Words: Split by whitespace
            const words = text.match(/\b\w+\b/g) || [];
            const wordCount = Math.max(1, words.length);

            // Syllables & Complex Words
            let syllableCount = 0;
            let complexCount = 0; // Words with 3+ syllables

            words.forEach(w => {
                const s = countSyllables(w);
                syllableCount += s;
                if (s >= 3) complexCount++;
            });

            // Characters (for Coleman-Liau)
            const charCount = text.replace(/[^a-zA-Z0-9]/g, '').length;

            // --- CALCULATE SCORES ---

            // 1. Flesch-Kincaid Grade Level
            // 0.39 * (words/sentences) + 11.8 * (syllables/words) - 15.59
            const fkGrade = (0.39 * (wordCount / sentenceCount)) + (11.8 * (syllableCount / wordCount)) - 15.59;

            // 2. Flesch Reading Ease
            // 206.835 - 1.015 * (words/sentences) - 84.6 * (syllables/words)
            const fEase = 206.835 - (1.015 * (wordCount / sentenceCount)) - (84.6 * (syllableCount / wordCount));

            // 3. Gunning Fog Index
            // 0.4 * ( (words/sentences) + 100 * (complex/words) )
            const fog = 0.4 * ((wordCount / sentenceCount) + 100 * (complexCount / wordCount));

            // 4. Coleman-Liau Index
            // L = Avg chars per 100 words, S = Avg sentences per 100 words
            // 0.0588 * L - 0.296 * S - 15.8
            const L = (charCount / wordCount) * 100;
            const S = (sentenceCount / wordCount) * 100;
            const cl = 0.0588 * L - 0.296 * S - 15.8;

            // 5. SMOG (Simple Measure of Gobbledygook)
            // 1.0430 * sqrt(30 * complex / sentences) + 3.1291
            const smog = 1.043 * Math.sqrt(30 * (complexCount / sentenceCount)) + 3.1291;

            updateUI({
                grade: Math.max(0, fkGrade),
                ease: Math.min(100, Math.max(0, fEase)),
                fog: Math.max(0, fog),
                cl: Math.max(0, cl),
                smog: Math.max(0, smog),
                stats: { sentences: sentenceCount, words: wordCount, syllables: syllableCount, complex: complexCount }
            });
        }

        function updateUI(data) {
            // Stats
            statSentences.textContent = data.stats.sentences;
            statWords.textContent = data.stats.words;
            statSyllables.textContent = data.stats.syllables;
            statComplex.textContent = data.stats.complex;

            // Grade Level
            gradeVal.textContent = data.grade.toFixed(1);
            let gradeText = "Unknown";
            if(data.grade < 5) gradeText = "Very Easy (Elementary)";
            else if(data.grade < 7) gradeText = "Easy (6th Grade)";
            else if(data.grade < 9) gradeText = "Average (8th Grade)";
            else if(data.grade < 12) gradeText = "Fairly Difficult (High School)";
            else if(data.grade < 16) gradeText = "Difficult (College)";
            else gradeText = "Very Difficult (Grad School)";
            gradeLabel.textContent = gradeText;

            // Reading Ease
            easeVal.textContent = Math.round(data.ease);
            easeBar.style.width = `${data.ease}%`;
            
            // Color code the bar
            if(data.ease > 80) easeBar.style.backgroundColor = "#34d399"; // Emerald
            else if(data.ease > 60) easeBar.style.backgroundColor = "#60a5fa"; // Blue
            else if(data.ease > 40) easeBar.style.backgroundColor = "#f59e0b"; // Amber
            else easeBar.style.backgroundColor = "#ef4444"; // Red

            // Other Scores
            scoreFog.textContent = data.fog.toFixed(1);
            scoreCl.textContent = data.cl.toFixed(1);
            scoreSmog.textContent = data.smog.toFixed(1);
        }

        function resetStats() {
            gradeVal.textContent = "0.0";
            gradeLabel.textContent = "No Text";
            easeVal.textContent = "0";
            easeBar.style.width = "0%";
            statSentences.textContent = "0";
            statWords.textContent = "0";
            statSyllables.textContent = "0";
            statComplex.textContent = "0";
            scoreFog.textContent = "-";
            scoreCl.textContent = "-";
            scoreSmog.textContent = "-";
        }

        // --- LISTENERS ---
        input.addEventListener('input', analyze);
        
        sampleBtn.addEventListener('click', () => {
            input.value = sampleText;
            analyze();
        });

        // Init
        resetStats();

    </script>
</body>
</html>