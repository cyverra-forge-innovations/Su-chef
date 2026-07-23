<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientPrice;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredientPriceController extends Controller
{
    /** Show the submit-a-price form for a given ingredient */
    public function create(Ingredient $ingredient)
    {
        $markets = Market::orderBy('name')->get();
        $summary = $ingredient->getPriceSummary();

        return view('ingredient-prices.create', compact('ingredient', 'markets', 'summary'));
    }

    /** Save a new price submission */
    public function store(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'market_id'     => 'required|exists:markets,id',
            'price'         => 'required|numeric|min:1|max:999999',
            'unit'          => 'required|string|max:50',
            'unit_quantity' => 'required|numeric|min:0.01',
        ]);

        IngredientPrice::create([
            'ingredient_id' => $ingredient->id,
            'market_id'     => $request->market_id,
            'user_id'       => Auth::id(),
            'price'         => $request->price,
            'unit'          => $request->unit,
            'unit_quantity' => $request->unit_quantity,
            'submitted_at'  => now(),
        ]);

        return redirect()->route('ingredients.show', $ingredient)
            ->with('success', 'Price submitted — thank you!');
    }

    /** Flag a submission as inaccurate */
    public function flag(IngredientPrice $price)
    {
        $price->update(['is_flagged' => true]);

        return redirect()->back()
            ->with('success', 'Price flagged for review.');
    }

    public function unflag(IngredientPrice $price)
{
    $price->update(['is_flagged' => false]);

    return redirect()->back()
        ->with('success', 'Price restored to active.');
}
}