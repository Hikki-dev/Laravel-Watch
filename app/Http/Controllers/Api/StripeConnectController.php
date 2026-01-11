<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Exception;

class StripeConnectController extends Controller
{
    public function connect()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->stripe_account_id && $user->stripe_account_enabled) {
            return response()->json([
                'status' => 'success',
                'message' => 'Already connected',
                'connected' => true
            ]);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            if (!$user->stripe_account_id) {
                $account = \Stripe\Account::create([
                    'type' => 'express',
                    'email' => $user->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);
                $user->stripe_account_id = $account->id;
                $user->save();
            }

            $accountLink = \Stripe\AccountLink::create([
                'account' => $user->stripe_account_id,
                'refresh_url' => 'https://example.com/stripe/refresh', // Mobile app deep link
                'return_url' => 'https://example.com/stripe/return',   // Mobile app deep link
                'type' => 'account_onboarding',
            ]);

            return response()->json([
                'status' => 'success',
                'url' => $accountLink->url,
                'connected' => false
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
