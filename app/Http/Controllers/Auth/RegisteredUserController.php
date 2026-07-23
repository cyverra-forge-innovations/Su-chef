<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'wants_market_woman' => ['nullable', 'boolean'],
        ]);

        $isPendingMarketWoman = $request->boolean('wants_market_woman');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isPendingMarketWoman ? 'pending_market_woman' : 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($isPendingMarketWoman) {
            return redirect(route('dashboard', absolute: false))
                ->with('success', 'Welcome to Su-chef! Your market-woman access is pending admin approval — you can browse and cook in the meantime.');
        }

        return redirect(route('dashboard', absolute: false));
    }
}