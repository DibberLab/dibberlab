<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Lookup | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Map Container */
        #map {
            height: 300px;
            width: 100%;
            border-radius: 1rem;
            z-index: 1; /* Keep below modal/header */
        }
        
        /* Custom Leaflet Dark Mode Tweaks (Inverting tiles) */
        .leaflet-layer,
        .leaflet-control-zoom-in,
        .leaflet-control-zoom-out,
        .leaflet-control-attribution {
            filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
        }

        /* Pulse Animation for Loader */
        .pulse-ring {
            display: inline-block;
            width: 80px;
            height: 80px;
        }
        .pulse-ring:after {
            content: " ";
            display: block;
            width: 64px;
            height: 64px;
            margin: 8px;
            border-radius: 50%;
            border: 6px solid #f59e0b;
            border-color: #f59e0b transparent #f59e0b transparent;
            animation: ring 1.2s linear infinite;
        }
        @keyframes ring {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Data Card Hover */
        .data-card {
            transition: transform 0.2s, border-color 0.2s;
        }
        .data-card:hover {
            transform: translateY(-2px);
            border-color: #4b5563; /* Gray-600 */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-5xl mx-auto">
            
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold text-amber-400">IP Intelligence</h1>
                    <p class="text-gray-400 text-sm">Public network details.</p>
                </div>
                <button onclick="fetchData()" class="text-xs font-bold text-emerald-400 hover:text-white underline flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Data
                </button>
            </div>

            <div id="loader" class="flex flex-col items-center justify-center py-20">
                <div class="pulse-ring"></div>
                <p class="mt-4 text-gray-500 font-mono text-sm">Scanning Network...</p>
            </div>

            <div id="error-box" class="hidden bg-red-900/30 border border-red-800 rounded-xl p-8 text-center">
                <div class="text-4xl mb-2">📡</div>
                <h2 class="text-xl font-bold text-red-400">Connection Failed</h2>
                <p class="text-gray-400 text-sm mt-2 max-w-md mx-auto">Could not fetch IP data. This is often caused by AdBlockers or strict privacy settings blocking the geolocation API.</p>
                <button onclick="fetchData()" class="mt-4 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-bold border border-gray-600">Try Again</button>
            </div>

            <div id="content" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-8 animate-fade-in">
                
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your Public IP</label>
                        <div class="flex items-center gap-3 mt-2">
                            <h2 id="ip-address" class="text-4xl lg:text-5xl font-black text-white mono-font tracking-tighter">...</h2>
                            <button onclick="copyIP()" class="p-2 text-gray-400 hover:text-white transition-colors" title="Copy">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            </button>
                        </div>
                        <div id="ipv-type" class="inline-block mt-3 px-2 py-1 bg-gray-900 rounded text-[10px] font-bold text-emerald-400 uppercase border border-gray-600">IPv4</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="data-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Provider (ISP)</label>
                            <div id="isp-name" class="text-sm font-bold text-white mt-1">...</div>
                        </div>
                        <div class="data-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">ASN</label>
                            <div id="asn-val" class="text-sm font-bold text-amber-400 mono-font mt-1">...</div>
                        </div>
                        <div class="data-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Location</label>
                            <div id="loc-city" class="text-sm font-bold text-white mt-1">...</div>
                        </div>
                        <div class="data-card bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Timezone</label>
                            <div id="timezone" class="text-sm font-bold text-blue-400 mt-1">...</div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-800">
                        <button onclick="toggleJson()" class="text-xs font-bold text-gray-500 hover:text-gray-300 flex items-center gap-1">
                            <span>{ } Show Raw JSON</span>
                        </button>
                        <pre id="json-box" class="hidden mt-4 bg-black p-4 rounded-lg text-xs text-green-500 font-mono overflow-x-auto border border-gray-700"></pre>
                    </div>

                </div>

                <div class="lg:col-span-7">
                    <div class="bg-gray-800 p-1 rounded-2xl border border-gray-700 shadow-lg relative">
                        <div id="map"></div>
                        
                        <div class="absolute bottom-4 left-4 z-10 bg-black/70 backdrop-blur px-3 py-1 rounded-lg border border-white/10">
                            <span class="text-[10px] font-mono text-gray-300">LAT: <span id="lat-val" class="text-white font-bold">0.00</span></span>
                            <span class="text-[10px] font-mono text-gray-300 ml-3">LON: <span id="lon-val" class="text-white font-bold">0.00</span></span>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-xs text-gray-500 text-center">
                        Note: Geolocation is based on ISP registration and may not reflect your exact street address.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // DOM Elements
        const loader = document.getElementById('loader');
        const content = document.getElementById('content');
        const errorBox = document.getElementById('error-box');
        
        const ipDisplay = document.getElementById('ip-address');
        const ipvType = document.getElementById('ipv-type');
        const ispName = document.getElementById('isp-name');
        const asnVal = document.getElementById('asn-val');
        const locCity = document.getElementById('loc-city');
        const timezone = document.getElementById('timezone');
        const latVal = document.getElementById('lat-val');
        const lonVal = document.getElementById('lon-val');
        const jsonBox = document.getElementById('json-box');

        let map = null;

        // --- FETCH LOGIC ---

        async function fetchData() {
            // Reset UI
            loader.classList.remove('hidden');
            content.classList.add('hidden');
            errorBox.classList.add('hidden');

            try {
                // Fetch from ipapi.co (Free tier, good for client-side demos)
                const response = await fetch('https://ipapi.co/json/');
                
                if (!response.ok) throw new Error("API Limit or Error");
                
                const data = await response.json();
                
                // Add slight delay for smooth UI transition
                setTimeout(() => {
                    renderData(data);
                }, 800);

            } catch (error) {
                console.error(error);
                loader.classList.add('hidden');
                errorBox.classList.remove('hidden');
            }
        }

        function renderData(data) {
            loader.classList.add('hidden');
            content.classList.remove('hidden');

            // Populate Text
            ipDisplay.textContent = data.ip || "Unknown";
            ipvType.textContent = data.version || "IPv4";
            ispName.textContent = data.org || "Unknown ISP";
            asnVal.textContent = data.asn || "---";
            locCity.textContent = `${data.city}, ${data.region}, ${data.country_name}`;
            timezone.textContent = `${data.timezone} (${data.utc_offset})`;
            
            latVal.textContent = data.latitude;
            lonVal.textContent = data.longitude;

            // Raw JSON
            jsonBox.textContent = JSON.stringify(data, null, 2);

            // Initialize Map
            initMap(data.latitude, data.longitude);
        }

        // --- MAP LOGIC ---

        function initMap(lat, lng) {
            if (map) {
                map.remove(); // Reset map if refreshing
            }

            // Create Map
            map = L.map('map', {
                center: [lat, lng],
                zoom: 13,
                zoomControl: false, // Reposition or hide default
                attributionControl: true
            });

            // Use OpenStreetMap Tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add Marker
            const markerIcon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            L.marker([lat, lng], {icon: markerIcon}).addTo(map)
                .bindPopup("<b>Network Location</b><br>Approximate ISP Center").openPopup();
                
            // Fix Leaflet sizing bug inside hidden containers
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }

        // --- UTILS ---

        function copyIP() {
            navigator.clipboard.writeText(ipDisplay.textContent);
            alert("IP Copied to clipboard!");
        }

        function toggleJson() {
            jsonBox.classList.toggle('hidden');
        }

        // Init on load
        fetchData();

    </script>
</body>
</html>