<div>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            @if ($item->type == App\Notifications\Media\Imported::class)
            <?php $item->model = $item->data['model_type']::find($item->data['model_id']); ?>
                <li class="py-4 flex items-center">
                    <img class="rounded-md" src="{{ $item->model->poster_url_xs }}" alt="">
                    <div class="flex-grow ml-3">
                        <p class="text-sm font-medium text-gray-900"><a class="text-blue-500 hover:text-blue-600 font-bold" href="{{ $item->model->path }}">{{ $item->model->name }}</a> wurde aktualisiert.</p>
                        <p class="text-sm text-gray-500"><span title="{{ $item->created_at->format('d.m.Y (H:i)') }}">{{ $item->created_at->diffForHumans() }}</span></p>
                    </div>
                    <div class="inline-flex items-center justify-center p-3">
                        @if (is_null($item->read_at))
                            <span class="block h-2 w-2 rounded-full bg-red-400"></span>
                        @endif
                    </div>
                </li>
            @elseif ($item->type == App\Notifications\Apis\Tract\Sync\Watched::class)
                <li class="py-4 flex items-center">
                    <img class="h-12 w-12 rounded-full" src="https://www.gravatar.com/avatar/{{ md5($item->notifiable->email) }}" alt="">
                    <div class="flex-grow ml-3">
                        <p class="text-sm font-medium text-gray-900">Es wurden {{ $item->data['episode_sync_count'] }} {{ App\Models\Shows\Episodes\Episode::label($item->data['episode_sync_count']) }} und {{ $item->data['movie_sync_count'] }} {{ App\Models\Movies\Movie::label($item->data['movie_sync_count']) }} importiert. </p>
                        <p class="text-sm text-gray-500"><span title="{{ $item->created_at->format('d.m.Y (H:i)') }}">{{ $item->created_at->diffForHumans() }}</span></p>
                    </div>
                    <div class="inline-flex items-center justify-center p-3">
                        @if (is_null($item->read_at))
                            <span class="block h-2 w-2 rounded-full bg-red-400"></span>
                        @endif
                    </div>
                </li>
            @endif
            @if (is_null($item->read_at))
                <?php $item->markAsRead(); ?>
            @endif
        @empty
            <li class="py-4 flex items-center">Keine Benachrichtigungen vorhanden<li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
