<x-app-layout>
    <x-slot name="header">
        <a href="{{ $model->index_path }}">Filme</a> > {{ $model->name }}
    </x-slot>

    {{ $model->overview }}

    {{ $model->genres->implode('name', ', ') }}

    @dump($model->credits)


    @auth
        <div class="mt-3">
            @livewire('watched.ul', ['model' => $model])
        </div>
    @endauth

</x-app-layout>