<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palette Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* The Palette Columns */
        .color-col {
            transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        /* Hover Actions */
        .color-col:hover .col-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .col-actions {
            transition: all 0.2s ease;
        }

        /* Lock Button States */
        .lock-btn {
            transition: all 0.2s;
        }
        .lock-btn.locked {
            color: inherit;
            opacity: 1 !important;
        }
        .lock-btn:not(.locked) {
            opacity: 0.5;
        }
        .lock-btn:not(.locked):hover {
            opacity: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        /* Select Dropdown */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow flex flex-col px-4 pb-4 h-[calc(100vh-100px)]">
        
        <div class="w-full max-w-7xl mx-auto mb-4 flex flex-wrap items-center justify-between gap-4 bg-gray-800 p-3 rounded-xl border border-gray-700 shadow-lg z-20">
            
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-amber-400 hidden md:block pl-2">Palette Gen</h1>
                
                <div class="relative">
                    <select id="mode-select" class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-40 p-2.5 font-bold">
                        <option value="random">Random</option>
                        <option value="analogous">Analogous</option>
                        <option value="monochromatic">Monochrome</option>
                        <option value="triadic">Triadic</option>
                        <option value="complementary">Complementary</option>
                        <option value="pastel">Pastel</option>
                        <option value="dark">Dark Mode</option>
                    </select>
                </div>
                
                <span class="text-xs text-gray-500 hidden md:inline">Press <strong>Spacebar</strong> to generate</span>
            </div>

            <div class="flex gap-2">
                <button id="generate-btn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors shadow-md flex items-center gap-2">
                    <span>🔄</span> Generate
                </button>
                <button id="copy-css-btn" class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-bold text-sm transition-colors border border-gray-600">
                    Copy CSS
                </button>
            </div>
        </div>

        <div class="w-full max-w-7xl mx-auto flex-grow flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl border border-gray-700 relative" id="palette-container">
            </div>

    </main>

    <div id="toast" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-full shadow-2xl font-bold border border-gray-600 translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-2">
        <span class="text-emerald-400">✓</span> <span id="toast-msg">Copied!</span>
    </div>

    <textarea id="copy-area" class="hidden"></textarea>

    <script>
        const container = document.getElementById('palette-container');
        const generateBtn = document.getElementById('generate-btn');
        const modeSelect = document.getElementById('mode-select');
        const copyCssBtn = document.getElementById('copy-css-btn');
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-msg');

        // State
        let colors = [
            { hex: "#3B82F6", locked: false },
            { hex: "#10B981", locked: false },
            { hex: "#F59E0B", locked: false },
            { hex: "#EF4444", locked: false },
            { hex: "#8B5CF6", locked: false }
        ];

        // --- UTILS ---

        function hslToHex(h, s, l) {
            l /= 100;
            const a = s * Math.min(l, 1 - l) / 100;
            const f = n => {
                const k = (n + h / 30) % 12;
                const color = l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
                return Math.round(255 * color).toString(16).padStart(2, '0');
            };
            return `#${f(0)}${f(8)}${f(4)}`.toUpperCase();
        }

        // Get contrast color (black or white)
        function getContrastYIQ(hexcolor){
            hexcolor = hexcolor.replace("#", "");
            var r = parseInt(hexcolor.substr(0,2),16);
            var g = parseInt(hexcolor.substr(2,2),16);
            var b = parseInt(hexcolor.substr(4,2),16);
            var yiq = ((r*299)+(g*587)+(b*114))/1000;
            return (yiq >= 128) ? '#111827' : '#FFFFFF'; // Gray-900 vs White
        }

        // --- GENERATION LOGIC ---

        function generateColors() {
            const mode = modeSelect.value;
            
            // Base Hue (Random)
            const baseHue = Math.floor(Math.random() * 360);
            
            // Generate 5 colors based on mode
            let newColors = [];

            for (let i = 0; i < 5; i++) {
                // If locked, keep existing
                if (colors[i].locked) {
                    newColors.push(colors[i]);
                    continue;
                }

                let h, s, l;

                if (mode === 'random') {
                    h = Math.floor(Math.random() * 360);
                    s = Math.floor(Math.random() * 40) + 60; // 60-100%
                    l = Math.floor(Math.random() * 40) + 30; // 30-70%
                } 
                else if (mode === 'monochromatic') {
                    h = baseHue;
                    s = Math.floor(Math.random() * 30) + 50;
                    // Spread lightness evenly: 20, 35, 50, 65, 80 approx
                    l = 15 + (i * 15) + Math.floor(Math.random() * 10);
                }
                else if (mode === 'analogous') {
                    // Spread hues by 30deg
                    h = (baseHue + (i * 30)) % 360;
                    s = 70;
                    l = 50 + Math.floor(Math.random() * 20 - 10);
                }
                else if (mode === 'complementary') {
                    // 0, 0, 0, 180, 180 (Mix of base and comp)
                    if (i < 3) h = baseHue;
                    else h = (baseHue + 180) % 360;
                    
                    s = 70 + Math.floor(Math.random() * 20);
                    l = 40 + (i * 10);
                }
                else if (mode === 'triadic') {
                    // 0, 120, 240
                    h = (baseHue + (i * 120)) % 360;
                    s = 70;
                    l = 50;
                }
                else if (mode === 'pastel') {
                    h = Math.floor(Math.random() * 360);
                    s = 100;
                    l = 85 + Math.floor(Math.random() * 10);
                }
                else if (mode === 'dark') {
                    h = Math.floor(Math.random() * 360);
                    s = 60;
                    l = 15 + Math.floor(Math.random() * 20);
                }

                newColors.push({
                    hex: hslToHex(h, s, l),
                    locked: false
                });
            }

            colors = newColors;
            render();
        }

        // --- RENDER UI ---

        function render() {
            container.innerHTML = '';

            colors.forEach((color, index) => {
                const textColor = getContrastYIQ(color.hex);
                
                const col = document.createElement('div');
                col.className = "color-col flex-1 h-32 md:h-full flex flex-col items-center justify-center gap-4 group";
                col.style.backgroundColor = color.hex;
                col.style.color = textColor;

                // Inner content
                col.innerHTML = `
                    <div class="col-actions flex flex-col items-center gap-2 opacity-100 md:opacity-0 md:translate-y-4 transition-all duration-300">
                        
                        <button onclick="toggleLock(${index})" class="lock-btn ${color.locked ? 'locked' : ''} p-2 rounded-full hover:bg-black/10 transition-colors" title="Lock Color">
                            ${color.locked 
                                ? `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>` 
                                : `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>`
                            }
                        </button>

                        <button onclick="copyColor('${color.hex}')" class="font-mono text-xl font-bold uppercase tracking-wider hover:scale-110 transition-transform cursor-pointer select-none">
                            ${color.hex}
                        </button>

                        <p class="text-[10px] font-bold opacity-60 uppercase">Click to Copy</p>
                    </div>
                `;

                container.appendChild(col);
            });
        }

        // --- ACTIONS ---

        window.toggleLock = function(index) {
            colors[index].locked = !colors[index].locked;
            render();
        }

        window.copyColor = function(hex) {
            navigator.clipboard.writeText(hex);
            showToast(`Copied ${hex}`);
        }

        function showToast(msg) {
            toastMsg.textContent = msg;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 2000);
        }

        copyCssBtn.addEventListener('click', () => {
            const css = `/* Dibber Lab Palette */
:root {
${colors.map((c, i) => `  --color-${i + 1}: ${c.hex};`).join('\n')}
}`;
            navigator.clipboard.writeText(css);
            showToast("CSS Exported!");
        });

        // --- LISTENERS ---

        generateBtn.addEventListener('click', generateColors);

        // Spacebar to generate
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && e.target.tagName !== 'BUTTON') {
                e.preventDefault(); // Stop scrolling
                generateColors();
            }
        });

        // Init
        generateColors(); // Start with random

    </script>
</body>
</html>