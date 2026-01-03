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
            'images_json' => 'nullable|array',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        return \App\Models\Product::create($validated);
    }

    public function show(string $id)
    {
        return \App\Models\Product::with(['category', 'reviews'])->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'gold_weight' => 'sometimes|string',
            'karat' => 'sometimes|string',
            'stock_status' => 'sometimes|string',
            'images_json' => 'sometimes|array',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $product->update($validated);
        return $product;
    }

    public function destroy(string $id)
    {
        \App\Models\Product::destroy($id);
        return response()->noContent();
    }
}
