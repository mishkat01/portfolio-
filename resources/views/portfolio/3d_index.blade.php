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

        <div id="ui-layer" class="flex flex-col min-h-screen">
            <!-- Header -->
            <header class="flex justify-between items-center interactive-ui p-6 md:p-10 relative z-50">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <span class="text-xl font-bold text-white">S</span>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-10 text-[10px] font-bold tracking-[0.3em]">
                    <a href="#" class="nav-link text-white/40 hover:text-white transition-colors duration-300" onclick="window.cameraTo('projects'); return false;">PROJECTS</a>
                    <a href="#" class="nav-link text-white/40 hover:text-white transition-colors duration-300" onclick="window.cameraTo('skills'); return false;">SKILLS</a>
                    <a href="#" class="nav-link text-white/40 hover:text-white transition-colors duration-300" onclick="window.cameraTo('about'); return false;">ABOUT</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 glass-panel rounded-full text-purple-400 hover:text-white hover:bg-purple-500/20 transition-all">PANEL</a>
                    @endauth
                </nav>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-btn" class="md:hidden text-white/70 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M10 16h10"></path>
                    </svg>
                </button>
            </header>

            <!-- Centered Hero Content -->
            <main class="flex-grow flex items-center justify-center pointer-events-none">
                <div id="hero-content" class="text-center interactive-ui max-w-4xl px-6">
                    <div class="inline-block px-4 py-1.5 glass-panel rounded-full border-purple-500/30 text-[10px] font-bold tracking-[0.4em] text-purple-400 uppercase mb-8 shadow-lg shadow-purple-500/10">
                        Welcome to the Metaverse
                    </div>
                    <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 tracking-tighter leading-none">
                        {{ $profile->hero_title ?? 'Designer' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">&</span> <br>
                        Developer
                    </h1>
                    <p class="text-lg md:text-xl text-white/40 max-w-2xl mx-auto font-light leading-relaxed mb-12">
                        {{ $profile->subtitle ?? "Building high-performance digital universes with modern tech." }}
                    </p>

                    <!-- Statistics Bar -->
                    <div id="stats-bar" class="flex flex-wrap items-center justify-center gap-8 md:gap-16 mb-16 opacity-0 translate-y-4 transition-all duration-1000 delay-500">
                        <div class="text-center group">
                            <div class="text-3xl md:text-4xl font-bold text-white mb-2 group-hover:text-purple-400 transition-colors" id="stat-projects">0</div>
                            <div class="text-[8px] font-bold tracking-[0.4em] text-white/30 uppercase">Projects Completed</div>
                        </div>
                        <div class="w-[1px] h-10 bg-white/10 hidden md:block"></div>
                        <div class="text-center group">
                            <div class="text-3xl md:text-4xl font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors" id="stat-skills">0</div>
                            <div class="text-[8px] font-bold tracking-[0.4em] text-white/30 uppercase">Tech Domains</div>
                        </div>
                        <div class="w-[1px] h-10 bg-white/10 hidden md:block"></div>
                        <div class="text-center group">
                            <div class="text-3xl md:text-4xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors" id="stat-status">ACTIVE</div>
                            <div class="text-[8px] font-bold tracking-[0.4em] text-white/30 uppercase">System Status</div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-6">
                        <button onclick="window.cameraTo('projects')" class="w-full md:w-auto px-10 py-4 bg-white text-black text-sm font-black tracking-widest rounded-2xl hover:bg-purple-500 hover:text-white transition-all transform hover:-translate-y-2">
                            EXPLORE GALAXY
                        </button>
                        <a href="#about" onclick="window.cameraTo('about'); return false;" class="w-full md:w-auto px-10 py-4 glass-panel text-white text-sm font-bold tracking-widest rounded-2xl hover:bg-white/5 transition-all">
                            MY STORY
                        </a>
                    </div>
                </div>
            </main>

            <!-- Project Side Drawer (Remains similar but themed) -->
            <div id="project-modal" class="fixed right-0 top-0 h-full w-full md:w-[500px] glass-panel border-l border-purple-500/10 p-12 transform translate-x-full transition-all duration-700 ease-in-out interactive-ui overflow-y-auto">
                <button id="close-modal" class="group absolute top-10 right-10 text-white/30 hover:text-white transition-colors">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <div id="modal-content" class="mt-8">
                    <div class="mb-10">
                        <span id="p-tech" class="text-[10px] font-bold tracking-[0.3em] text-purple-400 uppercase mb-2 block">System // Tech</span>
                        <h2 class="text-5xl font-bold text-white mb-6 tracking-tight leading-tight" id="p-title">Project</h2>
                    </div>

                    <div class="relative group mb-10 overflow-hidden rounded-3xl border border-white/5 bg-black/40">
                        <img src="" id="p-image" class="w-full h-80 object-cover transition-transform duration-1000 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    </div>

                    <p class="text-xl leading-relaxed text-white/60 mb-12 font-light" id="p-desc">Description</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" id="p-link" target="_blank" class="flex items-center justify-center py-5 bg-purple-600 text-white text-xs font-black tracking-widest rounded-2xl hover:bg-purple-500 transition-all transform hover:-translate-y-1">
                            VIEW LIVE
                        </a>
                        <a href="#" id="p-github" target="_blank" class="flex items-center justify-center py-5 glass-panel text-white text-xs font-black tracking-widest rounded-2xl hover:bg-white/10 transition-all transform hover:-translate-y-1">
                            SOURCE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced About Section (Appears at end of scroll) -->
            <div id="about-section" class="absolute inset-0 flex items-center justify-center p-8 transition-all duration-1000 opacity-0 pointer-events-none z-0 interactive-ui">
                <div class="max-w-2xl glass-panel p-16 rounded-[48px] border border-purple-500/10 text-center transform scale-95 transition-transform duration-1000">
                    <div class="relative inline-block mb-10">
                        <div class="absolute inset-[-20px] bg-purple-500/30 blur-3xl rounded-full animate-pulse"></div>
                        <img src="{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->hero_title) . '&background=7c3aed&color=fff' }}" 
                             class="w-40 h-40 rounded-[40px] relative z-10 border border-white/10 shadow-2xl object-cover hover:rotate-3 transition-transform duration-500">
                    </div>
                    
                    <h2 class="text-5xl font-bold mb-4 tracking-tight">{{ $profile->hero_title }}</h2>
                    <h3 class="text-lg text-purple-400 font-bold tracking-[0.4em] uppercase mb-10">{{ $profile->subtitle }}</h3>
                    
                    <p class="text-white/50 leading-relaxed mb-14 font-light text-xl">
                        {{ $profile->about_text ?? "Creating cinematic web experiences." }}
                    </p>

                    <div class="flex justify-center space-x-12">
                        @if($profile->resume_url)
                            <a href="{{ $profile->resume_url }}" target="_blank" class="text-[10px] font-black tracking-[0.4em] text-white/30 hover:text-purple-400 transition-colors uppercase">Resume</a>
                        @endif
                        @if(isset($profile->social_links['github']))
                            <a href="{{ $profile->social_links['github'] }}" target="_blank" class="text-[10px] font-black tracking-[0.4em] text-white/30 hover:text-purple-400 transition-colors uppercase">GitHub</a>
                        @endif
                        @if(isset($profile->social_links['linkedin']))
                            <a href="{{ $profile->social_links['linkedin'] }}" target="_blank" class="text-[10px] font-black tracking-[0.4em] text-white/30 hover:text-purple-400 transition-colors uppercase">LinkedIn</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Enhanced Footer -->
            <footer class="mt-auto flex justify-between items-end interactive-ui p-6 md:p-10">
                <div class="space-y-2 opacity-30">
                    <p class="text-[8px] font-bold tracking-[0.5em] text-white uppercase">System Status</p>
                    <p class="text-[8px] font-medium text-white tracking-[0.2em]">ORBITAL VELOCITY: 17,500 MPH</p>
                </div>
                
                <div class="flex items-center space-x-8">
                    <div class="text-right opacity-30 hidden md:block">
                        <p class="text-[8px] font-bold tracking-[0.5em] text-white uppercase">Coordinate Info</p>
                        <p class="text-[8px] font-medium text-white tracking-[0.2em]">RA 18H 36M 56S | DEC +38° 47′ 1″</p>
                    </div>
                    <div class="w-[1px] h-10 bg-white/10"></div>
                    <div class="text-[10px] font-black text-purple-500/50 tracking-tighter italic">V.GALAXY-3D</div>
                </div>
            </footer>

        </div>

    </body>
</html>
