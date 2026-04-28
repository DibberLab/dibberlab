<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Ticker | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Neon Glows */
        .card-glow {
            transition: all 0.3s ease;
        }
        .card-glow:hover {
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            transform: translateY(-2px);
            border-color: #4b5563;
        }

        /* Price Flash Animation */
        .flash-green { animation: flashGreen 1s; }
        .flash-red { animation: flashRed 1s; }

        @keyframes flashGreen {
            0% { color: #10b981; text-shadow: 0 0 10px #10b981; }
            100% { color: white; text-shadow: none; }
        }
        @keyframes flashRed {
            0% { color: #ef4444; text-shadow: 0 0 10px #ef4444; }
            100% { color: white; text-shadow: none; }
        }

        /* Sparkline SVG */
        .sparkline {
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Loader */
        .loader-bar {
            height: 2px;
            width: 100%;
            background: #1f2937;
            overflow: hidden;
            position: relative;
        }
        .loader-progress {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            background: #3b82f6;
            width: 0%;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center py-8">
        <div class="w-full max-w-6xl">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                        Market Watch
                    </h1>
                    <p class="text-gray-400 text-sm">Live feed via CoinGecko.</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-xs text-right font-mono text-gray-500">
                        <div>NEXT UPDATE</div>
                        <div id="timer-val" class="text-blue-400">00:00</div>
                    </div>
                    <button onclick="fetchData()" class="bg-gray-800 hover:bg-gray-700 p-2 rounded-lg transition-colors border border-gray-700">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>

            <div class="loader-bar mb-8 rounded-full">
                <div id="loader" class="loader-progress"></div>
            </div>

            <div id="ticker-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                </div>

            <div class="mt-8 text-center text-xs text-gray-600">
                Note: "PSRHF" (OTC Stock) replaced with Solana for API compatibility. Prices delayed by API latency.
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-600 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // CONFIG
        // Using CoinGecko IDs. 
        // Note: PSRHF is a stock (Purpose Bitcoin ETF). Free crypto APIs don't track pink sheets.
        // Swapped PSRHF for 'solana' to keep the tool functional.
        const COINS = ['bitcoin', 'ethereum', 'ripple', 'solana'];
        const REFRESH_SECONDS = 60;
        
        // DOM
        const grid = document.getElementById('ticker-grid');
        const loader = document.getElementById('loader');
        const timerVal = document.getElementById('timer-val');

        let secondsLeft = REFRESH_SECONDS;
        let timerInterval;

        // --- CORE LOGIC ---

        async function fetchData() {
            startLoader();
            
            try {
                // Request: USD prices, 24h change, and 7-day sparkline data
                const ids = COINS.join(',');
                const url = `https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=${ids}&sparkline=true`;
                
                const response = await fetch(url);
                if (!response.ok) throw new Error("API Limit Reached");
                
                const data = await response.json();
                
                renderCards(data);
                resetTimer();

            } catch (err) {
                console.error(err);
                grid.innerHTML = `<div class="col-span-4 text-center text-red-400 p-8 border border-red-900 bg-red-900/10 rounded-xl">API Rate Limit Exceeded. Please wait a moment.</div>`;
            }
        }

        function renderCards(data) {
            grid.innerHTML = ''; // Clear current

            // Sort by our config order (API returns in market cap order usually)
            const sortedData = COINS.map(id => data.find(item => item.id === id)).filter(item => item);

            sortedData.forEach(coin => {
                const price = formatCurrency(coin.current_price);
                const change = coin.price_change_percentage_24h;
                const isUp = change >= 0;
                const colorClass = isUp ? 'text-emerald-400' : 'text-rose-400';
                const arrow = isUp ? '▲' : '▼';
                
                // Draw Sparkline
                const svgPath = generateSparklinePath(coin.sparkline_in_7d.price);

                const card = document.createElement('div');
                card.className = "card-glow bg-gray-900 border border-gray-800 rounded-2xl p-6 flex flex-col relative overflow-hidden";
                
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="flex items-center gap-3">
                            <img src="${coin.image}" class="w-8 h-8 rounded-full" alt="${coin.name}">
                            <div>
                                <h3 class="font-bold text-white leading-tight">${coin.name}</h3>
                                <span class="text-xs font-bold text-gray-500 uppercase">${coin.symbol}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-bold text-gray-500 uppercase">24H</div>
                            <div class="${colorClass} font-mono text-sm font-bold flex items-center justify-end gap-1">
                                ${arrow} ${Math.abs(change).toFixed(2)}%
                            </div>
                        </div>
                    </div>

                    <div class="text-3xl font-bold text-white mono-font mb-4 relative z-10 tracking-tight">
                        ${price}
                    </div>

                    <div class="h-16 w-full mt-auto relative opacity-80">
                         <svg class="w-full h-full overflow-visible" preserveAspectRatio="none">
                            <path d="${svgPath}" class="sparkline ${isUp ? 'stroke-emerald-500' : 'stroke-rose-500'}" fill="none" />
                            <path d="${svgPath} V 100 L 0 100 Z" class="${isUp ? 'fill-emerald-500/10' : 'fill-rose-500/10'}" stroke="none" />
                        </svg>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        // --- SPARKLINE GENERATOR ---
        // Converts array of prices into SVG Path commands
        function generateSparklinePath(prices) {
            if (!prices || prices.length === 0) return "";

            const min = Math.min(...prices);
            const max = Math.max(...prices);
            const range = max - min;
            
            // SVG coordinate space: 0,0 is top-left.
            // We want 100x100 box logic usually, or normalize to percent.
            // Let's assume viewbox is handled by CSS sizing, so we normalize to 0-100 coordinates.
            
            const points = prices.map((price, index) => {
                const x = (index / (prices.length - 1)) * 100; // X percent
                // Normalize Y. Higher price = Lower Y value in SVG.
                const y = 100 - ((price - min) / range) * 80 - 10; // keep 10px padding
                return `${x},${y}`;
            });

            return `M ${points.join(' L ')}`;
        }

        // --- UTILS ---

        function formatCurrency(num) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }

        function startLoader() {
            loader.style.width = '0%';
            loader.style.transition = 'none';
            setTimeout(() => {
                loader.style.transition = 'width 1s ease-in-out';
                loader.style.width = '100%';
            }, 50);
        }

        function resetTimer() {
            clearInterval(timerInterval);
            secondsLeft = REFRESH_SECONDS;
            updateTimerDisplay();
            
            timerInterval = setInterval(() => {
                secondsLeft--;
                updateTimerDisplay();
                if (secondsLeft <= 0) {
                    fetchData();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const m = Math.floor(secondsLeft / 60);
            const s = secondsLeft % 60;
            timerVal.textContent = `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }

        // Init
        fetchData();

    </script>
</body>
</html>