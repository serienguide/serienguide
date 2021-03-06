<nav id="nav" class="fixed w-screen z-50 bg-gray-800" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex-grow flex items-center">
                <div class="flex-shrink-0">
                    <a href="/">
                        <img loading="lazy" class="h-8 w-8" src="{{ Storage::disk('s3')->url('icons/favicon-96x96.png') }}" alt="Workflow logo">
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ App\Models\Shows\Show::indexPath() }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700">Serien</a>
                        <a href="{{ App\Models\Movies\Movie::indexPath() }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700">Filme</a>
                        <a href="{{ route('calendar.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700">Kalender</a>
                    </div>
                </div>
                @livewire('search-bar')
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    @auth
                        <a href="{{ route('users.notifications.index') }}" class="relative p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-gray-700" aria-label="Notifications">
                            @if (Auth::user()->unreadNotifications->count())
                                <span class="absolute top-1 right-1 block h-2 w-2 transform -translate-y-1/2 translate-x-1/2 rounded-full bg-red-400"></span>
                            @endif
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </a>

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = true" class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid" id="user-menu" aria-label="User menu" aria-haspopup="true">
                                    <img loading="lazy" class="h-8 w-8 rounded-full" src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}" alt="">
                                </button>
                            </div>
                              <!--
                                Profile dropdown panel, show/hide based on dropdown state.

                                Entering: "transition ease-out duration-100"
                                  From: "transform opacity-0 scale-95"
                                  To: "transform opacity-100 scale-100"
                                Leaving: "transition ease-in duration-75"
                                  From: "transform opacity-100 scale-100"
                                  To: "transform opacity-0 scale-95"
                              -->
                            <div
                                x-show="open"
                                x-cloak
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                                <div class="py-1 rounded-md bg-white shadow-xs">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="{{ Auth::user()->profile_path }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                    <a href="{{ Auth::user()->profile_path }}/watched" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gesehen</a>
                                    <a href="{{ Auth::user()->profile_path }}/lists" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Listen</a>
                                    <a href="{{ App\Models\Auth\OauthProvider::indexPath() }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Verbindungen</a>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Abmelden</a>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Registrieren</a>
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Anmelden</a>
                    @endauth
                </div>
            </div>

            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <!-- Menu open: "hidden", Menu closed: "block" -->
                    <svg @click="open=true"
                        x-show="open==false"
                        class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Menu open: "block", Menu closed: "hidden" -->
                    <svg @click="open=false"
                        x-show="open==true"
                        x-cloak
                        class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.

      Open: "block", closed: "hidden"
    -->
    <div x-show="open"
        x-cloak
        @click.away="open = false"
        class="md:hidden bg-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ App\Models\Shows\Show::indexPath() }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Serien</a>
            <a href="{{ App\Models\Movies\Movie::indexPath() }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Filme</a>
            <a href="{{ route('calendar.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Kalender</a>
        </div>
        @auth
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5 space-x-3">
                    <div class="flex-shrink-0">
                        <img loading="lazy" class="h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}" alt="">
                    </div>
                    <div class="space-y-1">
                        <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Dashboard</a>
                    <a href="{{ Auth::user()->profile_path }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Profil</a>
                    <a href="{{ Auth::user()->profile_path }}/watched" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Gesehen</a>
                    <a href="{{ Auth::user()->profile_path }}/lists" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Listen</a>
                    <a href="{{ App\Models\Auth\OauthProvider::indexPath() }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Verbindungen</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700" role="menuitem">Abmelden</a>
                </div>
            </div>
        @else
            <div class="mt-3 px-2 space-y-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Registrieren</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Anmelden</a>
            </div>
        @endauth
    </div>
</nav>