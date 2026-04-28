<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favicon Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Upload Zone */
        #drop-zone {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.2s;
        }
        #drop-zone:hover {
            background-color: rgba(31, 41, 55, 0.5);
        }
        #drop-zone.drag-active {
            background-color: rgba(16, 185, 129, 0.1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%2310B981FF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }

        /* Mock Browser Tab */
        .browser-mockup {
            background: #1f2937;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            overflow: hidden;
        }
        .browser-tab {
            background: #374151;
            border-radius: 8px 8px 0 0;
            max-width: 200px;
            margin-left: 10px;
            margin-top: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Favicon Generator</h1>
                <p class="text-center text-gray-400">Create modern icons for all browsers and devices.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <div id="drop-zone" class="h-48 rounded-2xl flex flex-col items-center justify-center cursor-pointer relative group">
                        <input type="file" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/png, image/jpeg, image/svg+xml">
                        <div class="text-center pointer-events-none group-hover:scale-105 transition-transform">
                            <div class="text-4xl mb-3">🖼️</div>
                            <p class="text-gray-300 font-bold">Click or Drop Image</p>
                            <p class="text-gray-500 text-xs mt-1">Recommended: 512x512 PNG</p>
                        </div>
                    </div>

                    <div id="source-preview-container" class="hidden bg-gray-900 rounded-xl p-4 border border-gray-700 flex items-center gap-4">
                        <img id="source-img" class="w-16 h-16 object-contain rounded-lg bg-gray-800" src="">
                        <div>
                            <p class="text-sm font-bold text-white mb-1">Source Image</p>
                            <p id="source-dims" class="text-xs text-gray-500 font-mono">0 x 0</p>
                        </div>
                        <button id="reset-btn" class="ml-auto text-xs text-red-400 hover:text-red-300 underline">Reset</button>
                    </div>

                    <div class="mt-auto">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Browser Preview</p>
                        <div class="browser-mockup border border-gray-600 h-32 relative bg-gray-800">
                            <div class="h-10 bg-gray-900 border-b border-gray-700 flex items-end">
                                <div class="browser-tab">
                                    <img id="tab-icon" class="w-4 h-4 rounded-sm" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%239CA3AF'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z'/%3E%3C/svg%3E">
                                    <span class="text-xs text-white font-medium truncate w-24">My Awesome Website</span>
                                    <span class="text-gray-500 text-xs ml-2 cursor-pointer hover:text-gray-300">×</span>
                                </div>
                            </div>
                            <div class="p-4 bg-white h-full"></div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-7 flex flex-col h-full">
                    
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-xs font-bold text-gray-500 uppercase">Generated Assets</label>
                        <button id="download-all-btn" disabled class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-bold text-sm transition-colors shadow-lg flex items-center gap-2">
                            <span>📦</span> Download Zip
                        </button>
                    </div>

                    <div class="bg-gray-900 rounded-xl border border-gray-700 p-1 overflow-hidden flex-grow relative min-h-[400px]">
                        
                        <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600">
                            <span class="text-3xl mb-2 opacity-50">✨</span>
                            <p class="text-sm">Upload an image to generate icons</p>
                        </div>

                        <div id="results-list" class="hidden divide-y divide-gray-700 h-full overflow-y-auto custom-scrollbar">
                            
                            <div class="p-4 flex items-center gap-4 hover:bg-gray-800 transition-colors">
                                <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-600">
                                    <img id="prev-16" class="w-4 h-4 rendering-pixelated">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-white">favicon-16x16.png</p>
                                    <p class="text-xs text-gray-500">Standard desktop browser tab icon.</p>
                                </div>
                                <button class="dl-btn p-2 text-gray-400 hover:text-white" data-size="16">⬇</button>
                            </div>

                            <div class="p-4 flex items-center gap-4 hover:bg-gray-800 transition-colors">
                                <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-600">
                                    <img id="prev-32" class="w-8 h-8 rendering-pixelated">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-white">favicon-32x32.png</p>
                                    <p class="text-xs text-gray-500">Retina screens and taskbars.</p>
                                </div>
                                <button class="dl-btn p-2 text-gray-400 hover:text-white" data-size="32">⬇</button>
                            </div>

                            <div class="p-4 flex items-center gap-4 hover:bg-gray-800 transition-colors">
                                <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-600">
                                    <img id="prev-180" class="w-full h-full object-contain rounded-md">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-white">apple-touch-icon.png</p>
                                    <p class="text-xs text-gray-500">180x180 for iPhone/iPad home screen.</p>
                                </div>
                                <button class="dl-btn p-2 text-gray-400 hover:text-white" data-size="180">⬇</button>
                            </div>

                            <div class="p-4 flex items-center gap-4 hover:bg-gray-800 transition-colors">
                                <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-600">
                                    <img id="prev-192" class="w-full h-full object-contain rounded-md">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-white">android-chrome-192.png</p>
                                    <p class="text-xs text-gray-500">Android home screen shortcut.</p>
                                </div>
                                <button class="dl-btn p-2 text-gray-400 hover:text-white" data-size="192">⬇</button>
                            </div>

                            <div class="p-4 flex items-center gap-4 hover:bg-gray-800 transition-colors">
                                <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-600">
                                    <img id="prev-512" class="w-full h-full object-contain rounded-md">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-white">android-chrome-512.png</p>
                                    <p class="text-xs text-gray-500">PWA Splash screen / Play Store.</p>
                                </div>
                                <button class="dl-btn p-2 text-gray-400 hover:text-white" data-size="512">⬇</button>
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
        // DOM Elements
        const fileInput = document.getElementById('file-input');
        const dropZone = document.getElementById('drop-zone');
        const sourcePreviewContainer = document.getElementById('source-preview-container');
        const sourceImg = document.getElementById('source-img');
        const sourceDims = document.getElementById('source-dims');
        const resetBtn = document.getElementById('reset-btn');
        const tabIcon = document.getElementById('tab-icon');
        const emptyState = document.getElementById('empty-state');
        const resultsList = document.getElementById('results-list');
        const downloadAllBtn = document.getElementById('download-all-btn');
        const dlBtns = document.querySelectorAll('.dl-btn');

        // Image Data Storage
        let generatedImages = {}; // { 16: blob, 32: blob ... }
        
        const sizes = [16, 32, 180, 192, 512];
        const filenames = {
            16: "favicon-16x16.png",
            32: "favicon-32x32.png",
            180: "apple-touch-icon.png",
            192: "android-chrome-192x192.png",
            512: "android-chrome-512x512.png"
        };

        // --- PROCESSING LOGIC ---

        function processFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Please upload a valid image file.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    // Update Source Info
                    sourceImg.src = e.target.result;
                    sourceDims.textContent = `${img.naturalWidth} x ${img.naturalHeight}`;
                    sourcePreviewContainer.classList.remove('hidden');
                    dropZone.classList.add('hidden');
                    
                    // Generate Icons
                    generateIcons(img);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        async function generateIcons(img) {
            generatedImages = {}; // Reset
            
            // Loop through needed sizes
            for (const size of sizes) {
                const canvas = document.createElement('canvas');
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                
                // Draw resized image
                // Use better quality scaling
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(img, 0, 0, size, size);

                // Convert to Blob
                await new Promise(resolve => {
                    canvas.toBlob(blob => {
                        generatedImages[size] = blob;
                        const url = URL.createObjectURL(blob);
                        
                        // Update UI Preview
                        const previewEl = document.getElementById(`prev-${size}`);
                        if (previewEl) previewEl.src = url;
                        
                        // Update Browser Mock (use 16x16)
                        if (size === 16) tabIcon.src = url;

                        resolve();
                    }, 'image/png');
                });
            }

            // Show UI
            emptyState.classList.add('hidden');
            resultsList.classList.remove('hidden');
            downloadAllBtn.disabled = false;
        }

        // --- DOWNLOAD LOGIC ---

        function downloadSingle(size) {
            const blob = generatedImages[size];
            if (!blob) return;
            saveAs(blob, filenames[size]);
        }

        function downloadZip() {
            if (Object.keys(generatedImages).length === 0) return;

            const zip = new JSZip();
            
            for (const size of sizes) {
                if (generatedImages[size]) {
                    zip.file(filenames[size], generatedImages[size]);
                }
            }

            zip.generateAsync({type:"blob"}).then(function(content) {
                saveAs(content, "favicons.zip");
            });
        }

        // --- EVENT LISTENERS ---

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) processFile(e.target.files[0]);
        });

        // Drag & Drop
        dropZone.addEventListener('dragenter', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragleave', (e) => { e.preventDefault(); dropZone.classList.remove('drag-active'); });
        dropZone.addEventListener('drop', (e) => { 
            e.preventDefault(); 
            dropZone.classList.remove('drag-active');
            if (e.dataTransfer.files.length > 0) processFile(e.dataTransfer.files[0]);
        });

        // Reset
        resetBtn.addEventListener('click', () => {
            sourcePreviewContainer.classList.add('hidden');
            dropZone.classList.remove('hidden');
            resultsList.classList.add('hidden');
            emptyState.classList.remove('hidden');
            downloadAllBtn.disabled = true;
            fileInput.value = '';
            // Reset Browser Mock
            tabIcon.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%239CA3AF'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z'/%3E%3C/svg%3E";
        });

        // Downloads
        dlBtns.forEach(btn => {
            btn.addEventListener('click', () => downloadSingle(parseInt(btn.dataset.size)));
        });

        downloadAllBtn.addEventListener('click', downloadZip);

    </script>
</body>
</html>