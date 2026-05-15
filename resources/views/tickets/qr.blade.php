@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
    <div class="md:flex md:items-center">
        <div class="md:w-1/2">
            <h2 class="text-2xl font-bold">{{ $ticket->event->title }}</h2>
            <p class="text-sm text-gray-500">{{ $ticket->event->date->format('M d, Y H:i') }} &middot; {{ $ticket->event->location }}</p>
            <p class="mt-4 text-gray-700">Ticket ID: <span class="font-medium">{{ $ticket->id }}</span></p>
            <p class="mt-2">Status: @if($ticket->used) <span class="text-red-500">Used</span> @else <span class="text-green-600">Valid</span> @endif</p>
        </div>
        <div class="md:w-1/2 flex justify-center mt-4 md:mt-0">
            <div class="bg-white p-4 rounded-lg border text-center">
                @php $qrPath = '/storage/qrcodes/' . ($ticket->qr_code ?? ''); @endphp
                <img src="{{ $qrPath }}" alt="QR" class="w-56 h-56 object-contain mx-auto" />
                <div class="mt-4">
                    <a href="{{ $qrPath }}" download="ticket-{{ $ticket->id }}.png" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Download Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
