@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-yellow-400">✨ Auto-Generate Event with AI</h1>
            <div class="space-x-4">
                <a href="{{ route('admin.create-event') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg transition font-bold inline-block">
                    📝 Manual Create Instead
                </a>
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-900/40 border border-blue-700 rounded-lg p-6 mb-8">
            <p class="text-blue-300">💡 Just describe your event in natural language and we'll automatically extract all the details. Example: "Concert by The Kings at Lagos Convention Center on 25/5/2026 at 7pm, tickets ₦15000, 500 spots"</p>
        </div>

        <!-- Generate Event Form -->
        <form action="{{ route('admin.generate-event') }}" method="POST" class="bg-gray-900 border border-gray-700 rounded-lg p-8 space-y-6">
            @csrf

            <!-- Organizer Selection -->
            <div>
                <label class="block text-white font-semibold mb-2">👤 Select Organizer</label>
                <select name="user_id" required class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition">
                    <option value="">-- Select Organizer --</option>
                    @foreach($organizers as $organizer)
                        <option value="{{ $organizer->id }}">{{ $organizer->name }} ({{ $organizer->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Event Description -->
            <div>
                <label class="block text-white font-semibold mb-2">🎯 Describe Your Event</label>
                <textarea name="description" placeholder="Examples:
• Concert by The Kings at Lagos Convention Center, 25/5/2026, 7pm, ₦15000, 500 spots
• Tech Summit in Abuja - May 20, 2026, 10am, Location: Sheraton Hotel, Price: ₦2500, Capacity: 1000
• Movie premiere at IMAX Lekki, 18/5/2026 6pm, price 8000 naira, 200 tickets

Be as detailed as you want - include date, time, location, price, and ticket quantity." 
                    required rows="10"
                    class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition resize-none"
                    value="{{ old('description') }}"></textarea>
                <p class="text-gray-400 text-sm mt-2">💡 Include: Event name, location, date, time, price, number of tickets</p>
                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Notes -->
            <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                <p class="text-gray-300 text-sm"><strong>Note:</strong> Our AI will intelligently extract:</p>
                <ul class="list-disc list-inside text-gray-400 text-sm mt-2 space-y-1">
                    <li>Event title from the first line</li>
                    <li>Location (looks for "at", "in", "venue")</li>
                    <li>Date in any common format (dd/mm/yyyy, yyyy-mm-dd, etc.)</li>
                    <li>Time (HH:mm format)</li>
                    <li>Price (looks for ₦, N, price, cost, ticket)</li>
                    <li>Ticket quantity (looks for tickets, capacity, spots)</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.events') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-3 rounded-lg transition font-bold">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition font-bold flex items-center space-x-2">
                    <span>✨</span>
                    <span>Generate Event</span>
                </button>
            </div>
        </form>

        <!-- Examples Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-yellow-400 mb-6">📝 Example Descriptions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                    <p class="text-yellow-400 font-bold mb-2">Concert Event</p>
                    <p class="text-gray-300 text-sm">Burna Boy's Afrobeats Concert at Eko Hotels, Lagos on June 15, 2026 at 8:00 PM. VIP Ticket price: ₦25000. Total capacity: 5000 people</p>
                </div>
                <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                    <p class="text-yellow-400 font-bold mb-2">Tech Conference</p>
                    <p class="text-gray-300 text-sm">Tech Conference 2026 at Abuja Sheraton, 2026-05-20, 9am. Entry: ₦5000 per ticket. We're expecting 2000 attendees</p>
                </div>
                <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                    <p class="text-yellow-400 font-bold mb-2">Sports Event</p>
                    <p class="text-gray-300 text-sm">Final Match - Nigerian Premier League at Lekki Stadium, May 28, 2026, 4pm. Entry fee: ₦3000. Available spots: 10000</p>
                </div>
                <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                    <p class="text-yellow-400 font-bold mb-2">Workshop</p>
                    <p class="text-gray-300 text-sm">Digital Marketing Workshop at Victoria Island Training Center on 22/5/2026 at 10:00am. Price per person: ₦1500. Capacity: 150 participants</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
