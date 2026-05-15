@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-surface-primary border border-edge-strong rounded-2xl shadow-lux-xl p-8 text-center relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-red-500/20 rounded-full blur-2xl"></div>
            <div class="mb-6 text-6xl font-black text-red-500 relative z-10">419</div>
            
            <h1 class="text-2xl font-black text-white mb-4 relative z-10">Session Expired</h1>
            
            <p class="text-gray-400 text-sm mb-8 font-semibold relative z-10">
                Your session has expired due to inactivity. This typically happens after:
            </p>

            <ul class="text-left text-sm text-gray-400 mb-8 space-y-2 bg-surface-elevated p-4 rounded-xl border border-edge-subtle shadow-inner font-semibold relative z-10">
                <li>• Browser refresh or back button</li>
                <li>• Long periods without activity</li>
                <li>• Clearing browser cookies</li>
                <li>• Token verification failure</li>
            </ul>

            <div class="space-y-3">
                <a href="{{ request()->header('Referer') ?: route('home') }}" class="btn-accent w-full py-3 m-0 inline-block text-center shadow-lux-sm">
                    Go Back & Try Again
                </a>
                
                <a href="{{ route('home') }}" class="w-full py-3 rounded-lg border border-edge-strong text-white font-bold uppercase tracking-wider text-sm hover:bg-surface-elevated transition-colors duration-300 inline-block text-center">
                    Return to Home
                </a>
            </div>

            <div class="mt-8 p-4 bg-surface-elevated border border-edge-subtle rounded-xl shadow-inner relative z-10">
                <p class="text-xs text-gold">
                    <strong class="font-bold text-white uppercase tracking-widest text-[10px]">Pro Tip:</strong> Make sure cookies are enabled in your browser and you haven't cleared your cache.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
