@extends('layouts.sidebar')

@section('title', $ingredient->name)

@section('content')
@php $summary = $ingredient->getPriceSummary(); @endphp

<div class="bg-suBg min-h-screen pt-10">
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- Header --}}
        <div class="mb-8 flex items-start justify-between">
            <div>
                <h1 class="font-serif text-4xl font-bold text-suText mb-2">{{ $ingredient->name }}</h1>
                <p class="text-gray-500">Unit: {{ $ingredient->unit ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('ingredients.edit', $ingredient) }}"
                   class="bg-white border border-gray-200 text-suText px-5 py-2 rounded-full text-sm font-semibold hover:border-primary hover:text-primary transition">
                    Edit
                </a>
                <a href="{{ route('ingredients.index') }}"
                   class="bg-white border border-gray-200 text-gray-500 px-5 py-2 rounded-full text-sm font-semibold hover:border-primary hover:text-primary transition">
                    Back to Ingredients
                </a>
            </div>
        </div>

        {{-- Market Price block --}}
        <div class="bg-white rounded-2xl border border-gray-100 px-6 py-5 mb-8 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-semibold text-suText text-sm mb-1">Market Price</p>
                    @if ($summary)
                        <p class="text-2xl font-bold text-primary font-serif">
                            ₦{{ number_format($summary['min'], 0) }}
                            @if ($summary['min'] !== $summary['max'])
                                – ₦{{ number_format($summary['max'], 0) }}
                            @endif
                            <span class="text-sm font-normal text-gray-500">per {{ $ingredient->unit ?? 'unit' }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Based on {{ $summary['count'] }} {{ Str::plural('submission', $summary['count']) }} ·
                            Last updated {{ $summary['last_updated']?->diffForHumans() }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">No price data yet for this ingredient.</p>
                    @endif
                </div>

                @auth
                    @if (auth()->user()?->canManagePrices())
                        <a href="{{ route('ingredient-prices.create', $ingredient) }}"
                           class="shrink-0 bg-primary text-white px-5 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition">
                            Submit a Price
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Recipes using this ingredient --}}
        <div class="bg-white rounded-2xl border border-gray-100 px-6 py-5 shadow-sm">
            <p class="font-semibold text-suText text-sm mb-3">Used in Recipes</p>

            @if ($ingredient->recipes->isNotEmpty())
                <ul class="space-y-2">
                    @foreach ($ingredient->recipes as $recipe)
                        <li>
                            <a href="{{ route('recipes.show', $recipe) }}"
                               class="text-primary hover:underline text-sm">
                                {{ $recipe->title }}
                            </a>
                            <span class="text-xs text-gray-400">— {{ $recipe->pivot->quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Not used in any recipes yet.</p>
            @endif
        </div>

        {{-- Admin: individual price submissions --}}
@if (auth()->user()?->isAdmin())
@php $submissions = $ingredient->recentPriceSubmissions(); @endphp
<div class="bg-white rounded-2xl border border-gray-100 px-6 py-5 mb-8 shadow-sm">
    <p class="font-semibold text-suText text-sm mb-4">All Price Submissions (Admin)</p>

    @if ($submissions->isEmpty())
        <p class="text-sm text-gray-500">No submissions in the last 30 days.</p>
    @else
        <div class="space-y-2">
            @foreach ($submissions as $price)
                <div class="flex items-center justify-between px-4 py-3 rounded-xl border {{ $price->is_flagged ? 'border-red-200 bg-red-50' : 'border-gray-100' }}">
                    <div>
                        <p class="text-sm font-semibold text-suText">
                            ₦{{ number_format($price->price, 0) }} for {{ $price->unit_quantity }} {{ $price->unit }}
                            @if ($price->is_flagged)
                                <span class="text-xs text-red-500 font-normal ml-2">Flagged</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $price->market->name }} · submitted by {{ $price->user->name }} ·
                            {{ $price->submitted_at->diffForHumans() }}
                        </p>
                    </div>

                    @if ($price->is_flagged)
                        <form method="POST" action="{{ route('ingredient-prices.unflag', $price) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs border border-gray-300 text-gray-500 hover:border-primary hover:text-primary px-4 py-2 rounded-full transition">
                                Restore
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('ingredient-prices.flag', $price) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs border border-red-300 text-red-400 hover:bg-red-500 hover:text-white px-4 py-2 rounded-full transition">
                                Flag as Invalid
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endif

    </div>
</div>
@endsection