@extends('layouts.sidebar')

@section('content')
<!-- <button class="rounded-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4" onclick="window.history.back()"><i class="fa-solid fa-arrow-left-long"></i> Back</button> -->
<div class="bg-suBg min-h-screen rounded-md pt-10">
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="font-serif text-4xl font-bold text-suText mb-2">Add New Recipe</h1>
            <p class="text-gray-500">Share your delicious recipe with the Su-chef community.</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Title --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-semibold text-suText mb-2">Recipe Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Jollof Rice with Chicken"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-semibold text-suText mb-2">Description *</label>
                <textarea name="description" rows="4" placeholder="Describe your recipe briefly..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Instructions --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-semibold text-suText mb-2">Instructions *</label>
                <textarea name="instructions" rows="8" placeholder="Step-by-step instructions, one step per line..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary resize-none">{{ old('instructions') }}</textarea>
                @error('instructions') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Image --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-semibold text-suText mb-2">Recipe Image</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Cook Time & Difficulty --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-suText mb-2">Cook Time (minutes) *</label>
                    <input type="number" name="cook_time" value="{{ old('cook_time') }}" placeholder="e.g. 45"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                    @error('cook_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-suText mb-2">Difficulty *</label>
                    <select name="difficulty"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                        <option value="">Select difficulty</option>
                        <option value="easy" {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ old('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                    @error('difficulty') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Categories --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-semibold text-suText mb-4">Categories</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($categories as $category)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            class="w-4 h-4 accent-primary">
                        <span class="text-sm text-gray-600">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                @if($categories->count() === 0)
                    <p class="text-gray-400 text-sm">No categories yet. <a href="{{ route('categories.create') }}" class="text-primary">Add one first.</a></p>
                @endif
            </div>

            {{-- Ingredients --}}
<div class="bg-white rounded-2xl p-6 shadow-sm">
    <div class="flex justify-between items-center mb-4">
        <label class="block text-sm font-semibold text-suText">Ingredients</label>
        <button type="button" onclick="addIngredient()" class="text-xs bg-suBg hover:bg-primary hover:text-white text-primary border border-primary px-4 py-2 rounded-full transition-all duration-200">
            + Add Ingredient
        </button>
    </div>
    <div id="ingredients-list" class="space-y-3">
        <div class="flex gap-3 items-center ingredient-row">
            <div class="relative flex-1">
                <input type="text"
                       class="ingredient-search w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary"
                       placeholder="Type to search ingredient..."
                       autocomplete="off">
                <input type="hidden" name="ingredient_ids[]" class="ingredient-id-input">
                <div class="ingredient-dropdown absolute z-20 w-full bg-white border border-gray-200 rounded-xl mt-1 max-h-48 overflow-y-auto shadow-lg hidden">
                    @foreach($ingredients as $ingredient)
                        <div class="ingredient-option px-4 py-2 text-sm hover:bg-suBg cursor-pointer"
                             data-id="{{ $ingredient->id }}"
                             data-name="{{ $ingredient->name }} ({{ $ingredient->unit }})">
                            {{ $ingredient->name }} ({{ $ingredient->unit }})
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="number" name="quantities[]" placeholder="Quantity e.g. 2 cups"
                class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary">
            <button type="button" onclick="this.closest('.ingredient-row').remove()" class="text-red-400 hover:text-red-600 text-lg">✕</button>
        </div>
    </div>
    @if($ingredients->count() === 0)
        <p class="text-gray-400 text-sm mt-3">No ingredients yet. <a href="{{ route('ingredients.create') }}" class="text-primary">Add some first.</a></p>
    @endif
</div>

            {{-- Submit --}}
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-4 rounded-full text-lg transition-all duration-200 hover:-translate-y-1">
                    🍳 Publish Recipe
                </button>
                <a href="{{ route('recipes.index') }}" class="px-8 py-4 border border-gray-300 text-gray-500 hover:border-primary hover:text-primary rounded-full font-semibold transition-all duration-200">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function initIngredientRow(row) {
        const searchInput = row.querySelector('.ingredient-search');
        const hiddenInput = row.querySelector('.ingredient-id-input');
        const dropdown = row.querySelector('.ingredient-dropdown');
        const options = row.querySelectorAll('.ingredient-option');

        searchInput.addEventListener('focus', () => {
            dropdown.classList.remove('hidden');
        });

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            hiddenInput.value = ''; // clear selection if they keep typing
            let anyVisible = false;

            options.forEach(opt => {
                const matches = opt.dataset.name.toLowerCase().includes(term);
                opt.style.display = matches ? 'block' : 'none';
                if (matches) anyVisible = true;
            });

            dropdown.classList.toggle('hidden', !anyVisible);
        });

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                searchInput.value = opt.dataset.name;
                hiddenInput.value = opt.dataset.id;
                dropdown.classList.add('hidden');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!row.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    function addIngredient() {
        const list = document.getElementById('ingredients-list');
        const row = document.querySelector('.ingredient-row').cloneNode(true);

        // Reset values in the clone
        row.querySelector('.ingredient-search').value = '';
        row.querySelector('.ingredient-id-input').value = '';
        row.querySelectorAll('input[type="number"], input[type="text"][name="quantities[]"]').forEach(el => el.value = '');
        row.querySelectorAll('.ingredient-option').forEach(opt => opt.style.display = 'block');
        row.querySelector('.ingredient-dropdown').classList.add('hidden');

        list.appendChild(row);
        initIngredientRow(row);
    }

    // Initialize the first row on page load
    document.querySelectorAll('.ingredient-row').forEach(initIngredientRow);
</script>
@endpush