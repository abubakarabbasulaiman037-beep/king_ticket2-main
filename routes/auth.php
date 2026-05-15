<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Simple authentication routes (login, register, logout) implemented with closures

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $data['email'])->first();
    if (!$user || !Hash::check($data['password'], $user->password)) {
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    $remember = $request->boolean('remember');
    Auth::login($user, $remember);
    $request->session()->regenerate();

    // Redirect to admin dashboard if admin, otherwise organizer dashboard
    if ($user->is_admin) {
        return redirect()->intended(route('admin.dashboard'));
    }
    return redirect()->intended(route('dashboard.index'));
})->name('login.post');

Route::get('register', function () {
    return view('auth.register');
})->name('register');

Route::post('register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);

    return redirect()->route('dashboard.index');
})->name('register.post');

Route::post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
