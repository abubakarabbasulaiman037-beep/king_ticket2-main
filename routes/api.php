<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;

Route::get('/search/events', function(Request $request){
    $q = $request->query('q');
    $results = Event::where('title', 'like', "%{$q}%")
        ->orWhere('description', 'like', "%{$q}%")
        ->limit(10)
        ->get(['id','title','date','banner']);
    return response()->json($results);
});
