<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function indexPublic(Request $request)
    {
        $q = $request->query('q');
        $location = $request->query('location');

        $query = Event::query();

        if ($q) {
            $query->where(function ($qr) use ($q) {
                $qr->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }

        $events = $query->latest()->paginate(9)->appends($request->only('q', 'location'));

        return view('events.index', compact('events'));
    }

    public function showPublic($id, $slug = null)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $events = $user->events()->withCount('tickets')->get();
        $totalEvents = $events->count();
        $totalTicketsSold = $events->sum('tickets_count');
        return view('dashboard', compact('events', 'totalEvents', 'totalTicketsSold'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'banner' => 'nullable|image|max:2048',
            'available_tickets' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|max:10',
            'account_number' => 'nullable|string|max:64',
            'bank_code' => 'nullable|string|max:16',
            'bank_name' => 'nullable|string|max:128',
            'account_name' => 'nullable|string|max:255',
            'auto_payout' => 'nullable|boolean',
        ]);

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('banners', $file, $filename);
            $data['banner'] = $filename;
        }

        $data['user_id'] = Auth::id();
        $data['available_tickets'] = $data['available_tickets'] ?? 0;
        $data['currency'] = $data['currency'] ?? 'NGN';
        $data['account_number'] = $data['account_number'] ?? null;
        $data['bank_code'] = $data['bank_code'] ?? null;
        $data['bank_name'] = $data['bank_name'] ?? null;
        $data['account_name'] = $data['account_name'] ?? null;
        $data['auto_payout'] = !empty($data['auto_payout']);
        $data['payout_balance'] = 0;

        if ($data['available_tickets'] > 0) {
            $data['scan_token'] = Str::random(40);
        }

        // --- DIRECT PAYOUTS VIA PAYSTACK SUBACCOUNTS ---
        $paystackKey = config('services.paystack.secret');
        if (!empty($data['bank_code']) && !empty($data['account_number']) && !empty($paystackKey)) {
            $ch = curl_init('https://api.paystack.co/subaccount');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'business_name' => 'Event: ' . $data['title'],
                'settlement_bank' => $data['bank_code'],
                'account_number' => $data['account_number'],
                'percentage_charge' => 5 // 5% platform fee, 95% straight to creator
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $paystackKey,
                'Content-Type: application/json'
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $resData = json_decode($response, true);
                if (isset($resData['status']) && $resData['status'] == true && isset($resData['data']['subaccount_code'])) {
                    $data['subaccount_code'] = $resData['data']['subaccount_code'];
                }
            }
        }

        $event = Event::create($data);
        
        // Generate scanner code for the event
        $event->generateScannerCode();

        return redirect()->route('dashboard')->with('success', 'Event created successfully. Payouts are active.');
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403);
        }
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'banner' => 'nullable|image|max:2048',
            'available_tickets' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|max:10',
            'account_number' => 'nullable|string|max:64',
            'bank_code' => 'nullable|string|max:16',
            'bank_name' => 'nullable|string|max:128',
            'account_name' => 'nullable|string|max:255',
            'auto_payout' => 'nullable|boolean',
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner) {
                Storage::disk('public')->delete('banners/'.$event->banner);
            }
            $file = $request->file('banner');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('banners', $file, $filename);
            $data['banner'] = $filename;
        }

        $data['available_tickets'] = $data['available_tickets'] ?? $event->available_tickets;
        $data['currency'] = $data['currency'] ?? $event->currency;
        $data['account_number'] = $data['account_number'] ?? $event->account_number;
        $data['bank_code'] = $data['bank_code'] ?? $event->bank_code;
        $data['bank_name'] = $data['bank_name'] ?? $event->bank_name;
        $data['account_name'] = $data['account_name'] ?? $event->account_name;
        $data['auto_payout'] = isset($data['auto_payout']) ? (bool)$data['auto_payout'] : $event->auto_payout;

        // If tickets increased and there's no scan_token, generate one
        if ($data['available_tickets'] > 0 && !$event->scan_token) {
            $data['scan_token'] = Str::random(40);
        }

        $event->update($data);

        return redirect()->route('dashboard')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403);
        }
        if ($event->banner) {
            Storage::disk('public')->delete('banners/'.$event->banner);
        }
        $event->delete();
        return redirect()->route('dashboard')->with('success', 'Event deleted.');
    }

    public function regenerateScannerCode(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $newCode = $event->regenerateScannerCode();
            return response()->json([
                'success' => true,
                'scanner_code' => $newCode,
                'message' => 'Scanner code regenerated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to regenerate scanner code'
            ], 500);
        }
    }
}
