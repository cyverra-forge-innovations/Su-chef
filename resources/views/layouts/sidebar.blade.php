<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Su-chef — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="bg-suBg font-sans text-suText">

    <div id="page-loader" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 text-white">
        <div class="flex flex-col items-center gap-3">
            <div class="h-16 w-16 rounded-full border-4 border-white/10 border-t-white animate-spin"></div>
            <span class="text-sm uppercase tracking-[0.3em]">Loading...</span>
        </div>
    </div>

    <div class="flex min-h-screen">

        {{-- ── Sidebar ── --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 h-full w-64 bg-suText text-white flex flex-col z-50 transform transition-transform duration-300 ease-in-out sm:translate-x-0">

            {{-- Logo --}}
            <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <a href="{{ route('home') }}" class="font-serif text-2xl font-bold text-white">
                        Su<span class="text-secondary">-chef</span>
                    </a>
                    <p class="text-xs text-gray-400 mt-1">Cook With What You Have</p>
                </div>

                <div class="sm:hidden">
                    <button @click="sidebarOpen = ! sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-secondary focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto scrollbar-thumb-primary scrollbar-none">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 px-2">Main</p>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-house w-4"></i> Dashboard
                </a>
                <a href="{{ route('recipes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('recipes.index') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-utensils w-4"></i> All Recipes
                </a>
                <a href="{{ route('recipes.match') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('recipes.match*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-wand-magic-sparkles w-4"></i> Smart Match
                </a>
                <a href="{{ route('recipes.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('recipes.create') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-plus w-4"></i> Add Recipe
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('categories*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-folder w-4"></i> Categories
                </a>
                <a href="{{ route('ingredients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('ingredients*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-carrot w-4"></i> Ingredients
                </a>
                <a href="{{ route('markets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('markets*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-store w-4"></i> Markets
                </a>
                @if (auth()->user()?->isAdmin())
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 mt-6 px-2">Admin</p>
                    <a href="{{ route('admin.market-women.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.market-women*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                        <i class="fa-solid fa-user-check w-4"></i> Market Woman Approvals
                    </a>
                @endif
                
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 mt-6 px-2">My Stuff</p>
                
                <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('favorites*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-heart w-4"></i> Favorites
                </a>
                <a href="{{ route('shopping-lists.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('shopping-lists*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-cart-shopping w-4"></i> Shopping Lists
                </a>
                <a href="{{ route('preferences.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('preferences*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-sliders w-4"></i> Preferences
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('profile*') ? 'bg-primary/20 text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }} font-medium text-sm transition-all duration-200">
                    <i class="fa-solid fa-user w-4"></i> Profile
                </a>
            </nav>

            <section class="grid grid-cols-5 gap-3">
                {{-- User Info --}}
                <div class="px-6 py-4 border-b border-white/10 col-span-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center font-bold text-white text-sm">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <!-- <p class="text-xs text-gray-400">{{ auth()->user()->email ?? 'Email not available' }}</p> -->
                        </div>
                    </div>
                </div>

                {{-- Logout --}}
                <div class="py-4 border-t border-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="items-center py-2.5 px-2 rounded-xl hover:bg-red-500/20 text-gray-300 hover:text-red-400 font-medium text-sm transition-all duration-200">
                            <i class="fa-solid fa-right-from-bracket w-4"></i>
                        </button>
                    </form>
                </div>
            </section>
        </aside>

        {{-- ── Main Content --}}
        <main class="ml-0 flex-1 p-8 sm:ml-64 transition-all duration-300">

            <button @click="sidebarOpen = ! sidebarOpen" class="sm:hidden fixed top-2 right-4 z-40 inline-flex items-center justify-center p-2 rounded-md bg-white/10 text-secondary hover:bg-white/20 focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <!-- <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /> -->
                </svg>
            </button>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div id="flash-message" class="mb-6 bg-primary text-white px-6 py-4 rounded-xl shadow-lg font-medium transition-opacity duration-500">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        const flash = document.getElementById('flash-message');
                        if(flash) { flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }
                    }, 3000);
                </script>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
