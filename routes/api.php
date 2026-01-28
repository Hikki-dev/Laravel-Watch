<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/auth/google', [\App\Http\Controllers\Api\AuthController::class, 'loginWithGoogle']);

// Public Product Routes
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
Route::get('/brands', [\App\Http\Controllers\Api\BrandController::class, 'index']);

// Image Proxy Route to fix CORS on Web
Route::get('/image-proxy', function (Request $request) {
    $path = $request->query('path');
    if (!$path) return response()->json(['error' => 'Path required'], 400);

    // Security: Prevent accessing files outside public folder
    if (str_contains($path, '..') || str_starts_with($path, '/')) {
        return response()->json(['error' => 'Invalid path'], 403);
    }

    $fullPath = public_path($path);
    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    return response()->file($fullPath);
});

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
    
    // Mobile/Granular Cart Routes
    Route::patch('/cart/product/{product}', [\App\Http\Controllers\Api\CartController::class, 'updateByProduct']);
    Route::delete('/cart/product/{product}', [\App\Http\Controllers\Api\CartController::class, 'destroyByProduct']);
    Route::delete('/cart/clear', [\App\Http\Controllers\Api\CartController::class, 'clear']);

    // Order Routes
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);

    // Profile Routes
    Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/photo', [\App\Http\Controllers\Api\ProfileController::class, 'uploadPhoto']);

    // Review Routes
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);

    // Favorites Routes
    Route::get('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'index']);
    Route::post('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'store']); // Toggle
    Route::delete('/favorites/{productId}', [\App\Http\Controllers\Api\FavoriteController::class, 'destroy']);

    // Checkout Route
    Route::post('/checkout', [\App\Http\Controllers\Api\CheckoutController::class, 'store']);

    // Seller Product Management - Requires specific abilities
    Route::middleware(['ability:product:create,product:update,product:delete'])->prefix('seller')->group(function () {
        Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store']);
        Route::put('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::delete('/products/{product}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);
    });

    // Admin Routes
    Route::middleware(['ability:*'])->prefix('admin')->group(function () {
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
    // Seller - Stripe Connect
    Route::get('/seller/stripe/connect', [\App\Http\Controllers\Api\StripeConnectController::class, 'connect']);

    // ====================================================
    // [SSP Integration] - FULL User Management CRUD
    // ====================================================

    // User Management - ADMIN ONLY (ability:*)
    Route::middleware(['ability:*'])->group(function () {
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
                    'is_active' => $request->boolean('is_active', true),
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
            
            $user->update($request->only(['name', 'email', 'role', 'is_active']));
            
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
    });
});