<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::withCount('prices')->latest()->get();
        return view('markets.index', compact('markets'));
    }

    public function create()
    {
        return view('markets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Market::create($request->only('name', 'location'));

        return redirect()->route('markets.index')
            ->with('success', 'Market added successfully!');
    }
}