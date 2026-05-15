@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-yellow-400">Edit Event</h1>
            <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Events
            </a>
        </div>

        <!-- Edit Event Form -->
        <form action="{{ route('admin.update-event', $event->id) }}" method="POST" class="bg-gray-900 border border-gray-700 rounded-lg p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Organizer Selection -->
            <div>
                <label class="block text-white font-semibold mb-2">👤 Organizer</label>
                <select name="user_id" required class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition">
                    <option value="">-- Select Organizer --</option>
                    @foreach($organizers as $organizer)
                        <option value="{{ $organizer->id }}" {{ $event->user_id == $organizer->id ? 'selected' : '' }}>
                            {{ $organizer->name }} ({{ $organizer->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Event Title -->
            <div>
                <label class="block text-white font-semibold mb-2">🎭 Event Title</label>
                <input type="text" name="title" placeholder="Enter event title" required
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                    value="{{ old('title', $event->title) }}">
                @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Event Description -->
            <div>
                <label class="block text-white font-semibold mb-2">📝 Description</label>
                <textarea name="description" placeholder="Detailed event description" required rows="5"
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition resize-none">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Date & Time Row -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">📅 Date</label>
                    <input type="date" name="date" required 
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('date', $event->date->format('Y-m-d')) }}">
                    @error('date') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">⏰ Time</label>
                    <input type="time" name="time" required
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('time', $event->date->format('H:i')) }}">
                    @error('time') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-white font-semibold mb-2">📍 Location</label>
                <input type="text" name="location" placeholder="Event location" required
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                    value="{{ old('location', $event->location) }}">
                @error('location') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price & Tickets Row -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">💰 Ticket Price (₦)</label>
                    <input type="number" name="price" min="0" step="100" required
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('price', $event->price) }}">
                    @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">🎫 Available Tickets</label>
                    <input type="number" name="available_tickets" min="1" required
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('available_tickets', $event->available_tickets) }}">
                    @error('available_tickets') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Event Stats (Read-only) -->
            <div class="grid grid-cols-3 gap-4 bg-gray-800/50 rounded-lg p-6 border border-gray-700">
                <div>
                    <p class="text-gray-400 text-sm">Tickets Sold</p>
                    <p class="text-yellow-400 font-bold text-2xl">{{ $event->tickets()->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Revenue</p>
                    <p class="text-green-400 font-bold text-2xl">₦{{ number_format($event->tickets()->sum('price')) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Capacity</p>
                    <p class="text-blue-400 font-bold text-2xl">{{ $event->available_tickets }}</p>
                </div>
            </div>

            <!-- Warning for sold-out events -->
            @if($event->tickets()->count() >= $event->available_tickets)
                <div class="bg-orange-900/40 border border-orange-700 rounded-lg p-4">
                    <p class="text-orange-300">⚠️ This event is sold out. Consider increasing the available tickets if you want to allow more sales.</p>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-3 rounded-lg transition font-bold">
                    Cancel
                </a>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-black px-8 py-3 rounded-lg transition font-bold">
                    ✏️ Update Event
                </button>
            </div>
        </form>

        <!-- Delete Event (Danger Zone) -->
        <form action="{{ route('admin.delete-event', $event->id) }}" method="POST" class="mt-12 bg-red-900/10 border border-red-800 rounded-lg p-8">
            @csrf
            @method('DELETE')
            
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-red-400 mb-2">🗑️ Delete Event</h3>
                    <p class="text-red-300 text-sm">This action cannot be undone. All associated data will be deleted.</p>
                </div>
                <button type="submit" onclick="return confirm('Are you sure you want to delete this event?')" 
                    class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg transition font-bold">
                    Delete Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
