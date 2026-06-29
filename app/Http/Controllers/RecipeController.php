<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // Show all recipes
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'categories', 'ingredients', 'reviews']);

        // Search by title
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by difficulty
        if ($request->difficulty && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by category
        if ($request->category && $request->category !== 'all') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $recipes = $query->latest()->get();

        return view('recipes.index', compact('recipes'));
    }

    // Show form to create a new recipe
    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::orderBy('name')->get();
        return view('recipes.create', compact('categories', 'ingredients'));
    }

    // Save new recipe to database
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'cook_time'   => 'required|integer',
            'difficulty'  => 'required|in:easy,medium,hard',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = Recipe::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'cook_time'   => $request->cook_time,
            'difficulty'  => $request->difficulty,
            'image'       => $imagePath,
        ]);

        // Attach categories
        if ($request->categories) {
            $recipe->categories()->attach($request->categories);
        }

        // Attach ingredients
        if ($request->ingredient_ids) {
            foreach ($request->ingredient_ids as $index => $ingredientId) {
                if ($ingredientId) {
                    $recipe->ingredients()->attach($ingredientId, [
                        'quantity' => $request->quantities[$index] ?? ''
                    ]);
                }
            }
        }

        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully!');
    }

    // Show a single recipe
    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'categories', 'ingredients', 'reviews.user']);
        return view('recipes.show', compact('recipe'));
    }

    // Show form to edit a recipe
    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        $ingredients = Ingredient::orderBy('name')->get();
        return view('recipes.edit', compact('recipe', 'categories', 'ingredients'));
    }

    // Update recipe in database
    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'cook_time'   => 'required|integer',
            'difficulty'  => 'required|in:easy,medium,hard',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
            $recipe->update(['image' => $imagePath]);
        }

        $recipe->update([
            'title'       => $request->title,
            'description' => $request->description,
            'cook_time'   => $request->cook_time,
            'difficulty'  => $request->difficulty,
        ]);

        // Sync categories
        if ($request->categories) {
            $recipe->categories()->sync($request->categories);
        }

        // Sync ingredients
        if ($request->ingredient_ids) {
            $ingredientData = [];
            foreach ($request->ingredient_ids as $index => $ingredientId) {
                if ($ingredientId) {
                    $ingredientData[$ingredientId] = [
                        'quantity' => $request->quantities[$index] ?? ''
                    ];
                }
            }
            $recipe->ingredients()->sync($ingredientData);
        } else {
            $recipe->ingredients()->detach();
        }

        return redirect()->route('recipes.show', $recipe)->with('success', 'Recipe updated successfully!');
    }

    // Delete a recipe
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully!');
    }

    // Smart Ingredient Matching
    public function match(Request $request)
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $matchedRecipes = collect();

        if ($request->isMethod('post') && $request->ingredient_ids) {
            $selectedIds = array_filter($request->ingredient_ids);

            if (!empty($selectedIds)) {
                $recipes = Recipe::with(['ingredients', 'categories', 'user', 'reviews'])->get();

                foreach ($recipes as $recipe) {
                    $recipeIngredientIds = $recipe->ingredients->pluck('id')->toArray();
                    $matchCount = count(array_intersect($selectedIds, $recipeIngredientIds));
                    $totalIngredients = count($recipeIngredientIds);

                    if ($matchCount > 0) {
                        $percentage = $totalIngredients > 0
                            ? round(($matchCount / $totalIngredients) * 100)
                            : 0;

                        $recipe->match_count = $matchCount;
                        $recipe->match_percentage = $percentage;
                        $recipe->total_ingredients = $totalIngredients;
                        $matchedRecipes->push($recipe);
                    }
                }

                // Sort by match percentage (highest first)
                $matchedRecipes = $matchedRecipes->sortByDesc('match_percentage');
            }
        }

        return view('recipes.match', compact('ingredients', 'matchedRecipes'));
    }
}