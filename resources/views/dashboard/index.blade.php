@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white Playfair Display mb-2">📊 Organizer Dashboard</h1>
            <p class="text-gray-400">Manage your events, view earnings, and track payouts</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Earnings -->
            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-lg p-6 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Total Earnings (95%)</p>
                        <p class="text-3xl font-bold text-yellow-400">₦{{ number_format($earnings['total'], 0) }}</p>
                    </div>
                    <div class="text-4xl">💰</div>
                </div>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 rounded-lg p-6 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Tickets Sold</p>
                        <p class="text-3xl font-bold text-blue-400">{{ $earnings['tickets_sold'] }}</p>
                    </div>
                    <div class="text-4xl">🎫</div>
                </div>
            </div>

            <!-- Pending Payout -->
            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 rounded-lg p-6 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Pending Payout</p>
                        <p class="text-3xl font-bold text-purple-400">₦{{ number_format($earnings['pending'], 0) }}</p>
                    </div>
                    <div class="text-4xl">⏳</div>
                </div>
            </div>

            <!-- Platform Fee (5%) -->
            <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-lg p-6 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Platform Fee (5%)</p>
                        <p class="text-3xl font-bold text-red-400">₦{{ number_format($earnings['platform_fee'], 0) }}</p>
                    </div>
                    <div class="text-4xl">⚙️</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-yellow-500/20 rounded-lg p-6 backdrop-blur-xl">
                    <h2 class="text-xl font-bold text-white mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('dashboard.create-event') }}" class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-black font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                            + Create New Event
                        </a>
                        <a href="{{ route('dashboard.payout-history') }}" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                            View Payouts
                        </a>
                    </div>
                </div>

                <!-- Your Events -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-white">Your Events</h2>
                        <span class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 px-3 py-1 rounded-full text-sm font-semibold">{{ count($events) }}</span>
                    </div>

                    @if ($events->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-400 mb-4">You haven't created any events yet</p>
                            <a href="{{ route('dashboard.create-event') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-6 rounded-lg transition">
                                Create Your First Event
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($events as $event)
                                <div class="bg-gray-800/50 border border-gray-700/50 rounded-lg p-4 hover:border-yellow-500/30 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-white">{{ $event->title }}</h3>
                                            <p class="text-gray-400 text-sm">{{ $event->date->format('M d, Y') }} • {{ $event->location }}</p>
                                            <p class="text-yellow-400 text-sm mt-2">
                                                <strong>₦{{ number_format($event->price, 0) }}</strong> • 
                                                <strong>{{ $event->available_tickets }}</strong> tickets left
                                            </p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('dashboard.edit-event', $event->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-semibold transition">
                                                Edit
                                            </a>
                                            <a href="{{ route('dashboard.event-earnings', $event->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded font-semibold transition">
                                                Earnings
                                            </a>
                                            <form action="{{ route('dashboard.delete-event', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Bank Account Info -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4">🏦 Bank Account</h3>
                    
                    @if (Auth::user()->account_number)
                        <div class="space-y-2">
                            <p class="text-gray-400 text-sm">Account Holder</p>
                            <p class="text-white font-semibold mb-4">{{ Auth::user()->account_name }}</p>
                            
                            <p class="text-gray-400 text-sm">Account Number</p>
                            <p class="text-white font-mono mb-4">{{ Auth::user()->account_number }}</p>
                            
                            <p class="text-gray-400 text-sm">Bank</p>
                            <p class="text-white mb-4">{{ Auth::user()->bank_name }}</p>
                            
                            <a href="#update-bank" class="text-yellow-400 hover:text-yellow-300 text-sm font-semibold">Update Bank Details →</a>
                        </div>
                    @else
                        <div class="bg-red-500/20 border border-red-500/50 rounded p-4 mb-4">
                            <p class="text-red-300 text-sm font-semibold">⚠️ No bank account linked</p>
                            <p class="text-red-300 text-xs mt-2">Add your bank details to receive payouts</p>
                        </div>
                        <a href="#update-bank" class="block text-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded font-semibold transition">
                            Add Bank Account
                        </a>
                    @endif
                </div>

                <!-- Recent Payouts -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4">Recent Payouts</h3>
                    
                    @php
                        $recentPayouts = Auth::user()->payouts()->latest()->take(5)->get();
                    @endphp

                    @if ($recentPayouts->isEmpty())
                        <p class="text-gray-400 text-sm">No payouts yet</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentPayouts as $payout)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white font-semibold">₦{{ number_format($payout->amount, 0) }}</p>
                                        <p class="text-gray-400 text-xs">{{ $payout->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-semibold 
                                        @if ($payout->status === 'completed') bg-green-500/20 text-green-400
                                        @elseif ($payout->status === 'pending') bg-yellow-500/20 text-yellow-400
                                        @else bg-red-500/20 text-red-400
                                        @endif">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('dashboard.payout-history') }}" class="block text-center text-yellow-400 hover:text-yellow-300 text-sm font-semibold mt-4">
                        View All Payouts →
                    </a>
                </div>

                <!-- Update Bank Account Form -->
                <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
                    <h3 class="text-lg font-bold text-white mb-4" id="update-bank">Update Bank Account</h3>
                    <form action="{{ route('dashboard.update-bank') }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Account Name</label>
                            <input type="text" name="account_name" value="{{ Auth::user()->account_name }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-3 py-2 rounded focus:border-yellow-500 focus:outline-none" placeholder="Your Name" required>
                            @error('account_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Account Number</label>
                            <input type="text" name="account_number" value="{{ Auth::user()->account_number }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-3 py-2 rounded focus:border-yellow-500 focus:outline-none" placeholder="10-digit account" required>
                            @error('account_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ Auth::user()->bank_name }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-3 py-2 rounded focus:border-yellow-500 focus:outline-none" placeholder="e.g., GTBank" required>
                            @error('bank_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">Bank Code</label>
                            <input type="text" name="bank_code" value="{{ Auth::user()->bank_code }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-3 py-2 rounded focus:border-yellow-500 focus:outline-none" placeholder="e.g., 007" required>
                            @error('bank_code') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded transition">
                            💾 Save Bank Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
