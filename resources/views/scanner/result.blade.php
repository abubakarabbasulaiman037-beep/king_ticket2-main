@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-12 px-4 shadow-lux-xl animate-fade-in text-center">
    @if($status === 'invalid')
        <div class="bg-surface-primary border border-edge-strong rounded-2xl p-8 mb-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                <span class="text-3xl">🚫</span>
            </div>
            <h2 class="text-2xl font-black text-red-500 uppercase tracking-widest mb-2">Invalid Asset</h2>
            <p class="text-gray-400 font-semibold">{{ $message ?? 'Asset not located in system.' }}</p>
        </div>
    @elseif($status === 'used')
        <div class="bg-surface-primary border border-edge-strong rounded-2xl p-8 mb-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-500/20 border border-yellow-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(234,179,8,0.3)]">
                <span class="text-3xl">⚠️</span>
            </div>
            <h2 class="text-2xl font-black text-yellow-500 uppercase tracking-widest mb-2">Clearance Depleted</h2>
            <p class="text-gray-400 font-semibold mb-6">{{ $message }}</p>
            
            <div class="bg-surface-elevated border border-edge-subtle rounded-xl p-6 text-left space-y-4 shadow-inner">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Protocol Node</p>
                    <p class="text-white font-bold">{{ $ticket->event->title }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Identifier</p>
                    <p class="font-mono text-white">{{ $ticket->id }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Authorized To</p>
                    <p class="text-white">{{ $ticket->user ? $ticket->user->name : 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Assigned Sector</p>
                    <p class="text-white">{{ $ticket->seat_number ?? 'General Admission' }}</p>
                </div>
            </div>
        </div>
    @elseif($status === 'valid')
        <div class="bg-surface-primary border border-edge-strong rounded-2xl p-8 mb-4 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-green-500/20 rounded-full blur-2xl"></div>
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-500/20 border border-green-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(34,197,94,0.3)] relative z-10">
                <span class="text-3xl">✅</span>
            </div>
            <h2 class="text-2xl font-black text-green-400 uppercase tracking-widest mb-2 relative z-10">Access Granted</h2>
            <p class="text-gray-400 font-semibold mb-6 relative z-10">{{ $message }}</p>
            
            <div class="bg-surface-elevated border border-edge-subtle rounded-xl p-6 text-left space-y-4 shadow-inner relative z-10">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Protocol Node</p>
                    <p class="text-white font-bold">{{ $ticket->event->title }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Identifier</p>
                    <p class="font-mono text-gold-light">{{ $ticket->id }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Authorized To</p>
                    <p class="text-white">{{ $ticket->user ? $ticket->user->name : 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Assigned Sector</p>
                    <p class="text-white">{{ $ticket->seat_number ?? 'General Admission' }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <div class="mt-8">
        <a href="{{ route('scanner.index') }}" class="btn-accent px-8 py-3 m-0 shadow-lux-sm inline-block">
            Resume Operations
        </a>
    </div>
</div>
@endsection
