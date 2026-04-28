<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roman Numerals | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .serif-font { font-family: 'Cinzel', serif; }

        /* Marble Background Texture */
        .marble-bg {
            background-color: #f3f4f6;
            background-image: 
                radial-gradient(at 10% 10%, rgba(0,0,0,0.05) 0%, transparent 50%),
                radial-gradient(at 90% 90%, rgba(0,0,0,0.05) 0%, transparent 50%);
        }

        /* Gold Text Gradient */
        .text-gold {
            background: linear-gradient(to bottom, #d97706, #b45309);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Stone Input Fields */
        .stone-input {
            background: #e5e7eb;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.1), inset -2px -2px 5px rgba(255,255,255,0.7);
            border: 1px solid #d1d5db;
            transition: all 0.3s;
        }
        .stone-input:focus {
            outline: none;
            background: #f3f4f6;
            box-shadow: inset 1px 1px 3px rgba(0,0,0,0.05), inset -1px -1px 3px rgba(255,255,255,1);
            border-color: #d97706;
        }

        /* Chiseled Card Look */
        .chiseled-card {
            background: #f9fafb;
            box-shadow: 
                20px 20px 60px #bebebe, 
                -20px -20px 60px #ffffff;
            border-radius: 20px;
        }

        /* Error Shake Animation */
        .shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; border-color: #ef4444 !important; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="marble-bg text-gray-800 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-12">
        <div class="w-full max-w-2xl mx-auto">
            
            <div class="text-center mb-10">
                <h1 class="text-5xl font-black serif-font text-gold mb-2 tracking-widest uppercase">Imperium</h1>
                <p class="text-gray-500 text-sm tracking-widest uppercase">Roman Numeral Converter</p>
            </div>

            <div class="chiseled-card p-8 md:p-12 relative overflow-hidden">
                
                <div class="absolute top-0 left-8 w-2 h-full border-l border-r border-gray-200 opacity-50"></div>
                <div class="absolute top-0 right-8 w-2 h-full border-l border-r border-gray-200 opacity-50"></div>

                <div class="relative z-10 flex flex-col gap-8">
                    
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Arabic (Standard)</label>
                        <input type="number" id="input-number" placeholder="1994" class="stone-input w-full p-6 text-center text-3xl font-bold text-gray-800 rounded-xl font-mono" oninput="convertFromNumber()">
                    </div>

                    <div class="flex justify-center">
                        <div class="bg-gray-200 rounded-full p-2 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Roman</label>
                        <input type="text" id="input-roman" placeholder="MCMXCIV" class="stone-input w-full p-6 text-center text-3xl font-bold text-gray-800 rounded-xl serif-font uppercase" oninput="convertFromRoman()">
                    </div>

                </div>

                <div id="error-msg" class="text-center mt-6 text-red-500 font-bold text-sm h-6 opacity-0 transition-opacity">
                    Invalid format
                </div>

            </div>

            <div class="mt-12 flex justify-center gap-4 text-gray-400 text-xs font-bold uppercase tracking-widest serif-font">
                <span>I = 1</span>
                <span>V = 5</span>
                <span>X = 10</span>
                <span>L = 50</span>
                <span>C = 100</span>
                <span>D = 500</span>
                <span>M = 1000</span>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-400 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const numInput = document.getElementById('input-number');
        const romInput = document.getElementById('input-roman');
        const errorMsg = document.getElementById('error-msg');

        // Logic Maps
        const VAL_MAP = [
            { val: 1000, sym: 'M' },
            { val: 900, sym: 'CM' },
            { val: 500, sym: 'D' },
            { val: 400, sym: 'CD' },
            { val: 100, sym: 'C' },
            { val: 90, sym: 'XC' },
            { val: 50, sym: 'L' },
            { val: 40, sym: 'XL' },
            { val: 10, sym: 'X' },
            { val: 9, sym: 'IX' },
            { val: 5, sym: 'V' },
            { val: 4, sym: 'IV' },
            { val: 1, sym: 'I' }
        ];

        const ROM_MAP = {
            'I': 1, 'V': 5, 'X': 10, 'L': 50, 'C': 100, 'D': 500, 'M': 1000
        };

        // --- CONVERTERS ---

        function toRoman(num) {
            if (num < 1 || num > 3999) return ""; // Standard roman numerals usually stop at 3999
            let result = '';
            for (let i of VAL_MAP) {
                while (num >= i.val) {
                    result += i.sym;
                    num -= i.val;
                }
            }
            return result;
        }

        function toArabic(str) {
            if (!/^M*(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/.test(str)) {
                return null; // Invalid pattern check
            }
            
            let result = 0;
            for (let i = 0; i < str.length; i++) {
                const current = ROM_MAP[str[i]];
                const next = ROM_MAP[str[i + 1]];

                if (next && current < next) {
                    result -= current;
                } else {
                    result += current;
                }
            }
            return result;
        }

        // --- HANDLERS ---

        function convertFromNumber() {
            resetState();
            const val = parseInt(numInput.value);
            
            if (isNaN(val)) {
                romInput.value = "";
                return;
            }

            if (val < 1 || val > 3999) {
                showError("Enter a number between 1 and 3999", numInput);
                romInput.value = "";
                return;
            }

            romInput.value = toRoman(val);
        }

        function convertFromRoman() {
            resetState();
            // Force uppercase for consistent handling
            const val = romInput.value.toUpperCase();
            // Update input visually to uppercase immediately
            romInput.value = val;

            if (!val) {
                numInput.value = "";
                return;
            }

            const num = toArabic(val);

            if (num === null) {
                showError("Invalid Roman Numeral sequence", romInput);
                numInput.value = "";
                return;
            }

            numInput.value = num;
        }

        // --- UTILS ---

        function showError(msg, el) {
            errorMsg.textContent = msg;
            errorMsg.style.opacity = "1";
            el.classList.add('shake');
            setTimeout(() => el.classList.remove('shake'), 400);
        }

        function resetState() {
            errorMsg.style.opacity = "0";
            numInput.classList.remove('shake');
            romInput.classList.remove('shake');
        }

    </script>
</body>
</html>