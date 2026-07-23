@extends('layouts.sidebar')

@section('title', 'Add Market')

@section('content')
    <h2 class="text-2xl font-bold text-suText font-serif mb-8">Add a Market</h2>

    <div class="max-w-xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('markets.store') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-semibold text-suText mb-1">Market Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="e.g. Mile 12 Market"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-7">
                <label class="block text-sm font-semibold text-suText mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location') }}"
                       placeholder="e.g. Ketu, Lagos"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 @error('location') border-red-400 @enderror">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:opacity-90 transition">
                    Save Market
                </button>
                <a href="{{ route('markets.index') }}"
                   class="px-6 py-2.5 rounded-full text-sm font-semibold text-gray-500 hover:bg-suBg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection