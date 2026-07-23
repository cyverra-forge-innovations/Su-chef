<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A user has many recipes
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    // A user has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // A user has many favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // A user has many shopping lists
    public function shoppingLists()
    {
        return $this->hasMany(ShoppingList::class);
    }

    // A user has one preference
    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function isMarketWoman(): bool
    {
        return $this->role === 'market_woman';
    }

    public function isPendingMarketWoman(): bool
    {
        return $this->role === 'pending_market_woman';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canManagePrices(): bool
    {
        return in_array($this->role, ['market_woman', 'admin']);
    }
}