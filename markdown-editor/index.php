<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown Editor | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Editor Fonts */
        .mono-font {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Preview Typography (Tailwind Prose-ish) */
        .markdown-preview h1 { font-size: 2em; font-weight: 800; margin-bottom: 0.5em; color: #f59e0b; border-bottom: 1px solid #374151; padding-bottom: 0.2em; }
        .markdown-preview h2 { font-size: 1.5em; font-weight: 700; margin-top: 1em; margin-bottom: 0.5em; color: #fbbf24; }
        .markdown-preview h3 { font-size: 1.25em; font-weight: 600; margin-top: 1em; margin-bottom: 0.5em; color: #d1d5db; }
        .markdown-preview p { margin-bottom: 1em; line-height: 1.6; color: #d1d5db; }
        .markdown-preview ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1em; color: #d1d5db; }
        .markdown-preview ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1em; color: #d1d5db; }
        .markdown-preview blockquote { border-left: 4px solid #f59e0b; padding-left: 1em; color: #9ca3af; font-style: italic; margin-bottom: 1em; background: rgba(245, 158, 11, 0.1); padding: 0.5em 1em; border-radius: 4px; }
        .markdown-preview code { background-color: #374151; padding: 0.2em 0.4em; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 0.9em; color: #a7f3d0; }
        .markdown-preview pre { background-color: #1f2937; padding: 1em; border-radius: 8px; overflow-x: auto; margin-bottom: 1em; border: 1px solid #374151; }
        .markdown-preview pre code { background-color: transparent; padding: 0; color: #e5e7eb; }
        .markdown-preview a { color: #3b82f6; text-decoration: underline; }
        .markdown-preview hr { border: 0; height: 1px; background: #374151; margin: 2em 0; }
        .markdown-preview strong { color: white; font-weight: 800; }
        .markdown-preview em { color: #f3f4f6; }

        /* Toolbar Button */
        .tool-btn {
            transition: all 0.1s;
        }
        .tool-btn:hover {
            background-color: #374151;
            color: white;
        }
        .tool-btn:active {
            background-color: #f59e0b;
            color: #111827;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex flex-col items-center">
        <div class="w-full max-w-7xl mx-auto h-[80vh] flex flex-col">
            
            <div class="flex flex-wrap justify-between items-center bg-gray-800 border-x border-t border-gray-700 rounded-t-xl p-3 gap-4">
                
                <div class="flex flex-wrap gap-1">
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 font-bold text-sm" onclick="insertSyntax('**', '**')" title="Bold">B</button>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 italic text-sm" onclick="insertSyntax('*', '*')" title="Italic">I</button>
                    <div class="w-px h-6 bg-gray-600 my-auto mx-1"></div>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 font-bold text-sm" onclick="insertSyntax('# ')" title="Heading 1">H1</button>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 font-bold text-sm" onclick="insertSyntax('## ')" title="Heading 2">H2</button>
                    <div class="w-px h-6 bg-gray-600 my-auto mx-1"></div>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 text-sm" onclick="insertSyntax('> ')" title="Quote">❝</button>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 text-sm" onclick="insertSyntax('`', '`')" title="Code">`</button>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 text-sm" onclick="insertSyntax('- ')" title="List">☰</button>
                    <button class="tool-btn px-3 py-1.5 rounded text-gray-400 text-sm" onclick="insertSyntax('[', '](url)')" title="Link">🔗</button>
                </div>

                <div class="flex gap-2">
                    <button id="copy-html-btn" class="px-3 py-1.5 rounded bg-gray-700 hover:bg-gray-600 text-xs font-bold text-gray-300 transition-colors">
                        Copy HTML
                    </button>
                    <button id="download-btn" class="px-3 py-1.5 rounded bg-amber-600 hover:bg-amber-500 text-xs font-bold text-white transition-colors flex items-center gap-1">
                        <span>⬇</span> .MD
                    </button>
                </div>
            </div>

            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 border border-gray-700 bg-gray-900 overflow-hidden rounded-b-xl">
                
                <textarea id="markdown-input" class="w-full h-full bg-gray-900 text-gray-300 p-6 focus:outline-none resize-none mono-font text-sm leading-relaxed border-b md:border-b-0 md:border-r border-gray-700 custom-scrollbar" placeholder="Type Markdown here..."></textarea>

                <div id="preview-output" class="w-full h-full bg-gray-800 p-6 overflow-y-auto markdown-preview custom-scrollbar"></div>
                
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const input = document.getElementById('markdown-input');
        const output = document.getElementById('preview-output');
        const downloadBtn = document.getElementById('download-btn');
        const copyBtn = document.getElementById('copy-html-btn');

        // Sample Content
        const initialText = `# Welcome to Markdown
This is a live editor. **Start typing** on the left!

## Features
- Real-time preview
- *Italic* and **Bold** support
- Code blocks:
\`\`\`
console.log("Hello World");
\`\`\`
- [Links](https://dibberlab.me) and Lists

> "Simplicity is the ultimate sophistication."`;

        // --- PARSER LOGIC ---
        // A lightweight, regex-based Markdown parser
        function parseMarkdown(text) {
            let html = text
                // Escape HTML (Security)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                
                // Headers (H1 - H6)
                .replace(/^###### (.*$)/gim, '<h6>$1</h6>')
                .replace(/^##### (.*$)/gim, '<h5>$1</h5>')
                .replace(/^#### (.*$)/gim, '<h4>$1</h4>')
                .replace(/^### (.*$)/gim, '<h3>$1</h3>')
                .replace(/^## (.*$)/gim, '<h2>$1</h2>')
                .replace(/^# (.*$)/gim, '<h1>$1</h1>')

                // Blockquotes
                .replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>')

                // Bold & Italic
                .replace(/\*\*(.*)\*\*/gim, '<strong>$1</strong>')
                .replace(/\*(.*)\*/gim, '<em>$1</em>')
                .replace(/__(.*)__/gim, '<strong>$1</strong>')
                .replace(/_(.*)_/gim, '<em>$1</em>')

                // Code Block (Triple backtick)
                .replace(/```([\s\S]*?)```/gim, '<pre><code>$1</code></pre>')
                
                // Inline Code
                .replace(/`([^`]+)`/gim, '<code>$1</code>')

                // Links
                .replace(/\[(.*?)\]\((.*?)\)/gim, "<a href='$2' target='_blank'>$1</a>")

                // Horizontal Rule
                .replace(/^---$/gim, '<hr>')

                // Unordered Lists (Simple implementation)
                .replace(/^\s*[\-\*] (.*$)/gim, '<ul><li>$1</li></ul>')
                .replace(/<\/ul>\s*<ul>/gim, '') // Merge adjacent lists

                // Ordered Lists
                .replace(/^\s*\d+\. (.*$)/gim, '<ol><li>$1</li></ol>')
                .replace(/<\/ol>\s*<ol>/gim, '') // Merge adjacent lists

                // Paragraphs (Double newline)
                .replace(/\n\n/gim, '<p></p>')
                .replace(/\n/gim, '<br>');

            return html.trim();
        }

        // --- UPDATE FUNCTION ---
        function updatePreview() {
            const text = input.value;
            const html = parseMarkdown(text);
            output.innerHTML = html;
        }

        // --- INSERT HELPER ---
        // Insert text at cursor position
        window.insertSyntax = function(startTag, endTag = '') {
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const text = input.value;
            const selection = text.substring(start, end);
            
            const replacement = startTag + selection + endTag;
            
            input.value = text.substring(0, start) + replacement + text.substring(end);
            
            // Move cursor inside tags
            input.focus();
            input.selectionStart = start + startTag.length;
            input.selectionEnd = start + startTag.length + selection.length;
            
            updatePreview();
        };

        // --- SCROLL SYNC ---
        const syncScroll = () => {
            const percentage = input.scrollTop / (input.scrollHeight - input.offsetHeight);
            output.scrollTop = percentage * (output.scrollHeight - output.offsetHeight);
        };

        // --- LISTENERS ---
        input.addEventListener('input', updatePreview);
        input.addEventListener('scroll', syncScroll);

        // Download .md File
        downloadBtn.addEventListener('click', () => {
            const blob = new Blob([input.value], { type: 'text/markdown' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'document.md';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

        // Copy HTML
        copyBtn.addEventListener('click', () => {
            const html = parseMarkdown(input.value);
            navigator.clipboard.writeText(html).then(() => {
                const originalText = copyBtn.textContent;
                copyBtn.textContent = "Copied!";
                copyBtn.classList.replace('bg-gray-700', 'bg-emerald-600');
                
                setTimeout(() => {
                    copyBtn.textContent = originalText;
                    copyBtn.classList.replace('bg-emerald-600', 'bg-gray-700');
                }, 1500);
            });
        });

        // Initialize
        input.value = initialText;
        updatePreview();

    </script>
</body>
</html>