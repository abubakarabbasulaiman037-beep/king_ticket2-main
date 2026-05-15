@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard.index') }}" class="text-yellow-400 hover:text-yellow-300 mb-4 inline-block">← Back to Dashboard</a>
            <h1 class="text-4xl font-bold text-white Playfair Display">💸 Payout History</h1>
            <p class="text-gray-400 mt-2">Track all your bank transfers and instant payouts</p>
        </div>

        <!-- Payout Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Paid -->
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Total Paid Out</p>
                <p class="text-3xl font-bold text-green-400">₦{{ number_format($stats['total_paid'], 0) }}</p>
                <p class="text-gray-500 text-xs mt-2">Successfully transferred</p>
            </div>

            <!-- Pending Payouts -->
            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Pending Payouts</p>
                <p class="text-3xl font-bold text-yellow-400">₦{{ number_format($stats['total_pending'], 0) }}</p>
                <p class="text-yellow-500 text-xs mt-2">Being processed</p>
            </div>

            <!-- Failed Payouts -->
            <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-lg p-6 backdrop-blur-xl">
                <p class="text-gray-400 text-sm mb-2">Failed Transfers</p>
                <p class="text-3xl font-bold text-red-400">₦{{ number_format($stats['total_failed'], 0) }}</p>
                <p class="text-red-500 text-xs mt-2">{{ $stats['failed_count'] }} transaction(s)</p>
            </div>
        </div>

        <!-- Payouts List -->
        <div class="bg-gradient-to-br from-gray-900/50 to-gray-800/50 border border-gray-700/50 rounded-lg p-6 backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white mb-6">All Transactions</h2>

            @php
                $payouts = Auth::user()->payouts()->latest()->paginate(20);
            @endphp

            @if ($payouts->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-400 mb-4 text-lg">No payouts yet</p>
                    <p class="text-gray-500">When you sell tickets, your earnings will be transferred automatically</p>
                    <a href="{{ route('dashboard.index') }}" class="inline-block mt-6 bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-6 rounded-lg transition">
                        Back to Dashboard
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="pb-4 text-gray-400 font-semibold">Date</th>
                                <th class="pb-4 text-gray-400 font-semibold">Event</th>
                                <th class="pb-4 text-gray-400 font-semibold">Amount</th>
                                <th class="pb-4 text-gray-400 font-semibold">Platform Fee</th>
                                <th class="pb-4 text-gray-400 font-semibold">Reference</th>
                                <th class="pb-4 text-gray-400 font-semibold">Status</th>
                                <th class="pb-4 text-gray-400 font-semibold">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payouts as $payout)
                                <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition">
                                    <td class="py-4">
                                        <span class="text-gray-300">{{ $payout->created_at->format('M d, Y') }}</span>
                                        <span class="text-gray-500 text-xs block">{{ $payout->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-white font-semibold">{{ $payout->event->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-green-400 font-bold">₦{{ number_format($payout->amount, 0) }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-red-400">₦{{ number_format($payout->platform_fee, 0) }}</span>
                                    </td>
                                    <td class="py-4">
                                        <code class="text-yellow-400 text-xs bg-gray-800 px-2 py-1 rounded">{{ substr($payout->reference, -8) }}</code>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($payout->status === 'completed') bg-green-500/20 text-green-400
                                            @elseif ($payout->status === 'pending') bg-yellow-500/20 text-yellow-400
                                            @elseif ($payout->status === 'retrying') bg-blue-500/20 text-blue-400
                                            @else bg-red-500/20 text-red-400
                                            @endif">
                                            @if ($payout->status === 'completed')
                                                ✓ Completed
                                            @elseif ($payout->status === 'pending')
                                                ⏳ Pending
                                            @elseif ($payout->status === 'retrying')
                                                🔄 Retrying
                                            @else
                                                ✗ Failed
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <button onclick="showPayoutDetails({{ json_encode($payout) }})" class="text-blue-400 hover:text-blue-300 font-semibold">
                                            View →
                                        </button>
                                    </td>
                                </tr>

                                @if ($payout->error_message)
                                    <tr class="border-b border-gray-700/50">
                                        <td colspan="7" class="py-3">
                                            <div class="bg-red-500/10 border border-red-500/30 rounded p-3 text-red-400 text-sm">
                                                <strong>Error:</strong> {{ $payout->error_message }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>

        <!-- How Payouts Work -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-lg p-6 backdrop-blur-xl">
                <h3 class="text-lg font-bold text-white mb-4">⚡ Instant Transfers</h3>
                <div class="space-y-3 text-gray-300 text-sm">
                    <p>✓ Payouts processed immediately when ticket is sold</p>
                    <p>✓ 95% of ticket price sent to your bank account</p>
                    <p>✓ No waiting period or delays</p>
                    <p>✓ Tracked in real-time on this dashboard</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 rounded-lg p-6 backdrop-blur-xl">
                <h3 class="text-lg font-bold text-white mb-4">💡 Fee Breakdown</h3>
                <div class="space-y-3 text-gray-300 text-sm">
                    <p>🏦 Your Cut: <strong>95%</strong> - Transferred instantly</p>
                    <p>⚙️ Platform Fee: <strong>5%</strong> - For operations</p>
                    <p>📊 View details per event in dashboard</p>
                    <p>🔒 Secure Paystack payment gateway</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<script>
function showPayoutDetails(payout) {
    const message = `
Payout Details
==============
Event: ${payout.event ? payout.event.name : 'N/A'}
Date: ${new Date(payout.created_at).toLocaleDateString()}
Amount: ₦${Number(payout.amount).toLocaleString()}
Platform Fee: ₦${Number(payout.platform_fee).toLocaleString()}
Status: ${payout.status}
Reference: ${payout.reference}
${payout.paystack_transfer_code ? 'Transfer Code: ' + payout.paystack_transfer_code : ''}
${payout.error_message ? 'Error: ' + payout.error_message : ''}
    `;
    alert(message);
}
</script>
@endsection
