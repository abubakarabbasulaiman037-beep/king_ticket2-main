@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold">{{ $event->title }} — Scan & Check-in</h2>
    <p class="text-sm text-gray-500">Use this page to verify tickets for the event. You can paste the ticket URL or ID from an attendee's phone into the scanner, or use the admin scanner if logged in.</p>

    <div class="mt-4">
        <a href="{{ route('events.show.public', $event) }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Event Page</a>
        @auth
            <a href="{{ route('scanner.index') }}" class="ml-2 text-sm text-indigo-600">Open Scanner (admin)</a>
        @endauth
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded">
        <div><strong>Available Tickets:</strong> {{ $event->available_tickets }}</div>
        <div class="mt-2"><strong>Scan URL:</strong> <a href="{{ $event->scanUrl() }}" class="underline text-indigo-600">{{ $event->scanUrl() }}</a></div>
    </div>
</div>
@endsection
