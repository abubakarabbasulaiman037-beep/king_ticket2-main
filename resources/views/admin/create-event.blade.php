@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-yellow-400">🎬 Create Event</h1>
            <div class="space-x-4">
                <a href="{{ route('admin.generate-event-form') }}" class="bg-green-900 hover:bg-green-800 text-green-300 px-6 py-2 rounded-lg transition font-bold inline-block">
                    ✨ AI Generate Instead
                </a>
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Create Event Form -->
        <form action="{{ route('admin.store-event') }}" method="POST" class="bg-gray-900 border border-gray-700 rounded-lg p-8 space-y-6">
            @csrf

            <!-- Organizer Selection -->
            <div>
                <label class="block text-white font-semibold mb-2">👤 Organizer</label>
                <select name="user_id" required class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition">
                    <option value="">-- Select Organizer --</option>
                    @foreach($organizers as $organizer)
                        <option value="{{ $organizer->id }}">{{ $organizer->name }} ({{ $organizer->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Event Title -->
            <div>
                <label class="block text-white font-semibold mb-2">🎫 Event Title</label>
                <input type="text" name="title" placeholder="e.g., Concert by The King" required 
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                    value="{{ old('title') }}">
                @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-white font-semibold mb-2">📝 Description</label>
                <textarea name="description" placeholder="Describe the event in detail..." required rows="5"
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-2">📅 Date</label>
                    <input type="date" name="date" required 
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('date') }}" min="{{ now()->addDay()->format('Y-m-d') }}">
                    @error('date') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">🕐 Time</label>
                    <input type="time" name="time" required 
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('time', '18:00') }}">
                    @error('time') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-white font-semibold mb-2">📍 Location</label>
                <input type="text" name="location" placeholder="e.g., Lagos Convention Center" required 
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                    value="{{ old('location') }}">
                @error('location') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price & Tickets -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-2">💰 Price (NGN)</label>
                    <input type="number" name="price" placeholder="5000" required min="0" step="100"
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('price', 5000) }}">
                    @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">🎟️ Available Tickets</label>
                    <input type="number" name="available_tickets" placeholder="100" required min="1"
                        class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition"
                        value="{{ old('available_tickets', 100) }}">
                    @error('available_tickets') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-3 rounded-lg transition font-bold">
                    Cancel
                </a>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg transition font-bold">
                    ✅ Create Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
