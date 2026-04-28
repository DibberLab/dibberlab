<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morse Translator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Morse Ticker Tape Effect */
        #morse-output {
            letter-spacing: 0.1em;
            word-wrap: break-word;
            line-height: 1.8;
            font-variant-ligatures: none;
        }

        /* Active character highlight during playback */
        .char-highlight {
            color: #f59e0b; /* Amber */
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
            background: rgba(245, 158, 11, 0.1);
            border-radius: 2px;
        }

        /* Telegraph Button */
        .telegraph-btn {
            transition: all 0.05s;
            box-shadow: 0 4px 0 #1f2937; /* Fake 3D depth */
        }
        .telegraph-btn:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 #1f2937;
        }

        /* Custom Scrollbar */
        textarea::-webkit-scrollbar, div::-webkit-scrollbar { width: 8px; }
        textarea::-webkit-scrollbar-track, div::-webkit-scrollbar-track { background: #1f2937; }
        textarea::-webkit-scrollbar-thumb, div::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="flex flex-col h-full">