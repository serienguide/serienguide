<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <p>angemeldet als {{ Auth::user()->name }}</p>

    <p>Die Grundfunktionen stehen.</p>
    <p>Ich werde nach und nach weitere einbauen.</p>
    <br />
    <p>Feedback, Fehler und Ideen könen <a class="underline" href="https://github.com/serienguide/serienguide/issues" target="_blank">hier</a> eintragen werden.</p>
</x-app-layout>
