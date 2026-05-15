@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-surface-primary border border-edge-strong rounded-2xl shadow-lux-xl p-8 relative overflow-hidden">
            <h1 class="text-3xl font-black text-white mb-6 uppercase tracking-widest relative z-10">Scanner Test</h1>
            
            <div class="mb-6 p-4 bg-surface-elevated border border-edge-subtle rounded-xl shadow-inner relative z-10">
                <p class="text-[10px] uppercase font-bold tracking-widest text-gold mb-2">Testing Protocol Submit</p>
                <p class="text-xs text-gray-400 font-semibold">System routing nominal ✓</p>
            </div>

            <form method="POST" action="{{ route('scanner.validate.code') }}" class="space-y-4 relative z-10">
                @csrf
                
                <input type="text" name="scanner_code" placeholder="Enter Scanner Code" class="input-cyber w-full focus:border-gold focus:ring-gold/20" maxlength="12" required>
                
                <button type="submit" class="btn-accent w-full py-3 m-0 shadow-lux-sm">
                    Execute Test (with Secure Token)
                </button>
            </form>

            <hr class="border-edge-subtle my-6 relative z-10">

            <form method="POST" action="{{ route('scanner.validate.code') }}" class="space-y-4 relative z-10">
                <!-- No CSRF on this form -->
                <input type="text" name="scanner_code" placeholder="Enter Scanner Code (no CSRF)" class="input-cyber w-full focus:border-gold focus:ring-gold/20" maxlength="12" required>
                
                <button type="submit" class="w-full py-3 rounded-lg border border-edge-strong text-white font-bold uppercase tracking-wider text-sm hover:bg-surface-elevated transition-colors">
                    Execute Test (no Secure Token)
                </button>
            </form>

            <div class="mt-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl relative z-10">
                <p class="text-[10px] uppercase font-bold tracking-widest text-green-400">Connection Status</p>
                <p class="text-xs text-gray-400 mt-2 font-mono">
                    • GET /scanner/entry - <span class="text-green-500">✓</span><br>
                    • POST /scanner/validate-code - <span class="text-green-500">✓</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
