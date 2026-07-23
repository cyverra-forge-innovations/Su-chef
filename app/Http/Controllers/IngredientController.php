<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Show all ingredients
    public function index()
    {
        $ingredients = Ingredient::orderBy('category')->orderBy('name')->get();
        $grouped = $ingredients->groupBy('category');
        return view('ingredients.index', compact('grouped'));
    }

    // Show form to create a new ingredient
    public function create()
    {
        return view('ingredients.create');
    }

    // Save new ingredient to database
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'unit'     => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        Ingredient::create([
            'name'     => $request->name,
            'unit'     => $request->unit,
            'category' => $request->category,
        ]);

        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient created successfully!');
    }

    // Show a single ingredient
    public function show(Ingredient $ingredient)
    {
        $ingredient->load('recipes');
        return view('ingredients.show', compact('ingredient'));
    }

    // Show form to edit an ingredient
    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    // Update ingredient in database
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'unit'     => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $ingredient->update([
            'name'     => $request->name,
            'unit'     => $request->unit,
            'category' => $request->category,
        ]);

        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient updated successfully!');
    }

    // Delete an ingredient
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient deleted successfully!');
    }
}