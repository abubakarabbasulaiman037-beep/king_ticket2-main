<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;

class ScannerController extends Controller
{
    /**
     * Show entry page for public scanner (no login required)
     */
    public function publicEntry()
    {
        return view('scanner.public_entry');
    }

    /**
     * Validate scanner code and show public scanner
     */
    public function validateCode(Request $request)
    {
        try {
            // Validate scanner code format
            $validated = $request->validate([
                'scanner_code' => 'required|string|min:12|max:12'
            ]);

            // Convert to uppercase for consistency
            $code = strtoupper($validated['scanner_code']);

            // Find event by scanner code
            $event = Event::where('scanner_code', $code)
                ->where('scanner_enabled', true)
                ->first();

            if (!$event) {
                return back()->withErrors([
                    'scanner_code' => 'Invalid or inactive scanner code. Please check and try again.'
                ])->withInput();
            }

            // Store validated code in session for verification later
            $request->session()->put([
                'scanner_code' => $event->scanner_code,
                'event_id' => $event->id,
                'scanner_validated_at' => now(),
            ]);

            return redirect()->route('scanner.public.camera', $event->id);
        } catch (\Exception $e) {
            \Log::error('Scanner validation error', [
                'error' => $e->getMessage(),
                'code' => $request->input('scanner_code')
            ]);
            
            return back()->withErrors([
                'scanner_code' => 'An error occurred. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Public camera page (after code validation)
     */
    public function publicCamera(Request $request, $eventId)
    {
        // Check session for valid scanner code
        if (session('event_id') !== (int)$eventId) {
            return redirect()->route('scanner.public.entry')->withErrors(['scanner_code' => 'Invalid session. Please enter code again.']);
        }

        $event = Event::findOrFail($eventId);

        return view('scanner.public_camera', compact('event'));
    }

    /**
     * Verify ticket via public scanner (after code validation)
     */
    public function verifyPublic(Request $request, $eventId)
    {
        // Check session for valid scanner code
        if (session('event_id') !== (int)$eventId) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Session expired. Please enter code again.'], 401);
            }
            return back()->withErrors(['code' => 'Session expired. Please enter code again.']);
        }

        $code = $request->query('code') ?? $request->input('code') ?? $request->input('ticket_code');
        if (!$code) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'No code supplied.'], 400);
            }
            return back()->withErrors(['code' => 'No code supplied.']);
        }

        $event = Event::findOrFail($eventId);

        // Extract id from URL or plain id
        if (preg_match('/ticket\/(\d+)/', $code, $m)) {
            $id = $m[1];
        } else {
            $id = intval($code);
        }

        $ticket = Ticket::with('user', 'event')->find($id);
        if (!$ticket || $ticket->event_id != $event->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket not found for this event.',
                    'detail' => 'The QR code does not match any ticket for this event.'
                ], 404);
            }
            return view('scanner.result', ['status' => 'invalid', 'message' => 'Ticket not found for this event.']);
        }

        if ($ticket->used) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket already scanned.',
                    'detail' => 'This ticket has already been checked in.',
                    'ticket' => ['id' => $ticket->id, 'ticket_code' => $ticket->ticket_code]
                ], 409);
            }
            return view('scanner.result', ['status' => 'used', 'ticket' => $ticket, 'message' => 'Ticket already used.']);
        }

        $ticket->update(['used' => true]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ticket verified successfully!',
                'event' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'banner' => $event->banner
                ],
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'attendee_name' => $ticket->user->name ?? 'N/A',
                    'attendee_email' => $ticket->user->email ?? 'N/A',
                    'seat' => $ticket->seat_number ?? 'General Admission'
                ]
            ]);
        }

        return view('scanner.result', [
            'status' => 'valid',
            'ticket' => $ticket,
            'message' => 'Ticket is valid and marked used.',
            'buyer_name' => $ticket->user->name ?? 'N/A',
            'ticket_id' => $ticket->id,
            'seat_number' => $ticket->seat_number ?? 'N/A',
        ]);
    }

    public function index()
    {
        return view('scanner.scan');
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
        ]);

        // Expecting either ticket id or full URL containing ticket id
        $code = $data['code'];

        // Try to extract id from URL
        if (preg_match('/ticket\/(\d+)/', $code, $m)) {
            $id = $m[1];
        } else {
            $id = intval($code);
        }

        $ticket = Ticket::with('user', 'event')->find($id);
        if (!$ticket) {
            return view('scanner.result', ['status' => 'invalid', 'message' => 'Ticket not found.']);
        }

        if ($ticket->used) {
            return view('scanner.result', ['status' => 'used', 'ticket' => $ticket, 'message' => 'Ticket already used.']);
        }

        $ticket->update(['used' => true]);

        return view('scanner.result', [
            'status' => 'valid',
            'ticket' => $ticket,
            'message' => 'Ticket is valid and marked used.',
            'buyer_name' => $ticket->user->name ?? 'N/A',
            'ticket_id' => $ticket->id,
            'seat_number' => $ticket->seat_number ?? 'N/A',
        ]);
    }

    // Public facing scan via token on event
    public function showByToken($token)
    {
        $event = Event::where('scan_token', $token)->first();
        if (!$event) {
            abort(404);
        }

        // Show a public page for scanning attendees — includes instructions and link to buy ticket
        return view('scanner.token', compact('event'));
    }

    // New: verify by code via GET for camera redirects
    public function verifyByCode(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return redirect()->route('scanner.index')->with('error', 'No code supplied.');
        }

        // Reuse same extraction logic
        if (preg_match('/ticket\/(\d+)/', $code, $m)) {
            $id = $m[1];
        } else {
            $id = intval($code);
        }

        $ticket = Ticket::with('user', 'event')->find($id);
        if (!$ticket) {
            return view('scanner.result', ['status' => 'invalid', 'message' => 'Ticket not found.']);
        }

        if ($ticket->used) {
            return view('scanner.result', ['status' => 'used', 'ticket' => $ticket, 'message' => 'Ticket already used.']);
        }

        $ticket->update(['used' => true]);

        return view('scanner.result', [
            'status' => 'valid',
            'ticket' => $ticket,
            'message' => 'Ticket is valid and marked used.',
            'buyer_name' => $ticket->user->name ?? 'N/A',
            'ticket_id' => $ticket->id,
            'seat_number' => $ticket->seat_number ?? 'N/A',
        ]);
    }

    // Verify a scanned code but restrict to tickets belonging to the event identified by token
    public function verifyByToken(Request $request, $token)
    {
        $code = $request->query('code') ?? $request->input('code');
        if (!$code) {
            return redirect()->route('scan.show', $token)->with('error', 'No code supplied.');
        }

        $event = Event::where('scan_token', $token)->first();
        if (!$event) {
            abort(404);
        }

        // extract id from URL or plain id
        if (preg_match('/ticket\/(\d+)/', $code, $m)) {
            $id = $m[1];
        } else {
            $id = intval($code);
        }

        $ticket = Ticket::with('user', 'event')->find($id);
        if (!$ticket || $ticket->event_id != $event->id) {
            return view('scanner.result', ['status' => 'invalid', 'message' => 'Ticket not found for this event.']);
        }

        if ($ticket->used) {
            return view('scanner.result', ['status' => 'used', 'ticket' => $ticket, 'message' => 'Ticket already used.']);
        }

        $ticket->update(['used' => true]);

        return view('scanner.result', [
            'status' => 'valid',
            'ticket' => $ticket,
            'message' => 'Ticket is valid and marked used.',
            'buyer_name' => $ticket->user->name ?? 'N/A',
            'ticket_id' => $ticket->id,
            'seat_number' => $ticket->seat_number ?? 'N/A',
        ]);
    }
}
