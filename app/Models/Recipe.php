<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'instructions', 'cook_time', 'difficulty', 'image',
    ];

    // A recipe belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A recipe has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // A recipe has many favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // A recipe belongs to many ingredients (via recipe_ingredients)
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')->withPivot('quantity');
    }

    // A recipe belongs to many categories (via recipe_categories)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'recipe_categories');
    }

    public function getEstimatedCost(): ?array
    {
        $totalMin = 0;
        $totalMax = 0;
        $missing = [];
        $hasAny = false;

        foreach ($this->ingredients as $ingredient) {
            $summary = $ingredient->getPriceSummary();
            $qty = is_numeric($ingredient->pivot->quantity) ? (float) $ingredient->pivot->quantity : null;

            if ($summary && $qty) {
                $totalMin += $summary['min'] * $qty;
                $totalMax += $summary['max'] * $qty;
                $hasAny = true;
            } else {
                $missing[] = $ingredient->name;
            }
        }

        if (! $hasAny) {
            return null;
        }

        return [
            'min'      => round($totalMin, 0),
            'max'      => round($totalMax, 0),
            'complete' => empty($missing),
            'missing'  => $missing,
        ];
    }
}