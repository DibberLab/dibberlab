<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Molar Mass | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Formula Input */
        .formula-input {
            background: transparent;
            border: none;
            border-bottom: 2px solid #4b5563;
            width: 100%;
            font-size: 2.5rem;
            color: white;
            font-family: 'JetBrains Mono', monospace;
            text-align: center;
            transition: all 0.3s;
        }
        .formula-input:focus {
            outline: none;
            border-color: #f59e0b; /* Amber */
        }
        .formula-input::placeholder {
            color: #374151;
            opacity: 0.5;
        }

        /* Result Card Animation */
        .result-card {
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Breakdown Table Bars */
        .mass-bar {
            height: 6px;
            border-radius: 3px;
            background: #10b981; /* Emerald */
            transition: width 0.5s ease-out;
        }

        /* Element Badges */
        .el-badge {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            background: #1f2937;
            border: 1px solid #374151;
        }

        /* Quick Add Buttons */
        .quick-btn {
            transition: all 0.2s;
        }
        .quick-btn:hover {
            background-color: #374151;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-3xl mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Molar Mass Calculator</h1>
                <p class="text-gray-400 text-sm">Calculate the weight of chemical compounds.</p>
            </div>

            <div class="w-full bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl mb-8">
                
                <input type="text" id="formula-input" class="formula-input mb-6" placeholder="H2O" autocomplete="off" spellcheck="false">
                
                <div class="flex flex-wrap justify-center gap-2 mb-4">
                    <button class="quick-btn bg-gray-900 px-3 py-1 rounded-lg text-xs font-mono text-gray-400 border border-gray-700" onclick="setFormula('H2SO4')">H2SO4</button>
                    <button class="quick-btn bg-gray-900 px-3 py-1 rounded-lg text-xs font-mono text-gray-400 border border-gray-700" onclick="setFormula('C6H12O6')">Glucose</button>
                    <button class="quick-btn bg-gray-900 px-3 py-1 rounded-lg text-xs font-mono text-gray-400 border border-gray-700" onclick="setFormula('NaCl')">Salt</button>
                    <button class="quick-btn bg-gray-900 px-3 py-1 rounded-lg text-xs font-mono text-gray-400 border border-gray-700" onclick="setFormula('C8H10N4O2')">Caffeine</button>
                    <button class="quick-btn bg-gray-900 px-3 py-1 rounded-lg text-xs font-mono text-gray-400 border border-gray-700" onclick="setFormula('Ca(NO3)2')">Ca(NO3)2</button>
                </div>

                <div id="error-msg" class="text-red-400 text-sm font-bold text-center h-5 opacity-0 transition-opacity">
                    Invalid chemical formula
                </div>

            </div>

            <div id="result-container" class="w-full hidden">
                
                <div class="result-card bg-emerald-900/20 border border-emerald-500/50 p-6 rounded-2xl text-center mb-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Total Molar Mass</span>
                    <div class="text-5xl md:text-6xl font-black text-white mono-font mt-2" id="total-mass">0.00</div>
                    <div class="text-sm font-bold text-gray-400 mt-1">g/mol</div>
                </div>

                <div class="result-card bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="p-4 bg-gray-900/50 border-b border-gray-700">
                        <h3 class="font-bold text-gray-300">Composition Breakdown</h3>
                    </div>
                    
                    <div class="p-4">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase border-b border-gray-700">
                                    <th class="pb-2 pl-2">Element</th>
                                    <th class="pb-2 text-center">Count</th>
                                    <th class="pb-2 text-right">Mass %</th>
                                    <th class="pb-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="breakdown-body" class="text-sm">
                                </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const input = document.getElementById('formula-input');
        const errorMsg = document.getElementById('error-msg');
        const resultContainer = document.getElementById('result-container');
        const totalMassEl = document.getElementById('total-mass');
        const breakdownBody = document.getElementById('breakdown-body');

        // --- ATOMIC DATA (Common Elements) ---
        const ATOMIC_MASS = {
            "H": 1.008, "He": 4.0026, "Li": 6.94, "Be": 9.0122, "B": 10.81, "C": 12.011,
            "N": 14.007, "O": 15.999, "F": 18.998, "Ne": 20.180, "Na": 22.990, "Mg": 24.305,
            "Al": 26.982, "Si": 28.085, "P": 30.974, "S": 32.06, "Cl": 35.45, "K": 39.098,
            "Ar": 39.948, "Ca": 40.078, "Sc": 44.956, "Ti": 47.867, "V": 50.942, "Cr": 51.996,
            "Mn": 54.938, "Fe": 55.845, "Co": 58.933, "Ni": 58.693, "Cu": 63.546, "Zn": 65.38,
            "Ga": 69.723, "Ge": 72.63, "As": 74.922, "Se": 78.96, "Br": 79.904, "Kr": 83.798,
            "Rb": 85.468, "Sr": 87.62, "Y": 88.906, "Zr": 91.224, "Nb": 92.906, "Mo": 95.96,
            "Tc": 98, "Ru": 101.07, "Rh": 102.91, "Pd": 106.42, "Ag": 107.87, "Cd": 112.41,
            "In": 114.82, "Sn": 118.71, "Sb": 121.76, "Te": 127.60, "I": 126.90, "Xe": 131.29,
            "Cs": 132.91, "Ba": 137.33, "La": 138.91, "Ce": 140.12, "Pr": 140.91, "Nd": 144.24,
            "Pm": 145, "Sm": 150.36, "Eu": 151.96, "Gd": 157.25, "Tb": 158.93, "Dy": 162.50,
            "Ho": 164.93, "Er": 167.26, "Tm": 168.93, "Yb": 173.05, "Lu": 174.97, "Hf": 178.49,
            "Ta": 180.95, "W": 183.84, "Re": 186.21, "Os": 190.23, "Ir": 192.22, "Pt": 195.08,
            "Au": 196.97, "Hg": 200.59, "Tl": 204.38, "Pb": 207.2, "Bi": 208.98, "Po": 209,
            "At": 210, "Rn": 222, "Fr": 223, "Ra": 226, "Ac": 227, "Th": 232.04, "Pa": 231.04,
            "U": 238.03, "Np": 237, "Pu": 244, "Am": 243, "Cm": 247, "Bk": 247, "Cf": 251,
            "Es": 252, "Fm": 257, "Md": 258, "No": 259, "Lr": 262, "Rf": 267, "Db": 268,
            "Sg": 271, "Bh": 272, "Hs": 270, "Mt": 276, "Ds": 281, "Rg": 280, "Cn": 285,
            "Nh": 284, "Fl": 289, "Mc": 288, "Lv": 293, "Ts": 294, "Og": 294
        };

        // --- PARSER LOGIC ---

        function parseFormula(formula) {
            // Stack based parser to handle parentheses: Mg(NO3)2
            const stack = [{}]; 
            
            // Regex matches: (Element)(Number) OR (OpenParen) OR (CloseParen)(Number)
            // Group 1: Element Symbol (e.g. He, C)
            // Group 2: Count for Element
            // Group 3: (
            // Group 4: )
            // Group 5: Count for Group
            const regex = /([A-Z][a-z]*)(\d*)|(\()|(\))(\d*)/g;
            
            let match;
            let lastIndex = 0;

            while ((match = regex.exec(formula)) !== null) {
                // Check for gaps (invalid characters skipped by regex)
                if (match.index !== lastIndex) throw new Error("Invalid characters");
                lastIndex = regex.lastIndex;

                if (match[1]) {
                    // It's an element
                    const element = match[1];
                    const count = match[2] ? parseInt(match[2]) : 1;
                    
                    if (!ATOMIC_MASS[element]) throw new Error(`Unknown element: ${element}`);
                    
                    const currentMap = stack[stack.length - 1];
                    currentMap[element] = (currentMap[element] || 0) + count;
                } 
                else if (match[3]) {
                    // Open parenthesis: Start new scope
                    stack.push({});
                } 
                else if (match[4]) {
                    // Close parenthesis
                    if (stack.length < 2) throw new Error("Unbalanced parentheses");
                    
                    const topMap = stack.pop();
                    const multiplier = match[5] ? parseInt(match[5]) : 1;
                    const parentMap = stack[stack.length - 1];

                    // Merge top scope into parent scope
                    for (const [el, qty] of Object.entries(topMap)) {
                        parentMap[el] = (parentMap[el] || 0) + (qty * multiplier);
                    }
                }
            }

            if (lastIndex !== formula.length) throw new Error("Invalid syntax");
            if (stack.length !== 1) throw new Error("Unbalanced parentheses");

            return stack[0];
        }

        function calculate() {
            const raw = input.value.trim();
            if (!raw) {
                resultContainer.classList.add('hidden');
                errorMsg.style.opacity = '0';
                return;
            }

            try {
                const elements = parseFormula(raw);
                let totalMass = 0;
                const breakdown = [];

                for (const [el, count] of Object.entries(elements)) {
                    const mass = ATOMIC_MASS[el];
                    const subtotal = mass * count;
                    totalMass += subtotal;
                    breakdown.push({ el, count, mass, subtotal });
                }

                renderResult(totalMass, breakdown);
                errorMsg.style.opacity = '0';
            } catch (e) {
                resultContainer.classList.add('hidden');
                errorMsg.textContent = e.message || "Invalid Formula";
                errorMsg.style.opacity = '1';
            }
        }

        function renderResult(total, parts) {
            resultContainer.classList.remove('hidden');
            totalMassEl.textContent = total.toFixed(3);

            breakdownBody.innerHTML = '';
            
            // Sort by mass contribution descending
            parts.sort((a, b) => b.subtotal - a.subtotal);

            parts.forEach(p => {
                const percent = (p.subtotal / total) * 100;
                
                const tr = document.createElement('tr');
                tr.className = "border-b border-gray-700/50 last:border-0 hover:bg-gray-700/30 transition-colors";
                tr.innerHTML = `
                    <td class="py-3 pl-2">
                        <div class="flex items-center gap-3">
                            <div class="el-badge text-emerald-400 font-mono">${p.el}</div>
                            <div>
                                <div class="font-bold text-gray-200">${p.el}</div>
                                <div class="text-[10px] text-gray-500">${p.mass} avg</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-center font-mono text-gray-300">x${p.count}</td>
                    <td class="py-3 pr-2">
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-xs font-bold text-gray-400">${percent.toFixed(1)}%</span>
                            <div class="w-20 bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                <div class="mass-bar" style="width: ${percent}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-right font-mono font-bold text-emerald-400 pr-2">${p.subtotal.toFixed(3)}</td>
                `;
                breakdownBody.appendChild(tr);
            });
        }

        function setFormula(val) {
            input.value = val;
            calculate();
        }

        // --- LISTENERS ---
        let debounceTimer;
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(calculate, 300);
        });

    </script>
</body>
</html>