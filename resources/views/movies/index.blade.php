<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Filme
    </x-slot>
    <x-container class="py-4">
        <deck-movies-index :filter-options="{{ json_encode($filter_options) }}"></deck-movies-index>
    </x-container>
</x-app-layout>