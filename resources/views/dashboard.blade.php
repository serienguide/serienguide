<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

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
        @livewire('dashboard.following.last-watched', ['user' => Auth::user()])
    </x-container>
</x-app-layout>
