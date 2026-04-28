<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Compressor | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Drop Zone */
        #drop-zone {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.2s;
        }
        #drop-zone:hover { background-color: rgba(31, 41, 55, 0.5); }
        #drop-zone.drag-active {
            background-color: rgba(16, 185, 129, 0.1);
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%2310B981FF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }

        /* Range Slider */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 20px; width: 20px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }

        /* Select Styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }
        
        /* Checkerboard background for transparency */
        .checkerboard {
            background-color: #1f2937;
            background-image: linear-gradient(45deg, #374151 25%, transparent 25%), 
                              linear-gradient(-45deg, #374151 25%, transparent 25%), 
                              linear-gradient(45deg, transparent 75%, #374151 75%), 
                              linear-gradient(-45deg, transparent 75%, #374151 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Image Compressor</h1>
                <p class="text-center text-gray-400">Reduce file size securely in your browser.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <div id="drop-zone" class="h-48 rounded-2xl flex flex-col items-center justify-center cursor-pointer relative group">
                        <input type="file" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/png, image/jpeg, image/webp">
                        <div class="text-center pointer-events-none group-hover:scale-105 transition-transform">
                            <div class="text-4xl mb-3">📸</div>
                            <p class="text-gray-300 font-bold">Click or Drop Image</p>
                            <p class="text-gray-500 text-xs mt-1">JPG, PNG, WEBP</p>
                        </div>
                    </div>

                    <div id="controls-area" class="space-y-6 opacity-50 pointer-events-none transition-opacity">
                        
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Quality</span>
                                <span id="quality-val" class="text-white">80%</span>
                            </div>
                            <input type="range" id="quality-slider" min="1" max="100" value="80" class="w-full">
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-gray-400 font-bold uppercase mb-2">
                                <span>Max Width (Resize)</span>
                                <span id="width-val" class="text-white">Original</span>
                            </div>
                            <input type="range" id="width-slider" min="100" max="4000" step="100" value="1920" class="w-full">
                            <p class="text-[10px] text-gray-500 mt-1">Scaling down dimensions saves the most space.</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Output Format</label>
                            <select id="format-select" class="w-full bg-gray-900 border border-gray-600 rounded-lg py-2 px-3 text-white focus:border-amber-500">
                                <option value="image/jpeg">JPEG (Best for Photos)</option>
                                <option value="image/webp">WEBP (Modern & Small)</option>
                                <option value="image/png">PNG (Best for Transparency)</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="lg:col-span-7 flex flex-col h-full bg-gray-900 rounded-xl border border-gray-700 p-1 relative min-h-[400px]">
                    
                    <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 z-10 bg-gray-900 rounded-xl">
                        <span class="text-3xl mb-2 opacity-50">📉</span>
                        <p class="text-sm">Upload an image to start compressing</p>
                    </div>

                    <div id="results-view" class="hidden flex flex-col h-full">
                        
                        <div class="flex justify-between items-center bg-gray-800 p-4 rounded-t-lg border-b border-gray-700">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">Original</p>
                                <p id="original-size" class="text-white font-mono">0 KB</p>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-emerald-400" id="savings-text">-0%</div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase font-bold">Compressed</p>
                                <p id="compressed-size" class="text-white font-mono">0 KB</p>
                            </div>
                        </div>

                        <div class="flex-grow checkerboard relative flex items-center justify-center p-4 overflow-hidden">
                            <img id="preview-img" class="max-w-full max-h-full object-contain shadow-2xl rounded">
                            
                            <div id="loading-spinner" class="absolute inset-0 bg-black/50 flex items-center justify-center hidden">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-800 rounded-b-lg border-t border-gray-700 flex justify-end">
                            <button id="download-btn" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors shadow-lg flex items-center gap-2">
                                <span>⬇</span> Download Image
                            </button>
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
        const controlsArea = document.getElementById('controls-area');
        const qualitySlider = document.getElementById('quality-slider');
        const qualityVal = document.getElementById('quality-val');
        const widthSlider = document.getElementById('width-slider');
        const widthVal = document.getElementById('width-val');
        const formatSelect = document.getElementById('format-select');
        
        const emptyState = document.getElementById('empty-state');
        const resultsView = document.getElementById('results-view');
        const previewImg = document.getElementById('preview-img');
        const originalSizeDisplay = document.getElementById('original-size');
        const compressedSizeDisplay = document.getElementById('compressed-size');
        const savingsText = document.getElementById('savings-text');
        const downloadBtn = document.getElementById('download-btn');
        const loadingSpinner = document.getElementById('loading-spinner');

        // State
        let originalFile = null;
        let originalImage = null; // Image Object
        let compressedBlob = null;

        // --- CORE LOGIC ---

        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert("Please upload an image file.");
                return;
            }

            originalFile = file;
            originalSizeDisplay.textContent = formatBytes(file.size);

            // Enable UI
            controlsArea.classList.remove('opacity-50', 'pointer-events-none');
            emptyState.classList.add('hidden');
            resultsView.classList.remove('hidden');

            // Load Image
            const reader = new FileReader();
            reader.onload = (e) => {
                originalImage = new Image();
                originalImage.onload = () => {
                    // Set sliders based on image
                    widthSlider.max = originalImage.width;
                    widthSlider.value = originalImage.width;
                    widthVal.textContent = `${originalImage.width}px (Original)`;
                    
                    compressImage(); // Initial run
                };
                originalImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function compressImage() {
            if (!originalImage) return;

            // Show loading
            loadingSpinner.classList.remove('hidden');

            // Use requestAnimationFrame to prevent UI freeze
            requestAnimationFrame(() => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // 1. Calculate Dimensions
                const maxWidth = parseInt(widthSlider.value);
                let w = originalImage.width;
                let h = originalImage.height;

                if (w > maxWidth) {
                    const ratio = h / w;
                    w = maxWidth;
                    h = w * ratio;
                }

                canvas.width = w;
                canvas.height = h;

                // 2. Draw
                ctx.drawImage(originalImage, 0, 0, w, h);

                // 3. Compress
                const quality = parseInt(qualitySlider.value) / 100;
                const type = formatSelect.value;

                canvas.toBlob((blob) => {
                    compressedBlob = blob;
                    
                    // Update UI
                    const url = URL.createObjectURL(blob);
                    previewImg.src = url;
                    compressedSizeDisplay.textContent = formatBytes(blob.size);
                    
                    // Calculate Savings
                    const savedBytes = originalFile.size - blob.size;
                    const percent = Math.round((savedBytes / originalFile.size) * 100);
                    
                    if (percent > 0) {
                        savingsText.textContent = `-${percent}%`;
                        savingsText.className = "text-2xl font-bold text-emerald-400";
                    } else {
                        savingsText.textContent = `+${Math.abs(percent)}%`;
                        savingsText.className = "text-2xl font-bold text-red-400";
                    }

                    loadingSpinner.classList.add('hidden');

                }, type, quality);
            });
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // --- LISTENERS ---

        // Input Changes (Debounce slightly?)
        // For simplicity, change event is better than input for heavy ops, but input feels snappier.
        // We will use 'input' for sliders but lightweight processing helps.
        
        qualitySlider.addEventListener('input', (e) => {
            qualityVal.textContent = e.target.value + '%';
            compressImage(); // Trigger compression
        });

        widthSlider.addEventListener('input', (e) => {
            widthVal.textContent = e.target.value + 'px';
            compressImage();
        });

        formatSelect.addEventListener('change', compressImage);

        // File Input
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) handleFile(e.target.files[0]);
        });

        // Drag & Drop
        dropZone.addEventListener('dragenter', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-active'); });
        dropZone.addEventListener('dragleave', (e) => { e.preventDefault(); dropZone.classList.remove('drag-active'); });
        dropZone.addEventListener('drop', (e) => { 
            e.preventDefault(); 
            dropZone.classList.remove('drag-active');
            if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
        });

        // Download
        downloadBtn.addEventListener('click', () => {
            if (!compressedBlob) return;
            
            const ext = formatSelect.value === 'image/jpeg' ? 'jpg' : (formatSelect.value === 'image/png' ? 'png' : 'webp');
            // Remove old extension from name
            const oldName = originalFile.name.replace(/\.[^/.]+$/, "");
            const newName = `${oldName}-compressed.${ext}`;

            const url = URL.createObjectURL(compressedBlob);
            const a = document.createElement('a');
            a.href = url;
            a.download = newName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

    </script>
</body>
</html>