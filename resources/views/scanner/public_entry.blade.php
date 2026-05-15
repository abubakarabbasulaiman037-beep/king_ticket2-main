@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#FCFCFD] font-sans pb-24">
    <!-- ACCESS HERO SECTION -->
    <section class="relative pt-32 pb-24 overflow-hidden max-w-[1280px] mx-auto px-6 border-b border-gray-100">
        <!-- Glow accents -->
        <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-[#CBA469]/5 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
            
            <!-- Left Side: Copy and Access Form -->
            <div class="flex flex-col items-start px-2">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200 mb-8">
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-gray-600 tracking-wider uppercase">Enterprise Security</span>
                </div>
                
                <h1 class="text-5xl md:text-[64px] leading-[1.1] font-serif text-black mb-6">
                    Secure Event <br> <span class="text-[#CBA469] italic">Access.</span>
                </h1>
                <p class="text-[16px] text-gray-500 mb-10 max-w-md leading-relaxed">
                    Unlock professional QR ticket scanning for seamless and secure attendee verification.
                </p>
                
                <!-- Errors -->
                @if($errors->any())
                    <div class="w-full max-w-md bg-red-50 border border-red-100 text-red-600 text-[13px] font-medium p-4 rounded-xl mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif
                
                <!-- Access Form Card -->
                <div class="w-full max-w-md bg-white p-6 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.04)] border border-gray-100 relative">
                    <div class="absolute -top-5 -right-5 w-14 h-14 bg-white rounded-full shadow-lg border border-gray-100 flex items-center justify-center animate-bounce duration-3000">
                        <svg class="w-6 h-6 text-[#CBA469]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>

                    <form action="{{ route('scanner.validate.code') }}" method="POST" class="flex flex-col gap-5">
                        @csrf
                        <div>
                            <label class="block text-[12px] font-bold text-gray-800 mb-3 uppercase tracking-wider">Access Node</label>
                            <input 
                                type="text" 
                                name="scanner_code" 
                                required
                                placeholder="Enter 12-character scanner code" 
                                value="{{ old('scanner_code') }}"
                                class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-[15px] font-mono tracking-widest text-center focus:outline-none focus:ring-2 focus:ring-[#CBA469]/20 focus:border-[#CBA469] transition-all uppercase placeholder-gray-400 placeholder:normal-case placeholder:tracking-normal placeholder:font-sans"
                            >
                        </div>
                        <button type="submit" class="w-full px-8 py-4 bg-black text-white rounded-2xl font-bold text-[15px] shadow-lg shadow-black/10 hover:shadow-xl hover:shadow-black/20 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group">
                            Unlock Scanner
                            <svg class="w-4 h-4 opacity-70 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Right Side: Device Illustration -->
            <div class="relative z-0 hidden lg:flex justify-center items-center h-[600px]">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-[#FCFCFD] to-[#CBA469]/10 blur-[80px] rounded-full -z-10"></div>
                
                <img src="{{ asset('images/scanner_illustration.png') }}" class="w-full max-w-[450px] object-contain drop-shadow-[0_40px_60px_rgba(0,0,0,0.1)] hover:-translate-y-4 hover:scale-105 transition-all duration-1000 mix-blend-multiply" alt="Premium Scanner Mockup">
                
                <!-- Floating Verification Cards -->
                <div class="absolute top-20 right-0 bg-white/90 backdrop-blur-xl p-4 rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-white flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Scanned</p>
                        <p class="text-[14px] font-bold text-black border-b border-dashed border-gray-300">Ticket Valid</p>
                    </div>
                </div>

                <div class="absolute bottom-20 left-0 bg-white/90 backdrop-blur-xl p-4 rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-white flex items-center gap-4 animate-fade-in-up" style="animation-delay: 1.5s;">
                    <div class="w-10 h-10 bg-[#CBA469]/10 rounded-full flex items-center justify-center text-[#CBA469]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Encrypt</p>
                        <p class="text-[14px] font-bold text-black border-b border-dashed border-gray-300">End-to-End</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST & SECURITY SECTION -->
    <section class="max-w-[1280px] mx-auto px-6 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-black font-serif mb-3">Enterprise Grade Validation</h2>
            <p class="text-[15px] text-gray-500 max-w-xl mx-auto">Protect your events with our military-grade security infrastructure ensuring zero ticket duplication.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-gray-100 rounded-3xl p-8 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all hover:-translate-y-1">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-800 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-black mb-2">Encrypted Verification</h3>
                <p class="text-[13px] text-gray-500 leading-relaxed">Each ticket contains unique encrypted hashes preventing any unauthorized tampering.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-8 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all hover:-translate-y-1">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-800 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-black mb-2">Secure Validation</h3>
                <p class="text-[13px] text-gray-500 leading-relaxed">Offline and online sync guaranteeing smooth validation even in high-density areas.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-8 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all hover:-translate-y-1">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-800 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-black mb-2">Fraud Protection</h3>
                <p class="text-[13px] text-gray-500 leading-relaxed">Automatic duplicate ticket rejection and instant organizer alerting mechanisms.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-8 hover:shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all hover:-translate-y-1">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-800 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-black mb-2">Real-time Sync</h3>
                <p class="text-[13px] text-gray-500 leading-relaxed">Multi-device scanning capabilities update attendance records simultaneously.</p>
            </div>
        </div>
    </section>

    <!-- PROTOCOL TIMELINE SECTION -->
    <section class="max-w-[1280px] mx-auto px-6 mb-24">
        <div class="bg-white border border-gray-100 rounded-[2rem] p-10 md:p-16 shadow-[0_10px_40px_rgba(0,0,0,0.02)]">
            <h2 class="text-2xl font-bold text-black font-serif mb-12 text-center md:text-left">Protocol Sequence</h2>
            
            <div class="relative">
                <!-- Desktop Connection Line -->
                <div class="hidden md:block absolute top-10 left-0 w-full h-[2px] bg-gray-50"></div>
                <div class="hidden md:block absolute top-10 left-0 w-1/3 h-[2px] bg-gradient-to-r from-[#CBA469] to-gray-50"></div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 text-center md:text-left relative">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="w-20 h-20 mx-auto md:mx-0 bg-white border-2 border-[#CBA469] rounded-2xl flex items-center justify-center text-[#CBA469] text-xl font-bold mb-6 shadow-sm z-10 relative">
                            01
                        </div>
                        <h4 class="text-[16px] font-bold text-black mb-2">Receive Access</h4>
                        <p class="text-[13px] text-gray-500 leading-relaxed">Event creator generates a secure 12-character scanner code.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="w-20 h-20 mx-auto md:mx-0 bg-white border-2 border-gray-100 rounded-2xl flex items-center justify-center text-gray-300 text-xl font-bold mb-6 shadow-sm z-10 relative">
                            02
                        </div>
                        <h4 class="text-[16px] font-bold text-black mb-2">Unlock Scanner</h4>
                        <p class="text-[13px] text-gray-500 leading-relaxed">Enter the scanner code into this terminal to authenticate session.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="w-20 h-20 mx-auto md:mx-0 bg-white border-2 border-gray-100 rounded-2xl flex items-center justify-center text-gray-300 text-xl font-bold mb-6 shadow-sm z-10 relative">
                            03
                        </div>
                        <h4 class="text-[16px] font-bold text-black mb-2">Scan Tickets</h4>
                        <p class="text-[13px] text-gray-500 leading-relaxed">Present attendees' digital QR codes to the device camera.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative">
                        <div class="w-20 h-20 mx-auto md:mx-0 bg-white border-2 border-gray-100 rounded-2xl flex items-center justify-center text-gray-300 text-xl font-bold mb-6 shadow-sm z-10 relative">
                            04
                        </div>
                        <h4 class="text-[16px] font-bold text-black mb-2">Verify Instantly</h4>
                        <p class="text-[13px] text-gray-500 leading-relaxed">Instant visual and auditory feedback marks the ticket as used.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .duration-3000 {
        animation-duration: 3s;
    }
</style>
@endsection
