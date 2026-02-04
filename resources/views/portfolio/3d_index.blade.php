<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $profile->hero_title ?? 'My Portfolio' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/portfolio_3d/main.js'])
        
        <script>
            // Pass PHP data to JavaScript
            window.portfolioData = {
                profile: @json($profile),
                projects: @json($projects),
                skills: @json($skills),
                assetPath: "{{ asset('storage/') }}"
            };
        </script>

        <style>
            body { 
                margin: 0; 
                overflow: hidden; 
                background-color: #0b0b15;
                font-family: 'Outfit', sans-serif;
            }
            #canvas-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 1;
            }
            #ui-layer {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
                pointer-events: none; /* Let clicks pass through to 3D scene */
            }
            .interactive-ui {
                pointer-events: auto;
            }
        </style>
    </head>
    <body class="antialiased text-white">
        
        <div id="canvas-container"></div>

        <div id="ui-layer" class="flex flex-col justify-between p-8">
            <!-- Header -->
            <header class="flex justify-between items-center interactive-ui">
                <div class="text-2xl font-bold tracking-widest uppercase">
                    {{ $profile->hero_title ?? 'Portfolio' }}
                </div>
                <nav class="space-x-6 text-sm font-medium opacity-80">
                    <a href="#" class="hover:text-cyan-400 transition" onclick="window.cameraTo('projects')">Works</a>
                    <a href="#" class="hover:text-cyan-400 transition" onclick="window.cameraTo('skills')">Skills</a>
                    <a href="#" class="hover:text-cyan-400 transition" onclick="window.cameraTo('about')">About</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300">Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white">Login</a>
                    @endauth
                </nav>
            </header>

            <!-- Loading Screen (Removed by JS) -->
            <div id="loading" class="absolute inset-0 flex items-center justify-center bg-black z-50 transition-opacity duration-1000 interactive-ui">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-4 animate-pulse">Initializing System...</div>
                    <div class="w-64 h-1 bg-gray-800 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-cyan-500 transition-all duration-300" id="loading-bar"></div>
                    </div>
                </div>
            </div>

            <!-- Project Details Modal (Hidden by default) -->
            <div id="project-modal" class="hidden absolute right-0 top-0 h-full w-full md:w-1/3 bg-black/80 backdrop-blur-md border-l border-white/10 p-10 transform translate-x-full transition-transform duration-500 interactive-ui overflow-y-auto">
                <button id="close-modal" class="absolute top-6 right-6 text-white/50 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div id="modal-content" class="mt-10">
                    <!-- Specific project details injected here -->
                    <h2 class="text-4xl font-bold mb-2 text-cyan-400" id="p-title">Project Title</h2>
                    <p class="text-sm text-gray-400 mb-6" id="p-tech">Laravel, Vue</p>
                    <img src="" id="p-image" class="w-full rounded-lg mb-6 border border-white/20 hidden">
                    <p class="text-lg leading-relaxed text-gray-300 mb-8" id="p-desc">Description goes here...</p>
                    
                    <div class="flex space-x-4">
                        <a href="#" id="p-link" target="_blank" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 rounded text-black font-bold transition">View Live</a>
                        <a href="#" id="p-github" target="_blank" class="px-6 py-3 border border-white/30 hover:border-white rounded transition">GitHub</a>
                    </div>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="absolute bottom-8 left-8 text-xs text-gray-500 interactive-ui">
                <p>Scroll to Explore • Click to Interact</p>
            </div>

        </div>

    </body>
</html>
