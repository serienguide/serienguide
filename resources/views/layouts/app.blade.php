<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{ $html_attributes['title'] }} | serienguide.tv</title>
        <meta name="description" content="{{ $html_attributes['description'] }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="Daniel">
        <meta name="publisher" content="www.d15r.de">
        <meta name="copyright" content="www.serienguide.tv">
        <meta name="revisit-after" CONTENT="1 days">
        <meta name="Abstract" content="Überbilck über deine Serien und Filme">
        <meta name="page-topic" content="www.serienguide.tv">
        <meta name="audience" content="Alle">
        <meta name="robots" content="INDEX,FOLLOW,ALL">
        <meta name="language" content="german, deutsch, de">
        <meta http-equiv="reply-to" content="mailto:info@serienguide.tv">
        <meta name="apple-mobile-web-app-title" content="serienguide.tv">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">

        <link rel="apple-touch-icon" sizes="57x57" href="{{ Storage::disk('s3')->url('icons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ Storage::disk('s3')->url('icons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ Storage::disk('s3')->url('icons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ Storage::disk('s3')->url('icons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ Storage::disk('s3')->url('icons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ Storage::disk('s3')->url('icons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ Storage::disk('s3')->url('icons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ Storage::disk('s3')->url('icons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ Storage::disk('s3')->url('icons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ Storage::disk('s3')->url('icons/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Storage::disk('s3')->url('icons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ Storage::disk('s3')->url('icons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ Storage::disk('s3')->url('icons/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <style>
            [x-cloak] { display: none; }
        </style>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
        @stack('scripts')
    </head>
    <body class="font-sans antialiased">
        <x-navigation />
        @isset ($header)
            <header class="bg-white shadow-sm pt-16">
                <x-container class="py-4">
                    <div class="flex items-center">
                        <h1 class="flex-grow text-lg leading-6 font-semibold text-gray-900">
                            {{ $header }}
                        </h1>
                        <div class="flex items-center">
                            {{ $header_stats ?? '' }}
                        </div>
                        <div class="flex items-center">
                            {{ $header_actions ?? '' }}
                        </div>
                    </div>
                </x-container>
            </header>
        @endif
        <main id="app" @isset($itemtype) itemtype="{{ $itemtype }}" itemscope="" @endisset>
            <auth :user="{{ json_encode(Auth::user()) }}"></auth>
            {{ $slot }}
        </main>
        <footer class="bg-gray-800" aria-labelledby="footerHeading">
            <h2 id="footerHeading" class="sr-only">Footer</h2>
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                <div class="xl:grid xl:grid-cols-2 xl:gap-8">
                    <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                    Importieren
                                </h3>
                                <ul class="mt-4 space-y-4">
                                    <li>
                                        <a href="{{ route('media.imports.tmdb.index', ['media_type' => 'shows']) }}" class="text-base text-gray-300 hover:text-white">
                                            Serien
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('media.imports.tmdb.index', ['media_type' => 'movies']) }}" class="text-base text-gray-300 hover:text-white">
                                            Filme
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            Page rendered in: <span class="font-bold">{{ round((microtime(true) - LARAVEL_START), 4) }}</span> seconds
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-12 md:mt-0">
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                    Allgemein
                                </h3>
                                <ul class="mt-4 space-y-4">
                                    <li>
                                        <a href="https://github.com/serienguide/serienguide" target="_blank" class="text-base text-gray-300 hover:text-white">
                                            Github
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://github.com/serienguide/serienguide/issues" target="_blank" class="text-base text-gray-300 hover:text-white">
                                            Feedback
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">
                                            <!-- API -->
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('legal.impressum.index') }}" class="text-base text-gray-300 hover:text-white">
                                            Kontakt
                                        </a>
                                    </li>
                                </ul>
                                </div>
                        </div>
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">

                                </h3>
                                <ul class="mt-4 space-y-4">
                                    <li>
                                        <a href="#" class="text-base text-gray-300 hover:text-white">

                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-12 md:mt-0">
                                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                                    Unterstützt von
                                </h3>
                                <ul class="mt-4 space-y-4">
                                    <li>
                                        <a href="https://www.themoviedb.org/" target="_blank" class="text-base text-gray-300 hover:text-white">
                                            <img loading="lazy" src="{{ Storage::disk('s3')->url('tmdb_logo.png') }}" style="max-width: 100px; border: 0;">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="http://thetvdb.com/" target="_blank" class="text-base text-gray-300 hover:text-white">
                                            <img loading="lazy" src="{{ Storage::disk('s3')->url('tvdb_logo.png') }}" style="max-width: 100px; border: 0;">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                    <p class="mt-8 text-base text-gray-400 md:mt-0">
                        &copy; {{ date('Y') }} serienguide  - made with <i class="fas fa-fw fa-heart"></i> by <a class="text-white text-underline" href="https://d15r.de" target="_blank">D15r</a>
                    </p>
                    <div>
                        <a href="{{ route('legal.impressum.index') }}" class="text-base text-gray-300 hover:text-white">
                             Impressum & Datenschutz
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        <x-notification :show="(session()->has('status') ? 'true' : 'false')" :message="(session()->has('status') ? session('status')['text'] : '')" />
        @stack('modals')

        @livewireScripts
        <script type="text/javascript" defer>
            (function() {
                if (!localStorage.getItem('cookieconsent')) {
                    document.body.innerHTML += '\
                    <div class="cookieconsent" style="position:fixed;padding:20px;left:0;bottom:0;background-color: rgba(37, 47, 63);color: rgba(210, 214, 220);text-align:center;width:100%;z-index:99999;">\
                        Diese Website verwendet Cookies. Wenn Du die Website weiterhin nutzt, gehen wir davon aus, dass Du deine Zustimmung gegeben hast. \
                        <a href="/impressum" style="color: rgba(63, 131, 248);">Datenschutz</a>\
                        <button style="padding: 0.5rem 1rem;line-height: 1.25rem;font-size: 0.875rem;font-weight: 500;align-items: center;display: inline-flex;border-radius: 0.375rem;border-width: 1px;border-color: rgba(210, 214, 220);background-color: rgba(255, 255, 255);color: rgba(107, 114, 128);">Ok</button>\
                    </div>\
                    ';
                    document.querySelector('.cookieconsent button').onclick = function(e) {
                        e.preventDefault();
                        document.querySelector('.cookieconsent').style.display = 'none';
                        localStorage.setItem('cookieconsent', true);
                    };
                }
            })();
        </script>
    </body>
</html>
