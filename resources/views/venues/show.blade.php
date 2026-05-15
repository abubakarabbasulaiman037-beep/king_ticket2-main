@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-24 text-gray-200">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-xs font-semibold text-gray-500 uppercase tracking-widest">
            <a href="{{ route('venues.index') }}" class="hover:text-white transition-colors">Venues</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $venue->name }}</span>
        </nav>

        <!-- Main Banner -->
        <div class="relative w-full h-[400px] md:h-[500px] rounded-3xl overflow-hidden mb-8 border border-edge-strong shadow-2xl">
            <img src="{{ $venue->images_gallery ? $venue->images_gallery[0] : 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=1200&auto=format&fit=crop' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-12">
                <div class="flex gap-2 mb-4">
                    <span class="px-3 py-1 bg-white/10 backdrop-blur border border-white/20 rounded-full text-[10px] text-white uppercase tracking-widest font-bold">{{ $venue->state->name }}</span>
                    <span class="px-3 py-1 bg-black/50 backdrop-blur border border-edge-subtle rounded-full text-[10px] text-gray-300 uppercase tracking-widest font-bold">{{ $venue->hall_type }}</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4">{{ $venue->name }}</h1>
                <p class="text-gray-300 text-sm md:text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    {{ $venue->full_address }}, {{ $venue->lga->name }}, {{ $venue->state->name }}
                </p>
            </div>
            
            <div class="absolute top-6 right-6">
                <span class="bg-white text-black font-black text-sm px-4 py-2 rounded-xl shadow-lg border border-edge-strong flex items-center gap-2 uppercase tracking-wide">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    {{ $venue->availability_status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-surface-primary border border-edge-strong rounded-3xl p-8 shadow-premium">
                    <h3 class="text-xl font-bold text-white mb-6">About this Venue</h3>
                    <p class="text-gray-400 leading-relaxed font-medium">
                        Welcome to {{ $venue->name }}, one of the finest event centers in {{ $venue->city_town }}. Ideal for weddings, corporate events, birthdays, and luxury gatherings. Features state-of-the-art facilities and guaranteed power supply.
                    </p>
                </div>
                
                <div class="bg-surface-primary border border-edge-strong rounded-3xl p-8 shadow-premium">
                    <h3 class="text-xl font-bold text-white mb-6">Amenities & Features</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full {{ $venue->has_parking ? 'bg-green-500' : 'bg-red-500' }}"></span> Parking Space
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full {{ $venue->has_generator ? 'bg-green-500' : 'bg-red-500' }}"></span> Backup Generator
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full {{ $venue->has_decoration ? 'bg-green-500' : 'bg-red-500' }}"></span> Decoration
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full {{ $venue->has_catering ? 'bg-green-500' : 'bg-red-500' }}"></span> Catering
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Air Conditioning
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Restrooms
                        </div>
                    </div>
                </div>

                <!-- Google Map Placeholder -->
                <div class="h-64 rounded-3xl border border-edge-strong overflow-hidden bg-surface-secondary relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-map-pattern opacity-20"></div>
                    <div class="z-10 text-center">
                        <svg class="w-12 h-12 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <p class="text-sm font-bold text-gray-400 tracking-widest uppercase">Live Maps Integrated</p>
                    </div>
                </div>
            </div>

            <!-- Right Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-white text-black border border-gray-200 rounded-3xl p-8 sticky top-24 shadow-2xl">
                    <div class="mb-6 flex justify-between items-end">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Starting Price</p>
                            <h2 class="text-3xl font-black">₦{{ number_format($venue->starting_price, 0) }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Capacity</p>
                            <h2 class="text-xl font-black">{{ $venue->capacity }} pax</h2>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">📞</div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone</p>
                                <p class="font-bold text-sm">{{ $venue->phone_number }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">✉️</div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</p>
                                <p class="font-bold text-sm">{{ mb_strtolower(str_replace(' ', '', $venue->name)) }}@example.com</p>
                            </div>
                        </div>
                    </div>

                    <button class="w-full py-4 bg-black text-white hover:bg-gray-800 transition-colors rounded-xl font-bold uppercase tracking-widest text-xs mb-3 shadow-lg">
                        Request Booking
                    </button>
                    <button class="w-full py-4 bg-green-500 text-white hover:bg-green-600 transition-colors rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg">
                        Chat on WhatsApp
                    </button>
                    
                    <p class="text-center text-[10px] text-gray-400 font-medium mt-4">
                        By requesting to book, you agree to King Ticket policies.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
