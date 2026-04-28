<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Viewer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Drop Zone */
        .drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }
        .drop-zone:hover, .drop-zone.dragover {
            background-color: rgba(31, 41, 55, 0.5);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%233B82F6FF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transform: scale(0.99);
        }

        /* Metadata Tags */
        .meta-tag {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeIn { to { opacity: 1; } }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center py-8">
        <div class="w-full max-w-6xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-blue-400">EXIF Viewer</h1>
                <p class="text-center text-gray-400 text-sm">View hidden photo metadata locally.</p>
            </div>

            <div id="upload-area" class="w-full max-w-2xl mx-auto mb-10">
                <label class="drop-zone w-full h-64 flex flex-col items-center justify-center cursor-pointer rounded-3xl group relative overflow-hidden">
                    
                    <div class="z-10 flex flex-col items-center pointer-events-none">
                        <svg class="w-16 h-16 text-gray-500 group-hover:text-blue-400 mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-xl font-bold text-gray-300 group-hover:text-white transition-colors">Drop image here</p>
                        <p class="text-sm text-gray-500 mt-2">or click to browse (JPG/JPEG)</p>
                    </div>

                    <input type="file" id="file-input" class="hidden" accept="image/jpeg, image/jpg" onchange="handleFile(this.files[0])">
                </label>
            </div>

            <div id="results-area" class="hidden grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 p-2 rounded-2xl border border-gray-700 shadow-xl sticky top-8">
                        <img id="preview-img" class="w-full rounded-xl object-contain bg-black/50" src="" alt="Preview">
                        
                        <div class="mt-4 px-2 pb-2">
                            <h3 id="file-name" class="font-bold text-white truncate">filename.jpg</h3>
                            <p id="file-size" class="text-xs text-gray-400">0 MB</p>
                        </div>

                        <button onclick="reset()" class="w-full py-3 mt-2 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition-colors text-sm">
                            Analyze Another
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Camera</span>
                            <div id="stat-model" class="font-bold text-white text-sm truncate mt-1">--</div>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Aperture</span>
                            <div id="stat-aperture" class="font-bold text-amber-400 text-sm mt-1">--</div>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">ISO</span>
                            <div id="stat-iso" class="font-bold text-blue-400 text-sm mt-1">--</div>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Shutter</span>
                            <div id="stat-shutter" class="font-bold text-emerald-400 text-sm mt-1">--</div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                        <div class="p-4 border-b border-gray-700 bg-gray-900/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-300">Detailed Metadata</h3>
                            <button onclick="copyData()" class="text-xs font-bold text-blue-400 hover:text-white uppercase">Copy JSON</button>
                        </div>
                        <div id="data-list" class="divide-y divide-gray-700 text-sm">
                            <div class="p-8 text-center text-gray-500 italic">No EXIF data found.</div>
                        </div>
                    </div>

                    <div id="gps-box" class="hidden bg-gray-800 rounded-2xl border border-gray-700 p-4">
                        <h3 class="font-bold text-gray-300 mb-2">GPS Coordinates</h3>
                         
                        <div class="grid grid-cols-2 gap-4 text-sm font-mono text-gray-400">
                            <div>Lat: <span id="gps-lat" class="text-white"></span></div>
                            <div>Lon: <span id="gps-lon" class="text-white"></span></div>
                        </div>
                        <a id="maps-link" href="#" target="_blank" class="block mt-3 text-center py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-lg text-xs transition-colors">
                            Open in Google Maps
                        </a>
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
        const uploadArea = document.getElementById('upload-area');
        const resultsArea = document.getElementById('results-area');
        const dropZone = document.querySelector('.drop-zone');
        const previewImg = document.getElementById('preview-img');
        const dataList = document.getElementById('data-list');
        
        // Stat Elements
        const statModel = document.getElementById('stat-model');
        const statAperture = document.getElementById('stat-aperture');
        const statIso = document.getElementById('stat-iso');
        const statShutter = document.getElementById('stat-shutter');
        
        // File Info
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        // GPS
        const gpsBox = document.getElementById('gps-box');
        const gpsLat = document.getElementById('gps-lat');
        const gpsLon = document.getElementById('gps-lon');
        const mapsLink = document.getElementById('maps-link');

        let currentExifData = {};

        // --- DRAG & DROP ---

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFile(files[0]);
        });

        // --- CORE LOGIC ---

        function handleFile(file) {
            if (!file) return;
            
            // Validate Type
            if (!file.type.includes('jpeg') && !file.type.includes('jpg')) {
                alert("Please upload a JPG/JPEG image to view EXIF data.");
                return;
            }

            // UI Switch
            uploadArea.classList.add('hidden');
            resultsArea.classList.remove('hidden');
            resultsArea.classList.add('grid'); // restore grid layout

            // Basic File Info
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + " MB";

            // Preview Image
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Read EXIF
            EXIF.getData(file, function() {
                currentExifData = EXIF.getAllTags(this);
                renderData(currentExifData);
            });
        }

        function renderData(tags) {
            dataList.innerHTML = '';
            
            // 1. Quick Stats
            statModel.textContent = tags.Model || "Unknown";
            statAperture.textContent = tags.FNumber ? `f/${tags.FNumber}` : "--";
            statIso.textContent = tags.ISOSpeedRatings || "--";
            
            // Shutter calculation (often stored as 1/X)
            if (tags.ExposureTime) {
                // Determine if it's a decimal that should be a fraction
                if (tags.ExposureTime < 1) {
                    statShutter.textContent = `1/${Math.round(1/tags.ExposureTime)}s`;
                } else {
                    statShutter.textContent = `${tags.ExposureTime}s`;
                }
            } else {
                statShutter.textContent = "--";
            }

            // 2. Full List Table
            if (Object.keys(tags).length === 0) {
                dataList.innerHTML = '<div class="p-8 text-center text-gray-500 italic">No EXIF data found in this image.</div>';
                return;
            }

            // Filter out massive binary data (thumbnail) or undefined
            const keys = Object.keys(tags).filter(k => k !== 'thumbnail' && k !== 'MakerNote' && k !== 'UserComment');

            keys.forEach(key => {
                const val = tags[key];
                const row = document.createElement('div');
                row.className = "meta-tag grid grid-cols-3 gap-4 p-3 hover:bg-gray-700/50 transition-colors";
                row.innerHTML = `
                    <div class="col-span-1 text-gray-500 font-bold break-words">${key}</div>
                    <div class="col-span-2 text-gray-200 font-mono break-all">${formatVal(val)}</div>
                `;
                dataList.appendChild(row);
            });

            // 3. GPS Logic
            if (tags.GPSLatitude && tags.GPSLongitude) {
                const lat = convertDMSToDD(tags.GPSLatitude, tags.GPSLatitudeRef);
                const lon = convertDMSToDD(tags.GPSLongitude, tags.GPSLongitudeRef);
                
                gpsLat.textContent = lat.toFixed(6);
                gpsLon.textContent = lon.toFixed(6);
                
                mapsLink.href = `https://www.google.com/maps?q=${lat},${lon}`;
                gpsBox.classList.remove('hidden');
            } else {
                gpsBox.classList.add('hidden');
            }
        }

        // Helper: Convert array/object values to readable string
        function formatVal(val) {
            if (typeof val === 'object') {
                if (val instanceof Number) return val.valueOf();
                return JSON.stringify(val).substring(0, 50) + (JSON.stringify(val).length > 50 ? '...' : '');
            }
            return val;
        }

        // Helper: Convert GPS Degrees-Minutes-Seconds to Decimal Degrees
        function convertDMSToDD(dms, ref) {
            let dd = dms[0] + dms[1]/60 + dms[2]/3600;
            if (ref === "S" || ref === "W") {
                dd = dd * -1;
            }
            return dd;
        }

        function reset() {
            uploadArea.classList.remove('hidden');
            resultsArea.classList.add('hidden');
            resultsArea.classList.remove('grid');
            document.getElementById('file-input').value = ""; // Clear input
            currentExifData = {};
        }

        function copyData() {
            // Remove circular refs or huge binary data before copying
            const cleanData = {...currentExifData};
            delete cleanData.thumbnail;
            delete cleanData.MakerNote;
            
            navigator.clipboard.writeText(JSON.stringify(cleanData, null, 2));
            alert("Metadata copied to clipboard!");
        }

    </script>
</body>
</html>