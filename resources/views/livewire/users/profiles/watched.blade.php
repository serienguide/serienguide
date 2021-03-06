<div x-data="{ show_dividers: true }">
    <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Gesehen
        </h3>
        <div class="mt-3 flex sm:mt-0 sm:ml-4">
            <select wire:model="filter.watchable_type" class="rounded-md border-gray-300">
                <option value="0">Alle</option>
                <option value="{{ App\Models\Movies\Movie::class }}">{{ App\Models\Movies\Movie::label() }}</option>
                <option value="{{ App\Models\Shows\Episodes\Episode::class }}">{{ App\Models\Shows\Episodes\Episode::label() }}</option>
            </select>
            <select wire:model="sort_by" class="ml-3 rounded-md border-gray-300">
                <option value="created_at">Erstellt</option>
                <option value="watched_at">Gesehen</option>
            </select>
            <button @click="show_dividers = !show_dividers" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-code"></i>
            </button>
        </div>
    </div>

    <div wire:loading.delay class="text-center w-100 p-5">
        <i class="fa fa-spinner fa-spin fa-3x text-grey"></i>
    </div>
    <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
        @forelse ($items as $item)
            @if ((is_null($last_date)) || ($last_date->format('Ymd') != $item->$sort_by->format('Ymd')))
                <?php $last_date = $item->$sort_by; ?>
                <li x-show="show_dividers" class="pb-1 border-b border-gray-200 sm:flex sm:items-center sm:justify-between" style="grid-column: 1 / -1;">
                    <h3 class="text-base leading-6 font-medium text-gray-900">
                        <i class="fas fa-history"></i> {{ $last_date->dayName }}, {{ $last_date->format('d.') }} {{ $last_date->monthName }} {{ $last_date->format('Y') }}
                    </h3>
                    <div class="text-sm mt-3 flex sm:mt-0 sm:ml-4 text-gray-400">
                        {{ floor($daily_runtimes[$last_date->format('Ymd')] / 60) }}h {{ $daily_runtimes[$last_date->format('Ymd')] % 60 }}m
                    </div>
                </li>
            @endif
            @livewire('media.card', ['action' => $item, 'model' => $item->watchable], key('media-card-' . $item->watchable->id . '-action-' . $item->id))
        @empty
           <li class="bg-white rounded-lg shadow px-4 py-2" style="grid-column: 1 / -1;">Noch nichts gesehen.</li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
