<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Lga;
use App\Models\EventCenter;

class EventCenterController extends Controller
{
    public function index()
    {
        return view('venues.index');
    }

    public function show($id)
    {
        $venue = EventCenter::with(['state', 'lga'])->findOrFail($id);
        return view('venues.show', compact('venue'));
    }

    public function getStates()
    {
        return response()->json(State::orderBy('name')->get());
    }

    public function getLgas(State $state)
    {
        return response()->json($state->lgas()->orderBy('name')->get());
    }

    public function search(Request $request)
    {
        // Simple search and filtering endpoint
        $query = EventCenter::with(['state', 'lga']);

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('lga_id')) {
            $query->where('lga_id', $request->lga_id);
        }
        
        if ($request->filled('query')) {
            $q = $request->input('query');
            $query->where(function($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('city_town', 'like', "%{$q}%")
                   ->orWhere('features', 'like', "%{$q}%")
                   ->orWhere('hall_type', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('hall_type', $request->type);
        }

        if ($request->filled('price_min')) {
            $query->where('starting_price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('starting_price', '<=', $request->price_max);
        }

        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', $request->capacity_min);
        }

        $venues = $query->paginate(12);

        return response()->json($venues);
    }
}
