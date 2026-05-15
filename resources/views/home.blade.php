@extends('layouts.public')

@section('content')

<!-- HERO SECTION -->
<section class="relative pt-20 pb-20 lg:pt-32 lg:pb-32 overflow-hidden max-w-[1280px] mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="z-10 flex flex-col items-start px-2">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 mb-8">
                <svg class="h-4 w-4 text-[#CBA469]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                </svg>
                <span class="text-[11px] font-bold text-gray-800 tracking-wider">YOUR EXPERIENCE, OUR PRIORITY</span>
            </div>

            <!-- Headline -->
            <h1 class="text-6xl md:text-[84px] leading-[1.05] font-serif text-black mb-6">
                Discover Events.<br>
                Book <span class="text-[#CBA469] italic">Moments.</span>
            </h1>

            <!-- Subhead -->
            <p class="text-[16px] text-gray-500 mb-10 max-w-md leading-relaxed">
                Find the best concerts, festivals, conferences and experiences in your city and beyond.
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('events.public') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-[#111] text-white rounded-xl font-medium text-[15px] shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-transform">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Explore Events
                </a>
                <a href="#" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-black rounded-xl font-medium text-[15px] border border-gray-100 shadow-xl shadow-gray-100 hover:-translate-y-0.5 transition-transform">
                    <div class="w-6 h-6 rounded-full border border-gray-200 flex items-center justify-center">
                        <svg class="w-3 h-3 ml-0.5 text-gray-800" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    How It Works
                </a>
            </div>
        </div>
        
        <!-- Hero Image -->
        <div class="relative z-0">
            <!-- Decorative light beam -->
            <div class="absolute -top-10 -right-20 w-[600px] h-[600px] bg-gradient-to-tr from-transparent to-[#CBA469]/20 blur-[100px] rounded-full"></div>
            <img src="{{ asset('images/hero_crown.png') }}" alt="Premium Crown" class="w-full h-auto max-w-[600px] mx-auto mix-blend-multiply drop-shadow-2xl object-contain animate-fade-in-up" />
        </div>
    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
    <div class="mb-12">
        <h2 class="text-[32px] font-bold text-black mb-2">Browse by Category</h2>
        <p class="text-[15px] text-gray-500">Find events that match your interests</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('events.public') }}?category={{ $category->id }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#CBA469]/20 to-[#CBA469]/5 border border-[#CBA469]/20 p-6 hover:border-[#CBA469]/50 transition-all duration-300 hover:shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-t from-black/0 to-transparent opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center justify-center text-center h-full">
                    <div class="w-12 h-12 rounded-full bg-[#CBA469]/20 flex items-center justify-center mb-3 group-hover:bg-[#CBA469]/30 transition-colors">
                        <svg class="w-6 h-6 text-[#CBA469]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h18M3 16h18"></path></svg>
                    </div>
                    <h3 class="font-bold text-[15px] text-black group-hover:text-[#CBA469] transition-colors">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="text-[12px] text-gray-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-2 sm:col-span-3 lg:col-span-5 text-center py-12">
                <p class="text-gray-500">No categories available</p>
            </div>
        @endforelse
    </div>
</section>

<!-- POPULAR EVENTS SECTION -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-[32px] font-bold text-black mb-2">Popular Events</h2>
            <p class="text-[15px] text-gray-500">Trending events people love</p>
        </div>
        <a href="{{ route('events.public') }}" class="text-[14px] font-medium text-gray-800 hover:text-[#CBA469] flex items-center gap-1 transition-colors">
            View All Events
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    @php
        // Fetch real events if possible, or fallback to UI mockup
        $events = \App\Models\Event::where('date', '>', now())->limit(4)->get();
        
        // Mock data to match the UI if not enough real events
        $mockEvents = [
            ['title' => 'Sound Ignite Live', 'category' => 'CONCERT', 'date' => '24', 'month' => 'MAY', 'location' => 'Lagos, Nigeria', 'price' => '8,000'],
            ['title' => 'Afrobeats Night', 'category' => 'NIGHTLIFE', 'date' => '31', 'month' => 'MAY', 'location' => 'Abuja, Nigeria', 'price' => '8,000'],
            ['title' => 'Business Summit 2026', 'category' => 'CONFERENCE', 'date' => '12', 'month' => 'JUN', 'location' => 'Lagos, Nigeria', 'price' => '15,000'],
            ['title' => 'Sunset Music Festival', 'category' => 'FESTIVAL', 'date' => '20', 'month' => 'JUN', 'location' => 'Port Harcourt, Nigeria', 'price' => '18,000'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($mockEvents as $index => $mock)
            @php 
                $event = $events[$index] ?? null; 
                // Default placeholder logic if needed
                $imageUrl = $event ? $event->getCinematicImageUrl() : 'https://picsum.photos/seed/event'.$index.'/600/600';
            @endphp
            
            <a href="{{ $event ? route('events.show.public', [$event->id, \Illuminate\Support\Str::slug($event->title)]) : '#' }}" class="group block bg-white rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-50 transition-all duration-300 hover:-translate-y-1">
                <div class="relative h-60 w-full overflow-hidden">
                    <img src="{{ $imageUrl }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Event Cover">
                    <div class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                </div>
                
                <div class="relative p-6 pt-8">
                    <!-- Overlapping Date Badge -->
                    <div class="absolute -top-10 left-6 bg-white rounded-xl shadow-lg w-14 h-16 flex flex-col items-center justify-center border border-gray-100">
                        <span class="text-xl font-bold leading-none text-black">{{ $event ? \Carbon\Carbon::parse($event->date)->format('d') : $mock['date'] }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase mt-1">{{ $event ? \Carbon\Carbon::parse($event->date)->format('M') : $mock['month'] }}</span>
                    </div>

                    <p class="text-[11px] font-semibold text-gray-400 tracking-wider mb-2 uppercase">{{ $event ? $event->category->name ?? 'EVENT' : $mock['category'] }}</p>
                    <h3 class="text-lg font-bold text-black mb-1 line-clamp-1 group-hover:text-[#CBA469] transition-colors">{{ $event ? $event->title : $mock['title'] }}</h3>
                    <p class="text-[13px] text-gray-500 mb-5">{{ $event->location ?? $mock['location'] }}</p>
                    
                    <div class="flex flex-col">
                        <span class="text-[11px] text-gray-400">From</span>
                        <span class="font-bold text-black">₦{{ $event ? number_format($event->price, 0) : $mock['price'] }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination Dots -->
    <div class="flex items-center justify-center gap-2 mt-10">
        <div class="w-8 h-1.5 rounded-full bg-black"></div>
        <div class="w-2 h-1.5 rounded-full bg-gray-200"></div>
        <div class="w-2 h-1.5 rounded-full bg-gray-200"></div>
        <div class="w-2 h-1.5 rounded-full bg-gray-200"></div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="max-w-[1280px] mx-auto px-6 py-20 border-t border-b border-gray-100 my-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 flex items-center justify-center text-[#CBA469] mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <h3 class="font-bold text-[16px] text-black mb-2">Find Events</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed">Discover amazing events happening near you.</p>
        </div>
        
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 flex items-center justify-center text-[#CBA469] mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 12l2 2 4-4"></path></svg>
            </div>
            <h3 class="font-bold text-[16px] text-black mb-2">Book Tickets</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed">Choose your ticket and book in just a few clicks.</p>
        </div>
        
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 flex items-center justify-center text-[#CBA469] mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h3 class="font-bold text-[16px] text-black mb-2">Secure Payment</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed">Your payments are safe and protected.</p>
        </div>
        
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 flex items-center justify-center text-[#CBA469] mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4L9 9H4l4 3-1 4 4-2 4 2-1-4 4-3h-5l-2-5z"></path></svg>
            </div>
            <h3 class="font-bold text-[16px] text-black mb-2">Enjoy Moments</h3>
            <p class="text-[13px] text-gray-500 leading-relaxed">Experience unforgettable moments with us.</p>
        </div>
    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="max-w-[1280px] mx-auto px-6 py-20 pb-32">
    <div class="flex justify-between items-end mb-12">
        <h2 class="text-[28px] font-bold text-black">Browse By Category</h2>
        <a href="#" class="text-[14px] font-medium text-gray-800 hover:text-[#CBA469] flex items-center gap-1 transition-colors">
            View All Categories
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    @php
        $cats = [
            ['name' => 'Concerts', 'icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'],
            ['name' => 'Nightlife', 'icon' => 'M21.17 3.25L12 14.5l-9.17-11.25zM12 14.5v6.5m-3 0h6'],
            ['name' => 'Conferences', 'icon' => 'M17 20h5V4H2v16h5m10 0v-5a3 3 0 00-3-3h-4a3 3 0 00-3 3v5m10 0H7m5-10a3 3 0 100-6 3 3 0 000 6z'],
            ['name' => 'Festivals', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
            ['name' => 'Sports', 'icon' => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
            ['name' => 'More', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($cats as $i => $cat)
            <a href="#" class="group relative h-40 rounded-2xl overflow-hidden bg-black flex flex-col items-center justify-center">
                <img src="https://picsum.photos/seed/cat{{ $i }}/300/300" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-60 transition-opacity duration-300 group-hover:scale-110" alt="{{ $cat['name'] }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 mb-2 text-white/90 group-hover:text-white transition-colors">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $cat['icon'] }}"></path></svg>
                    </div>
                    <span class="text-white font-medium text-[14px]">{{ $cat['name'] }}</span>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- MEMBERSHIP SECTION -->
<section class="max-w-[1280px] mx-auto px-6 mb-24">
    <div class="rounded-3xl overflow-hidden bg-gradient-to-r from-[#FDFCFB] to-[#F3F2F0] shadow-sm border border-gray-100 flex flex-col md:flex-row items-center">
        <!-- Text side -->
        <div class="p-12 lg:p-20 flex-1 w-full order-2 md:order-1">
            <p class="text-[11px] font-bold text-[#CBA469] tracking-wider uppercase mb-3">EXCLUSIVE BENEFITS</p>
            <h2 class="text-4xl lg:text-[42px] font-serif text-black leading-tight mb-4">
                Join King Ticket Community
            </h2>
            <p class="text-[15px] text-gray-500 mb-8 max-w-sm">
                Get access to exclusive events, early bird tickets and special offers.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-black text-white rounded-xl font-medium text-[14px] shadow-lg shadow-black/10 hover:shadow-black/20 hover:-translate-y-0.5 transition-all">
                Join Now <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
        
        <!-- Image side -->
        <div class="flex-1 w-full order-1 md:order-2 p-10 relative flex justify-center lg:justify-end">
            <!-- decorative circles -->
            <div class="absolute right-10 top-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-[#CBA469]/5 rounded-full blur-3xl"></div>
            <img src="{{ asset('images/vip_cards.png') }}" class="relative z-10 w-full max-w-[500px] object-contain drop-shadow-2xl hover:-translate-y-2 transition-transform duration-700 mix-blend-multiply" alt="VIP Memberships">
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="max-w-[1280px] mx-auto px-6 pb-20 border-b border-gray-100 mb-0">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#CBA469]/10 flex items-center justify-center text-[#CBA469]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-black">10K+</h4>
                <p class="text-[12px] text-gray-500 uppercase tracking-widest font-medium">Events</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#CBA469]/10 flex items-center justify-center text-[#CBA469]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-black">500K+</h4>
                <p class="text-[12px] text-gray-500 uppercase tracking-widest font-medium">Happy Users</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#CBA469]/10 flex items-center justify-center text-[#CBA469]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-black">50+</h4>
                <p class="text-[12px] text-gray-500 uppercase tracking-widest font-medium">Cities</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#CBA469]/10 flex items-center justify-center text-[#CBA469]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-black">100%</h4>
                <p class="text-[12px] text-gray-500 uppercase tracking-widest font-medium">Secure</p>
            </div>
        </div>
    </div>
</section>

<!-- BANKS & PAYMENT METHODS SECTION -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
    <div class="mb-12">
        <h2 class="text-[32px] font-bold text-black mb-2">Secure Payment Methods</h2>
        <p class="text-[15px] text-gray-500">We accept payments from all major Nigerian banks and payment platforms</p>
    </div>

    @php
        $categoryLabels = [
            'commercial' => '🏢 Commercial Banks',
            'merchant' => '🏛️ Merchant Banks',
            'digital' => '💳 Digital Banks & Fintech',
            'psb' => '📱 Payment Service Banks',
            'microfinance' => '💰 Microfinance Banks',
            'telco' => '📞 Telco Wallets',
        ];
    @endphp

    <div class="space-y-8">
        @forelse($banks as $category => $bankList)
            @if(isset($categoryLabels[$category]))
                <div>
                    <h3 class="text-lg font-semibold text-black mb-4">{{ $categoryLabels[$category] }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($bankList as $bank)
                            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-[#CBA469] hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-full bg-[#CBA469]/10 flex items-center justify-center mx-auto mb-2 group-hover:bg-[#CBA469]/20 transition-colors">
                                    <svg class="w-6 h-6 text-[#CBA469]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m-7 4h4m-11-8h2a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v5a1 1 0 001 1zm0 0h14a1 1 0 001-1v-3a1 1 0 00-1-1h-2.5a1 1 0 00-1 1v2m-2 0V5a1 1 0 010-1h1a1 1 0 011 1v2"></path></svg>
                                </div>
                                <h4 class="font-semibold text-[13px] text-black group-hover:text-[#CBA469] transition-colors">{{ $bank->name }}</h4>
                                <p class="text-[11px] text-gray-400 mt-1">Code: {{ $bank->code }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No payment methods available</p>
            </div>
        @endforelse
    </div>

    <!-- Trust Badge -->
    <div class="mt-16 bg-gradient-to-r from-[#CBA469]/5 to-transparent border border-[#CBA469]/20 rounded-2xl p-8">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-[#CBA469]/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-[#CBA469]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-black mb-1">All Transactions Are Secure</h3>
                <p class="text-gray-600">Your payment information is encrypted and protected. We use industry-standard security protocols to keep your data safe.</p>
            </div>
        </div>
    </div>
</section>

@endsection
