<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meme Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Inputs */
        .meme-input {
            background: #1f2937; /* Gray-800 */
            border: 2px solid #374151; /* Gray-700 */
            transition: all 0.2s;
            color: white;
            font-weight: bold;
        }
        .meme-input:focus {
            outline: none;
            border-color: #f59e0b; /* Amber */
            background: #111827; /* Gray-900 */
        }
        .meme-input::placeholder {
            color: #4b5563;
            text-transform: none;
            font-weight: normal;
        }

        /* Color Pickers */
        input[type="color"] {
            -webkit-appearance: none;
            border: 2px solid #374151;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            padding: 0;
            overflow: hidden;
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; }

        /* Range Slider */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #f59e0b;
            cursor: pointer;
            margin-top: -6px;
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #374151;
            border-radius: 2px;
        }

        /* Canvas Container to manage Aspect Ratio */
        .canvas-container {
            background-image: url("data:image/svg+xml,%3csvg width='20' height='20' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='10' height='10' fill='%231f2937'/%3e%3crect x='10' y='10' width='10' height='10' fill='%231f2937'/%3e%3c/svg%3e");
            background-color: #111827;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 py-8 flex justify-center">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 space-y-6">
                
                <div>
                    <h1 class="text-3xl font-bold text-amber-400 mb-2">Meme Generator</h1>
                    <p class="text-gray-400 text-sm">Make dank memes fast.</p>
                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700">
                    <label class="block w-full flex flex-col items-center justify-center px-4 py-6 bg-gray-900 text-gray-400 rounded-xl border-2 border-dashed border-gray-600 cursor-pointer hover:border-amber-500 hover:text-amber-500 transition group">
                        <svg class="w-8 h-8 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-bold">Select Image Template</span>
                        <input type='file' id="image-input" class="hidden" accept="image/*" />
                    </label>
                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700 space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Top Text</label>
                        <input type="text" id="top-text" class="meme-input w-full rounded-xl p-3 uppercase" placeholder="TOP TEXT">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Bottom Text</label>
                        <input type="text" id="bottom-text" class="meme-input w-full rounded-xl p-3 uppercase" placeholder="BOTTOM TEXT">
                    </div>
                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700 space-y-4">
                    <h3 class="text-sm font-bold text-gray-300 mb-4">Styling</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Font Size</label>
                            <input type="range" id="font-size" min="20" max="120" value="50" class="w-full">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">Colors (Fill / Outline)</label>
                            <div class="flex gap-2">
                                <input type="color" id="text-color" value="#FFFFFF" title="Text Color">
                                <input type="color" id="outline-color" value="#000000" title="Outline Color">
                            </div>
                        </div>
                    </div>
                </div>

                 <button onclick="downloadMeme()" id="dl-btn" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none" disabled>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Meme
                </button>

            </div>

            <div class="lg:col-span-7 flex items-start justify-center">
                <div class="canvas-container w-full rounded-2xl overflow-hidden border-2 border-gray-700 shadow-2xl flex items-center justify-center min-h-[400px] lg:min-h-[600px] relative">
                    
                    <div id="placeholder-text" class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                        <p class="text-lg font-bold uppercase tracking-widest opacity-50">Preview Area</p>
                        <p class="text-sm opacity-30">Upload an image to start</p>
                    </div>
                    
                    <canvas id="meme-canvas" class="max-w-full max-h-[70vh] object-contain hidden"></canvas>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const imageInput = document.getElementById('image-input');
        const topTextInput = document.getElementById('top-text');
        const bottomTextInput = document.getElementById('bottom-text');
        const fontSizeInput = document.getElementById('font-size');
        const textColorInput = document.getElementById('text-color');
        const outlineColorInput = document.getElementById('outline-color');
        
        const canvas = document.getElementById('meme-canvas');
        const ctx = canvas.getContext('2d');
        const placeholderText = document.getElementById('placeholder-text');
        const dlBtn = document.getElementById('dl-btn');

        // State
        let currentImage = null;

        // --- CORE LOGIC ---

        function handleImageUpload(e) {
            const file = e.target.files[0];
            if(!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                currentImage = new Image();
                currentImage.onload = () => {
                    // Image loaded successfully
                    placeholderText.classList.add('hidden');
                    canvas.classList.remove('hidden');
                    dlBtn.disabled = false;
                    drawMeme();
                };
                currentImage.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        function drawMeme() {
            if (!currentImage) return;

            // 1. Set Canvas Dimensions to match image
            canvas.width = currentImage.width;
            canvas.height = currentImage.height;

            // 2. Draw Image
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(currentImage, 0, 0);

            // 3. Configure Text Styles
            const fontSize = fontSizeInput.value;
            ctx.font = `900 ${fontSize}px Impact, "Arial Black", sans-serif`;
            ctx.textAlign = 'center';
            
            ctx.fillStyle = textColorInput.value;
            ctx.strokeStyle = outlineColorInput.value;
            // Outline thickness scales relative to font size for consistency
            ctx.lineWidth = fontSize / 15;
            ctx.lineJoin = 'round'; // Smooth corners on outline

            // 4. Draw Top Text
            const topText = topTextInput.value.toUpperCase();
            if (topText) {
                ctx.textBaseline = 'top';
                // Draw stroke first (behind fill)
                ctx.strokeText(topText, canvas.width / 2, 20);
                ctx.fillText(topText, canvas.width / 2, 20);
            }

            // 5. Draw Bottom Text
            const bottomText = bottomTextInput.value.toUpperCase();
            if (bottomText) {
                ctx.textBaseline = 'bottom';
                // Draw stroke first
                ctx.strokeText(bottomText, canvas.width / 2, canvas.height - 20);
                ctx.fillText(bottomText, canvas.width / 2, canvas.height - 20);
            }
        }

        function downloadMeme() {
            if(!currentImage) return;
            
            // Convert canvas data to a PNG blob
            const imageURL = canvas.toDataURL("image/png");
            
            // Create temporary link to trigger download
            const link = document.createElement('a');
            link.download = "meme.png";
            link.href = imageURL;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // --- LISTENERS ---
        imageInput.addEventListener('change', handleImageUpload);
        
        // Redraw whenever any input changes
        [topTextInput, bottomTextInput, fontSizeInput, textColorInput, outlineColorInput].forEach(input => {
            input.addEventListener('input', drawMeme);
        });

    </script>
</body>
</html>