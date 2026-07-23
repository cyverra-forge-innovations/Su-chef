@extends('layouts.sidebar')

@section('title', 'Submit a Price')

@section('content')
    <h2 class="text-2xl font-bold text-suText font-serif mb-8">
        Submit a Price — {{ $ingredient->name }}
    </h2>

    <div class="max-w-xl space-y-6">

        @if ($summary)
            <div class="bg-suBg border border-gray-100 rounded-2xl px-6 py-4 text-sm">
                <p class="font-semibold mb-1 text-suText">Current market price for {{ $ingredient->name }}</p>
                <p class="text-2xl font-bold text-primary">
                    ₦{{ number_format($summary['min'], 0) }}
                    @if ($summary['min'] !== $summary['max']) – ₦{{ number_format($summary['max'], 0) }} @endif
                    <span class="text-base font-normal text-gray-500">per {{ $ingredient->unit ?? 'unit' }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Based on {{ $summary['count'] }} {{ Str::plural('submission', $summary['count']) }} ·
                    Last updated {{ $summary['last_updated']?->diffForHumans() ?? '—' }}
                </p>
            </div>
        @else
            <div class="bg-suBg border border-gray-100 rounded-2xl px-6 py-4 text-sm text-gray-500">
                No recent price data for <strong class="text-suText">{{ $ingredient->name }}</strong>. Be the first to submit one!
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form method="POST" action="{{ route('ingredient-prices.store', $ingredient) }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-suText mb-1">Market</label>
                    @if ($markets->isEmpty())
                        <p class="text-sm text-gray-500">
                            No markets available yet.
                            <a href="{{ route('markets.create') }}" class="text-primary underline">Add one first.</a>
                        </p>
                    @else
                        <select name="market_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('market_id') border-red-400 @enderror">
                            <option value="">— Select a market —</option>
                            @foreach ($markets as $market)
                                <option value="{{ $market->id }}" {{ old('market_id') == $market->id ? 'selected' : '' }}>
                                    {{ $market->name }} ({{ $market->location }})
                                </option>
                            @endforeach
                        </select>
                        @error('market_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-suText mb-1">Price</label>
                    <p class="text-xs text-gray-500 mb-2">Enter the price you paid and how many units it covered. E.g. ₦1000 for 2 cups.</p>
                    <div class="flex gap-3 items-start">
                        <div class="flex-1">
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-sm text-gray-500">₦</span>
                                <input type="number" name="price" value="{{ old('price') }}"
                                       placeholder="500" min="1" step="0.01"
                                       class="w-full border border-gray-200 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('price') border-red-400 @enderror">
                            </div>
                            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <span class="text-sm text-gray-500 pt-2.5">for</span>

                        <div class="w-20">
                            <input type="number" name="unit_quantity" value="{{ old('unit_quantity', 1) }}"
                                   placeholder="1" min="0.01" step="0.01"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('unit_quantity') border-red-400 @enderror">
                            @error('unit_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex-1">
                            <input type="text" name="unit" value="{{ old('unit', $ingredient->unit) }}"
                                   placeholder="cup, kg, piece…"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('unit') border-red-400 @enderror">
                            @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-7">
                    <button type="submit"
                            class="bg-primary text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:opacity-90 transition">
                        Submit Price
                    </button>
                    <a href="{{ route('ingredients.show', $ingredient) }}"
                       class="px-6 py-2.5 rounded-full text-sm font-semibold text-gray-500 hover:bg-suBg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection