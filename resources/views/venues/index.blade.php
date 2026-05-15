@extends('layouts.public')

@section('content')
<!-- Alpine.js is included via layout/app.js usually, or I can pull it from CDN for guaranteed working -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="venueDiscovery()" x-init="init()" class="min-h-screen pb-24 bg-[#FCFCFD]">
    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-20 overflow-hidden max-w-[1280px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left Side: Copy -->
            <div class="z-10 flex flex-col items-start px-2">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 mb-8">
                    <svg class="h-4 w-4 text-[#CBA469]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-gray-800 tracking-wider">LUXURY COLLECTION</span>
                </div>
                
                <h1 class="text-5xl md:text-[68px] leading-[1.1] font-serif text-black mb-6">
                    Discover Premium <br> <span class="text-[#CBA469] italic">Event Venues</span>
                </h1>
                <p class="text-[16px] text-gray-500 mb-10 max-w-lg leading-relaxed">
                    Find elegant spaces for weddings, concerts, conferences, nightlife, and unforgettable experiences.
                </p>
                
                <!-- Quick Search attached to Hero -->
                <div class="w-full flex items-center gap-3 max-w-md">
                    <div class="relative w-full shadow-sm rounded-xl">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" x-model="filters.query" @keyup.enter="fetchVenues()" placeholder="Search venue name, city..." class="w-full pl-12 pr-4 py-4 bg-white border border-gray-100 rounded-xl text-[15px] focus:outline-none focus:ring-2 focus:ring-[#CBA469]/20 focus:border-[#CBA469] transition-all shadow-sm">
                    </div>
                    <button @click="fetchVenues()" class="px-8 py-4 bg-[#111] text-white rounded-xl font-medium text-[15px] shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-transform flex-shrink-0">
                        Explore
                    </button>
                </div>
            </div>
            
            <!-- Right Side: Cinematic Collage -->
            <div class="relative z-0 hidden lg:block">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-[#FCFCFD] to-[#CBA469]/10 blur-[80px] rounded-full"></div>
                
                <div class="relative h-[600px] w-full flex items-center justify-center">
                    <div class="w-[500px] h-[550px] bg-white rounded-3xl p-4 shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-gray-100 -rotate-2 hover:rotate-0 transition-transform duration-700">
                        <img src="{{ asset('images/venue_collage.png') }}" class="w-full h-full object-cover rounded-2xl" alt="Premium Venue Collage">
                    </div>
                    
                    <div class="absolute top-10 -right-6 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-white animate-bounce" style="animation-duration: 4s;">
                        <p class="text-[14px] font-bold text-black flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Available Now
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT AREA -->
    <section class="max-w-[1280px] mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- SIDEBAR FILTERS (Floating Panel) -->
            <aside class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-3xl p-8 sticky top-32 shadow-[0_8px_40px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-[18px] font-bold text-black font-serif">Smart Filters</h3>
                        <button @click="resetFilters()" class="text-[12px] font-bold text-gray-400 hover:text-[#CBA469] transition-colors uppercase tracking-widest">Reset</button>
                    </div>

                    <!-- State Selector -->
                    <div class="mb-6">
                        <label class="block text-[12px] font-bold text-gray-800 mb-3 uppercase tracking-wider">Location: State</label>
                        <div class="relative">
                            <select x-model="filters.state_id" @change="loadLgas(); fetchVenues()" class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-[14px] font-medium rounded-xl focus:ring-[#CBA469]/20 focus:border-[#CBA469] p-3.5 appearance-none cursor-pointer transition-colors">
                                <option value="">Everywhere</option>
                                <template x-for="state in states" :key="state.id">
                                    <option :value="state.id" x-text="state.name"></option>
                                </template>
                            </select>
                            <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- LGA Selector -->
                    <div class="mb-8">
                        <label class="block text-[12px] font-bold text-gray-800 mb-3 uppercase tracking-wider">City / LGA</label>
                        <div class="relative">
                            <select x-model="filters.lga_id" @change="fetchVenues()" :disabled="!filters.state_id || lgas.length === 0" class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-[14px] font-medium rounded-xl focus:ring-[#CBA469]/20 focus:border-[#CBA469] p-3.5 appearance-none cursor-pointer transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">All Cities</option>
                                <template x-for="lga in lgas" :key="lga.id">
                                    <option :value="lga.id" x-text="lga.name"></option>
                                </template>
                            </select>
                            <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <hr class="border-gray-100 mb-8 border-dashed">

                    <!-- Venue Type Chips (Indoor/Outdoor/Mixed) -->
                    <div class="mb-8">
                        <label class="block text-[12px] font-bold text-gray-800 mb-4 uppercase tracking-wider">Venue Space</label>
                        <div class="flex flex-col gap-3">
                            <template x-for="type in ['indoor', 'outdoor', 'mixed']">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center justify-center w-5 h-5 border rounded-full transition-colors" 
                                         :class="filters.type === type ? 'border-[#CBA469] bg-[#CBA469]' : 'border-gray-300 bg-white group-hover:border-[#CBA469]'">
                                        <div x-show="filters.type === type" class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                    <span class="text-[14px] font-medium capitalize text-gray-600 group-hover:text-black transition-colors" x-text="type"></span>
                                    <input type="radio" :value="type" x-model="filters.type" @change="fetchVenues()" class="hidden">
                                </label>
                            </template>
                            <label class="flex items-center gap-3 cursor-pointer group mt-1">
                                <div class="relative flex items-center justify-center w-5 h-5 border rounded-full transition-colors" 
                                        :class="filters.type === '' ? 'border-[#CBA469] bg-[#CBA469]' : 'border-gray-300 bg-white group-hover:border-[#CBA469]'">
                                    <div x-show="filters.type === ''" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <span class="text-[14px] font-medium text-gray-600 group-hover:text-black transition-colors">Any</span>
                                <input type="radio" value="" x-model="filters.type" @change="fetchVenues()" class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- Minimum Capacity -->
                    <div class="mb-8 hidden lg:block">
                        <label class="block text-[12px] font-bold text-gray-800 mb-3 uppercase tracking-wider flex justify-between">
                            <span>Capacity</span>
                            <span class="text-[#CBA469]" x-text="filters.capacity_min ? filters.capacity_min + '+' : 'Any'"></span>
                        </label>
                        <input type="range" min="0" max="5000" step="100" x-model="filters.capacity_min" @change="fetchVenues()" class="w-full accent-[#CBA469] h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    </div>

                    <!-- Budget Max -->
                    <div class="mb-4">
                        <label class="block text-[12px] font-bold text-gray-800 mb-3 uppercase tracking-wider">Max Budget (₦)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₦</span>
                            <input type="number" x-model="filters.price_max" @change.debounce.500ms="fetchVenues()" placeholder="e.g. 500000" class="w-full pl-8 pr-4 py-3.5 bg-gray-50 border border-gray-100 text-gray-800 text-[14px] font-medium rounded-xl focus:ring-[#CBA469]/20 focus:border-[#CBA469] transition-colors placeholder-gray-400">
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN GRID -->
            <main class="lg:col-span-3">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-black font-serif">Featured Venues</h2>
                        <p class="text-[14px] text-gray-500 mt-1">Explore our exclusive selection of properties</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-full px-4 py-2 shadow-sm text-[13px] font-medium text-gray-600 flex items-center gap-2 max-w-max">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span x-text="totalVenues"></span> places matching
                    </div>
                </div>

                <!-- Loader / Skeleton -->
                <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <template x-for="i in 4">
                        <div class="bg-white rounded-3xl border border-gray-50 overflow-hidden shadow-sm animate-pulse">
                            <div class="h-64 bg-gray-100"></div>
                            <div class="p-6">
                                <div class="h-5 bg-gray-100 rounded w-1/3 mb-4"></div>
                                <div class="h-6 bg-gray-100 rounded w-3/4 mb-4"></div>
                                <div class="h-4 bg-gray-100 rounded w-1/2 mb-6"></div>
                                <div class="h-px w-full bg-gray-50 mb-4"></div>
                                <div class="flex justify-between">
                                    <div class="h-6 bg-gray-100 rounded w-1/4"></div>
                                    <div class="h-6 bg-gray-100 rounded w-1/4"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Venues Grid -->
                <!-- Apple / Airbnb Style Card Design -->
                <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 gap-8" style="display: none;">
                    <template x-for="venue in venues" :key="venue.id">
                        <a :href="`/venues/${venue.id}`" class="group block relative bg-white border border-transparent hover:border-gray-100 rounded-3xl overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] transition-all duration-500 hover:-translate-y-1">
                            
                            <!-- Image Wrap -->
                            <div class="relative h-64 overflow-hidden rounded-t-3xl md:rounded-3xl md:m-2">
                                <img :src="venue.images_gallery ? venue.images_gallery[0] : 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800&auto=format&fit=crop'" :alt="venue.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/95 backdrop-blur shadow-sm text-black text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full" x-text="venue.availability_status || 'AVAILABLE'"></span>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <button class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-white transition-all shadow-sm group/btn">
                                        <svg class="w-4 h-4 group-hover/btn:fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Content Wrap -->
                            <div class="p-6 md:px-4 md:py-5">
                                <div class="flex justify-between items-start mb-1 gap-2">
                                    <h3 class="text-lg font-bold text-black truncate leading-tight group-hover:text-[#CBA469] transition-colors" x-text="venue.name"></h3>
                                    <span class="flex items-center gap-1 text-[13px] font-bold text-black whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span x-text="venue.rating || '4.9'"></span>
                                    </span>
                                </div>
                                
                                <p class="text-[14px] text-gray-500 mb-4 truncate font-medium">
                                    <span x-text="`${venue.city_town}, ${venue.state?.name}`"></span>
                                    <span class="mx-1">•</span> 
                                    <span class="capitalize" x-text="venue.hall_type"></span>
                                </p>

                                <div class="flex items-center gap-3 mb-5">
                                    <span class="text-[12px] bg-gray-50 text-gray-600 px-2.5 py-1 rounded-md font-medium border border-gray-100 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-5a3 3 0 00-3-3h-4a3 3 0 00-3 3v5m10 0H7m5-10a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                                        <span x-text="`Up to ${venue.capacity}`"></span>
                                    </span>
                                    <span class="text-[12px] bg-gray-50 text-gray-600 px-2.5 py-1 rounded-md font-medium border border-gray-100 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Premium
                                    </span>
                                </div>

                                <div class="pt-4 border-t border-gray-100 flex justify-between items-center mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] text-gray-400 font-bold uppercase tracking-wide">Starting At</span>
                                        <p class="text-[15px] font-bold text-black" x-text="`₦${Number(venue.starting_price).toLocaleString()}`"></p>
                                    </div>
                                    <div class="flex -space-x-2">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white overflow-hidden"><img src="https://picsum.photos/seed/a/100/100" class="w-full h-full object-cover"></div>
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white overflow-hidden"><img src="https://picsum.photos/seed/b/100/100" class="w-full h-full object-cover"></div>
                                        <div class="w-7 h-7 rounded-full bg-[#111] border-2 border-white flex items-center justify-center text-[8px] font-bold text-white">+5</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && venues.length === 0" class="text-center py-20 bg-white rounded-3xl border border-gray-100 mt-6 shadow-sm flex flex-col items-center justify-center" style="display: none;">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-black mb-2 font-serif">No premium venues found</h3>
                    <p class="text-gray-500 text-[15px] mb-8 max-w-sm mx-auto">Try adjusting your filters, expanding your budget, or exploring different locations.</p>
                    <button @click="resetFilters()" class="px-8 py-3.5 bg-black text-white rounded-xl shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:-translate-y-0.5 transition-transform text-[14px] font-bold">Clear All Filters</button>
                </div>

                <!-- Pagination -->
                <div x-show="!loading && nextPageUrl" class="mt-16 text-center" style="display: none;">
                    <button @click="loadMore()" class="px-8 py-4 bg-white border border-gray-200 hover:border-black hover:bg-gray-50 transition-all rounded-xl text-black text-[14px] font-bold shadow-sm">
                        Load More Venues
                    </button>
                </div>

            </main>
        </div>
    </section>
</div>

<!-- API Integration Logic -->
<script>
    function venueDiscovery() {
        return {
            states: [],
            lgas: [],
            venues: [],
            totalVenues: 0,
            loading: true,
            nextPageUrl: null,
            filters: {
                query: '',
                state_id: '',
                lga_id: '',
                type: '',
                price_max: '',
                capacity_min: 0
            },
            
            async init() {
                await this.loadStates();
                this.fetchVenues();
            },
            
            async loadStates() {
                try {
                    const res = await fetch('/api/states');
                    this.states = await res.json();
                } catch (e) {
                    console.error('Failed to load states', e);
                }
            },
            
            async loadLgas() {
                this.filters.lga_id = '';
                if (!this.filters.state_id) {
                    this.lgas = [];
                    return;
                }
                
                try {
                    const res = await fetch(`/api/lgas/${this.filters.state_id}`);
                    this.lgas = await res.json();
                } catch (e) {
                    console.error('Failed to load LGAs', e);
                }
            },
            
            async fetchVenues() {
                this.loading = true;
                this.venues = [];
                
                try {
                    const params = new URLSearchParams();
                    for (const key in this.filters) {
                        if (this.filters[key]) {
                            params.append(key, this.filters[key]);
                        }
                    }
                    
                    const res = await fetch(`/api/venues?${params.toString()}`);
                    const data = await res.json();
                    
                    this.venues = data.data;
                    this.totalVenues = data.total;
                    this.nextPageUrl = data.next_page_url;
                } catch (e) {
                    console.error('Failed to fetch venues', e);
                } finally {
                    this.loading = false;
                }
            },
            
            async loadMore() {
                if (!this.nextPageUrl) return;
                
                this.loading = true;
                try {
                    const res = await fetch(this.nextPageUrl);
                    const data = await res.json();
                    
                    this.venues = [...this.venues, ...data.data];
                    this.nextPageUrl = data.next_page_url;
                } catch (e) {
                    console.error('Failed to load more', e);
                } finally {
                    this.loading = false;
                }
            },
            
            resetFilters() {
                this.filters = {
                    query: '', state_id: '', lga_id: '', type: '', price_max: '', capacity_min: 0
                };
                this.lgas = [];
                this.fetchVenues();
            }
        }
    }
</script>
@endsection
