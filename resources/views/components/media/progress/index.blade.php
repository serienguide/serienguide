<div class="my-3 rounded h-4 w-full bg-blue-900" title="{{ $model->progress['watched_count'] }}/{{ $model->progress['watchable_count'] }} {{ $model->progress['percent'] }}%">
    <div class="bg-blue-500 h-4 text-xs leading-none text-center text-white @if ($model->progress['percent'] > 0) rounded-l @endif @if ($model->progress['percent'] == 100) rounded-r @endif" style="width: {{ $model->progress['percent'] }}%"></div>
</div>
    <div>
        <span class="font-bold">{{ $model->progress['watched_count'] }}</span>
        von <span class="font-bold">{{ $model->progress['watchable_count'] }}</span>
        (<span class="font-bold">{{ floor($model->progress['watched_runtime'] / 60) }}h
        {{ $model->progress['watched_runtime'] % 60 }}m</span>)
        {{ $model->progress['labels']['plural'] }} gesehen.
        Es {{ ($model->progress['unwatched_count'] == 1 ? ' ist' : 'sind') }}
        noch <span class="font-bold">{{ $model->progress['unwatched_count'] }}</span>
        {{ ($model->progress['unwatched_count'] == 1 ? $model->progress['labels']['singular'] : $model->progress['labels']['plural']) }}
        übrig
        (<span class="font-bold">{{ floor($model->progress['unwatched_runtime'] / 60) }}h {{ $model->progress['unwatched_runtime'] % 60 }}m</span>).
    </div>
    @if ($model->last_watched)
        <div>
            Zuletzt gesehen
            @if ($model->is_collection)
                <a class="text-blue-500 hover:text-blue-600" href="{{ $model->last_watched->watchable->path }}">{{ $model->last_watched->watchable->name }}</a>
            @elseif ($model->is_show)
                <a class="text-blue-500 hover:text-blue-600" href="{{ $model->last_watched->watchable->path }}">{{ $model->last_watched->watchable->season->season_number }}x{{ $model->last_watched->watchable->episode_number }} {{ $model->last_watched->watchable->name }}</a>
            @endif
            {{ $model->last_watched->watched_at->diffForHumans() }} am {{ $model->last_watched->watched_at->format('d') }}. {{ $model->last_watched->watched_at->monthName }} {{ $model->last_watched->watched_at->format('Y (H:i)') }}
        </div>
    @endif