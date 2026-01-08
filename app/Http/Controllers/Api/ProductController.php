<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        return response()->json(['data' => \App\Models\Product::all()]);
    }
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 3. Prepare Data
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        $validated['seller_id'] = auth()->id();
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';
        $validated['is_active'] = auth()->user()->isAdmin(); 

        // 4. Save Product
        $product = Product::create($validated);

        // 5. Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $fileContent = file_get_contents($image->getRealPath());

                $productImage = $product->images()->create([
                    'image_path' => 'storage/' . $path,
                    'image_data' => $fileContent,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);

                if ($index === 0) {
                     $product->update(['image_url' => route('images.products', $productImage->id)]);
                }
            }
        }

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

        if ($request->hasFile('images')) {
             $request->validate([
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $fileContent = file_get_contents($image->getRealPath());
                
                $productImage = $product->images()->create([
                    'image_path' => 'storage/' . $path,
                    'image_data' => $fileContent,
                    'is_primary' => false,
                    'sort_order' => $index,
                ]);

                if (!$product->image_url && $index === 0) {
                     $product->update(['image_url' => route('images.products', $productImage->id)]);
                }
            }
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
