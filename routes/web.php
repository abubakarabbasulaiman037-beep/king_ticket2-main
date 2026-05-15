<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SocialAuthController;

Route::get('/', function () {
    $categories = \App\Models\Category::all();
    $banks = \App\Models\Bank::orderBy('category')->orderBy('name')->get()->groupBy('category');
    return view('home', compact('categories', 'banks'));
})->name('home');

Route::get('/test-ticket', function () {
    $ticket = \App\Models\Ticket::with(['event.state', 'user'])->first();
    if (!$ticket) {
        $event = \App\Models\Event::first();
        if(!$event) {
            $event = \App\Models\Event::factory()->create();
        }
        $ticket = \App\Models\Ticket::create(['event_id' => $event->id, 'user_id' => $event->user_id ?? 1, 'seat_number' => 'Test Seat']);
    }
    return view('tickets.show', compact('ticket'));
});

// Public listing
Route::get('/events', [EventController::class, 'indexPublic'])->name('events.public');
// Friendly public show route: /events/{id}-{slug}
Route::get('/events/{id}-{slug}', [EventController::class, 'showPublic'])->name('events.show.public')->where('id', '[0-9]+');

// Venues (Event Centers) Discovery
Route::get('/venues', [App\Http\Controllers\EventCenterController::class, 'index'])->name('venues.index');
Route::get('/venues/{id}', [App\Http\Controllers\EventCenterController::class, 'show'])->name('venues.show');
// Venues API Data
Route::get('/api/states', [App\Http\Controllers\EventCenterController::class, 'getStates']);
Route::get('/api/lgas/{state}', [App\Http\Controllers\EventCenterController::class, 'getLgas']);
Route::get('/api/venues', [App\Http\Controllers\EventCenterController::class, 'search']);

// Public create route: redirect guests to login, allow authenticated users to access the create page
Route::get('/events/create', function () {
    if (Auth::guest()) {
        return redirect()->route('login')->with('error', 'Please login to create an event.');
    }
    return view('events.create');
})->name('events.create');

// Public scan token route (QR links)
Route::get('/scan/{token}', [ScannerController::class, 'showByToken'])->name('scan.show');
// Public camera page for token holders and public verify endpoint
Route::get('/scan/{token}/camera', [ScannerController::class, 'publicCamera'])->name('scan.camera');
Route::get('/scan/{token}/verify', [ScannerController::class, 'verifyByToken'])->name('scan.verify.get');

// Public scanner with code (no login required)
Route::get('/scanner/entry', [ScannerController::class, 'publicEntry'])->name('scanner.public.entry');
Route::post('/scanner/validate-code', [ScannerController::class, 'validateCode'])->name('scanner.validate.code');
Route::get('/scanner/camera/{eventId}', [ScannerController::class, 'publicCamera'])->name('scanner.public.camera');
Route::get('/scanner/verify/{eventId}', [ScannerController::class, 'verifyPublic'])->name('scanner.verify.public');
Route::post('/scanner/verify/{eventId}', [ScannerController::class, 'verifyPublic'])->name('scanner.verify.public.post');

// Camera scanner result endpoint (legacy admin camera)
Route::get('/scanner/verify', [ScannerController::class, 'verifyByCode'])->name('scanner.verify.get');

// Social auth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Authentication-protected routes
Route::middleware(['auth'])->group(function () {
    // Redirect old dashboard route to new one
    Route::get('/dashboard', function() {
        return redirect()->route('dashboard.index');
    })->name('dashboard');

    // Organizer/Creator Dashboard
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        
        // Event management
        Route::get('events/create', [DashboardController::class, 'createEvent'])->name('create-event');
        Route::post('events', [DashboardController::class, 'storeEvent'])->name('store-event');
        Route::get('events/{event}/edit', [DashboardController::class, 'editEvent'])->name('edit-event');
        Route::put('events/{event}', [DashboardController::class, 'updateEvent'])->name('update-event');
        Route::delete('events/{event}', [DashboardController::class, 'deleteEvent'])->name('delete-event');
        
        // Earnings & Payouts
        Route::get('events/{event}/earnings', [DashboardController::class, 'eventEarnings'])->name('event-earnings');
        Route::get('payouts', [DashboardController::class, 'payoutHistory'])->name('payout-history');
        
        // Bank account
        Route::put('bank-account', [DashboardController::class, 'updateBankAccount'])->name('update-bank');
    });

    // Event CRUD for authenticated users (exclude index, show, create because handled above)
    Route::resource('events', EventController::class)->except(['index', 'show', 'create']);
    
    // Scanner code management
    Route::post('/events/{event}/regenerate-scanner-code', [EventController::class, 'regenerateScannerCode'])->name('events.regenerate-scanner-code');

    // Tickets
    Route::get('tickets/mine', [TicketController::class, 'myTickets'])->name('tickets.mine');
    Route::get('ticket/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('ticket/{ticket}/download-pdf', [TicketController::class, 'downloadPDF'])->name('tickets.download-pdf');
    Route::get('ticket/{ticket}/download-image', [TicketController::class, 'downloadImage'])->name('tickets.download-image');

    // Payments
    Route::get('events/{event}/buy', [PaymentController::class, 'showCheckout'])->name('payment.checkout');
    Route::post('events/{event}/pay', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    // Scanner (manual)
    Route::get('scanner', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('scanner/verify', [ScannerController::class, 'verify'])->name('scanner.verify');

    // Admin Panel (Protected)
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        
        // Users management
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('users/{user}', [AdminController::class, 'showUser'])->name('user-details');
        Route::delete('users/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');
        
        // Events management
        Route::get('events', [AdminController::class, 'events'])->name('events');
        Route::get('events/{event}', [AdminController::class, 'showEvent'])->name('event-details');
        Route::delete('events/{event}', [AdminController::class, 'deleteEvent'])->name('delete-event');
        
        // Event creation (Admin)
        Route::get('events-create', [AdminController::class, 'createEvent'])->name('create-event');
        Route::post('events-create', [AdminController::class, 'storeEvent'])->name('store-event');
        Route::get('events/{event}/edit', [AdminController::class, 'editEvent'])->name('edit-event');
        Route::put('events/{event}', [AdminController::class, 'updateEvent'])->name('update-event');
        
        // Event generation (AI)
        Route::get('generate-event', [AdminController::class, 'generateEventForm'])->name('generate-event-form');
        Route::post('generate-event', [AdminController::class, 'generateEvent'])->name('generate-event');
        
        // Payments
        Route::get('payments', [AdminController::class, 'payments'])->name('payments');
        
        // Payouts
        Route::get('payouts', [AdminController::class, 'payouts'])->name('payouts');
    });
});

// Include simple auth routes (login/register/logout)
require __DIR__.'/auth.php';
