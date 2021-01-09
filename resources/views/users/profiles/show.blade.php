<x-app-layout>
    <section class="relative w-screen bg-no-repeat bg-cover bg-top" style="height: 30vh; min-height: 250px;  @isset ($user->last_watched) background-image: url({{ $user->last_watched->watchable->backdrop_url_xl }}) @endisset" >
        <div class="absolute w-screen bottom-0 h-72" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
        <div class="absolute w-screen bottom-0 text-white font-bold">
            <x-container class="py-4 pt-16">
                <div class="flex items-end">

                    <div class="flex-grow text-right pr-3 hidden md:block">
                        @isset($user->last_watched)
                            <a href="{{ $user->last_watched->watchable->path }}" class="text-sm text-white font-bold">
                                @if ($user->last_watched->watchable->is_episode)
                                    {{ $user->last_watched->watchable->show->name }} {{ $user->last_watched->watchable->season->season_number }}x{{ $user->last_watched->watchable->episode_number }}
                                @else
                                    {{ $user->last_watched->watchable->name }}
                                @endif
                            </a>
                        @endisset
                    </div>
                </div>
            </x-container>
        </div>

    </section>

    <section>
        <x-container>
            <div class="-mt-12 z-20 relative sm:-mt-16 sm:flex sm:items-end sm:space-x-5">
                <div class="flex">
                    <img class="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32" src="https://www.gravatar.com/avatar/{{ md5($user->email) }}">
                </div>
                <div class="mt-6 sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                    <div class="mt-6 min-w-0 flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 truncate">
                            {{ $user->name }}
                        </h1>
                    </div>
                    <div class="mt-6 flex flex-col justify-stretch space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                        @if (false)
                            <button type="button" class="inline-flex justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                                <span>Folgen</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </x-container>
    </section>

    <section class="bg-gray-800 overflow-auto mt-6 sm:mt-2 2xl:mt-5">
        <x-container class="py-2">
            <div class="flex flex-nowrap justify-between intems-center">
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}">Profil</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/watched">Gesehen</a>
                @env('local')
                    <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/progress">Fortschritt</a>
                @endenv
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/rated">Bewertet</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/lists">Listen</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href=""></a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href=""></a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href=""></a>
            </div>
        </x-container>
    </section>

    <section class="">
        <x-container class="py-4">
            @livewire('users.profiles.' . $section, ['user' => $user])
        </x-container>
    </section>

</x-app-layout>
<script type="text/javascript">
    var nav = document.querySelector('nav#nav'),
        searchbar = document.getElementById('search');
    nav.classList.add("bg-transparent");
    nav.classList.remove("bg-gray-800");
    searchbar.classList.add("bg-transparent");
    searchbar.classList.remove("bg-gray-700");
    window.onscroll = function() {
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            nav.classList.add("bg-gray-800");
            nav.classList.remove("bg-transparent");
            searchbar.classList.add("bg-gray-700");
            searchbar.classList.remove("bg-transparent");
        } else {
            nav.classList.add("bg-transparent");
            nav.classList.remove("bg-gray-800");
            searchbar.classList.add("bg-transparent");
            searchbar.classList.remove("bg-gray-700");
        }
    };
</script>