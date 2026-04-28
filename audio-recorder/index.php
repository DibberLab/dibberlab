<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Recorder | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Record Button Pulse */
        .record-btn-shadow {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Status Indicator */
        .status-dot {
            width: 8px; height: 8px;
            background-color: #374151;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .status-dot.recording {
            background-color: #ef4444;
            box-shadow: 0 0 10px #ef4444;
            animation: blink 1s infinite;
        }

        @keyframes blink { 50% { opacity: 0.5; } }

        /* Canvas */
        canvas {
            width: 100%;
            height: 100%;
        }

        /* Custom Audio Player Style */
        audio {
            width: 100%;
            height: 40px;
            border-radius: 9999px;
            margin-top: 10px;
        }
        
        audio::-webkit-media-controls-panel { background-color: #374151; }
        audio::-webkit-media-controls-current-time-display,
        audio::-webkit-media-controls-time-remaining-display { color: white; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-2xl mx-auto flex flex-col items-center">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-rose-500">Voice Memo</h1>
                <p class="text-center text-gray-400 text-sm">Record, visualize, and download audio.</p>
            </div>

            <div class="w-full bg-gray-800 rounded-3xl border border-gray-700 shadow-2xl p-6 md:p-8 relative overflow-hidden">
                
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2 bg-black/30 px-3 py-1 rounded-full">
                        <div id="status-dot" class="status-dot"></div>
                        <span id="status-text" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ready</span>
                    </div>
                    <div id="timer" class="font-mono text-2xl font-bold text-white tracking-widest">00:00</div>
                </div>

                <div class="w-full h-32 bg-black rounded-xl border border-gray-700 mb-8 relative overflow-hidden">
                    <canvas id="visualizer" width="600" height="128"></canvas>
                    
                    <div class="absolute top-1/2 left-0 w-full h-px bg-gray-800 pointer-events-none"></div>
                </div>

                <div class="flex justify-center items-center gap-8 mb-6">
                    
                    <button onclick="resetRecorder()" id="btn-trash" class="text-gray-500 hover:text-red-400 transition-colors p-4 rounded-full hover:bg-white/5 disabled:opacity-30 disabled:pointer-events-none" disabled>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>

                    <button id="record-btn" onclick="toggleRecording()" class="w-20 h-20 bg-rose-500 hover:bg-rose-400 text-white rounded-full flex items-center justify-center transition-all transform hover:scale-105 shadow-lg shadow-rose-500/30">
                        <svg id="icon-mic" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                        <div id="icon-stop" class="w-8 h-8 bg-white rounded-md hidden"></div>
                    </button>

                    <a id="btn-download" class="text-gray-500 hover:text-emerald-400 transition-colors p-4 rounded-full hover:bg-white/5 opacity-30 pointer-events-none flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>

                </div>

                <div id="playback-area" class="hidden animate-fade-in border-t border-gray-700 pt-6">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Review Recording</label>
                    <audio id="audio-player" controls></audio>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const recordBtn = document.getElementById('record-btn');
        const iconMic = document.getElementById('icon-mic');
        const iconStop = document.getElementById('icon-stop');
        const statusDot = document.getElementById('status-dot');
        const statusText = document.getElementById('status-text');
        const timerEl = document.getElementById('timer');
        const btnTrash = document.getElementById('btn-trash');
        const btnDownload = document.getElementById('btn-download');
        const playbackArea = document.getElementById('playback-area');
        const audioPlayer = document.getElementById('audio-player');
        const canvas = document.getElementById('visualizer');
        const canvasCtx = canvas.getContext("2d");

        // Audio Context
        let mediaRecorder;
        let audioChunks = [];
        let audioContext;
        let analyser;
        let dataArray;
        let source;
        let stream;
        
        // State
        let isRecording = false;
        let startTime;
        let timerInterval;
        let animationId;

        // --- CORE LOGIC ---

        async function toggleRecording() {
            if (!isRecording) {
                // START RECORDING
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    startRecording(stream);
                } catch (err) {
                    alert("Microphone access denied or not available.");
                    console.error(err);
                }
            } else {
                // STOP RECORDING
                stopRecording();
            }
        }

        function startRecording(stream) {
            isRecording = true;
            
            // 1. Setup MediaRecorder
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                
                // Setup Player
                audioPlayer.src = audioUrl;
                playbackArea.classList.remove('hidden');

                // Setup Download
                btnDownload.href = audioUrl;
                btnDownload.download = `recording_${new Date().getTime()}.wav`;
                btnDownload.classList.remove('opacity-30', 'pointer-events-none');
            };

            mediaRecorder.start();

            // 2. Setup Visualizer
            setupVisualizer(stream);

            // 3. UI Updates
            recordBtn.classList.add('record-btn-shadow');
            iconMic.classList.add('hidden');
            iconStop.classList.remove('hidden');
            statusDot.classList.add('recording');
            statusText.textContent = "Recording";
            statusText.classList.add('text-rose-500');
            statusText.classList.remove('text-gray-400');
            btnTrash.disabled = true;
            btnDownload.classList.add('opacity-30', 'pointer-events-none');
            playbackArea.classList.add('hidden');

            // 4. Timer
            startTime = Date.now();
            timerInterval = setInterval(updateTimer, 1000);
        }

        function stopRecording() {
            isRecording = false;
            mediaRecorder.stop();
            
            // Stop Visualizer / Stream
            cancelAnimationFrame(animationId);
            stream.getTracks().forEach(track => track.stop());

            // UI Updates
            recordBtn.classList.remove('record-btn-shadow');
            iconMic.classList.remove('hidden');
            iconStop.classList.add('hidden');
            statusDot.classList.remove('recording');
            statusText.textContent = "Finished";
            statusText.classList.remove('text-rose-500');
            statusText.classList.add('text-gray-400');
            clearInterval(timerInterval);
            
            btnTrash.disabled = false;
            
            // Clear canvas
            canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function resetRecorder() {
            if(confirm("Discard current recording?")) {
                audioChunks = [];
                audioPlayer.src = "";
                playbackArea.classList.add('hidden');
                btnDownload.classList.add('opacity-30', 'pointer-events-none');
                btnTrash.disabled = true;
                timerEl.textContent = "00:00";
                statusText.textContent = "Ready";
            }
        }

        function updateTimer() {
            const diff = Date.now() - startTime;
            const secs = Math.floor((diff / 1000) % 60);
            const mins = Math.floor((diff / (1000 * 60)) % 60);
            timerEl.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // --- VISUALIZER ---

        function setupVisualizer(stream) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioContext.createAnalyser();
            source = audioContext.createMediaStreamSource(stream);
            source.connect(analyser);

            analyser.fftSize = 2048;
            const bufferLength = analyser.frequencyBinCount;
            dataArray = new Uint8Array(bufferLength);

            drawVisualizer();
        }

        function drawVisualizer() {
            if (!isRecording) return;
            animationId = requestAnimationFrame(drawVisualizer);

            analyser.getByteTimeDomainData(dataArray);

            canvasCtx.fillStyle = '#000000'; // Black BG
            canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

            canvasCtx.lineWidth = 2;
            canvasCtx.strokeStyle = '#f43f5e'; // Rose-500

            canvasCtx.beginPath();

            const sliceWidth = canvas.width * 1.0 / dataArray.length;
            let x = 0;

            for(let i = 0; i < dataArray.length; i++) {
                const v = dataArray[i] / 128.0;
                const y = v * canvas.height / 2;

                if(i === 0) {
                    canvasCtx.moveTo(x, y);
                } else {
                    canvasCtx.lineTo(x, y);
                }

                x += sliceWidth;
            }

            canvasCtx.lineTo(canvas.width, canvas.height / 2);
            canvasCtx.stroke();
        }

    </script>
</body>
</html>