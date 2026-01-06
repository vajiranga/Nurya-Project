<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Product::with(['category', 'reviews'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'gold_weight' => 'nullable|string',
            'karat' => 'nullable|string',
            'stock_status' => 'nullable|string',
            'sku' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image
            'sizes' => 'nullable|array' // Array of { size, price }
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate unique name
                $filename = time() . '_' . $image->getClientOriginalName();
                // Store in 'public/products' (accessible via web)
                $image->move(public_path('images/products'), $filename);
                $imagePaths[] = '/images/products/' . $filename;
            }
        }
        
        // If no images uploaded, use empty array or maybe a default
        $validated['images_json'] = $imagePaths;

        return \App\Models\Product::create($validated);
    }

    public function show(string $id)
    {
        return \App\Models\Product::with(['category', 'reviews'])->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\Log::info('Update Product Request:', $request->all());
        
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'gold_weight' => 'nullable|string',
            'karat' => 'nullable|string',
            'stock_status' => 'nullable|string',
            'sku' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'nullable|array'
        ]);

        if ($request->hasFile('images')) {
            \Illuminate\Support\Facades\Log::info('Images found in request');
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/products'), $filename);
                $imagePaths[] = '/images/products/' . $filename;
            }
            $validated['images_json'] = $imagePaths;
            \Illuminate\Support\Facades\Log::info('New Images processed:', $imagePaths);
        } else {
             \Illuminate\Support\Facades\Log::info('No images in request');
        }

        $product->update($validated);
        return $product;
    }

    public function destroy(string $id)
    {
        \App\Models\Product::destroy($id);
        return response()->noContent();
    }
}
