<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    angemeldet als {{ Auth::user()->name }}
</x-app-layout>
