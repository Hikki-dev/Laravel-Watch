<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Public Product Routes
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('/cart/add/{product}', [\App\Http\Controllers\Api\CartController::class, 'store']);
    Route::patch('/cart/{cart}', [\App\Http\Controllers\Api\CartController::class, 'update']);
    Route::delete('/cart/{cart}', [\App\Http\Controllers\Api\CartController::class, 'destroy']);

    // Order Routes
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);

    // Profile Routes
    Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/photo', [\App\Http\Controllers\Api\ProfileController::class, 'uploadPhoto']);

    // Review Routes
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);

    // Checkout Route
    Route::post('/checkout', [\App\Http\Controllers\Api\CheckoutController::class, 'store']);

    // Seller Product Management
    Route::prefix('seller')->group(function () {
        Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store']);
        Route::put('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::delete('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);
    });

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\AdminController::class, 'dashboard']);
        Route::get('/approvals', [\App\Http\Controllers\Api\AdminController::class, 'approvals']);
        Route::post('/products/{product}/approve', [\App\Http\Controllers\Api\AdminController::class, 'approve']);
        Route::post('/products/{product}/reject', [\App\Http\Controllers\Api\AdminController::class, 'reject']);
        
        // Category Management
        Route::post('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'store']);
        Route::put('/categories/{category}', [\App\Http\Controllers\Api\CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [\App\Http\Controllers\Api\CategoryController::class, 'destroy']);
    });

    // Seller - Stripe Connect
    Route::get('/seller/stripe/connect', [\App\Http\Controllers\Api\StripeConnectController::class, 'connect']);
});


// ====================================================
// [SSP Integration] - FULL User Management CRUD
// ====================================================

// 1. Get All Users
Route::get('/users', function () {
    return response()->json(['data' => \App\Models\User::all()]);
});

// 2. Create User (Admin/Manual Add)
Route::post('/users', function (Request $request) {
    // Basic validation
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'nullable|string'
    ]);

    try {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'customer',
        ]);
        return response()->json($user, 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400); 
    }
});

// 3. Update User
Route::put('/users/{id}', function (Request $request, $id) {
    $user = \App\Models\User::find($id);
    if (!$user) return response()->json(['message' => 'User not found'], 404);
    
    $user->update($request->only(['name', 'email', 'role']));
    
    // Only update password if provided
    if ($request->filled('password')) {
        $user->update(['password' => bcrypt($request->password)]);
    }
    
    return response()->json($user);
});

// 4. Delete User
Route::delete('/users/{id}', function ($id) {
    \App\Models\User::destroy($id);
    return response()->json(['message' => 'User deleted']);
});