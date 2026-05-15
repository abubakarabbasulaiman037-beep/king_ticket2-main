<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show organizer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's events
        $events = Event::where('user_id', $user->id)
            ->with('tickets')
            ->orderBy('date', 'desc')
            ->get();

        // Calculate earnings
        $totalEarnings = 0;
        $totalTicketsSold = 0;
        $totalPlatformFee = 0;

        foreach ($events as $event) {
            $soldTickets = $event->tickets()->whereHas('payment', function($q) {
                $q->where('status', 'completed');
            })->get();

            $totalTicketsSold += $soldTickets->count();
            foreach ($soldTickets as $ticket) {
                if ($ticket->payment) {
                    $totalEarnings += $ticket->payment->amount * 0.95;
                    $totalPlatformFee += $ticket->payment->amount * 0.05;
                }
            }
        }

        // Get already paid out
        $alreadyPaid = Payout::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate pending amount (earnings that haven't been paid out yet)
        $pendingPayout = $totalEarnings - $alreadyPaid;

        // Format earnings for view
        $earnings = [
            'total' => $totalEarnings,
            'tickets_sold' => $totalTicketsSold,
            'pending' => $pendingPayout,
            'platform_fee' => $totalPlatformFee,
            'already_paid' => $alreadyPaid,
        ];

        return view('dashboard.index', compact('events', 'earnings', 'user'));
    }

    /**
     * Create event page
     */
    public function createEvent()
    {
        return view('dashboard.create-event');
    }

    /**
     * Store new event
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d|after:today',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'lga_id' => 'nullable|exists:lgas,id',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        // Combine date and time into datetime
        $dateTime = $validated['date'] . ' ' . $validated['time'];
        unset($validated['date']);
        unset($validated['time']);
        
        $validated['user_id'] = Auth::id();
        $validated['title'] = $validated['name']; // Store as 'title' since that's the db field
        unset($validated['name']);
        $validated['date'] = $dateTime;
        $validated['currency'] = 'NGN';
        $validated['scanner_code'] = strtoupper(substr(md5(uniqid() . time()), 0, 12));

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner'] = $path;
        }

        $event = Event::create($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Edit event page
     */
    public function editEvent(Event $event)
    {
        $this->authorize('update', $event);
        return view('dashboard.edit-event', compact('event'));
    }

    /**
     * Update event
     */
    public function updateEvent(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d|after:today',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'lga_id' => 'nullable|exists:lgas,id',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        // Combine date and time into datetime
        $dateTime = $validated['date'] . ' ' . $validated['time'];
        unset($validated['date']);
        unset($validated['time']);
        $validated['title'] = $validated['name']; // Store as 'title' since that's the db field
        unset($validated['name']);
        $validated['date'] = $dateTime;

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner'] = $path;
        }

        $event->update($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event
     */
    public function deleteEvent(Event $event)
    {
        $this->authorize('delete', $event);

        // Check if event has completed payments
        $hasSoldTickets = $event->tickets()
            ->whereHas('payment', function($q) {
                $q->where('status', 'completed');
            })
            ->exists();

        if ($hasSoldTickets) {
            return redirect()->route('dashboard')
                ->with('error', 'Cannot delete events with sold tickets.');
        }

        $event->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Show event earnings details
     */
    public function eventEarnings(Event $event)
    {
        $this->authorize('view', $event);

        $payments = $event->payments()
            ->where('status', 'completed')
            ->with('buyer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRevenue = $payments->sum('amount');
        $organizerEarnings = $totalRevenue * 0.95;
        $platformFee = $totalRevenue * 0.05;
        $alreadyPaid = Payout::where('event_id', $event->id)
            ->where('status', 'completed')
            ->sum('amount');
        $ticketsSold = $event->tickets()
            ->whereHas('payment', function($q) {
                $q->where('status', 'completed');
            })
            ->count();

        $earnings = [
            'total_revenue' => $totalRevenue,
            'organizer_earnings' => $organizerEarnings,
            'platform_fee' => $platformFee,
            'already_paid' => $alreadyPaid,
            'tickets_sold' => $ticketsSold,
        ];

        return view('dashboard.event-earnings', compact(
            'event',
            'earnings'
        ));
    }

    /**
     * Update bank account details
     */
    public function updateBankAccount(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:100',
            'bank_code' => 'required|string|max:10',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('dashboard.index')
            ->with('success', 'Bank account updated successfully!');
    }

    /**
     * Get payout history
     */
    public function payoutHistory()
    {
        $payouts = Payout::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_paid' => Payout::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->sum('amount'),
            'total_pending' => Payout::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'retrying'])
                ->sum('amount'),
            'total_failed' => Payout::where('user_id', Auth::id())
                ->where('status', 'failed')
                ->sum('amount'),
            'failed_count' => Payout::where('user_id', Auth::id())
                ->where('status', 'failed')
                ->count(),
        ];

        return view('dashboard.payout-history', compact('payouts', 'stats'));
    }
}
