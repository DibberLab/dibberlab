<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base64 Converter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace for data */
        .data-font {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Tab Transitions */
        .tab-btn {
            transition: all 0.2s ease;
            border-bottom: 2px solid transparent;
        }
        .tab-btn.active {
            color: #f59e0b; /* Amber */
            border-color: #f59e0b;
            background-color: rgba(245, 158, 11, 0.1);
        }

        /* Drag and Drop Zone */
        #drop-zone {
            transition: all 0.2s ease;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='12' ry='12' stroke='%234B5563FF' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }
        #drop-zone.drag-active {
            background-color: rgba(16, 185, 129, 0.1); /* Emerald tint */
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='12' ry='12' stroke='%2310B981FF' stroke-width='3' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-5xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">Base64 Converter</h1>
            <p class="text-center text-gray-400 mb-8">Encode and decode text, images, and files.</p>

            <div class="flex border-b border-gray-700 mb-6">
                <button class="tab-btn active flex-1 py-3 font-bold text-lg text-gray-400 hover:text-gray-200" data-mode="encode">
                    Encode (Text & Files)
                </button>
                <button class="tab-btn flex-1 py-3 font-bold text-lg text-gray-400 hover:text-gray-200" data-mode="decode">
                    Decode (Base64 to Text)
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="flex flex-col h-[500px]">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Input</label>
                        <span id="file-name-display" class="text-xs text-emerald-400 font-mono hidden"></span>
                    </div>

                    <textarea id="text-input" class="data-font flex-grow w-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-sm text-gray-300 focus:outline-none focus:border-amber-500 resize-none mb-4 transition-all" placeholder="Type text here to convert..."></textarea>

                    <div id="or-divider" class="flex items-center gap-4 mb-4">
                        <div class="h-px bg-gray-700 flex-grow"></div>
                        <span class="text-xs text-gray-500 font-bold uppercase">OR</span>
                        <div class="h-px bg-gray-700 flex-grow"></div>
                    </div>

                    <div id="drop-zone" class="h-32 rounded-xl flex flex-col items-center justify-center cursor-pointer hover:bg-gray-700/30">
                        <input type="file" id="file-input" class="hidden">
                        <span class="text-3xl mb-2">📁</span>
                        <p class="text-sm text-gray-400 font-bold">Click or Drop File</p>
                        <p class="text-xs text-gray-600 mt-1">Images, PDFs, etc.</p>
                    </div>
                </div>

                <div class="flex flex-col h-[500px]">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Output Result</label>
                        <div class="flex gap-2">
                            <span id="status-msg" class="text-xs text-emerald-400 font-bold uppercase opacity-0 transition-opacity">Copied!</span>
                            <button id="clear-btn" class="text-xs text-gray-500 hover:text-white underline">Clear</button>
                        </div>
                    </div>

                    <div class="relative flex-grow">
                        <textarea id="output-area" readonly class="data-font w-full h-full bg-gray-900 border border-gray-600 rounded-xl p-4 text-sm text-emerald-400 focus:outline-none resize-none" placeholder="Result will appear here..."></textarea>
                        
                        <div id="preview-overlay" class="hidden absolute bottom-4 right-4 max-w-[120px] max-h-[120px] bg-gray-800 p-2 rounded-lg border border-gray-600 shadow-xl">
                            <img id="preview-img" src="" class="w-full h-full object-contain rounded">
                            <div class="text-[10px] text-center text-gray-400 mt-1">Preview</div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button id="copy-btn" class="flex-1 py-3 rounded-xl font-bold bg-amber-600 hover:bg-amber-500 text-white shadow-lg transition-transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            Copy Base64
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // Elements
        const tabs = document.querySelectorAll('.tab-btn');
        const textInput = document.getElementById('text-input');
        const fileInput = document.getElementById('file-input');
        const dropZone = document.getElementById('drop-zone');
        const orDivider = document.getElementById('or-divider');
        const outputArea = document.getElementById('output-area');
        const copyBtn = document.getElementById('copy-btn');
        const clearBtn = document.getElementById('clear-btn');
        const statusMsg = document.getElementById('status-msg');
        const fileNameDisplay = document.getElementById('file-name-display');
        const previewOverlay = document.getElementById('preview-overlay');
        const previewImg = document.getElementById('preview-img');

        let mode = 'encode'; // 'encode' or 'decode'

        // --- TAB LOGIC ---
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // UI Toggle
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Logic Switch
                mode = tab.dataset.mode;
                
                // Reset inputs
                textInput.value = '';
                outputArea.value = '';
                fileNameDisplay.textContent = '';
                fileNameDisplay.classList.add('hidden');
                previewOverlay.classList.add('hidden');

                if (mode === 'encode') {
                    dropZone.classList.remove('hidden');
                    orDivider.classList.remove('hidden');
                    textInput.placeholder = "Type text here to convert...";
                    textInput.style.height = ""; // Reset flex
                } else {
                    dropZone.classList.add('hidden');
                    orDivider.classList.add('hidden');
                    textInput.placeholder = "Paste Base64 string here to decode...";
                    textInput.style.height = "100%"; // Full height for text input
                }
            });
        });

        // --- CONVERSION LOGIC ---

        // 1. Text Conversion
        textInput.addEventListener('input', () => {
            const val = textInput.value;
            if (!val) {
                outputArea.value = '';
                return;
            }

            try {
                if (mode === 'encode') {
                    // Safe Encode (Handles Unicode/Emojis)
                    const encoded = btoa(unescape(encodeURIComponent(val)));
                    outputArea.value = encoded;
                } else {
                    // Safe Decode
                    const decoded = decodeURIComponent(escape(window.atob(val)));
                    outputArea.value = decoded;
                }
                previewOverlay.classList.add('hidden'); // No preview for text
            } catch (e) {
                if (mode === 'decode') {
                    outputArea.value = "Invalid Base64 string.";
                }
            }
        });

        // 2. File Conversion (Encode Only)
        function handleFile(file) {
            if (mode !== 'encode') return;

            const reader = new FileReader();
            
            reader.onload = function(e) {
                const result = e.target.result; // Data URL (data:image/png;base64,....)
                outputArea.value = result;
                
                // Update UI
                textInput.value = ''; // Clear text input to show file priority
                fileNameDisplay.textContent = `File: ${file.name}`;
                fileNameDisplay.classList.remove('hidden');

                // If image, show preview
                if (file.type.startsWith('image/')) {
                    previewImg.src = result;
                    previewOverlay.classList.remove('hidden');
                } else {
                    previewOverlay.classList.add('hidden');
                }
            };

            reader.readAsDataURL(file);
        }

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // --- DRAG & DROP ---
        dropZone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-active'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-active'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) handleFile(files[0]);
        });

        // --- ACTIONS ---
        
        copyBtn.addEventListener('click', () => {
            if (!outputArea.value) return;
            
            outputArea.select();
            document.execCommand('copy');
            if (navigator.clipboard) navigator.clipboard.writeText(outputArea.value);

            statusMsg.classList.remove('opacity-0');
            setTimeout(() => statusMsg.classList.add('opacity-0'), 2000);
        });

        clearBtn.addEventListener('click', () => {
            textInput.value = '';
            outputArea.value = '';
            fileInput.value = '';
            fileNameDisplay.textContent = '';
            fileNameDisplay.classList.add('hidden');
            previewOverlay.classList.add('hidden');
        });

    </script>
</body>
</html>