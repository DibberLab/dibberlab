<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexbox Playground | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        :root {
            --item-font-size: 24px;
        }

        body { font-family: 'Inter', sans-serif; }
        
        /* The Flex Container */
        #flex-container {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 400px;
            background-image: radial-gradient(#374151 1px, transparent 1px);
            background-size: 20px 20px;
            overflow: auto; 
        }

        /* Flex Items */
        .flex-item {
            min-width: 80px;
            min-height: 80px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: var(--item-font-size);
            line-height: 1.2;
            color: white;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            position: relative;
            cursor: text;
            outline: none;
            word-break: break-word;
        }
        
        .flex-item:focus {
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
            z-index: 10;
        }
        
        /* Gradients for items */
        .item-1 { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .item-2 { background: linear-gradient(135deg, #10b981, #059669); }
        .item-3 { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .item-4 { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .item-5 { background: linear-gradient(135deg, #ec4899, #db2777); }
        .item-6 { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .item-n { background: linear-gradient(135deg, #6b7280, #4b5563); }

        /* Control Buttons */
        .prop-btn {
            transition: all 0.2s;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: lowercase;
        }
        .prop-btn.active {
            background-color: #f59e0b; /* Amber */
            color: #111827;
            border-color: #f59e0b;
        }

        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none; background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 16px; width: 16px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -6px; 
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }

        /* Code Block */
        .code-block {
            font-family: 'JetBrains Mono', monospace;
            background-image: linear-gradient(to bottom, #1f2937, #111827);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-7xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-4 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-amber-400">Flexbox Playground</h1>
                <p class="text-gray-400">Visual guide to CSS Flexbox layout properties.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6 max-h-[700px] overflow-y-auto pr-2 custom-scrollbar">
                    
                    <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-sm font-bold text-white">Item Content</span>
                        </div>
                        
                        <div class="mb-2">
                            <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2">
                                <span>Text Size</span>
                                <span id="font-val" class="text-white">24px</span>
                            </div>
                            <input type="range" id="font-slider" min="12" max="64" value="24" class="w-full">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">💡 Tip: Click inside any box to type custom text.</p>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">flex-direction</div>
                        <div class="grid grid-cols-2 gap-2" id="ctrl-direction">
                            <button class="prop-btn active px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="row">row</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="column">column</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="row-reverse">row-rev</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="column-reverse">col-rev</button>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">flex-wrap</div>
                        <div class="grid grid-cols-2 gap-2" id="ctrl-wrap">
                            <button class="prop-btn active px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="nowrap">nowrap</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="wrap">wrap</button>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">justify-content</div>
                        <div class="grid grid-cols-2 gap-2" id="ctrl-justify">
                            <button class="prop-btn active px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="flex-start">flex-start</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="center">center</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="flex-end">flex-end</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="space-between">space-between</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="space-around">space-around</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="space-evenly">space-evenly</button>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">align-items</div>
                        <div class="grid grid-cols-2 gap-2" id="ctrl-align">
                            <button class="prop-btn active px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="stretch">stretch</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="flex-start">flex-start</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="center">center</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="flex-end">flex-end</button>
                            <button class="prop-btn px-3 py-2 rounded bg-gray-700 border border-gray-600 hover:bg-gray-600" data-val="baseline">baseline</button>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2">
                            <span>Gap</span>
                            <span id="gap-val" class="text-white">10px</span>
                        </div>
                        <input type="range" id="gap-slider" min="0" max="50" value="10" class="w-full">
                    </div>

                    <div class="pt-4 border-t border-gray-700">
                        <div class="flex gap-2">
                            <button id="add-btn" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2 rounded-lg transition-colors text-sm">
                                + Add Box
                            </button>
                            <button id="remove-btn" class="flex-1 bg-red-600 hover:bg-red-500 text-white font-bold py-2 rounded-lg transition-colors text-sm">
                                - Remove
                            </button>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    <div id="flex-container" class="w-full bg-gray-900 rounded-xl border border-gray-600 p-4 flex gap-[10px]" style="flex-direction: row; justify-content: flex-start; align-items: stretch; flex-wrap: nowrap;">
                        <div class="flex-item item-1" contenteditable="true" spellcheck="false">1</div>
                        <div class="flex-item item-2" contenteditable="true" spellcheck="false">2</div>
                        <div class="flex-item item-3" contenteditable="true" spellcheck="false">3</div>
                        <div class="flex-item item-4" contenteditable="true" spellcheck="false">4</div>
                    </div>

                    <div class="relative">
                        <div class="absolute top-0 left-0 bg-gray-700 text-xs text-gray-300 px-3 py-1 rounded-br-lg rounded-tl-lg font-bold">CSS Code</div>
                        <textarea id="code-output" readonly class="code-block w-full h-36 p-4 pt-8 rounded-xl text-emerald-400 text-sm focus:outline-none resize-none">
.container {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  justify-content: flex-start;
  align-items: stretch;
  gap: 10px;
}</textarea>
                        <button onclick="copyToClipboard('code-output', this)" class="absolute top-4 right-4 bg-gray-700 hover:bg-gray-600 text-white p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>

                    <div class="relative">
                        <div class="absolute top-0 left-0 bg-gray-700 text-xs text-gray-300 px-3 py-1 rounded-br-lg rounded-tl-lg font-bold">HTML Code</div>
                        <textarea id="html-output" readonly class="code-block w-full h-36 p-4 pt-8 rounded-xl text-blue-300 text-sm focus:outline-none resize-none"></textarea>
                        <button onclick="copyToClipboard('html-output', this)" class="absolute top-4 right-4 bg-gray-700 hover:bg-gray-600 text-white p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const container = document.getElementById('flex-container');
        const codeOutput = document.getElementById('code-output');
        const htmlOutput = document.getElementById('html-output');
        const gapSlider = document.getElementById('gap-slider');
        const gapVal = document.getElementById('gap-val');
        const fontSlider = document.getElementById('font-slider');
        const fontVal = document.getElementById('font-val');
        const addBtn = document.getElementById('add-btn');
        const removeBtn = document.getElementById('remove-btn');

        // State defaults
        let state = {
            direction: 'row',
            wrap: 'nowrap',
            justify: 'flex-start',
            align: 'stretch',
            gap: 10
        };

        // --- CORE FUNCTIONS ---

        function updateStyles() {
            // Apply CSS
            container.style.flexDirection = state.direction;
            container.style.flexWrap = state.wrap;
            container.style.justifyContent = state.justify;
            container.style.alignItems = state.align;
            container.style.gap = `${state.gap}px`;

            // Update Labels
            gapVal.textContent = `${state.gap}px`;

            // Update CSS Code
            codeOutput.value = `.container {
  display: flex;
  flex-direction: ${state.direction};
  flex-wrap: ${state.wrap};
  justify-content: ${state.justify};
  align-items: ${state.align};
  gap: ${state.gap}px;
}`;
        }

        function updateHTML() {
            let html = `<div class="container">\n`;
            
            const items = container.querySelectorAll('.flex-item');
            items.forEach((item, index) => {
                const text = item.textContent.trim();
                // Simple class logic for demonstration
                const className = `item-${Math.min(index + 1, 6)}`; 
                html += `  <div class="flex-item">${text}</div>\n`;
            });
            
            html += `</div>`;
            htmlOutput.value = html;
        }

        // --- EVENT LISTENERS ---

        // Button Groups
        function setupButtonGroup(id, key) {
            const group = document.getElementById(id);
            const btns = group.querySelectorAll('button');
            
            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    state[key] = btn.dataset.val;
                    updateStyles();
                });
            });
        }

        setupButtonGroup('ctrl-direction', 'direction');
        setupButtonGroup('ctrl-wrap', 'wrap');
        setupButtonGroup('ctrl-justify', 'justify');
        setupButtonGroup('ctrl-align', 'align');

        // Gap Slider
        gapSlider.addEventListener('input', (e) => {
            state.gap = e.target.value;
            updateStyles();
        });

        // Font Size Slider
        fontSlider.addEventListener('input', (e) => {
            const size = e.target.value + 'px';
            document.documentElement.style.setProperty('--item-font-size', size);
            fontVal.textContent = size;
        });

        // Add/Remove Items
        addBtn.addEventListener('click', () => {
            const count = container.children.length + 1;
            const div = document.createElement('div');
            const colorClass = count <= 6 ? `item-${count}` : 'item-n';
            
            div.className = `flex-item ${colorClass} scale-0`; 
            div.contentEditable = "true";
            div.spellcheck = false;
            div.textContent = count;
            
            // Listen for text changes to update HTML box
            div.addEventListener('input', updateHTML);
            
            container.appendChild(div);
            updateHTML();
            
            requestAnimationFrame(() => {
                div.classList.remove('scale-0');
            });
        });

        removeBtn.addEventListener('click', () => {
            if(container.children.length > 1) {
                container.lastElementChild.remove();
                updateHTML();
            }
        });

        // Listen for content changes in existing items
        container.addEventListener('input', updateHTML);

        // Copy Function
        window.copyToClipboard = function(elementId, btn) {
            const el = document.getElementById(elementId);
            el.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(el.value);
            
            const originalHTML = btn.innerHTML;
            btn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">COPIED</span>`;
            setTimeout(() => btn.innerHTML = originalHTML, 1500);
        };

        // Init
        updateStyles();
        updateHTML();

    </script>
</body>
</html>