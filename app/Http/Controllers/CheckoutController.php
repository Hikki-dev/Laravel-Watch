<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index()
    {
        $cartItems = Auth::user()->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        $countries = [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
            'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan',
            'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
            'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica',
            'Croatia', 'Cuba', 'Cyprus', 'Czechia (Czech Republic)', 'Democratic Republic of the Congo', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor (Timor-Leste)',
            'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini (fmr. "Swaziland")', 'Ethiopia', 'Fiji', 'Finland',
            'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea',
            'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq',
            'Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati',
            'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania',
            'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius',
            'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (formerly Burma)', 'Namibia',
            'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway',
            'Oman', 'Pakistan', 'Palau', 'Palestine State', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland',
            'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino',
            'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands',
            'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland',
            'Syria', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
            'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City',
            'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
        ];

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total', 'countries'));
    }

    /**
     * Process the checkout and create the order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:credit_card,cash_on_delivery',
            'card_holder' => 'required_if:payment_method,credit_card|nullable|string|max:255',
            'card_number' => ['required_if:payment_method,credit_card', 'nullable', 'string', 'regex:/^[\d\s]+$/', 'min:16', 'max:19'],
            'card_expiry' => ['required_if:payment_method,credit_card', 'nullable', 'string', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],
            'card_cvc' => ['required_if:payment_method,credit_card', 'nullable', 'string', 'regex:/^[0-9]{3,4}$/'],
        ], [
            'card_holder.required_if' => 'The card holder name is required for card payments.',
            'card_number.required_if' => 'The card number is required for card payments.',
            'card_number.regex' => 'The card number must contain only digits.',
            'card_expiry.required_if' => 'The expiry date is required for card payments.',
            'card_expiry.regex' => 'The expiry date must be in MM/YY format.',
            'card_cvc.required_if' => 'The CVC is required for card payments.',
            'card_cvc.regex' => 'The CVC must be a valid 3 or 4 digit number.',
        ]);

        $user = Auth::user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        // Use a transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // 1. Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address . ', ' . $request->city . ', ' . $request->postal_code . ', ' . $request->country,
                'payment_status' => 'paid', // Simulating successful payment
                'payment_method' => $request->payment_method,
            ]);

            // 2. Create Order Items and Deduct Stock
            foreach ($cartItems as $item) {
                // Check stock again
                if ($item->product->stock_quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: " . $item->product->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Deduct stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // 3. Clear Cart
            $user->cart()->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the success page.
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
