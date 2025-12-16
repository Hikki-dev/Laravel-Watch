<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Stripe\Stripe;

class StripeConnectController extends Controller
{
    public function connect()
    {
        $user = Auth::user();
        
        if ($user->stripe_account_id && $user->stripe_account_enabled) {
            return redirect()->route('seller.dashboard')->with('status', 'You are already connected to Stripe.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 1. Create a Stripe Express account for the user if they don't have one
        if (!$user->stripe_account_id) {
            try {
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
            } catch (Exception $e) {
                return redirect()->route('seller.dashboard')->with('error', 'Error creating Stripe account: ' . $e->getMessage());
            }
        }

        // 2. Create an Account Link for onboarding
        try {
            $accountLink = \Stripe\AccountLink::create([
                'account' => $user->stripe_account_id,
                'refresh_url' => route('stripe.connect'),
                'return_url' => route('stripe.callback'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (Exception $e) {
            return redirect()->route('seller.dashboard')->with('error', 'Error creating Stripe account link: ' . $e->getMessage());
        }
    }

    public function callback()
    {
        $user = Auth::user();

        if (!$user->stripe_account_id) {
            return redirect()->route('seller.dashboard')->with('error', 'Stripe account not found.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $account = \Stripe\Account::retrieve($user->stripe_account_id);

            if ($account->charges_enabled) {
                $user->stripe_account_enabled = true;
                $user->save();
                return redirect()->route('seller.dashboard')->with('status', 'Stripe account connected successfully!');
            } else {
                 return redirect()->route('seller.dashboard')->with('warning', 'Stripe onboarding not fully completed. Please try again.');
            }
        } catch (Exception $e) {
            return redirect()->route('seller.dashboard')->with('error', 'Error retrieving Stripe account details: ' . $e->getMessage());
        }
    }
}
