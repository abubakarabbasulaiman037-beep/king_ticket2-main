@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-20 animate-fade-in">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-0 overflow-hidden rounded-2xl border border-edge-strong shadow-premium bg-surface-primary">

        <!-- Beautiful Left Side -->
        <div class="hidden lg:flex flex-col justify-between p-16 bg-gradient-to-br from-gold/10 to-amber-600/5 backdrop-blur-sm relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(245,166,35,0.08),transparent_70%)]"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-12">
                    <span class="text-3xl crown-icon">👑</span>
                    <span class="text-lg font-extrabold text-white tracking-wide">KING TICKET</span>
                </div>
                <h1 class="text-5xl font-black text-white mb-8 leading-tight">
                    Create an Account
                </h1>
                <p class="text-gray-300 text-base font-medium leading-relaxed max-w-[300px]">
                    Join King Ticket and start your journey. Book tickets fast, easy, and hassle-free.
                </p>
            </div>

            <div class="relative z-10 space-y-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-gold rounded-full"></div>
                        <p class="text-sm text-gray-300">Instant event discovery</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-gold rounded-full"></div>
                        <p class="text-sm text-gray-300">Secure ticket booking</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-gold rounded-full"></div>
                        <p class="text-sm text-gray-300">Real-time notifications</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="p-12 lg:p-16 relative">
            <div class="mb-12">
                <h2 class="text-3xl font-black text-white mb-3">Create Account</h2>
                <p class="text-gray-400 text-sm font-medium">Join King Ticket and start your journey</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-8">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-semibold text-red-300 mb-1 last:mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-300">Fullname</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input-cyber w-full" placeholder="Your full name" required autofocus>
                    @error('name')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-300">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-cyber w-full" placeholder="you@example.com" required>
                    @error('email')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passwords Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-300">Password</label>
                        <input type="password" name="password" class="input-cyber w-full" placeholder="At least 8 characters" required>
                        @error('password')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-300">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="input-cyber w-full" placeholder="Confirm password" required>
                        @error('password_confirmation')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" name="agree" required class="w-5 h-5 rounded border border-edge-strong bg-surface-card checked:bg-gold checked:border-gold transition-all mt-1 accent-amber-500">
                    <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">
                        I agree to the <a href="#" class="text-gold hover:text-gold-light">Terms & Conditions</a> and <a href="#" class="text-gold hover:text-gold-light">Privacy Policy</a>
                    </span>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="btn-accent w-full py-3.5 font-bold tracking-wider mt-4">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-500 mt-8">
                Already have an account?
                <a href="{{ route('login') }}" class="text-gold hover:text-gold-light font-semibold transition-colors">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
