@extends('layouts.sidebar')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="font-serif text-3xl font-bold text-suText">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋
    </h1>
    <p class="text-gray-500 mt-1">Here's what's happening with your Su-chef account.</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex justify-between items-start mb-3">
            <i class="fa-solid fa-utensils text-2xl text-primary"></i>
            <span class="text-xs bg-primary/10 text-primary font-semibold px-2 py-1 rounded-full">Recipes</span>
        </div>
        <p class="text-3xl font-bold text-suText">{{ auth()->user()->recipes->count() }}</p>
        <p class="text-gray-400 text-sm mt-1">Created</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex justify-between items-start mb-3">
            <i class="fa-solid fa-heart text-2xl text-red-400"></i>
            <span class="text-xs bg-red-50 text-red-400 font-semibold px-2 py-1 rounded-full">Saved</span>
        </div>
        <p class="text-3xl font-bold text-suText">{{ auth()->user()->favorites->count() }}</p>
        <p class="text-gray-400 text-sm mt-1">Favourites</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex justify-between items-start mb-3">
            <i class="fa-solid fa-star text-2xl text-yellow-400"></i>
            <span class="text-xs bg-yellow-50 text-yellow-500 font-semibold px-2 py-1 rounded-full">Reviews</span>
        </div>
        <p class="text-3xl font-bold text-suText">{{ auth()->user()->reviews->count() }}</p>
        <p class="text-gray-400 text-sm mt-1">Given</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex justify-between items-start mb-3">
            <i class="fa-solid fa-cart-shopping text-2xl text-green-500"></i>
            <span class="text-xs bg-green-50 text-green-500 font-semibold px-2 py-1 rounded-full">Lists</span>
        </div>
        <p class="text-3xl font-bold text-suText">{{ auth()->user()->shoppingLists->count() }}</p>
        <p class="text-gray-400 text-sm mt-1">Shopping Lists</p>
    </div>
</div>

{{-- My Recipes --}}
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-serif text-xl font-bold text-suText">My Recipes</h2>
        <a href="{{ route('recipes.create') }}" class="bg-primary hover:bg-secondary text-white text-xs font-semibold px-5 py-2 rounded-full transition-all duration-200">
            <i class="fa-solid fa-plus mr-1"></i> Add Recipe
        </a>
    </div>
    @if(auth()->user()->recipes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(auth()->user()->recipes()->latest()->take(6)->get() as $recipe)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="relative h-36 overflow-hidden">
                    @if($recipe->image)
                        <img src="{{ Storage::disk('neon')->url($recipe->image) }}" alt="{{ $recipe->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                            <i class="fa-solid fa-utensils text-4xl text-primary/40"></i>
                        </div>
                    @endif
                    <span class="absolute top-2 right-2 text-xs font-semibold px-2 py-1 rounded-full
                        {{ $recipe->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $recipe->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $recipe->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($recipe->difficulty) }}
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="font-serif font-bold text-suText mb-1 group-hover:text-primary transition-colors text-sm">{{ $recipe->title }}</h3>
                    <p class="text-xs text-gray-400 mb-3"><i class="fa-regular fa-clock mr-1"></i> {{ $recipe->cook_time }} mins</p>
                    <div class="flex gap-2">
                        <a href="{{ route('recipes.show', $recipe) }}" class="flex-1 text-center bg-primary hover:bg-secondary text-white text-xs font-semibold py-1.5 rounded-full transition-all duration-200">
                            View
                        </a>
                        <a href="{{ route('recipes.edit', $recipe) }}" class="flex-1 text-center border border-primary text-primary hover:bg-primary hover:text-white text-xs font-semibold py-1.5 rounded-full transition-all duration-200">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-10 text-center shadow-sm">
            <i class="fa-solid fa-utensils text-5xl text-gray-200 mb-3"></i>
            <p class="text-gray-500 mb-4 text-sm">You haven't created any recipes yet.</p>
            <a href="{{ route('recipes.create') }}" class="inline-block bg-primary hover:bg-secondary text-white font-semibold px-6 py-2 rounded-full text-sm transition-all duration-200">
                Create Your First Recipe
            </a>
        </div>
    @endif
</div>

{{-- Quick Links --}}
<div>
    <h2 class="font-serif text-xl font-bold text-suText mb-4">Quick Links</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('favorites.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-heart text-2xl text-red-400"></i>
            </div>
            <div>
                <h3 class="font-semibold text-suText group-hover:text-primary transition-colors text-sm">My Favourites</h3>
                <p class="text-gray-400 text-xs mt-0.5">{{ auth()->user()->favorites->count() }} saved recipes</p>
            </div>
        </a>
        <a href="{{ route('shopping-lists.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping text-2xl text-green-500"></i>
            </div>
            <div>
                <h3 class="font-semibold text-suText group-hover:text-primary transition-colors text-sm">Shopping Lists</h3>
                <p class="text-gray-400 text-xs mt-0.5">{{ auth()->user()->shoppingLists->count() }} lists</p>
            </div>
        </a>
        <a href="{{ route('preferences.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex items-center gap-4 group">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-sliders text-2xl text-primary"></i>
            </div>
            <div>
                <h3 class="font-semibold text-suText group-hover:text-primary transition-colors text-sm">Preferences</h3>
                <p class="text-gray-400 text-xs mt-0.5">Personalise your experience</p>
            </div>
        </a>
    </div>
</div>

@endsection