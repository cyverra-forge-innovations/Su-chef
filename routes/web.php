<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\IngredientPriceController;
use App\Http\Controllers\Admin\MarketWomanApprovalController;

// Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Public Routes ────────────────────────────────────────────────────────────

// Recipes
Route::get('recipes', [RecipeController::class, 'index'])->name('recipes.index');

// Categories
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');


// ── Protected Routes ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Smart Ingredient Matching
    Route::get('match', [RecipeController::class, 'match'])->name('recipes.match');
    Route::post('match', [RecipeController::class, 'match'])->name('recipes.match.post');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recipes
    Route::get('recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // Categories
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Ingredients
    Route::get('ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::get('ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create');
    Route::post('ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('ingredients/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
    Route::get('ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
    Route::put('ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

    // Reviews
    Route::post('recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Favorites
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('favorites/{recipe}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{recipe}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Shopping Lists
    Route::get('shopping-lists', [ShoppingListController::class, 'index'])->name('shopping-lists.index');
    Route::get('shopping-lists/create', [ShoppingListController::class, 'create'])->name('shopping-lists.create');
    Route::post('shopping-lists', [ShoppingListController::class, 'store'])->name('shopping-lists.store');
    Route::get('shopping-lists/{shoppingList}', [ShoppingListController::class, 'show'])->name('shopping-lists.show');
    Route::delete('shopping-lists/{shoppingList}', [ShoppingListController::class, 'destroy'])->name('shopping-lists.destroy');
    Route::patch('shopping-lists/items/{item}/toggle', [ShoppingListController::class, 'toggleItem'])->name('shopping-lists.toggle');
    // Generate shopping list from recipe
    Route::post('shopping-lists/generate/{recipe}', [ShoppingListController::class, 'generateFromRecipe'])->name('shopping-lists.generate');

    // User Preferences
    Route::get('preferences', [UserPreferenceController::class, 'index'])->name('preferences.index');
    Route::get('preferences/edit', [UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::put('preferences', [UserPreferenceController::class, 'update'])->name('preferences.update');

    // Markets — viewing is open to all logged-in users
    Route::get('markets', [MarketController::class, 'index'])->name('markets.index');

    // Market Woman / Admin gated — submitting prices and adding markets
    Route::middleware('can-manage-prices')->group(function () {
        Route::get('markets/create', [MarketController::class, 'create'])->name('markets.create');
        Route::post('markets', [MarketController::class, 'store'])->name('markets.store');
        Route::get('ingredients/{ingredient}/prices/submit', [IngredientPriceController::class, 'create'])->name('ingredient-prices.create');
        Route::post('ingredients/{ingredient}/prices', [IngredientPriceController::class, 'store'])->name('ingredient-prices.store');
    });

    // Admin-only — moderation and approvals
    Route::middleware('is-admin')->group(function () {
        Route::patch('ingredient-prices/{price}/flag', [IngredientPriceController::class, 'flag'])->name('ingredient-prices.flag');
        Route::patch('ingredient-prices/{price}/unflag', [IngredientPriceController::class, 'unflag'])->name('ingredient-prices.unflag');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('market-women', [MarketWomanApprovalController::class, 'index'])->name('market-women.index');
            Route::patch('market-women/{user}/approve', [MarketWomanApprovalController::class, 'approve'])->name('market-women.approve');
            Route::patch('market-women/{user}/deny', [MarketWomanApprovalController::class, 'deny'])->name('market-women.deny');
        });
    });

});

// ── Public Show Routes (must come AFTER protected create routes) ──────────────
Route::get('recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

require __DIR__.'/auth.php';