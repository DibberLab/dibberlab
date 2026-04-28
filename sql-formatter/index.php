<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Formatter | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Monospace Editors */
        .sql-font {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Syntax Highlighting Colors */
        .sql-keyword { color: #f59e0b; font-weight: bold; } /* Amber */
        .sql-string { color: #a7f3d0; } /* Emerald */
        .sql-function { color: #60a5fa; } /* Blue */
        .sql-number { color: #f472b6; } /* Pink */
        .sql-symbol { color: #9ca3af; } /* Gray */

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 flex items-center justify-center">
        <div class="w-full max-w-6xl mx-auto bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 md:p-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-center mb-2 text-amber-400">SQL Formatter</h1>
                <p class="text-center text-gray-400">Beautify, uppercase, and debug messy SQL queries.</p>
            </div>

            <div class="flex flex-wrap gap-3 mb-4 justify-between items-center bg-gray-900 p-3 rounded-xl border border-gray-700">
                <div class="flex gap-2">
                    <button id="format-btn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2">
                        <span>✨</span> Format
                    </button>
                    <button id="minify-btn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-bold text-sm transition-colors">
                        Minify
                    </button>
                    <button id="clear-btn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-bold text-sm transition-colors">
                        Clear
                    </button>
                </div>

                <div class="flex gap-2 items-center">
                    <button id="sample-btn" class="text-xs text-gray-500 hover:text-gray-300 underline mr-2">Load Sample</button>
                    <button id="copy-btn" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2">
                        <span>📋</span> Copy Result
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 h-[600px]">
                
                <div class="flex flex-col h-full">
                    <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2 px-1">
                        <span>Raw SQL</span>
                    </div>
                    <textarea id="sql-input" class="sql-font w-full h-full bg-gray-900 text-gray-300 p-4 rounded-xl border border-gray-600 focus:outline-none focus:border-amber-500 resize-none placeholder-gray-700" placeholder="SELECT * FROM table WHERE id = 1..."></textarea>
                </div>

                <div class="flex flex-col h-full relative">
                    <div class="flex justify-between text-xs text-gray-500 font-bold uppercase mb-2 px-1">
                        <span>Formatted Result</span>
                    </div>
                    
                    <div class="relative w-full h-full bg-gray-900 border border-gray-600 rounded-xl overflow-hidden">
                        <pre id="sql-output" class="sql-font w-full h-full p-4 overflow-auto whitespace-pre-wrap break-all text-gray-300"></pre>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-sm">
        <p>&copy; <script>document.write(new Date().getFullYear())</script> Dibber Lab</p>
    </footer>

    <script>
        const input = document.getElementById('sql-input');
        const output = document.getElementById('sql-output');
        const formatBtn = document.getElementById('format-btn');
        const minifyBtn = document.getElementById('minify-btn');
        const clearBtn = document.getElementById('clear-btn');
        const copyBtn = document.getElementById('copy-btn');
        const sampleBtn = document.getElementById('sample-btn');

        // Sample Data
        const sampleSQL = "select u.id, u.username, p.title, count(c.id) as comment_count from users u left join posts p on u.id = p.user_id left join comments c on p.id = c.post_id where u.active = 1 and p.published_at > '2023-01-01' group by u.id order by comment_count desc limit 10;";

        // --- KEYWORD DATABASE ---
        // Newlines will be added BEFORE these
        const newlineKeywords = [
            "SELECT", "FROM", "WHERE", "GROUP BY", "ORDER BY", "HAVING", "LIMIT", 
            "LEFT JOIN", "RIGHT JOIN", "INNER JOIN", "OUTER JOIN", "JOIN", 
            "UNION", "UNION ALL", "INSERT INTO", "VALUES", "UPDATE", "SET", "DELETE FROM",
            "CREATE TABLE", "DROP TABLE", "ALTER TABLE"
        ];

        // Basic keywords to uppercase
        const otherKeywords = [
            "AS", "ON", "AND", "OR", "NOT", "IN", "IS", "NULL", "LIKE", "BETWEEN", 
            "EXISTS", "ASC", "DESC", "DISTINCT", "CASE", "WHEN", "THEN", "ELSE", "END",
            "COUNT", "SUM", "AVG", "MIN", "MAX"
        ];

        const allKeywords = [...newlineKeywords, ...otherKeywords];

        // --- FORMATTER LOGIC ---

        function formatSQL(minify = false) {
            let sql = input.value;
            if (!sql) {
                output.innerHTML = '';
                return;
            }

            // 1. Minify Phase (Remove extra whitespace/newlines)
            sql = sql.replace(/\s+/g, ' ').trim();

            if (minify) {
                output.textContent = sql;
                return;
            }

            // 2. Tokenize
            // Regex matches: strings, backtick quotes, alphanumerics, or specific symbols
            const regex = /(".*?"|'.*?'|`.*?`|[\w\d]+|[(),;=<>*])/gim;
            let tokens = sql.match(regex);

            if (!tokens) {
                output.textContent = sql;
                return;
            }

            // 3. Process Tokens
            let formatted = "";
            let indentLevel = 0;
            const indentStr = "  "; // 2 spaces

            for (let i = 0; i < tokens.length; i++) {
                let token = tokens[i];
                let upperToken = token.toUpperCase();
                let nextToken = tokens[i + 1] ? tokens[i + 1].toUpperCase() : "";
                
                // Combine multi-word keywords (e.g., GROUP BY, LEFT JOIN)
                // Check current + next
                let doubleToken = (upperToken + " " + nextToken).trim();
                let isDouble = false;

                if (newlineKeywords.includes(doubleToken)) {
                    token = doubleToken;
                    upperToken = doubleToken;
                    i++; // Skip next token
                    isDouble = true;
                }

                // --- LOGIC GATES ---

                // 1. Newline Keywords (SELECT, FROM, etc)
                if (newlineKeywords.includes(upperToken)) {
                    if (formatted !== "") formatted += "\n";
                    formatted += indentStr.repeat(indentLevel) + upperToken + " ";
                }
                // 2. Opening Parenthesis
                else if (token === "(") {
                    formatted += "(";
                    // Only newline/indent if it's a subquery or long list (heuristic)
                    // For simple functions count(id), keep inline
                    // Heuristic: If previous token was a function keyword, stay inline.
                    // For simplicity in this lightweight tool:
                    // If next token is SELECT, new block.
                    if (tokens[i + 1] && tokens[i + 1].toUpperCase() === "SELECT") {
                        indentLevel++;
                        formatted += "\n" + indentStr.repeat(indentLevel);
                    }
                }
                // 3. Closing Parenthesis
                else if (token === ")") {
                    // Check if we need to outdent (if we indented previously)
                    // Simple regex heuristic based on formatting string so far
                    if (formatted.endsWith(indentStr.repeat(indentLevel))) {
                        indentLevel = Math.max(0, indentLevel - 1);
                        // Remove last indent
                        formatted = formatted.substring(0, formatted.length - indentStr.length);
                    } else if (formatted.trim().endsWith(")")) {
                         // Nested closing
                    }
                    
                    // If the current line is just indentation, outdent current line
                    const lastNewLine = formatted.lastIndexOf('\n');
                    const currentLine = formatted.substring(lastNewLine + 1);
                    if(/^\s+$/.test(currentLine)) {
                         indentLevel = Math.max(0, indentLevel - 1);
                         formatted = formatted.substring(0, lastNewLine + 1) + indentStr.repeat(indentLevel);
                    }

                    formatted += ")";
                }
                // 4. Commas
                else if (token === ",") {
                    formatted += ",\n" + indentStr.repeat(indentLevel === 0 ? 0 : indentLevel) + (indentLevel === 0 ? "    " : ""); 
                    // Add slight indentation for comma lists in root
                }
                // 5. Normal Keywords
                else if (otherKeywords.includes(upperToken)) {
                    formatted += " " + upperToken + " ";
                }
                // 6. Generic Token
                else {
                    // Avoid space if previous was parenthesis or space
                    if (formatted.endsWith(" ") || formatted.endsWith("\n") || formatted.endsWith("(")) {
                        formatted += token;
                    } else {
                        formatted += " " + token;
                    }
                }
            }

            // 4. Clean up spaces
            formatted = formatted.replace(/\( /g, "(").replace(/ \)/g, ")").replace(/ ,/g, ",");

            // 5. Syntax Highlight
            output.innerHTML = highlightSQL(formatted);
        }

        // --- SYNTAX HIGHLIGHTER ---
        function highlightSQL(sql) {
            // Escape HTML first
            let safe = sql.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

            // 1. Strings (Single quotes)
            safe = safe.replace(/('.*?')/g, '<span class="sql-string">$1</span>');
            
            // 2. Numbers
            safe = safe.replace(/\b(\d+)\b/g, '<span class="sql-number">$1</span>');

            // 3. Keywords (Using Regex with word boundary)
            allKeywords.forEach(kw => {
                // Matches keywords that aren't inside HTML tags (spans)
                // This naive regex replacement has limits but works for simple formatting
                const reg = new RegExp(`\\b(${kw})\\b`, 'g');
                safe = safe.replace(reg, '<span class="sql-keyword">$1</span>');
            });

            // 4. Functions (Approximation: word followed by parenthesis)
            safe = safe.replace(/(\w+)\(/g, '<span class="sql-function">$1</span>(');

            return safe;
        }

        // --- LISTENERS ---

        formatBtn.addEventListener('click', () => formatSQL(false));
        minifyBtn.addEventListener('click', () => formatSQL(true));
        
        clearBtn.addEventListener('click', () => {
            input.value = '';
            output.innerHTML = '';
            input.focus();
        });

        sampleBtn.addEventListener('click', () => {
            input.value = sampleSQL;
            formatSQL(false);
        });

        copyBtn.addEventListener('click', () => {
            // Copy pure text, not HTML
            const text = output.innerText;
            if(!text) return;

            navigator.clipboard.writeText(text).then(() => {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = "<span>✅</span> Copied!";
                copyBtn.classList.replace('bg-amber-600', 'bg-emerald-600');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.replace('bg-emerald-600', 'bg-amber-600');
                }, 2000);
            });
        });

        // Initialize with nothing
        input.focus();

    </script>
</body>
</html>