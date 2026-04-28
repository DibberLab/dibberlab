<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Resizer | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Preset Buttons */
        .preset-btn {
            transition: all 0.2s;
            text-align: left;
            border: 1px solid #374151;
        }
        .preset-btn:hover { background-color: #374151; }
        .preset-btn.active {
            background-color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            color: #111827;
        }
        .preset-btn.active .dim-text { color: #4b5563; }

        /* Canvas Container */
        #canvas-container {
            background-image: 
                linear-gradient(45deg, #1f2937 25%, transparent 25%), 
                linear-gradient(-45deg, #1f2937 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #1f2937 75%), 
                linear-gradient(-45deg, transparent 75%, #1f2937 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            background-color: #111827;
            cursor: grab;
        }
        #canvas-container:active { cursor: grabbing; }

        /* Zoom Slider */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 20px; width: 20px;
            border-radius: 50%; background: #f59e0b; cursor: pointer; margin-top: -8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #4b5563; border-radius: 2px;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-7xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden flex flex-col md:flex-row h-[85vh] md:h-[800px]">
            
            <div class="w-full md:w-80 bg-gray-900 border-r border-gray-700 flex flex-col p-4 z-10 shadow-lg">
                
                <h1 class="text-xl font-bold text-amber-400 mb-6 flex items-center gap-2">
                    <span>✂️</span> Resizer
                </h1>

                <div class="mb-6">
                    <label class="block w-full bg-gray-800 hover:bg-gray-700 border-2 border-dashed border-gray-600 hover:border-gray-500 rounded-xl p-4 text-center cursor-pointer transition-all group">
                        <span class="text-sm font-bold text-gray-300 group-hover:text-white">Upload Image</span>
                        <input type="file" id="file-input" class="hidden" accept="image/*">
                    </label>
                </div>

                <div class="flex-grow overflow-y-auto custom-scrollbar mb-4 -mx-2 px-2">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-2 sticky top-0 bg-gray-900 py-1">Instagram</p>
                    <button class="preset-btn w-full p-3 rounded-lg mb-2 active" data-w="1080" data-h="1080">
                        <div class="font-bold text-sm">Post (Square)</div>
                        <div class="text-xs text-gray-500 dim-text">1080 x 1080</div>
                    </button>
                    <button class="preset-btn w-full p-3 rounded-lg mb-2" data-w="1080" data-h="1350">
                        <div class="font-bold text-sm">Portrait (4:5)</div>
                        <div class="text-xs text-gray-500 dim-text">1080 x 1350</div>
                    </button>
                    <button class="preset-btn w-full p-3 rounded-lg mb-4" data-w="1080" data-h="1920">
                        <div class="font-bold text-sm">Story / Reel</div>
                        <div class="text-xs text-gray-500 dim-text">1080 x 1920</div>
                    </button>

                    <p class="text-xs font-bold text-gray-500 uppercase mb-2 sticky top-0 bg-gray-900 py-1">Twitter / X</p>
                    <button class="preset-btn w-full p-3 rounded-lg mb-2" data-w="1600" data-h="900">
                        <div class="font-bold text-sm">Post (Landscape)</div>
                        <div class="text-xs text-gray-500 dim-text">1600 x 900</div>
                    </button>
                    <button class="preset-btn w-full p-3 rounded-lg mb-4" data-w="1500" data-h="500">
                        <div class="font-bold text-sm">Header</div>
                        <div class="text-xs text-gray-500 dim-text">1500 x 500</div>
                    </button>

                    <p class="text-xs font-bold text-gray-500 uppercase mb-2 sticky top-0 bg-gray-900 py-1">YouTube</p>
                    <button class="preset-btn w-full p-3 rounded-lg mb-2" data-w="1280" data-h="720">
                        <div class="font-bold text-sm">Thumbnail</div>
                        <div class="text-xs text-gray-500 dim-text">1280 x 720</div>
                    </button>
                    <button class="preset-btn w-full p-3 rounded-lg mb-4" data-w="2560" data-h="1440">
                        <div class="font-bold text-sm">Channel Art</div>
                        <div class="text-xs text-gray-500 dim-text">2560 x 1440</div>
                    </button>

                    <p class="text-xs font-bold text-gray-500 uppercase mb-2 sticky top-0 bg-gray-900 py-1">LinkedIn</p>
                    <button class="preset-btn w-full p-3 rounded-lg mb-2" data-w="1200" data-h="627">
                        <div class="font-bold text-sm">Post Link</div>
                        <div class="text-xs text-gray-500 dim-text">1200 x 627</div>
                    </button>
                    <button class="preset-btn w-full p-3 rounded-lg mb-4" data-w="1128" data-h="191">
                        <div class="font-bold text-sm">Cover Photo</div>
                        <div class="text-xs text-gray-500 dim-text">1128 x 191</div>
                    </button>
                </div>

                <div class="pt-4 border-t border-gray-700">
                    <button id="download-btn" disabled class="w-full py-3 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-500 disabled:bg-gray-700 disabled:text-gray-500 disabled:cursor-not-allowed text-white shadow-lg transition-all flex items-center justify-center gap-2">
                        <span>⬇</span> Download
                    </button>
                </div>
            </div>

            <div class="flex-grow flex flex-col relative bg-gray-800">
                
                <div class="absolute top-4 left-1/2 -translate-x-1/2 z-20 bg-gray-900/80 backdrop-blur px-6 py-2 rounded-full border border-gray-600 flex items-center gap-4 shadow-xl">
                    <span class="text-xs font-bold text-gray-400">ZOOM</span>
                    <input type="range" id="zoom-slider" min="0.1" max="3" step="0.01" value="1" class="w-32">
                    <button id="fit-btn" class="text-xs font-bold text-amber-400 hover:text-amber-300">FIT</button>
                </div>

                <div id="canvas-container" class="w-full h-full relative overflow-hidden flex items-center justify-center">
                    <div id="empty-state" class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-gray-500 z-10">
                        <div class="text-6xl mb-4 opacity-50">🖼️</div>
                        <p class="font-bold text-lg">Upload an image to start</p>
                    </div>
                    
                    <canvas id="editor-canvas"></canvas>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const fileInput = document.getElementById('file-input');
        const canvas = document.getElementById('editor-canvas');
        const ctx = canvas.getContext('2d');
        const container = document.getElementById('canvas-container');
        const zoomSlider = document.getElementById('zoom-slider');
        const fitBtn = document.getElementById('fit-btn');
        const downloadBtn = document.getElementById('download-btn');
        const emptyState = document.getElementById('empty-state');
        const presetBtns = document.querySelectorAll('.preset-btn');

        // State
        let img = null;
        let currentPreset = { w: 1080, h: 1080 }; // Default IG Post
        
        // Transform State
        let state = {
            scale: 1,
            x: 0,
            y: 0,
            isDragging: false,
            startX: 0,
            startY: 0
        };

        // --- INIT ---
        function initCanvas() {
            // Set canvas size to container size
            canvas.width = container.clientWidth;
            canvas.height = container.clientHeight;
            draw();
        }

        window.addEventListener('resize', initCanvas);

        // --- CORE DRAWING ---
        function draw() {
            // Clear Background
            ctx.fillStyle = '#111827';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            if (!img) return;

            // 1. Draw Image with transforms
            ctx.save();
            ctx.translate(canvas.width / 2 + state.x, canvas.height / 2 + state.y);
            ctx.scale(state.scale, state.scale);
            ctx.drawImage(img, -img.width / 2, -img.height / 2);
            ctx.restore();

            // 2. Draw Overlay (The "Crop Mask")
            drawMask();
        }

        function drawMask() {
            // Calculate scale to fit the target crop box within the canvas view
            // We want the crop box to occupy about 70% of the screen max
            const padding = 40;
            const maxW = canvas.width - (padding * 2);
            const maxH = canvas.height - (padding * 2);
            
            // Calculate aspect ratios
            const targetRatio = currentPreset.w / currentPreset.h;
            const canvasRatio = maxW / maxH;

            let drawW, drawH;

            if (targetRatio > canvasRatio) {
                // Limited by width
                drawW = maxW;
                drawH = maxW / targetRatio;
            } else {
                // Limited by height
                drawH = maxH;
                drawW = maxH * targetRatio;
            }

            // Store the "Visual" crop box dimensions for math later
            state.cropBox = {
                w: drawW,
                h: drawH,
                x: (canvas.width - drawW) / 2,
                y: (canvas.height - drawH) / 2
            };

            // Dim everything outside the box
            ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
            
            // Top rect
            ctx.fillRect(0, 0, canvas.width, state.cropBox.y);
            // Bottom rect
            ctx.fillRect(0, state.cropBox.y + state.cropBox.h, canvas.width, canvas.height - (state.cropBox.y + state.cropBox.h));
            // Left rect
            ctx.fillRect(0, state.cropBox.y, state.cropBox.x, state.cropBox.h);
            // Right rect
            ctx.fillRect(state.cropBox.x + state.cropBox.w, state.cropBox.y, canvas.width - (state.cropBox.x + state.cropBox.w), state.cropBox.h);

            // Draw Border
            ctx.strokeStyle = '#f59e0b';
            ctx.lineWidth = 2;
            ctx.strokeRect(state.cropBox.x, state.cropBox.y, state.cropBox.w, state.cropBox.h);
            
            // Draw Dimensions Text
            ctx.fillStyle = '#f59e0b';
            ctx.font = 'bold 12px Inter';
            ctx.fillText(`${currentPreset.w} x ${currentPreset.h}`, state.cropBox.x, state.cropBox.y - 10);
        }

        // --- INTERACTION ---

        function handleFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                img = new Image();
                img.onload = () => {
                    emptyState.classList.add('hidden');
                    downloadBtn.disabled = false;
                    fitImage();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function fitImage() {
            if (!img) return;
            // Reset position
            state.x = 0;
            state.y = 0;
            
            // Calculate scale to "Cover" the crop area initially? Or "Contain"?
            // Usually "Contain" within the crop box is safer for user to start
            // But visually, we render the crop box dynamically. 
            // Let's scale image to roughly 50% of canvas width
            const targetScale = (canvas.width * 0.6) / img.width;
            state.scale = targetScale;
            zoomSlider.value = state.scale;
            
            draw();
        }

        // --- EVENT LISTENERS ---

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) handleFile(e.target.files[0]);
        });

        // Zoom
        zoomSlider.addEventListener('input', (e) => {
            state.scale = parseFloat(e.target.value);
            draw();
        });

        fitBtn.addEventListener('click', fitImage);

        // Presets
        presetBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                presetBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentPreset = {
                    w: parseInt(btn.dataset.w),
                    h: parseInt(btn.dataset.h)
                };
                draw();
            });
        });

        // Panning Logic
        container.addEventListener('mousedown', (e) => {
            if (!img) return;
            state.isDragging = true;
            state.startX = e.clientX - state.x;
            state.startY = e.clientY - state.y;
        });

        window.addEventListener('mousemove', (e) => {
            if (state.isDragging) {
                e.preventDefault();
                state.x = e.clientX - state.startX;
                state.y = e.clientY - state.startY;
                draw();
            }
        });

        window.addEventListener('mouseup', () => {
            state.isDragging = false;
        });

        // Mouse Wheel Zoom
        container.addEventListener('wheel', (e) => {
            if(!img) return;
            e.preventDefault();
            const delta = e.deltaY * -0.001;
            const newScale = Math.min(Math.max(0.1, state.scale + delta), 3);
            state.scale = newScale;
            zoomSlider.value = newScale;
            draw();
        });

        // --- DOWNLOAD LOGIC ---
        downloadBtn.addEventListener('click', () => {
            if (!img) return;

            // 1. Create a temporary canvas at TARGET resolution
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = currentPreset.w;
            tempCanvas.height = currentPreset.h;
            const tempCtx = tempCanvas.getContext('2d');

            // 2. Calculate the mapping
            // We need to map the visible pixels inside 'state.cropBox' to the full 'tempCanvas'
            
            // Center of the export canvas
            const exportCenterX = tempCanvas.width / 2;
            const exportCenterY = tempCanvas.height / 2;

            // Calculate the image's position relative to the crop box center
            // In the visual editor:
            // CropCenter = (state.cropBox.x + state.cropBox.w/2, ...)
            // ImagePos = (canvas.width/2 + state.x, ...)
            
            // Visual Offset from Crop Center
            const visualCropCenterX = state.cropBox.x + (state.cropBox.w / 2);
            const visualCropCenterY = state.cropBox.y + (state.cropBox.h / 2);
            
            const visualImgCenterX = (canvas.width / 2) + state.x;
            const visualImgCenterY = (canvas.height / 2) + state.y;

            const diffX = visualImgCenterX - visualCropCenterX;
            const diffY = visualImgCenterY - visualCropCenterY;

            // Now, we need the "Ratio" of (Export Size / Visual Crop Box Size)
            const exportRatio = currentPreset.w / state.cropBox.w;

            // Scale everything up by that ratio
            const finalScale = state.scale * exportRatio;
            const finalX = diffX * exportRatio;
            const finalY = diffY * exportRatio;

            // Draw to temp canvas
            tempCtx.translate(exportCenterX + finalX, exportCenterY + finalY);
            tempCtx.scale(finalScale, finalScale);
            tempCtx.drawImage(img, -img.width / 2, -img.height / 2);

            // Download
            const link = document.createElement('a');
            link.download = `resized-image-${currentPreset.w}x${currentPreset.h}.jpg`;
            link.href = tempCanvas.toDataURL('image/jpeg', 0.9);
            link.click();
        });

        // Init
        initCanvas();

    </script>
</body>
</html>