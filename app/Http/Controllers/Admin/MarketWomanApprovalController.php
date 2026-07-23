<?php
// app/Http/Controllers/Admin/MarketWomanApprovalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class MarketWomanApprovalController extends Controller
{
    public function index()
    {
        $pending = User::where('role', 'pending_market_woman')->latest()->get();
        return view('admin.market-women.index', compact('pending'));
    }

    public function approve(User $user)
    {
        $user->update(['role' => 'market_woman']);
        return back()->with('success', "{$user->name} approved as a market woman.");
    }

    public function deny(User $user)
    {
        $user->update(['role' => 'user']);
        return back()->with('success', "{$user->name}'s request was denied.");
    }
}