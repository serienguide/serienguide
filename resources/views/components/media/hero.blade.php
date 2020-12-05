<section class="relative w-screen h-screen bg-no-repeat bg-cover bg-top" style="background-image: url({{ Storage::disk('s3')->url('w1920' . $model->backdrop_path) }})">
    <div class="absolute w-screen bottom-0 h-36" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
    <div class="absolute w-screen bottom-0 text-white font-bold">
        <x-container class="py-4 whitespace-nowrap">
            <div class="flex items-center text-5xl">
                <div class="flex items-center overflow-hidden">
                    @if ($model->is_episode)
                        <div>
                            <h2 class="text-2xl"><a href="{{ $model->show->path }}">{{ $model->show->name }}</a></h2>
                            <h1 class="">{{ $model->season->season_number }}x{{ $model->episode_number }} {{ $model->name }}</h1>
                        </div>
                    @else
                        <h1 class="">{{ $model->name }}</h1>
                    @endif
                </div>
                <div class="ml-2 flex-grow">
                    @if ($model->imdb_id)
                        <a class="" href="https://www.imdb.com/title/{{ $model->imdb_id }}/" target="_blank" rel="noopener"><i class="fab fa-imdb"></i></a>
                    @endif
                    @if ($model->facebook)
                        <a class="" href="https://www.facebook.com/{{ $model->facebook }}" target="_blank" rel="noopener"><i class="fab fa-facebook-square"></i></a>
                    @endif
                    @if ($model->instagram)
                        <a class="" href="https://www.instagram.com/{{ $model->instagram }}/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if ($model->twitter)
                        <a class="" href="https://twitter.com/{{ $model->twitter }}" target="_blank" rel="noopener"><i class="fab fa-twitter-square"></i></a>
                    @endif
                </div>
                <div class="ml-2 text-center">
                    @if ($model->is_movie && $model->is_show)
                        <div>
                            <div class="text-2xl">{{ $model->list_items()->count() }}</div>
                            <div class="text-xs text-gray-500"><i class="fas fa-list"></i> LISTEN</div>
                        </div>
                   @endif
                </div>
            </div>
        </x-container>
    </div>
</section>
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