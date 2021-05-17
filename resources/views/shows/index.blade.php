<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Serien
    </x-slot>
    <x-container class="py-4">
        <deck-shows-index></deck-shows-index>
    </x-container>
</x-app-layout>