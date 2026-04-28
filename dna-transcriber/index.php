<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNA Transcriber | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* DNA Strand Animation */
        .helix-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
            height: 100%;
            overflow: hidden;
            mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
        }

        .base-pair {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 40px;
            margin: 0 auto;
            animation: spin 2s infinite ease-in-out;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .connection {
            flex-grow: 1;
            height: 2px;
            background: rgba(255,255,255,0.1);
        }

        @keyframes spin {
            0% { transform: scaleX(1); opacity: 1; }
            50% { transform: scaleX(0.2); opacity: 0.5; }
            100% { transform: scaleX(1); opacity: 1; }
        }

        /* Base Colors for Text Highlighting */
        .base-A { color: #10b981; } /* Emerald */
        .base-T { color: #ef4444; } /* Red */
        .base-U { color: #f59e0b; } /* Amber */
        .base-C { color: #3b82f6; } /* Blue */
        .base-G { color: #8b5cf6; } /* Purple */
        .base-invalid { color: #6b7280; text-decoration: line-through; }

        /* Output Card */
        .result-card {
            transition: border-color 0.2s;
        }
        .result-card:focus-within {
            border-color: #f59e0b;
        }

        /* Copy Button */
        .copy-btn {
            opacity: 0.5;
            transition: all 0.2s;
        }
        .copy-btn:hover { opacity: 1; color: #10b981; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 flex flex-col gap-6">
                
                <div>
                    <h1 class="text-3xl font-bold text-amber-400 mb-2">DNA Transcriber</h1>
                    <p class="text-gray-400 text-sm">Convert sequences to mRNA and Protein.</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-1 flex">
                    <textarea id="dna-input" class="w-full h-64 bg-gray-900 border border-gray-800 rounded-xl p-4 text-white mono-font uppercase text-lg focus:outline-none focus:border-amber-500 resize-none transition-colors" placeholder="ENTER DNA SEQUENCE
Ex: TAC GCG ATA..."></textarea>
                    
                    <div class="w-12 bg-gray-900 ml-1 rounded-xl py-4 hidden md:block">
                        <div class="helix-container" id="helix-anim">
                            </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-xs font-bold text-gray-500">
                        Length: <span id="seq-len" class="text-white">0</span> bp
                    </div>
                    <div class="flex gap-2">
                        <button onclick="loadSample()" class="px-3 py-1 bg-gray-800 hover:bg-gray-700 text-xs font-bold text-emerald-400 rounded-lg border border-gray-700">Sample</button>
                        <button onclick="clearAll()" class="px-3 py-1 bg-gray-800 hover:bg-red-900/30 text-xs font-bold text-red-400 rounded-lg border border-gray-700">Clear</button>
                    </div>
                </div>

                <div class="flex gap-3 text-[10px] font-bold uppercase font-mono bg-gray-800 p-3 rounded-lg border border-gray-700">
                    <span class="base-A">A: Adenine</span>
                    <span class="base-T">T: Thymine</span>
                    <span class="base-C">C: Cytosine</span>
                    <span class="base-G">G: Guanine</span>
                    <span class="base-U">U: Uracil</span>
                </div>

            </div>

            <div class="lg:col-span-7 flex flex-col gap-4">

                <div class="result-card bg-gray-800 border border-gray-700 rounded-xl p-4 flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-gray-500 uppercase">mRNA (Transcription)</label>
                        <button class="copy-btn text-xs font-bold uppercase" onclick="copyText('out-mrna')">Copy</button>
                    </div>
                    

[Image of DNA transcription]

                    <div id="out-mrna" class="text-sm font-mono text-gray-300 break-all leading-relaxed h-16 overflow-y-auto custom-scrollbar"></div>
                </div>

                <div class="result-card bg-gray-800 border border-gray-700 rounded-xl p-4 flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-gray-500 uppercase">Complementary DNA</label>
                        <button class="copy-btn text-xs font-bold uppercase" onclick="copyText('out-cdna')">Copy</button>
                    </div>
                    <div id="out-cdna" class="text-sm font-mono text-gray-300 break-all leading-relaxed h-16 overflow-y-auto custom-scrollbar"></div>
                </div>

                <div class="result-card bg-gray-800 border border-gray-700 rounded-xl p-4 flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-gray-500 uppercase">Reverse Complement</label>
                        <button class="copy-btn text-xs font-bold uppercase" onclick="copyText('out-rev')">Copy</button>
                    </div>
                    <div id="out-rev" class="text-sm font-mono text-gray-300 break-all leading-relaxed h-16 overflow-y-auto custom-scrollbar"></div>
                </div>

                <div class="result-card bg-gray-800 border border-emerald-500/30 rounded-xl p-4 flex flex-col gap-2 flex-grow">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-emerald-500 uppercase">Protein (Translation)</label>
                        <button class="copy-btn text-xs font-bold uppercase" onclick="copyText('out-protein')">Copy</button>
                    </div>
                    <div id="out-protein" class="text-sm font-mono text-emerald-300 break-all leading-relaxed h-full overflow-y-auto custom-scrollbar"></div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const dnaInput = document.getElementById('dna-input');
        const seqLen = document.getElementById('seq-len');
        const helixContainer = document.getElementById('helix-anim');
        
        const outMrna = document.getElementById('out-mrna');
        const outCdna = document.getElementById('out-cdna');
        const outRev = document.getElementById('out-rev');
        const outProtein = document.getElementById('out-protein');

        // CODON CHART (Standard Genetic Code)
        const CODONS = {
            'ATA':'I', 'ATC':'I', 'ATT':'I', 'ATG':'M',
            'ACA':'T', 'ACC':'T', 'ACG':'T', 'ACT':'T',
            'AAC':'N', 'AAT':'N', 'AAA':'K', 'AAG':'K',
            'AGC':'S', 'AGT':'S', 'AGA':'R', 'AGG':'R',                 
            'CTA':'L', 'CTC':'L', 'CTG':'L', 'CTT':'L',
            'CCA':'P', 'CCC':'P', 'CCG':'P', 'CCT':'P',
            'CAC':'H', 'CAT':'H', 'CAA':'Q', 'CAG':'Q',
            'CGA':'R', 'CGC':'R', 'CGG':'R', 'CGT':'R',
            'GTA':'V', 'GTC':'V', 'GTG':'V', 'GTT':'V',
            'GCA':'A', 'GCC':'A', 'GCG':'A', 'GCT':'A',
            'GAC':'D', 'GAT':'D', 'GAA':'E', 'GAG':'E',
            'GGA':'G', 'GGC':'G', 'GGG':'G', 'GGT':'G',
            'TCA':'S', 'TCC':'S', 'TCG':'S', 'TCT':'S',
            'TTC':'F', 'TTT':'F', 'TTA':'L', 'TTG':'L',
            'TAC':'Y', 'TAT':'Y', 'TAA':'_', 'TAG':'_',
            'TGC':'C', 'TGT':'C', 'TGA':'_', 'TGG':'W',
        };

        // --- CORE LOGIC ---

        function processSequence() {
            // 1. Clean Input (Remove spaces, numbers, newlines)
            const raw = dnaInput.value.toUpperCase();
            const clean = raw.replace(/[^A-Z]/g, '');
            
            // Update length
            seqLen.textContent = clean.length;

            if (!clean) {
                clearOutputs();
                return;
            }

            // 2. Generate mRNA (Replace T with U)
            // Visual output should be color coded, so we build HTML strings
            const mrnaStr = clean.replace(/T/g, 'U');
            outMrna.innerHTML = colorize(mrnaStr);

            // 3. Complementary DNA (A<->T, C<->G)
            let compStr = "";
            for (let char of clean) {
                if (char === 'A') compStr += 'T';
                else if (char === 'T') compStr += 'A';
                else if (char === 'C') compStr += 'G';
                else if (char === 'G') compStr += 'C';
                else compStr += char; // Keep unknowns
            }
            outCdna.innerHTML = colorize(compStr);

            // 4. Reverse Complement
            const revCompStr = compStr.split('').reverse().join('');
            outRev.innerHTML = colorize(revCompStr);

            // 5. Protein Translation
            // We translate the DNA Coding Strand directly (using the codon chart above which is DNA-based)
            // Or we translate mRNA. Standard chart above is DNA.
            let proteinStr = "";
            for (let i = 0; i < clean.length; i += 3) {
                const codon = clean.substr(i, 3);
                if (codon.length === 3) {
                    const amino = CODONS[codon] || '?';
                    proteinStr += amino;
                }
            }
            outProtein.textContent = proteinStr;
        }

        // Colorize bases for display
        function colorize(seq) {
            let html = "";
            for (let char of seq) {
                if(char === 'A') html += `<span class="base-A">A</span>`;
                else if(char === 'T') html += `<span class="base-T">T</span>`;
                else if(char === 'U') html += `<span class="base-U">U</span>`;
                else if(char === 'C') html += `<span class="base-C">C</span>`;
                else if(char === 'G') html += `<span class="base-G">G</span>`;
                else html += `<span class="base-invalid">${char}</span>`;
            }
            return html;
        }

        // --- VISUALS ---

        function initHelix() {
            helixContainer.innerHTML = '';
            for(let i=0; i<20; i++) {
                const pair = document.createElement('div');
                pair.className = 'base-pair';
                
                // Stagger animation delays
                pair.style.animationDelay = `${i * 0.1}s`;

                // Random colors for dots
                const colors = ['#10b981', '#ef4444', '#3b82f6', '#f59e0b'];
                const c1 = colors[Math.floor(Math.random()*4)];
                const c2 = colors[Math.floor(Math.random()*4)];

                pair.innerHTML = `
                    <div class="dot" style="background:${c1}"></div>
                    <div class="connection"></div>
                    <div class="dot" style="background:${c2}"></div>
                `;
                helixContainer.appendChild(pair);
            }
        }

        // --- UTILS ---

        function copyText(id) {
            const el = document.getElementById(id);
            navigator.clipboard.writeText(el.innerText);
        }

        function loadSample() {
            dnaInput.value = "ATG CGC TAC TGG AGC GGG ATT TAA";
            processSequence();
        }

        function clearOutputs() {
            outMrna.innerHTML = '';
            outCdna.innerHTML = '';
            outRev.innerHTML = '';
            outProtein.innerHTML = '';
            seqLen.textContent = '0';
        }

        function clearAll() {
            dnaInput.value = '';
            clearOutputs();
        }

        // Listeners
        dnaInput.addEventListener('input', processSequence);

        // Init
        initHelix();

    </script>
</body>
</html>