@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-yellow-400 mb-2">🎫 Event Management</h1>
                <p class="text-gray-400">Total {{ $events->total() }} events | Page {{ $events->currentPage() }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Events Table -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700 bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Event</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Organizer</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Date</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Tickets</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Payments</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-4 px-6 text-white font-semibold">{{ $event->title }}</td>
                            <td class="py-4 px-6 text-gray-400">{{ $event->user->name }}</td>
                            <td class="py-4 px-6 text-gray-400">{{ $event->date->format('M d, Y') }}</td>
                            <td class="py-4 px-6 text-center text-white font-bold">{{ $event->tickets_count }}</td>
                            <td class="py-4 px-6 text-center text-white font-bold">{{ $event->payments_count }}</td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.event-details', $event) }}" class="text-yellow-400 hover:text-yellow-300 font-bold">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">No events found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
