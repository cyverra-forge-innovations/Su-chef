@extends('layouts.sidebar')

@section('content')

{{-- Header --}}
<div class="bg-suText py-16 px-6 text-center rounded-lg">
    <h1 class="font-serif text-5xl font-bold text-white mb-4">Ingredients</h1>
    <p class="text-gray-400 text-lg max-w-xl mx-auto">All ingredients available on Su-chef.</p>
    @auth
        <a href="{{ route('ingredients.create') }}" class="inline-block mt-8 bg-primary hover:bg-secondary text-white font-semibold px-8 py-3 rounded-full transition-all duration-200 hover:-translate-y-1">
            + Add New Ingredient
        </a>
    @endauth
</div>

{{-- Search Bar --}}
<div class="bg-white shadow-sm sticky top-16 z-40 px-6 py-4">
    <div class="max-w-4xl mx-auto">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
            <input
                type="text"
                id="ingredient-search"
                placeholder="Search ingredients..."
                class="w-full border border-gray-200 rounded-full pl-12 pr-6 py-3 text-sm focus:outline-none focus:border-primary"
                autocomplete="off"
            />
        </div>
    </div>
</div>

{{-- Ingredients List --}}
<section class="py-16 px-6 bg-suBg min-h-screen">
    <div class="max-w-4xl mx-auto">
        @if($grouped->count() > 0)

            <div id="ingredients-container">
                @foreach($grouped as $category => $ingredients)
                <div class="mb-10 ingredient-category-group">

                    {{-- Category Header --}}
                    <div class="flex items-center gap-4 mb-4">
                        <h2 class="font-serif text-xl font-bold text-suText">
                            {{ $category ?: 'Uncategorised' }}
                        </h2>
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-xs text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-100 ingredient-count">
                            {{ $ingredients->count() }} {{ Str::plural('ingredient', $ingredients->count()) }}
                        </span>
                    </div>

                    {{-- Ingredients in this category --}}
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        @foreach($ingredients as $ingredient)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-suBg transition-colors ingredient-row"
                             data-name="{{ strtolower($ingredient->name) }}">
                            <a href="{{ route('ingredients.show', $ingredient) }}" class="flex items-center gap-4 flex-1">

                                {{-- Letter avatar --}}
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center font-bold text-primary text-sm">
                                    {{ strtoupper(substr($ingredient->name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-semibold text-suText hover:text-primary transition-colors">{{ $ingredient->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $ingredient->unit ?? 'No unit specified' }}</p>
                                </div>
                            </a>

                            @auth
                            <div class="flex gap-2">
                                <a href="{{ route('ingredients.edit', $ingredient) }}"
                                   class="text-xs border border-primary text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-full transition-all duration-200">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('ingredients.destroy', $ingredient) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs border border-red-300 text-red-400 hover:bg-red-500 hover:text-white px-4 py-2 rounded-full transition-all duration-200">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endauth
                        </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>

            {{-- No results state (hidden by default) --}}
            <div id="no-results" class="text-center py-24 hidden">
                <div class="text-8xl mb-6">🔍</div>
                <h3 class="font-serif text-2xl font-bold text-suText mb-2">No matches found</h3>
                <p class="text-gray-500">Try a different search term.</p>
            </div>

        @else
            <div class="text-center py-24">
                <div class="text-8xl mb-6">🥕</div>
                <h3 class="font-serif text-3xl font-bold text-suText mb-4">No ingredients yet</h3>
                <p class="text-gray-500 mb-12">Add ingredients to use them in recipes!</p>
                @auth
                    <a href="{{ route('ingredients.create') }}" class="inline-block bg-primary hover:bg-secondary text-white font-bold px-12 py-4 text-lg rounded-full transition-all duration-200 hover:-translate-y-1 shadow-lg">
                        Add First Ingredient
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.getElementById('ingredient-search')?.addEventListener('input', function (e) {
        const term = e.target.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.ingredient-row');
        const groups = document.querySelectorAll('.ingredient-category-group');
        const noResults = document.getElementById('no-results');

        let anyVisible = false;

        groups.forEach(group => {
            let groupHasMatch = false;

            group.querySelectorAll('.ingredient-row').forEach(row => {
                const matches = row.dataset.name.includes(term);
                row.style.display = matches ? 'flex' : 'none';
                if (matches) {
                    groupHasMatch = true;
                    anyVisible = true;
                }
            });

            group.style.display = groupHasMatch ? 'block' : 'none';
        });

        noResults.classList.toggle('hidden', anyVisible || term === '');
    });
</script>
@endpush