@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard.index') }}" class="text-yellow-400 hover:text-yellow-300 mb-4 inline-block">← Back to Dashboard</a>
            <h1 class="text-4xl font-bold text-white Playfair Display">✨ Create New Event</h1>
            <p class="text-gray-400 mt-2">Fill in the details below to create your event</p>
        </div>

        <!-- Form Card -->
        <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-yellow-500/20 rounded-lg p-8 backdrop-blur-xl">
            <form action="{{ route('dashboard.store-event') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Event Name -->
                <div>
                    <label class="block text-white font-semibold mb-2">Event Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="Enter event name" required>
                    @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-white font-semibold mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="Describe your event" required>{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-white font-semibold mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="e.g., Concert, Sports, Conference" required>
                    @error('category') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Event Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Date</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" required>
                        @error('date') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Time</label>
                        <input type="time" name="time" value="{{ old('time') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" required>
                        @error('time') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-white font-semibold mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="Event venue" required>
                    @error('location') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Price & Currency -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Price</label>
                        <div class="flex items-center">
                            <span class="text-yellow-400 font-bold text-lg mr-2">₦</span>
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" class="flex-1 bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="0.00" required>
                        </div>
                        @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Number of Tickets</label>
                        <input type="number" name="available_tickets" value="{{ old('available_tickets') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="e.g., 100" required>
                        @error('available_tickets') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Banner Image -->
                <div>
                    <label class="block text-white font-semibold mb-2">Banner Image (Optional)</label>
                    <input type="file" name="banner" accept="image/*" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition file:bg-yellow-500 file:text-black file:border-0 file:rounded file:font-semibold file:cursor-pointer" />
                    @error('banner') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- LGA (Location) Selection -->
                <div>
                    <label class="block text-white font-semibold mb-2">LGA</label>
                    <select name="lga_id" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition">
                        <option value="">Select LGA (optional)</option>
                        @foreach (\App\Models\Lga::all() as $lga)
                            <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                        @endforeach
                    </select>
                    @error('lga_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Bank Selection for Payouts -->
                <div class="border-t border-gray-600 pt-6">
                    <h3 class="text-lg font-semibold text-yellow-400 mb-4">🏦 Your Bank for Payouts</h3>
                    <p class="text-gray-400 text-sm mb-4">When buyers purchase tickets, 95% of the payment will be transferred to your selected bank account instantly.</p>
                    
                    <label class="block text-white font-semibold mb-2">Bank Name</label>
                    <input type="text" name="bank" value="{{ old('bank') }}" class="w-full bg-gray-700/50 border border-gray-600 text-white px-4 py-3 rounded-lg focus:border-yellow-500 focus:outline-none transition" placeholder="e.g., GTBank, Access Bank, Opay" required>
                    @error('bank') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                    
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mt-4">
                        <p class="text-blue-300 text-sm">
                            <strong>💡 Pro Tip:</strong> Make sure to use the same bank name when updating your bank details in the dashboard for instant payouts to work correctly.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-black font-bold py-3 px-6 rounded-lg transition transform hover:scale-105">
                        ✨ Create Event
                    </button>
                    <a href="{{ route('dashboard.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
