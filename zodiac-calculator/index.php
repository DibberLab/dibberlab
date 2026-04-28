<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zodiac Calculator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .serif-font { font-family: 'Cinzel', serif; }

        /* Animated Space Background */
        .space-bg {
            background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%);
            overflow: hidden;
            position: relative;
        }
        
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite linear;
            opacity: var(--opacity);
        }

        @keyframes twinkle {
            0% { opacity: 0; transform: translateY(0); }
            50% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-20px); }
        }

        /* Glassmorphism Card */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        /* Sign Symbol */
        .zodiac-symbol {
            font-size: 6rem;
            line-height: 1;
            background: -webkit-linear-gradient(#fcd34d, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.3));
        }

        /* Animations */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="space-bg text-gray-100 min-h-screen flex flex-col">

    <div id="stars-container" class="absolute inset-0 pointer-events-none"></div>

    <main class="flex-grow px-4 flex items-center justify-center py-8 z-10">
        <div class="w-full max-w-lg mx-auto">
            
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-amber-400 serif-font mb-2">Cosmic Alignment</h1>
                <p class="text-gray-400 text-sm">Discover your astrological identity.</p>
            </div>

            <div class="glass-panel rounded-2xl p-8 mb-8">
                <label class="text-xs font-bold text-amber-500/80 uppercase tracking-widest block mb-3">Date of Birth</label>
                
                <div class="flex gap-4">
                    <input type="date" id="dob-input" class="w-full bg-black/30 border border-gray-600 rounded-xl p-4 text-white text-lg font-bold focus:border-amber-500 outline-none transition-colors" style="color-scheme: dark;">
                </div>

                <button onclick="calculateZodiac()" class="w-full mt-6 py-4 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-black font-bold text-lg rounded-xl shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 serif-font">
                    Reveal My Sign
                </button>
            </div>

            <div id="result-card" class="hidden glass-panel rounded-2xl p-8 text-center fade-in-up relative overflow-hidden">
                
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-amber-500/20 blur-3xl rounded-full pointer-events-none"></div>

                <div class="relative z-10">
                    <div id="res-symbol" class="zodiac-symbol mb-4"></div>
                    <h2 id="res-name" class="text-4xl font-bold text-white serif-font mb-1">Leo</h2>
                    <p id="res-dates" class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-6">July 23 - Aug 22</p>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-black/30 rounded-lg p-3 border border-white/5">
                            <span class="text-[10px] text-gray-500 uppercase font-bold">Element</span>
                            <div id="res-element" class="text-lg font-bold text-amber-400">Fire</div>
                        </div>
                        <div class="bg-black/30 rounded-lg p-3 border border-white/5">
                            <span class="text-[10px] text-gray-500 uppercase font-bold">Quality</span>
                            <div id="res-quality" class="text-lg font-bold text-emerald-400">Fixed</div>
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <p id="res-desc" class="text-gray-300 italic text-sm leading-relaxed">
                            "Passionate, generous, warm-hearted, cheerful, humorous."
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-600 text-sm relative z-10">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // --- DATA ---
        // Format: Name, Symbol, Element, Quality, StartMonth, StartDay, EndMonth, EndDay
        const SIGNS = [
            { name: "Capricorn", symbol: "♑", element: "Earth", quality: "Cardinal", desc: "Disciplined, responsible, self-control, good managers.", sM: 12, sD: 22, eM: 1, eD: 19 },
            { name: "Aquarius", symbol: "♒", element: "Air", quality: "Fixed", desc: "Progressive, original, independent, humanitarian.", sM: 1, sD: 20, eM: 2, eD: 18 },
            { name: "Pisces", symbol: "♓", element: "Water", quality: "Mutable", desc: "Compassionate, artistic, intuitive, gentle, wise.", sM: 2, sD: 19, eM: 3, eD: 20 },
            { name: "Aries", symbol: "♈", element: "Fire", quality: "Cardinal", desc: "Courageous, determined, confident, enthusiastic.", sM: 3, sD: 21, eM: 4, eD: 19 },
            { name: "Taurus", symbol: "♉", element: "Earth", quality: "Fixed", desc: "Reliable, patient, practical, devoted, responsible.", sM: 4, sD: 20, eM: 5, eD: 20 },
            { name: "Gemini", symbol: "♊", element: "Air", quality: "Mutable", desc: "Gentle, affectionate, curious, adaptable.", sM: 5, sD: 21, eM: 6, eD: 20 },
            { name: "Cancer", symbol: "♋", element: "Water", quality: "Cardinal", desc: "Tenacious, highly imaginative, loyal, emotional.", sM: 6, sD: 21, eM: 7, eD: 22 },
            { name: "Leo", symbol: "♌", element: "Fire", quality: "Fixed", desc: "Creative, passionate, generous, warm-hearted, cheerful.", sM: 7, sD: 23, eM: 8, eD: 22 },
            { name: "Virgo", symbol: "♍", element: "Earth", quality: "Mutable", desc: "Loyal, analytical, kind, hardworking, practical.", sM: 8, sD: 23, eM: 9, eD: 22 },
            { name: "Libra", symbol: "♎", element: "Air", quality: "Cardinal", desc: "Cooperative, diplomatic, gracious, fair-minded.", sM: 9, sD: 23, eM: 10, eD: 22 },
            { name: "Scorpio", symbol: "♏", element: "Water", quality: "Fixed", desc: "Resourceful, brave, passionate, stubborn.", sM: 10, sD: 23, eM: 11, eD: 21 },
            { name: "Sagittarius", symbol: "♐", element: "Fire", quality: "Mutable", desc: "Generous, idealistic, great sense of humor.", sM: 11, sD: 22, eM: 12, eD: 21 }
        ];

        const MONTH_NAMES = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // --- STARS BG ---
        function initStars() {
            const container = document.getElementById('stars-container');
            for(let i=0; i<50; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                const size = Math.random() * 2 + 1;
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
                star.style.setProperty('--opacity', Math.random());
                container.appendChild(star);
            }
        }

        // --- LOGIC ---

        function calculateZodiac() {
            const input = document.getElementById('dob-input').value;
            const resultCard = document.getElementById('result-card');
            
            if (!input) {
                alert("Please select your birth date.");
                return;
            }

            // String Parsing (avoids timezone issues)
            // Input format is always YYYY-MM-DD
            const parts = input.split('-');
            const month = parseInt(parts[1]);
            const day = parseInt(parts[2]);

            // Logic
            let foundSign = null;

            for (let s of SIGNS) {
                // Check if date falls in standard range
                if (
                    (month === s.sM && day >= s.sD) || 
                    (month === s.eM && day <= s.eD)
                ) {
                    foundSign = s;
                    break;
                }
                // Special check for Capricorn wrapping year
                if (s.name === "Capricorn") {
                    if ((month === 12 && day >= 22) || (month === 1 && day <= 19)) {
                        foundSign = s;
                        break;
                    }
                }
            }

            if (foundSign) renderResult(foundSign);
        }

        function renderResult(sign) {
            const resultCard = document.getElementById('result-card');
            
            // Re-trigger animation
            resultCard.classList.remove('hidden');
            resultCard.classList.remove('fade-in-up');
            void resultCard.offsetWidth; // Force reflow
            resultCard.classList.add('fade-in-up');

            // Inject Data
            document.getElementById('res-symbol').textContent = sign.symbol;
            document.getElementById('res-name').textContent = sign.name;
            document.getElementById('res-element').textContent = sign.element;
            document.getElementById('res-quality').textContent = sign.quality;
            document.getElementById('res-desc').textContent = `"${sign.desc}"`;
            
            // Format Date String
            const startM = MONTH_NAMES[sign.sM - 1];
            const endM = MONTH_NAMES[sign.eM - 1];
            document.getElementById('res-dates').textContent = `${startM} ${sign.sD} - ${endM} ${sign.eD}`;

            // Colorize Element
            const elDiv = document.getElementById('res-element');
            elDiv.className = "text-lg font-bold";
            if(sign.element === 'Fire') elDiv.classList.add('text-red-500');
            else if(sign.element === 'Water') elDiv.classList.add('text-blue-400');
            else if(sign.element === 'Earth') elDiv.classList.add('text-emerald-400');
            else if(sign.element === 'Air') elDiv.classList.add('text-gray-300');
        }

        // Init
        initStars();

    </script>
</body>
</html>