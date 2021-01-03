<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Filme
    </x-slot>
    <x-container class="py-4">
        @livewire('movies.index')
    </x-container>
</x-app-layout>