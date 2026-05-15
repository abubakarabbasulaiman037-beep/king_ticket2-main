<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>King Ticket</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAFAFA;
            color: #111111;
        }
        .text-gold {
            color: #CBA469;
        }
        .bg-gold {
            background-color: #CBA469;
        }
        .border-gold {
            border-color: #CBA469;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="antialiased min-h-screen relative flex flex-col pt-24 font-sans text-gray-900 bg-[#FCFCFD]">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-[1280px] mx-auto px-6 h-[88px] flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <svg class="h-8 w-8 text-[#CBA469]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                </svg>
                <div class="flex flex-col">
                    <span class="font-bold text-[18px] leading-tight tracking-wide text-black uppercase">King</span>
                    <span class="font-medium text-[12px] leading-tight text-gray-500 uppercase tracking-widest">Ticket</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-[14px] font-medium text-gray-500 hover:text-black transition-colors relative group">
                    Home
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-[#CBA469] group-hover:w-full transition-all duration-300"></div>
                </a>
                <a href="{{ route('events.public') }}" class="text-[14px] font-medium text-black relative group">
                    Events
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-0.5 bg-[#CBA469]"></div>
                </a>
                <a href="{{ route('venues.index') }}" class="text-[14px] font-medium text-gray-500 hover:text-black transition-colors">Venues</a>
                <a href="{{ route('scanner.public.entry') }}" class="text-[14px] font-medium text-gray-500 hover:text-black transition-colors">Scanner</a>
            </nav>

            <!-- Actions -->
            <div class="hidden md:flex items-center gap-5">
                <div class="relative">
                    <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-100 rounded-full text-sm w-[180px] focus:outline-none focus:border-[#CBA469] bg-gray-50/50" />
                    <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                @auth
                    <!-- Avatar Dropdown (Simulated hover menu) -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-[14px] font-medium text-black hover:text-[#CBA469] transition-colors focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-[#CBA469]/20 border border-[#CBA469]/30 flex items-center justify-center text-[#CBA469] font-bold overflow-hidden shadow-sm">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-2">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                <p class="text-sm font-bold text-black truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-black">Dashboard</a>
                            <a href="{{ route('tickets.mine') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-black">My Tickets</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-gray-50 pt-1">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-[14px] font-medium text-black hover:text-[#CBA469] transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-black text-white text-[14px] font-medium rounded-full hover:bg-gray-800 transition-colors shadow-md shadow-black/10">Sign Up</a>
                @endauth
            </div>
            
            <!-- Mobile Menu Btn -->
            <button class="md:hidden text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full overflow-hidden">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-20 pb-10">
        <div class="max-w-[1280px] mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 mb-16">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                        <svg class="h-8 w-8 text-[#CBA469]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                        </svg>
                        <div class="flex flex-col">
                            <span class="font-bold text-[18px] leading-tight tracking-wide text-black uppercase">King</span>
                            <span class="font-medium text-[12px] leading-tight text-gray-500 uppercase tracking-widest">Ticket</span>
                        </div>
                    </a>
                    <p class="text-gray-500 text-[14px] max-w-xs mb-6">
                        Your ticket to unforgettable experiences.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-[#CBA469] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <!-- Add other social icons as needed -->
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-[16px] text-black mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">About Us</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Events</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Venues</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Contact Us</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">FAQs</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="font-bold text-[16px] text-black mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Help Center</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Terms & Conditions</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Privacy Policy</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Refund Policy</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Ticket Policy</a></li>
                    </ul>
                </div>

                <!-- Newsletter (simulated with For Organizers content) -->
                <div>
                    <h4 class="font-bold text-[16px] text-black mb-4">For Organizers</h4>
                    <ul class="space-y-3 mb-6">
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Create Event</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Organizer Guide</a></li>
                        <li><a href="#" class="text-[14px] text-gray-500 hover:text-[#CBA469]">Become a Partner</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[13px] text-gray-500">© {{ date('Y') }} King Ticket. All rights reserved.</p>
                <div class="flex items-center gap-3 text-[13px] text-gray-500 font-bold uppercase tracking-widest">
                    <span>Designed by Brynovate</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
