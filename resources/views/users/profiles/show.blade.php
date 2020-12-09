<x-app-layout>

    <section class="relative w-screen bg-no-repeat bg-cover bg-top" style="height: 50vh; min-height: 500px;  @isset ($user->last_watched) background-image: url({{ $user->last_watched->watchable->backdrop_url_xl }}) @endisset" >
        <div class="absolute w-screen bottom-0 h-72" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
            <div class="absolute w-screen bottom-0 text-white font-bold">
                <x-container class="py-4 pt-16">
                    <div class="flex items-end">
                        <img class="h-48 w-48 rounded-full" src="https://www.gravatar.com/avatar/{{ md5($user->email) }}">
                        <div class="flex flex-col ml-3">
                            <div class="flex items-center">
                                <h1 class="flex-grow text-2xl font-bold">{{ $user->name }}</h1>
                                <div>
                                    @env('local')
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            Level
                                        </span>
                                    @endenv
                                </div>
                            </div>
                            @env('local')
                                <div class="py-4 text-center">
                                    <button type="button" class="inline-flex items-center px-4 py-2 text-sm leading-5 font-medium rounded-md bg-blue-700 text-white hover:bg-blue-500 active:bg-blue-800 active:bg-gray-50 transition ease-in-out duration-150">
                                        Folgen
                                    </button>
                                </div>
                                <div class="flex items-center text-center">
                                    <div class="mr-3">
                                        <div class="text-2xl">XX</div>
                                        <div class="text-xs text-gray-500">FOLLOWER</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl">XX</div>
                                        <div class="text-xs text-gray-500">FOLLOWING</div>
                                    </div>
                                </div>
                            @endenv
                        </div>
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

    <section class="bg-gray-800 overflow-auto">
        <x-container class="py-2">
            <div class="flex flex-nowrap justify-between intems-center">
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}">Profil</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/watched">Gesehen</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href="{{ $user->profile_path }}/rated">Bewertet</a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href=""></a>
                <a class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700" href=""></a>
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