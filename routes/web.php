<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoogleAuthController;

// Google Auth Routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    if (Auth::check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }
    }

    $categories = \Illuminate\Support\Facades\Cache::remember('home_categories', 60 * 60, function () {
        return \App\Models\Category::all();
    });

    $featured_products = \Illuminate\Support\Facades\Cache::remember('home_featured_products', 60 * 60, function () {
        $products = \App\Models\Product::where('is_featured', true)->limit(4)->get();
        if ($products->isEmpty()) {
            return \App\Models\Product::latest()->limit(4)->get();
        }
        return $products;
    });

    return view('welcome', compact('categories', 'featured_products'));
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('users.destroy');
    Route::get('/orders', [\App\Http\Controllers\AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\AdminController::class, 'showOrder'])->name('orders.show');
    Route::get('/approvals', [\App\Http\Controllers\AdminController::class, 'approvals'])->name('products.approvals');
    Route::post('/products/{product}/approve', [\App\Http\Controllers\AdminController::class, 'approve'])->name('products.approve');
    Route::post('/products/{product}/reject', [\App\Http\Controllers\AdminController::class, 'reject'])->name('products.reject');
});

// Seller Routes
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [\App\Http\Controllers\SellerController::class, 'products'])->name('products.index');
    
    // Stripe Connect
    Route::get('/stripe/connect', [\App\Http\Controllers\StripeConnectController::class, 'connect'])->name('stripe.connect');
    Route::get('/stripe/callback', [\App\Http\Controllers\StripeConnectController::class, 'callback'])->name('stripe.callback');
});

Route::get('/debug-config', function () {
    return [
        'db_connection' => config('database.default'),
        'mysql_host' => config('database.connections.mysql.host'),
        'mysql_port' => config('database.connections.mysql.port'),
        'mysql_database' => config('database.connections.mysql.database'),
        'mysql_username' => config('database.connections.mysql.username'),
        'mysql_url_is_set' => !empty(config('database.connections.mysql.url')),
        'env_db_host' => env('DB_HOST'),
        'env_mysql_host' => env('MYSQLHOST'),
        'env_db_url_is_set' => !empty(env('DB_URL')),
        'app_name' => config('app.name'),
        'env_app_name' => env('APP_NAME'),
        'app_key_is_set' => !empty(env('APP_KEY')),
    ];
});

Route::get('/debug-vite', function () {
    $manifestPath = public_path('build/manifest.json');
    $manifestExists = file_exists($manifestPath);
    $manifestContent = $manifestExists ? json_decode(file_get_contents($manifestPath), true) : null;
    
    return [
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'asset_url' => config('app.asset_url'),
        'public_path' => public_path(),
        'build_path' => public_path('build'),
        'manifest_exists' => $manifestExists,
        'manifest_content' => $manifestContent,
        'hot_file_exists' => file_exists(public_path('hot')),
        'vite_tags' => Illuminate\Support\Facades\Vite::tags(['resources/css/app.css', 'resources/js/app.js'])->toHtml(),
    ];
});

// Image Serving Routes
Route::get('/images/products/{id}', [\App\Http\Controllers\ImageController::class, 'showProductImage'])->name('images.products');
Route::get('/images/users/{id}', [\App\Http\Controllers\ImageController::class, 'showUserImage'])->name('images.users');

