@extends('layouts.public')

@section('content')

<!-- EVENTS HERO SECTION -->
<section class="relative pt-32 pb-20 overflow-hidden max-w-[1280px] mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Left Side: Copy & Quick Search -->
        <div class="z-10 flex flex-col items-start px-2">
            <h1 class="text-5xl md:text-[68px] leading-[1.1] font-serif text-black mb-6">
                Discover <br> <span class="text-[#CBA469] italic">Extraordinary</span> Events.
            </h1>
            <p class="text-[16px] text-gray-500 mb-10 max-w-lg leading-relaxed">
                Book premium concerts, festivals, conferences, and unforgettable experiences curated just for you.
            </p>
            
            <div class="w-full flex items-center gap-3">
                <a href="#events-grid" class="inline-flex items-center gap-2 px-8 py-4 bg-[#111] text-white rounded-xl font-medium text-[15px] shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-transform">
                    Explore Now
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex items-center gap-8 mt-12 pt-8 border-t border-gray-100 w-full max-w-sm">
                <div>
                    <p class="text-2xl font-bold text-black">2K+</p>
                    <p class="text-[12px] text-gray-400 font-medium uppercase tracking-wider">Events Available</p>
                </div>
                <div class="w-px h-10 bg-gray-200"></div>
                <div>
                    <p class="text-2xl font-bold text-black">15+</p>
                    <p class="text-[12px] text-gray-400 font-medium uppercase tracking-wider">Cities</p>
                </div>
            </div>
        </div>
        
        <!-- Right Side: Cinematic Collage -->
        <div class="relative z-0 hidden lg:block">
            <!-- Decorative blur background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-gray-100 to-[#CBA469]/10 blur-[80px] rounded-full"></div>
            
            <div class="relative h-[600px] w-full flex items-center justify-center">
                <div class="w-[500px] h-[550px] bg-white rounded-3xl p-3 shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-gray-100 rotate-2 hover:rotate-0 transition-transform duration-700">
                    <img src="{{ asset('images/event_collage.png') }}" class="w-full h-full object-cover rounded-2xl" alt="Premium Event Collage">
                </div>
                
                <!-- Floating Element -->
                <div class="absolute -bottom-6 -left-12 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-white flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                    <div class="w-12 h-12 bg-[#CBA469]/20 rounded-full flex items-center justify-center text-[#CBA469]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Top Rated</p>
                        <p class="text-[14px] font-bold text-black">Premium Quality</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADVANCED SEARCH & FILTER AREA -->
<section class="max-w-[1280px] mx-auto px-6 -mt-8 relative z-20 mb-20">
    <div class="bg-white rounded-3xl shadow-[0_8px_40px_rgba(0,0,0,0.04)] border border-gray-100 p-6 lg:p-8">
        <form action="{{ route('events.public') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-4">
            
            <!-- Main Search -->
            <div class="flex-1 w-full relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="q" placeholder="Search by event name or artist..." value="{{ request('q') }}" class="w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-[15px] focus:outline-none focus:ring-2 focus:ring-[#CBA469]/20 focus:border-[#CBA469] transition-all">
            </div>

            <div class="w-full lg:w-px h-px lg:h-12 bg-gray-100 hidden lg:block"></div>

            <!-- Location -->
            <div class="w-full lg:w-48 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <input type="text" name="location" placeholder="City or location" value="{{ request('location') }}" class="w-full pl-12 pr-4 py-4 bg-transparent border-0 text-[15px] focus:outline-none placeholder-gray-500 font-medium">
            </div>
            
            <div class="w-full lg:w-px h-px lg:h-12 bg-gray-100 hidden lg:block"></div>

            <!-- Category Dropdown -->
            <div class="w-full lg:w-48 relative">
                <select name="category" class="w-full pl-4 pr-10 py-4 bg-transparent border-0 text-[15px] focus:outline-none appearance-none font-medium cursor-pointer text-gray-600">
                    <option value="">All Categories</option>
                    <option value="concert" @selected(request('category') == 'concert')>Concerts</option>
                    <option value="festival" @selected(request('category') == 'festival')>Festivals</option>
                    <option value="conference" @selected(request('category') == 'conference')>Conferences</option>
                </select>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full lg:w-auto px-8 py-4 bg-black text-white rounded-2xl font-bold text-[14px] shadow-lg shadow-black/10 hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                Search
            </button>
        </form>
    </div>
</section>

<!-- TRENDING EVENTS HORIZONTAL SCROLL -->
@php
    $trending = \App\Models\Event::where('date', '>', now())->inRandomOrder()->limit(5)->get();
@endphp
@if($trending->count() > 0)
<section class="max-w-[1280px] mx-auto px-6 mb-24 overflow-hidden">
    <h2 class="text-2xl font-bold text-black mb-8">Trending This Week</h2>
    
    <div class="flex gap-6 overflow-x-auto pb-8 snap-x scrollbar-hide" style="scrollbar-width: none;">
        @foreach($trending as $event)
            <a href="{{ route('events.show.public', [$event->id, \Illuminate\Support\Str::slug($event->title)]) }}" class="group relative min-w-[320px] md:min-w-[400px] h-[250px] rounded-3xl overflow-hidden shadow-sm flex-shrink-0 snap-start border border-gray-100 block">
                <img src="{{ $event->getCinematicImageUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $event->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <div class="absolute inset-0 p-6 flex flex-col justify-end">
                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold text-white tracking-widest uppercase mb-3 w-max">
                        {{ \Carbon\Carbon::parse($event->date)->format('M d') }}
                    </span>
                    <h3 class="text-xl font-bold text-white mb-1 group-hover:text-[#CBA469] transition-colors leading-tight">{{ $event->title }}</h3>
                    <p class="text-white/80 text-[13px]">{{ $event->location }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- EVENT CATEGORIES GRID -->
<section class="border-t border-gray-100 bg-[#FAFAFA] py-24 mb-24">
    <div class="max-w-[1280px] mx-auto px-6">
        <h2 class="text-2xl font-bold text-black mb-10 text-center">Explore By Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $cats = [
                    ['name' => 'Concerts', 'img' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Festivals', 'img' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Conferences', 'img' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Sports', 'img' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Nightlife', 'img' => 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?q=80&w=600&auto=format&fit=crop'],
                    ['name' => 'Weddings', 'img' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=600&auto=format&fit=crop'],
                ];
            @endphp
            @foreach($cats as $cat)
                <a href="{{ route('events.public', ['category' => strtolower($cat['name'])]) }}" class="group relative h-32 rounded-2xl overflow-hidden bg-black flex flex-col items-center justify-center">
                    <img src="{{ $cat['img'] }}" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity duration-500 group-hover:scale-110" alt="{{ $cat['name'] }}">
                    <div class="relative z-10 font-bold text-white text-[15px] tracking-wide">{{ $cat['name'] }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- MAIN EVENTS GRID (FEATURED EVENTS) -->
<section id="events-grid" class="max-w-[1280px] mx-auto px-6 mb-24">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-3xl font-bold text-black mb-2">Featured Experiences</h2>
            <p class="text-[15px] text-gray-500">Discover handpicked events for you</p>
        </div>
    </div>

    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-gray-100 transition-all duration-500 hover:-translate-y-1.5 flex flex-col">
                    <a href="{{ route('events.show.public', [$event->id, \Illuminate\Support\Str::slug($event->title)]) }}" class="relative h-64 w-full overflow-hidden block">
                        <img src="{{ $event->getCinematicImageUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $event->title }}">
                        
                        <!-- Top Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 bg-black/60 backdrop-blur border border-white/10 rounded-full text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $event->category->name ?? 'MOMENT' }}
                            </span>
                        </div>
                        
                        <!-- Floating Date -->
                        <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur rounded-xl shadow-lg w-14 h-14 flex flex-col items-center justify-center">
                            <span class="text-[18px] font-bold leading-none text-black">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                            <span class="text-[10px] font-bold text-[#CBA469] uppercase mt-0.5">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                        </div>
                    </a>
                    
                    <div class="p-6 pt-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-black mb-2 line-clamp-2 leading-tight">
                                <a href="{{ route('events.show.public', [$event->id, \Illuminate\Support\Str::slug($event->title)]) }}" class="hover:text-[#CBA469] transition-colors">
                                    {{ $event->title }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center gap-2 text-[13px] text-gray-500 mb-4">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-5 border-t border-gray-100 mt-2">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-gray-400 font-medium">Tickets from</span>
                                <span class="font-bold text-[16px] text-black">{{ $event->currency ?? '₦' }}{{ number_format($event->price) }}</span>
                            </div>
                            <a href="{{ route('events.show.public', [$event->id, \Illuminate\Support\Str::slug($event->title)]) }}" class="px-5 py-2.5 bg-gray-50 hover:bg-black hover:text-white text-black font-semibold text-[13px] rounded-xl transition-colors border border-gray-100 flex items-center gap-2">
                                Book
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $events->links('pagination::tailwind') }}
        </div>
    @else
        <!-- PREMIUM EMPTY STATE -->
        <div class="w-full bg-white border border-gray-100 rounded-3xl p-16 text-center flex flex-col items-center justify-center min-h-[400px] shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-black mb-3 font-serif">No events found right now</h3>
            <p class="text-[15px] text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
                Explore featured categories or check back soon for upcoming premium experiences tailored for you.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('events.public') }}" class="px-6 py-3 bg-black text-white text-[14px] font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-transform">
                    Reset Filters
                </a>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-white text-black border border-gray-200 text-[14px] font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    @endif
</section>

<!-- Hide scrollbar class for the trending section (requires tailwind plugin or custom css) -->
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
