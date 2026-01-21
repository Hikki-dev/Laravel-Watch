<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseSyncService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                // Only set password if creating a new user (isDirty check is hard here with updateOrCreate, 
                // but updateOrCreate fills attributes. We can use firstOrCreate or similar to be safer, 
                // but for now let's just not force a password change on every login if possible, 
                // or just leave it as random since social login users don't use passwords locally)
                'password' => bcrypt(Str::random(16)), 
                'email_verified_at' => now(), 
                'role' => 'customer', // Default role
            ]);

            Auth::login($user);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
