@extends('layouts.app')

@section('content')
<!-- HERO BANNER -->
<section class="relative -mx-6 lg:-mx-12 mb-16 h-96 overflow-hidden rounded-b-3xl group">
    <!-- Background Image -->
    <img src="{{ $event->getCinematicImageUrl() }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
    
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
    
    <!-- Content -->
    <div class="relative h-full flex flex-col justify-end p-12">
        <div class="max-w-4xl">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full border border-yellow-500/50 bg-yellow-500/20 mb-6 backdrop-blur w-fit">
                <span class="text-xs font-bold text-yellow-300 uppercase tracking-wider">🎬 Premium Event</span>
            </div>

            <!-- Title -->
            <h1 class="text-6xl md:text-7xl font-black text-white mb-4 leading-tight">{{ $event->title }}</h1>
            
            <!-- Quick Info -->
            <div class="flex flex-wrap gap-8 text-yellow-300">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1 4.5 4.5 0 11-4.814 6.98z"></path></svg>
                    {{ $event->date->format('F d, Y • g:i A') }}
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                    {{ $event->location }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
    <!-- LEFT: Event Details -->
    <div class="lg:col-span-2 space-y-12">
        <!-- About Section -->
        <div>
            <h2 class="text-3xl font-black text-white mb-6">About This Event</h2>
            <p class="text-lg text-gray-300 leading-relaxed">{{ $event->description }}</p>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-8 bg-black/40 backdrop-blur border border-yellow-500/20 rounded-2xl p-8">
            <div>
                <p class="text-xs font-bold text-yellow-400 uppercase tracking-wider mb-2">Organizer</p>
                <p class="text-lg font-bold text-white">{{ $event->user->name ?? 'King Ticket' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-yellow-400 uppercase tracking-wider mb-2">Category</p>
                <p class="text-lg font-bold text-white">{{ $event->categories->first()?->name ?? 'Event' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-yellow-400 uppercase tracking-wider mb-2">Tickets Left</p>
                <p class="text-lg font-bold text-white">{{ $event->available_tickets }} Available</p>
            </div>
            <div>
                <p class="text-xs font-bold text-yellow-400 uppercase tracking-wider mb-2">Security</p>
                <p class="text-lg font-bold text-white">🔒 Verified</p>
            </div>
        </div>

        <!-- Features -->
        <div>
            <h3 class="text-2xl font-black text-white mb-6">Why Attend</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-6 bg-black/40 border border-yellow-500/20 rounded-xl hover:border-yellow-500/50 transition">
                    <p class="text-2xl mb-2">🎫</p>
                    <p class="font-bold text-white">Instant Delivery</p>
                    <p class="text-sm text-gray-400">Digital tickets sent immediately</p>
                </div>
                <div class="p-6 bg-black/40 border border-yellow-500/20 rounded-xl hover:border-yellow-500/50 transition">
                    <p class="text-2xl mb-2">🔐</p>
                    <p class="font-bold text-white">Secure QR</p>
                    <p class="text-sm text-gray-400">Unforgeable digital credentials</p>
                </div>
                <div class="p-6 bg-black/40 border border-yellow-500/20 rounded-xl hover:border-yellow-500/50 transition">
                    <p class="text-2xl mb-2">💬</p>
                    <p class="font-bold text-white">24/7 Support</p>
                    <p class="text-sm text-gray-400">Always here to help</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Booking Section -->
    <div class="lg:col-span-1">
        <div class="bg-black/60 backdrop-blur border border-yellow-500/30 rounded-2xl p-8 sticky top-32 space-y-6">
            <!-- Price -->
            <div>
                <p class="text-sm text-gray-400 mb-2">Ticket Price</p>
                <p class="text-4xl font-black text-yellow-400">
                    {{ $event->currency ?? '₦' }}{{ number_format($event->price) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">per ticket</p>
            </div>

            <!-- Quantity Selector -->
            <div>
                <p class="text-sm text-gray-400 mb-3">Number of Tickets</p>
                <div class="flex items-center gap-4">
                    <button onclick="decreaseQty()" class="w-10 h-10 rounded-lg bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500/30 transition font-bold">−</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $event->available_tickets }}" class="flex-1 text-center bg-black border border-yellow-500/30 rounded-lg py-2 text-white focus:outline-none focus:border-yellow-500">
                    <button onclick="increaseQty()" class="w-10 h-10 rounded-lg bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500/30 transition font-bold">+</button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Max: {{ $event->available_tickets }} tickets</p>
            </div>

            <!-- Pricing Summary -->
            <div class="space-y-2 border-t border-yellow-500/20 pt-6">
                <div class="flex justify-between text-gray-300">
                    <span>Subtotal</span>
                    <span id="subtotal">{{ $event->currency ?? '₦' }}{{ number_format($event->price) }}</span>
                </div>
                <div class="flex justify-between text-gray-300 text-sm">
                    <span>Fee</span>
                    <span id="fee">{{ $event->currency ?? '₦' }}0</span>
                </div>
                <div class="flex justify-between font-bold text-yellow-400 text-lg pt-4 border-t border-yellow-500/20">
                    <span>Total</span>
                    <span id="total">{{ $event->currency ?? '₦' }}{{ number_format($event->price) }}</span>
                </div>
            </div>

            <!-- CTA Button -->
            @auth
                <form action="{{ route('payment.initiate', $event->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full group relative block">
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg blur-lg opacity-75 group-hover:opacity-100 transition-all"></div>
                        <div class="relative bg-black px-6 py-4 rounded-lg border border-yellow-500/50 font-bold text-white text-center hover:text-yellow-100">
                            Book Now
                        </div>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full group relative block">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg blur-lg opacity-75 group-hover:opacity-100 transition-all"></div>
                    <div class="relative bg-black px-6 py-4 rounded-lg border border-yellow-500/50 font-bold text-white text-center hover:text-yellow-100">
                        Login to Book
                    </div>
                </a>
            @endauth

            <!-- Security Badge -->
            <div class="text-center pt-4 border-t border-yellow-500/20">
                <p class="text-xs text-gray-400">🔒 Secured by Paystack</p>
            </div>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    const price = {{ $event->price }};
    const subtotal = qty * price;
    const fee = Math.round(subtotal * 0.01); // 1% fee
    const total = subtotal + fee;
    
    document.getElementById('subtotal').textContent = '{{ $event->currency ?? "₦" }}' + subtotal.toLocaleString();
    document.getElementById('fee').textContent = '{{ $event->currency ?? "₦" }}' + fee.toLocaleString();
    document.getElementById('total').textContent = '{{ $event->currency ?? "₦" }}' + total.toLocaleString();
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    input.value = Math.max(1, parseInt(input.value) - 1);
    updateTotal();
}

function increaseQty() {
    const input = document.getElementById('quantity');
    input.value = Math.min({{ $event->available_tickets }}, parseInt(input.value) + 1);
    updateTotal();
}

document.getElementById('quantity').addEventListener('change', updateTotal);
</script>
@endsection
