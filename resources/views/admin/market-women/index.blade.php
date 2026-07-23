@extends('layouts.sidebar')

@section('content')

<div class="bg-suText py-12 px-6 text-center">
    <h1 class="font-serif text-4xl font-bold text-white">Market Woman Approvals</h1>
    <p class="text-gray-400 mt-2">Review and approve pending market-woman registrations.</p>
</div>

<section class="py-12 px-6 bg-suBg min-h-screen">
    <div class="max-w-3xl mx-auto">

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($pending->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <p class="text-lg">No pending requests.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pending as $user)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-suText">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">Requested {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.market-women.approve', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="bg-primary text-white px-5 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.market-women.deny', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="border border-gray-300 text-gray-500 px-5 py-2 rounded-full text-sm font-semibold hover:border-red-300 hover:text-red-500 transition">
                                    Deny
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

@endsection