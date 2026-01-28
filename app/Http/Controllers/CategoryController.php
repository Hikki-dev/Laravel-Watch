<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'approved')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Allow Admin and Seller
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'seller') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle Image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/brands'), $imageName);
            $validated['image'] = $imageName;
        } elseif ($request->filled('image_url')) {
            // Save full URL. View must handle this distinction.
            $validated['image'] = $request->image_url;
        }

        // Set Status: Admin -> Approved, Seller -> Pending
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';

        Category::create($validated);

        // Redirect based on role
        if (auth()->user()->role === 'seller') {
            return redirect()->route('seller.dashboard')->with('success', 'Brand created successfully.');
        }

        return redirect()->route('categories.index')->with('success', 'Brand created successfully.');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_url' => 'nullable|url',
        ]);

        if ($request->has('name') && $request->name !== $category->name) {
             $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle Image
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is a file
            if ($category->image && file_exists(public_path('images/brands/' . $category->image))) {
                unlink(public_path('images/brands/' . $category->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/brands'), $imageName);
            $validated['image'] = $imageName;
        } elseif ($request->filled('image_url')) {
             $validated['image'] = $request->image_url;
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
