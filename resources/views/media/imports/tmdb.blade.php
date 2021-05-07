<x-app-layout>
    <x-slot name="header">
        {{ $media_class_name::label() }} importieren
    </x-slot>
    <x-container class="py-4">
        <media-imports-tmdb-index media-type="{{ $media_type }}"></media-imports-tmdb-index>
    </x-container>
</x-app-layout>