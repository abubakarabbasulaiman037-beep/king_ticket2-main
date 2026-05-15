@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-yellow-400">{{ $user->name }}</h1>
            <a href="{{ route('admin.users') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Users
            </a>
        </div>

        <!-- User Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Basic Info -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">📋 Basic Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">Email</p>
                        <p class="text-white">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Member Since</p>
                        <p class="text-white">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">🏦 Bank Details</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">Account Name</p>
                        <p class="text-white">{{ $user->account_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Bank</p>
                        <p class="text-white">{{ $user->bank_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Account Number</p>
                        <p class="text-white">{{ $user->account_number ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Events -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm">Total Events</p>
                <p class="text-3xl font-bold text-yellow-400">{{ $events_count }}</p>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm">Tickets Sold</p>
                <p class="text-3xl font-bold text-yellow-400">{{ $tickets_sold }}</p>
            </div>

            <!-- Revenue -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-green-400">₦{{ number_format($total_revenue, 2) }}</p>
            </div>
        </div>

        <!-- Events -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
            <h3 class="text-xl font-bold text-yellow-400 mb-4">🎫 Events</h3>
            @if($user->events->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-4 text-gray-400">Event</th>
                            <th class="text-left py-3 px-4 text-gray-400">Date</th>
                            <th class="text-left py-3 px-4 text-gray-400">Location</th>
                            <th class="text-center py-3 px-4 text-gray-400">Tickets</th>
                            <th class="text-left py-3 px-4 text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->events as $event)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-3 px-4 text-white">{{ $event->title }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $event->date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $event->location }}</td>
                            <td class="py-3 px-4 text-center text-white font-bold">{{ $event->tickets->count() }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.event-details', $event) }}" class="text-yellow-400 hover:text-yellow-300">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500">No events created yet</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end">
            <form action="{{ route('admin.delete-user', $user) }}" method="POST" onsubmit="return confirm('This action cannot be undone. Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-900 hover:bg-red-800 text-red-300 px-6 py-2 rounded-lg transition font-bold">
                    🗑️ Delete User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
