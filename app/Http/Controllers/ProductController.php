<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     * Flutter Equivalent: The 'build' method of your ProductListScreen.
     */
    public function index(Request $request)
    {
        // Start a query to fetch active products
        $query = Product::with('category')->where('is_active', true);

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // 2. Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. Brand Filter
        if ($request->filled('brand')) {
            $brand = $request->brand;
            $query->where(function($q) use ($brand) {
                $q->whereHas('category', fn($q) => $q->where('name', $brand))
                  ->orWhere('brand', $brand);
            });
        }

        // Fetch results with pagination (12 items per page)
        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     * Flutter Equivalent: The 'onPressed' callback of your "Save Product" button.
     */
    public function store(Request $request)
    {
        // 1. Authorization: Only Admins or Sellers can create products
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validation: Ensure data is correct before saving
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'brand' => 'required|string',
            'model' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Allow multiple images
        ]);

        // 3. Prepare Data
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6); // Unique URL friendly name
        $validated['seller_id'] = auth()->id();
        
        // Logic: Admins are auto-approved. Sellers must wait for approval.
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';
        $validated['is_active'] = auth()->user()->isAdmin(); 

        // 4. Save Product
        $product = Product::create($validated);

        // 5. Handle Image Uploads
        // 5. Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $fileContent = file_get_contents($image->getRealPath());
                
                // Create database record for the image
                $productImage = $product->images()->create([
                    'image_path' => 'storage/' . $path,
                    'image_data' => $fileContent, // Store binary
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
                
                // Update legacy main image to point to serving route
                if ($index === 0) {
                    $product->update(['image_url' => route('images.products', $productImage->id)]);
                }
            }
        }

        // 6. Redirect User
        $message = auth()->user()->isAdmin() 
            ? 'Product created successfully.' 
            : 'Product submitted for approval.';

        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.products.index')->with('success', $message);
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    /**
     * Display the specified product.
     * Flutter Equivalent: Navigating to ProductDetailScreen.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'seller', 'images', 'reviews']);
        
        $related_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related_products'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Authorization: Admins or the Product Owner (Seller) can edit
        if (!auth()->user()->isAdmin() && auth()->id() !== $product->seller_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'brand' => 'required|string',
            'model' => 'required|string',
        ]);

        if ($request->has('name') && $request->name !== $product->name) {
             $validated['slug'] = Str::slug($validated['name']);
        }

        // Logic: If a seller updates the product, reset status to 'pending' for re-approval
        if (auth()->user()->role === 'seller') {
            $validated['status'] = 'pending';
        }

        if ($request->hasFile('images')) {
             $request->validate([
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Optional: Delete old images if you want a complete replace, or just append
            // For now, let's append new images. If you want to replace, uncomment below:
            // $product->images()->delete(); 

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $fileContent = file_get_contents($image->getRealPath());
                
                $productImage = $product->images()->create([
                    'image_path' => 'storage/' . $path,
                    'image_data' => $fileContent,
                    'is_primary' => false,
                    'sort_order' => $index,
                ]);

                // Update legacy main image if it was empty or we want to override
                if (!$product->image_url && $index === 0) {
                     $product->update(['image_url' => route('images.products', $productImage->id)]);
                }
            }
        }

        $product->update($validated);

        $message = auth()->user()->role === 'seller' 
            ? 'Product updated and submitted for re-approval.' 
            : 'Product updated successfully.';

        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.products.index')->with('success', $message);
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Authorization: Admins or the Product Owner (Seller) can delete
        if (!auth()->user()->isAdmin() && auth()->id() !== $product->seller_id) {
            abort(403, 'Unauthorized action.');
        }

        $product->delete();

        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
