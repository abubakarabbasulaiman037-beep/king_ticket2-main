@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 md:px-8">
    <!-- Controls -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12 print:hidden gap-4">
        <a href="{{ route('tickets.mine') }}" class="text-sm font-bold text-gray-400 hover:text-gold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Tickets
        </a>
        <div class="flex flex-wrap gap-3">
            <!-- Download PDF -->
            <a href="{{ route('tickets.download-pdf', $ticket) }}" class="px-4 py-2 rounded-lg border border-gold/40 text-gold font-semibold text-sm hover:bg-gold/10 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                PDF
            </a>

            <!-- Download Image -->
            <a href="{{ route('tickets.download-image', $ticket) }}" class="px-4 py-2 rounded-lg border border-gold/40 text-gold font-semibold text-sm hover:bg-gold/10 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Image
            </a>

            <!-- Print -->
            <button onclick="window.print()" class="btn-accent px-4 py-2 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-8H5"></path></svg>
                Print
            </button>
        </div>
    </div>

    <!-- TICKET CONTAINER -->
    <div class="relative w-full mx-auto border border-edge-default rounded-2xl overflow-hidden bg-surface-primary shadow-lg print:shadow-none print:border-0">
        <!-- Split Layout: Main Ticket (70%) and Stub (30%) -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-0">
            <!-- MAIN TICKET SECTION (70%) -->
            <div class="md:col-span-8 border-r border-edge-default print:border-r-0">
                <!-- Event Banner -->
                <div class="relative aspect-[16/6] overflow-hidden bg-gradient-to-br from-gold/5 to-purple-600/5">
                    <img src="{{ $ticket->event->getCinematicImageUrl() }}" class="w-full h-full object-cover" alt="{{ $ticket->event->title }}">
                    <div class="absolute inset-0 bg-black/40"></div>
                    
                    <!-- Event Header -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-gold text-xs font-bold uppercase tracking-widest mb-4">Event Ticket</p>
                            <h1 class="text-4xl md:text-5xl font-black text-white mb-2 leading-tight px-4">
                                {{ $ticket->event->title }}
                            </h1>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="p-8 md:p-12">
                    <!-- Status Badge -->
                    <div class="mb-8">
                        @if($ticket->is_used)
                            <span class="inline-block px-4 py-2 rounded-lg bg-red-500/20 border border-red-500/40 text-red-300 text-sm font-bold uppercase tracking-wider">✓ Used</span>
                        @elseif($ticket->is_verified)
                            <span class="inline-block px-4 py-2 rounded-lg bg-green-500/20 border border-green-500/40 text-green-300 text-sm font-bold uppercase tracking-wider">✓ Verified</span>
                        @else
                            <span class="inline-block px-4 py-2 rounded-lg bg-gold/20 border border-gold/40 text-gold text-sm font-bold uppercase tracking-wider">● Valid</span>
                        @endif
                    </div>

                    <!-- Event Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8 mb-12 pb-12 border-b border-edge-subtle">
                        <!-- Date -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Date & Time</p>
                            <p class="text-lg font-black text-white">{{ $ticket->event->date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-400">{{ $ticket->event->date->format('g:i A') }}</p>
                        </div>

                        <!-- Location -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Location</p>
                            <p class="text-lg font-black text-white">{{ $ticket->event->location }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Price Paid</p>
                            <p class="text-lg font-black text-gold">₦{{ number_format($ticket->price, 0) }}</p>
                        </div>

                        <!-- Ticket Type -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Ticket Type</p>
                            <p class="text-lg font-black text-white">{{ $ticket->ticket_type ?? 'Standard' }}</p>
                        </div>

                        <!-- Attendee -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Attendee</p>
                            <p class="text-lg font-black text-white">{{ $ticket->attendee_name ?? 'N/A' }}</p>
                        </div>

                        <!-- Purchase Date -->
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Purchased</p>
                            <p class="text-lg font-black text-white">{{ $ticket->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Ticket Number -->
                    <div class="mb-8">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-3">Ticket Number</p>
                        <p class="text-2xl font-black text-gold font-mono">{{ $ticket->ticket_number }}</p>
                    </div>

                    <!-- Transaction Info -->
                    @if($ticket->transaction_id)
                        <div class="p-4 rounded-lg bg-surface-secondary border border-edge-default">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Transaction ID</p>
                            <p class="text-sm font-mono text-gray-300">{{ $ticket->transaction_id }}</p>
                        </div>
                    @endif
                </div>

                <!-- Footer Strip -->
                <div class="px-8 md:px-12 py-6 bg-gradient-to-r from-gold/10 to-gold/5 border-t border-edge-default flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Event Organizer</p>
                        <p class="text-sm font-bold text-white">{{ $ticket->event->organizer->name ?? 'King Ticket' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Powered by</p>
                        <p class="text-sm font-bold text-gold">KING TICKET</p>
                    </div>
                </div>
            </div>

            <!-- STUB SECTION (30%) -->
            <div class="md:col-span-4 p-8 flex flex-col justify-between bg-gradient-to-b from-surface-card to-surface-primary">
                <!-- Top Ribbon -->
                <div class="absolute top-0 right-0 left-[66.666%] h-6 bg-gradient-to-r from-purple-600 to-gold text-white flex items-center justify-center">
                    <span class="text-xs font-black uppercase tracking-widest">ADMIT ONE</span>
                </div>

                <!-- Content -->
                <div class="mt-8">
                    <!-- Status -->
                    <div class="mb-8 text-center">
                        @if($ticket->is_used)
                            <p class="text-xs text-red-300 font-bold uppercase tracking-widest mb-2">Status: Used</p>
                            <div class="text-4xl text-red-300 opacity-50 font-black">✓</div>
                        @elseif($ticket->is_verified)
                            <p class="text-xs text-green-300 font-bold uppercase tracking-widest mb-2">Status: Verified</p>
                            <div class="text-4xl text-green-300 opacity-50 font-black">✓</div>
                        @else
                            <p class="text-xs text-gold font-bold uppercase tracking-widest mb-2">Status: Valid</p>
                            <div class="text-4xl text-gold opacity-50 font-black">●</div>
                        @endif
                    </div>

                    <!-- QR Code -->
                    <div class="mb-8">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider text-center mb-4">QR CODE</p>
                        <div class="flex justify-center">
                            <div class="border-2 border-gold/30 p-3 rounded-lg bg-white/5">
                                @if($ticket->qr_code)
                                    <img src="{{ url('storage/qrcodes/' . $ticket->qr_code) }}" alt="QR Code" class="w-32 h-32 object-contain" />
                                @else
                                    <div class="w-32 h-32 bg-surface-secondary flex items-center justify-center text-gray-500 text-xs rounded">
                                        <div class="text-center">
                                            <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <p>QR Pending</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ticket ID Text -->
                    <div class="text-center mb-8">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Ticket ID</p>
                        <p class="text-sm font-mono text-gold font-bold break-all">{{ $ticket->ticket_number }}</p>
                    </div>
                </div>

                <!-- Bottom Rules -->
                <div class="border-t border-edge-default pt-6 text-center text-xs text-gray-400 space-y-2">
                    <p class="font-semibold text-gray-300">Important</p>
                    <p>Present this ticket at entry</p>
                    <p>Non-transferable</p>
                    <p class="text-gray-500 text-[10px]">king-ticket.com</p>
                </div>
            </div>
        </div>

        <!-- Perforation Line -->
        <div class="absolute top-0 bottom-0 left-[66.666%] w-px border-l-2 border-dashed border-gold/30 print:hidden"></div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body {
                background: white;
            }
            .print\:hidden {
                display: none !important;
            }
            .ticket-container {
                filter: none;
                box-shadow: none;
            }
        }
    </style>
</div>

@endsection
