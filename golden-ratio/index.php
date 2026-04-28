<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Ratio Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Visualizer Box */
        #visual-container {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .ratio-box {
            transition: width 0.3s ease;
        }

        /* Spiral SVG Overlay */
        .spiral-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            pointer-events: none;
            opacity: 0.3;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Golden Ratio Calculator</h1>
                <p class="text-center text-gray-400">Calculate divine proportions for layout and typography.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                
                <div class="space-y-8">
                    
                    <div class="flex items-center justify-center gap-1 mb-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                        <div class="w-16 h-8 border-2 border-emerald-500 bg-emerald-500/20 flex items-center justify-center text-emerald-400">A</div>
                        <span>+</span>
                        <div class="w-10 h-8 border-2 border-blue-500 bg-blue-500/20 flex items-center justify-center text-blue-400">B</div>
                        <span>=</span>
                        <div class="w-24 h-8 border-2 border-gray-500 flex items-center justify-center text-gray-400">Total</div>
                    </div>

                    <div class="space-y-6">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Total Length (A + B)</label>
                            <div class="relative">
                                <input type="number" id="input-total" class="mono-font w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-white text-lg focus:outline-none focus:border-amber-500 transition-colors" placeholder="0">
                                <div class="absolute inset-y-0 right-4 flex items-center text-gray-500 font-bold">T</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-emerald-400 uppercase mb-2">Larger Side (A)</label>
                                <div class="relative">
                                    <input type="number" id="input-a" class="mono-font w-full bg-gray-900 border border-emerald-500/50 rounded-xl p-4 text-emerald-400 text-lg focus:outline-none focus:border-emerald-500 transition-colors font-bold" placeholder="0">
                                    <div class="absolute inset-y-0 right-4 flex items-center text-emerald-500/50 font-bold">61.8%</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-blue-400 uppercase mb-2">Smaller Side (B)</label>
                                <div class="relative">
                                    <input type="number" id="input-b" class="mono-font w-full bg-gray-900 border border-blue-500/50 rounded-xl p-4 text-blue-400 text-lg focus:outline-none focus:border-blue-500 transition-colors font-bold" placeholder="0">
                                    <div class="absolute inset-y-0 right-4 flex items-center text-blue-500/50 font-bold">38.2%</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-900 rounded-xl border border-gray-700 p-6 mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-white uppercase">Typography Scale</h3>
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-gray-500">Base Size:</label>
                                <input type="number" id="base-font" value="16" class="w-16 bg-gray-800 border border-gray-600 rounded px-2 py-1 text-xs text-white text-center">
                                <span class="text-xs text-gray-500">px</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-end gap-4">
                                <span class="text-xs text-gray-500 w-8 mb-1">H1</span>
                                <div class="text-3xl font-bold text-white truncate" id="typo-h1" style="font-size: 67.77px;">Heading 1</div>
                                <span class="text-xs text-gray-600 font-mono ml-auto mb-1" id="val-h1">67.8px</span>
                            </div>
                            <div class="flex items-end gap-4">
                                <span class="text-xs text-gray-500 w-8 mb-1">H2</span>
                                <div class="text-2xl font-bold text-white truncate" id="typo-h2" style="font-size: 41.89px;">Heading 2</div>
                                <span class="text-xs text-gray-600 font-mono ml-auto mb-1" id="val-h2">41.9px</span>
                            </div>
                            <div class="flex items-end gap-4">
                                <span class="text-xs text-gray-500 w-8 mb-1">H3</span>
                                <div class="text-xl font-bold text-white truncate" id="typo-h3" style="font-size: 25.89px;">Heading 3</div>
                                <span class="text-xs text-gray-600 font-mono ml-auto mb-1" id="val-h3">25.9px</span>
                            </div>
                            <div class="flex items-end gap-4">
                                <span class="text-xs text-gray-500 w-8 mb-1">Body</span>
                                <div class="text-base text-gray-300 truncate" id="typo-body" style="font-size: 16px;">Body Text</div>
                                <span class="text-xs text-gray-600 font-mono ml-auto mb-1" id="val-body">16.0px</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1 h-full flex flex-col justify-center">
                    
                    <div class="w-full aspect-[1.618/1] bg-gray-900 border-2 border-gray-600 rounded-lg relative overflow-hidden flex shadow-2xl" id="visual-container">
                        
                        <div class="h-full bg-emerald-600/20 border-r-2 border-gray-600 flex items-center justify-center relative" style="width: 61.8%">
                            <span class="text-2xl font-bold text-emerald-500">A</span>
                            <div class="absolute bottom-2 left-2 text-xs text-emerald-700 font-mono">1.618</div>
                        </div>

                        <div class="h-full bg-blue-600/20 flex flex-col items-center justify-center relative flex-grow">
                            <span class="text-xl font-bold text-blue-500">B</span>
                            <div class="absolute bottom-2 right-2 text-xs text-blue-700 font-mono">1.0</div>
                            
                            <svg viewBox="0 0 100 100" class="absolute top-0 right-0 w-full h-full spiral-overlay" preserveAspectRatio="none">
                                <path d="M100,100 C100,0 0,0 0,0" fill="none" stroke="#f59e0b" stroke-width="2" vector-effect="non-scaling-stroke" />
                            </svg>
                        </div>

                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-6xl font-bold text-gray-800 select-none">φ</p>
                        <p class="text-xs text-gray-500 font-mono mt-2">1.61803398875</p>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // Constants
        const PHI = 1.61803398875;

        // Elements
        const inputTotal = document.getElementById('input-total');
        const inputA = document.getElementById('input-a');
        const inputB = document.getElementById('input-b');
        
        const baseFontInput = document.getElementById('base-font');
        const typoEls = {
            h1: document.getElementById('typo-h1'), valH1: document.getElementById('val-h1'),
            h2: document.getElementById('typo-h2'), valH2: document.getElementById('val-h2'),
            h3: document.getElementById('typo-h3'), valH3: document.getElementById('val-h3'),
            body: document.getElementById('typo-body'), valBody: document.getElementById('val-body'),
        };

        // --- CALCULATION LOGIC ---

        function calculateFromTotal(val) {
            if (!val) return clearAll();
            const a = val / PHI;
            const b = val - a;
            updateInputs(val, a, b, 'total');
        }

        function calculateFromA(val) {
            if (!val) return clearAll();
            const b = val / PHI;
            const total = parseFloat(val) + b;
            updateInputs(total, val, b, 'a');
        }

        function calculateFromB(val) {
            if (!val) return clearAll();
            const a = val * PHI;
            const total = a + parseFloat(val);
            updateInputs(total, a, val, 'b');
        }

        function updateInputs(total, a, b, source) {
            // Update fields that didn't trigger the event
            if (source !== 'total') inputTotal.value = parseFloat(total.toFixed(2));
            if (source !== 'a') inputA.value = parseFloat(a.toFixed(2));
            if (source !== 'b') inputB.value = parseFloat(b.toFixed(2));
        }

        function clearAll() {
            inputTotal.value = '';
            inputA.value = '';
            inputB.value = '';
        }

        // --- TYPOGRAPHY SCALE ---

        function updateTypography() {
            const base = parseFloat(baseFontInput.value) || 16;
            
            // Scale logic: Golden Ratio Typography usually skips steps or uses powers
            // Body = base
            // H3 = base * phi
            // H2 = base * phi^2
            // H1 = base * phi^3
            
            // But that gets huge fast. Let's use a slightly milder scale or steps.
            // Standard Golden Ratio scale: 
            const s3 = base * PHI; 
            const s2 = s3 * PHI;
            const s1 = s2 * PHI;

            // Apply Styles
            typoEls.body.style.fontSize = `${base}px`;
            typoEls.valBody.textContent = `${base}px`;

            typoEls.h3.style.fontSize = `${s3.toFixed(1)}px`;
            typoEls.valH3.textContent = `${s3.toFixed(1)}px`;

            typoEls.h2.style.fontSize = `${s2.toFixed(1)}px`;
            typoEls.valH2.textContent = `${s2.toFixed(1)}px`;

            // Cap H1 display size so it doesn't break layout, but show real value text
            const h1DisplaySize = Math.min(s1, 80); 
            typoEls.h1.style.fontSize = `${h1DisplaySize}px`;
            typoEls.valH1.textContent = `${s1.toFixed(1)}px`;
        }

        // --- LISTENERS ---

        inputTotal.addEventListener('input', (e) => calculateFromTotal(e.target.value));
        inputA.addEventListener('input', (e) => calculateFromA(e.target.value));
        inputB.addEventListener('input', (e) => calculateFromB(e.target.value));

        baseFontInput.addEventListener('input', updateTypography);

        // Init
        inputTotal.value = 1000;
        calculateFromTotal(1000);
        updateTypography();

    </script>
</body>
</html>