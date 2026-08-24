<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyBee 2.0 - Vibrant AI Study Workspace & Exam Copilot</title>
    <meta name="description" content="StudyBee 2.0: Modern AI study copilot and adaptive learning workspace for university and college students. Features DeepSolve, The Examiner, and Method Studio.">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN with Custom Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        coral: {
                            500: '#ec4899',
                            600: '#db2777',
                        },
                        purple: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Favicon: StudyBee Mascot -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 240 210'><defs><linearGradient id='bg' x1='60' y1='60' x2='180' y2='180' gradientUnits='userSpaceOnUse'><stop offset='0%25' stop-color='%23FDE047'/><stop offset='100%25' stop-color='%23D97706'/></linearGradient><linearGradient id='st' x1='0' y1='0' x2='0' y2='1'><stop offset='0%25' stop-color='%231E1B4B'/><stop offset='100%25' stop-color='%230F172A'/></linearGradient><clipPath id='cp'><ellipse cx='120' cy='124' rx='52' ry='46'/></clipPath></defs><g><path d='M92 92C60 55 42 38 48 24C54 10 76 12 96 42C104 54 103 76 92 92Z' fill='%23BAE6FD' stroke='%23FFF' stroke-width='3'/><path d='M148 92C180 55 198 38 192 24C186 10 164 12 144 42C136 54 137 76 148 92Z' fill='%23BAE6FD' stroke='%23FFF' stroke-width='3'/></g><path d='M104 74C95 56 86 48 80 44' fill='none' stroke='%231E1B4B' stroke-width='4' stroke-linecap='round'/><circle cx='78' cy='42' r='7' fill='%23F59E0B'/><path d='M136 74C145 56 154 48 160 44' fill='none' stroke='%231E1B4B' stroke-width='4' stroke-linecap='round'/><circle cx='162' cy='42' r='7' fill='%23F59E0B'/><ellipse cx='120' cy='124' rx='52' ry='46' fill='url(%23bg)'/><g clip-path='url(%23cp)'><path d='M60 108C80 114 160 114 180 108L180 122C160 128 80 128 60 122Z' fill='url(%23st)'/><path d='M62 136C82 142 158 142 178 136L178 150C158 156 82 156 62 150Z' fill='url(%23st)'/><path d='M80 162C95 167 145 167 160 162L160 174C145 178 95 178 80 174Z' fill='url(%23st)'/></g><ellipse cx='102' cy='106' rx='8' ry='11' fill='%230F172A'/><circle cx='100' cy='102' r='3' fill='%23FFF'/><ellipse cx='138' cy='106' rx='8' ry='11' fill='%230F172A'/><circle cx='136' cy='102' r='3' fill='%23FFF'/><ellipse cx='90' cy='116' rx='7' ry='4.5' fill='%23FB7185'/><ellipse cx='150' cy='116' rx='7' ry='4.5' fill='%23FB7185'/><path d='M113 115Q120 123 127 115' fill='none' stroke='%230F172A' stroke-width='3' stroke-linecap='round'/><g transform='translate(120, 68) rotate(-4) translate(-120, -68)'><ellipse cx='120' cy='72' rx='16' ry='6' fill='%231E1B4B'/><polygon points='120,52 154,64 120,74 86,64' fill='%234338CA' stroke='%236366F1' stroke-width='1.5'/><circle cx='120' cy='63' r='3' fill='%23FBBF24'/></g></svg>">
    
    <!-- Custom Helper Styles (3D Card flips, transitions, scrollbars) -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-[#FAF9FD] text-slate-800 font-sans antialiased min-h-screen overflow-x-hidden">
    <div id="app-layout" class="flex flex-col h-screen overflow-hidden">
        
        <!-- Top Navbar Header with Pastel Gradient Mesh -->
        <header class="h-16 bg-white/85 backdrop-blur-md border-b border-slate-100/90 px-6 flex items-center justify-between z-50 flex-shrink-0 shadow-sm shadow-indigo-100/20 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-50/40 via-purple-50/30 to-pink-50/20 pointer-events-none"></div>
            
            <div class="flex items-center gap-4 relative z-10">
                <button id="sidebarToggleBtn" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/80 rounded-xl transition-all duration-200" title="Toggle Navigation">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                
                <div id="headerHomeBtn" class="flex items-center gap-2.5 cursor-pointer select-none group py-1" title="StudyBee AI Study Agent">
                    <div class="relative flex flex-col items-center justify-center header-mascot-container">
                        <!-- High-Fidelity Scalable Header Bee Mascot (Floating & Suspended) -->
                        <svg class="w-10 h-10 header-bee-svg filter drop-shadow-xs" viewBox="0 0 240 210" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="hBeeBodyGrad" x1="60" y1="60" x2="180" y2="180" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#FDE047" />
                                    <stop offset="35%" stop-color="#FBBF24" />
                                    <stop offset="75%" stop-color="#F59E0B" />
                                    <stop offset="100%" stop-color="#D97706" />
                                </linearGradient>
                                <linearGradient id="hBeeStripeGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#1E1B4B" />
                                    <stop offset="100%" stop-color="#0F172A" />
                                </linearGradient>
                                <linearGradient id="hBeeWingGradL" x1="40" y1="30" x2="110" y2="100" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#E0F2FE" stop-opacity="0.95" />
                                    <stop offset="60%" stop-color="#BAE6FD" stop-opacity="0.75" />
                                    <stop offset="100%" stop-color="#7DD3FC" stop-opacity="0.45" />
                                </linearGradient>
                                <linearGradient id="hBeeWingGradR" x1="200" y1="30" x2="130" y2="100" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#E0F2FE" stop-opacity="0.95" />
                                    <stop offset="60%" stop-color="#BAE6FD" stop-opacity="0.75" />
                                    <stop offset="100%" stop-color="#7DD3FC" stop-opacity="0.45" />
                                </linearGradient>
                                <linearGradient id="hBeeCapGrad" x1="80" y1="40" x2="160" y2="70" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#4338CA" />
                                    <stop offset="100%" stop-color="#312E81" />
                                </linearGradient>
                                <radialGradient id="hBeeAntennaGlow" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#FEF08A" />
                                    <stop offset="60%" stop-color="#F59E0B" />
                                    <stop offset="100%" stop-color="#D97706" />
                                </radialGradient>
                                <radialGradient id="hBeeCheekGlow" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#FB7185" stop-opacity="0.9" />
                                    <stop offset="100%" stop-color="#FB7185" stop-opacity="0" />
                                </radialGradient>
                                <clipPath id="hBeeBodyClip">
                                    <ellipse cx="120" cy="124" rx="52" ry="46" />
                                </clipPath>
                            </defs>

                            <!-- Wings with Flutter Animation -->
                            <g class="h-bee-wing-left">
                                <path d="M 92 92 C 60 55 42 38 48 24 C 54 10 76 12 96 42 C 104 54 103 76 92 92 Z" fill="url(#hBeeWingGradL)" stroke="#FFFFFF" stroke-width="2.5" />
                                <path d="M 68 34 C 76 46 84 62 88 78" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" opacity="0.7" />
                                <path d="M 85 96 C 65 80 52 82 54 94 C 56 104 70 106 85 98 Z" fill="url(#hBeeWingGradL)" stroke="#FFFFFF" stroke-width="1.5" opacity="0.8" />
                            </g>
                            <g class="h-bee-wing-right">
                                <path d="M 148 92 C 180 55 198 38 192 24 C 186 10 164 12 144 42 C 136 54 137 76 148 92 Z" fill="url(#hBeeWingGradR)" stroke="#FFFFFF" stroke-width="2.5" />
                                <path d="M 172 34 C 164 46 156 62 152 78" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" opacity="0.7" />
                                <path d="M 155 96 C 175 80 188 82 186 94 C 184 104 170 106 155 98 Z" fill="url(#hBeeWingGradR)" stroke="#FFFFFF" stroke-width="1.5" opacity="0.8" />
                            </g>

                            <!-- Antennae -->
                            <g class="h-bee-antenna-left">
                                <path d="M 104 74 C 95 56 86 48 80 44" fill="none" stroke="#1E1B4B" stroke-width="4" stroke-linecap="round" />
                                <circle cx="78" cy="42" r="7" fill="url(#hBeeAntennaGlow)" stroke="#F59E0B" stroke-width="1.5" />
                            </g>
                            <g class="h-bee-antenna-right">
                                <path d="M 136 74 C 145 56 154 48 160 44" fill="none" stroke="#1E1B4B" stroke-width="4" stroke-linecap="round" />
                                <circle cx="162" cy="42" r="7" fill="url(#hBeeAntennaGlow)" stroke="#F59E0B" stroke-width="1.5" />
                            </g>

                            <!-- Stinger -->
                            <path d="M 120 184 L 114 168 L 126 168 Z" fill="#0F172A" />

                            <!-- Body -->
                            <ellipse cx="120" cy="124" rx="52" ry="46" fill="url(#hBeeBodyGrad)" />
                            <g clip-path="url(#hBeeBodyClip)">
                                <path d="M 60 108 C 80 114 160 114 180 108 L 180 122 C 160 128 80 128 60 122 Z" fill="url(#hBeeStripeGrad)" />
                                <path d="M 62 136 C 82 142 158 142 178 136 L 178 150 C 158 156 82 156 62 150 Z" fill="url(#hBeeStripeGrad)" />
                                <path d="M 80 162 C 95 167 145 167 160 162 L 160 174 C 145 178 95 178 80 174 Z" fill="url(#hBeeStripeGrad)" />
                                <ellipse cx="120" cy="85" rx="34" ry="8" fill="#FFFFFF" opacity="0.35" />
                            </g>

                            <!-- Eyes -->
                            <g>
                                <ellipse class="bee-eye-pupil" cx="102" cy="106" rx="8.5" ry="11.5" fill="#0F172A" />
                                <circle cx="100" cy="102" r="3.5" fill="#FFFFFF" />
                                <circle cx="104" cy="111" r="1.5" fill="#FFFFFF" />
                            </g>
                            <g>
                                <ellipse class="bee-eye-pupil" cx="138" cy="106" rx="8.5" ry="11.5" fill="#0F172A" />
                                <circle cx="136" cy="102" r="3.5" fill="#FFFFFF" />
                                <circle cx="140" cy="111" r="1.5" fill="#FFFFFF" />
                            </g>

                            <!-- Cheeks -->
                            <ellipse cx="89" cy="116" rx="7.5" ry="5" fill="url(#hBeeCheekGlow)" />
                            <ellipse cx="151" cy="116" rx="7.5" ry="5" fill="url(#hBeeCheekGlow)" />

                            <!-- Cute Smile -->
                            <path d="M 113 115 Q 120 123 127 115" fill="none" stroke="#0F172A" stroke-width="3" stroke-linecap="round" />
                            <path d="M 116 117 Q 120 122 124 117 Z" fill="#FB7185" opacity="0.9" />

                            <!-- Academic Mortarboard Cap -->
                            <g transform="translate(120, 68) rotate(-4) translate(-120, -68)">
                                <ellipse cx="120" cy="72" rx="16" ry="6" fill="#1E1B4B" />
                                <polygon points="120,52 154,64 120,74 86,64" fill="url(#hBeeCapGrad)" stroke="#6366F1" stroke-width="1.5" />
                                <circle cx="120" cy="63" r="3" fill="#FBBF24" />
                                <path d="M 120 63 C 132 65 142 72 144 82" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" />
                                <rect x="141" y="81" width="5" height="7" rx="1.5" fill="#F59E0B" />
                            </g>
                        </svg>

                        <!-- Micro Spatial Shadow Underneath Floating Bee -->
                        <div class="header-bee-shadow"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-lg text-slate-900 tracking-tight group-hover:text-amber-600 transition-colors">StudyBee</span>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 border border-amber-200/60 shadow-xs">2.0</span>
                    </div>
                </div>
            </div>

            <!-- Workspace Selector Center -->
            <div class="flex items-center gap-2 relative z-10">
                <div class="relative flex items-center">
                    <div class="absolute left-3 text-indigo-500 pointer-events-none">
                        <i data-lucide="folder-git-2" class="w-4 h-4"></i>
                    </div>
                    <select id="workspaceSelect" class="appearance-none bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200/80 text-slate-700 text-xs font-semibold pl-9 pr-8 py-2 rounded-2xl cursor-pointer transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 max-w-[240px] sm:max-w-[280px]">
                        <!-- Populated dynamically via JS -->
                    </select>
                    <div class="absolute right-3 text-slate-400 pointer-events-none">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <button id="openNewWorkspaceBtn" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-2xl border border-indigo-100 transition-all active:scale-95 flex-shrink-0" title="Create New Study Vault / Subject">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- User Status & Supporter Right -->
            <div class="flex items-center gap-3 relative z-10">
                <div class="hidden sm:flex items-center gap-2 bg-emerald-50/80 border border-emerald-100/80 px-3 py-1.5 rounded-full text-xs font-semibold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="headerReadinessScore">Readiness: 88%</span>
                </div>
                
                <button id="openSupportBtn" class="flex items-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white hover:from-amber-600 hover:to-orange-600 px-4 py-2 rounded-2xl text-xs font-bold shadow-md shadow-amber-500/20 transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>EcoCash Pro</span>
                </button>
            </div>
        </header>

        <!-- Main Body Wrapper -->
        <div class="flex flex-1 overflow-hidden relative">
            
            <!-- Collapsible Sidebar -->
            <aside id="mainSidebar" class="w-64 bg-white border-r border-slate-100/80 flex flex-col justify-between p-4 transition-all duration-300 ease-in-out z-40 flex-shrink-0 shadow-sm">
                <nav class="flex flex-col gap-1.5">
                    <button class="nav-item active flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-dashboard" data-tooltip="Dashboard">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">Dashboard</span>
                    </button>

                    <button class="nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-vault" data-tooltip="The Vault">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="library" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">The Vault</span>
                    </button>

                    <button class="nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-solve" data-tooltip="DeepSolve">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-purple-50 text-purple-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="cpu" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">DeepSolve</span>
                    </button>

                    <button class="nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-examiner" data-tooltip="The Examiner">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-pink-50 text-pink-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="file-check-2" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">The Examiner</span>
                    </button>

                    <button class="nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-methods" data-tooltip="Method Studio">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">Method Studio</span>
                    </button>

                    <button class="nav-item flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:bg-slate-50 group" data-view="view-arena" data-tooltip="Exam Arena">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform">
                            <i data-lucide="trophy" class="w-4 h-4"></i>
                        </div>
                        <span class="nav-label">Exam Arena</span>
                    </button>
                </nav>

                <!-- Sidebar Footer User Card -->
                <div class="pt-4 border-t border-slate-100/90 flex flex-col gap-2">
                    <div class="flex items-center gap-3 p-2 rounded-2xl bg-slate-50/80 border border-slate-100">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shadow-xs">
                            M
                        </div>
                        <div class="user-info flex flex-col overflow-hidden">
                            <span class="text-xs font-bold text-slate-900 truncate">Mphathisi Moyo</span>
                            <span class="text-[11px] font-medium text-indigo-600">Full Access Pass</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Dynamic Workspace View -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">

                <!-- =========================================================
                     LANDING POINT / DASHBOARD
                     ========================================================= -->
                <section id="view-dashboard" class="view-pane active-view max-w-5xl mx-auto space-y-8 pb-10">
                    
                    <!-- Hero Welcome Section with Hovering Animated SVG Bee Mascot -->
                    <div class="flex flex-col items-center justify-center text-center pt-2 sm:pt-4">
                        
                        <!-- Animated Bee Mascot Container -->
                        <div class="bee-mascot-wrapper mb-2">
                            <!-- Floating Interactive Speech Bubble -->
                            <div id="beeSpeechBubble" class="bee-speech-bubble px-4 py-2 rounded-2xl text-xs font-bold text-amber-900 mb-3 flex items-center gap-2 select-none" title="Click me for study tips!">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500 flex-shrink-0 animate-pulse"></i>
                                <span id="beeSpeechText">Bzzz! Ready to crush today's study goals? 🚀</span>
                            </div>

                            <!-- High-Fidelity Scalable SVG Bee -->
                            <svg id="studyBeeMascot" class="studybee-mascot-svg" viewBox="0 0 240 210" fill="none" xmlns="http://www.w3.org/2000/svg" title="StudyBee AI Companion">
                                <defs>
                                    <!-- Golden Amber Body Gradient -->
                                    <linearGradient id="beeBodyGrad" x1="60" y1="60" x2="180" y2="180" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FDE047" />
                                        <stop offset="35%" stop-color="#FBBF24" />
                                        <stop offset="75%" stop-color="#F59E0B" />
                                        <stop offset="100%" stop-color="#D97706" />
                                    </linearGradient>

                                    <!-- Striped Dark Gradient -->
                                    <linearGradient id="stripeGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#1E1B4B" />
                                        <stop offset="100%" stop-color="#0F172A" />
                                    </linearGradient>

                                    <!-- Translucent Wings Gradient -->
                                    <linearGradient id="wingGradL" x1="40" y1="30" x2="110" y2="100" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#E0F2FE" stop-opacity="0.92" />
                                        <stop offset="60%" stop-color="#BAE6FD" stop-opacity="0.65" />
                                        <stop offset="100%" stop-color="#7DD3FC" stop-opacity="0.35" />
                                    </linearGradient>
                                    <linearGradient id="wingGradR" x1="200" y1="30" x2="130" y2="100" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#E0F2FE" stop-opacity="0.92" />
                                        <stop offset="60%" stop-color="#BAE6FD" stop-opacity="0.65" />
                                        <stop offset="100%" stop-color="#7DD3FC" stop-opacity="0.35" />
                                    </linearGradient>

                                    <!-- Mortarboard Cap Gradient -->
                                    <linearGradient id="capGrad" x1="80" y1="40" x2="160" y2="70" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#4338CA" />
                                        <stop offset="100%" stop-color="#312E81" />
                                    </linearGradient>

                                    <!-- Antenna Golden Glow -->
                                    <radialGradient id="antennaGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#FEF08A" />
                                        <stop offset="60%" stop-color="#F59E0B" />
                                        <stop offset="100%" stop-color="#D97706" />
                                    </radialGradient>

                                    <!-- Rosy Cheeks -->
                                    <radialGradient id="cheekGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#FB7185" stop-opacity="0.85" />
                                        <stop offset="100%" stop-color="#FB7185" stop-opacity="0" />
                                    </radialGradient>
                                </defs>

                                <!-- Ambient Floating Sparkles -->
                                <g class="bee-sparkle bee-sparkle-1">
                                    <path d="M 45 60 Q 48 68 56 71 Q 48 74 45 82 Q 42 74 34 71 Q 42 68 45 60 Z" fill="#FBBF24" opacity="0.85" />
                                    <circle cx="58" cy="95" r="2.5" fill="#F59E0B" opacity="0.6" />
                                </g>
                                <g class="bee-sparkle bee-sparkle-2">
                                    <path d="M 195 55 Q 198 62 205 64 Q 198 66 195 73 Q 192 66 185 64 Q 192 62 195 55 Z" fill="#FBBF24" opacity="0.85" />
                                    <circle cx="180" cy="90" r="2" fill="#FDE047" opacity="0.7" />
                                </g>
                                <g class="bee-sparkle bee-sparkle-3">
                                    <circle cx="50" cy="130" r="3" fill="#F59E0B" opacity="0.5" />
                                    <circle cx="192" cy="138" r="2.5" fill="#FBBF24" opacity="0.6" />
                                </g>

                                <!-- Wings (Behind Body) -->
                                <!-- Left Wing -->
                                <g class="bee-wing-left">
                                    <path d="M 92 92 C 60 55 42 38 48 24 C 54 10 76 12 96 42 C 104 54 103 76 92 92 Z" fill="url(#wingGradL)" stroke="#FFFFFF" stroke-width="2" />
                                    <path d="M 68 34 C 76 46 84 62 88 78" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round" opacity="0.7" />
                                    <path d="M 75 48 C 66 54 60 62 58 70" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" opacity="0.5" />
                                    <path d="M 85 96 C 65 80 52 82 54 94 C 56 104 70 106 85 98 Z" fill="url(#wingGradL)" stroke="#FFFFFF" stroke-width="1.5" opacity="0.8" />
                                </g>

                                <!-- Right Wing -->
                                <g class="bee-wing-right">
                                    <path d="M 148 92 C 180 55 198 38 192 24 C 186 10 164 12 144 42 C 136 54 137 76 148 92 Z" fill="url(#wingGradR)" stroke="#FFFFFF" stroke-width="2" />
                                    <path d="M 172 34 C 164 46 156 62 152 78" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round" opacity="0.7" />
                                    <path d="M 165 48 C 174 54 180 62 182 70" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" opacity="0.5" />
                                    <path d="M 155 96 C 175 80 188 82 186 94 C 184 104 170 106 155 98 Z" fill="url(#wingGradR)" stroke="#FFFFFF" stroke-width="1.5" opacity="0.8" />
                                </g>

                                <!-- Antennae -->
                                <!-- Left Antenna -->
                                <g class="bee-antenna-left">
                                    <path d="M 104 74 C 95 56 86 48 80 44" fill="none" stroke="#1E1B4B" stroke-width="3.5" stroke-linecap="round" />
                                    <circle cx="78" cy="42" r="6" fill="url(#antennaGlow)" stroke="#F59E0B" stroke-width="1.5" />
                                </g>
                                <!-- Right Antenna -->
                                <g class="bee-antenna-right">
                                    <path d="M 136 74 C 145 56 154 48 160 44" fill="none" stroke="#1E1B4B" stroke-width="3.5" stroke-linecap="round" />
                                    <circle cx="162" cy="42" r="6" fill="url(#antennaGlow)" stroke="#F59E0B" stroke-width="1.5" />
                                </g>

                                <!-- Bee Stinger -->
                                <path d="M 120 184 L 115 168 L 125 168 Z" fill="#0F172A" />

                                <!-- Bee Main Plump Body -->
                                <g id="beeBodyGroup">
                                    <ellipse cx="120" cy="124" rx="52" ry="46" fill="url(#beeBodyGrad)" />
                                    <clipPath id="bodyClipGrad">
                                        <ellipse cx="120" cy="124" rx="52" ry="46" />
                                    </clipPath>

                                    <g clip-path="url(#bodyClipGrad)">
                                        <!-- Stripes -->
                                        <path d="M 60 108 C 80 114 160 114 180 108 L 180 122 C 160 128 80 128 60 122 Z" fill="url(#stripeGrad)" />
                                        <path d="M 62 136 C 82 142 158 142 178 136 L 178 150 C 158 156 82 156 62 150 Z" fill="url(#stripeGrad)" />
                                        <path d="M 80 162 C 95 167 145 167 160 162 L 160 174 C 145 178 95 178 80 174 Z" fill="url(#stripeGrad)" />
                                        <!-- 3D Highlight Arc -->
                                        <ellipse cx="120" cy="85" rx="34" ry="8" fill="#FFFFFF" opacity="0.32" />
                                    </g>
                                </g>

                                <!-- Eyes & Face -->
                                <g class="bee-eye-left">
                                    <ellipse class="bee-eye-pupil" cx="102" cy="106" rx="8" ry="11" fill="#0F172A" />
                                    <circle cx="100" cy="102" r="3.5" fill="#FFFFFF" />
                                    <circle cx="104" cy="111" r="1.5" fill="#FFFFFF" />
                                </g>
                                <g class="bee-eye-right">
                                    <ellipse class="bee-eye-pupil" cx="138" cy="106" rx="8" ry="11" fill="#0F172A" />
                                    <circle cx="136" cy="102" r="3.5" fill="#FFFFFF" />
                                    <circle cx="140" cy="111" r="1.5" fill="#FFFFFF" />
                                </g>

                                <!-- Rosy Cheeks -->
                                <ellipse cx="90" cy="116" rx="7" ry="4.5" fill="url(#cheekGlow)" />
                                <ellipse cx="150" cy="116" rx="7" ry="4.5" fill="url(#cheekGlow)" />

                                <!-- Cute Smile -->
                                <path d="M 113 115 Q 120 123 127 115" fill="none" stroke="#0F172A" stroke-width="3" stroke-linecap="round" />
                                <path d="M 116 117 Q 120 122 124 117 Z" fill="#FB7185" opacity="0.85" />

                                <!-- Academic Mortarboard Cap -->
                                <g id="gradCap" transform="translate(120, 68) rotate(-4) translate(-120, -68)">
                                    <ellipse cx="120" cy="72" rx="16" ry="6" fill="#1E1B4B" />
                                    <polygon points="120,52 154,64 120,74 86,64" fill="url(#capGrad)" stroke="#6366F1" stroke-width="1.5" />
                                    <circle cx="120" cy="63" r="3" fill="#FBBF24" />
                                    <path d="M 120 63 C 132 65 142 72 144 82" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" />
                                    <rect x="141" y="81" width="5" height="7" rx="1.5" fill="#F59E0B" />
                                </g>
                            </svg>

                            <!-- Coordinated 3D Hover Shadow -->
                            <div class="bee-shadow"></div>
                        </div>

                        <!-- Greeting Headline & Bio -->
                        <h1 id="landingGreetingTitle" class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Good day, <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-amber-500 bg-clip-text text-transparent">Scholar</span> ✨
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto mt-2 font-medium leading-relaxed">
                            What would you like to master today? Chat with course slides, solve mathematical proofs, audit essays against rubrics, or kickstart active recall rituals.
                        </p>
                    </div>

                    <!-- Gemini / ChatGPT Style Smart Omnibar -->
                    <div class="omnibar-container rounded-3xl p-3 sm:p-4 bg-white border border-indigo-100/90 shadow-xl max-w-3xl mx-auto space-y-3">
                        
                        <!-- Mode Selector Chips inside Omnibar -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-[11px] font-bold">
                            <span class="text-slate-400 pl-1 mr-1 hidden sm:inline">Mode:</span>
                            <button type="button" class="omni-mode-chip active bg-indigo-600 text-white px-3 py-1 rounded-xl transition-all shadow-xs" data-mode="auto">
                                ✨ Smart Assistant
                            </button>
                            <button type="button" class="omni-mode-chip bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-xl transition-all" data-mode="solve">
                                ⚡ DeepSolve
                            </button>
                            <button type="button" class="omni-mode-chip bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-xl transition-all" data-mode="vault">
                                📚 Vault Notes
                            </button>
                            <button type="button" class="omni-mode-chip bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-xl transition-all" data-mode="examiner">
                                📝 Essay Rubric
                            </button>
                            <button type="button" class="omni-mode-chip bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-xl transition-all" data-mode="feynman">
                                🧠 Feynman
                            </button>
                        </div>

                        <!-- Central Omnibar Input Box -->
                        <div class="px-1">
                            <textarea id="dashboardOmniInput" class="w-full bg-transparent border-0 text-xs sm:text-sm font-medium focus:outline-none placeholder:text-slate-400 resize-none py-1 text-slate-800 leading-relaxed" rows="2" placeholder="Ask anything, solve a calculus proof, audit an essay, or ask a question from your course notes..."></textarea>
                        </div>

                        <!-- Omnibar Bottom Toolbar -->
                        <div class="flex items-center justify-between gap-3 pt-1 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <button type="button" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200/80 hover:border-indigo-200 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-all active:scale-95" onclick="document.getElementById('vaultFileInput').click()" title="Import PDF or TXT Notes">
                                    <i data-lucide="paperclip" class="w-3.5 h-3.5 text-indigo-500"></i>
                                    <span>Import Notes</span>
                                </button>
                                
                                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-indigo-50/70 border border-indigo-100 text-[11px] font-semibold text-indigo-700">
                                    <i data-lucide="folder-git-2" class="w-3 h-3 text-indigo-500"></i>
                                    <span id="omniActiveWorkspaceName">Active Workspace</span>
                                </span>
                            </div>

                            <button id="dashboardOmniSendBtn" class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-md shadow-indigo-200 transition-all hover:scale-105 active:scale-95">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                <span>Launch</span>
                            </button>
                        </div>
                    </div>

                    <!-- Suggested Prompts / Inspiration Starters Chips -->
                    <div class="max-w-3xl mx-auto space-y-2">
                        <div class="flex items-center gap-2 text-slate-400 text-xs font-semibold px-1">
                            <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span>Suggested Study Starters:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button class="prompt-chip bg-white border border-slate-200/80 px-3.5 py-2 rounded-2xl text-xs font-medium text-slate-700 shadow-xs flex items-center gap-2" data-prompt="Derive Newton's 2nd Law (F=ma) from rate of change of momentum" data-target="view-solve">
                                <span>📐 Derive Newton's 2nd Law & Momentum</span>
                            </button>
                            <button class="prompt-chip bg-white border border-slate-200/80 px-3.5 py-2 rounded-2xl text-xs font-medium text-slate-700 shadow-xs flex items-center gap-2" data-prompt="Explain Photosynthesis light-dependent vs light-independent reactions simply" data-target="view-methods" data-method="method-feynman">
                                <span>🌿 Explain Photosynthesis simply</span>
                            </button>
                            <button class="prompt-chip bg-white border border-slate-200/80 px-3.5 py-2 rounded-2xl text-xs font-medium text-slate-700 shadow-xs flex items-center gap-2" data-prompt="Generate 5 high-yield exam practice questions on my uploaded notes" data-target="view-vault" data-vtab="vtab-quiz">
                                <span>📝 Practice Exam Questions</span>
                            </button>
                            <button class="prompt-chip bg-white border border-slate-200/80 px-3.5 py-2 rounded-2xl text-xs font-medium text-slate-700 shadow-xs flex items-center gap-2" data-prompt="Audit my essay thesis and clarity against university grading criteria" data-target="view-examiner">
                                <span>✍️ Grade Essay Thesis & Rubric</span>
                            </button>
                        </div>
                    </div>

                    <!-- Feature Launchpad Grid (6 Core Quicklink Cards) -->
                    <div class="max-w-4xl mx-auto space-y-3">
                        <div class="flex items-center justify-between px-1">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Quick Launchpad & Study Tools</h3>
                            <span class="text-xs font-semibold text-indigo-600">Full Access 2.0</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            <!-- Card 1: The Vault -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-vault')">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="library" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">The Vault</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Chat with lecture slides, past papers, and syllabi with page citations and note indexing.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-indigo-600">
                                    <span>Notes & Q&A</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                            <!-- Card 2: DeepSolve -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-solve')">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="cpu" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-purple-600 transition-colors">DeepSolve</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Step-by-step mathematical proofs, science solutions, code derivations, and deep reasoning.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-purple-600">
                                    <span>Problem Solver</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                            <!-- Card 3: The Examiner -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-examiner')">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-pink-600 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-pink-600 transition-colors">The Examiner</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Score essay and assignment drafts against official marking schemes before submission.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-pink-600">
                                    <span>Rubric Grader</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                            <!-- Card 4: Method Studio -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-methods')">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Method Studio</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Active cognitive rituals: Pomodoro sprints, Feynman teach-backs, and 5-min blurting audits.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-amber-600">
                                    <span>Study Rituals</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                            <!-- Card 5: Exam Arena -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-arena')">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="trophy" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Exam Arena</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Simulate authentic timed exams under realistic conditions with immediate score breakdown.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-emerald-600">
                                    <span>Mock Simulation</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                            <!-- Card 6: 3D Flashcards -->
                            <div class="feature-card rounded-3xl p-5 shadow-sm hover:shadow-xl flex flex-col justify-between group" onclick="switchView('view-vault', () => { document.querySelector('[data-vtab=\'vtab-cards\']')?.click(); })">
                                <div>
                                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xs">
                                        <i data-lucide="layers" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">3D Flashcards</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Spaced repetition flipcards generated automatically from your uploaded notes and syllabi.</p>
                                </div>
                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-100 text-xs font-bold text-blue-600">
                                    <span>Active Recall</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Workspace Snapshot & Quick Upload Banner -->
                    <div class="max-w-4xl mx-auto bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-amber-500/10 border border-indigo-100/90 rounded-3xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-white text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-xs border border-indigo-100">
                                <i data-lucide="sparkles" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Adaptive AI Study Engine Ready</h4>
                                <p class="text-[11px] text-slate-500">Groq Llama 3.3 70B active. Fast sub-second responses for academic mastery.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button class="bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs px-4 py-2 rounded-2xl border border-slate-200 shadow-xs transition-all" onclick="switchView('view-vault')">
                                Manage Notes
                            </button>
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-2xl shadow-md shadow-indigo-200 transition-all hover:scale-105" onclick="document.getElementById('vaultFileInput').click()">
                                + Upload New Slide
                            </button>
                        </div>
                    </div>

                </section>

                <!-- =========================================================
                     PILLAR 1: THE VAULT
                     ========================================================= -->
                <section id="view-vault" class="view-pane max-w-6xl mx-auto space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                                    <i data-lucide="library" class="w-5 h-5"></i>
                                </div>
                                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">The Vault</h1>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Upload lecture slides, past papers, and notes to generate summaries, interactive flashcards, quizzes, and citations.</p>
                        </div>

                        <button class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold text-xs px-5 py-2.5 rounded-2xl shadow-md shadow-indigo-200 transition-all duration-200 hover:-translate-y-0.5 active:scale-95" onclick="document.getElementById('vaultFileInput').click()">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                            <span>Upload Notes (PDF/TXT)</span>
                        </button>
                        <input type="file" id="vaultFileInput" style="display:none;" accept=".pdf,.txt,.docx" onchange="handleVaultUpload(event)">
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <!-- Left: Document Viewer -->
                        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                                        <h3 class="text-sm font-bold text-slate-900">Course Document Index</h3>
                                    </div>
                                    <span id="docCountBadge" class="text-[11px] font-semibold bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">1 Active Note</span>
                                </div>
                                <div id="documentViewer" class="space-y-3 max-h-[460px] overflow-y-auto pr-1">
                                    <!-- Populated dynamically via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Right: Interactive Study Deck -->
                        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 flex flex-col">
                            <!-- Tabs Header -->
                            <div class="flex gap-1.5 p-1 bg-slate-100/80 rounded-2xl mb-5">
                                <button class="vault-tab-btn active flex-1 flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900" data-vtab="vtab-chat">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                    <span>Chat with Notes</span>
                                </button>
                                <button class="vault-tab-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900" data-vtab="vtab-cards">
                                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                    <span>3D Flashcards</span>
                                </button>
                                <button class="vault-tab-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900" data-vtab="vtab-quiz">
                                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                                    <span>Practice Quiz</span>
                                </button>
                            </div>

                            <!-- VTab 1: RAG Chat -->
                            <div id="vtab-chat" class="vtab-content flex-1 flex flex-col justify-between">
                                <div id="vaultChatThread" class="h-80 overflow-y-auto space-y-3 pr-2 mb-4">
                                    <div class="p-3.5 rounded-2xl bg-indigo-50/60 border border-indigo-100/80 text-xs text-slate-700 leading-relaxed max-w-[90%]">
                                        👋 <b>Welcome to StudyBee Vault!</b> Ask any question about your course notes or syllabus. Responses include exact page citations.
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" id="vaultChatInput" class="flex-1 bg-slate-50 border border-slate-200 text-xs font-medium px-4 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-800" placeholder="Ask a question about your notes...">
                                    <button id="vaultChatSendBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-2xl shadow-md shadow-indigo-200 transition-all hover:scale-105 active:scale-95" title="Send Question">
                                        <i data-lucide="send" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- VTab 2: 3D Flashcards -->
                            <div id="vtab-cards" class="vtab-content flex-1 flex flex-col justify-between" style="display:none;">
                                <div class="flex items-center justify-between pb-2">
                                    <span id="flashcardCounter" class="text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">Card 1 of 2</span>
                                    <div class="flex items-center gap-2">
                                        <button id="markMasteredBtn" class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 px-2.5 py-1 rounded-xl transition-all" title="Mark this card as mastered">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            <span>Mastered</span>
                                        </button>
                                        <button onclick="flipActiveCard()" class="flex items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 px-2.5 py-1 rounded-xl transition-all" title="Click card or press Space to flip">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                            <span>Flip (Space)</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flashcard-stage my-2">
                                    <div id="activeFlashcard" class="flashcard-card cursor-pointer" onclick="flipActiveCard()">
                                        <div class="card-face card-front bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-3xl border border-indigo-800/40 shadow-xl flex flex-col justify-between">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-300">Question</span>
                                                <span class="text-[10px] text-indigo-200/60 font-mono">Active Recall</span>
                                            </div>
                                            <div id="cardQuestionText" class="text-sm font-semibold text-center my-auto leading-relaxed px-2">Click below to generate flashcards from your uploaded notes.</div>
                                            <span class="text-[11px] text-indigo-300/70 text-center flex items-center justify-center gap-1">
                                                <i data-lucide="mouse-pointer-click" class="w-3 h-3"></i> Click card to reveal answer
                                            </span>
                                        </div>
                                        <div class="card-face card-back bg-gradient-to-tr from-indigo-950 via-purple-950 to-slate-900 text-emerald-300 p-6 rounded-3xl border border-indigo-700/50 shadow-xl flex flex-col justify-between">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400">Answer & Explanation</span>
                                                <span class="text-[10px] text-emerald-200/60 font-mono">Verified Concept</span>
                                            </div>
                                            <div id="cardAnswerText" class="text-sm font-semibold text-center my-auto leading-relaxed text-white px-2">Explanation and key takeaways.</div>
                                            <span class="text-[11px] text-slate-400 text-center">Click to flip back</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between gap-2.5 pt-3">
                                    <button id="prevCardBtn" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-2xl transition-all">← Prev</button>
                                    <button id="generateCardsBtn" class="flex-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs py-2.5 rounded-2xl shadow-md shadow-indigo-200 transition-all flex items-center justify-center gap-1.5">
                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                        <span>Generate Cards</span>
                                    </button>
                                    <button id="nextCardBtn" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-2xl transition-all">Next →</button>
                                </div>
                            </div>

                            <!-- VTab 3: Practice Quiz -->
                            <div id="vtab-quiz" class="vtab-content flex-1 flex flex-col justify-between" style="display:none;">
                                <div id="quizContainer" class="min-h-[220px] max-h-[340px] overflow-y-auto space-y-3 pr-1">
                                    <div class="text-center py-10 text-xs text-slate-400">
                                        Click button below to generate practice questions.
                                    </div>
                                </div>
                                <button id="loadQuizBtn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-xs py-2.5 rounded-2xl shadow-md shadow-indigo-200 transition-all mt-4">Generate Practice Questions</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================
                     PILLAR 2: DEEPSOLVE
                     ========================================================= -->
                <section id="view-solve" class="view-pane max-w-4xl mx-auto space-y-6">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-purple-50 text-purple-600">
                                <i data-lucide="cpu" class="w-5 h-5"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">DeepSolve</h1>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Get instant step-by-step solutions, mathematical derivations, and conceptual explanations with deep reasoning.</p>
                    </div>

                    <!-- Subject Pills Row -->
                    <div class="flex flex-wrap gap-2">
                        <button class="subject-pill active bg-purple-100 text-purple-700 font-bold border border-purple-200 px-3.5 py-1.5 rounded-2xl text-xs transition-all hover:scale-105" data-subject="General">General</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Mathematics">Mathematics</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Physics">Physics</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Biology">Biology</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Chemistry">Chemistry</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Law">Law</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Economics">Economics</button>
                        <button class="subject-pill bg-white text-slate-600 border border-slate-200/80 px-3.5 py-1.5 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all" data-subject="Computer Science">Computer Science</button>
                    </div>

                    <!-- Problem Input Card -->
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4">
                        <textarea id="solveQuestionInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-slate-800 min-h-[130px] resize-y" placeholder="Type or paste your homework question, proof, or problem here..."></textarea>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                            <div id="deepReasoningToggle" class="deep-reasoning-toggle active flex items-center gap-3 px-4 py-2 rounded-2xl bg-purple-50 border border-purple-200/80 cursor-pointer select-none">
                                <div class="toggle-switch w-9 h-5 bg-purple-600 rounded-full relative transition-all">
                                    <div class="w-3.5 h-3.5 bg-white rounded-full absolute top-0.5 right-1 transition-all"></div>
                                </div>
                                <span class="text-xs font-bold text-purple-900">Deep Reasoning Mode (Exhaustive Proofs)</span>
                            </div>

                            <button id="runSolveBtn" class="flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-2xl shadow-lg shadow-purple-200 transition-all hover:-translate-y-0.5 active:scale-95 w-full sm:w-auto justify-center">
                                <i data-lucide="zap" class="w-4 h-4"></i>
                                <span>Solve Problem</span>
                            </button>
                        </div>
                    </div>

                    <!-- Solution Output Card -->
                    <div id="solveOutputContainer" style="display:none;" class="bg-white rounded-3xl border border-purple-100 p-6 shadow-xl shadow-purple-100/40 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <i data-lucide="sparkles" class="w-4 h-4 text-purple-600"></i>
                                <span class="text-xs font-bold text-purple-900 uppercase tracking-wider">Step-by-Step Derivation & Solution</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="solvedSubjectTag" class="text-[11px] font-bold bg-purple-50 text-purple-600 px-3 py-1 rounded-full">Physics</span>
                                <button id="copySolutionBtn" class="flex items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 px-2.5 py-1 rounded-xl transition-all" title="Copy Solution">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                        </div>
                        <div id="solutionTextBody" class="text-xs text-slate-700 leading-relaxed space-y-3"></div>
                    </div>
                </section>

                <!-- =========================================================
                     PILLAR 3: THE EXAMINER (PAPER GRADER)
                     ========================================================= -->
                <section id="view-examiner" class="view-pane max-w-6xl mx-auto space-y-6">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-pink-50 text-pink-600">
                                <i data-lucide="file-check-2" class="w-5 h-5"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">The Examiner</h1>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Grade assignment drafts against official marking schemes and rubrics to maximize marks before submission.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Input Draft Card -->
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Marking Scheme / Rubric Criteria:</label>
                                <input type="text" id="examinerRubricInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500" placeholder="e.g. Thesis & Structure (30%), Academic Accuracy (40%), Citations (30%)">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Assignment / Essay Draft:</label>
                                <textarea id="examinerDraftInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 min-h-[220px] resize-y" placeholder="Paste your essay draft here..."></textarea>
                            </div>

                            <button id="runGradePaperBtn" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-bold text-xs py-3 rounded-2xl shadow-lg shadow-pink-200 transition-all hover:-translate-y-0.5 active:scale-95">
                                <i data-lucide="award" class="w-4 h-4"></i>
                                <span>Evaluate Draft Against Rubric</span>
                            </button>
                        </div>

                        <!-- Feedback Report Card -->
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                                    <h3 class="text-sm font-bold text-slate-900">Examiner Feedback Report</h3>
                                    <div id="rubricScoreBadge" class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col items-center justify-center text-emerald-700 font-extrabold" style="display:none;">
                                        <span id="rubricScoreNum" class="text-base">85</span>
                                        <span class="text-[9px] uppercase tracking-wider text-emerald-600">/ 100</span>
                                    </div>
                                </div>
                                <div id="examinerOutputReport" class="text-xs text-slate-600 leading-relaxed max-h-[380px] overflow-y-auto pr-1">
                                    Paste your essay draft and marking scheme on the left to receive an immediate predictive score and constructive line-by-line mark maximizer.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================
                     PILLAR 4: METHOD STUDIO (MORPHING STUDY ENGINE)
                     ========================================================= -->
                <section id="view-methods" class="view-pane max-w-5xl mx-auto space-y-6">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-amber-50 text-amber-600">
                                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Method Studio</h1>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">The adaptive study engine. Pick a proven cognitive method below or follow the AI recommendation—the interface morphs to support your chosen rhythm.</p>
                    </div>

                    <!-- AI Coach Recommendation Banner -->
                    <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-pink-50 border border-amber-200/80 rounded-3xl p-4 flex items-center gap-3.5 shadow-sm">
                        <div class="w-9 h-9 rounded-2xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-amber-950">AI Study Recommendation</h4>
                            <p class="text-[11px] text-amber-900/80">For formula derivations and abstract definitions, the <b>Feynman Teach-Back</b> and <b>Blurting Audit</b> yield 85% higher retention.</p>
                        </div>
                    </div>

                    <!-- Method Switcher Pills -->
                    <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-3">
                        <button class="method-pill-btn active flex items-center gap-2 bg-amber-500 text-white font-bold px-4 py-2 rounded-2xl text-xs shadow-md shadow-amber-200 transition-all hover:scale-105" data-method="method-pomodoro">
                            <i data-lucide="timer" class="w-3.5 h-3.5"></i>
                            <span>Pomodoro Flow</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-feynman">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            <span>Feynman Teach-Back</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-blurting">
                            <i data-lucide="brain" class="w-3.5 h-3.5"></i>
                            <span>Blurting Audit</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-irac">
                            <i data-lucide="scale" class="w-3.5 h-3.5"></i>
                            <span>IRAC Legal Studio</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-decoder">
                            <i data-lucide="target" class="w-3.5 h-3.5"></i>
                            <span>Lecturer Decoder</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-socratic">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                            <span>Socratic Sparring</span>
                        </button>
                        <button class="method-pill-btn flex items-center gap-2 bg-white text-slate-700 font-semibold border border-slate-200/80 px-4 py-2 rounded-2xl text-xs hover:bg-slate-50 transition-all" data-method="method-sq3r">
                            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                            <span>SQ3R Deep Reader</span>
                        </button>
                    </div>

                    <!-- Method Layout 1: Pomodoro Flow -->
                    <div id="method-pomodoro" class="method-layout-pane active-method">
                        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl shadow-indigo-100/30 text-center max-w-md mx-auto space-y-4">
                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-amber-600 bg-amber-50 px-3 py-1 rounded-full">25-Minute Focus Sprint</span>
                            <div id="pomodoroTimerDisplay" class="text-5xl font-black text-slate-900 tracking-tight my-4">25:00</div>
                            <div class="flex justify-center gap-3">
                                <button id="startPomodoroBtn" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs px-6 py-2.5 rounded-2xl shadow-md shadow-amber-200 transition-all hover:scale-105">Start Sprint</button>
                                <button id="resetPomodoroBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-5 py-2.5 rounded-2xl transition-all">Reset</button>
                            </div>
                        </div>
                    </div>

                    <!-- Method Layout 2: Feynman Teach-Back -->
                    <div id="method-feynman" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-2xl mx-auto">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Topic to Explain:</label>
                                <input type="text" id="feynmanTopicInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="e.g. Newton's 3rd Law, Photosynthesis, or Contract Offer & Acceptance">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Your Simple Explanation (Teach it to a 10-year-old):</label>
                                <textarea id="feynmanExplanationInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 min-h-[120px] resize-y" placeholder="Explain the concept simply without using technical jargon..."></textarea>
                            </div>
                            <button id="runFeynmanBtn" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-xs py-3 rounded-2xl shadow-md shadow-amber-200 transition-all">Evaluate Concept Mastery</button>
                            <div id="feynmanFeedbackOutput" class="pt-2"></div>
                        </div>
                    </div>

                    <!-- Method Layout 3: Blurting Audit -->
                    <div id="method-blurting" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-2xl mx-auto">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">5-Minute Memory Dump</h4>
                                    <p class="text-[11px] text-slate-400">Type all formulas, definitions, and terms you recall from memory.</p>
                                </div>
                                <span id="blurtingTimerText" class="font-mono text-lg font-bold text-amber-600">05:00</span>
                            </div>
                            <textarea id="blurtingCanvas" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 min-h-[160px] resize-y" placeholder="Start typing everything you remember..."></textarea>
                            <div class="flex gap-3">
                                <button id="startBlurtingBtn" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-2xl transition-all">Start 5-Min Timer</button>
                                <button id="submitBlurtingAuditBtn" class="flex-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 text-white font-bold text-xs py-2.5 rounded-2xl shadow-md shadow-amber-200 transition-all">Audit Against Vault Notes</button>
                            </div>
                            <div id="blurtingAuditOutput" class="pt-2"></div>
                        </div>
                    </div>

                    <!-- Method Layout 4: IRAC Legal & Problem Studio -->
                    <div id="method-irac" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-3xl mx-auto">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 rounded-xl bg-amber-50 text-amber-600">
                                        <i data-lucide="scale" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">IRAC Analytical Framework</h4>
                                        <p class="text-[11px] text-slate-400">Issue, Rule, Application, and Conclusion for legal, management, and ethical problem questions.</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Scenario / Case Facts:</label>
                                <textarea id="iracFactsInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 min-h-[120px] resize-y" placeholder="Describe the factual problem scenario (e.g., A seller delivers defective components to a manufacturer...)"></textarea>
                            </div>
                            <button id="runIRACBtn" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 text-white font-bold text-xs py-3 rounded-2xl shadow-md shadow-amber-200 transition-all flex items-center justify-center gap-2">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                                <span>Generate IRAC Legal Breakdown</span>
                            </button>
                            <div id="iracOutputContainer" class="pt-2 space-y-3"></div>
                        </div>
                    </div>

                    <!-- Method Layout 5: Lecturer Decoder & Yield Analyzer -->
                    <div id="method-decoder" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-3xl mx-auto">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 rounded-xl bg-purple-50 text-purple-600">
                                        <i data-lucide="target" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Lecturer Decoder & Exam Yield Analyzer</h4>
                                        <p class="text-[11px] text-slate-400">Reverse-engineers course notes to identify high-yield exam archetypes, priority weighting, and trap questions.</p>
                                    </div>
                                </div>
                                <button id="runDecoderBtn" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 text-white font-bold text-xs px-4 py-2 rounded-2xl shadow-md shadow-purple-200 transition-all flex items-center gap-1.5">
                                    <i data-lucide="scan-line" class="w-3.5 h-3.5"></i>
                                    <span>Decode Vault Notes</span>
                                </button>
                            </div>
                            <div id="decoderOutputContainer" class="space-y-3">
                                <div class="text-center py-8 text-xs text-slate-400 bg-slate-50 rounded-2xl border border-slate-100">
                                    Click "Decode Vault Notes" above to extract high-yield exam topics from your active syllabus.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Method Layout 6: Socratic Sparring -->
                    <div id="method-socratic" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-2xl mx-auto">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Academic Concept / Assertion:</label>
                                <input type="text" id="socraticTopicInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="e.g. Total energy is conserved in isolated systems">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Your Academic Defense / Claim:</label>
                                <textarea id="socraticArgumentInput" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 min-h-[120px] resize-y" placeholder="State your defense. The AI professor will challenge your premises..."></textarea>
                            </div>
                            <button id="runSocraticBtn" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs py-3 rounded-2xl shadow-md shadow-amber-200 transition-all">Challenge Professor</button>
                            <div id="socraticOutput" class="pt-2"></div>
                        </div>
                    </div>

                    <!-- Method Layout 7: SQ3R Deep Reader -->
                    <div id="method-sq3r" class="method-layout-pane" style="display:none;">
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xl shadow-indigo-100/30 space-y-4 max-w-2xl mx-auto">
                            <h4 class="text-xs font-bold text-slate-900">SQ3R Active Reading Framework</h4>
                            <div class="flex flex-wrap gap-2">
                                <button class="sq3r-step-btn active bg-amber-500 text-white font-bold px-3 py-1.5 rounded-xl text-xs" data-step="Survey">1. Survey</button>
                                <button class="sq3r-step-btn bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs hover:bg-slate-200" data-step="Question">2. Question</button>
                                <button class="sq3r-step-btn bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs hover:bg-slate-200" data-step="Read">3. Read</button>
                                <button class="sq3r-step-btn bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs hover:bg-slate-200" data-step="Recite">4. Recite</button>
                                <button class="sq3r-step-btn bg-slate-100 text-slate-700 font-semibold px-3 py-1.5 rounded-xl text-xs hover:bg-slate-200" data-step="Review">5. Review</button>
                            </div>
                            <button id="runSQ3RGuideBtn" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs py-3 rounded-2xl shadow-md shadow-amber-200 transition-all">Generate Step Guidance</button>
                            <div id="sq3rOutput" class="pt-2"></div>
                        </div>
                    </div>
                </section>

                <!-- =========================================================
                     PILLAR 5: EXAM ARENA (TIMED MOCK CHAMBER)
                     ========================================================= -->
                <section id="view-arena" class="view-pane max-w-4xl mx-auto space-y-6">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                                <i data-lucide="trophy" class="w-5 h-5"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Exam Arena</h1>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Simulate real university exam conditions with timed mock papers, question navigators, and instant marking schemes.</p>
                    </div>

                    <!-- Exam Arena Status Bar -->
                    <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-xl shadow-indigo-100/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600">Active Simulation</span>
                            <h3 id="arenaExamTitle" class="text-sm font-bold text-slate-900">ZIMSEC A-Level / University Mock</h3>
                        </div>
                        <div class="flex items-center gap-2 font-mono text-base font-bold text-rose-600 bg-rose-50 px-4 py-1.5 rounded-2xl border border-rose-100">
                            <i data-lucide="alarm-clock" class="w-4 h-4"></i>
                            <span id="arenaClockDisplay">15:00</span>
                        </div>
                        <button id="generateArenaExamBtn" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-2xl shadow-md shadow-emerald-200 transition-all hover:scale-105">Generate New Mock</button>
                    </div>

                    <!-- Questions Container -->
                    <div id="arenaQuestionsContainer" class="space-y-4">
                        <div class="bg-white rounded-3xl border border-slate-100 p-10 shadow-xl shadow-indigo-100/30 text-center text-xs text-slate-400">
                            Click "Generate New Mock" above to start a timed exam simulation from your Vault notes.
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- EcoCash / Support Modal -->
    <div id="supportModal" class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-3xl border border-slate-100 max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-xl bg-amber-50 text-amber-600">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Support StudyBee Zimbabwe</h3>
                </div>
                <button id="closeSupportModalBtn" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                StudyBee is handcrafted for Zimbabwean and African university students. Your pass includes 100% full access to Groq Llama 3.3 70B, DeepSolve, The Examiner, and all study rituals.
            </p>
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Select Supporter Pass:</label>
                    <select id="supporterOptionSelect" class="w-full bg-white border border-slate-200 text-xs font-semibold px-3.5 py-2.5 rounded-2xl">
                        <option value="term_pass">Exam Term Pass - $0.50 EcoCash</option>
                        <option value="year_pass">Full Academic Year Pass - $3.00 EcoCash</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">EcoCash Phone Number:</label>
                    <input type="text" id="ecocashPhoneNumber" class="w-full bg-white border border-slate-200 text-xs font-medium px-3.5 py-2.5 rounded-2xl" placeholder="077XXXXXXX">
                </div>
            </div>
            <button id="submitEcocashPayBtn" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 text-white font-bold text-xs py-3 rounded-2xl shadow-lg shadow-amber-200 transition-all">Pay via EcoCash</button>
        </div>
    </div>

    <!-- Create New Workspace / Subject Modal -->
    <div id="newWorkspaceModal" class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-3xl border border-slate-100 max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="folder-plus" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Create New Study Vault</h3>
                </div>
                <button id="closeNewWorkspaceModalBtn" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Course / Subject Name:</label>
                    <input type="text" id="newWorkspaceNameInput" class="w-full bg-slate-50 border border-slate-200 text-xs font-medium px-3.5 py-2.5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="e.g. Computer Science & Data Structures">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Academic Category:</label>
                    <select id="newWorkspaceCategorySelect" class="w-full bg-slate-50 border border-slate-200 text-xs font-semibold px-3.5 py-2.5 rounded-2xl">
                        <option value="ZIMSEC A-Level">ZIMSEC A-Level</option>
                        <option value="University Engineering">University Engineering</option>
                        <option value="Law & Humanities">Law & Humanities</option>
                        <option value="Computer Science & IT">Computer Science & IT</option>
                        <option value="Medicine & Health Sciences">Medicine & Health Sciences</option>
                        <option value="Commerce & Economics">Commerce & Economics</option>
                        <option value="General Studies">General Studies</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Description (Optional):</label>
                    <textarea id="newWorkspaceDescInput" class="w-full bg-slate-50 border border-slate-200 text-xs font-medium px-3.5 py-2.5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-none h-16" placeholder="Module topics, lecturer focus, or past paper notes..."></textarea>
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button id="cancelNewWorkspaceBtn" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2.5 rounded-2xl transition-all">Cancel</button>
                <button id="submitNewWorkspaceBtn" class="flex-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 text-white font-bold text-xs py-2.5 rounded-2xl shadow-md shadow-indigo-200 transition-all">Create Vault</button>
            </div>
        </div>
    </div>

    <!-- Exam Arena Results Modal -->
    <div id="arenaResultsModal" class="modal-overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-3xl border border-slate-100 max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="award" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Exam Arena Grading Report</h3>
                </div>
                <button id="closeArenaResultsModalBtn" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Score Banner -->
            <div class="bg-gradient-to-tr from-emerald-50 to-teal-50 border border-emerald-200/80 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-700">Official Simulation Grade</span>
                    <h4 id="arenaFinalGradeText" class="text-xl font-black text-emerald-950">Grade A (88%)</h4>
                    <p id="arenaTimeSpentText" class="text-[11px] text-emerald-800/80 mt-0.5">Completed in 12 mins 40 secs</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-white border border-emerald-200 shadow-sm flex flex-col items-center justify-center text-emerald-700">
                    <span id="arenaEarnedMarks" class="text-lg font-black">15</span>
                    <span id="arenaTotalMarks" class="text-[9px] font-bold text-emerald-600 uppercase">/ 17 Marks</span>
                </div>
            </div>

            <div id="arenaDetailedBreakdown" class="space-y-3 text-xs text-slate-700 leading-relaxed">
                <!-- Populated dynamically via JS -->
            </div>

            <button id="finishArenaReviewBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-2xl shadow-lg shadow-emerald-200 transition-all">Done & Return to Vault</button>
        </div>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
