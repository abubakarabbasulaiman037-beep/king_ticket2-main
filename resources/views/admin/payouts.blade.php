@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-yellow-400 mb-2">📤 Payouts</h1>
                <p class="text-gray-400">Total {{ $payouts->total() }} payouts | Page {{ $payouts->currentPage() }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Payout Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Completed -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm mb-2">✅ Completed</p>
                <p class="text-3xl font-bold text-green-400">₦{{ number_format($stats['total_paid'], 2) }}</p>
            </div>

            <!-- Pending -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm mb-2">⏳ Pending</p>
                <p class="text-3xl font-bold text-yellow-400">₦{{ number_format($stats['total_pending'], 2) }}</p>
            </div>

            <!-- Failed -->
            <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
                <p class="text-gray-400 text-sm mb-2">❌ Failed</p>
                <p class="text-3xl font-bold text-red-400">₦{{ number_format($stats['total_failed'], 2) }}</p>
            </div>
        </div>

        <!-- Payouts Table -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700 bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Amount</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Organizer</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Event</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Reference</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Status</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-4 px-6 text-white font-bold">₦{{ number_format($payout->amount, 2) }}</td>
                            <td class="py-4 px-6 text-gray-400">
                                <a href="{{ route('admin.user-details', $payout->user) }}" class="text-yellow-400 hover:text-yellow-300">
                                    {{ $payout->user->name }}
                                </a>
                            </td>
                            <td class="py-4 px-6 text-gray-400">
                                <a href="{{ route('admin.event-details', $payout->event) }}" class="text-yellow-400 hover:text-yellow-300">
                                    {{ $payout->event->title }}
                                </a>
                            </td>
                            <td class="py-4 px-6 text-gray-400 font-mono text-sm">{{ substr($payout->reference, 0, 20) }}...</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded text-xs font-bold
                                    @if($payout->status === 'completed') bg-green-900 text-green-400
                                    @elseif($payout->status === 'pending') bg-yellow-900 text-yellow-400
                                    @elseif($payout->status === 'retrying') bg-blue-900 text-blue-400
                                    @else bg-red-900 text-red-400
                                    @endif">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400">{{ $payout->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">No payouts found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection
