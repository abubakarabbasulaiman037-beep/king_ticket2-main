@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-yellow-400 mb-2">👥 User Management</h1>
                <p class="text-gray-400">Total {{ $users->total() }} users | Page {{ $users->currentPage() }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Users Table -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700 bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Name</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Email</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Bank</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Events</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-4 px-6 text-white font-semibold">{{ $user->name }}</td>
                            <td class="py-4 px-6 text-gray-400">{{ $user->email }}</td>
                            <td class="py-4 px-6 text-gray-400">{{ $user->bank_name ?? '-' }}</td>
                            <td class="py-4 px-6 text-center text-white font-bold">{{ $user->events_count }}</td>
                            <td class="py-4 px-6 text-center space-x-2">
                                <a href="{{ route('admin.user-details', $user) }}" class="text-yellow-400 hover:text-yellow-300 font-bold">View</a>
                                <form action="{{ route('admin.delete-user', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 font-bold">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
