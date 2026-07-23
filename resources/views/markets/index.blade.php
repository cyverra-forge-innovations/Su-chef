@extends('layouts.sidebar')

@section('title', 'Markets')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-suText font-serif">Markets</h2>
        @if (auth()->user()?->canManagePrices())
    <a href="{{ route('markets.create') }}"
       class="bg-primary text-white px-5 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition">
        + Add Market
    </a>
@endif
    </div>

    @if ($markets->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p class="text-lg mb-4">No markets added yet.</p>
            <a href="{{ route('markets.create') }}"
               class="bg-primary text-white px-6 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition">
                Add the first market
            </a>
        </div>
    @else
        <div class="grid gap-4">
            @foreach ($markets as $market)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-suText text-base font-serif">{{ $market->name }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $market->location }}</p>
                    </div>
                    <span class="text-xs text-gray-500 bg-suBg px-3 py-1 rounded-full">
                        {{ $market->prices_count }} {{ Str::plural('price', $market->prices_count) }} submitted
                    </span>
                </div>
            @endforeach
        </div>
    @endif
@endsection