<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'customer', // Added role support in registration
        ]);

        $abilities = $this->getAbilities($user);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'abilities' => $abilities
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $abilities = $this->getAbilities($user);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'abilities' => $abilities
            ]
        ]);
    }

    /**
     * Sign in with a Firebase/Google ID token.
     *
     * The identity comes from the verified token, never from the request body:
     * the token is checked by Google (signature, expiry, project) and the email
     * and Google id are read from Google's response.
     */
    public function loginWithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $apiKey = config('services.firebase.web_api_key');
        if (!$apiKey) {
            return response()->json(['status' => 'error', 'message' => 'Google login is not configured.'], 503);
        }

        $lookup = Http::timeout(10)->post(
            'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . $apiKey,
            ['idToken' => $request->id_token]
        );

        $identity = $lookup->successful() ? $lookup->json('users.0') : null;
        $email = $identity['email'] ?? null;

        if (!$email || empty($identity['emailVerified'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or unverified Google token.'], 401);
        }

        $googleId = collect($identity['providerUserInfo'] ?? [])
            ->firstWhere('providerId', 'google.com')['rawId'] ?? null;

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->is_active === false) {
                return response()->json(['status' => 'error', 'message' => 'Account is disabled.'], 403);
            }
            // Link the Google id once. Never touch the role or password of an existing account.
            if ($googleId && !$user->google_id) {
                $user->update(['google_id' => $googleId]);
            }
        } else {
            $user = User::create([
                'email' => $email,
                'name' => $request->name ?: ($identity['displayName'] ?? explode('@', $email)[0]),
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(32)),
                'role' => 'customer',
            ]);
        }

        $abilities = $this->getAbilities($user);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Google Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'abilities' => $abilities
            ]
        ]);
    }

    /**
     * Get Sanctum abilities based on user role
     */
    private function getAbilities(User $user)
    {
        if ($user->role === 'admin') {
            return ['*'];
        } elseif ($user->role === 'seller') {
            return ['product:create', 'product:update', 'product:delete'];
        } else {
            return ['order:create'];
        }
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }
}
