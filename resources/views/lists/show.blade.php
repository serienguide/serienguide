<x-app-layout>
    <x-slot name="header">
        <a href="{{ $model->index_path }}">Listen</a> > <a href="{{ $model->path }}">{{ $model->name }}</a>
    </x-slot>

    @can('update', $model)
        <x-slot name="header_actions">
            <a href="{{ $model->edit_path }}"><i class="fas fa-edit"></i></a>
        </x-slot>
    @endcan

    <x-container class="py-4">
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
    </x-container>
</x-app-layout>