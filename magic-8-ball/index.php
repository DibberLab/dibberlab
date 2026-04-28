<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic 8 Ball | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* THE BALL */
        .eight-ball {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at 30% 30%, #444, #000);
            border-radius: 50%;
            position: relative;
            box-shadow: 
                0 20px 50px rgba(0,0,0,0.5),
                inset 0 -10px 20px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.1s;
        }

        /* Inner Window */
        .window-rim {
            width: 160px;
            height: 160px;
            background: #222;
            border-radius: 50%;
            box-shadow: 
                inset 0 5px 10px rgba(0,0,0,0.8),
                0 1px 2px rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* The Liquid */
        .liquid {
            width: 140px;
            height: 140px;
            background: radial-gradient(circle at 50% 50%, #1e3a8a, #0f172a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 20px #000;
            position: relative;
        }

/* The Triangle Die */
        .triangle {
            width: 100px;
            height: 90px;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            background: #2563eb; /* Blue-600 */
            display: flex;
            align-items: center;
            justify-content: center; /* Center horizontally */
            text-align: center;
            
            /* KEY FIXES BELOW */
            padding-top: 35px; /* Push text down to the wide part */
            padding-left: 4px;
            padding-right: 4px;
            padding-bottom: 5px;
            
            opacity: 0;
            transform: scale(0.5) translateY(20px);
            transition: all 1s ease-out;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
        }
        
        .answer-text {
            color: #bfdbfe; /* Blue-200 */
            /* Shrink font to fit long phrases */
            font-size: 0.55rem; 
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.1;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            
            /* Ensure text wraps nicely */
            width: 100%;
            word-wrap: break-word;
        }

        /* Animations */
        .shaking {
            animation: shake 0.5s infinite linear;
        }
        
        @keyframes shake {
            0% { transform: translate(2px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(0px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(2px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(2px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
        }

        /* Reveal Animation */
        .reveal {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        /* Glow effect on the 8 (back side simulation) */
        .number-eight {
            position: absolute;
            font-size: 10rem;
            font-weight: 900;
            color: rgba(255,255,255,0.05);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Input styling */
        .mystic-input {
            background: transparent;
            border: none;
            border-bottom: 2px solid #4b5563;
            color: #e5e7eb;
            font-size: 1.25rem;
            text-align: center;
        }
        .mystic-input:focus {
            outline: none;
            border-color: #60a5fa;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center justify-center py-8">
        
        <div class="relative mb-12">
            
            <div id="ball" class="eight-ball cursor-pointer" onclick="askOracle()">
                
                <div class="number-eight">8</div>

                <div class="window-rim z-10">
                    <div class="liquid">
                        <div id="die" class="triangle">
                            <span id="answer" class="answer-text">ASK<br>ME</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-48 h-8 bg-black/50 blur-xl rounded-full pointer-events-none"></div>

        </div>

        <div class="w-full max-w-md text-center space-y-6 z-10">
            
            <h1 class="text-3xl font-bold text-blue-400">Magic 8 Ball</h1>
            
            <div class="relative">
                <input type="text" id="question-input" class="mystic-input w-full pb-2" placeholder="Type your yes/no question...">
            </div>

            <button onclick="askOracle()" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full shadow-lg shadow-blue-600/20 transition-transform hover:-translate-y-1 active:scale-95">
                Reveal Answer
            </button>
            
            <p class="text-xs text-gray-500 mt-4">
                Concentrate deeply and click the ball or button.
            </p>

        </div>

    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

<script>
        // DOM Elements
        const ball = document.getElementById('ball');
        const die = document.getElementById('die');
        const answerText = document.getElementById('answer');
        const questionInput = document.getElementById('question-input');

        // State
        let isShaking = false;

        // The 20 Standard Answers
        const ANSWERS = [
            // Affirmative
            "It is certain",
            "It is decidedly so",
            "Without a doubt",
            "Yes definitely",
            "You may rely on it",
            "As I see it, yes",
            "Most likely",
            "Outlook good",
            "Yes",
            "Signs point to yes",
            // Non-Committal
            "Reply hazy, try again",
            "Ask again later",
            "Better not tell you now",
            "Cannot predict now",
            "Concentrate and ask again",
            // Negative
            "Don't count on it",
            "My reply is no",
            "My sources say no",
            "Outlook not so good",
            "Very doubtful"
        ];

        function askOracle() {
            if (isShaking) return;
            
            isShaking = true;

            // 1. Reset state (Hide answer)
            // FIX: We simply remove the class. We do NOT set inline styles.
            die.classList.remove('reveal');

            // 2. Start Shake
            ball.classList.add('shaking');

            // 3. Wait for shake to finish
            setTimeout(() => {
                ball.classList.remove('shaking');
                showAnswer();
                isShaking = false;
            }, 1000); // 1 second shake
        }

        function showAnswer() {
            // Pick Random
            const randIndex = Math.floor(Math.random() * ANSWERS.length);
            const text = ANSWERS[randIndex];
            
            answerText.innerHTML = text;

            // Show animation
            die.classList.add('reveal');
        }

        // Support Enter Key
        questionInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') askOracle();
        });

        // Initial state
        setTimeout(() => {
            die.classList.add('reveal');
        }, 500);

    </script>
</body>
</html>