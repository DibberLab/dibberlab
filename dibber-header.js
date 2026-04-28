// File: /dibber-header.js

document.addEventListener("DOMContentLoaded", function() {
    // 1. Create the Header Element
    const header = document.createElement("header");
    header.className = "bg-gray-800 border-b border-gray-700 text-white py-4 px-6 mb-8";

    // 2. Define the HTML Content
    // This includes the Logo (left) and the Coffee Button (right)
    header.innerHTML = `
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            
            <a href="/" class="group flex items-center gap-2 no-underline">
                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-amber-500 rounded-lg flex items-center justify-center font-bold text-gray-900 text-lg group-hover:scale-105 transition-transform">
                    D
                </div>
                <span class="font-bold text-xl tracking-tight text-gray-100 group-hover:text-white transition-colors">
                    Dibber <span class="text-emerald-400">Lab</span>
                </span>
            </a>

            <a href="https://buymeacoffee.com/andrewmich9" target="_blank" 
               class="flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-semibold px-3 py-1.5 rounded-full text-sm transition-all transform hover:-translate-y-0.5 shadow-lg">
                <span class="text-lg">☕</span>
                <span class="hidden sm:inline">Buy me a coffee</span>
            </a>
        </div>
    `;

    // 3. Inject it at the very top of the body
    document.body.prepend(header);
});