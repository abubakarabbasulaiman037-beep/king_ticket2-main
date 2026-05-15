<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>King Ticket - Premium Events</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #000000;
            -webkit-font-smoothing: antialiased;
        }
        .text-gold { color: #D4AF37; }
        .bg-gold { background-color: #D4AF37; }
        .border-gold { border-color: #D4AF37; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        /* Universal reset for strictly Black and White UI */
        .bg-black { background-color: #000000 !important; color: #FFFFFF !important; }
        .text-white { color: #FFFFFF !important; }
        
        .btn-dark { background-color: #000000 !important; color: #FFFFFF !important; border: 1px solid #000000 !important; }
        .btn-dark:hover { opacity: 0.9 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
        
        .btn-light { background-color: #FFFFFF !important; color: #000000 !important; border: 1px solid #000000 !important; }
        .btn-light:hover { opacity: 0.8 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important; }
    </style>
</head>
<body class="min-h-screen relative flex flex-col pt-24 bg-white">

    <!-- Global Floating Navbar (Minimal Editorial) -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#EEEEEE]">
        <div class="max-w-[1280px] mx-auto px-6 h-[88px] flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <svg class="h-6 w-6 text-[#000000]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                </svg>
                <div class="flex flex-col">
                    <span class="font-bold text-[16px] leading-tight tracking-widest text-[#000000] uppercase font-serif">King Ticket</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-10">
                <a href="{{ url('/') }}" class="text-[12px] uppercase tracking-widest font-medium {{ request()->is('/') ? 'text-[#000000]' : 'text-[#888888] hover:text-[#000000]' }} transition-colors relative group">
                    Home
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 h-px bg-[#D4AF37] transition-all duration-300 {{ request()->is('/') ? 'w-full' : 'w-0 group-hover:w-full' }}"></div>
                </a>
                <a href="{{ route('events.public') }}" class="text-[12px] uppercase tracking-widest font-medium {{ request()->routeIs('events.*') ? 'text-[#000000]' : 'text-[#888888] hover:text-[#000000]' }} transition-colors relative group">
                    Events
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 h-px bg-[#D4AF37] transition-all duration-300 {{ request()->routeIs('events.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></div>
                </a>
                <a href="{{ route('venues.index') }}" class="text-[12px] uppercase tracking-widest font-medium {{ request()->routeIs('venues.*') ? 'text-[#000000]' : 'text-[#888888] hover:text-[#000000]' }} transition-colors relative group">
                    Venues
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 h-px bg-[#D4AF37] transition-all duration-300 {{ request()->routeIs('venues.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></div>
                </a>
                <a href="{{ route('scanner.public.entry') }}" class="text-[12px] uppercase tracking-widest font-medium {{ request()->routeIs('scanner.*') ? 'text-[#000000]' : 'text-[#888888] hover:text-[#000000]' }} transition-colors relative group">
                    Scanner
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 h-px bg-[#D4AF37] transition-all duration-300 {{ request()->routeIs('scanner.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></div>
                </a>
            </nav>

            <!-- Actions -->
            <div class="hidden md:flex items-center gap-6">
                @auth
                    <!-- Avatar Dropdown (Simulated hover menu) -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none">
                            <span class="text-[12px] uppercase tracking-widest font-bold text-[#000000] hidden lg:block">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            <div class="w-8 h-8 rounded-full border border-[#EEEEEE] flex items-center justify-center text-[#000000] font-bold overflow-hidden shadow-sm bg-[#FFFFFF]">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" class="w-full h-full object-cover grayscale">
                                @else
                                    <span class="text-[11px] font-serif">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </button>
                        
                        <div x-show="open" x-transition class="absolute right-0 mt-4 w-56 bg-[#FFFFFF] border border-[#EEEEEE] rounded-none shadow-[0_15px_40px_rgba(0,0,0,0.08)] py-4 z-50">
                            <div class="px-6 pb-4 border-b border-[#EEEEEE] mb-2">
                                <p class="text-[13px] font-bold text-[#000000] uppercase tracking-widest truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-[#888888] truncate mt-1">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard.index') }}" class="block px-6 py-2.5 text-[12px] uppercase tracking-widest font-medium text-[#000000] hover:bg-[#FFFFFF] transition-colors">Dashboard</a>
                            <a href="{{ route('tickets.mine') }}" class="block px-6 py-2.5 text-[12px] uppercase tracking-widest font-medium text-[#000000] hover:bg-[#FFFFFF] transition-colors">My Tickets</a>
                            <a href="#" class="block px-6 py-2.5 text-[12px] uppercase tracking-widest font-medium text-[#000000] hover:bg-[#FFFFFF] transition-colors">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-[#EEEEEE] pt-2">
                                @csrf
                                <button type="submit" class="block w-full text-left px-6 py-2 text-[12px] uppercase tracking-widest font-bold text-[#000000] hover:bg-[#FFFFFF] transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-[12px] uppercase tracking-widest font-medium text-[#000000] border-b border-transparent hover:border-[#000000] transition-colors pb-0.5">Log In</a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-[#000000] text-[#FFFFFF] text-[11px] font-bold uppercase tracking-widest rounded-none hover:opacity-80 transition-opacity">Sign Up</a>
                @endauth
            </div>
            
            <!-- Mobile Menu Btn -->
            <button class="md:hidden text-[#000000]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full flex flex-col bg-[#FFFFFF]">
        <!-- Global Notifications Space (Monochrome minimal) -->
        @if(session('success') || session('error'))
            <div class="w-full max-w-[1280px] mx-auto px-6 mt-6 z-40 relative animate-fade-in-up">
                @if(session('success'))
                    <div class="bg-[#FFFFFF] border border-[#000000] p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-[#000000] rounded-full"></span>
                            <p class="text-[12px] font-bold uppercase tracking-widest text-[#000000]">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-[#000000] p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-[#FFFFFF] rounded-full"></span>
                            <p class="text-[12px] font-bold uppercase tracking-widest text-[#FFFFFF]">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Global Footer (Minimal Editorial) -->
    <footer class="bg-[#FFFFFF] border-t border-[#000000] pt-24 pb-12 mt-auto">
        <div class="max-w-[1280px] mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2 flex flex-col items-start">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6 border-b border-[#000000] pb-2">
                        <span class="font-bold text-[24px] leading-tight tracking-widest text-[#000000] uppercase font-serif">King Ticket</span>
                    </a>
                    <p class="text-[#000000] text-[13px] max-w-sm mb-8 leading-relaxed font-light">
                        The definitive editorial destination for extraordinary experiences. High-end ticketing and verified venue curation.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-[11px] text-[#000000] mb-6 uppercase tracking-widest">Navigation</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('events.public') }}" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Events</a></li>
                        <li><a href="{{ route('venues.index') }}" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Venues</a></li>
                        <li><a href="{{ route('scanner.public.entry') }}" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Scanner</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="font-bold text-[11px] text-[#000000] mb-6 uppercase tracking-widest">Legal</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Privacy Policy</a></li>
                        <li><a href="#" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Terms of Service</a></li>
                        <li><a href="#" class="text-[13px] font-light text-[#000000] hover:text-[#D4AF37] transition-colors relative group"><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-px bg-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity"></span> Contact Studio</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-[#EEEEEE] pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[11px] text-[#000000] font-bold uppercase tracking-widest">© {{ date('Y') }} King Ticket Editions</p>
                <div class="flex items-center gap-3 text-[11px] text-[#000000] font-bold uppercase tracking-widest">
                    <span>Designed by Brynovate</span>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(5px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
    </style>
</body>
</html>
