@extends('layouts.app')

@section('content')
<!-- Vault Header -->
<div class="mb-16">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
        <div>
            <h1 class="text-xs font-bold text-gold uppercase tracking-widest mb-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-gold rounded-full"></span>
                My Bookings
            </h1>
            <div class="text-4xl md:text-5xl font-black text-white tracking-tight">Your Tickets</div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex bg-surface-primary border border-edge-subtle p-1 rounded-lg shadow-lux-sm">
                <button class="filter-btn active-lux px-6 py-2 text-xs font-bold uppercase tracking-wider transition-all rounded-md" data-filter="all">All Tickets</button>
                <button class="filter-btn px-6 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider hover:text-white transition-all rounded-md" data-filter="upcoming">Upcoming</button>
                <button class="filter-btn px-6 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider hover:text-white transition-all rounded-md" data-filter="attended">Past</button>
            </div>
        </div>
    </div>
</div>

<!-- Assets Grid -->
@if($tickets->isEmpty())
    <div class="card-cyber py-32 text-center border-dashed border-edge-strong bg-transparent">
        <div class="mb-6 opacity-50">
            <span class="text-6xl text-gold">🎫</span>
        </div>
        <p class="text-white text-xl font-bold mb-8">You don't have any tickets yet.</p>
        <a href="{{ route('events.public') }}" class="btn-primary">Explore Inventory</a>
    </div>
@else
    <div class="space-y-8" id="ticket-container">
        @foreach($tickets as $ticket)
            @php
                $isUpcoming = $ticket->event->date > now();
                $status = $isUpcoming ? 'upcoming' : 'attended';
            @endphp
            <div class="ticket-card card-cyber group p-0 overflow-hidden transition-all duration-500" data-status="{{ $status }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-0">
                    <!-- Visual Segment -->
                    <div class="relative aspect-video md:aspect-auto bg-surface-card overflow-hidden border-r border-edge-subtle">
                        @if($ticket->event->banner)
                            <img src="{{ $ticket->event->bannerUrl() }}" class="absolute inset-0 w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 transition-all duration-1000">
                        @else
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-gold/10 to-amber-600/10 flex items-center justify-center grayscale">
                                <span class="text-gold font-black text-4xl opacity-20">👑</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/40 z-10 group-hover:bg-black/20 transition-all"></div>
                        <div class="absolute inset-0 flex items-center justify-center z-20">
                             <div class="w-12 h-12 rounded-full bg-surface-base/80 backdrop-blur-sm border border-gold/30 flex items-center justify-center text-gold font-black shadow-lux-sm">
                                0{{ $loop->iteration }}
                             </div>
                        </div>
                    </div>

                    <!-- Intelligence Segment -->
                    <div class="md:col-span-2 p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $ticket->event->date->format('M d, Y') }}</div>
                            <div class="w-1.5 h-1.5 bg-gray-500 rounded-full"></div>
                            <div class="text-xs font-bold {{ $isUpcoming ? 'text-green-400 bg-green-400/10 px-3 py-1 rounded-full' : 'text-gray-500 bg-gray-500/10 px-3 py-1 rounded-full' }} uppercase tracking-widest">
                                {{ $isUpcoming ? 'Upcoming' : 'Past Event' }}
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-white tracking-tight mb-4 truncate group-hover:text-gold transition-colors">
                            {{ $ticket->event->title }}
                        </h3>
                        <div class="text-sm font-bold text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $ticket->event->location }}
                        </div>
                    </div>

                    <!-- Action Segment -->
                    <div class="p-8 flex items-center justify-end border-l border-edge-subtle bg-surface-primary group-hover:bg-surface-secondary transition-colors">
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn-accent w-full md:w-auto text-xs px-6 py-3">
                            View Ticket
                        </a>
                    </div>
                </div>
                <!-- Security Line -->
                <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-gold to-gold-dark w-0 group-hover:w-full transition-all duration-700"></div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .active-lux {
        @apply bg-gold text-surface-base border-gold shadow-lux-md;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const tickets = document.querySelectorAll('.ticket-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                filterBtns.forEach(b => b.classList.remove('active-lux', 'text-surface-base', 'bg-gold'));
                filterBtns.forEach(b => b.classList.add('text-gray-500'));
                btn.classList.add('active-lux');
                btn.classList.remove('text-gray-500');
                
                const filter = btn.dataset.filter;

                tickets.forEach(ticket => {
                    const status = ticket.dataset.status;
                    if (filter === 'all' || status === filter) {
                        ticket.style.display = 'block';
                        setTimeout(() => ticket.style.opacity = '1', 10);
                    } else {
                        ticket.style.opacity = '0';
                        setTimeout(() => ticket.style.display = 'none', 500);
                    }
                });
            });
        });
    });
</script>
@endsection
