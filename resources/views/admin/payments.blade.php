@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-yellow-400 mb-2">💳 Payments</h1>
                <p class="text-gray-400">Total {{ $payments->total() }} payments | Page {{ $payments->currentPage() }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 px-6 py-2 rounded-lg transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Payments Table -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700 bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Amount</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Event</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Buyer</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Reference</th>
                            <th class="text-center py-4 px-6 text-yellow-400 font-bold">Status</th>
                            <th class="text-left py-4 px-6 text-yellow-400 font-bold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                            <td class="py-4 px-6 text-white font-bold">₦{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-4 px-6 text-gray-400">
                                <a href="{{ route('admin.event-details', $payment->event) }}" class="text-yellow-400 hover:text-yellow-300">
                                    {{ $payment->event->title }}
                                </a>
                            </td>
                            <td class="py-4 px-6 text-gray-400">{{ $payment->buyer->name }}</td>
                            <td class="py-4 px-6 text-gray-400 font-mono text-sm">{{ substr($payment->reference, 0, 20) }}...</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded text-xs font-bold
                                    @if($payment->status === 'completed') bg-green-900 text-green-400
                                    @elseif($payment->status === 'pending') bg-yellow-900 text-yellow-400
                                    @else bg-red-900 text-red-400
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">No payments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
