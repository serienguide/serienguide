<x-app-layout>
    <x-slot name="header">
        {{ $media_class_name::label() }} importieren
    </x-slot>
    <x-container class="py-4">
        @livewire('media.imports.tmdb', ['media_type' => $media_type])
    </x-container>
</x-app-layout>