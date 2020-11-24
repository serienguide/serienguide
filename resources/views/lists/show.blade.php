<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex-grow">
                <a href="{{ $model->index_path }}">Listen</a> > <a href="{{ $model->path }}">{{ $model->name }}</a>
            </div>
            <div>
                <a href="{{ $model->edit_path }}">Bearbeiten</a>
            </div>
        </div>
    </x-slot>

    @if ($model->description)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-3">
            <div class="px-1 py-1 sm:px-2">
                <div class="whitespace-pre-line">
                    {{ $model->description }}
                </div>
            </div>
        </div>
    @endif

    @livewire('users.lists.items.index', ['model' => $model])
</x-app-layout>