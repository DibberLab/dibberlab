<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Phase | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }
        .serif-font { font-family: 'Cinzel', serif; }

        /* Starry Background */
        .stars-bg {
            background-image: 
                radial-gradient(1px 1px at 20px 30px, #eee, rgba(0,0,0,0)),
                radial-gradient(1px 1px at 40px 70px, #fff, rgba(0,0,0,0)),
                radial-gradient(1px 1px at 50px 160px, #ddd, rgba(0,0,0,0)),
                radial-gradient(1px 1px at 90px 40px, #fff, rgba(0,0,0,0));
            background-size: 200px 200px;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            opacity: 0.5;
            animation: twinkle 100s linear infinite;
        }

        @keyframes twinkle {
            from { background-position: 0 0; }
            to { background-position: -200px -200px; }
        }

        /* --- THE MOON VISUALIZER --- */
        .moon-container {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            position: relative;
            background-color: #1a1a1a; /* Dark part of moon */
            box-shadow: 0 0 50px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .moon-texture {
            position: absolute;
            inset: 0;
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png'); /* Subtle noise */
            opacity: 0.3;
            z-index: 10;
            border-radius: 50%;
        }

        /* The lit part */
        .hemisphere {
            width: 100px; /* Half width */
            height: 200px;
            position: absolute;
            top: 0;
            background-color: #e5e7eb; /* Moon White */
            z-index: 2;
        }
        
        .light-hemisphere { right: 0; }
        .dark-hemisphere { left: 0; background-color: #1a1a1a; }

        /* The shadow oval that moves to create the phase */
        .divider {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            transform-origin: center;
            transition: transform 1s ease-out, background-color 1s;
        }

        /* Glow Effect */
        .moon-glow {
            box-shadow: 0 0 40px 10px rgba(229, 231, 235, 0.2);
        }

        /* Date Picker Styling */
        input[type="date"] { color-scheme: dark; }

    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col relative overflow-hidden">

    <div class="stars-bg"></div>

    <main class="flex-grow px-4 flex items-center justify-center py-8 z-10">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <div class="flex flex-col items-center justify-center">
                
                <div class="moon-container moon-glow mb-8" id="moon">
                    <div class="moon-texture"></div>
                    <div class="hemisphere light-hemisphere" id="right-hemi"></div>
                    <div class="hemisphere dark-hemisphere" id="left-hemi"></div>
                    <div class="divider" id="moon-divider"></div>
                </div>

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white serif-font tracking-widest uppercase mb-1" id="phase-name">Full Moon</h2>
                    <div class="text-emerald-400 font-mono text-sm" id="illumination">100% Illuminated</div>
                </div>

            </div>

            <div class="flex flex-col gap-6">
                
                <div>
                    <h1 class="text-4xl font-bold text-gray-200 serif-font mb-2">Lunar Cycle</h1>
                    <p class="text-gray-400 text-sm">Track the phases of the moon.</p>
                </div>

                <div class="bg-gray-900/80 p-6 rounded-2xl border border-gray-800 backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-bold text-gray-500 uppercase">Selected Date</label>
                        <button onclick="setToday()" class="text-xs text-amber-500 hover:text-amber-400 font-bold uppercase tracking-wider">Today</button>
                    </div>
                    <input type="date" id="date-input" class="w-full bg-gray-950 border border-gray-700 rounded-xl p-3 text-white font-bold focus:border-gray-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-900/60 p-4 rounded-xl border border-gray-800">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Moon Age</span>
                        <div class="text-2xl font-bold text-white mt-1"><span id="moon-age">14.5</span> <span class="text-sm text-gray-500">days</span></div>
                    </div>
                    <div class="bg-gray-900/60 p-4 rounded-xl border border-gray-800">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Next Full Moon</span>
                        <div class="text-lg font-bold text-white mt-1" id="next-full">...</div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-[10px] text-gray-500 uppercase font-bold mb-2">
                        <span>New</span>
                        <span>Full</span>
                        <span>New</span>
                    </div>
                    <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden relative">
                        <div id="cycle-progress" class="absolute top-0 left-0 h-full bg-gradient-to-r from-gray-700 via-white to-gray-700 w-full" style="transform: translateX(-50%)"></div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm z-10">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const dateInput = document.getElementById('date-input');
        const phaseName = document.getElementById('phase-name');
        const illumination = document.getElementById('illumination');
        const moonAgeEl = document.getElementById('moon-age');
        const nextFullEl = document.getElementById('next-full');
        const cycleProgress = document.getElementById('cycle-progress');
        
        // Moon Visual Elements
        const rightHemi = document.getElementById('right-hemi');
        const leftHemi = document.getElementById('left-hemi');
        const divider = document.getElementById('moon-divider');

        // Config
        const SYNODIC_MONTH = 29.53058867; // Average length of lunar cycle

        // --- CORE CALCULATION ---

        function calculateMoon(date) {
            // Calculate age of moon (days since last New Moon)
            // Reference New Moon: January 6, 2000 at 18:14 UTC (Julian: 2451550.1)
            // Or simpler modern reference: Jan 11 2024 was a New Moon.
            
            const knownNewMoon = new Date('2024-01-11T11:57:00Z');
            const diffTime = date.getTime() - knownNewMoon.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            
            let age = diffDays % SYNODIC_MONTH;
            if (age < 0) age += SYNODIC_MONTH;

            renderUI(age);
        }

        function renderUI(age) {
            moonAgeEl.textContent = age.toFixed(1);

            // 1. Determine Phase Name
            // 0 = New, 7.4 = First Quarter, 14.8 = Full, 22.1 = Last Quarter
            let phase = "";
            const segment = SYNODIC_MONTH / 8; // approx 3.69 days per phase segment
            
            // Adjust ranges slightly for human readability of "Full" vs "Gibbous"
            if (age < 1 || age > SYNODIC_MONTH - 1) phase = "New Moon";
            else if (age < 7) phase = "Waxing Crescent";
            else if (age < 8) phase = "First Quarter";
            else if (age < 14) phase = "Waxing Gibbous";
            else if (age < 15.5) phase = "Full Moon";
            else if (age < 22) phase = "Waning Gibbous";
            else if (age < 23) phase = "Last Quarter";
            else phase = "Waning Crescent";

            phaseName.textContent = phase;

            // 2. Calculate Illumination %
            // 0% at age 0, 100% at age 14.8, 0% at age 29.5
            // Using cosine approximation
            const phaseAngle = (age / SYNODIC_MONTH) * 2 * Math.PI; // 0 to 2PI
            const percentLit = (1 - Math.cos(phaseAngle)) / 2 * 100;
            
            illumination.textContent = `${Math.round(percentLit)}% Illuminated`;

            // 3. Update Visuals (CSS Tricks)
            updateMoonVisual(age);

            // 4. Progress Bar
            // We want the bar to represent 0 -> 29.5
            // But visuals: New (Dark) -> Full (Light) -> New (Dark)
            // We shift the gradient based on age
            const pct = (age / SYNODIC_MONTH) * 100;
            // Not translating bar, but maybe just using age
            // Let's make the bar specific: 0% is left, 100% is right
            // Gradient is fixed: Dark -> Light -> Dark
            // We show a marker instead? Or just let the gradient be the cycle?
            // Actually, let's slide a marker.
            cycleProgress.style.transform = `translateX(${pct - 50}%)`; 
            // Note: I reused a div for a gradient background previously, simple sliding works better as a marker
            // Let's refine the bar logic:
            cycleProgress.className = "absolute top-0 h-full w-1 bg-amber-500 shadow-[0_0_10px_#f59e0b]";
            cycleProgress.style.left = `${pct}%`;
            cycleProgress.style.transform = `translateX(-50%)`;

            // 5. Next Full Moon calculation
            const daysUntilFull = 14.765 - age;
            let nextFullDate = new Date(dateInput.value);
            
            if (daysUntilFull > 0) {
                nextFullDate.setDate(nextFullDate.getDate() + daysUntilFull);
            } else {
                nextFullDate.setDate(nextFullDate.getDate() + (daysUntilFull + SYNODIC_MONTH));
            }
            
            const options = { month: 'short', day: 'numeric' };
            nextFullEl.textContent = nextFullDate.toLocaleDateString('en-US', options);
        }

        function updateMoonVisual(age) {
            // Colors
            const colorDark = '#1a1a1a';
            const colorLight = '#e5e7eb';

            // Reset transform
            divider.style.transform = "rotateY(0deg)";

            // Logic derived from standard Moon CSS implementations
            // New Moon (0) -> First Quarter (7.4)
            if (age <= 7.4) {
                rightHemi.style.backgroundColor = colorDark;
                leftHemi.style.backgroundColor = colorDark;
                divider.style.backgroundColor = colorLight;
                // Rotate divider from 90deg (hidden behind) to 0 (half) ?
                // Actually easier logic:
                // Waxing Crescent: Right is Dark. Left is Dark. Divider (Light) rotates to reveal light on right.
                // Wait, simplified:
                
                // Let's use simple overlap logic:
                // 0 - 14.7 (Waxing)
                if (age <= 14.76) {
                    // Right side is getting lighter
                    leftHemi.style.backgroundColor = colorDark; // Left stays dark initially
                    
                    if (age <= 7.38) {
                        // 0 - 50% lit (Crescent)
                        rightHemi.style.backgroundColor = colorDark;
                        divider.style.backgroundColor = colorLight;
                        const deg = 90 - ((age / 7.38) * 90);
                        divider.style.transform = `rotateY(${deg}deg)`;
                        // This rotation logic is tricky in 3D without deeper css
                        // Alternative 2D standard:
                        // Move divider? No.
                        // Let's stick to: Divider is the curve.
                    } else {
                        // 50% - 100% lit (Gibbous)
                        rightHemi.style.backgroundColor = colorLight;
                        divider.style.backgroundColor = colorDark;
                        const deg = ((age - 7.38) / 7.38) * 90;
                        divider.style.transform = `rotateY(${deg}deg)`;
                    }
                } 
                // 14.7 - 29.5 (Waning)
                else {
                    rightHemi.style.backgroundColor = colorLight; // Right stays light? No right goes dark.
                    
                    if (age <= 22.15) {
                        // 100% - 50% lit (Gibbous)
                        leftHemi.style.backgroundColor = colorLight;
                        divider.style.backgroundColor = colorDark;
                        const deg = 90 - (((age - 14.76) / 7.38) * 90);
                        divider.style.transform = `rotateY(${deg}deg)`; // Cover left side with dark
                    } else {
                        // 50% - 0% lit (Crescent)
                        leftHemi.style.backgroundColor = colorDark;
                        divider.style.backgroundColor = colorLight;
                        const deg = (((age - 22.15) / 7.38) * 90);
                        divider.style.transform = `rotateY(${deg}deg)`;
                    }
                }
            }
        }

        // --- LISTENERS ---

        function setToday() {
            const today = new Date();
            // Format YYYY-MM-DD local
            const offset = today.getTimezoneOffset();
            const localDate = new Date(today.getTime() - (offset*60*1000));
            dateInput.value = localDate.toISOString().split('T')[0];
            calculateMoon(today);
        }

        dateInput.addEventListener('change', () => {
            if(dateInput.value) {
                // Parse as local time to avoid UTC shifts
                const [y,m,d] = dateInput.value.split('-').map(Number);
                const date = new Date(y, m-1, d, 12, 0, 0); // Noon local
                calculateMoon(date);
            }
        });

        // Init
        setToday();

    </script>
</body>
</html>