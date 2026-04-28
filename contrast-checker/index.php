<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrast Checker | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Color Input Styling */
        .color-wrapper {
            position: relative;
            height: 48px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #4b5563;
            display: flex;
            align-items: center;
            background: #1f2937;
        }
        
        input[type=color] {
            -webkit-appearance: none;
            border: none;
            width: 48px;
            height: 50px;
            padding: 0;
            cursor: pointer;
            margin-left: -2px; /* Pull left to hide border */
            margin-top: -2px;
        }
        input[type=color]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type=color]::-webkit-color-swatch { border: none; }

        input[type=text] {
            background: transparent;
            border: none;
            color: white;
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            width: 100%;
            padding: 0 12px;
            text-transform: uppercase;
        }
        input[type=text]:focus { outline: none; }

        /* Badge Styles */
        .badge {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-pass { background-color: #065f46; color: #34d399; border: 1px solid #059669; }
        .badge-fail { background-color: #7f1d1d; color: #fca5a5; border: 1px solid #dc2626; }

        /* Preview Area Transition */
        #preview-box { transition: background-color 0.2s, color 0.2s; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Contrast Checker</h1>
                <p class="text-center text-gray-400">Ensure your color combinations are accessible (WCAG).</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                
                <div class="space-y-8">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Foreground (Text)</label>
                        <div class="color-wrapper">
                            <input type="color" id="fg-picker" value="#FFFFFF">
                            <input type="text" id="fg-text" value="#FFFFFF" maxlength="7">
                        </div>
                    </div>

                    <div class="flex justify-center -my-2 relative z-10">
                        <button id="swap-btn" class="bg-gray-700 hover:bg-gray-600 border border-gray-600 rounded-full p-2 transition-transform hover:rotate-180 shadow-lg" title="Swap Colors">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        </button>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Background</label>
                        <div class="color-wrapper">
                            <input type="color" id="bg-picker" value="#3B82F6">
                            <input type="text" id="bg-text" value="#3B82F6" maxlength="7">
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-xl p-6 text-center border border-gray-700 mt-6">
                        <span class="text-xs font-bold text-gray-500 uppercase">Contrast Ratio</span>
                        <div class="text-5xl font-bold text-white mt-2 mb-1" id="ratio-display">4.21</div>
                        <div class="text-xs font-bold" id="rating-text">Good</div>
                    </div>

                </div>

                <div class="flex flex-col gap-6">
                    
                    <div id="preview-box" class="flex-grow rounded-xl p-8 flex flex-col justify-center items-center text-center shadow-inner min-h-[200px]" style="background-color: #3b82f6; color: #ffffff;">
                        <h2 class="text-3xl font-bold mb-2">Hello World</h2>
                        <p class="text-lg opacity-90 mb-4">This is how your text looks.</p>
                        <span class="text-sm opacity-75">Small print example text.</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        
                        <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-gray-300">Normal Text</span>
                                <span id="aa-normal" class="badge badge-fail">Fail</span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>AA (4.5:1)</span>
                                <span id="aaa-normal" class="font-mono">Fail</span>
                            </div>
                        </div>

                        <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-gray-300">Large Text</span>
                                <span id="aa-large" class="badge badge-pass">Pass</span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>AA (3.0:1)</span>
                                <span id="aaa-large" class="font-mono">Fail</span>
                            </div>
                        </div>

                    </div>

                    <div class="text-xs text-gray-500 text-center">
                        <p><strong>Large Text</strong> = 18pt (24px) or 14pt (18.6px) Bold</p>
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
        const fgPicker = document.getElementById('fg-picker');
        const fgText = document.getElementById('fg-text');
        const bgPicker = document.getElementById('bg-picker');
        const bgText = document.getElementById('bg-text');
        const swapBtn = document.getElementById('swap-btn');
        
        const previewBox = document.getElementById('preview-box');
        const ratioDisplay = document.getElementById('ratio-display');
        const ratingText = document.getElementById('rating-text');
        
        const badges = {
            aaNormal: document.getElementById('aa-normal'),
            aaaNormal: document.getElementById('aaa-normal'),
            aaLarge: document.getElementById('aa-large'),
            aaaLarge: document.getElementById('aaa-large')
        };

        // --- CORE LOGIC ---

        function hexToRgb(hex) {
            const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            hex = hex.replace(shorthandRegex, (m, r, g, b) => r + r + g + g + b + b);
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function getLuminance(r, g, b) {
            const a = [r, g, b].map(v => {
                v /= 255;
                return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
            });
            return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
        }

        function calculateRatio() {
            const fgHex = fgPicker.value;
            const bgHex = bgPicker.value;

            const fg = hexToRgb(fgHex);
            const bg = hexToRgb(bgHex);

            if (!fg || !bg) return;

            const l1 = getLuminance(fg.r, fg.g, fg.b);
            const l2 = getLuminance(bg.r, bg.g, bg.b);

            let ratio = l1 > l2 
                ? (l1 + 0.05) / (l2 + 0.05)
                : (l2 + 0.05) / (l1 + 0.05);

            ratio = Math.round(ratio * 100) / 100; // Round to 2 decimals

            updateUI(ratio, fgHex, bgHex);
        }

        function updateUI(ratio, fg, bg) {
            // Update Text
            fgText.value = fg.toUpperCase();
            bgText.value = bg.toUpperCase();
            ratioDisplay.textContent = ratio.toFixed(2);

            // Update Visuals
            previewBox.style.color = fg;
            previewBox.style.backgroundColor = bg;

            // Determine Pass/Fail
            const updateBadge = (el, pass) => {
                if (pass) {
                    el.textContent = "Pass";
                    el.className = el.id.includes('aaa') ? "font-mono text-emerald-400" : "badge badge-pass";
                } else {
                    el.textContent = "Fail";
                    el.className = el.id.includes('aaa') ? "font-mono text-red-400" : "badge badge-fail";
                }
            };

            updateBadge(badges.aaNormal, ratio >= 4.5);
            updateBadge(badges.aaaNormal, ratio >= 7.0);
            updateBadge(badges.aaLarge, ratio >= 3.0);
            updateBadge(badges.aaaLarge, ratio >= 4.5);

            // Rating Text Color
            if (ratio >= 7) {
                ratingText.textContent = "Superb (AAA)";
                ratingText.className = "text-xs font-bold text-emerald-400";
            } else if (ratio >= 4.5) {
                ratingText.textContent = "Good (AA)";
                ratingText.className = "text-xs font-bold text-emerald-300";
            } else if (ratio >= 3) {
                ratingText.textContent = "Okay (Large Text Only)";
                ratingText.className = "text-xs font-bold text-amber-400";
            } else {
                ratingText.textContent = "Poor Visibility";
                ratingText.className = "text-xs font-bold text-red-400";
            }
        }

        // --- LISTENERS ---

        // Pickers
        fgPicker.addEventListener('input', calculateRatio);
        bgPicker.addEventListener('input', calculateRatio);

        // Text Inputs (with simple validation)
        const handleTextInput = (e, picker) => {
            let val = e.target.value;
            if (!val.startsWith('#')) val = '#' + val;
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                picker.value = val;
                calculateRatio();
            }
        };

        fgText.addEventListener('input', (e) => handleTextInput(e, fgPicker));
        bgText.addEventListener('input', (e) => handleTextInput(e, bgPicker));

        // Swap
        swapBtn.addEventListener('click', () => {
            const temp = fgPicker.value;
            fgPicker.value = bgPicker.value;
            bgPicker.value = temp;
            calculateRatio();
        });

        // Initialize
        calculateRatio();

    </script>
</body>
</html>