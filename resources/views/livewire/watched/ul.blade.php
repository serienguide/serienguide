<div>
    <span class="inline-flex rounded-md shadow-sm">
        <button wire:click="watch" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
            Gesehen
        </button>
    </span>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            <li class="py-4 space-x-3" x-data="{isEditing: @entangle('isEditing.' . $item->id)}">
                <div class="flex items-center justify-between" x-show="isEditing == false">
                    <div class="text-sm leading-5 font-medium text-blue-600 truncate">
                        {{ $item->watched_at->format('d.m.Y H:i') }}
                        <div class="text-sm text-gray-500">Erstellt: {{ $item->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <div x-show="isEditing == false" class="ml-2 flex-shrink-0 flex">
                        <i @click="isEditing = true" class="mr-1 fas fa-edit text-sm text-gray-500 hover:text-gray-700 cursor-pointer"></i>
                        <i wire:click="destroy({{ $loop->index }})" class="fas fa-trash-alt text-sm text-red-500 hover:text-red-700 cursor-pointer" title="Löschen"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between" x-show="isEditing == true">
                    <div class="text-sm leading-5 font-medium text-blue-600 truncate">
                        <div>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model.defer="form.{{ $item->id }}.watchedAt" type="text" id="email" class="block w-full px-1 @error('form.' . $item->id . '.watchedAt') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @else focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 @enderror sm:text-sm rounded-md" aria-invalid="true" aria-describedby="email-error">
                            </div>
                            @error('form.' . $item->id . '.watchedAt')
                                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div x-show="isEditing == true" class="ml-2 flex-shrink-0 flex">
                        <i @click="isEditing = false" class="mr-1 fas fa-times text-sm text-gray-500 hover:text-gray-700 cursor-pointer"></i>
                        <i wire:click="update({{ $item->id }})" class="fas fa-save text-sm text-gray-500 hover:text-gray-700 cursor-pointer" title="Löschen"></i>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-4 flex space-x-3">Noch nicht gesehen</li>
        @endforelse
</div>
