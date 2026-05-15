@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#FFFFFF] font-sans pb-32">
    
    <!-- 1. CINEMATIC WELCOME BANNER (EDITORIAL B&W) -->
    <section class="max-w-[1280px] mx-auto px-6 pt-12 pb-20">
        <div class="relative w-full h-[500px] md:h-[600px] overflow-hidden group">
            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1456104443903-88229b0151f1?q=80&w=1600&auto=format&fit=crop&monochrome=true" class="absolute inset-0 w-full h-full object-cover grayscale opacity-90 group-hover:scale-[1.02] transition-transform duration-[2000ms]" alt="Cinematic Studio Background">
            
            <!-- Soft Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#FFFFFF] via-[#FFFFFF]/80 to-transparent"></div>
            
            <!-- Content -->
            <div class="relative z-10 h-full flex flex-col justify-center px-10 md:px-20 max-w-3xl">
                <div class="inline-flex items-center gap-3 mb-8 self-start">
                    <span class="w-8 h-px bg-[#000000]"></span>
                    <span class="text-[10px] font-bold text-[#000000] tracking-[0.2em] uppercase">Studio Dashboard</span>
                </div>
                
                <h1 class="text-5xl md:text-[72px] leading-[1.05] font-serif text-[#000000] mb-6">
                    Welcome,<br> <span class="italic font-light">{{ explode(' ', Auth::user()->name)[0] }}.</span>
                </h1>
                
                <p class="text-[14px] text-[#000000] mb-12 max-w-md leading-relaxed font-light tracking-wide">
                    Create and manage unforgettable premium experiences from your centralized creative workspace.
                </p>
                
                <div class="flex items-center gap-6">
                    <a href="{{ route('events.create') }}" class="px-10 py-4 bg-[#000000] text-[#FFFFFF] text-[11px] uppercase tracking-widest font-bold hover:opacity-80 transition-opacity flex items-center gap-3 group/btn">
                        <span>Create Experience</span>
                        <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            
            <!-- Minimal Grid Lines Overlay -->
            <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-[#000000]/20 to-transparent"></div>
            <div class="absolute inset-y-0 left-0 w-px bg-gradient-to-b from-[#000000]/20 to-transparent"></div>
        </div>
    </section>

    <!-- 2. BEAUTIFUL QUICK ACTION CARDS (MINIMAL) -->
    <section class="max-w-[1280px] mx-auto px-6 mb-32">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-0 border-y border-l border-[#EEEEEE]">
            <a href="{{ route('events.create') }}" class="group bg-[#FFFFFF] p-8 border-r border-[#EEEEEE] hover:bg-[#FFFFFF] transition-colors flex flex-col items-center justify-center text-center gap-6 h-[200px]">
                <div class="text-[#000000] group-hover:-translate-y-1 transition-transform">
                    <svg class="w-8 h-8 font-light" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#000000]">Create Event</span>
            </a>
            <a href="{{ route('events.index') }}" class="group bg-[#FFFFFF] p-8 border-r border-[#EEEEEE] hover:bg-[#FFFFFF] transition-colors flex flex-col items-center justify-center text-center gap-6 h-[200px]">
                <div class="text-[#000000] group-hover:-translate-y-1 transition-transform">
                    <svg class="w-8 h-8 font-light" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#000000]">My Events</span>
            </a>
            <a href="#" class="group bg-[#FFFFFF] p-8 border-r border-[#EEEEEE] hover:bg-[#FFFFFF] transition-colors flex flex-col items-center justify-center text-center gap-6 h-[200px]">
                <div class="text-[#000000] group-hover:text-[#D4AF37] group-hover:-translate-y-1 transition-all">
                    <svg class="w-8 h-8 font-light" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#000000]">Earnings</span>
            </a>
            <a href="{{ route('scanner.public.entry') }}" class="group bg-[#FFFFFF] p-8 border-r border-[#EEEEEE] hover:bg-[#FFFFFF] transition-colors flex flex-col items-center justify-center text-center gap-6 h-[200px]">
                <div class="text-[#000000] group-hover:-translate-y-1 transition-transform">
                    <svg class="w-8 h-8 font-light" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#000000]">Scanner</span>
            </a>
            <a href="{{ route('venues.index') }}" class="group bg-[#FFFFFF] p-8 border-r border-[#EEEEEE] hover:bg-[#FFFFFF] transition-colors flex flex-col items-center justify-center text-center gap-6 h-[200px] col-span-2 md:col-span-1 border-b md:border-b-0">
                <div class="text-[#000000] group-hover:-translate-y-1 transition-transform">
                    <svg class="w-8 h-8 font-light" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#000000]">Venues</span>
            </a>
        </div>
    </section>

    <!-- 3. FEATURED EVENT PREVIEW SECTION -->
    <section class="max-w-[1280px] mx-auto px-6 mb-32">
        <div class="flex items-end justify-between mb-16 pb-4 border-b border-[#000000]">
            <h2 class="text-2xl font-serif font-bold text-[#000000] uppercase tracking-widest">Selected Works</h2>
            <a href="{{ route('events.index') }}" class="hidden md:inline-flex items-center gap-2 text-[11px] font-bold text-[#000000] hover:text-[#D4AF37] uppercase tracking-widest transition-colors">
                View Archive
            </a>
        </div>

        @if(isset($organizedEvents) && $organizedEvents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($organizedEvents->take(3) as $event)
                    <div class="group flex flex-col">
                        <!-- Image Wrap -->
                        <div class="relative h-[400px] overflow-hidden mb-6 bg-[#FFFFFF]">
                            <img src="{{ $event->banner ?? 'https://source.unsplash.com/random/800x1200?editorial' }}" alt="{{ $event->title }}" class="w-full h-full object-cover grayscale group-hover:scale-105 transition-transform duration-1000">
                            
                            <!-- Status Label Minimal -->
                            <div class="absolute top-4 right-4">
                                @if(\Carbon\Carbon::parse($event->date)->isPast())
                                    <span class="bg-[#FFFFFF] text-[#000000] border border-[#000000] text-[9px] font-bold uppercase tracking-widest px-2 py-1">Ended</span>
                                @else
                                    <span class="bg-[#000000] text-[#FFFFFF] border border-[#000000] text-[9px] font-bold uppercase tracking-widest px-2 py-1">Live</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="flex-1 flex flex-col">
                            <span class="text-[10px] font-bold text-[#888888] uppercase tracking-widest mb-2">{{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}</span>
                            <h3 class="text-2xl font-serif font-bold text-[#000000] mb-4 truncate" title="{{ $event->title }}">{{ $event->title }}</h3>
                            
                            <!-- Analytics Simple Line -->
                            <div class="flex items-center gap-6 mb-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-[#888888] uppercase tracking-widest mb-1">Passes</span>
                                    <span class="text-[16px] font-light text-[#000000]">{{ rand(10, 150) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-[#888888] uppercase tracking-widest mb-1">Views</span>
                                    <span class="text-[16px] font-light text-[#000000]">{{ rand(300, 1500) }}</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-4 mt-auto pt-6 border-t border-[#EEEEEE]">
                                <a href="{{ route('events.edit', $event->id) }}" class="flex-1 py-3 bg-[#FFFFFF] border border-[#000000] text-[#000000] hover:bg-[#FFFFFF] text-center text-[10px] font-bold uppercase tracking-widest transition-colors">
                                    Manage
                                </a>
                                <a href="{{ route('events.show', $event->id) }}" class="w-12 h-12 border border-[#000000] flex items-center justify-center text-[#000000] hover:bg-[#000000] hover:text-[#FFFFFF] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- BEAUTIFUL EMPTY STATE -->
            <div class="w-full h-[600px] border border-[#EEEEEE] overflow-hidden flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 p-12 lg:p-24 flex flex-col justify-center border-b md:border-b-0 md:border-r border-[#EEEEEE] h-full">
                    <span class="text-[10px] font-bold text-[#888888] tracking-[0.2em] uppercase mb-8">Initiation</span>
                    <h3 class="text-4xl lg:text-5xl font-serif font-bold text-[#000000] mb-6 leading-tight">Your Canvas is Blank</h3>
                    <p class="text-[14px] font-light text-[#000000] mb-12 max-w-sm leading-relaxed">
                        Step entirely into the spotlight. Curate and design your inaugural premier event.
                    </p>
                    <a href="{{ route('events.create') }}" class="self-start px-10 py-4 bg-[#000000] text-[#FFFFFF] text-[11px] font-bold uppercase tracking-widest hover:opacity-80 transition-opacity">Launch Event</a>
                </div>
                <div class="w-full md:w-1/2 h-full bg-[#FFFFFF]">
                    <img src="https://images.unsplash.com/photo-1507676184212-d0c30a634bda?q=80&w=1200&auto=format&fit=crop&monochrome=true" class="w-full h-full object-cover grayscale" alt="Empty State Graphic">
                </div>
            </div>
        @endif
    </section>

    <!-- 4. MODERN EARNINGS AND BANKING -->
    <section class="max-w-[1280px] mx-auto px-6 mb-24">
        <div class="flex flex-col lg:flex-row gap-0 border border-[#EEEEEE]">
            
            <!-- Minimal Earnings Preview -->
            <div class="w-full lg:w-1/2 p-12 lg:p-20 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-[#EEEEEE] relative bg-[#FFFFFF]">
                <h2 class="text-[11px] font-bold text-[#000000] uppercase tracking-widest mb-10 pb-4 border-b border-[#000000]">Financial Overview</h2>
                
                <h3 class="text-[72px] lg:text-[84px] font-serif font-bold text-[#000000] leading-none mb-6 -ml-2 tracking-tighter">
                    <span class="text-[#888888] text-[48px] font-sans font-light pr-2">₦</span>{{ number_format($totalRevenue ?? 0) }}
                </h3>
                
                <p class="text-[13px] font-light text-[#000000] mb-16 max-w-sm leading-relaxed">Aggregated ticket revenue. Final payouts are processed automatically following event closure.</p>
                
                <div class="h-[120px] border-b border-[#000000] relative flex items-end justify-between px-2 pt-8">
                    <!-- Ultra Minimal Black/White Chart Line Indicator -->
                    <div class="w-full absolute bottom-0 left-0 h-px bg-[#000000]/20"></div>
                </div>
                <div class="flex justify-between text-[9px] font-bold text-[#888888] uppercase tracking-widest mt-4">
                    <span>Performance Trace (MTD)</span>
                    <span class="text-[#D4AF37]">Active</span>
                </div>
            </div>

            <!-- Minimal Banking Form Layout -->
            <div class="w-full lg:w-1/2 p-12 lg:p-20 flex flex-col justify-center bg-[#FFFFFF]">
                <h2 class="text-[11px] font-bold text-[#000000] uppercase tracking-widest mb-10 pb-4 border-b border-[#000000]">Routing Details</h2>
                
                <form class="space-y-10">
                    <div class="relative">
                        <label class="block text-[10px] font-bold text-[#888888] uppercase tracking-widest mb-4">Institution Name</label>
                        <select class="w-full bg-transparent border-0 border-b border-[#EEEEEE] text-[#000000] text-[16px] font-serif focus:ring-0 focus:border-[#000000] px-0 py-2 rounded-none appearance-none cursor-pointer">
                            <option>Guaranty Trust Bank</option>
                            <option>Zenith Bank</option>
                            <option>Access Bank</option>
                        </select>
                        <div class="absolute right-0 top-10 pointer-events-none">
                            <svg class="w-4 h-4 text-[#000000]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-[#888888] uppercase tracking-widest mb-4">Account Number</label>
                        <input type="text" placeholder="0000000000" class="w-full bg-transparent border-0 border-b border-[#EEEEEE] text-[#000000] text-[20px] font-serif focus:ring-0 focus:border-[#000000] px-0 py-2 rounded-none placeholder-[#EEEEEE]">
                    </div>
                    
                    <button type="button" class="w-full py-5 bg-[#FFFFFF] border border-[#000000] text-[#000000] hover:bg-[#000000] hover:text-[#FFFFFF] text-[11px] font-bold uppercase tracking-widest transition-colors mt-8">
                        Update Routing
                    </button>
                </form>
            </div>
        </div>
    </section>

</div>
@endsection
