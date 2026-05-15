<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Overall statistics
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_organizers' => Event::select('user_id')->distinct()->count(),
            'total_events' => Event::count(),
            'total_tickets_sold' => Ticket::whereHas('payment', function($q) {
                $q->where('status', 'completed');
            })->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'platform_earnings' => Payment::where('status', 'completed')->sum('amount') * 0.05,
            'pending_payouts' => Payout::whereIn('status', ['pending', 'retrying'])->sum('amount'),
            'completed_payouts' => Payout::where('status', 'completed')->sum('amount'),
        ];

        // Recent events
        $recent_events = Event::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent payments
        $recent_payments = Payment::with('event', 'buyer')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent payouts
        $recent_payouts = Payout::with('user', 'event')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_events', 'recent_payments', 'recent_payouts'));
    }

    /**
     * List all users
     */
    public function users()
    {
        $users = User::where('is_admin', false)
            ->withCount('events')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }

        $user->load('events', 'events.payments');
        $events_count = $user->events()->count();
        $tickets_sold = Ticket::whereHas('payment', function($q) {
            $q->where('status', 'completed');
        })->whereHas('event', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $total_revenue = Payment::whereHas('event', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'completed')->sum('amount');

        return view('admin.user-details', compact('user', 'events_count', 'tickets_sold', 'total_revenue'));
    }

    /**
     * List all events
     */
    public function events()
    {
        $events = Event::with('user')
            ->withCount('tickets', 'payments')
            ->paginate(20);

        return view('admin.events', compact('events'));
    }

    /**
     * Show event details
     */
    public function showEvent(Event $event)
    {
        $event->load('user', 'tickets', 'payments');
        $ticket_count = $event->tickets()->count();
        $sold_tickets = $event->tickets()->whereHas('payment', function($q) {
            $q->where('status', 'completed');
        })->count();
        $total_revenue = $event->payments()->where('status', 'completed')->sum('amount');

        return view('admin.event-details', compact('event', 'ticket_count', 'sold_tickets', 'total_revenue'));
    }

    /**
     * List all payments
     */
    public function payments()
    {
        $payments = Payment::with('event', 'buyer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments', compact('payments'));
    }

    /**
     * List all payouts
     */
    public function payouts()
    {
        $payouts = Payout::with('user', 'event')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_paid' => Payout::where('status', 'completed')->sum('amount'),
            'total_pending' => Payout::whereIn('status', ['pending', 'retrying'])->sum('amount'),
            'total_failed' => Payout::where('status', 'failed')->sum('amount'),
        ];

        return view('admin.payouts', compact('payouts', 'stats'));
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Cannot delete admin users');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    /**
     * Delete an event
     */
    public function deleteEvent(Event $event)
    {
        if ($event->tickets()->whereHas('payment', function($q) {
            $q->where('status', 'completed');
        })->exists()) {
            return redirect()->back()->with('error', 'Cannot delete events with sold tickets');
        }

        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully');
    }

    /**
     * Show create event form
     */
    public function createEvent()
    {
        $organizers = User::where('is_admin', false)->get();
        return view('admin.create-event', compact('organizers'));
    }

    /**
     * Store a new event
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d|after:today',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
        ]);

        // Combine date and time
        $dateTime = $validated['date'] . ' ' . $validated['time'];
        unset($validated['date'], $validated['time']);
        
        $validated['name'] = $validated['title'];
        $validated['date'] = $dateTime;
        $validated['currency'] = 'NGN';
        $validated['scanner_code'] = strtoupper(substr(md5(uniqid() . time()), 0, 12));

        Event::create($validated);

        return redirect()->route('admin.events')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show event generation form
     */
    public function generateEventForm()
    {
        $organizers = User::where('is_admin', false)->get();
        return view('admin.generate-event', compact('organizers'));
    }

    /**
     * Generate event from description using AI
     */
    public function generateEvent(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:1000',
        ]);

        // Simple AI-like event generation from description
        $description = $validated['description'];
        
        // Extract key information from description
        $eventData = $this->parseEventDescription($description);
        $eventData['user_id'] = $validated['user_id'];

        // Validate extracted data
        if (empty($eventData['title']) || empty($eventData['location'])) {
            return back()->withErrors([
                'description' => 'Could not extract enough information. Please provide event title and location in your description.'
            ])->withInput();
        }

        // Create the event
        $event = Event::create($eventData);

        return redirect()->route('admin.event-details', $event)
            ->with('success', 'Event auto-generated successfully! Review and adjust as needed.');
    }

    /**
     * Parse event description and extract information
     */
    private function parseEventDescription($description)
    {
        // Smart parsing logic
        $lines = array_filter(array_map('trim', explode("\n", $description)));
        
        $eventData = [
            'name' => '',
            'title' => '',
            'description' => $description,
            'date' => now()->addDay()->format('Y-m-d H:i'),
            'location' => '',
            'price' => 5000,
            'available_tickets' => 100,
            'currency' => 'NGN',
            'scanner_code' => strtoupper(substr(md5(uniqid() . time()), 0, 12)),
        ];

        // Extract title (first line usually)
        if (!empty($lines[0])) {
            $eventData['title'] = $lines[0];
            $eventData['name'] = $lines[0];
        }

        // Look for common patterns
        $fullText = implode(' ', $lines);
        
        // Extract location (keywords: at, in, venue, location)
        if (preg_match('/(?:at|in|venue|location)[:\s]+([A-Z][\w\s]+?)(?:\.|,|$)/i', $fullText, $matches)) {
            $eventData['location'] = trim($matches[1]);
        } elseif (!empty($lines[1])) {
            $eventData['location'] = $lines[1];
        }

        // Extract date if mentioned
        if (preg_match('/(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}|\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2})/i', $fullText, $matches)) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', str_replace('-', '/', $matches[1]));
                $eventData['date'] = $date->format('Y-m-d') . ' 18:00';
            } catch (\Exception $e) {
                // Keep default date
            }
        }

        // Extract price (keywords: price, cost, ticket, ₦, N)
        if (preg_match('/(?:price|cost|ticket|₦|N)\s*[:\s]*(?:₦\s*)?(\d+(?:,\d+)?)/i', $fullText, $matches)) {
            $price = (int)str_replace(',', '', $matches[1]);
            $eventData['price'] = max($price, 1000); // minimum 1000 NGN
        }

        // Extract ticket quantity
        if (preg_match('/(?:ticket|capacity|spot)\w*\s*[:\s]*(\d+)/i', $fullText, $matches)) {
            $eventData['available_tickets'] = max((int)$matches[1], 10);
        }

        return $eventData;
    }

    /**
     * Edit event (admin version)
     */
    public function editEvent(Event $event)
    {
        $organizers = User::where('is_admin', false)->get();
        return view('admin.edit-event', compact('event', 'organizers'));
    }

    /**
     * Update event (admin version)
     */
    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
        ]);

        $dateTime = $validated['date'] . ' ' . $validated['time'];
        unset($validated['date'], $validated['time']);
        
        $validated['name'] = $validated['title'];
        $validated['date'] = $dateTime;

        $event->update($validated);

        return redirect()->route('admin.event-details', $event)
            ->with('success', 'Event updated successfully!');
    }
}

