@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-yellow-400 mb-2">🛡️ Admin Dashboard</h1>
            <p class="text-gray-400">Platform Overview & Management</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Users</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
            </div>

            <!-- Total Events -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Events</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['total_events'] }}</p>
                    </div>
                    <div class="text-4xl">🎫</div>
                </div>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Tickets Sold</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $stats['total_tickets_sold'] }}</p>
                    </div>
                    <div class="text-4xl">🎟️</div>
                </div>
            </div>

            <!-- Platform Earnings -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Platform Fee (5%)</p>
                        <p class="text-2xl font-bold text-yellow-400">₦{{ number_format($stats['platform_earnings'], 2) }}</p>
                    </div>
                    <div class="text-4xl">💰</div>
                </div>
            </div>
        </div>

        <!-- Revenue Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-white mb-4">💵 Total Revenue</h3>
                <p class="text-4xl font-bold text-green-400">₦{{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-sm text-gray-400 mt-2">From {{ $stats['total_tickets_sold'] }} tickets sold</p>
            </div>

            <!-- Payout Stats -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-white mb-4">📤 Payout Status</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Completed:</span>
                        <span class="text-green-400 font-bold">₦{{ number_format($stats['completed_payouts'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Pending:</span>
                        <span class="text-yellow-400 font-bold">₦{{ number_format($stats['pending_payouts'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-white mb-4">🔗 Quick Links</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users') }}" class="bg-gray-800 hover:bg-gray-700 rounded-lg p-4 text-center transition">
                    <div class="text-2xl mb-2">👥</div>
                    <div class="text-sm text-gray-300">Manage Users</div>
                </a>
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 rounded-lg p-4 text-center transition">
                    <div class="text-2xl mb-2">🎫</div>
                    <div class="text-sm text-gray-300">Manage Events</div>
                </a>
                <a href="{{ route('admin.payments') }}" class="bg-gray-800 hover:bg-gray-700 rounded-lg p-4 text-center transition">
                    <div class="text-2xl mb-2">💳</div>
                    <div class="text-sm text-gray-300">View Payments</div>
                </a>
                <a href="{{ route('admin.payouts') }}" class="bg-gray-800 hover:bg-gray-700 rounded-lg p-4 text-center transition">
                    <div class="text-2xl mb-2">📤</div>
                    <div class="text-sm text-gray-300">Manage Payouts</div>
                </a>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-white mb-4">🎬 Recent Events</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Event</th>
                            <th class="text-left py-3 px-4 text-gray-400">Organizer</th>
                            <th class="text-left py-3 px-4 text-gray-400">Date</th>
                            <th class="text-left py-3 px-4 text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recent_events as $event)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white">{{ $event->title }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $event->user->name }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $event->date->format('M d, Y') }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.event-details', $event) }}" class="text-yellow-400 hover:text-yellow-300">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">No events yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-white mb-4">💳 Recent Payments</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Amount</th>
                            <th class="text-left py-3 px-4 text-gray-400">Event</th>
                            <th class="text-left py-3 px-4 text-gray-400">Buyer</th>
                            <th class="text-left py-3 px-4 text-gray-400">Status</th>
                            <th class="text-left py-3 px-4 text-gray-400">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recent_payments as $payment)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white font-bold">₦{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $payment->event->title }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $payment->buyer->name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded text-xs font-bold
                                    @if($payment->status === 'completed') bg-green-900 text-green-400
                                    @elseif($payment->status === 'pending') bg-yellow-900 text-yellow-400
                                    @else bg-red-900 text-red-400
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No payments yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payouts -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">📤 Recent Payouts</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Amount</th>
                            <th class="text-left py-3 px-4 text-gray-400">Organizer</th>
                            <th class="text-left py-3 px-4 text-gray-400">Event</th>
                            <th class="text-left py-3 px-4 text-gray-400">Status</th>
                            <th class="text-left py-3 px-4 text-gray-400">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recent_payouts as $payout)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white font-bold">₦{{ number_format($payout->amount, 2) }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $payout->user->name }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $payout->event->title }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded text-xs font-bold
                                    @if($payout->status === 'completed') bg-green-900 text-green-400
                                    @elseif($payout->status === 'pending') bg-yellow-900 text-yellow-400
                                    @elseif($payout->status === 'retrying') bg-blue-900 text-blue-400
                                    @else bg-red-900 text-red-400
                                    @endif">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">{{ $payout->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No payouts yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
