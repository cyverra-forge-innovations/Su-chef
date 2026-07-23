<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    protected $fillable = ['name', 'location'];

    public function prices(): HasMany
    {
        return $this->hasMany(IngredientPrice::class);
    }
}