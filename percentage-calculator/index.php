<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Percentage Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Input Styling */
        input[type="number"] {
            -moz-appearance: textfield;
            font-family: 'JetBrains Mono', monospace;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .calc-card {
            transition: border-color 0.2s, transform 0.2s;
        }
        .calc-card:focus-within {
            border-color: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        /* Result Highlighting */
        .result-box {
            transition: color 0.2s, background-color 0.2s;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Percentage Calculator</h1>
                <p class="text-center text-gray-400">Calculate proportions, increases, and decreases instantly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="calc-card bg-gray-800 rounded-xl border border-gray-700 p-6 flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-blue-900/50 text-blue-300 px-2 py-1 rounded text-xs font-bold uppercase">Basic</span>
                        <h3 class="font-bold text-white">What is X% of Y?</h3>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="relative w-24">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                            <input type="number" id="c1-num1" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500 text-center" placeholder="X">
                        </div>
                        <span class="text-gray-500 font-bold">of</span>
                        <input type="number" id="c1-num2" class="flex-grow bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500" placeholder="Y">
                    </div>

                    <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 text-center">
                        <span class="text-xs text-gray-500 uppercase font-bold block mb-1">Result</span>
                        <span id="c1-result" class="text-3xl font-bold text-blue-400 mono-font">-</span>
                    </div>
                </div>

                <div class="calc-card bg-gray-800 rounded-xl border border-gray-700 p-6 flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-purple-900/50 text-purple-300 px-2 py-1 rounded text-xs font-bold uppercase">Reverse</span>
                        <h3 class="font-bold text-white">X is what % of Y?</h3>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="number" id="c2-num1" class="w-24 bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-purple-500 text-center" placeholder="X">
                        <span class="text-gray-500 font-bold text-sm">is what % of</span>
                        <input type="number" id="c2-num2" class="flex-grow bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-purple-500" placeholder="Y">
                    </div>

                    <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 text-center">
                        <span class="text-xs text-gray-500 uppercase font-bold block mb-1">Result</span>
                        <span id="c2-result" class="text-3xl font-bold text-purple-400 mono-font">-</span>
                    </div>
                </div>

                <div class="calc-card bg-gray-800 rounded-xl border border-gray-700 p-6 flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-emerald-900/50 text-emerald-300 px-2 py-1 rounded text-xs font-bold uppercase">Change</span>
                        <h3 class="font-bold text-white">Increase / Decrease</h3>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col flex-1">
                            <label class="text-[10px] text-gray-500 uppercase font-bold mb-1 pl-1">From</label>
                            <input type="number" id="c3-num1" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500" placeholder="Old Value">
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-[10px] text-gray-500 uppercase font-bold mb-1 pl-1">To</label>
                            <input type="number" id="c3-num2" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500" placeholder="New Value">
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 text-center flex justify-between items-center px-8">
                        <div class="text-left">
                            <span class="text-xs text-gray-500 uppercase font-bold block">Difference</span>
                            <span id="c3-diff" class="text-lg font-bold text-gray-300 mono-font">-</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 uppercase font-bold block">Change</span>
                            <span id="c3-result" class="text-3xl font-bold text-gray-500 mono-font">-</span>
                        </div>
                    </div>
                </div>

                <div class="calc-card bg-gray-800 rounded-xl border border-gray-700 p-6 flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-900/50 text-amber-300 px-2 py-1 rounded text-xs font-bold uppercase">Find Total</span>
                        <h3 class="font-bold text-white">X is Y% of what?</h3>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="number" id="c4-num1" class="flex-grow bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500" placeholder="X">
                        <span class="text-gray-500 font-bold text-sm">is</span>
                        <div class="relative w-24">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold">%</span>
                            <input type="number" id="c4-num2" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500 text-center" placeholder="Y">
                        </div>
                        <span class="text-gray-500 font-bold text-sm">of what?</span>
                    </div>

                    <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 text-center">
                        <span class="text-xs text-gray-500 uppercase font-bold block mb-1">Result (Total)</span>
                        <span id="c4-result" class="text-3xl font-bold text-amber-400 mono-font">-</span>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- CALC 1: X% of Y ---
        const c1n1 = document.getElementById('c1-num1');
        const c1n2 = document.getElementById('c1-num2');
        const c1res = document.getElementById('c1-result');

        function calc1() {
            const p = parseFloat(c1n1.value);
            const n = parseFloat(c1n2.value);
            if (!isNaN(p) && !isNaN(n)) {
                const res = (p / 100) * n;
                c1res.textContent = parseFloat(res.toFixed(2));
            } else {
                c1res.textContent = "-";
            }
        }
        c1n1.addEventListener('input', calc1);
        c1n2.addEventListener('input', calc1);

        // --- CALC 2: X is what % of Y ---
        const c2n1 = document.getElementById('c2-num1');
        const c2n2 = document.getElementById('c2-num2');
        const c2res = document.getElementById('c2-result');

        function calc2() {
            const part = parseFloat(c2n1.value);
            const total = parseFloat(c2n2.value);
            if (!isNaN(part) && !isNaN(total) && total !== 0) {
                const res = (part / total) * 100;
                c2res.textContent = parseFloat(res.toFixed(2)) + "%";
            } else {
                c2res.textContent = "-";
            }
        }
        c2n1.addEventListener('input', calc2);
        c2n2.addEventListener('input', calc2);

        // --- CALC 3: Percentage Change ---
        const c3n1 = document.getElementById('c3-num1');
        const c3n2 = document.getElementById('c3-num2');
        const c3res = document.getElementById('c3-result');
        const c3diff = document.getElementById('c3-diff');

        function calc3() {
            const oldVal = parseFloat(c3n1.value);
            const newVal = parseFloat(c3n2.value);
            
            if (!isNaN(oldVal) && !isNaN(newVal)) {
                const diff = newVal - oldVal;
                c3diff.textContent = (diff > 0 ? "+" : "") + parseFloat(diff.toFixed(2));
                
                if (oldVal !== 0) {
                    const change = (diff / oldVal) * 100;
                    const sign = change > 0 ? "+" : "";
                    c3res.textContent = sign + parseFloat(change.toFixed(2)) + "%";
                    
                    // Styling
                    if(change > 0) c3res.className = "text-3xl font-bold text-emerald-400 mono-font";
                    else if(change < 0) c3res.className = "text-3xl font-bold text-red-400 mono-font";
                    else c3res.className = "text-3xl font-bold text-gray-400 mono-font";
                } else {
                    c3res.textContent = "∞";
                }
            } else {
                c3res.textContent = "-";
                c3diff.textContent = "-";
            }
        }
        c3n1.addEventListener('input', calc3);
        c3n2.addEventListener('input', calc3);

        // --- CALC 4: X is Y% of what? ---
        const c4n1 = document.getElementById('c4-num1');
        const c4n2 = document.getElementById('c4-num2');
        const c4res = document.getElementById('c4-result');

        function calc4() {
            const part = parseFloat(c4n1.value);
            const percent = parseFloat(c4n2.value);
            
            if (!isNaN(part) && !isNaN(percent) && percent !== 0) {
                const total = part / (percent / 100);
                c4res.textContent = parseFloat(total.toFixed(2));
            } else {
                c4res.textContent = "-";
            }
        }
        c4n1.addEventListener('input', calc4);
        c4n2.addEventListener('input', calc4);

    </script>
</body>
</html>