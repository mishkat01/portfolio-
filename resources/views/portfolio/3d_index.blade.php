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
            <!-- Header -->
            <header class="flex justify-between items-center interactive-ui relative z-50">
                <div class="text-2xl font-bold tracking-widest uppercase text-white/90">
                    {{ $profile->hero_title ?? 'Portfolio' }}
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
                    <a href="#" class="text-white/70 hover:text-cyan-400 transition-colors duration-300" onclick="window.cameraTo('projects'); return false;">WORKS</a>
                    <a href="#" class="text-white/70 hover:text-cyan-400 transition-colors duration-300" onclick="window.cameraTo('skills'); return false;">SKILLS</a>
                    <a href="#" class="text-white/70 hover:text-cyan-400 transition-colors duration-300" onclick="window.cameraTo('about'); return false;">ABOUT</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors duration-300">ADMIN</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white/70 hover:text-cyan-400 transition-colors duration-300">LOGIN</a>
                    @endauth
                </nav>

                <!-- Mobile Hamburger Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Mobile Menu Overlay -->
                <div id="mobile-menu" class="fixed inset-0 bg-black/95 backdrop-blur-xl z-50 transform translate-x-full transition-transform duration-300 flex flex-col justify-center items-center md:hidden">
                    <button id="close-mobile-menu" class="mb-10 text-white/50 hover:text-cyan-400 transition-colors focus:outline-none group">
                        <svg class="w-12 h-12 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <nav class="flex flex-col space-y-8 text-center text-2xl font-light tracking-widest">
                        <a href="#" class="text-white hover:text-cyan-400 transition-colors" onclick="closeMobileMenu(); window.cameraTo('projects'); return false;">WORKS</a>
                        <a href="#" class="text-white hover:text-cyan-400 transition-colors" onclick="closeMobileMenu(); window.cameraTo('skills'); return false;">SKILLS</a>
                        <a href="#" class="text-white hover:text-cyan-400 transition-colors" onclick="closeMobileMenu(); window.cameraTo('about'); return false;">ABOUT</a>
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">ADMIN</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-cyan-400 transition-colors">LOGIN</a>
                        @endauth
                    </nav>
                </div>
            </header>

            <script>
                // Mobile Menu Logic
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileMenu = document.getElementById('mobile-menu');
                const closeMobileMenuBtn = document.getElementById('close-mobile-menu');

                function openMobileMenu() {
                    mobileMenu.classList.remove('translate-x-full');
                }

                function closeMobileMenu() {
                    mobileMenu.classList.add('translate-x-full');
                }

                if(mobileMenuBtn) {
                    mobileMenuBtn.addEventListener('click', openMobileMenu);
                }
                
                if(closeMobileMenuBtn) {
                    closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
                }
            </script>

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
            <!-- About Section (Appears when camera is at the end) -->
            <div id="about-section" class="absolute inset-0 flex items-center justify-center p-8 transition-opacity duration-1000 opacity-0 pointer-events-none z-0 interactive-ui">
                <div class="max-w-2xl bg-black/60 backdrop-blur-md p-8 rounded-2xl border border-white/10 text-center">
                    <img src="{{ $profile->hero_title === 'Welcome' ? 'https://ui-avatars.com/api/?name=Admin&background=random' : ($profile->social_links['avatar'] ?? 'https://ui-avatars.com/api/?name=Me&background=random') }}" 
                         class="w-24 h-24 rounded-full mx-auto mb-6 border-4 border-cyan-500 shadow-lg shadow-cyan-500/50">
                    
                    <h2 class="text-3xl font-bold mb-2">{{ $profile->hero_title }}</h2>
                    <h3 class="text-xl text-cyan-400 mb-6">{{ $profile->subtitle }}</h3>
                    
                    <p class="text-gray-300 leading-relaxed mb-8">
                        {{ $profile->about_text ?? "Welcome to my 3D universe. Use the admin panel to update this bio!" }}
                    </p>

                    <div class="flex justify-center space-x-6">
                        @if($profile->resume_url)
                            <a href="{{ $profile->resume_url }}" target="_blank" class="text-white hover:text-cyan-400 border-b border-cyan-400 pb-1">Resume</a>
                        @endif
                        @if(isset($profile->social_links['github']))
                            <a href="{{ $profile->social_links['github'] }}" target="_blank" class="text-white hover:text-cyan-400 border-b border-cyan-400 pb-1">GitHub</a>
                        @endif
                        @if(isset($profile->social_links['linkedin']))
                            <a href="{{ $profile->social_links['linkedin'] }}" target="_blank" class="text-white hover:text-cyan-400 border-b border-cyan-400 pb-1">LinkedIn</a>
                        @endif
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
