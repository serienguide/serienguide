<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <x-container class="py-4">
        <p>Schwerenherzens werde ich serienguide.tv einstellen.</p>

        <p>Die Gründe sind vielfältig. Zum einen ist die Zeit, die ich in das Projekt investieren kann und möchte, stark begrenzt. Zum anderen ist die Technik veraltet und die Codebasis nicht mehr zeitgemäß.</p>
        <p>Ich habe mich daher entschlossen, das Projekt einzustellen.</p>

        <p>Ich habe die Domain gekündigt und die Webseite ist noch bis zum 10.11.2023 zu erreichen.</p>

        <p>Das Github Repository wird weiterhin erreichbar sein.</p>

        <p>Ich empfehle euch <a href="https://trakt.tv" target="_blank">Trakt</a> zu nutzen.</p>
    </x-container>

    <x-container class="py-4">
        <deck-episodes-next index-path="{{ route('episodes.next.index') }}"></deck-episodes-next>
    </x-container>

    <x-container class="py-4">
        <p>angemeldet als {{ Auth::user()->name }}</p>

        <p>Die Grundfunktionen stehen.</p>
        <p>Ich werde nach und nach weitere einbauen.</p>
        <br />
        <p>Feedback, Fehler und Ideen könen <a class="underline" href="https://github.com/serienguide/serienguide/issues" target="_blank">hier</a> eintragen werden.</p>
    </x-container>

    <x-container class="py-4">
        <deck-following-last-watched index-path="{{ route('users.followings.last-watched') }}"></deck-following-last-watched>
    </x-container>
</x-app-layout>
