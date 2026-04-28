<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periodic Table | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* The Grid Layout */
        .periodic-grid {
            display: grid;
            grid-template-columns: repeat(18, minmax(0, 1fr));
            gap: 4px;
            width: 100%;
            user-select: none;
        }

        /* Element Cell */
        .element-cell {
            aspect-ratio: 1;
            background-color: #1f2937; /* Gray-800 */
            border: 1px solid #374151; /* Gray-700 */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 36px;
        }
        
        .element-cell:hover {
            transform: scale(1.2);
            z-index: 10;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            border-color: white;
        }

        /* Category Colors (Border & Text) */
        .cat-alkali { border-color: #ef4444; color: #ef4444; } /* Red */
        .cat-alkaline { border-color: #f59e0b; color: #f59e0b; } /* Amber */
        .cat-transition { border-color: #3b82f6; color: #3b82f6; } /* Blue */
        .cat-basic { border-color: #10b981; color: #10b981; } /* Emerald */
        .cat-semimetal { border-color: #06b6d4; color: #06b6d4; } /* Cyan */
        .cat-nonmetal { border-color: #8b5cf6; color: #8b5cf6; } /* Violet */
        .cat-halogen { border-color: #ec4899; color: #ec4899; } /* Pink */
        .cat-noble { border-color: #6366f1; color: #6366f1; } /* Indigo */
        .cat-lanthanide { border-color: #d946ef; color: #d946ef; } /* Fuchsia */
        .cat-actinide { border-color: #f43f5e; color: #f43f5e; } /* Rose */

        /* Background fills for active state */
        .element-cell.active-alkali { background-color: rgba(239, 68, 68, 0.2); }
        .element-cell.active-alkaline { background-color: rgba(245, 158, 11, 0.2); }
        .element-cell.active-transition { background-color: rgba(59, 130, 246, 0.2); }
        .element-cell.active-basic { background-color: rgba(16, 185, 129, 0.2); }
        .element-cell.active-semimetal { background-color: rgba(6, 182, 212, 0.2); }
        .element-cell.active-nonmetal { background-color: rgba(139, 92, 246, 0.2); }
        .element-cell.active-halogen { background-color: rgba(236, 72, 153, 0.2); }
        .element-cell.active-noble { background-color: rgba(99, 102, 241, 0.2); }
        .element-cell.active-lanthanide { background-color: rgba(217, 70, 239, 0.2); }
        .element-cell.active-actinide { background-color: rgba(244, 63, 94, 0.2); }

        /* Dimmed State */
        .dimmed { opacity: 0.2; filter: grayscale(1); transform: scale(1) !important; }

        /* Atomic Number */
        .at-num { font-size: 0.6rem; position: absolute; top: 2px; left: 4px; opacity: 0.7; }
        
        /* Symbol */
        .at-sym { font-weight: 800; font-size: 1.1rem; }
        
        /* Name (Only visible on larger screens/cells) */
        .at-name { font-size: 0.5rem; opacity: 0.8; display: none; }
        @media (min-width: 1024px) { .at-name { display: block; } }

        /* The Inspector Card */
        .atom-visual {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 1px dashed rgba(255,255,255,0.2);
            animation: spin 20s linear infinite;
        }
        .electron-ring {
            position: absolute;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Custom Scrollbar for horizontal scrolling table */
        .table-wrapper::-webkit-scrollbar { height: 8px; }
        .table-wrapper::-webkit-scrollbar-track { background: #111827; }
        .table-wrapper::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 py-8 flex flex-col items-center">
        <div class="w-full max-w-7xl">
            
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end mb-6 gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400 mb-2">Periodic Table</h1>
                    <p class="text-gray-400 text-sm max-w-md">Interactive explorer of the elements. Hover to analyze atomic structure.</p>
                </div>

                <div class="flex flex-wrap gap-2 text-[10px] font-bold uppercase">
                    <button class="px-3 py-1 rounded-full border border-red-500 text-red-500 hover:bg-red-500/20 transition-colors" onclick="filterCat('alkali')">Alkali</button>
                    <button class="px-3 py-1 rounded-full border border-amber-500 text-amber-500 hover:bg-amber-500/20 transition-colors" onclick="filterCat('alkaline')">Alkaline</button>
                    <button class="px-3 py-1 rounded-full border border-blue-500 text-blue-500 hover:bg-blue-500/20 transition-colors" onclick="filterCat('transition')">Transition</button>
                    <button class="px-3 py-1 rounded-full border border-emerald-500 text-emerald-500 hover:bg-emerald-500/20 transition-colors" onclick="filterCat('basic')">Basic Metal</button>
                    <button class="px-3 py-1 rounded-full border border-cyan-500 text-cyan-500 hover:bg-cyan-500/20 transition-colors" onclick="filterCat('semimetal')">Metalloid</button>
                    <button class="px-3 py-1 rounded-full border border-violet-500 text-violet-500 hover:bg-violet-500/20 transition-colors" onclick="filterCat('nonmetal')">Nonmetal</button>
                    <button class="px-3 py-1 rounded-full border border-pink-500 text-pink-500 hover:bg-pink-500/20 transition-colors" onclick="filterCat('halogen')">Halogen</button>
                    <button class="px-3 py-1 rounded-full border border-indigo-500 text-indigo-500 hover:bg-indigo-500/20 transition-colors" onclick="filterCat('noble')">Noble Gas</button>
                    <button class="px-3 py-1 rounded-full border border-gray-500 text-gray-400 hover:bg-gray-700 transition-colors" onclick="filterCat('all')">Reset</button>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-grow w-full overflow-x-auto table-wrapper pb-4">
                    <div class="periodic-grid w-[900px] xl:w-full" id="grid-container">
                        </div>
                </div>

                <div class="w-full lg:w-72 flex-shrink-0 sticky lg:top-8">
                    
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-2xl relative overflow-hidden transition-colors duration-300" id="inspector-card">
                        
                        <div class="absolute -right-4 -bottom-4 text-9xl font-black opacity-5 select-none" id="ins-bg-sym">H</div>

                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="text-6xl font-bold text-white mb-1" id="ins-sym">H</div>
                                <div class="text-lg text-gray-300" id="ins-name">Hydrogen</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-gray-500" id="ins-num">1</div>
                                <div class="text-xs font-mono text-emerald-400" id="ins-mass">1.008</div>
                            </div>
                        </div>

                        <div class="flex justify-center my-6">
                            <div class="atom-visual flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-full shadow-[0_0_10px_white]"></div> <div id="rings-container"></div>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="text-gray-500">Category</span>
                                <span class="font-bold text-white capitalize" id="ins-cat">Nonmetal</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="text-gray-500">Phase</span>
                                <span class="font-bold text-white" id="ins-phase">Gas</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="text-gray-500">Config</span>
                                <span class="font-mono text-amber-400 text-xs" id="ins-conf">1s1</span>
                            </div>
                            <div class="mt-4 text-xs text-gray-400 leading-relaxed italic" id="ins-desc">
                                "The lightest element."
                            </div>
                        </div>

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
        // Compressed dataset for the main table elements
        // [Atomic#, Symbol, Name, Mass, Category, Row, Col]
        // Categories: alkali, alkaline, transition, basic, semimetal, nonmetal, halogen, noble, lanthanide, actinide
        const ELEMENT_DATA = [
            [1,"H","Hydrogen","1.008","nonmetal",1,1,"1s1","Gas"],
            [2,"He","Helium","4.0026","noble",1,18,"1s2","Gas"],
            [3,"Li","Lithium","6.94","alkali",2,1,"[He] 2s1","Solid"],
            [4,"Be","Beryllium","9.0122","alkaline",2,2,"[He] 2s2","Solid"],
            [5,"B","Boron","10.81","semimetal",2,13,"[He] 2s2 2p1","Solid"],
            [6,"C","Carbon","12.011","nonmetal",2,14,"[He] 2s2 2p2","Solid"],
            [7,"N","Nitrogen","14.007","nonmetal",2,15,"[He] 2s2 2p3","Gas"],
            [8,"O","Oxygen","15.999","nonmetal",2,16,"[He] 2s2 2p4","Gas"],
            [9,"F","Fluorine","18.998","halogen",2,17,"[He] 2s2 2p5","Gas"],
            [10,"Ne","Neon","20.180","noble",2,18,"[He] 2s2 2p6","Gas"],
            [11,"Na","Sodium","22.990","alkali",3,1,"[Ne] 3s1","Solid"],
            [12,"Mg","Magnesium","24.305","alkaline",3,2,"[Ne] 3s2","Solid"],
            [13,"Al","Aluminum","26.982","basic",3,13,"[Ne] 3s2 3p1","Solid"],
            [14,"Si","Silicon","28.085","semimetal",3,14,"[Ne] 3s2 3p2","Solid"],
            [15,"P","Phosphorus","30.974","nonmetal",3,15,"[Ne] 3s2 3p3","Solid"],
            [16,"S","Sulfur","32.06","nonmetal",3,16,"[Ne] 3s2 3p4","Solid"],
            [17,"Cl","Chlorine","35.45","halogen",3,17,"[Ne] 3s2 3p5","Gas"],
            [18,"Ar","Argon","39.948","noble",3,18,"[Ne] 3s2 3p6","Gas"],
            [19,"K","Potassium","39.098","alkali",4,1,"[Ar] 4s1","Solid"],
            [20,"Ca","Calcium","40.078","alkaline",4,2,"[Ar] 4s2","Solid"],
            [21,"Sc","Scandium","44.956","transition",4,3,"[Ar] 3d1 4s2","Solid"],
            [22,"Ti","Titanium","47.867","transition",4,4,"[Ar] 3d2 4s2","Solid"],
            [23,"V","Vanadium","50.942","transition",4,5,"[Ar] 3d3 4s2","Solid"],
            [24,"Cr","Chromium","51.996","transition",4,6,"[Ar] 3d5 4s1","Solid"],
            [25,"Mn","Manganese","54.938","transition",4,7,"[Ar] 3d5 4s2","Solid"],
            [26,"Fe","Iron","55.845","transition",4,8,"[Ar] 3d6 4s2","Solid"],
            [27,"Co","Cobalt","58.933","transition",4,9,"[Ar] 3d7 4s2","Solid"],
            [28,"Ni","Nickel","58.693","transition",4,10,"[Ar] 3d8 4s2","Solid"],
            [29,"Cu","Copper","63.546","transition",4,11,"[Ar] 3d10 4s1","Solid"],
            [30,"Zn","Zinc","65.38","transition",4,12,"[Ar] 3d10 4s2","Solid"],
            [31,"Ga","Gallium","69.723","basic",4,13,"[Ar] 3d10 4s2 4p1","Solid"],
            [32,"Ge","Germanium","72.630","semimetal",4,14,"[Ar] 3d10 4s2 4p2","Solid"],
            [33,"As","Arsenic","74.922","semimetal",4,15,"[Ar] 3d10 4s2 4p3","Solid"],
            [34,"Se","Selenium","78.971","nonmetal",4,16,"[Ar] 3d10 4s2 4p4","Solid"],
            [35,"Br","Bromine","79.904","halogen",4,17,"[Ar] 3d10 4s2 4p5","Liquid"],
            [36,"Kr","Krypton","83.798","noble",4,18,"[Ar] 3d10 4s2 4p6","Gas"],
            [37,"Rb","Rubidium","85.468","alkali",5,1,"[Kr] 5s1","Solid"],
            [38,"Sr","Strontium","87.62","alkaline",5,2,"[Kr] 5s2","Solid"],
            [39,"Y","Yttrium","88.906","transition",5,3,"[Kr] 4d1 5s2","Solid"],
            [40,"Zr","Zirconium","91.224","transition",5,4,"[Kr] 4d2 5s2","Solid"],
            [41,"Nb","Niobium","92.906","transition",5,5,"[Kr] 4d4 5s1","Solid"],
            [42,"Mo","Molybdenum","95.95","transition",5,6,"[Kr] 4d5 5s1","Solid"],
            [43,"Tc","Technetium","(98)","transition",5,7,"[Kr] 4d5 5s2","Solid"],
            [44,"Ru","Ruthenium","101.07","transition",5,8,"[Kr] 4d7 5s1","Solid"],
            [45,"Rh","Rhodium","102.91","transition",5,9,"[Kr] 4d8 5s1","Solid"],
            [46,"Pd","Palladium","106.42","transition",5,10,"[Kr] 4d10","Solid"],
            [47,"Ag","Silver","107.87","transition",5,11,"[Kr] 4d10 5s1","Solid"],
            [48,"Cd","Cadmium","112.41","transition",5,12,"[Kr] 4d10 5s2","Solid"],
            [49,"In","Indium","114.82","basic",5,13,"[Kr] 4d10 5s2 5p1","Solid"],
            [50,"Sn","Tin","118.71","basic",5,14,"[Kr] 4d10 5s2 5p2","Solid"],
            [51,"Sb","Antimony","121.76","semimetal",5,15,"[Kr] 4d10 5s2 5p3","Solid"],
            [52,"Te","Tellurium","127.60","semimetal",5,16,"[Kr] 4d10 5s2 5p4","Solid"],
            [53,"I","Iodine","126.90","halogen",5,17,"[Kr] 4d10 5s2 5p5","Solid"],
            [54,"Xe","Xenon","131.29","noble",5,18,"[Kr] 4d10 5s2 5p6","Gas"],
            [55,"Cs","Cesium","132.91","alkali",6,1,"[Xe] 6s1","Solid"],
            [56,"Ba","Barium","137.33","alkaline",6,2,"[Xe] 6s2","Solid"],
            // Lanthanides gap
            [71,"Lu","Lutetium","174.97","lanthanide",6,3,"[Xe] 4f14 5d1 6s2","Solid"],
            [72,"Hf","Hafnium","178.49","transition",6,4,"[Xe] 4f14 5d2 6s2","Solid"],
            [73,"Ta","Tantalum","180.95","transition",6,5,"[Xe] 4f14 5d3 6s2","Solid"],
            [74,"W","Tungsten","183.84","transition",6,6,"[Xe] 4f14 5d4 6s2","Solid"],
            [75,"Re","Rhenium","186.21","transition",6,7,"[Xe] 4f14 5d5 6s2","Solid"],
            [76,"Os","Osmium","190.23","transition",6,8,"[Xe] 4f14 5d6 6s2","Solid"],
            [77,"Ir","Iridium","192.22","transition",6,9,"[Xe] 4f14 5d7 6s2","Solid"],
            [78,"Pt","Platinum","195.08","transition",6,10,"[Xe] 4f14 5d9 6s1","Solid"],
            [79,"Au","Gold","196.97","transition",6,11,"[Xe] 4f14 5d10 6s1","Solid"],
            [80,"Hg","Mercury","200.59","transition",6,12,"[Xe] 4f14 5d10 6s2","Liquid"],
            [81,"Tl","Thallium","204.38","basic",6,13,"[Xe] 4f14 5d10 6s2 6p1","Solid"],
            [82,"Pb","Lead","207.2","basic",6,14,"[Xe] 4f14 5d10 6s2 6p2","Solid"],
            [83,"Bi","Bismuth","208.98","basic",6,15,"[Xe] 4f14 5d10 6s2 6p3","Solid"],
            [84,"Po","Polonium","(209)","semimetal",6,16,"[Xe] 4f14 5d10 6s2 6p4","Solid"],
            [85,"At","Astatine","(210)","halogen",6,17,"[Xe] 4f14 5d10 6s2 6p5","Solid"],
            [86,"Rn","Radon","(222)","noble",6,18,"[Xe] 4f14 5d10 6s2 6p6","Gas"],
            [87,"Fr","Francium","(223)","alkali",7,1,"[Rn] 7s1","Solid"],
            [88,"Ra","Radium","(226)","alkaline",7,2,"[Rn] 7s2","Solid"],
            // Actinides gap
            [103,"Lr","Lawrencium","(266)","actinide",7,3,"[Rn] 5f14 7s2 7p1","Solid"],
            [104,"Rf","Rutherfordium","(267)","transition",7,4,"[Rn] 5f14 6d2 7s2","Solid"],
            // Lanthanides Row (Period 8 technically for visual layout)
            [57,"La","Lanthanum","138.91","lanthanide",9,4,"[Xe] 5d1 6s2","Solid"],
            [58,"Ce","Cerium","140.12","lanthanide",9,5,"[Xe] 4f1 5d1 6s2","Solid"],
            [59,"Pr","Praseodymium","140.91","lanthanide",9,6,"[Xe] 4f3 6s2","Solid"],
            [60,"Nd","Neodymium","144.24","lanthanide",9,7,"[Xe] 4f4 6s2","Solid"],
            [61,"Pm","Promethium","(145)","lanthanide",9,8,"[Xe] 4f5 6s2","Solid"],
            [62,"Sm","Samarium","150.36","lanthanide",9,9,"[Xe] 4f6 6s2","Solid"],
            [63,"Eu","Europium","151.96","lanthanide",9,10,"[Xe] 4f7 6s2","Solid"],
            [64,"Gd","Gadolinium","157.25","lanthanide",9,11,"[Xe] 4f7 5d1 6s2","Solid"],
            [65,"Tb","Terbium","158.93","lanthanide",9,12,"[Xe] 4f9 6s2","Solid"],
            [66,"Dy","Dysprosium","162.50","lanthanide",9,13,"[Xe] 4f10 6s2","Solid"],
            [67,"Ho","Holmium","164.93","lanthanide",9,14,"[Xe] 4f11 6s2","Solid"],
            [68,"Er","Erbium","167.26","lanthanide",9,15,"[Xe] 4f12 6s2","Solid"],
            [69,"Tm","Thulium","168.93","lanthanide",9,16,"[Xe] 4f13 6s2","Solid"],
            [70,"Yb","Ytterbium","173.05","lanthanide",9,17,"[Xe] 4f14 6s2","Solid"],
            // Actinides Row
            [89,"Ac","Actinium","(227)","actinide",10,4,"[Rn] 6d1 7s2","Solid"],
            [90,"Th","Thorium","232.04","actinide",10,5,"[Rn] 6d2 7s2","Solid"],
            [91,"Pa","Protactinium","231.04","actinide",10,6,"[Rn] 5f2 6d1 7s2","Solid"],
            [92,"U","Uranium","238.03","actinide",10,7,"[Rn] 5f3 6d1 7s2","Solid"],
            [93,"Np","Neptunium","(237)","actinide",10,8,"[Rn] 5f4 6d1 7s2","Solid"],
            [94,"Pu","Plutonium","(244)","actinide",10,9,"[Rn] 5f6 7s2","Solid"],
            [95,"Am","Americium","(243)","actinide",10,10,"[Rn] 5f7 7s2","Solid"],
            [96,"Cm","Curium","(247)","actinide",10,11,"[Rn] 5f7 6d1 7s2","Solid"],
            [97,"Bk","Berkelium","(247)","actinide",10,12,"[Rn] 5f9 7s2","Solid"],
            [98,"Cf","Californium","(251)","actinide",10,13,"[Rn] 5f10 7s2","Solid"],
            [99,"Es","Einsteinium","(252)","actinide",10,14,"[Rn] 5f11 7s2","Solid"],
            [100,"Fm","Fermium","(257)","actinide",10,15,"[Rn] 5f12 7s2","Solid"],
            [101,"Md","Mendelevium","(258)","actinide",10,16,"[Rn] 5f13 7s2","Solid"],
            [102,"No","Nobelium","(259)","actinide",10,17,"[Rn] 5f14 7s2","Solid"]
        ];

        const grid = document.getElementById('grid-container');
        const inspector = document.getElementById('inspector-card');
        const ringsContainer = document.getElementById('rings-container');

        // Inspector Elements
        const insSym = document.getElementById('ins-sym');
        const insBgSym = document.getElementById('ins-bg-sym');
        const insName = document.getElementById('ins-name');
        const insNum = document.getElementById('ins-num');
        const insMass = document.getElementById('ins-mass');
        const insCat = document.getElementById('ins-cat');
        const insPhase = document.getElementById('ins-phase');
        const insConf = document.getElementById('ins-conf');
        const insDesc = document.getElementById('ins-desc');

        // --- INIT ---

        function initTable() {
            ELEMENT_DATA.forEach(el => {
                const [num, sym, name, mass, cat, row, col, conf, phase] = el;
                
                const div = document.createElement('div');
                div.className = `element-cell cat-${cat}`;
                div.style.gridRow = row;
                div.style.gridColumn = col;
                div.dataset.cat = cat;
                
                // Content
                div.innerHTML = `
                    <span class="at-num">${num}</span>
                    <span class="at-sym">${sym}</span>
                    <span class="at-name">${name}</span>
                `;

                // Events
                div.addEventListener('mouseenter', () => updateInspector(el));
                
                grid.appendChild(div);
            });
        }

        // --- INSPECTOR LOGIC ---

        function updateInspector(data) {
            const [num, sym, name, mass, cat, row, col, conf, phase] = data;

            // Update Text
            insSym.textContent = sym;
            insBgSym.textContent = sym;
            insName.textContent = name;
            insNum.textContent = num;
            insMass.textContent = mass;
            insCat.textContent = cat;
            insPhase.textContent = phase;
            insConf.textContent = conf;

            // Generate "Fake" Description based on category (to save data size)
            insDesc.textContent = getDesc(cat, name);

            // Update Colors
            // Reset borders/colors from previous state is tricky with Tailwind classes in JS
            // Easier to map category to hex for style injection
            const colorMap = {
                alkali: '#ef4444', alkaline: '#f59e0b', transition: '#3b82f6',
                basic: '#10b981', semimetal: '#06b6d4', nonmetal: '#8b5cf6',
                halogen: '#ec4899', noble: '#6366f1', lanthanide: '#d946ef', actinide: '#f43f5e'
            };
            const color = colorMap[cat] || '#9ca3af';
            
            inspector.style.borderColor = color;
            insSym.style.color = color;

            // Update Atom Visuals (Electron Rings)
            renderRings(row); // Approximate rings by Period (Row)
        }

        function renderRings(period) {
            ringsContainer.innerHTML = '';
            // If it's lanthanide/actinide, adjust ring count manually as they sit lower visually
            let count = period;
            if (count > 7) count = count - 2; 

            for(let i=1; i<=count; i++) {
                const ring = document.createElement('div');
                ring.className = 'electron-ring';
                const size = i * 14 + 10;
                ring.style.width = `${size}px`;
                ring.style.height = `${size}px`;
                ringsContainer.appendChild(ring);
            }
        }

        function getDesc(cat, name) {
            const descs = {
                alkali: "Highly reactive metal. Never found freely in nature.",
                alkaline: "Reactive metal. Harder and denser than alkali metals.",
                transition: "High melting point metal. Good conductor of heat and electricity.",
                basic: "Soft metal with a relatively low melting point.",
                semimetal: "Has properties intermediate between metals and nonmetals.",
                nonmetal: "Brittle solid or gas. Poor conductor of heat and electricity.",
                halogen: "Highly reactive nonmetal. Forms salts with metals.",
                noble: "Odorless, colorless, monatomic gas with very low chemical reactivity.",
                lanthanide: "Silvery-white soft metal. Tarnishes rapidly in air.",
                actinide: "Radioactive metal. Most are synthetic."
            };
            return descs[cat] || `Analysis for ${name} loaded.`;
        }

        // --- FILTER LOGIC ---

        function filterCat(cat) {
            const cells = document.querySelectorAll('.element-cell');
            
            cells.forEach(cell => {
                cell.classList.remove('dimmed', `active-${cell.dataset.cat}`);
                
                if (cat === 'all') return;

                if (cell.dataset.cat !== cat) {
                    cell.classList.add('dimmed');
                } else {
                    cell.classList.add(`active-${cat}`);
                }
            });
        }

        // Init
        initTable();
        // Set Hydrogen as default
        updateInspector(ELEMENT_DATA[0]);

    </script>
</body>
</html>