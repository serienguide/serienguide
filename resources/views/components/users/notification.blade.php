<li class="py-4 flex items-center">
    @switch ($image_type)
        @case('model')
            <img class="rounded-md" src="{{ $model->poster_url_xs }}" alt="{{ $model->name }} Poster">
            @break
         @case('notifiable')
            <img class="h-12 w-12 rounded-full" src="https://www.gravatar.com/avatar/{{ md5($notifiable->email) }}" alt="User Avatar">
            @break
         @case('user')
            <img class="h-12 w-12 rounded-full" src="https://www.gravatar.com/avatar/{{ md5($user->email) }}" alt="{{ $user->name }} Avatar">
            @break
    @endswitch
    <div class="flex-grow ml-3">
        <p class="text-sm font-medium text-gray-900">
            @switch ($notification->type)
                @case(App\Notifications\Media\Imported::class)
                    <a class="text-blue-500 hover:text-blue-600 font-bold" href="{{ $model->path }}">{{ $model->name }}</a> wurde aktualisiert.
                    @break
                @case(App\Notifications\Apis\Tract\Sync\Watched::class)
                    Es wurden {{ $notification->data['episode_sync_count'] }} {{ App\Models\Shows\Episodes\Episode::label($notification->data['episode_sync_count']) }} und {{ $notification->data['movie_sync_count'] }} {{ App\Models\Movies\Movie::label($notification->data['movie_sync_count']) }} importiert.
                    @break
            @endswitch
        </p>
        <p class="text-sm text-gray-500"><span title="{{ $notification->created_at->format('d.m.Y (H:i)') }}">{{ $notification->created_at->diffForHumans() }}</span></p>
    </div>
    <div class="inline-flex notifications-center justify-center p-3">
        @if (is_null($notification->read_at))
            <span class="block h-2 w-2 rounded-full bg-red-400"></span>
        @endif
    </div>
</li>