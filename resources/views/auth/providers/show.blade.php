<x-app-layout>
    <x-slot name="header">
        Verbindungen > {{ $model->provider_type }}
    </x-slot>

    <x-container class="py-4">
        @dump($model)
    </x-container>
</x-app-layout>