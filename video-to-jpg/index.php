<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Tools | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Range Slider */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 24px;
            width: 8px;
            border-radius: 4px;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -10px;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #374151;
            border-radius: 2px;
        }

        /* Drop Zone */
        .drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }
        .drop-zone:hover, .drop-zone.dragover {
            background-color: rgba(31, 41, 55, 0.5);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%23F59E0BFF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transform: scale(0.99);
        }

        /* Tab Active State */
        .tab-btn.active {
            background-color: #f59e0b;
            color: #111827;
            border-color: #f59e0b;
        }

        /* Video Container */
        video {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        /* Flash Animation for Capture */
        .flash-overlay {
            animation: flash 0.3s ease-out;
        }
        @keyframes flash {
            0% { background: white; opacity: 0.8; }
            100% { background: transparent; opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center py-8">
        <div class="w-full max-w-5xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Video Tools</h1>
                <p class="text-center text-gray-400 text-sm">Extract frames from local files or YouTube.</p>
            </div>

            <div class="flex justify-center gap-4 mb-8">
                <button onclick="switchTab('local')" id="btn-tab-local" class="tab-btn active px-6 py-2 rounded-full border border-gray-600 text-sm font-bold transition-all hover:border-amber-400">Local File</button>
                <button onclick="switchTab('youtube')" id="btn-tab-youtube" class="tab-btn px-6 py-2 rounded-full border border-gray-600 text-sm font-bold transition-all hover:border-amber-400">YouTube URL</button>
            </div>

            <div id="mode-local" class="mode-section w-full max-w-2xl mx-auto mb-10">
                <label class="drop-zone w-full h-64 flex flex-col items-center justify-center cursor-pointer rounded-3xl group relative overflow-hidden">
                    <div class="z-10 flex flex-col items-center pointer-events-none">
                        <svg class="w-16 h-16 text-gray-500 group-hover:text-amber-400 mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <p class="text-xl font-bold text-gray-300 group-hover:text-white transition-colors">Drop Local Video</p>
                        <p class="text-sm text-gray-500 mt-2">MP4, WEBM, MOV</p>
                    </div>
                    <input type="file" id="file-input" class="hidden" accept="video/*" onchange="handleFile(this.files[0])">
                </label>
            </div>

            <div id="mode-youtube" class="mode-section hidden w-full max-w-2xl mx-auto mb-10">
                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">YouTube URL</label>
                    <div class="flex gap-2">
                        <input type="text" id="yt-input" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-400 transition-colors">
                        <button onclick="processYoutube()" class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold px-6 py-3 rounded-xl transition-colors whitespace-nowrap">
                            Get Images
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-3 italic">Note: Due to browser security, frame-by-frame scrubbing is not available for YouTube. This tool extracts the high-res thumbnails.</p>
                </div>
            </div>

            <div id="editor-area" class="hidden flex-col items-center gap-6 animate-fade-in">
                
                <div class="relative w-full max-w-3xl bg-black rounded-2xl overflow-hidden border border-gray-700">
                    <video id="main-video" class="w-full h-auto max-h-[60vh] mx-auto" playsinline></video>
                    <div id="capture-flash" class="absolute inset-0 pointer-events-none opacity-0"></div>
                </div>

                <div class="w-full max-w-3xl bg-gray-800 p-6 rounded-2xl border border-gray-700">
                    
                    <div class="flex justify-between items-center mb-4 font-mono text-sm">
                        <span id="current-time" class="text-amber-400 font-bold">00:00.00</span>
                        <span id="duration-time" class="text-gray-500">00:00.00</span>
                    </div>

                    <div class="relative w-full h-8 flex items-center mb-6">
                        <input type="range" id="scrubber" min="0" max="100" step="0.01" value="0" class="w-full z-10">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        
                        <div class="flex justify-center md:justify-start gap-2">
                            <button onclick="stepFrame(-0.1)" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-xs font-bold" title="-0.1s">&lt;&lt;</button>
                            <button onclick="stepFrame(-0.03)" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-xs font-bold" title="Previous Frame">&lt; Frame</button>
                            <button onclick="stepFrame(0.03)" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-xs font-bold" title="Next Frame">Frame &gt;</button>
                            <button onclick="stepFrame(0.1)" class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-xs font-bold" title="+0.1s">&gt;&gt;</button>
                        </div>

                        <div class="flex justify-center">
                            <button onclick="captureFrame()" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold px-8 py-3 rounded-full shadow-lg shadow-amber-500/20 transform transition hover:-translate-y-1 active:scale-95">
                                Capture Frame
                            </button>
                        </div>

                        <div class="flex justify-center md:justify-end">
                            <button onclick="reset()" class="text-xs text-gray-500 hover:text-white underline">Load New Video</button>
                        </div>

                    </div>
                </div>

                <div id="gallery" class="w-full max-w-6xl grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    </div>
            </div>

            <div id="youtube-results" class="hidden w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 flex flex-col">
                    <div class="aspect-video bg-black relative group">
                        <img id="yt-img-max" src="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                             
                        </div>
                    </div>
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white">Max Resolution</h3>
                            <p class="text-xs text-gray-500">1280x720 (HD)</p>
                        </div>
                        <button onclick="downloadYoutubeImage('yt-img-max')" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">Download</button>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 flex flex-col">
                    <div class="aspect-video bg-black">
                        <img id="yt-img-hq" src="" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white">High Quality</h3>
                            <p class="text-xs text-gray-500">480x360</p>
                        </div>
                        <button onclick="downloadYoutubeImage('yt-img-hq')" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">Download</button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <canvas id="canvas" class="hidden"></canvas>

    <script>
        // DOM Elements - Navigation
        const btnLocal = document.getElementById('btn-tab-local');
        const btnYoutube = document.getElementById('btn-tab-youtube');
        const modeLocal = document.getElementById('mode-local');
        const modeYoutube = document.getElementById('mode-youtube');
        
        // DOM Elements - Local
        const editorArea = document.getElementById('editor-area');
        const dropZone = document.querySelector('.drop-zone');
        const video = document.getElementById('main-video');
        const scrubber = document.getElementById('scrubber');
        const currentTimeEl = document.getElementById('current-time');
        const durationTimeEl = document.getElementById('duration-time');
        const flashOverlay = document.getElementById('capture-flash');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const gallery = document.getElementById('gallery');

        // DOM Elements - Youtube
        const ytInput = document.getElementById('yt-input');
        const ytResults = document.getElementById('youtube-results');
        const ytImgMax = document.getElementById('yt-img-max');
        const ytImgHq = document.getElementById('yt-img-hq');

        // --- TABS ---
        function switchTab(tab) {
            // Hide all results
            editorArea.classList.remove('flex');
            editorArea.classList.add('hidden');
            ytResults.classList.add('hidden');
            
            // Toggle Inputs
            if (tab === 'local') {
                btnLocal.classList.add('active');
                btnYoutube.classList.remove('active');
                modeLocal.classList.remove('hidden');
                modeYoutube.classList.add('hidden');
            } else {
                btnLocal.classList.remove('active');
                btnYoutube.classList.add('active');
                modeLocal.classList.add('hidden');
                modeYoutube.classList.remove('hidden');
            }
        }

        // --- YOUTUBE LOGIC ---
        function processYoutube() {
            const url = ytInput.value;
            const videoId = extractVideoID(url);

            if (!videoId) {
                alert("Invalid YouTube URL");
                return;
            }

            // Construct URLs
            const maxRes = `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`;
            const hqRes = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;

            ytImgMax.src = maxRes;
            ytImgHq.src = hqRes;

            ytResults.classList.remove('hidden');
        }

        function extractVideoID(url) {
            const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[7].length == 11) ? match[7] : false;
        }

        async function downloadYoutubeImage(imgId) {
            const imgEl = document.getElementById(imgId);
            const src = imgEl.src;
            
            try {
                // Fetch blob to force download instead of opening in tab
                const response = await fetch(src);
                const blob = await response.blob();
                const blobUrl = URL.createObjectURL(blob);
                
                const link = document.createElement("a");
                link.href = blobUrl;
                link.download = `youtube_thumb_${Date.now()}.jpg`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } catch (error) {
                // Fallback if fetch fails (CORS issues on some networks)
                window.open(src, '_blank');
            }
        }

        // --- LOCAL VIDEO LOGIC (Drag & Drop) ---
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

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

        function handleFile(file) {
            if (!file) return;
            if (!file.type.startsWith('video/')) return alert("Please upload a valid video file.");

            const url = URL.createObjectURL(file);
            video.src = url;

            modeLocal.classList.add('hidden');
            editorArea.classList.remove('hidden');
            editorArea.classList.add('flex');
        }

        // Video Player Logic
        video.addEventListener('loadedmetadata', () => {
            scrubber.max = video.duration;
            durationTimeEl.textContent = formatTime(video.duration);
        });

        video.addEventListener('timeupdate', () => {
            scrubber.value = video.currentTime;
            currentTimeEl.textContent = formatTime(video.currentTime);
        });

        scrubber.addEventListener('input', () => {
            video.currentTime = scrubber.value;
            currentTimeEl.textContent = formatTime(scrubber.value);
        });

        function stepFrame(seconds) { video.currentTime += seconds; }

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            const ms = Math.floor((seconds % 1) * 100);
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
        }

        function captureFrame() {
            flashOverlay.classList.remove('flash-overlay');
            void flashOverlay.offsetWidth; 
            flashOverlay.classList.add('flash-overlay');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
            addToGallery(dataUrl);
        }

        function addToGallery(dataUrl) {
            const div = document.createElement('div');
            div.className = "relative group aspect-video bg-black rounded-lg overflow-hidden border border-gray-700 shadow-lg animation-fade-in";
            const timeStamp = formatTime(video.currentTime).replace(':','-').replace('.','-');
            const fileName = `frame_${timeStamp}.jpg`;

            div.innerHTML = `
                <img src="${dataUrl}" class="w-full h-full object-contain">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <a href="${dataUrl}" download="${fileName}" class="p-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-400" title="Download">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                    <button onclick="this.closest('.group').remove()" class="p-2 bg-red-500 text-white rounded-full hover:bg-red-400" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
            gallery.insertBefore(div, gallery.firstChild);
        }

        function reset() {
            if(confirm("Discard current video and gallery?")) {
                video.src = "";
                gallery.innerHTML = "";
                modeLocal.classList.remove('hidden');
                editorArea.classList.add('hidden');
                editorArea.classList.remove('flex');
                document.getElementById('file-input').value = "";
            }
        }
    </script>
</body>
</html>