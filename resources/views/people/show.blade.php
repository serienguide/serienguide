<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $model->index_path }}">Personen</a> > {{ $model->name }}
        </h2>
    </x-slot>

    {{ $model->biography }}
</x-app-layout>