<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Obfuscator | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono-font { font-family: 'JetBrains Mono', monospace; }

        /* Custom Input */
        .cyber-input {
            background: #111827;
            border: 2px solid #374151;
            transition: all 0.2s;
            color: white;
        }
        .cyber-input:focus {
            outline: none;
            border-color: #10b981; /* Emerald */
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
            background: #1f2937;
        }

        /* Result Card Animation */
        .result-card {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }
        @keyframes slideIn { to { opacity: 1; transform: translateY(0); } }

        /* Copy Button Icon Shift */
        .copy-btn svg { transition: transform 0.2s; }
        .copy-btn:active svg { transform: scale(0.9); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center py-8">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-5 flex flex-col gap-6">
                
                <div>
                    <h1 class="text-3xl font-bold text-emerald-400 mb-2">Email Obfuscator</h1>
                    <p class="text-gray-400 text-sm">Protect your inbox from scrapers.</p>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Target Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input type="email" id="email-input" class="cyber-input w-full rounded-xl pl-10 pr-4 py-3 font-bold" placeholder="name@example.com" oninput="generate()">
                    </div>
                </div>

                <div class="bg-gray-800/50 p-4 rounded-2xl border border-gray-700/50">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">How it works</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Spam bots scrape websites looking for the <code>mailto:</code> tag or simple text patterns like <code>@</code>. This tool scrambles the code so humans can read it, but basic bots cannot.
                    </p>
                </div>

            </div>

            <div class="lg:col-span-7 flex flex-col gap-4">
                
                <div id="card-entities" class="result-card hidden bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white text-sm">Level 1: Decimal Encoding</h3>
                            <p class="text-xs text-gray-500">Encodes characters as HTML entities.</p>
                        </div>
                        <span class="text-xs font-bold text-yellow-500 bg-yellow-500/10 px-2 py-1 rounded">Basic</span>
                    </div>
                    <div class="p-4 relative group">
                        <textarea id="out-entities" class="w-full bg-transparent text-gray-400 font-mono text-xs resize-none focus:outline-none custom-scrollbar" rows="3" readonly></textarea>
                        <button onclick="copyToClipboard('out-entities')" class="copy-btn absolute top-4 right-4 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                </div>

                <div id="card-js" class="result-card hidden bg-gray-800 rounded-2xl border border-emerald-500/30 overflow-hidden shadow-lg shadow-emerald-500/10">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white text-sm">Level 2: JavaScript Injection</h3>
                            <p class="text-xs text-gray-500">Assembles the email only when the page loads.</p>
                        </div>
                        <span class="text-xs font-bold text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded">Recommended</span>
                    </div>
                    <div class="p-4 relative group">
                        <textarea id="out-js" class="w-full bg-transparent text-emerald-300 font-mono text-xs resize-none focus:outline-none custom-scrollbar" rows="4" readonly></textarea>
                        <button onclick="copyToClipboard('out-js')" class="copy-btn absolute top-4 right-4 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                </div>

                <div id="card-text" class="result-card hidden bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white text-sm">Level 3: Human Readable</h3>
                            <p class="text-xs text-gray-500">User must manually replace [at] and [dot].</p>
                        </div>
                        <span class="text-xs font-bold text-blue-400 bg-blue-400/10 px-2 py-1 rounded">Fallback</span>
                    </div>
                    <div class="p-4 relative group">
                        <textarea id="out-text" class="w-full bg-transparent text-gray-400 font-mono text-xs resize-none focus:outline-none custom-scrollbar" rows="1" readonly></textarea>
                        <button onclick="copyToClipboard('out-text')" class="copy-btn absolute top-4 right-4 p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                </div>

                <div id="empty-state" class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-700 rounded-2xl text-gray-600">
                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="font-bold uppercase tracking-widest text-sm opacity-50">Enter email to generate code</p>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        // DOM Elements
        const emailInput = document.getElementById('email-input');
        const emptyState = document.getElementById('empty-state');
        const cardEntities = document.getElementById('card-entities');
        const cardJs = document.getElementById('card-js');
        const cardText = document.getElementById('card-text');
        
        const outEntities = document.getElementById('out-entities');
        const outJs = document.getElementById('out-js');
        const outText = document.getElementById('out-text');

        function generate() {
            const email = emailInput.value.trim();

            if (!email || !validateEmail(email)) {
                emptyState.classList.remove('hidden');
                cardEntities.classList.add('hidden');
                cardJs.classList.add('hidden');
                cardText.classList.add('hidden');
                return;
            }

            // Valid email found, hide empty state
            emptyState.classList.add('hidden');
            cardEntities.classList.remove('hidden');
            cardJs.classList.remove('hidden');
            cardText.classList.remove('hidden');

            // 1. Entities
            const entities = encodeEntities(email);
            outEntities.value = `<a href="mailto:${entities}">${entities}</a>`;

            // 2. JS Injection
            const [user, domain] = email.split('@');
            const jsCode = `<script>
    var u = "${user}";
    var d = "${domain}";
    document.write('<a href="mailto:' + u + '@' + d + '">' + u + '@' + d + '</a>');
<\/script>`;
            outJs.value = jsCode;

            // 3. Text
            const textSafe = email.replace('@', ' [at] ').replace('.', ' [dot] ');
            outText.value = textSafe;
        }

        function encodeEntities(str) {
            let encoded = '';
            for (let i = 0; i < str.length; i++) {
                // Randomly choose decimal or hex to make it harder for regex
                if (Math.random() > 0.5) {
                    encoded += '&#' + str.charCodeAt(i) + ';';
                } else {
                    encoded += '&#x' + str.charCodeAt(i).toString(16) + ';';
                }
            }
            return encoded;
        }

        function validateEmail(email) {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        }

        function copyToClipboard(id) {
            const el = document.getElementById(id);
            el.select();
            el.setSelectionRange(0, 99999); // Mobile
            navigator.clipboard.writeText(el.value).then(() => {
                // Simple feedback (could be improved)
                const originalBg = el.style.backgroundColor;
                el.style.backgroundColor = "rgba(16, 185, 129, 0.1)"; // Green tint
                setTimeout(() => el.style.backgroundColor = "transparent", 200);
            });
        }
    </script>
</body>
</html>