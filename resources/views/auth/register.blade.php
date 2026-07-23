<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Account type --}}
        <div class="pt-2">
            <x-input-label :value="__('Account Type')" />
            <p class="text-xs text-suText mb-3">Choose how you'll use Su-chef.</p>

            <div class="space-y-2">
                <label class="flex items-start gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input type="radio" name="wants_market_woman" value="0" checked
                           class="mt-1 text-primary focus:ring-primary">
                    <span>
                        <span class="block text-sm font-semibold text-suText">Home Cook</span>
                        <span class="block text-xs text-suText">Browse recipes, save favourites, build shopping lists.</span>
                    </span>
                </label>

                <label class="flex items-start gap-3 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input type="radio" name="wants_market_woman" value="1"
                           class="mt-1 text-primary focus:ring-primary">
                    <span>
                        <span class="block text-sm font-semibold text-suText">Market Woman</span>
                        <span class="block text-xs text-suText">Submit real ingredient prices from your market. Subject to admin approval.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="underline text-sm text-primary hover:opacity-80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>