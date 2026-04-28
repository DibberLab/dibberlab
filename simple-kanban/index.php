<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Kanban | Dibber Lab</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="/dibber-header.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Column Styles */
        .kanban-col {
            background-color: #1f2937; /* Gray-900 */
            border: 1px solid #374151; /* Gray-700 */
            border-radius: 1rem;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s;
        }
        
        /* Dragging Highlights */
        .kanban-col.drag-over {
            border-color: #f59e0b; /* Amber */
            background-color: rgba(245, 158, 11, 0.05);
        }

        /* The Card */
        .task-card {
            background-color: #111827; /* Gray-950 */
            border: 1px solid #374151;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: grab;
            transition: transform 0.1s, box-shadow 0.2s;
            position: relative;
            user-select: none;
        }
        .task-card:hover {
            border-color: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        }
        .task-card:active { cursor: grabbing; }
        
        /* Color Tags */
        .tag-red { border-left: 4px solid #ef4444; }
        .tag-blue { border-left: 4px solid #3b82f6; }
        .tag-green { border-left: 4px solid #10b981; }
        .tag-amber { border-left: 4px solid #f59e0b; }
        .tag-purple { border-left: 4px solid #8b5cf6; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Modal Animation */
        .modal-enter { animation: fadeScale 0.2s ease-out; }
        @keyframes fadeScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <main class="flex-grow px-4 pb-8 flex flex-col h-[calc(100vh-80px)]">
        
        <div class="max-w-7xl mx-auto w-full mb-6 flex flex-wrap justify-between items-center gap-4 pt-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <span class="text-amber-400">📋</span> Simple Kanban
                </h1>
                <p class="text-xs text-gray-500 mt-1">Drag and drop to organize tasks.</p>
            </div>

            <div class="flex gap-3">
                <div class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700 flex items-center gap-2 text-sm">
                    <span class="text-gray-400">Completed:</span>
                    <span id="stat-percent" class="font-bold text-emerald-400">0%</span>
                </div>
                <button onclick="openModal()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-lg transition-transform hover:-translate-y-1 flex items-center gap-2">
                    <span>+</span> New Task
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-3 gap-6 flex-grow h-full overflow-hidden">
            
            <div class="kanban-col" id="col-todo" ondrop="drop(event)" ondragover="allowDrop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
                <div class="p-4 border-b border-gray-700 flex justify-between items-center sticky top-0 bg-gray-800/50 backdrop-blur rounded-t-2xl z-10">
                    <h2 class="font-bold text-sm text-gray-300 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> To Do
                    </h2>
                    <span id="count-todo" class="bg-gray-900 text-gray-500 text-xs px-2 py-1 rounded font-mono">0</span>
                </div>
                <div class="p-3 overflow-y-auto custom-scrollbar flex-grow" id="list-todo">
                    </div>
            </div>

            <div class="kanban-col" id="col-progress" ondrop="drop(event)" ondragover="allowDrop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
                <div class="p-4 border-b border-gray-700 flex justify-between items-center sticky top-0 bg-gray-800/50 backdrop-blur rounded-t-2xl z-10">
                    <h2 class="font-bold text-sm text-gray-300 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> In Progress
                    </h2>
                    <span id="count-progress" class="bg-gray-900 text-gray-500 text-xs px-2 py-1 rounded font-mono">0</span>
                </div>
                <div class="p-3 overflow-y-auto custom-scrollbar flex-grow" id="list-progress">
                    </div>
            </div>

            <div class="kanban-col" id="col-done" ondrop="drop(event)" ondragover="allowDrop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
                <div class="p-4 border-b border-gray-700 flex justify-between items-center sticky top-0 bg-gray-800/50 backdrop-blur rounded-t-2xl z-10">
                    <h2 class="font-bold text-sm text-gray-300 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Done
                    </h2>
                    <span id="count-done" class="bg-gray-900 text-gray-500 text-xs px-2 py-1 rounded font-mono">0</span>
                </div>
                <div class="p-3 overflow-y-auto custom-scrollbar flex-grow" id="list-done">
                    </div>
            </div>

        </div>

    </main>

    <div id="task-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-600 rounded-2xl w-full max-w-md p-6 shadow-2xl modal-enter">
            <h3 class="text-xl font-bold text-white mb-4">Add New Task</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Title</label>
                    <input type="text" id="modal-title" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:outline-none focus:border-amber-500" placeholder="e.g. Update Homepage">
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Color Tag</label>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full bg-red-500 border-2 border-transparent hover:scale-110 transition-transform focus:border-white" onclick="selectColor('red')"></button>
                        <button class="w-8 h-8 rounded-full bg-amber-500 border-2 border-transparent hover:scale-110 transition-transform focus:border-white" onclick="selectColor('amber')"></button>
                        <button class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-transparent hover:scale-110 transition-transform focus:border-white" onclick="selectColor('green')"></button>
                        <button class="w-8 h-8 rounded-full bg-blue-500 border-2 border-transparent hover:scale-110 transition-transform focus:border-white" onclick="selectColor('blue')"></button>
                        <button class="w-8 h-8 rounded-full bg-purple-500 border-2 border-transparent hover:scale-110 transition-transform focus:border-white" onclick="selectColor('purple')"></button>
                    </div>
                    <input type="hidden" id="modal-color" value="blue">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-400 hover:text-white font-bold transition-colors">Cancel</button>
                <button onclick="addTask()" class="px-6 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-bold shadow-lg transition-transform hover:-translate-y-1">Add Task</button>
            </div>
        </div>
    </div>

    <script>
        // --- STATE ---
        // Load from local storage or default
        let tasks = JSON.parse(localStorage.getItem('dibber-kanban')) || [
            { id: 't1', title: 'Welcome to Kanban', column: 'todo', color: 'amber' },
            { id: 't2', title: 'Drag me around!', column: 'progress', color: 'blue' },
            { id: 't3', title: 'Finished item', column: 'done', color: 'green' }
        ];

        // --- DOM Elements ---
        const lists = {
            todo: document.getElementById('list-todo'),
            progress: document.getElementById('list-progress'),
            done: document.getElementById('list-done')
        };
        const counts = {
            todo: document.getElementById('count-todo'),
            progress: document.getElementById('count-progress'),
            done: document.getElementById('count-done')
        };
        const modal = document.getElementById('task-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalColor = document.getElementById('modal-color');
        const statPercent = document.getElementById('stat-percent');

        // --- RENDER LOGIC ---

        function renderBoard() {
            // Clear lists
            Object.values(lists).forEach(el => el.innerHTML = '');
            
            // Counters
            let countMap = { todo: 0, progress: 0, done: 0 };

            tasks.forEach(task => {
                countMap[task.column]++;
                const card = createCardElement(task);
                lists[task.column].appendChild(card);
            });

            // Update Counts
            counts.todo.textContent = countMap.todo;
            counts.progress.textContent = countMap.progress;
            counts.done.textContent = countMap.done;

            // Update Progress Bar
            const total = tasks.length;
            const percent = total === 0 ? 0 : Math.round((countMap.done / total) * 100);
            statPercent.textContent = percent + "%";

            // Save
            localStorage.setItem('dibber-kanban', JSON.stringify(tasks));
        }

        function createCardElement(task) {
            const div = document.createElement('div');
            div.className = `task-card tag-${task.color}`;
            div.draggable = true;
            div.id = task.id;
            
            // Drag Events
            div.addEventListener('dragstart', dragStart);

            // Controls (Move Arrows for Mobile + Delete)
            let controls = '';
            
            if (task.column === 'todo') {
                controls += `<button onclick="moveTask('${task.id}', 'progress')" class="text-gray-500 hover:text-white" title="Move Forward">➡️</button>`;
            } else if (task.column === 'progress') {
                controls += `<button onclick="moveTask('${task.id}', 'todo')" class="text-gray-500 hover:text-white" title="Move Back">⬅️</button>`;
                controls += `<button onclick="moveTask('${task.id}', 'done')" class="text-gray-500 hover:text-white" title="Move Forward">➡️</button>`;
            } else {
                controls += `<button onclick="moveTask('${task.id}', 'progress')" class="text-gray-500 hover:text-white" title="Move Back">⬅️</button>`;
            }

            div.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <p class="font-medium text-sm text-gray-200 leading-snug">${task.title}</p>
                    <button onclick="deleteTask('${task.id}')" class="text-gray-600 hover:text-red-400 ml-2">×</button>
                </div>
                <div class="flex justify-end gap-3 mt-2 border-t border-gray-800 pt-2 text-xs">
                    ${controls}
                </div>
            `;
            return div;
        }

        // --- ACTIONS ---

        function addTask() {
            const title = modalTitle.value.trim();
            if (!title) return;

            const newTask = {
                id: 't' + Date.now(),
                title: title,
                column: 'todo',
                color: modalColor.value
            };

            tasks.push(newTask);
            renderBoard();
            closeModal();
        }

        function deleteTask(id) {
            tasks = tasks.filter(t => t.id !== id);
            renderBoard();
        }

        function moveTask(id, targetCol) {
            const task = tasks.find(t => t.id === id);
            if (task) {
                task.column = targetCol;
                renderBoard();
            }
        }

        // --- MODAL ---

        function openModal() {
            modalTitle.value = '';
            modal.classList.remove('hidden');
            modalTitle.focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function selectColor(color) {
            modalColor.value = color;
            // Visual feedback could be added here
        }

        // --- DRAG AND DROP ---

        function dragStart(e) {
            e.dataTransfer.setData("text/plain", e.target.id);
            e.dataTransfer.effectAllowed = "move";
            setTimeout(() => e.target.classList.add('opacity-50'), 0);
        }

        function dragEnter(e) {
            e.preventDefault();
            const col = e.target.closest('.kanban-col');
            if (col) col.classList.add('drag-over');
        }

        function dragLeave(e) {
            const col = e.target.closest('.kanban-col');
            if (col) col.classList.remove('drag-over');
        }

        function allowDrop(e) {
            e.preventDefault(); // Necessary to allow dropping
        }

        function drop(e) {
            e.preventDefault();
            const col = e.target.closest('.kanban-col');
            if (!col) return;

            col.classList.remove('drag-over');
            
            const id = e.dataTransfer.getData("text/plain");
            const task = tasks.find(t => t.id === id);
            
            // Map DOM ID to Column Key
            const colId = col.id.replace('col-', '');
            
            if (task && task.column !== colId) {
                task.column = colId;
                renderBoard();
            } else {
                // Just reset opacity if dropped in same col or invalid
                const el = document.getElementById(id);
                if(el) el.classList.remove('opacity-50');
            }
        }

        // --- INIT ---
        renderBoard();

        // Keyboard close modal
        document.addEventListener('keydown', (e) => {
            if(e.key === 'Escape') closeModal();
            if(e.key === 'Enter' && !modal.classList.contains('hidden')) addTask();
        });

    </script>
</body>
</html>