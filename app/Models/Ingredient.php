<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\IngredientPrice;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'unit', 'category'
    ];

    // An ingredient belongs to many recipes (via recipe_ingredients)
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')->withPivot('quantity');
    }

    // An ingredient belongs to many shopping list items
    public function shoppingListItems()
    {
        return $this->hasMany(ShoppingListItem::class);
    }

    // Add these relationships/helpers to your existing Ingredient model
    public function prices()
    {
        return $this->hasMany(IngredientPrice::class);
    }

    public function getPriceSummary(int $days = 30): ?array
    {
        $prices = $this->prices()
            ->active()
            ->recent($days)
            ->get()
            ->map(fn ($p) => $p->price_per_unit);

        if ($prices->isEmpty()) {
            return null;
        }

        $latest = $this->prices()
            ->active()
            ->recent($days)
            ->latest('submitted_at')
            ->first();

        return [
            'min'        => $prices->min(),
            'max'        => $prices->max(),
            'avg'        => round($prices->avg(), 2),
            'count'      => $prices->count(),
            'last_updated' => $latest?->submitted_at,
        ];
    }

    public function recentPriceSubmissions(int $days = 30)
    {
        return $this->prices()
            ->with(['market', 'user'])
            ->recent($days)
            ->latest('submitted_at')
            ->get();
    }
}