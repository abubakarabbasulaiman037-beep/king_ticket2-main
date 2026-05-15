@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-surface-base">
    <!-- Header Bar -->
    <div class="sticky top-0 z-50 bg-surface-primary/80 backdrop-blur-xl border-b border-edge-subtle shadow-lux-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="p-2 rounded-lg hover:bg-surface-elevated transition-all text-gold hover:text-gold-light">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-white font-bold">{{ $event->title }}</h1>
                    <p class="text-xs text-gray-500">{{ $event->date?->format('M d, Y') ?? 'TBA' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Scanner Status</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                        <span class="text-sm font-bold text-green-400">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 flex gap-8">
        <!-- Camera Section -->
        <div class="flex-1">
            <div class="bg-surface-primary border border-edge-strong rounded-2xl p-2 shadow-lux-xl relative overflow-hidden group">
                <!-- QR Scanner Container -->
                <div id="scanner-container" class="relative bg-surface-base w-full aspect-square rounded-xl overflow-hidden flex items-center justify-center border border-edge-subtle">
                    <video id="qr-scanner" class="w-full h-full object-cover" autoplay></video>
                    
                    <!-- Scanner Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="relative w-80 h-80">
                            <!-- Corner Markers -->
                            <div class="absolute top-0 left-0 w-12 h-12 border-t-4 border-l-4 border-gold rounded-tl-xl shadow-[0_0_15px_rgba(245,166,35,0.3)]"></div>
                            <div class="absolute top-0 right-0 w-12 h-12 border-t-4 border-r-4 border-gold rounded-tr-xl shadow-[0_0_15px_rgba(245,166,35,0.3)]"></div>
                            <div class="absolute bottom-0 left-0 w-12 h-12 border-b-4 border-l-4 border-gold rounded-bl-xl shadow-[0_0_15px_rgba(245,166,35,0.3)]"></div>
                            <div class="absolute bottom-0 right-0 w-12 h-12 border-b-4 border-r-4 border-gold rounded-br-xl shadow-[0_0_15px_rgba(245,166,35,0.3)]"></div>
                            
                            <!-- Center Dot -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <div class="w-2 h-2 bg-gold rounded-full shadow-[0_0_15px_rgba(245,166,35,0.8)] animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="scanner-loading" class="absolute inset-0 bg-surface-base/90 backdrop-blur-sm flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gold/10 border border-gold/30 flex items-center justify-center animate-pulse">
                                <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <p class="text-gold font-bold uppercase tracking-widest text-xs">Initializing Optics...</p>
                        </div>
                    </div>
                </div>

                <!-- Camera Controls -->
                <div class="bg-surface-elevated/50 p-6 mt-2 rounded-xl border border-edge-subtle">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></div>
                            <p id="scanner-status" class="text-xs font-bold text-gray-300 uppercase tracking-widest">Active • Ready for Scan</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="start-button" type="button" class="btn-accent px-4 py-2 m-0 text-xs hidden shadow-lux-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                Start
                            </button>
                            <button id="stop-button" type="button" class="btn-secondary px-4 py-2 m-0 text-xs shadow-lux-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 4h12v12H6z"/>
                                </svg>
                                Pause
                            </button>
                            <button id="torch-button" type="button" class="bg-surface-card hover:bg-surface-secondary border border-edge-subtle p-2.5 rounded-lg transition-all text-gold shadow-lux-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Entry Fallback -->
            <div class="mt-6 bg-surface-primary border border-edge-strong p-6 rounded-2xl shadow-lux-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Manual Override Config</p>
                <form method="POST" action="{{ route('scanner.verify.public', $event->id) }}" class="flex gap-4">
                    @csrf
                    <input type="text" name="ticket_code" placeholder="Enter ticket ID explicitly..." class="input-cyber flex-1 focus:border-gold focus:ring-gold/20" />
                    <button type="submit" class="btn-accent px-6 py-0 m-0 text-sm whitespace-nowrap shadow-lux-sm">Execute</button>
                </form>
            </div>
        </div>

        <!-- Results Panel -->
        <div class="w-96 flex flex-col gap-6">
            <!-- Stats Card -->
            <div class="bg-surface-primary border border-edge-strong rounded-2xl p-6 shadow-lux-md">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-surface-elevated border border-edge-subtle p-4 rounded-xl text-center shadow-inner relative overflow-hidden">
                        <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gold/10 rounded-full blur-xl"></div>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1 relative z-10">Scanned</p>
                        <p id="scan-count" class="text-4xl font-black text-gold relative z-10">0</p>
                    </div>
                    <div class="bg-surface-elevated border border-edge-subtle p-4 rounded-xl text-center shadow-inner relative overflow-hidden">
                        <div class="absolute -top-4 -left-4 w-16 h-16 bg-white/5 rounded-full blur-xl"></div>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1 relative z-10">Total Valid</p>
                        <p class="text-4xl font-black text-white relative z-10">{{ $event->tickets->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Last Scan Result -->
            <div class="sticky top-28 flex-1">
                <div id="result-placeholder" class="bg-surface-primary border border-edge-strong rounded-2xl p-8 shadow-lux-md h-full flex flex-col justify-center items-center text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-surface-elevated border border-edge-subtle flex items-center justify-center shadow-inner">
                        <span class="text-4xl font-black text-gold/30">👑</span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Awaiting Target</h3>
                    <p class="text-xs text-gray-600 font-semibold">Position QR code within the frame to verify identity.</p>
                </div>

                <div id="result-container" class="hidden">
                    <!-- Success Result -->
                    <div id="result-success" class="hidden shadow-lux-xl rounded-2xl overflow-hidden">
                        <!-- Event Banner Section -->
                        <div class="h-32 bg-surface-elevated relative border-b border-edge-subtle">
                            <img id="result-banner" src="" alt="Event Banner" class="w-full h-full object-cover opacity-60 mix-blend-luminosity" onload="this.style.display='block'" onerror="this.style.display='none'">
                            <div class="absolute inset-0 bg-gradient-to-t from-surface-primary to-transparent"></div>
                            <div class="absolute top-4 right-4 bg-green-500/20 text-green-400 border border-green-500/50 px-3 py-1 rounded-full flex items-center gap-2 backdrop-blur-md">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest">Verified</span>
                            </div>
                        </div>

                        <!-- Ticket Card Content -->
                        <div class="bg-surface-primary p-8 space-y-6">
                            <!-- Attendee Info -->
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-[0.2em]" id="result-event-name"></span>
                                <p id="result-name" class="text-2xl font-black text-white leading-tight mt-1"></p>
                                <p id="result-email" class="text-xs text-gray-400 font-semibold"></p>
                            </div>

                            <!-- Divider -->
                            <div class="h-px bg-edge-subtle w-full"></div>

                            <!-- Ticket Details Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Ticket Code -->
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Identifier</p>
                                    <p id="result-ticket-id" class="font-mono text-lg font-black text-gold break-all"></p>
                                </div>

                                <!-- Status -->
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Protocol</p>
                                    <p class="font-black text-green-400 text-lg uppercase tracking-wide">Granted</p>
                                </div>

                                <!-- Seat/Type -->
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Clearance</p>
                                    <p id="result-seat" class="font-bold text-gray-300">Standard</p>
                                </div>

                                <!-- Time Scanned -->
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Timestamp</p>
                                    <p id="result-time" class="font-bold text-gray-300 text-sm">Just now</p>
                                </div>
                            </div>

                            <!-- Bottom Action Bar -->
                            <div class="flex items-center gap-4 pt-6 border-t border-edge-subtle">
                                <div class="flex-1">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Queue ID</p>
                                    <p id="result-scan-number" class="text-sm font-black text-gold">#1</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Result -->
                    <div id="result-error" class="hidden shadow-lux-xl rounded-2xl overflow-hidden">
                        <!-- Error Header -->
                        <div class="h-28 bg-gradient-to-br from-red-900/40 to-surface-primary border-b border-red-500/30 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                                <span class="text-2xl">🚫</span>
                            </div>
                        </div>

                        <!-- Error Content -->
                        <div class="bg-surface-primary p-8 space-y-6">
                            <div class="space-y-2 text-center">
                                <p id="result-error-message" class="text-xl font-black text-red-400 uppercase tracking-wide"></p>
                                <p id="result-error-detail" class="text-xs font-bold text-gray-400"></p>
                            </div>

                            <!-- Error Tag -->
                            <div class="flex items-center justify-center pt-6 border-t border-edge-subtle">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center">Protocol Rejected • Identify Verification Failed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner JS Library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
    const video = document.getElementById('qr-scanner');
    const resultContainer = document.getElementById('result-container');
    const resultPlaceholder = document.getElementById('result-placeholder');
    const torchButton = document.getElementById('torch-button');
    const startButton = document.getElementById('start-button');
    const stopButton = document.getElementById('stop-button');
    const scannerStatus = document.getElementById('scanner-status');
    const scanCount = document.getElementById('scan-count');
    let scannedCount = 0;
    let isScanning = true;
    let stream = null;

    // Initialize camera
    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = stream;
            document.getElementById('scanner-loading').style.display = 'none';
            scanQRCode();
        } catch (err) {
            console.error('Camera error:', err);
            alert('Unable to access camera. Please check permissions.');
        }
    }

    // Stop camera
    function stopCamera() {
        isScanning = false;
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        video.srcObject = null;
        scannerStatus.textContent = 'Scanner Stopped';
        scannerStatus.classList.add('text-red-400');
        scannerStatus.classList.remove('text-gray-300');
        startButton.classList.remove('hidden');
        stopButton.classList.add('hidden');
    }

    // Start/Resume camera
    function startCamera() {
        initCamera();
        scannerStatus.textContent = 'Camera Active - Position QR Code in Frame';
        scannerStatus.classList.remove('text-red-400');
        scannerStatus.classList.add('text-gray-300');
        isScanning = true;
        startButton.classList.add('hidden');
        stopButton.classList.remove('hidden');
    }

    // QR Code scanning loop
    function scanQRCode() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        const scan = () => {
            if (!isScanning) return;
            
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    handleQRCode(code.data);
                    isScanning = false;
                    setTimeout(() => { isScanning = true; }, 2000);
                }
            }
            requestAnimationFrame(scan);
        };
        scan();
    }

    // Handle QR code result
    function handleQRCode(qrData) {
        scannerStatus.textContent = `Scanning...`;
        
        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('{{ route("scanner.verify.public.post", $event->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ticket_code: qrData })
        })
        .then(r => {
            if (r.status === 419) {
                alert('Session expired. Please restart the scanner.');
                window.location.href = '{{ route("scanner.public.entry") }}';
                return null;
            }
            return r.json();
        })
        .then(data => {
            if (data) {
                displayResult(data);
            }
            scannerStatus.textContent = `Camera Active - Position QR Code in Frame`;
        })
        .catch(err => {
            console.error('Scan error:', err);
            scannerStatus.textContent = `Scanner Error - Try Again`;
        });
    }

    // Display result
    function displayResult(data) {
        resultPlaceholder.style.display = 'none';
        resultContainer.style.display = 'block';
        
        if (data.status === 'success') {
            document.getElementById('result-error').style.display = 'none';
            document.getElementById('result-success').style.display = 'block';
            
            // Attendee info
            document.getElementById('result-name').textContent = data.ticket.attendee_name || 'Unknown Attendee';
            document.getElementById('result-email').textContent = data.ticket.attendee_email || 'No email';
            document.getElementById('result-ticket-id').textContent = (data.ticket.ticket_code || data.ticket.id || 'N/A').toUpperCase();
            
            // Event banner
            const bannerImg = document.getElementById('result-banner');
            if (data.event && data.event.banner) {
                bannerImg.src = `/storage/banners/${data.event.banner}`;
            }
            
            // Event name
            document.getElementById('result-event-name').textContent = data.event?.title || 'Event';
            
            // Seat info
            document.getElementById('result-seat').textContent = data.ticket.seat || 'General Admission';
            
            // Time
            const now = new Date();
            document.getElementById('result-time').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            
            // Scan count
            scannedCount++;
            scanCount.textContent = scannedCount;
            document.getElementById('result-scan-number').textContent = `#${scannedCount}`;
        } else {
            document.getElementById('result-success').style.display = 'none';
            document.getElementById('result-error').style.display = 'block';
            document.getElementById('result-error-message').textContent = data.message || 'Scan Failed';
            document.getElementById('result-error-detail').textContent = data.detail || 'Please try scanning again';
        }
    }

    // Torch control
    torchButton.addEventListener('click', async () => {
        try {
            const stream = video.srcObject;
            const track = stream.getVideoTracks()[0];
            const settings = track.getSettings();
            
            if (settings.torch !== undefined) {
                await track.applyConstraints({
                    advanced: [{ torch: !settings.torch }]
                });
                torchButton.classList.toggle('bg-gray-600');
                torchButton.classList.toggle('bg-yellow-600');
            }
        } catch (e) {
            console.log('Torch not supported');
        }
    });

    // Start button event listener
    startButton.addEventListener('click', startCamera);

    // Stop button event listener
    stopButton.addEventListener('click', stopCamera);

    // Initialize on load
    document.addEventListener('DOMContentLoaded', initCamera);
</script>
@endsection
