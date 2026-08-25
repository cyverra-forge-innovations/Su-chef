@extends('layouts.sidebar')

@section('content')
<button class="rounded-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4" onclick="window.history.back()"><i class="fa-solid fa-arrow-left-long"></i> Back</button>
{{-- Recipe Hero --}}
<div class="relative h-96 overflow-hidden rounded-lg mt-8">
    @if($recipe->image)
        <img src="{{ Storage::disk('neon')->url($recipe->image) }}" alt="{{ $recipe->title }}"
        class="w-full h-full object-cover">
    @else
        <div class="w-full h-full bg-gradient-to-br from-primary/30 to-secondary/30 flex items-center justify-center">
            <span class="text-9xl">🍽️</span>
        </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
    <div class="absolute bottom-8 left-8 right-8 text-white">
        <div class="flex gap-2 mb-3 flex-wrap">
            @foreach($recipe->categories as $category)
                <span class="bg-secondary/80 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $category->name }}</span>
            @endforeach
        </div>
        <h1 class="font-serif text-5xl font-bold mb-2">{{ $recipe->title }}</h1>
        <div class="flex gap-6 text-sm text-white/80">
            <span>⏱ {{ $recipe->cook_time }} mins</span>
            <span>📊 {{ ucfirst($recipe->difficulty) }}</span>
            <span>👤 {{ $recipe->user->name }}</span>
            <span>⭐ {{ $recipe->reviews->avg('rating') ? number_format($recipe->reviews->avg('rating'), 1) . '/5' : 'No reviews yet' }}</span>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="bg-suBg py-16 px-6">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Description --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="font-serif text-2xl font-bold text-suText mb-4">About this Recipe</h2>
                <p class="text-gray-600 leading-relaxed">{{ $recipe->description }}</p>
            </div>

            {{-- Instructions --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="font-serif text-2xl font-bold text-suText mb-6">Instructions</h2>
                @if($recipe->instructions)
                    <div class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $recipe->instructions }}</div>
                @else
                    <p class="text-gray-400">No instructions added yet.</p>
                @endif
            </div>

            {{-- Reviews --}}
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="font-serif text-2xl font-bold text-suText mb-6">Reviews ({{ $recipe->reviews->count() }})</h2>

                @auth
                {{-- Add Review Form --}}
                <form method="POST" action="{{ route('reviews.store', $recipe) }}" class="mb-8 pb-8 border-b border-gray-100">
                    @csrf
                    <h3 class="font-semibold text-suText mb-4">Leave a Review</h3>

                    <div class="flex gap-1 mb-4" x-data="{ rating: 0, hover: 0 }">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                       @change="rating = {{ $i }}" required>
                                <span class="text-3xl inline-block transition-transform hover:scale-110"
                                      @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0"
                                      :class="(hover || rating) >= {{ $i }} ? 'opacity-100' : 'opacity-25'">⭐</span>
                            </label>
                        @endfor
                    </div>

                    <textarea name="comment" rows="3" placeholder="Share your experience with this recipe..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary resize-none mb-4"></textarea>
                    <button type="submit" class="bg-primary hover:bg-secondary text-white font-semibold px-6 py-2 rounded-full transition-all duration-200">
                        Submit Review
                    </button>
                </form>
                @endauth

                {{-- Reviews List --}}
                @forelse($recipe->reviews as $review)
                <div class="py-4 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-semibold text-suText text-sm">{{ $review->user->name }}</span>
                            <span class="ml-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-base {{ $i <= $review->rating ? 'opacity-100' : 'opacity-20' }}">⭐</span>
                                @endfor
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    @if(auth()->id() === $review->user_id)
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-2">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    @endif
                </div>
                @empty
                    <p class="text-gray-400 text-sm">No reviews yet. Be the first to review this recipe!</p>
                @endforelse
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">

            {{-- Ingredients --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-serif text-xl font-bold text-suText mb-4">Ingredients</h2>
                @if($recipe->ingredients->count() > 0)
                    <ul class="space-y-3">
                        @foreach($recipe->ingredients as $ingredient)
                        <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <span class="text-gray-700 text-sm font-medium">{{ $ingredient->name }}</span><span class="text-gray-400 text-sm">{{ $ingredient->pivot->quantity }} {{ $ingredient->unit }}</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400 text-sm">No ingredients listed.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm space-y-3">
                <h2 class="font-serif text-xl font-bold text-suText mb-4">Actions</h2>
                @auth
                    <form method="POST" action="{{ route('favorites.store', $recipe) }}">
                        @csrf
                        <button class="w-full bg-primary hover:bg-secondary text-white font-semibold py-3 rounded-full transition-all duration-200">
                            ❤ Save to Favourites
                        </button>
                    </form>
                    {{-- Generate Shopping List --}}
                    <form method="POST" action="{{ route('shopping-lists.generate', $recipe) }}">
                        @csrf
                        <button class="w-full bg-suText hover:bg-gray-800 text-white font-semibold py-3 rounded-full transition-all duration-200">
                            <i class="fa-solid fa-cart-shopping mr-2"></i> Generate Shopping List
                        </button>
                    </form>
                    {{-- Download/Print Shopping List --}}
                    <!-- <button onclick="window.print()" class="w-full border border-suText text-suText hover:bg-suText hover:text-white font-semibold py-3 rounded-full transition-all duration-200">
                        <i class="fa-solid fa-print mr-2"></i> Print Ingredients
                    </button> -->
                    @if(auth()->id() === $recipe->user_id)
                        <a href="{{ route('recipes.edit', $recipe) }}" class="block w-full text-center border border-primary text-primary hover:bg-primary hover:text-white font-semibold py-3 rounded-full transition-all duration-200">
                            ✏ Edit Recipe
                        </a>
                        <form method="POST" action="{{ route('recipes.destroy', $recipe) }}">
                            @csrf @method('DELETE')
                            <button class="w-full border border-red-300 text-red-400 hover:bg-red-500 hover:text-white font-semibold py-3 rounded-full transition-all duration-200">
                                🗑 Delete Recipe
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-primary hover:bg-secondary text-white font-semibold py-3 rounded-full transition-all duration-200">
                        Login to Save Recipe
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
<style>
    @media print {
        nav, footer, .actions-sidebar, form { display: none !important; }
        .print-section { display: block !important; }
    }
</style>
@endpush