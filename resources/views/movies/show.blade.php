<x-app-layout>
    <x-slot name="header">
        <a href="{{ $model->index_path }}">Filme</a> > {{ $model->title }}
    </x-slot>

    {{ $model->overview }}

    @auth
        <div class="mt-3">
            @livewire('watched.ul', ['model' => $model])
        </div>
    @endauth

</x-app-layout>