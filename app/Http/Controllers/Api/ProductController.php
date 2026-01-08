<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // 1. Authorization: Only Sellers (or Admin) can create products
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'seller') {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // 2. Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'brand' => 'required|string',
            'model' => 'required|string',
            // Image handling via API usually involves base64 or multipart form-data.
            // For simplicity in this JSON API, we might expect a separate upload endpoint or skip complex file handling now.
            // We'll leave images optional here for the basic CRUD.
        ]);

        // 3. Prepare Data
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        $validated['seller_id'] = auth()->id();
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';
        $validated['is_active'] = auth()->user()->isAdmin(); 

        // 4. Save Product
        $product = Product::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => auth()->user()->isAdmin() ? 'Product created successfully.' : 'Product submitted for approval.',
            'data' => $product
        ], 201);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Authorization
        if (!auth()->user()->isAdmin() && auth()->id() !== $product->seller_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'stock_quantity' => 'sometimes|required|integer',
            'brand' => 'sometimes|required|string',
            'model' => 'sometimes|required|string',
        ]);

        if ($request->has('name') && $request->name !== $product->name) {
             $validated['slug'] = Str::slug($validated['name']);
        }

        // Reset status if seller updates
        if (auth()->user()->role === 'seller') {
            $validated['status'] = 'pending';
        }

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => auth()->user()->role === 'seller' ? 'Product updated and submitted for re-approval.' : 'Product updated successfully.',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Authorization
        if (!auth()->user()->isAdmin() && auth()->id() !== $product->seller_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    }
}
