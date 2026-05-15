@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-yellow-400">{{ $event->title }}</h1>
            <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Events
            </a>
        </div>

        <!-- Event Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Basic Info -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">ℹ️ Event Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">Organizer</p>
                        <p class="text-white">
                            <a href="{{ route('admin.user-details', $event->user) }}" class="text-yellow-400 hover:text-yellow-300">
                                {{ $event->user->name }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Date & Time</p>
                        <p class="text-white">{{ $event->date->format('M d, Y \\a\\t H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Location</p>
                        <p class="text-white">{{ $event->location }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Price per Ticket</p>
                        <p class="text-white">₦{{ number_format($event->price, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">📊 Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Tickets:</span>
                        <span class="text-white font-bold">{{ $ticket_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tickets Sold:</span>
                        <span class="text-green-400 font-bold">{{ $sold_tickets }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tickets Available:</span>
                        <span class="text-yellow-400 font-bold">{{ $ticket_count - $sold_tickets }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-700">
                        <span class="text-gray-400">Total Revenue:</span>
                        <span class="text-green-400 font-bold">₦{{ number_format($total_revenue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-yellow-400 mb-4">📝 Description</h3>
            <p class="text-gray-300">{{ $event->description }}</p>
        </div>

        <!-- Tickets -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-yellow-400 mb-4">🎟️ Tickets</h3>
            @if($event->tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Ticket #</th>
                            <th class="text-left py-3 px-4 text-gray-400">Buyer</th>
                            <th class="text-left py-3 px-4 text-gray-400">Status</th>
                            <th class="text-left py-3 px-4 text-gray-400">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->tickets as $ticket)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white font-mono">{{ $ticket->ticket_number ?? '#'.$ticket->id }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $ticket->user->name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded text-xs font-bold
                                    @if($ticket->used) bg-red-900 text-red-400
                                    @else bg-green-900 text-green-400
                                    @endif">
                                    {{ $ticket->used ? 'Used' : 'Valid' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">{{ $ticket->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500">No tickets for this event</p>
            @endif
        </div>

        <!-- Payments -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
            <h3 class="text-xl font-bold text-yellow-400 mb-4">💳 Payments</h3>
            @if($event->payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Amount</th>
                            <th class="text-left py-3 px-4 text-gray-400">Buyer</th>
                            <th class="text-left py-3 px-4 text-gray-400">Status</th>
                            <th class="text-left py-3 px-4 text-gray-400">Reference</th>
                            <th class="text-left py-3 px-4 text-gray-400">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->payments as $payment)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white font-bold">₦{{ number_format($payment->amount, 2) }}</td>
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
                            <td class="py-3 px-4 text-gray-400 font-mono text-xs">{{ substr($payment->reference, 0, 12) }}...</td>
                            <td class="py-3 px-4 text-gray-400">{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500">No payments yet</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <form action="{{ route('admin.delete-event', $event) }}" method="POST" onsubmit="return confirm('This action cannot be undone. Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-900 hover:bg-red-800 text-red-300 px-6 py-2 rounded-lg transition font-bold">
                    🗑️ Delete Event
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
