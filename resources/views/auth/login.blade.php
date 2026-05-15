@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 animate-fade-in py-12">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center mb-6">
                <span class="text-4xl crown-icon">👑</span>
            </div>
            <span class="text-sm font-bold text-gold uppercase tracking-wider block mb-2">KING TICKET</span>
            <h1 class="text-4xl font-black text-white mb-3">Welcome Back 👑</h1>
            <p class="text-gray-400 text-sm font-medium">Login to continue your journey</p>
        </div>

        <!-- Login Card -->
        <div class="card-cyber p-8 mb-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-semibold text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-300">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-cyber w-full" placeholder="you@example.com" required autofocus>
                    @error('email')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-300">Password</label>
                        <a href="#" class="text-gold text-xs hover:text-gold-light transition-colors font-semibold">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" class="input-cyber w-full" placeholder="••••••••" required>
                    @error('password')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <label class="flex items-center gap-2 text-gray-400 hover:text-gray-300 cursor-pointer transition text-sm">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border border-edge-strong bg-surface-card checked:bg-gold checked:border-gold transition-all cursor-pointer accent-amber-500">
                    <span>Remember Me</span>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="btn-accent w-full py-3.5 font-bold tracking-wider">
                    Login
                </button>
            </form>
        </div>

        <!-- Divider -->
        <div class="relative mb-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-edge-subtle"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-3 bg-surface-base text-gray-500 font-semibold">Or continue with</span>
            </div>
        </div>

        <!-- Social Login -->
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('auth.google') }}" class="card-cyber p-3 flex items-center justify-center gap-2 hover:border-gold/30 transition-all group">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="font-semibold text-gray-300 group-hover:text-white transition text-sm">Google</span>
            </a>
            <a href="#" class="card-cyber p-3 flex items-center justify-center gap-2 hover:border-gold/30 transition-all group">
                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                <span class="font-semibold text-gray-300 group-hover:text-white transition text-sm">Apple</span>
            </a>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-sm text-gray-500 mt-8">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-gold hover:text-gold-light font-semibold transition-colors">Register</a>
        </p>
    </div>
</div>
@endsection
