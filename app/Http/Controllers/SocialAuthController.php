<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $params = [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
        ];

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        return redirect($url);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->query('code');
        if (! $code) {
            return redirect()->route('login')->with('error', 'No code returned from Google.');
        }

        try {
            $tokenResp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT'),
                'grant_type' => 'authorization_code',
            ]);

            if ($tokenResp->failed()) {
                throw new \Exception('Failed to obtain access token.');
            }

            $tokenData = $tokenResp->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (! $accessToken) {
                throw new \Exception('Failed to obtain access token.');
            }

            $userResp = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if ($userResp->failed()) {
                throw new \Exception('Failed to fetch Google user.');
            }

            $googleUser = $userResp->json();

            $email = $googleUser['email'] ?? null;
            $name = $googleUser['name'] ?? ($googleUser['email'] ?? 'Google User');

            if (! $email) {
                throw new \Exception('Google did not return an email address.');
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make(Str::random(24))]
            );

            Auth::login($user);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
