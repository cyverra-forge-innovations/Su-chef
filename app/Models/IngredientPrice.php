<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientPrice extends Model
{
    protected $fillable = [
        'ingredient_id', 'market_id', 'user_id',
        'price', 'unit', 'unit_quantity',
        'is_archived', 'archived_at',
        'is_flagged', 'submitted_at',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'is_flagged'  => 'boolean',
        'submitted_at' => 'datetime',
        'archived_at'  => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /** Only active (not archived, not flagged) submissions */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false)
                      ->where('is_flagged', false);
    }

    /** Submissions from the last N days */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    /**
     * Price normalised to 1 unit, so submissions like
     * "₦1000 for 2 cups" compare fairly with "₦600 for 1 cup".
     */
    public function getPricePerUnitAttribute(): float
    {
        return $this->unit_quantity > 0
            ? $this->price / $this->unit_quantity
            : $this->price;
    }
}