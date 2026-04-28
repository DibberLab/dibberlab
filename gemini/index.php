<?php
// Use __DIR__ to ensure it looks in the same folder as this script
$env_path = __DIR__ . '/.env';
$env = parse_ini_file($env_path);
$api_key = $env['API_KEY'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Actor Picker</title>
    
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
    // This creates a global variable the browser can see
    window.ENV_CONFIG = {
        API_KEY: "<?php echo $api_key; ?>"
    };
    </script>

    <script type="importmap">
      {
        "imports": {
          "@google/genai": "https://esm.run/@google/genai"
        }
      }
    </script>
    <style>
        :root {
            --primary: #2563eb;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-ai: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f1f5f9; margin: 0; display: flex; height: 100vh; color: var(--text-main); }
        
        #sidebar { width: 320px; background: white; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .search-box { padding: 20px; border-bottom: 1px solid #f1f5f9; }
        input#filter { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box; }

        #button-list { flex: 1; overflow-y: auto; padding: 15px; }
        .actor-btn { 
            width: 100%; text-align: left; padding: 12px; margin-bottom: 8px;
            background: #fff; cursor: pointer; border-radius: 8px;
            border: 1px solid #e2e8f0; transition: all 0.2s;
        }
        .actor-btn:hover { border-color: var(--primary); background: #f0f7ff; }
        .actor-btn.active { background: var(--primary); color: white; border-color: var(--primary); }

        #chat-container { flex: 1; display: flex; flex-direction: column; background: #fff; position: relative; }
        #header { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; background: #fff; }
        
        .instruction-label { font-size: 11px; font-weight: bold; color: var(--text-muted); margin-bottom: 5px; display: block; }
        #instruction-editor { 
            width: 100%; font-family: inherit; font-size: 13px; color: var(--text-main); 
            background: #f8fafc; padding: 10px; border-radius: 6px; 
            border: 1px solid #cbd5e1; border-left: 4px solid var(--primary);
            resize: vertical; min-height: 60px; max-height: 150px; 
            line-height: 1.5; outline: none;
        }

        #messages { flex: 1; overflow-y: auto; padding: 30px; display: flex; flex-direction: column; gap: 20px; }
        .message { max-width: 85%; padding: 15px 25px; border-radius: 12px; font-size: 15px; line-height: 1.6; }
        .user { align-self: flex-end; background: var(--primary); color: white; border-bottom-right-radius: 2px; }
        .ai { align-self: flex-start; background: var(--bg-ai); border: 1px solid #e2e8f0; border-bottom-left-radius: 2px; }

        /* Error Message Style in Chat */
        .error-bubble { background: #fee2e2 !important; border: 1px solid #fecaca !important; color: #991b1b !important; }

        #input-area { padding: 25px; border-top: 1px solid #e2e8f0; display: flex; gap: 10px; background: #fff; align-items: center; }
        #user-input { flex: 1; padding: 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 15px; }
        .action-btn { padding: 12px 20px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.2s; }
        #send-btn { background: var(--primary); color: white; }
        #clear-btn { background: #f1f5f9; color: var(--danger); border: 1px solid #e2e8f0; }

        /* Rendered Markdown Styling */
        .ai h3 { border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-top: 0; }
        .ai pre { background: #1e293b; color: white; padding: 12px; border-radius: 6px; overflow-x: auto; }
        .ai code { font-family: monospace; background: #e2e8f0; padding: 2px 4px; border-radius: 4px; }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="search-box">
        <input type="text" id="filter" placeholder="Find actors..." onkeyup="filterActors()">
    </div>
    <div id="button-list"></div>
</div>

<div id="chat-container">
    <div id="header">
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <h2 id="current-actor-title" style="margin:0;">Select a Role</h2>
            <div id="status-indicator" style="font-size: 11px; font-weight: bold; color: var(--primary); text-align: right; max-width: 50%;">READY</div>
        </div>
        <div style="margin-top: 15px;">
            <label class="instruction-label">ACTIVE SYSTEM PROMPT (EDITABLE)</label>
            <textarea id="instruction-editor" placeholder="Select an actor..."></textarea>
        </div>
    </div>

    <div id="messages"></div>

    <div id="input-area">
        <button id="clear-btn" class="action-btn" onclick="clearChat()">Clear</button>
        <input type="text" id="user-input" placeholder="Type a message and press Enter..." disabled>
        <button id="send-btn" class="action-btn" onclick="sendMessage()" disabled>Send</button>
    </div>
</div>

<script type="module">

    import { GoogleGenAI } from "@google/genai"; // This MUST be here
    // Remove SETTINGS from the import if it's only used for the API key
    import { PROMPTS, SETTINGS } from "./config.js?v=1.3"; 

    // Use the global variable you defined at the top of the file
    console.log("Checking API Key...", window.ENV_CONFIG.API_KEY); 

    const ai = new GoogleGenAI({ apiKey: window.ENV_CONFIG.API_KEY });
    let chatHistory = [];

    const list = document.getElementById('button-list');
    PROMPTS.forEach((item) => {
        const btn = document.createElement('button');
        btn.className = 'actor-btn';
        btn.innerText = item.name;
        btn.onclick = () => {
            document.querySelectorAll('.actor-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('current-actor-title').innerText = item.name;
            document.getElementById('instruction-editor').value = item.prompt;
            document.getElementById('user-input').disabled = false;
            document.getElementById('send-btn').disabled = false;
            window.clearChat();
        };
        list.appendChild(btn);
    });

    document.getElementById('user-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') window.sendMessage();
    });

    window.clearChat = () => {
        chatHistory = [];
        document.getElementById('messages').innerHTML = "";
        updateStatus("CONTEXT CLEARED", "primary");
        setTimeout(() => { updateStatus("READY", "primary"); }, 1500);
    };

    function updateStatus(text, type) {
        const el = document.getElementById('status-indicator');
        el.innerText = text.toUpperCase();
        if (type === "error") el.style.color = "var(--danger)";
        else if (type === "warning") el.style.color = "var(--warning)";
        else el.style.color = "var(--primary)";
    }

    function startRetryCountdown(seconds) {
        let remaining = seconds;
        const interval = setInterval(() => {
            remaining--;
            if (remaining <= 0) {
                updateStatus("READY TO RETRY", "primary");
                clearInterval(interval);
            } else {
                updateStatus(`RETRY IN ${remaining}s`, "warning");
            }
        }, 1000);
    }

    window.sendMessage = async () => {
        const input = document.getElementById('user-input');
        const text = input.value.trim();
        const editor = document.getElementById('instruction-editor');
        
        if (!text) return;

        appendMessage(text, 'user');
        input.value = "";
        updateStatus("THINKING...", "primary");

        chatHistory.push({ role: "user", parts: [{ text: text }] });

        try {
            const response = await ai.models.generateContent({
                model: SETTINGS.MODEL_NAME,
                contents: [
                    { role: "system", parts: [{ text: editor.value }] },
                    ...chatHistory
                ]
            });

            appendMessage(response.text, 'ai');
            chatHistory.push({ role: "model", parts: [{ text: response.text }] });
            updateStatus("READY", "primary");
        } catch (err) {
            console.error("API Error Object:", err);
            
            let displayError = "Unknown Error Occurred";
            
            // Extracting the specific message from the API error
            if (err.message) {
                // If it's a JSON string from Google, try to parse it
                try {
                    const parsed = JSON.parse(err.message.substring(err.message.indexOf('{')));
                    displayError = parsed.error.message;
                } catch (e) {
                    displayError = err.message;
                }
            }

            appendMessage(`**API Error:** ${displayError}`, 'ai', true);
            updateStatus(displayError, "error");
            
            // If the error is high demand (503) or rate limit (429), suggest a wait
            if (displayError.includes("high demand") || displayError.includes("exhausted")) {
                startRetryCountdown(5);
            }
        }
    };

    function appendMessage(content, side, isError = false) {
        const div = document.createElement('div');
        div.className = `message ${side} ${isError ? 'error-bubble' : ''}`;
        div.innerHTML = side === 'ai' ? marked.parse(content) : content;
        document.getElementById('messages').appendChild(div);
        document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
    }

    window.filterActors = () => {
        const val = document.getElementById('filter').value.toLowerCase();
        document.querySelectorAll('.actor-btn').forEach(btn => {
            btn.style.display = btn.innerText.toLowerCase().includes(val) ? 'block' : 'none';
        });
    };
</script>
</body>
</html>
