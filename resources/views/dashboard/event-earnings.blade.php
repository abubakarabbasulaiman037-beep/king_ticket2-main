@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard.index') }}" class="text-yellow-400 hover:text-yellow-300 mb-4 inline-block">← Back to Dashboard</a>
            <h1 class="text-4xl font-bold text-white Playfair Display">📊 {{ $event->title }}</h1>
            <p class="text-gray-400 mt-2">Earnings breakdown for this event</p>
        </div>

        <!-- Event Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Total Revenue</p>
                <p class="text-3xl font-bold text-blue-400">₦{{ number_format($earnings['total_revenue'], 0) }}</p>
                <p class="text-gray-500 text-xs mt-2">From {{ $earnings['tickets_sold'] }} tickets</p>
            </div>

            <!-- Your Earnings (95%) -->
            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Your Earnings (95%)</p>
                <p class="text-3xl font-bold text-yellow-400">₦{{ number_format($earnings['organizer_earnings'], 0) }}</p>
                <p class="text-yellow-500 text-xs mt-2">✓ Instantly transferred</p>
            </div>

            <!-- Platform Fee (5%) -->
            <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Platform Fee (5%)</p>
                <p class="text-3xl font-bold text-red-400">₦{{ number_format($earnings['platform_fee'], 0) }}</p>
                <p class="text-red-500 text-xs mt-2">Service charge</p>
            </div>

            <!-- Already Paid Out -->
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Already Paid Out</p>
                <p class="text-3xl font-bold text-green-400">₦{{ number_format($earnings['already_paid'], 0) }}</p>
                <p class="text-green-500 text-xs mt-2">Transferred to bank</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Breakdown Card -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h2 class="text-2xl font-bold text-white mb-6">Earning Breakdown</h2>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-800/50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400">Per Ticket Revenue</span>
                                <span class="text-white font-bold">₦{{ number_format($event->price, 0) }}</span>
                            </div>
                            <p class="text-gray-500 text-sm">Buyers pay this amount</p>
                        </div>

                        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-yellow-400 font-semibold">Your Earnings (95%)</span>
                                <span class="text-yellow-400 font-bold">₦{{ number_format($event->price * 0.95, 0) }}</span>
                            </div>
                            <p class="text-yellow-600 text-sm">Instantly transferred to your bank</p>
                        </div>

                        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-red-400 font-semibold">Platform Fee (5%)</span>
                                <span class="text-red-400 font-bold">₦{{ number_format($event->price * 0.05, 0) }}</span>
                            </div>
                            <p class="text-red-600 text-sm">Kept by King Ticket for operations</p>
                        </div>
                    </div>
                </div>

                <!-- Sale Transactions -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h2 class="text-2xl font-bold text-white mb-6">Recent Sales</h2>
                    
                    @php
                        $payments = $event->payments()->where('status', 'success')->latest()->paginate(10);
                    @endphp

                    @if ($payments->isEmpty())
                        <p class="text-gray-400 text-center py-8">No sales yet</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($payments as $payment)
                        <div class="bg-gray-800/50 rounded-lg p-4 hover:border-yellow-500/30 border border-transparent transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-white font-semibold">{{ $payment->buyer->name ?? 'Unknown' }}</p>
                                            <p class="text-gray-400 text-sm">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-green-400 font-bold">+₦{{ number_format($payment->amount * 0.95, 0) }}</p>
                                            <p class="text-gray-400 text-xs">You earned</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="inline-block bg-green-500/20 text-green-400 px-2 py-1 rounded text-xs font-semibold">Paid</span>
                                        <span class="inline-block bg-blue-500/20 text-blue-400 px-2 py-1 rounded text-xs font-semibold">Ref: {{ substr($payment->reference, -8) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Event Info -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4">Event Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 text-sm">Date & Time</p>
                            <p class="text-white">{{ $event->date->format('M d, Y \a\t H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Location</p>
                            <p class="text-white">{{ $event->location }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Tickets Available</p>
                            <p class="text-white">{{ $event->available_tickets }} total</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Tickets Sold</p>
                            <p class="text-green-400 font-bold">{{ $earnings['tickets_sold'] }} sold</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="block text-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded font-semibold transition">
                            👁️ View Event
                        </a>
                        <a href="{{ route('dashboard.edit-event', $event->id) }}" class="block text-center bg-yellow-500 hover:bg-yellow-600 text-black py-2 px-4 rounded font-semibold transition">
                            ✏️ Edit Event
                        </a>
                        <a href="{{ route('dashboard.index') }}" class="block text-center bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded font-semibold transition">
                            ← Back
                        </a>
                    </div>
                </div>

                <!-- Payment Schedule -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4">💡 How It Works</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-yellow-500 text-black rounded-full flex items-center justify-center font-bold text-xs">1</span>
                            <p class="text-gray-400">Buyer purchases ticket</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-yellow-500 text-black rounded-full flex items-center justify-center font-bold text-xs">2</span>
                            <p class="text-gray-400">Payment processed & verified</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-yellow-500 text-black rounded-full flex items-center justify-center font-bold text-xs">3</span>
                            <p class="text-gray-400">95% instantly sent to your bank</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-yellow-500 text-black rounded-full flex items-center justify-center font-bold text-xs">4</span>
                            <p class="text-gray-400">5% retained as platform fee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
