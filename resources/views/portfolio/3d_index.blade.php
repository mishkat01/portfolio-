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
            :root {
                --accent-cyan: #06b6d4;
                --accent-blue: #3b82f6;
                --glass-bg: rgba(13, 13, 27, 0.6);
                --glass-border: rgba(255, 255, 255, 0.1);
            }
            
            body { 
                margin: 0; 
                overflow: hidden; 
                background-color: #05050a;
                font-family: 'Outfit', sans-serif;
                color: #e2e8f0;
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
                pointer-events: none;
            }

            .interactive-ui {
                pointer-events: auto;
            }

            .glass-panel {
                background: var(--glass-bg);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid var(--glass-border);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            }

            .text-gradient {
                background: linear-gradient(to right, var(--accent-cyan), var(--accent-blue));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            /* Custom Transitions */
            .fade-in { animation: fadeIn 1s ease-out forwards; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .nav-link {
                position: relative;
                padding-bottom: 2px;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 1px;
                background: var(--accent-cyan);
                transition: width 0.3s ease;
            }
            .nav-link:hover::after { width: 100%; }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 4px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }
        </style>
    </head>
    <body class="antialiased">
        
        <div id="canvas-container"></div>

        <div id="ui-layer" class="flex flex-col p-6 md:p-10">
            <!-- Header -->
            <header class="flex justify-between items-center interactive-ui relative z-50 animate-fade-in translate-y-[-10px] opacity-0" style="animation: fadeIn 0.8s ease-out 0.5s forwards">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <span class="text-xl font-bold text-white">M</span>
                    </div>
                    <div class="text-xl font-bold tracking-[0.2em] uppercase text-white/90">
                        {{ $profile->hero_title ?? 'Portfolio' }}
                    </div>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-10 text-xs font-bold tracking-widest">
                    <a href="#" class="nav-link text-white/60 hover:text-white transition-colors duration-300" onclick="window.cameraTo('projects'); return false;">WORKS</a>
                    <a href="#" class="nav-link text-white/60 hover:text-white transition-colors duration-300" onclick="window.cameraTo('skills'); return false;">SKILLS</a>
                    <a href="#" class="nav-link text-white/60 hover:text-white transition-colors duration-300" onclick="window.cameraTo('about'); return false;">ABOUT</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 glass-panel rounded-full text-cyan-400 hover:text-white hover:bg-cyan-500/20 transition-all">ADMIN</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white/40 hover:text-white transition-colors">LOGIN</a>
                    @endauth
                </nav>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-btn" class="md:hidden text-white/70 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M10 16h10"></path>
                    </svg>
                </button>

                <!-- Mobile Menu Overlay -->
                <div id="mobile-menu" class="fixed inset-0 glass-panel z-50 transform translate-x-full transition-transform duration-500 flex flex-col justify-center items-center md:hidden">
                    <button id="close-mobile-menu" class="absolute top-10 right-10 text-white/30 hover:text-white transition-colors focus:outline-none">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <nav class="flex flex-col space-y-12 text-center text-3xl font-light tracking-[0.3em]">
                        <a href="#" class="text-white/60 hover:text-white transition-all" onclick="closeMobileMenu(); window.cameraTo('projects'); return false;">WORKS</a>
                        <a href="#" class="text-white/60 hover:text-white transition-all" onclick="closeMobileMenu(); window.cameraTo('skills'); return false;">SKILLS</a>
                        <a href="#" class="text-white/60 hover:text-white transition-all" onclick="closeMobileMenu(); window.cameraTo('about'); return false;">ABOUT</a>
                    </nav>
                </div>
            </header>

            <script>
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileMenu = document.getElementById('mobile-menu');
                const closeMobileMenuBtn = document.getElementById('close-mobile-menu');
                function openMobileMenu() { mobileMenu.classList.remove('translate-x-full'); }
                function closeMobileMenu() { mobileMenu.classList.add('translate-x-full'); }
                if(mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
                if(closeMobileMenuBtn) closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
            </script>

            <!-- Loading Screen -->
            <div id="loading" class="fixed inset-0 flex items-center justify-center bg-[#05050a] z-[100] transition-opacity duration-1000 interactive-ui">
                <div class="text-center">
                    <div class="text-xs font-bold tracking-[0.5em] text-white/30 mb-8 uppercase animate-pulse">Establishing Connection</div>
                    <div class="w-48 h-[1px] bg-white/10 rounded-full overflow-hidden">
                        <div class="w-0 h-full bg-gradient-to-r from-cyan-500 to-blue-500 transition-all duration-300" id="loading-bar"></div>
                    </div>
                </div>
            </div>

            <!-- Project Side Drawer (Modern Modal replacement) -->
            <div id="project-modal" class="fixed right-0 top-0 h-full w-full md:w-[450px] glass-panel border-l border-white/5 p-12 transform translate-x-full transition-all duration-700 ease-in-out interactive-ui overflow-y-auto">
                <button id="close-modal" class="group absolute top-10 right-10 text-white/30 hover:text-white transition-colors">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <div id="modal-content" class="mt-8">
                    <div class="mb-10">
                        <span id="p-tech" class="text-[10px] font-bold tracking-widest text-cyan-400 uppercase mb-2 block">Tech Stack</span>
                        <h2 class="text-5xl font-bold text-white mb-6 leading-tight" id="p-title">Project Title</h2>
                    </div>

                    <div class="relative group mb-10 overflow-hidden rounded-2xl border border-white/10">
                        <img src="" id="p-image" class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>

                    <p class="text-lg leading-relaxed text-gray-400 mb-12 font-light" id="p-desc">Description goes here...</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" id="p-link" target="_blank" class="flex items-center justify-center py-4 bg-white text-black text-sm font-bold rounded-xl hover:bg-cyan-500 hover:text-white transition-all transform hover:-translate-y-1">
                            LIVE DEMO
                        </a>
                        <a href="#" id="p-github" target="_blank" class="flex items-center justify-center py-4 glass-panel text-white text-sm font-bold rounded-xl hover:bg-white/10 transition-all transform hover:-translate-y-1">
                            SOURCE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced About Section -->
            <div id="about-section" class="absolute inset-0 flex items-center justify-center p-8 transition-all duration-1000 opacity-0 pointer-events-none z-0 interactive-ui">
                <div class="max-w-xl glass-panel p-12 rounded-[32px] border border-white/5 text-center transform scale-95 transition-transform duration-1000">
                    <div class="relative inline-block mb-10">
                        <div class="absolute inset-[-10px] bg-cyan-500/20 blur-2xl rounded-full"></div>
                        <img src="{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->hero_title) . '&background=06b6d4&color=fff' }}" 
                             class="w-32 h-32 rounded-3xl relative z-10 border border-white/10 shadow-2xl object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    <h2 class="text-4xl font-bold mb-3 tracking-tight">{{ $profile->hero_title }}</h2>
                    <h3 class="text-lg text-cyan-400/80 font-medium tracking-widest uppercase mb-8">{{ $profile->subtitle }}</h3>
                    
                    <p class="text-gray-400 leading-relaxed mb-12 font-light text-lg">
                        {{ $profile->about_text ?? "Full-stack developer focused on creating cinematic digital experiences." }}
                    </p>

                    <div class="flex justify-center space-x-12">
                        @if($profile->resume_url)
                            <a href="{{ $profile->resume_url }}" target="_blank" class="text-xs font-bold tracking-widest text-white/40 hover:text-cyan-400 transition-colors">CV</a>
                        @endif
                        @if(isset($profile->social_links['github']))
                            <a href="{{ $profile->social_links['github'] }}" target="_blank" class="text-xs font-bold tracking-widest text-white/40 hover:text-cyan-400 transition-colors">GH</a>
                        @endif
                        @if(isset($profile->social_links['linkedin']))
                            <a href="{{ $profile->social_links['linkedin'] }}" target="_blank" class="text-xs font-bold tracking-widest text-white/40 hover:text-cyan-400 transition-colors">LI</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer / Status -->
            <footer class="mt-auto flex justify-between items-end interactive-ui p-2 animate-fade-in translate-y-[10px] opacity-0" style="animation: fadeIn 0.8s ease-out 1s forwards">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold tracking-[0.3em] text-white/20 uppercase">Navigation Protocol</p>
                    <p class="text-[10px] font-medium text-white/40">SCROLL TO ORBIT • CLICK TO DRIFT</p>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-[10px] font-bold tracking-[0.3em] text-white/20 uppercase">Location</p>
                        <p class="text-[10px] font-medium text-white/40">DEEP SPACE HUB-01</p>
                    </div>
                    <div class="w-[1px] h-8 bg-white/5"></div>
                    <div class="text-[10px] font-bold text-cyan-500/50">V.2.0.4</div>
                </div>
            </footer>

        </div>

    </body>
</html>
