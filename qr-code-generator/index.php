<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Styling for Color Inputs */
        input[type="color"] {
            -webkit-appearance: none;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: 2px solid #374151; /* Gray-700 border */
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; }

        /* Custom Select styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            appearance: none;
        }

        /* Preview Container Shadow */
        #qr-preview-box {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        /* Custom File Input Styling */
        .file-upload-label {
            cursor: pointer;
            transition: all 0.2s;
        }
        .file-upload-label:hover { border-color: #f59e0b; color: #f59e0b; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 space-y-6">
                
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-amber-400">QR Generator</h1>
                    <p class="text-gray-400 text-sm">Create custom, styled QR codes.</p>
                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700">
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Content (URL or Text)</label>
                    <textarea id="qr-content" class="w-full h-24 bg-gray-900 border border-gray-600 rounded-xl p-3 text-white focus:border-amber-500 outline-none resize-none custom-scrollbar placeholder-gray-600" placeholder="https://yourwebsite.com">https://dibberlab.com</textarea>
                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700 grid grid-cols-2 gap-6">
                    
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-sm font-bold text-white uppercase mb-3 border-b border-gray-700 pb-1">Colors</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 bg-gray-900 rounded-lg">
                                <label class="text-xs font-bold text-gray-400">Dots Color</label>
                                <input type="color" id="color-dots" value="#111827">
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-900 rounded-lg">
                                <label class="text-xs font-bold text-gray-400">Background</label>
                                <input type="color" id="color-bg" value="#ffffff">
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-sm font-bold text-white uppercase mb-3 border-b border-gray-700 pb-1">Shapes</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Dot Style</label>
                                <select id="style-dots" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                                    <option value="square">Square (Classic)</option>
                                    <option value="dots">Dots</option>
                                    <option value="rounded" selected>Rounded</option>
                                    <option value="extra-rounded">Extra Rounded</option>
                                    <option value="classy">Classy</option>
                                    <option value="classy-rounded">Classy Rounded</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Corner Style</label>
                                <select id="style-corners" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-2 text-white text-sm focus:border-amber-500 outline-none">
                                    <option value="square">Square</option>
                                    <option value="dot">Dot</option>
                                    <option value="extra-rounded" selected>Extra Rounded</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700">
                    <h3 class="text-sm font-bold text-white uppercase mb-3 border-b border-gray-700 pb-1">Logo (Optional)</h3>
                    
                    <div class="flex items-center gap-4">
                        <label class="file-upload-label flex-grow flex items-center justify-center px-4 py-3 bg-gray-900 text-gray-400 rounded-xl border-2 border-dashed border-gray-600 hover:border-amber-500 transition-colors cursor-pointer group">
                            <svg class="w-6 h-6 mr-2 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span id="file-name" class="text-sm font-bold">Upload Image</span>
                            <input type='file' id="logo-input" class="hidden" accept="image/png, image/jpeg, image/svg+xml" />
                        </label>
                        
                        <button onclick="clearLogo()" class="px-3 py-3 bg-gray-900 hover:bg-red-900/30 text-gray-400 hover:text-red-400 rounded-xl border border-gray-600 hover:border-red-500 transition-all" title="Clear Logo">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                </div>

            </div>

            <div class="lg:col-span-5 flex flex-col items-center justify-start lg:sticky lg:top-8">
                
                <div id="qr-preview-box" class="bg-white p-8 rounded-[2.5rem] mb-6 flex items-center justify-center">
                    <div id="qr-code-container"></div>
                </div>

                <div class="w-full max-w-xs flex gap-3">
                    
                    <div class="relative w-1/3">
                        <select id="download-ext" class="w-full h-full bg-gray-800 border border-gray-600 rounded-xl pl-3 pr-8 text-white font-bold appearance-none cursor-pointer hover:bg-gray-700 transition-colors focus:outline-none focus:border-amber-500">
                            <option value="png">PNG</option>
                            <option value="svg">SVG</option>
                            <option value="jpeg">JPG</option>
                            <option value="webp">WEBP</option>
                        </select>
                    </div>

                    <button id="download-btn" class="flex-grow py-4 bg-amber-500 hover:bg-amber-400 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-amber-500/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Save
                    </button>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const qrContent = document.getElementById('qr-content');
        const colorDots = document.getElementById('color-dots');
        const colorBg = document.getElementById('color-bg');
        const styleDots = document.getElementById('style-dots');
        const styleCorners = document.getElementById('style-corners');
        const logoInput = document.getElementById('logo-input');
        const fileNameLabel = document.getElementById('file-name');
        const qrContainer = document.getElementById('qr-code-container');
        const downloadBtn = document.getElementById('download-btn');
        const downloadExt = document.getElementById('download-ext');

        // State
        let logoData = null;
        let qrCode;

        // Initialize QR Code Styling Library
        function initQR() {
            qrCode = new QRCodeStyling({
                width: 300,
                height: 300,
                type: "canvas",
                data: qrContent.value,
                image: "",
                dotsOptions: {
                    color: colorDots.value,
                    type: styleDots.value
                },
                backgroundOptions: {
                    color: colorBg.value,
                },
                imageOptions: {
                    crossOrigin: "anonymous",
                    margin: 10,
                    imageSize: 0.4
                },
                cornersSquareOptions: {
                    type: styleCorners.value,
                    color: colorDots.value
                },
                cornersDotOptions: {
                     color: colorDots.value
                }
            });

            qrCode.append(qrContainer);
        }

        function updateQR() {
            const options = {
                data: qrContent.value || " ",
                image: logoData,
                dotsOptions: {
                    color: colorDots.value,
                    type: styleDots.value
                },
                backgroundOptions: {
                    color: colorBg.value,
                },
                cornersSquareOptions: {
                    type: styleCorners.value,
                    color: colorDots.value
                },
                cornersDotOptions: {
                     color: colorDots.value
                }
            };
            qrCode.update(options);
        }

        // Logo Handling
        logoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                fileNameLabel.textContent = file.name.substring(0, 20) + (file.name.length > 20 ? '...' : '');
                const reader = new FileReader();
                reader.onload = () => {
                    logoData = reader.result;
                    updateQR();
                }
                reader.readAsDataURL(file);
            }
        });

        function clearLogo() {
            logoInput.value = '';
            logoData = null;
            fileNameLabel.textContent = "Upload Image";
            updateQR();
        }

        // Event Listeners for live updates
        qrContent.addEventListener('input', updateQR);
        colorDots.addEventListener('input', updateQR);
        colorBg.addEventListener('input', updateQR);
        styleDots.addEventListener('change', updateQR);
        styleCorners.addEventListener('change', updateQR);

        // Download Action
        downloadBtn.addEventListener('click', () => {
            const ext = downloadExt.value;
            qrCode.download({ name: "qr-code", extension: ext });
        });

        // Init on load
        initQR();

    </script>
</body>
</html>