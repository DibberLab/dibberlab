<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dibber Lab | Free Web Tools & Utilities</title>
    <meta name="description" content="A collection of free, privacy-focused web tools for musicians, developers, and everyday problems. No ads, just utility.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="/dibber-header.js?v=1.1"></script>
    
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <header class="bg-gray-800 border-b border-gray-700 pt-12 pb-8 px-4 relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-[-50%] left-[20%] w-96 h-96 bg-emerald-500 rounded-full blur-[100px]"></div>
            <div class="absolute top-[-20%] right-[20%] w-96 h-96 bg-amber-500 rounded-full blur-[100px]"></div>
        </div>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-white mb-4">
                Dibber <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-amber-400">Lab</span>
            </h1>
            <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                A collection of simple, privacy-focused web tools for musicians, developers, and creative minds. No ads. No bloat.
            </p>

            <div class="relative max-w-lg mx-auto mb-6">
                <input type="text" id="search-input" placeholder="Search for a tool (e.g. 'BPM', 'JSON', 'Color')..." 
                    class="w-full bg-gray-900 border border-gray-600 text-white rounded-full py-4 px-6 pl-12 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent shadow-lg text-lg">
                <svg class="w-6 h-6 text-gray-500 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <div class="hidden md:flex justify-center flex-wrap gap-2 text-sm font-medium text-gray-400" id="category-filters">
                </div>
        </div>
    </header>

    <main class="flex-grow px-4 py-8">
        <div class="max-w-6xl mx-auto" id="tools-container">
            </div>

        <div id="no-results" class="hidden text-center py-20">
            <p class="text-2xl text-gray-500">No tools found matching that search.</p>
            <button onclick="document.getElementById('search-input').value = ''; filterTools();" class="mt-4 text-amber-400 hover:underline">Clear Search</button>
        </div>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-12 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h3 class="text-2xl font-bold mb-4">Support the Lab</h3>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">
                These tools are free and open source. If they saved you some time, consider buying me a coffee to keep the servers running.
            </p>
            
            <a href="https://buymeacoffee.com/andrewmich9" target="_blank" class="inline-block transition-transform hover:scale-105 duration-200">
                <img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" style="height: 50px !important; width: 217px !important;">
            </a>

            <div class="mt-12 text-gray-600 text-sm">
                &copy; <span id="year"></span> Dibber Lab. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();

        // --- THE DATABASE ---
        // This is where you configure your folders.
        // 'link' is the folder name (e.g. dibberlab.me/guitar-tuner)
        const toolsDatabase = [
            // --- MUSIC ---
            { name: "Guitar Tuner", category: "Music", icon: "🎸", link: "/guitar-tuner", desc: "Chromatic tuner with noise suppression." },
            { name: "Visual Metronome", category: "Music", icon: "⏱️", link: "/visual-metronome", desc: "Precise BPM clicks with visual flash." },
            { name: "BPM Tapper", category: "Music", icon: "👆", link: "/bpm-tapper", desc: "Tap along to find the tempo of a song." },
            { name: "Scale Finder", category: "Music", icon: "🎹", link: "/scale-finder", desc: "Find notes in any musical scale." },
            { name: "Circle of Fifths", category: "Music", icon: "🎼", link: "/circle-of-fifths", desc: "Interactive songwriting reference." },
            { name: "Tone Generator", category: "Music", icon: "🔊", link: "/frequency-generator", desc: "Generate pure sine/square waves." },
            { name: "Noise Generator", category: "Music", icon: "🌊", link: "/noise-generator", desc: "White, Pink, and Brown noise for focus." },
            { name: "Chord Library", category: "Music", icon: "🎵", link: "/chord-library", desc: "Guitar and piano chord voicings." },
            { name: "Delay Calculator", category: "Music", icon: "🎛️", link: "/delay-calculator", desc: "Convert BPM to milliseconds." },
            { name: "Sight Reader", category: "Music", icon: "👀", link: "/sight-reading", desc: "Flashcards for reading sheet music." },
            { name: "Drum Machine", category: "Music", icon: "🥁", link: "/simple-drum-machine", desc: "Basic 16-step beat sequencer." },

            // --- DEV TOOLS ---
            { name: "JSON Formatter", category: "Developer", icon: "🔮", link: "/json-formatter", desc: "Validate and beautify JSON data." },
            { name: "Box Shadows", category: "Developer", icon: "👻", link: "/css-box-shadow", desc: "CSS shadow generator." },
            { name: "CSS Gradients", category: "Developer", icon: "🌈", link: "/css-gradient", desc: "Build beautiful gradients visually." },
            { name: "Flexbox Playground", category: "Developer", icon: "📦", link: "/flexbox-playground", desc: "Visual guide to CSS Flexbox." },
            { name: "Base64 Converter", category: "Developer", icon: "🧬", link: "/base64-converter", desc: "Encode/Decode images and text." },
            { name: "Lorem Ipsum", category: "Developer", icon: "📄", link: "/lorem-ipsum", desc: "Generate placeholder text." },
            { name: "RegEx Tester", category: "Developer", icon: "🔎", link: "/regex-tester", desc: "Test regular expressions." },
            { name: "Markdown Editor", category: "Developer", icon: "📝", link: "/markdown-editor", desc: "Split-screen Markdown preview." },
            { name: "SQL Formatter", category: "Developer", icon: "🗄️", link: "/sql-formatter", desc: "Beautify messy SQL queries." },
            { name: "Cron Generator", category: "Developer", icon: "⏰", link: "/cron-generator", desc: "Generate cron job syntax." },
            { name: "UUID Generator", category: "Developer", icon: "🆔", link: "/uuid-generator", desc: "Bulk unique ID creator." },
            { name: "URL Decoder", category: "Developer", icon: "🔗", link: "/url-encoder", desc: "Fix and decode URL strings." },
            { name: "Screen Info", category: "Developer", icon: "🖥️", link: "/screen-resolution", desc: "Check your current viewport size." },
            { name: "Favicon Maker", category: "Developer", icon: "🖼️", link: "/favicon-generator", desc: "Create .ico files from images." },
            { name: "Diff Checker", category: "Developer", icon: "⚖️", link: "/diff-checker", desc: "Compare two text files." },

            // --- DESIGN ---
            { name: "Contrast Checker", category: "Design", icon: "🌗", link: "/contrast-checker", desc: "Check WCAG accessibility contrast." },
            { name: "Palette Gen", category: "Design", icon: "🎨", link: "/palette-generator", desc: "Generate color schemes." },
            { name: "HEX to RGB", category: "Design", icon: "#️⃣", link: "/color-converter", desc: "Convert color formats." },
            { name: "Image Compressor", category: "Design", icon: "🗜️", link: "/image-compressor", desc: "Shrink images locally." },
            { name: "Social Resizer", category: "Design", icon: "📱", link: "/social-resizer", desc: "Crop images for social media." },
            { name: "Ascii Art", category: "Design", icon: "💻", link: "/ascii-generator", desc: "Convert text/images to ASCII." },
            { name: "Golden Ratio", category: "Design", icon: "🌀", link: "/golden-ratio", desc: "Calculate perfect proportions." },
            { name: "Font Pairer", category: "Design", icon: "Aa", link: "/font-pairer", desc: "Find Google Fonts that match." },

            // --- WRITING ---
            { name: "Word Counter", category: "Writing", icon: "📊", link: "/word-counter", desc: "Count words and characters." },
            { name: "Case Converter", category: "Writing", icon: "🔠", link: "/case-converter", desc: "UPPER, lower, CamelCase." },
            { name: "Readability", category: "Writing", icon: "👓", link: "/readability-score", desc: "Check grade level of text." },
            { name: "Dedupe List", category: "Writing", icon: "✂️", link: "/duplicate-remover", desc: "Remove duplicate lines." },
            { name: "Alphabetizer", category: "Writing", icon: "🔤", link: "/list-sorter", desc: "Sort lists A-Z or Z-A." },
            { name: "Password Gen", category: "Writing", icon: "🔒", link: "/password-generator", desc: "Create secure passwords." },
            { name: "Find & Replace", category: "Writing", icon: "🔍", link: "/find-replace", desc: "Bulk text replacement tool." },
            { name: "Emoji Picker", category: "Writing", icon: "😀", link: "/emoji-picker", desc: "Search and copy emojis." },
            { name: "Upside Down Text", category: "Writing", icon: "🙃", link: "/flip-text", desc: "dils ʇxǝʇ uɹnʇ." },
            { name: "Slug Generator", category: "Writing", icon: "🐌", link: "/slug-generator", desc: "URL-friendly-text-maker." },
            { name: "Writing Studio", category: "Writing", icon: "📺", link: "/writing-studio", desc: "All writing functions combined" },

            // --- MATH ---
            { name: "Percentage Calc", category: "Math", icon: "%", link: "/percentage-calculator", desc: "Simple percentage math." },
            { name: "Tip Splitter", category: "Math", icon: "🧾", link: "/tip-calculator", desc: "Calculate tips per person." },
            { name: "Time Calculator", category: "Math", icon: "⌛", link: "/time-duration", desc: "Add hours and minutes." },
            { name: "Days Calc", category: "Math", icon: "📅", link: "/days-between", desc: "Count days between dates." },
            { name: "Age Calculator", category: "Math", icon: "🎂", link: "/age-calculator", desc: "Calculate exact age." },
            { name: "BMI Calculator", category: "Math", icon: "⚖️", link: "/bmi-calculator", desc: "Body Mass Index check." },
            { name: "Loan Calc", category: "Math", icon: "🏠", link: "/loan-amortization", desc: "Estimate monthly payments." },
            { name: "Compound Interest", category: "Math", icon: "📈", link: "/compound-interest", desc: "Visualize investment growth." },
            { name: "Unit Converter", category: "Math", icon: "📏", link: "/unit-converter", desc: "Length, Weight, Volume." },
            { name: "Discount Calc", category: "Math", icon: "🏷️", link: "/discount-calculator", desc: "Calculate sale prices." },
            { name: "Aspect Ratio", category: "Math", icon: "📺", link: "/aspect-ratio", desc: "Calculate dimensions." },

            // --- PRODUCTIVITY ---
            { name: "Pomodoro", category: "Productivity", icon: "🍅", link: "/pomodoro-timer", desc: "25/5 focus timer." },
            { name: "Breathing", category: "Productivity", icon: "🧘", link: "/breathing-exercise", desc: "4-7-8 visualizer." },
            { name: "Reaction Test", category: "Productivity", icon: "⚡", link: "/reaction-time", desc: "Test your reflexes." },
            { name: "Typing Test", category: "Productivity", icon: "⌨️", link: "/typing-speed", desc: "Check your WPM." },
            { name: "Decision Wheel", category: "Productivity", icon: "🎡", link: "/decision-wheel", desc: "Spin to decide." },
            { name: "Kanban Board", category: "Productivity", icon: "📋", link: "/simple-kanban", desc: "Simple To-Do list." },
            { name: "World Clock", category: "Productivity", icon: "🌍", link: "/world-clock", desc: "Compare timezones." },
            { name: "Water Tracker", category: "Productivity", icon: "💧", link: "/water-tracker", desc: "Daily hydration log." },
            { name: "Week Number", category: "Productivity", icon: "📆", link: "/week-number", desc: "Current week of the year." },

            // --- GAMES ---
            { name: "Dice Roller", category: "Games", icon: "🎲", link: "/dice-roller", desc: "Roll D6, D20, etc." },
            { name: "Coin Flipper", category: "Games", icon: "🪙", link: "/coin-flip", desc: "Heads or Tails." },
            { name: "Tic Tac Toe", category: "Games", icon: "❌", link: "/tic-tac-toe", desc: "Play vs Computer." },
            { name: "Memory Match", category: "Games", icon: "🧠", link: "/memory-game", desc: "Find the matching pairs." },
            { name: "Sudoku Solver", category: "Games", icon: "🔢", link: "/sudoku-solver", desc: "Solve puzzles instantly." },
            { name: "Bingo Cards", category: "Games", icon: "🎫", link: "/bingo-generator", desc: "Printable bingo sheets." },
            { name: "Scoreboard", category: "Games", icon: "🏆", link: "/simple-scoreboard", desc: "Keep score for games." },
            { name: "RNG", category: "Games", icon: "#️⃣", link: "/random-number", desc: "Random number generator." },
            { name: "R P S", category: "Games", icon: "✊", link: "/rock-paper-scissors", desc: "Rock, Paper, Scissors." },
            
            // --- MORE GAMES ---
            { name: "Hangman", category: "Games", icon: "🔤", link: "/hangman", desc: "Guess the hidden word." },
            { name: "Snake", category: "Games", icon: "🐍", link: "/snake-game", desc: "Classic retro arcade." },
            { name: "Minesweeper", category: "Games", icon: "💣", link: "/minesweeper", desc: "Clear the minefield." },
            { name: "2048", category: "Games", icon: "🧩", link: "/2048-game", desc: "Combine tiles to 2048." },
            { name: "Magic 8 Ball", category: "Games", icon: "🎱", link: "/magic-8-ball", desc: "Ask the future." },
            { name: "Wordle", category: "Games", icon: "🍳", link: "/wordle", desc: "Wordle clone." },

            // --- UTILITY ---
            { name: "QR Generator", category: "Utility", icon: "📱", link: "/qr-code-generator", desc: "Create QR codes." },
            { name: "Barcode Gen", category: "Utility", icon: "║█", link: "/barcode-generator", desc: "Create barcodes." },
            { name: "Cooking Units", category: "Utility", icon: "🍳", link: "/kitchen-converter", desc: "Cups to Grams." },
            { name: "Recipe Scaler", category: "Utility", icon: "🥗", link: "/recipe-scaler", desc: "Double or halve ingredients." },
            { name: "Net Speed", category: "Utility", icon: "🚀", link: "/speed-test", desc: "Estimate internet speed." },
            { name: "My IP", category: "Utility", icon: "📍", link: "/ip-lookup", desc: "Show public IP address." },
            { name: "Morse Code", category: "Utility", icon: "📻", link: "/morse-translator", desc: "Text to dots and dashes." },
            { name: "Braille", category: "Utility", icon: "⠃⠗", link: "/braille-translator", desc: "Text to Braille." },
            { name: "Zodiac Calc", category: "Utility", icon: "♈", link: "/zodiac-calculator", desc: "Find your star sign." },
            { name: "Moon Phase", category: "Utility", icon: "🌑", link: "/moon-phase", desc: "Current lunar cycle." },

            // --- SCIENCE ---
            { name: "Periodic Table", category: "Science", icon: "🧪", link: "/periodic-table", desc: "Interactive element list." },
            { name: "Molar Mass", category: "Science", icon: "🔬", link: "/molar-mass", desc: "Calculate chemical mass." },
            { name: "DNA Transcriber", category: "Science", icon: "🧬", link: "/dna-transcriber", desc: "DNA to RNA converter." },
            { name: "Multiplication", category: "Science", icon: "✖️", link: "/multiplication-table", desc: "Math reference for kids." },
            { name: "Roman Numerals", category: "Science", icon: "🏛️", link: "/roman-numerals", desc: "Convert numbers to Roman." },
            { name: "Binary Conv", category: "Science", icon: "0️⃣", link: "/binary-converter", desc: "Decimal to Binary." },
            { name: "Hex Converter", category: "Science", icon: "🇫", link: "/hex-converter", desc: "Decimal to Hexadecimal." },

            // --- MEDIA ---
            { name: "Exif Viewer", category: "Media", icon: "📷", link: "/exif-viewer", desc: "View photo metadata." },
            { name: "Video Still", category: "Media", icon: "🎞️", link: "/video-to-jpg", desc: "Save frame from video." },
            { name: "Meme Maker", category: "Media", icon: "🤣", link: "/meme-generator", desc: "Add text to images." },
            { name: "GIF Maker", category: "Media", icon: "🖼️", link: "/gif-maker", desc: "Images to GIF." },
            { name: "Audio Recorder", category: "Media", icon: "🎤", link: "/audio-recorder", desc: "Record mic to WAV." },

            // --- NICHE ---
            { name: "Email Hider", category: "Niche", icon: "🛡️", link: "/email-obfuscator", desc: "Hide email from bots." },
            { name: "Clean Tweet", category: "Niche", icon: "🐦", link: "/tweet-spacer", desc: "Format Twitter posts." },
            { name: "Tag Mixer", category: "Niche", icon: "#️⃣", link: "/hashtag-shuffler", desc: "Randomize Instagram tags." },
            { name: "Crypto Price", category: "Niche", icon: "💰", link: "/crypto-ticker", desc: "Live BTC/ETH prices." },
            { name: "Pizza Calc", category: "Niche", icon: "🍕", link: "/pizza-dough", desc: "Dough hydration math." },
            { name: "Letterbox Calc", category: "Niche", icon: "🎬", link: "/letterbox-calculator", desc: "Video aspect ratio bars." },
        ];

        const container = document.getElementById('tools-container');
        const filterContainer = document.getElementById('category-filters');
        const searchInput = document.getElementById('search-input');
        const noResults = document.getElementById('no-results');

        // Extract unique categories for the filter bar
        const categories = [...new Set(toolsDatabase.map(tool => tool.category))];
        
        // Render Filters
        categories.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = "px-3 py-1 bg-gray-800 hover:bg-gray-700 rounded-full border border-gray-700 transition-colors";
            btn.textContent = cat;
            btn.onclick = () => {
                searchInput.value = cat;
                filterTools();
            };
            filterContainer.appendChild(btn);
        });

        // Render Tools Function
        function renderTools(tools) {
            container.innerHTML = ''; // Clear existing
            
            // Group by Category
            const grouped = tools.reduce((acc, tool) => {
                acc[tool.category] = acc[tool.category] || [];
                acc[tool.category].push(tool);
                return acc;
            }, {});

            if (tools.length === 0) {
                noResults.classList.remove('hidden');
                return;
            } else {
                noResults.classList.add('hidden');
            }

            // Create sections for each category
            for (const [category, items] of Object.entries(grouped)) {
                // Category Title
                const catTitle = document.createElement('h2');
                catTitle.className = "text-2xl font-bold mb-4 mt-8 text-amber-400 border-b border-gray-800 pb-2 category-title";
                catTitle.textContent = category;
                container.appendChild(catTitle);

                // Grid for this category
                const grid = document.createElement('div');
                grid.className = "grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8";

                items.forEach(tool => {
                    const card = document.createElement('a');
                    card.href = tool.link;
                    card.className = "tool-card bg-gray-800 p-5 rounded-xl border border-gray-700 block h-full flex flex-col";
                    
                    card.innerHTML = `
                        <div class="text-4xl mb-3">${tool.icon}</div>
                        <h3 class="font-bold text-lg text-white mb-1">${tool.name}</h3>
                        <p class="text-sm text-gray-400 flex-grow">${tool.desc}</p>
                    `;
                    grid.appendChild(card);
                });

                container.appendChild(grid);
            }
        }

        // Search/Filter Function
        function filterTools() {
            const term = searchInput.value.toLowerCase();
            const filtered = toolsDatabase.filter(tool => 
                tool.name.toLowerCase().includes(term) || 
                tool.category.toLowerCase().includes(term) ||
                tool.desc.toLowerCase().includes(term)
            );
            renderTools(filtered);
        }

        // Initialize
        searchInput.addEventListener('input', filterTools);
        renderTools(toolsDatabase);

    </script>
</body>
</html>