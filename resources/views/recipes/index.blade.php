@extends('layouts.sidebar')

@section('content')

{{-- Page Header --}}
<div class="bg-suText py-16 px-6 text-center rounded-t-md">
    <h1 class="font-serif text-5xl font-bold text-white mb-4">All Recipes</h1>
    <p class="text-gray-400 text-lg max-w-xl mx-auto">Browse our collection of delicious recipes from our community of cooks.</p>
    @auth
        <a href="{{ route('recipes.create') }}" class="inline-block mt-8 bg-primary hover:bg-secondary text-white font-semibold px-8 py-3 rounded-full transition-all duration-200 hover:-translate-y-1">
            + Add New Recipe
        </a>
    @endauth
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('recipes.index') }}" class="bg-white shadow-sm sticky top-16 z-40 px-6 py-4">
    <div class="max-w-6xl mx-auto flex flex-wrap gap-4 items-center justify-between">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search recipes..."
            class="border border-gray-200 rounded-full px-6 py-2 text-sm w-full md:w-80 focus:outline-none focus:border-primary"
        />
        <div class="flex gap-3 flex-wrap items-center">
            <select name="category" class="border border-gray-200 rounded-full px-5 py-2 text-sm focus:outline-none focus:border-primary">
                <option value="all">All Categories</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->name }}" {{ request('category') === $category->name ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <select name="difficulty" class="border border-gray-200 rounded-full px-5 py-2 text-sm focus:outline-none focus:border-primary">
                <option value="all">All Difficulties</option>
                <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
            </select>

            {{-- Price range filter --}}
            <div class="flex items-center gap-2 border border-gray-200 rounded-full px-4 py-2">
                <span class="text-xs text-gray-400">₦</span>
                <input
                    type="number"
                    name="min_price"
                    value="{{ request('min_price') }}"
                    placeholder="Min"
                    min="0"
                    class="w-16 text-sm focus:outline-none"
                />
                <span class="text-gray-300">–</span>
                <input
                    type="number"
                    name="max_price"
                    value="{{ request('max_price') }}"
                    placeholder="Max"
                    min="0"
                    class="w-16 text-sm focus:outline-none"
                />
            </div>

            <button type="submit" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Search
            </button>
            @if(request('search') || request('category') || request('difficulty') || request('min_price') || request('max_price'))
                <a href="{{ route('recipes.index') }}" class="border border-gray-300 text-gray-500 hover:border-primary hover:text-primary px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                    Clear
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Recipes Grid --}}
<section class="py-16 px-6 bg-suBg min-h-screen">
    <div class="max-w-6xl mx-auto">
        @if($recipes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recipes as $recipe)
                @php $cost = $recipe->getEstimatedCost(); @endphp
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-200 group">
                    {{-- Recipe Image --}}
                    <div class="relative overflow-hidden h-52">
                        @if($recipe->image)
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="text-6xl">🍽️</span>
                            </div>
                        @endif
                        {{-- Difficulty Badge --}}
                        <span class="absolute top-3 right-3 text-xs font-semibold px-3 py-1 rounded-full
                            {{ $recipe->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $recipe->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $recipe->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($recipe->difficulty) }}
                        </span>
                        {{-- Cost Badge --}}
                        @if($cost)
                            <span class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full bg-white/90 text-suText">
                                ₦{{ number_format($cost['min'], 0) }}@if($cost['min'] !== $cost['max']) – ₦{{ number_format($cost['max'], 0) }}@endif
                                @unless($cost['complete'])
                                    <span class="text-gray-400">*</span>
                                @endunless
                            </span>
                        @endif
                    </div>

                    {{-- Recipe Info --}}
                    <div class="p-6">
                        {{-- Categories --}}
                        <div class="flex gap-2 flex-wrap mb-3">
                            @foreach($recipe->categories as $category)
                                <span class="text-xs bg-suBg text-secondary font-medium px-3 py-1 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>

                        <h3 class="font-serif text-xl font-bold text-suText mb-2 group-hover:text-primary transition-colors">
                            {{ $recipe->title }}
                        </h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                            {{ $recipe->description }}
                        </p>

                        {{-- Meta --}}
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-4">
                            <span>⏱ {{ $recipe->cook_time }} mins</span>
                            <span>👤 {{ $recipe->user->name }}</span>
                            <span>⭐ {{ $recipe->reviews->avg('rating') ? number_format($recipe->reviews->avg('rating'), 1) : 'No reviews' }}</span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3">
                            <a href="{{ route('recipes.show', $recipe) }}"
                               class="flex-1 text-center bg-primary hover:bg-secondary text-white text-sm font-semibold py-2 rounded-full transition-all duration-200">
                                View Recipe
                            </a>
                            @auth
                                <form method="POST" action="{{ route('favorites.store', $recipe) }}">
                                    @csrf
                                    <button type="submit" class="border border-primary text-primary hover:bg-primary hover:text-white w-10 h-10 rounded-full transition-all duration-200 flex items-center justify-center text-sm" onclick="$heartIcon">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-24">
                <div class="text-8xl mb-6">🍽️</div>
                <h3 class="font-serif text-3xl font-bold text-suText mb-4">No recipes yet</h3>
                <p class="text-gray-500 mb-12">Be the first to add a delicious recipe to Su-chef!</p>
                @auth
                    <a href="{{ route('recipes.create') }}" class="inline-block bg-primary hover:bg-secondary text-white font-bold px-12 py-5 text-lg rounded-full transition-all duration-200 hover:-translate-y-1 shadow-lg">
                        Add First Recipe
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-block bg-primary hover:bg-secondary text-white font-bold px-12 py-4 text-lg rounded-full transition-all duration-200 hover:-translate-y-1 shadow-lg">
                        Register to Add Recipes
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

@endsection