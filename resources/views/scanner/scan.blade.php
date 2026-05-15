@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-xs font-bold text-gold uppercase tracking-widest mb-2 flex items-center gap-2">
            <span class="w-2 h-2 bg-gold rounded-full"></span>
            Access Control
        </h1>
        <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">QR Scanner</h2>
        <p class="text-gray-400 font-semibold">Scan event tickets for quick verification and check-in</p>
    </div>

    <!-- Scanner Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Camera Section -->
        <div class="lg:col-span-2">
            <div class="bg-surface-primary border border-edge-strong rounded-2xl p-8 shadow-lux-lg relative overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-gold/5 rounded-full blur-3xl pointer-events-none"></div>
                <!-- Controls -->
                <div class="flex flex-wrap items-center gap-4 mb-6 relative z-10">
                    <button id="startCamera" class="btn-accent px-6 py-2.5 flex items-center gap-2 m-0 border-none shadow-lux-sm">
                        <svg class="w-5 h-5 text-surface-base" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                        </svg>
                        Start Camera
                    </button>
                    <button id="stopCamera" class="btn-secondary px-6 py-2.5 flex items-center gap-2 m-0 bg-surface-secondary border border-edge-subtle text-white font-bold hover:bg-surface-elevated transition-colors shadow-lux-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h12v12H6z"></path>
                        </svg>
                        Stop
                    </button>
                    <div class="ml-auto w-full sm:w-auto flex items-center justify-end gap-2 bg-surface-elevated/50 border border-edge-subtle px-4 py-2 rounded-lg">
                        <div class="w-2.5 h-2.5 rounded-full bg-gray-500 animate-pulse"></div>
                        <span id="scanStatus" class="font-bold text-gray-300 text-sm tracking-wide">Idle</span>
                    </div>
                </div>

                <!-- Video Stream -->
                <div class="relative border-2 border-edge-strong rounded-xl overflow-hidden bg-surface-base aspect-video mb-6 shadow-inner relative z-10 group">
                    <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    
                    <!-- Scanner Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:border-gold/30 transition-all border border-transparent">
                        <div class="w-64 h-64 border-2 border-gold/50 rounded-2xl relative shadow-[inset_0_0_40px_rgba(245,166,35,0.1)]">
                            <!-- Corner Accents -->
                            <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-gold rounded-tl-xl border-t-[rgb(245,166,35)] border-l-[rgb(245,166,35)]"></div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-gold rounded-tr-xl border-t-[rgb(245,166,35)] border-r-[rgb(245,166,35)]"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-gold rounded-bl-xl border-b-[rgb(245,166,35)] border-l-[rgb(245,166,35)]"></div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-gold rounded-br-xl border-b-[rgb(245,166,35)] border-r-[rgb(245,166,35)]"></div>
                            
                            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-gold to-transparent opacity-75 scan-line duration-2000"></div>
                        </div>
                    </div>

                    <!-- Info Text -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-surface-base via-surface-base/80 to-transparent p-4 text-center">
                        <p class="text-sm font-semibold text-gray-300">Aim camera at QR code to scan</p>
                    </div>
                </div>

                <p class="text-xs text-gray-500 text-center">
                    Camera will automatically detect and scan QR codes. Results appear instantly.
                </p>
            </div>
        </div>

        <!-- Manual Entry Section -->
        <div class="lg:col-span-1">
            <div class="bg-surface-primary border border-edge-strong rounded-2xl p-8 sticky top-24 shadow-lux-lg">
                <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-gold rounded-full"></span>
                    Manual Entry
                </h3>
                <form action="{{ route('scanner.verify') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest block mb-2">Ticket ID</label>
                        <input type="text" name="code" placeholder="Paste ID here..." class="w-full bg-surface-elevated border border-edge-subtle rounded-lg px-4 py-3 text-white font-semibold focus:border-gold focus:bg-surface-card focus:ring-2 focus:ring-gold/20 transition-all outline-none">
                    </div>

                    <button type="submit" class="btn-accent w-full py-3 m-0 shadow-lux-sm">
                        Verify Ticket
                    </button>
                </form>

                <!-- Features -->
                <div class="mt-8 pt-6 border-t border-edge-subtle">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">System Capabilities</h4>
                    <ul class="text-sm text-gray-400 font-semibold space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-gold rounded-full flex-shrink-0"></span>
                            QR Code scanning
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-gold rounded-full flex-shrink-0"></span>
                            Instant verification
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-gold rounded-full flex-shrink-0"></span>
                            Real-time database sync
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jsQR library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
(function(){
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const status = document.getElementById('scanStatus');
    let stream = null;
    let scanning = false;

    document.getElementById('startCamera').addEventListener('click', async function(){
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                scanning = true;
                status.textContent = 'Scanning...';
                status.parentElement.querySelector('div').classList.remove('bg-gray-500', 'bg-red-500');
                status.parentElement.querySelector('div').classList.add('bg-gold');
                tick();
            } catch (e) {
                alert('Camera access denied or not available.');
                status.textContent = 'Camera error';
            }
        } else {
            alert('Camera not supported in this browser.');
        }
    });

    document.getElementById('stopCamera').addEventListener('click', function(){
        if (stream) {
            stream.getTracks().forEach(t=>t.stop());
            stream = null;
        }
        scanning = false;
        status.textContent = 'Stopped';
        status.parentElement.querySelector('div').classList.remove('bg-gold');
        status.parentElement.querySelector('div').classList.add('bg-gray-500');
    });

    function tick(){
        if (!scanning) return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'attemptBoth' });
            if (code && code.data) {
                scanning = false;
                status.textContent = 'Code detected!';
                const scanned = encodeURIComponent(code.data);
                const url = '{{ route('scanner.verify.get') }}' + '?code=' + scanned;
                window.location.href = url;
                return;
            }
        }
        requestAnimationFrame(tick);
    }
})();
</script>
@endsection
