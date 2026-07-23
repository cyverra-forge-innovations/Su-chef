@extends('layouts.sidebar')

@section('content')
<button class="rounded-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4" onclick="window.history.back()"><i class="fa-solid fa-arrow-left-long"></i> Back</button>
<div class="bg-suBg min-h-screen">
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- Header (hidden when printing) --}}
        <div class="flex justify-between items-start mb-10 no-print">
            <div>
                <h1 class="font-serif text-4xl font-bold text-suText mb-2">{{ $shoppingList->name }}</h1>
                <p class="text-gray-500">{{ $shoppingList->items->count() }} {{ Str::plural('item', $shoppingList->items->count()) }} in this list</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('shopping-lists.index') }}" class="border border-gray-300 text-gray-500 hover:border-primary hover:text-primary px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back
                </a>
                <button onclick="printList()" class="bg-suText hover:bg-gray-800 text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                    <i class="fa-solid fa-print mr-1"></i> Print List
                </button>
                <form method="POST" action="{{ route('shopping-lists.destroy', $shoppingList) }}">
                    @csrf @method('DELETE')
                    <button class="border border-red-300 text-red-400 hover:bg-red-500 hover:text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-trash mr-1"></i> Delete List
                    </button>
                </form>
            </div>
        </div>

        {{-- Progress Bar (hidden when printing) --}}
        @php
            $total = $shoppingList->items->count();
            $checked = $shoppingList->items->where('is_checked', true)->count();
            $percentage = $total > 0 ? round(($checked / $total) * 100) : 0;
        @endphp
        <div class="bg-white rounded-2xl p-6 shadow-sm mb-6 no-print">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-suText">Shopping Progress</span>
                <span class="text-sm text-gray-400">{{ $checked }}/{{ $total }} items</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3">
                <div class="bg-primary h-3 rounded-full transition-all duration-500 w-[{{ $percentage }}%]"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $percentage }}% complete</p>
        </div>

        {{-- ── PRINT AREA ── --}}
        <div id="print-area">

            {{-- Print Header --}}
            <div class="print-header" style="display:none;">
                <div style="text-align:center; border-bottom: 2px solid #C0392B; padding-bottom: 16px; margin-bottom: 24px;">
                    <h1 style="font-family: serif; font-size: 28px; font-weight: bold; color: #2C1810;">Su-chef</h1>
                    <h2 style="font-size: 20px; font-weight: bold; margin-top: 8px;">{{ $shoppingList->name }}</h2>
                    <p style="color: #666; font-size: 13px; margin-top: 4px;">{{ $shoppingList->items->count() }} items — Generated on {{ now()->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Items List --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                @if($shoppingList->items->count() > 0)
                    @foreach($shoppingList->items as $item)
                    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-suBg transition-colors {{ $item->is_checked ? 'opacity-60' : '' }}">
                        {{-- Toggle Checkbox (hidden when printing) --}}
                        <form method="POST" action="{{ route('shopping-lists.toggle', $item) }}" class="no-print">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-6 h-6 rounded-full border-2 {{ $item->is_checked ? 'bg-primary border-primary' : 'border-gray-300' }} flex items-center justify-center transition-all duration-200">
                                @if($item->is_checked)
                                    <span class="text-white text-xs">✓</span>
                                @endif
                            </button>
                        </form>

                        {{-- Print Checkbox --}}
                        <div class="print-only" style="display:none; width:20px; height:20px; border:2px solid #C0392B; border-radius:50%; flex-shrink:0;">
                        </div>

                        {{-- Item Details --}}
                        <div class="flex-1">
                            <p class="font-medium text-suText {{ $item->is_checked ? 'line-through text-gray-400' : '' }}">
                                {{ $item->ingredient->name }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $item->quantity }}</p>
                        </div>

                        {{-- Status Badge --}}
                        <span class="text-xs font-semibold px-3 py-1 rounded-full no-print {{ $item->is_checked ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ $item->is_checked ? 'Got it!' : 'Needed' }}
                        </span>
                    </div>
                    @endforeach
                @else
                {{-- Inside the shopping list show view, after the list header --}}

@php
    $totalMin = 0;
    $totalMax = 0;
    $hasAnyPrice = false;
    $missingPrices = [];
@endphp

<div class="space-y-3 mb-6">
    @foreach ($shoppingList->items as $item)
        @php
            $summary = $item->ingredient->getPriceSummary();
            $qty = is_numeric($item->quantity) ? (float) $item->quantity : null;
        @endphp

        <div class="bg-white rounded-2xl border border-[#E8D5C4] px-5 py-4 flex items-start justify-between gap-4">

            {{-- Checkbox + name --}}
            <div class="flex items-start gap-3">
                <form method="POST" action="{{ route('shopping-lists.toggle', $item) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition
                                {{ $item->is_checked ? 'bg-[#C1440E] border-[#C1440E]' : 'border-[#D4B8A8]' }}">
                        @if ($item->is_checked)
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @endif
                    </button>
                </form>

                <div>
                    <p class="font-semibold text-sm text-[#5C3D2E] {{ $item->is_checked ? 'line-through text-[#B0957E]' : '' }}">
                        {{ $item->ingredient->name }}
                    </p>
                    <p class="text-xs text-[#8B6F5E]">{{ $item->quantity }}</p>
                </div>
            </div>

            {{-- Price estimate --}}
            <div class="text-right text-sm shrink-0">
                @if ($summary && $qty)
                    @php
                        $lineMin = round($summary['min'] * $qty, 0);
                        $lineMax = round($summary['max'] * $qty, 0);
                        $totalMin += $lineMin;
                        $totalMax += $lineMax;
                        $hasAnyPrice = true;
                    @endphp
                    <p class="font-semibold text-[#C1440E]">
                        ₦{{ number_format($lineMin, 0) }}
                        @if ($lineMin !== $lineMax) – ₦{{ number_format($lineMax, 0) }} @endif
                    </p>
                    <p class="text-xs text-[#8B6F5E]">
                        {{ $summary['count'] }} {{ Str::plural('submission', $summary['count']) }}
                    </p>
                @elseif ($summary)
                    <p class="text-xs text-[#8B6F5E]">Price available<br>qty not numeric</p>
                @else
                    @php $missingPrices[] = $item->ingredient->name; @endphp
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-[#B0957E]">No price data</span>
                        @if (auth()->user()?->canManagePrices())
    <a href="{{ route('ingredient-prices.create', $item->ingredient) }}"
       class="text-xs text-primary underline hover:no-underline">
        Submit price
    </a>
@else
    <span class="text-xs text-gray-400">No price data</span>
@endif
                    </div>
                @endif
            </div>

        </div>
    @endforeach
</div>

{{-- Total cost estimate --}}
@if ($hasAnyPrice)
    <div class="bg-[#FFF8F2] border border-[#E8D5C4] rounded-2xl px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-[#5C3D2E] text-sm">Estimated Total</p>
                @if (!empty($missingPrices))
                    <p class="text-xs text-[#8B6F5E] mt-0.5">
                        Excludes: {{ implode(', ', $missingPrices) }} (no price data yet)
                    </p>
                @endif
            </div>
            <p class="text-2xl font-bold text-[#C1440E]" style="font-family: Georgia, serif;">
                ₦{{ number_format($totalMin, 0) }}
                @if ($totalMin !== $totalMax) – ₦{{ number_format($totalMax, 0) }} @endif
            </p>
        </div>
    </div>
@endif
                    <div class="text-center py-16">
                        <i class="fa-solid fa-cart-shopping text-6xl text-gray-200 mb-4"></i>
                        <p class="text-gray-400">No items in this list yet.</p>
                    </div>
                @endif
            </div>

            {{-- Print Footer --}}
            <div class="print-header" style="display:none; margin-top: 30px; text-align:center;">
                <p style="color: #999; font-size: 12px;">Printed from Su-chef — Cook With What You Have</p>
            </div>
        </div>

        {{-- All Done Message (hidden when printing) --}}
        @if($total > 0 && $checked === $total)
        <div class="mt-6 bg-green-50 border border-green-200 rounded-2xl p-6 text-center no-print">
            <i class="fa-solid fa-circle-check text-4xl text-green-500 mb-2"></i>
            <h3 class="font-serif text-xl font-bold text-green-700">All done!</h3>
            <p class="text-green-600 text-sm mt-1">You've got everything on your list. Time to cook!</p>
        </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
    @media print {
        /* Hide the entire page */
        body > * { display: none !important; }
        
        /* Hide sidebar specifically */
        aside { display: none !important; }
        
        /* Reset main margin */
        main { margin-left: 0 !important; }

        /* Hide everything in main except print area */
        main > * { display: none !important; }

        /* Show only the print area */
        main #print-area { display: block !important; }
        
        /* Hide no-print elements inside print area */
        .no-print { display: none !important; }

        /* Show print-only elements */
        .print-header { display: block !important; }
        .print-only { display: flex !important; }

        /* Clean styling */
        body { background: white !important; }
        #print-area { padding: 40px !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    function printList() {
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>{{ $shoppingList->name }}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: Arial, sans-serif; padding: 40px; color: #2C1810; background: white; }
                    .header { text-align: center; border-bottom: 3px solid #C0392B; padding-bottom: 20px; margin-bottom: 30px; }
                    .logo { font-family: Georgia, serif; font-size: 32px; font-weight: bold; color: #2C1810; }
                    .logo span { color: #E67E22; }
                    .list-name { font-size: 20px; font-weight: bold; margin-top: 8px; }
                    .meta { color: #666; font-size: 13px; margin-top: 6px; }
                    .item { display: flex; align-items: center; gap: 16px; padding: 14px 0; border-bottom: 1px solid #eee; }
                    .circle { width: 22px; height: 22px; border: 2px solid #C0392B; border-radius: 50%; flex-shrink: 0; }
                    .item-name { font-weight: 600; font-size: 15px; }
                    .item-qty { font-size: 12px; color: #999; margin-top: 3px; }
                    .footer { margin-top: 40px; text-align: center; color: #aaa; font-size: 12px; border-top: 1px solid #eee; padding-top: 16px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="logo">Su<span>-chef</span></div>
                    <div class="list-name">{{ $shoppingList->name }}</div>
                    <div class="meta">{{ $shoppingList->items->count() }} items &mdash; Generated on {{ now()->format('d M Y') }}</div>
                </div>
                @foreach($shoppingList->items as $item)
                <div class="item">
                    <div class="circle"></div>
                    <div>
                        <div class="item-name">{{ $item->ingredient->name }}</div>
                        <div class="item-qty">{{ $item->quantity }}</div>
                    </div>
                </div>
                @endforeach
                <div class="footer">Printed from Su-chef &mdash; Cook With What You Have</div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() { window.close(); }
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush