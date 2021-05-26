<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Serien
    </x-slot>
    <x-container class="py-4">
        <deck-shows-index :filter-options="{{ json_encode($filter_options) }}"></deck-shows-index>
    </x-container>
</x-app-layout>