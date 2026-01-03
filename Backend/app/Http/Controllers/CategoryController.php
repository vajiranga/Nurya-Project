<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Category::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
        ]);

        return \App\Models\Category::create($validated);
    }

    public function show(string $id)
    {
        return \App\Models\Category::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validated);
        return $category;
    }

    public function destroy(string $id)
    {
        \App\Models\Category::destroy($id);
        return response()->noContent();
    }
}
