@extends('layouts.app')

@section('content')
<div class="px-4 py-12 lg:py-20 animate-fade-in">
    <div class="max-w-4xl mx-auto">
        <!-- Title -->
        <div class="mb-12">
            <h1 class="text-xs font-bold text-gold uppercase tracking-widest mb-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-gold rounded-full"></span>
                Event Configuration
            </h1>
            <div class="text-4xl md:text-5xl font-black text-white tracking-tight">Update Event</div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-surface-primary border border-edge-strong shadow-lux-lg p-8 md:p-12">
            <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-10">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-8">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm font-semibold text-red-300 mb-1 last:mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Title Designation -->
                    <div class="space-y-4 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-300">Event Title</label>
                        <input type="text" name="title" value="{{ $event->title }}" placeholder="Enter event title" class="input-cyber w-full" required>
                    </div>

                    <!-- Modern Date Sync -->
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-gray-300">Date & Time</label>
                        <div class="relative group">
                            <input type="datetime-local" name="date" value="{{ $event->date->format('Y-m-d\TH:i') }}" class="input-cyber w-full cursor-pointer" required>
                        </div>
                    </div>

                    <!-- Geospatial -->
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-gray-300">Location</label>
                        <input type="text" name="location" value="{{ $event->location }}" placeholder="Enter full address or venue" class="input-cyber w-full">
                    </div>

                    <!-- Valuation -->
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-gray-300">Ticket Price (₦)</label>
                        <input type="number" step="0.01" name="price" value="{{ $event->price }}" placeholder="0.00" class="input-cyber w-full" required>
                    </div>

                    <!-- Supply Protocol -->
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-gray-300">Available Tickets</label>
                        <input type="number" name="available_tickets" value="{{ $event->available_tickets }}" placeholder="Total number of tickets" class="input-cyber w-full" required>
                    </div>

                    <!-- Sector Category -->
                    <div class="space-y-4">
                        <label class="text-sm font-semibold text-gray-300">Category</label>
                        <select name="category" class="input-cyber w-full cursor-pointer">
                            <option value="music" {{ $event->category == 'music' ? 'selected' : '' }}>Music</option>
                            <option value="tech" {{ $event->category == 'tech' ? 'selected' : '' }}>Technology</option>
                            <option value="sports" {{ $event->category == 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="business" {{ $event->category == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="social" {{ $event->category == 'social' ? 'selected' : '' }}>Social/Lifestyle</option>
                        </select>
                    </div>

                    <!-- Direct Payout Info -->
                    <div class="space-y-4 md:col-span-2 p-6 bg-surface-secondary border border-edge-subtle rounded-2xl">
                        <h3 class="text-white font-bold mb-2 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Direct Payout Settings
                        </h3>
                        <p class="text-xs text-gray-400 mb-4">Provide your bank details to receive ticket sales directly into your account (Split Payments).</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-300 uppercase tracking-widest">Bank</label>
                                <select name="bank_code" class="input-cyber w-full cursor-pointer text-sm">
                                    <option value="">Select Bank</option>
                                    <option value="999992" {{ $event->bank_code == '999992' ? 'selected' : '' }}>OPay Digital Services (Paycom)</option>
                                    <option value="090405" {{ $event->bank_code == '090405' ? 'selected' : '' }}>Moniepoint MFB</option>
                                    <option value="090281" {{ $event->bank_code == '090281' ? 'selected' : '' }}>PalmPay</option>
                                    <option value="044" {{ $event->bank_code == '044' ? 'selected' : '' }}>Access Bank</option>
                                    <option value="011" {{ $event->bank_code == '011' ? 'selected' : '' }}>First Bank of Nigeria</option>
                                    <option value="058" {{ $event->bank_code == '058' ? 'selected' : '' }}>Guaranty Trust Bank (GTB)</option>
                                    <option value="033" {{ $event->bank_code == '033' ? 'selected' : '' }}>United Bank for Africa (UBA)</option>
                                    <option value="032" {{ $event->bank_code == '032' ? 'selected' : '' }}>Union Bank of Nigeria</option>
                                    <option value="057" {{ $event->bank_code == '057' ? 'selected' : '' }}>Zenith Bank</option>
                                    <option value="214" {{ $event->bank_code == '214' ? 'selected' : '' }}>First City Monument Bank (FCMB)</option>
                                    <option value="050" {{ $event->bank_code == '050' ? 'selected' : '' }}>Ecobank Nigeria</option>
                                    <option value="221" {{ $event->bank_code == '221' ? 'selected' : '' }}>Stanbic IBTC Bank</option>
                                    <option value="035" {{ $event->bank_code == '035' ? 'selected' : '' }}>Wema Bank</option>
                                    <option value="076" {{ $event->bank_code == '076' ? 'selected' : '' }}>Polaris Bank</option>
                                    <option value="082" {{ $event->bank_code == '082' ? 'selected' : '' }}>Keystone Bank</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-300 uppercase tracking-widest">Account Number</label>
                                <input type="text" name="account_number" value="{{ $event->account_number }}" placeholder="10-digit account number" class="input-cyber w-full text-sm" pattern="\d{10}">
                            </div>
                        </div>
                    </div>

                    <!-- Brief -->
                    <div class="space-y-4 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-300">Description</label>
                        <textarea name="description" rows="4" placeholder="Tell attendees what to expect..." class="input-cyber w-full resize-none">{{ $event->description }}</textarea>
                    </div>

                    <!-- Identity Visual -->
                    <div class="space-y-4 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-300">Banner Image</label>
                        <div class="group relative">
                            @if($event->banner)
                                <div class="mb-6 relative aspect-video overflow-hidden rounded-xl border border-edge-subtle group-hover:border-gold/30 transition-all">
                                    <img src="{{ $event->bannerUrl() }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="relative group">
                                <input type="file" name="banner" class="w-full bg-surface-elevated border border-edge-subtle rounded-xl p-8 text-gray-400 font-semibold text-sm file:hidden cursor-pointer hover:border-gold/30 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none border-2 border-dashed border-edge-strong rounded-xl group-hover:border-gold/50 transition-all bg-surface-elevated/50 group-hover:bg-gold/5">
                                    <span class="text-sm font-semibold text-gray-400 flex flex-col items-center gap-2 group-hover:text-gold transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Replace Banner Image (1200x600 recommended)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Cluster -->
                <div class="pt-8 border-t border-edge-subtle flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-xs text-gray-500 font-semibold block">
                        Changes will be applied immediately across the platform.
                    </p>
                    <button type="submit" class="btn-accent px-8 py-3.5 w-full md:w-auto mt-0">
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling the datetime-local picker for high-end look */
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
        opacity: 0.1;
    }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
        opacity: 0.5;
    }
</style>
@endsection
