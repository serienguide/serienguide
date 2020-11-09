<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $model->index_path }}">Filme</a> > {{ $model->title }}
        </h2>
    </x-slot>

    {{ $model->overview }}

    @auth
        <div class="mt-3">
            @livewire('watched.ul', ['model' => $model])
        </div>
    @endauth

</x-app-layout>