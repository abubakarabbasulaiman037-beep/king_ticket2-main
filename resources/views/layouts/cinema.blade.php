<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>King Ticket | Premium Event Ticketing</title>
    <meta name="description" content="Experience premium event ticketing like never before.">

    <!-- Google Fonts: Poppins (more cinematic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        h1, h2, h3, h4, .heading-special {
            font-family: 'Playfair Display', serif;
        }
        
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(5, 5, 5, 0.8);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #f5a623, #e8931f);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #ffc145, #f5a623);
        }
    </style>
</head>
<body class="antialiased selection:bg-yellow-500/30 selection:text-white min-h-screen relative overflow-x-hidden bg-black">
    <!-- Premium Background -->
    <div class="fixed inset-0 z-0">
        <!-- Main Background -->
        <div class="absolute inset-0 bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(5, 5, 5, 0.92) 0%, rgba(20, 10, 0, 0.88) 50%, rgba(5, 5, 5, 0.92) 100%), url('https://picsum.photos/seed/cinema-theatre/1920/1080?blur=3'); background-size: cover;"></div>
        
        <!-- Cinematic Vignette -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/40"></div>
        
        <!-- Accent Lights -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-yellow-500/15 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-tl from-purple-600/10 to-transparent rounded-full blur-3xl"></div>
    </div>

    <div id="app" class="relative z-10 flex flex-col min-h-screen">
        <!-- Premium Cinema Header -->
        <header class="sticky top-0 z-50 backdrop-blur-xl bg-black/40 border-b border-yellow-500/20 shadow-2xl">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-center h-24 lg:h-20">
                    <!-- Logo Section -->
                    <a href="{{ url('/') }}" class="group flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative bg-black px-3 py-2 rounded-lg">
                                <span class="text-xl font-black bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">KT</span>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-bold text-yellow-400">KING TICKET</p>
                            <p class="text-xs text-gray-400">Premium Events</p>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center gap-8">
                        <a href="{{ url('/') }}" class="relative text-white font-semibold text-sm group">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('events.public') }}" class="relative text-white font-semibold text-sm group">
                            Events
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('scanner.public.entry') }}" class="relative text-white font-semibold text-sm group">
                            Scanner
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="relative text-white font-semibold text-sm group">
                                Dashboard
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 group-hover:w-full transition-all duration-300"></span>
                            </a>
                            <a href="{{ route('tickets.mine') }}" class="relative text-white font-semibold text-sm group">
                                My Tickets
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-yellow-500 to-orange-500 group-hover:w-full transition-all duration-300"></span>
                            </a>
                        @endauth
                    </nav>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button class="px-4 py-2 text-sm font-bold text-red-400 hover:text-red-300 transition-colors">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-white hover:text-yellow-400 transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg font-bold text-sm text-black hover:shadow-lg hover:shadow-yellow-500/50 transition-all duration-300">Sign Up</a>
                        @endauth
                        
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" class="lg:hidden text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden lg:hidden pb-4 border-t border-yellow-500/20 pt-4 space-y-2">
                    <a href="{{ url('/') }}" class="block px-4 py-3 text-white hover:bg-yellow-500/10 rounded-lg transition">Home</a>
                    <a href="{{ route('events.public') }}" class="block px-4 py-3 text-white hover:bg-yellow-500/10 rounded-lg transition">Events</a>
                    <a href="{{ route('scanner.public.entry') }}" class="block px-4 py-3 text-white hover:bg-yellow-500/10 rounded-lg transition">Scanner</a>
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-3 text-white hover:bg-yellow-500/10 rounded-lg transition">Dashboard</a>
                        <a href="{{ route('tickets.mine') }}" class="block px-4 py-3 text-white hover:bg-yellow-500/10 rounded-lg transition">My Tickets</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Alerts -->
        @if(session('success') || session('error'))
            <div class="max-w-7xl mx-auto px-6 lg:px-12 mt-6">
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500/50 rounded-lg p-4 flex items-start gap-4 animate-fade-in backdrop-blur">
                        <div class="w-2 h-2 bg-green-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-sm text-green-300">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 flex items-start gap-4 animate-fade-in backdrop-blur">
                        <div class="w-2 h-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-sm text-red-300">{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1 py-12 lg:py-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                @yield('content')
            </div>
        </main>

        <!-- Premium Footer -->
        <footer class="border-t border-yellow-500/20 bg-black/60 backdrop-blur-xl mt-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div>
                        <h3 class="text-lg font-bold bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent mb-4">KING TICKET</h3>
                        <p class="text-sm text-gray-400">Premium event ticketing for the world's best events.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white mb-4">Navigation</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="{{ url('/') }}" class="hover:text-yellow-400 transition">Home</a></li>
                            <li><a href="{{ route('events.public') }}" class="hover:text-yellow-400 transition">Events</a></li>
                            <li><a href="{{ route('scanner.public.entry') }}" class="hover:text-yellow-400 transition">Scanner</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white mb-4">Support</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="#" class="hover:text-yellow-400 transition">Help Center</a></li>
                            <li><a href="#" class="hover:text-yellow-400 transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-yellow-400 transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="#" class="hover:text-yellow-400 transition">Privacy</a></li>
                            <li><a href="#" class="hover:text-yellow-400 transition">Terms</a></li>
                            <li><a href="#" class="hover:text-yellow-400 transition">Cookies</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-yellow-500/20 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-xs text-gray-600">© 2024 King Ticket. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center text-yellow-400 hover:bg-yellow-500/20 transition text-xs">f</a>
                        <a href="#" class="w-8 h-8 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center text-yellow-400 hover:bg-yellow-500/20 transition text-xs">i</a>
                        <a href="#" class="w-8 h-8 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center text-yellow-400 hover:bg-yellow-500/20 transition text-xs">t</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
