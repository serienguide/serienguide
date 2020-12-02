<div class="relative inline-block text-left" x-data="{ open: false }">
    <div class="px-1">
        <button @click="open = true" class="flex items-center text-gray-400 px-3 py-3 border border-gray-300 rounded-full hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
            <i class="fas fa-list"></i>
        </button>
    </div>

    <div x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg z-10"
        style="min-width: 300px;">
        <div class="rounded-md bg-white shadow-xs">
            <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                @livewire('media.listing', ['model' => $model])
            </div>
        </div>
    </div>
</div>
