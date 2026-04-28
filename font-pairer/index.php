<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Font Pairer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The Dynamic Font Container */
        #preview-container {
            transition: opacity 0.2s ease;
        }
        
        /* Editable areas */
        [contenteditable]:focus {
            outline: 2px dashed #f59e0b;
            outline-offset: 4px;
            border-radius: 4px;
        }

        /* Code Blocks */
        .code-block {
            font-family: 'JetBrains Mono', monospace;
            background-color: #111827;
            background-image: linear-gradient(to bottom, #1f2937, #111827);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
            
            <div class="w-full lg:w-96 bg-gray-900 border-r border-gray-700 p-6 flex flex-col z-10 shadow-lg">
                
                <h1 class="text-2xl font-bold text-amber-400 mb-6">Font Pairer</h1>
                <p class="text-sm text-gray-400 mb-8">Curated Google Font combinations for your next project.</p>

                <div class="space-y-6 mb-8 flex-grow">
                    
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-1">Heading Font</span>
                        <div class="text-xl font-bold text-white flex items-center gap-2">
                            <span id="label-h">Montserrat</span>
                            <a id="link-h" href="#" target="_blank" class="text-gray-500 hover:text-white text-xs opacity-0 group-hover:opacity-100">↗</a>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-1">Body Font</span>
                        <div class="text-xl font-bold text-white flex items-center gap-2">
                            <span id="label-b">Open Sans</span>
                            <a id="link-b" href="#" target="_blank" class="text-gray-500 hover:text-white text-xs opacity-0 group-hover:opacity-100">↗</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button id="swap-btn" class="col-span-2 py-2 rounded-lg border border-gray-600 hover:bg-gray-800 text-gray-300 text-sm font-bold transition-colors">
                            ⇅ Swap Fonts
                        </button>
                    </div>

                </div>

                <div class="space-y-3">
                    <button id="copy-link-btn" class="w-full py-2 rounded-lg bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-200 text-sm font-bold transition-colors flex items-center justify-center gap-2">
                        <span>🔗</span> Copy HTML Link
                    </button>
                    <button id="copy-css-btn" class="w-full py-2 rounded-lg bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-200 text-sm font-bold transition-colors flex items-center justify-center gap-2">
                        <span>🎨</span> Copy CSS Rules
                    </button>
                    
                    <hr class="border-gray-700 my-4">

                    <button id="generate-btn" class="w-full py-4 rounded-xl text-lg font-bold bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/50 transition-all transform hover:-translate-y-1">
                        Generate New Pair
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-2">Press <strong>Spacebar</strong> to cycle</p>
                </div>

            </div>

            <div class="flex-grow bg-white text-gray-900 p-8 md:p-16 flex flex-col justify-center relative group" id="preview-area">
                
                <div id="preview-container" class="max-w-2xl mx-auto space-y-6">
                    <h1 id="preview-heading" class="text-5xl md:text-6xl font-black leading-tight outline-none" contenteditable="true" spellcheck="false">
                        Design is intelligence made visible.
                    </h1>
                    
                    <p id="preview-body" class="text-lg md:text-xl leading-relaxed text-gray-600 outline-none" contenteditable="true" spellcheck="false">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Good design is obvious. Great design is transparent. Click here to edit this text and see how your content looks with this typography combination.
                    </p>

                    <button class="mt-8 px-6 py-3 bg-black text-white font-bold rounded-full hover:opacity-80 transition-opacity" style="font-family: inherit;">
                        Button Style
                    </button>
                </div>

                <button id="theme-toggle" class="absolute top-6 right-6 p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors" title="Toggle Dark/Light Preview">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA: CURATED PAIRS ---
        // Format: [Heading Font, Body Font]
        const pairs = [
            ['Montserrat', 'Open Sans'],
            ['Playfair Display', 'Lato'],
            ['Merriweather', 'Open Sans'],
            ['Oswald', 'Quattrocento'],
            ['Roboto', 'Roboto Slab'],
            ['Raleway', 'Merriweather'],
            ['Abril Fatface', 'Poppins'],
            ['Josefin Sans', 'Lato'],
            ['Ubuntu', 'Lora'],
            ['Anton', 'Roboto'],
            ['Lobster', 'Cabin'],
            ['Pacifico', 'Quicksand'],
            ['Nunito', 'Nunito Sans'],
            ['Fjalla One', 'Cantarell'],
            ['Bebas Neue', 'Montserrat'],
            ['Space Mono', 'Muli'],
            ['Vollkorn', 'Exo'],
            ['Crimson Text', 'Work Sans'],
            ['Karla', 'Inconsolata'],
            ['Syne', 'Inter']
        ];

        // --- DOM ELEMENTS ---
        const labelH = document.getElementById('label-h');
        const labelB = document.getElementById('label-b');
        const prevH = document.getElementById('preview-heading');
        const prevB = document.getElementById('preview-body');
        const previewArea = document.getElementById('preview-area');
        const prevContainer = document.getElementById('preview-container');
        
        const generateBtn = document.getElementById('generate-btn');
        const swapBtn = document.getElementById('swap-btn');
        const themeBtn = document.getElementById('theme-toggle');
        const copyLinkBtn = document.getElementById('copy-link-btn');
        const copyCssBtn = document.getElementById('copy-css-btn');

        // State
        let currentPair = [null, null]; // [Header, Body]
        let isDark = false;

        // --- LOGIC ---

        function generatePair() {
            // Pick random pair
            let newPair;
            do {
                newPair = pairs[Math.floor(Math.random() * pairs.length)];
            } while (newPair[0] === currentPair[0] && newPair[1] === currentPair[1]);

            currentPair = newPair;
            applyFonts(currentPair[0], currentPair[1]);
        }

        function applyFonts(hFont, bFont) {
            // 1. Fade out slightly
            prevContainer.style.opacity = '0.5';

            // 2. Load Fonts via Google API
            const link = document.createElement('link');
            link.href = `https://fonts.googleapis.com/css2?family=${hFont.replace(/ /g, '+')}&family=${bFont.replace(/ /g, '+')}&display=swap`;
            link.rel = 'stylesheet';
            document.head.appendChild(link);

            // 3. Apply Styles once loaded (img onload hack isn't needed for CSS, usually instant enough)
            setTimeout(() => {
                prevH.style.fontFamily = `'${hFont}', serif`; // Fallback doesn't matter much as we load both
                prevB.style.fontFamily = `'${bFont}', sans-serif`;
                
                // If Body font is Roboto/Inter/Sans, Header usually looks good. 
                // We trust the curation.

                // Update Labels
                labelH.textContent = hFont;
                labelB.textContent = bFont;

                // Fade back in
                prevContainer.style.opacity = '1';
            }, 100);
        }

        function swapFonts() {
            // Swap array
            currentPair = [currentPair[1], currentPair[0]];
            applyFonts(currentPair[0], currentPair[1]);
        }

        // --- EXPORT ---

        function copyLink() {
            const h = currentPair[0].replace(/ /g, '+');
            const b = currentPair[1].replace(/ /g, '+');
            const html = `<link href="https://fonts.googleapis.com/css2?family=${h}&family=${b}&display=swap" rel="stylesheet">`;
            
            copyText(html, copyLinkBtn);
        }

        function copyCSS() {
            const css = `h1 { font-family: '${currentPair[0]}', sans-serif; }\nbody { font-family: '${currentPair[1]}', sans-serif; }`;
            copyText(css, copyCssBtn);
        }

        function copyText(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = `<span class="text-emerald-400">✓ Copied!</span>`;
                setTimeout(() => btn.innerHTML = orig, 1500);
            });
        }

        // --- LISTENERS ---

        generateBtn.addEventListener('click', generatePair);
        swapBtn.addEventListener('click', swapFonts);
        copyLinkBtn.addEventListener('click', copyLink);
        copyCssBtn.addEventListener('click', copyCSS);

        // Theme Toggle
        themeBtn.addEventListener('click', () => {
            isDark = !isDark;
            if (isDark) {
                previewArea.classList.replace('bg-white', 'bg-black');
                previewArea.classList.replace('text-gray-900', 'text-gray-100');
                prevB.classList.replace('text-gray-600', 'text-gray-400');
            } else {
                previewArea.classList.replace('bg-black', 'bg-white');
                previewArea.classList.replace('text-gray-100', 'text-gray-900');
                prevB.classList.replace('text-gray-400', 'text-gray-600');
            }
        });

        // Spacebar
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && e.target.tagName !== 'BUTTON' && !e.target.isContentEditable) {
                e.preventDefault();
                generatePair();
            }
        });

        // Init
        generatePair();

    </script>
</body>
</html>