<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Serien
    </x-slot>
    <x-container class="py-4">
        @livewire('shows.index')
    </x-container>
</x-app-layout>