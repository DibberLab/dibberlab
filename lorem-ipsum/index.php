<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorem Ipsum Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none; 
            background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #4b5563;
            border-radius: 2px;
        }

        /* Unit Buttons */
        .unit-btn {
            transition: all 0.2s;
        }
        .unit-btn.active {
            background-color: #374151;
            border-color: #f59e0b;
            color: #f59e0b;
        }

        /* Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10b981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Lorem Ipsum Generator</h1>
            <p class="text-center text-gray-400 mb-8">Generate placeholder text for your designs.</p>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                <div class="md:col-span-4 space-y-8">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Generate By</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button class="unit-btn active border-2 border-gray-600 rounded-lg py-2 text-sm font-bold text-gray-400 hover:text-white" data-unit="paragraphs">Paras</button>
                            <button class="unit-btn border-2 border-gray-600 rounded-lg py-2 text-sm font-bold text-gray-400 hover:text-white" data-unit="sentences">Sentences</button>
                            <button class="unit-btn border-2 border-gray-600 rounded-lg py-2 text-sm font-bold text-gray-400 hover:text-white" data-unit="words">Words</button>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2">
                            <span>Quantity</span>
                            <span id="count-display" class="text-white">3</span>
                        </div>
                        <input type="range" id="count-slider" min="1" max="50" value="3" class="w-full">
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Start with "Lorem ipsum..."</span>
                            <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                <input type="checkbox" id="toggle-start" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                <label for="toggle-start" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-300">Include HTML Tags</span>
                            <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                <input type="checkbox" id="toggle-html" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 transition-all duration-300 top-0 left-0"/>
                                <label for="toggle-html" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer transition-all duration-300"></label>
                            </div>
                        </div>
                    </div>

                    <button id="generate-btn" class="w-full py-3 rounded-xl text-lg font-bold bg-gray-700 hover:bg-gray-600 border border-gray-600 shadow-lg transition-all transform hover:-translate-y-1">
                        🔄 Regenerate
                    </button>

                </div>

                <div class="md:col-span-8 flex flex-col h-[500px]">
                    <div class="relative flex-grow">
                        <div class="absolute top-0 left-0 bg-gray-700 text-xs text-gray-300 px-3 py-1 rounded-br-lg rounded-tl-lg font-bold z-10">Result</div>
                        
                        <textarea id="output-area" readonly class="w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-6 pt-10 text-gray-300 focus:outline-none focus:border-amber-500 resize-none font-serif leading-relaxed text-lg shadow-inner custom-scrollbar"></textarea>
                        
                        <button id="copy-btn" class="absolute top-4 right-4 bg-amber-600 hover:bg-amber-500 text-white p-2 px-4 rounded-lg font-bold text-sm shadow-lg transition-colors flex items-center gap-2">
                            <span>📋</span> Copy Text
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
        // --- DATA ---
        const dictionary = [
            "lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "sed", "do", "eiusmod", 
            "tempor", "incididunt", "ut", "labore", "et", "dolore", "magna", "aliqua", "ut", "enim", "ad", "minim", 
            "veniam", "quis", "nostrud", "exercitation", "ullamco", "laboris", "nisi", "ut", "aliquip", "ex", "ea", 
            "commodo", "consequat", "duis", "aute", "irure", "dolor", "in", "reprehenderit", "in", "voluptate", 
            "velit", "esse", "cillum", "dolore", "eu", "fugiat", "nulla", "pariatur", "excepteur", "sint", "occaecat", 
            "cupidatat", "non", "proident", "sunt", "in", "culpa", "qui", "officia", "deserunt", "mollit", "anim", 
            "id", "est", "laborum", "accusamus", "dignissimos", "ducimus", "blanditiis", "praesentium", "voluptatum", 
            "deleniti", "atque", "corrupti", "quos", "dolores", "quas", "molestias", "excepturi", "sint", "occaecati", 
            "cupiditate", "non", "provident", "similique", "sunt", "in", "culpa", "qui", "officia", "deserunt", 
            "mollitia", "animi", "id", "est", "laborum", "et", "dolorum", "fuga", "harum", "quidem", "rerum", 
            "facilis", "est", "et", "expedita", "distinctio", "nam", "libero", "tempore", "cum", "soluta", "nobis", 
            "est", "eligendi", "optio", "cumque", "nihil", "impedit", "quo", "minus", "id", "quod", "maxime", 
            "placeat", "facere", "possimus", "omnis", "voluptas", "assumenda", "est", "omnis", "dolor", "repellendus"
        ];

        // --- ELEMENTS ---
        const outputArea = document.getElementById('output-area');
        const countSlider = document.getElementById('count-slider');
        const countDisplay = document.getElementById('count-display');
        const unitBtns = document.querySelectorAll('.unit-btn');
        const toggleStart = document.getElementById('toggle-start');
        const toggleHtml = document.getElementById('toggle-html');
        const generateBtn = document.getElementById('generate-btn');
        const copyBtn = document.getElementById('copy-btn');

        // --- STATE ---
        let state = {
            unit: 'paragraphs',
            count: 3,
            startWithLorem: true,
            useHtml: false
        };

        // --- LOGIC ---

        function getRandomWord() {
            return dictionary[Math.floor(Math.random() * dictionary.length)];
        }

        function generateSentence() {
            // Random length between 8 and 16 words for natural flow
            const length = Math.floor(Math.random() * 9) + 8; 
            let words = [];
            for (let i = 0; i < length; i++) {
                words.push(getRandomWord());
            }
            // Capitalize first letter
            let sentence = words.join(" ");
            return sentence.charAt(0).toUpperCase() + sentence.slice(1) + ".";
        }

        function generateParagraph() {
            // Random length between 3 and 7 sentences
            const length = Math.floor(Math.random() * 5) + 3;
            let sentences = [];
            for (let i = 0; i < length; i++) {
                sentences.push(generateSentence());
            }
            return sentences.join(" ");
        }

        function runGenerator() {
            let result = "";
            let items = [];

            // 1. Generate Raw Content
            if (state.unit === 'words') {
                for (let i = 0; i < state.count; i++) {
                    items.push(getRandomWord());
                }
                result = items.join(" ");
                // Capitalize first word just for looks
                result = result.charAt(0).toUpperCase() + result.slice(1);
            } 
            else if (state.unit === 'sentences') {
                for (let i = 0; i < state.count; i++) {
                    items.push(generateSentence());
                }
                result = items.join(" ");
            } 
            else {
                // Paragraphs
                for (let i = 0; i < state.count; i++) {
                    items.push(generateParagraph());
                }
            }

            // 2. Handle "Start With Lorem"
            if (state.startWithLorem) {
                const prefix = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. ";
                
                // If it's words, we just replace the start
                if (state.unit === 'words') {
                    // Just prepend roughly, simple logic
                    let current = result.split(" ");
                    let pre = prefix.replace(".", "").toLowerCase().split(" ");
                    // We need to keep total count same-ish or just prepend?
                    // Let's just prepend.
                    if (state.count < 5) {
                        result = "Lorem ipsum dolor sit amet"; // simple fallback for low count
                    } else {
                        // Remove first few random words to replace with lorem
                        let arr = result.toLowerCase().split(" ");
                        arr.splice(0, 5, ...pre.slice(0,5)); // replace first 5
                        result = arr.join(" ");
                        result = result.charAt(0).toUpperCase() + result.slice(1);
                    }
                }
                else {
                    // For sentences/paragraphs, prepend to the very first item
                    if (items.length > 0) {
                        // Strip the first sentence of the first item to replace it, or just prepend?
                        // Let's prepend to the first paragraph/sentence
                        items[0] = prefix + items[0].charAt(0).toLowerCase() + items[0].slice(1);
                        
                        if (state.unit === 'sentences') result = items.join(" ");
                    }
                }
            }

            // 3. Handle HTML Tags
            if (state.useHtml) {
                if (state.unit === 'paragraphs') {
                    result = items.map(p => `<p>${p}</p>`).join("\n\n");
                } else if (state.unit === 'sentences' || state.unit === 'words') {
                    // Wrap the whole blob
                    result = `<p>${state.unit === 'sentences' ? items.join(" ") : result}</p>`;
                }
            } else {
                if (state.unit === 'paragraphs') {
                    result = items.join("\n\n");
                }
            }

            outputArea.value = result;
        }

        // --- LISTENERS ---

        // Units
        unitBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                unitBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                state.unit = btn.dataset.unit;
                runGenerator();
            });
        });

        // Slider
        countSlider.addEventListener('input', (e) => {
            state.count = e.target.value;
            countDisplay.textContent = state.count;
            runGenerator();
        });

        // Toggles
        toggleStart.addEventListener('change', (e) => {
            state.startWithLorem = e.target.checked;
            runGenerator();
        });

        toggleHtml.addEventListener('change', (e) => {
            state.useHtml = e.target.checked;
            runGenerator();
        });

        // Buttons
        generateBtn.addEventListener('click', runGenerator);

        copyBtn.addEventListener('click', () => {
            outputArea.select();
            document.execCommand('copy');
            if(navigator.clipboard) navigator.clipboard.writeText(outputArea.value);
            
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = `<span class="text-emerald-400 font-bold text-xs">COPIED</span>`;
            setTimeout(() => copyBtn.innerHTML = originalHTML, 1500);
        });

        // Init
        runGenerator();

    </script>
</body>
</html>