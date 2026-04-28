<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle of Fifths | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        #circle-container {
            position: relative;
            width: 100%;
            max-width: 340px; /* Slightly wider for breathing room */
            aspect-ratio: 1/1;
            margin: 0 auto;
        }

        .key-node {
            position: absolute;
            /* Positioning is now handled via top/left in JS, not transform */
            width: 48px;
            height: 48px;
            margin-left: -24px; /* Centering offset */
            margin-top: -24px;  /* Centering offset */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s, box-shadow 0.2s;
            background-color: #374151; /* gray-700 */
            border: 2px solid #4b5563;
            color: #d1d5db;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        /* Hover now safely scales without breaking position */
        .key-node:hover {
            transform: scale(1.15); 
            background-color: #4b5563;
            color: white;
            z-index: 50; /* Ensure it pops over neighbors */
        }

        .key-node.active {
            background-color: #f59e0b !important; /* Amber */
            border-color: #f59e0b;
            color: #111827;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.4);
            transform: scale(1.2);
            z-index: 40;
        }

        /* Inner Circle (Minors) */
        .minor-node {
            width: 38px;
            height: 38px;
            margin-left: -19px;
            margin-top: -19px;
            font-size: 0.8rem;
            background-color: #1f2937; /* darker */
            border: 1px solid #374151;
        }
        
        .minor-node.active {
            background-color: #10b981 !important; /* Emerald */
            border-color: #10b981;
            color: white;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }

        #center-info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, #374151 0%, #1f2937 100%);
            border: 1px solid #4b5563;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            z-index: 5;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
            pointer-events: none; /* Let clicks pass through if needed */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 pb-12">
        <div class="w-full max-w-xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Circle of Fifths</h1>
            <p class="text-center text-gray-400 mb-8">Click a key to see its signature.</p>

            <div id="circle-container">
                <div id="center-info">
                    <span class="text-xs text-gray-400 uppercase tracking-widest mb-1">KEY</span>
                    <h2 id="display-major" class="text-4xl font-bold text-white mb-1">C</h2>
                    <p id="display-minor" class="text-sm text-emerald-400 font-medium mb-2">Rel: Am</p>
                    <div class="h-px w-16 bg-gray-600 mb-2"></div>
                    <p id="display-sig" class="text-xs text-gray-300">No Sharps/Flats</p>
                </div>
                </div>

            <div class="mt-8 flex justify-center gap-6 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div> Major
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div> Minor
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA ---
        // Order: 12 o'clock (C) clockwise
        const circleData = [
            { maj: "C", min: "Am", sig: "0", detail: "Natural" },
            { maj: "G", min: "Em", sig: "1♯", detail: "F♯" },
            { maj: "D", min: "Bm", sig: "2♯", detail: "F♯, C♯" },
            { maj: "A", min: "F♯m", sig: "3♯", detail: "F♯, C♯, G♯" },
            { maj: "E", min: "C♯m", sig: "4♯", detail: "F♯, C♯, G♯, D♯" },
            { maj: "B", min: "G♯m", sig: "5♯", detail: "F♯, C♯, G♯, D♯, A♯" },
            { maj: "F♯", min: "D♯m", sig: "6♯", detail: "F♯, C♯, G♯, D♯, A♯, E♯" }, // Enharmonic Gb
            { maj: "D♭", min: "B♭m", sig: "5♭", detail: "B♭, E♭, A♭, D♭, G♭" },
            { maj: "A♭", min: "Fm", sig: "4♭", detail: "B♭, E♭, A♭, D♭" },
            { maj: "E♭", min: "Cm", sig: "3♭", detail: "B♭, E♭, A♭" },
            { maj: "B♭", min: "Gm", sig: "2♭", detail: "B♭, E♭" },
            { maj: "F", min: "Dm", sig: "1♭", detail: "B♭" }
        ];

        const container = document.getElementById('circle-container');
        const dispMaj = document.getElementById('display-major');
        const dispMin = document.getElementById('display-minor');
        const dispSig = document.getElementById('display-sig');

        // Radii for the circles (pixels from center)
        const radiusMaj = 145; // Increased slightly for clarity
        const radiusMin = 100;

        function initCircle() {
            circleData.forEach((data, index) => {
                // Calculate Angle
                // -90deg is 12 o'clock. Each step is 30deg (360 / 12)
                const angleDeg = (index * 30) - 90;
                const angleRad = angleDeg * (Math.PI / 180);

                // Create Major Node
                createNode(data.maj, index, radiusMaj, angleRad, 'maj');

                // Create Minor Node
                createNode(data.min, index, radiusMin, angleRad, 'min');
            });
            
            // Select C by default
            selectKey(0);
        }

        function createNode(text, index, radius, angleRad, type) {
            const node = document.createElement('div');
            
            // Calculate X/Y offsets from center
            const x = radius * Math.cos(angleRad);
            const y = radius * Math.sin(angleRad);

            node.textContent = text;
            node.className = `key-node ${type === 'min' ? 'minor-node' : ''}`;
            
            // FIXED LOGIC: Use Left/Top for position using Calc
            // 50% is center, + X px moves it to the spot
            node.style.left = `calc(50% + ${x}px)`;
            node.style.top = `calc(50% + ${y}px)`;
            
            // Store Index for click handling
            node.dataset.index = index;

            // Click Event
            node.addEventListener('click', () => selectKey(index));
            
            // Add custom ID for highlighting logic
            node.id = `${type}-${index}`;

            container.appendChild(node);
        }

        function selectKey(index) {
            const data = circleData[index];

            // Update Center Display
            dispMaj.textContent = data.maj;
            dispMin.textContent = `Rel: ${data.min}`;
            dispSig.textContent = data.sig === "0" ? "No Sharps/Flats" : `${data.sig} (${data.detail})`;

            // Update Active Classes
            document.querySelectorAll('.key-node').forEach(el => el.classList.remove('active'));
            
            document.getElementById(`maj-${index}`).classList.add('active');
            document.getElementById(`min-${index}`).classList.add('active');
        }

        // Run
        initCircle();

    </script>
</body>
</html>