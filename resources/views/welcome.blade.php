<x-app-layout>
    <section class="relative w-screen h-screen bg-no-repeat bg-cover bg-top" style="background-image: url({{ Storage::disk('s3')->url('w1920/lmZFxXgJE3vgrciwuDib0N8CfQo.jpg') }})">
        <div class="absolute w-screen bottom-0 top-0" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
        <div class="absolute w-screen top-52 left-0 right-0 text-white font-bold text-center">
            <x-container class="py-4 whitespace-nowrap">
                <div class="flex items-center justify-center text-5xl mb-8">
                    <img class="mr-1" src="{{ Storage::disk('s3')->url('icons/apple-icon-114x114.png') }}" alt="serieguide.tv Logo" width="75" />
                    serienguide.tv
                </div>
                <h1 class="my-3 text-4xl">Verfolge deine Lieblingsserien & Filme.</h1>
                <h2 class="text-xl">Finde Streams und entdecke was angesagt ist.</h2>
                @guest
                    <a href="{{ route('register') }}" class="mt-16 font-bold inline-flex items-center px-4 py-2 text-sm leading-5 font-medium rounded-md bg-blue-700 text-white hover:bg-blue-500 active:bg-blue-800 active:bg-gray-50 transition ease-in-out duration-150">
                        Melde dich jetzt kostenlos an
                    </a>
                @endguest
            </x-container>
        </div>
    </section>

    <section>
        <x-container class="py-4">
            <div class="py-12 bg-white">
                <div class="max-w-xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                    <dl class="space-y-10 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-8">
                        <div>
                            <div class="flex items-center justify-center h-12 w-12 text-white">
                                <i class="fas fa-fw fa-2x fa-check text-blue-500"></i>
                            </div>
                            <div class="mt-5">
                                <dt class="text-lg leading-6 font-medium text-gray-900">
                                    Serienchronik
                                </dt>
                                <dd class="mt-2 text-base text-gray-500">
                                    Verfolge und verwalte deine Serien & Filme. Erstelle deine Serienchronik und werte die umfangreichen Statistiken aus.
                                </dd>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-center h-12 w-12 text-white">
                                <i class="fas fa-fw fa-2x fa-calendar text-blue-500"></i>
                            </div>
                            <div class="mt-5">
                                <dt class="text-lg leading-6 font-medium text-gray-900">
                                    Ausstrahlungstermine
                                </dt>
                                <dd class="mt-2 text-base text-gray-500">
                                    Im <a class="underline" href="{{ route('calendar.index') }}">Kalender</a> findest du <strong>deutsche</strong> und englische Sendetermine. Neue Staffeln deiner Serien landen bei Erstausstrahlung direkt in "Meine Serien".
                                </dd>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-center h-12 w-12 text-white">
                                <i class="fas fa-fw fa-2x fa-plus text-blue-500"></i>
                            </div>
                            <div class="mt-5">
                                <dt class="text-lg leading-6 font-medium text-gray-900">
                                    Entdecke Neues
                                </dt>
                                <dd class="mt-2 text-base text-gray-500">
                                    Erhalte Empfehlungen von anderen Serienfans oder auf Basis deiner Serienchronik. Entdecke Neues auf den Übersichtsseiten mit umfangreichen Filtermöglichkeiten.
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

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
