<div>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            @if ($item->is_custom != $is_custom)
                <?php $is_custom = true; ?>
                <li class="py-3"></li>
            @endif
            <li class="">
                <div class="flex items-stretch px-4 py-4 sm:px-6 liste-info">
                    <div class="poster-items-wrapper">
                        <div class="posters">
                            <a href="{{ $item->path }}">
                                <div class="poster-items-wrapper">
                                    <div class="poster-items">
                                        @foreach($item->items()->with('medium')->latest()->take(5)->get() as $list_item)
                                            <div class="poster-item">
                                                <div class="poster">
                                                    <img src="{{ $list_item->medium->poster_url }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="flex-grow px-3">
                        <div class="py-3">
                            <div class="flex items-center">
                                <div class="flex-grow flex items-center">
                                    <img loading="lazy" class="h-12 w-12 rounded-full mr-3" src="https://www.gravatar.com/avatar/{{ md5($item->user->email) }}" alt="">
                                    <div>
                                        <a class="font-bold hover:text-gray-700" href="{{ $item->path }}">{{ $item->name }}</a>
                                        <div class="text-sm">von <a class="hover:text-gray-700" href="{{ $item->user->path }}">{{ $item->user->name }}</a></div>
                                    </div>
                                </div>
                                <a class="text-gray-400 hover:text-gray-700" href="{{ $item->path }}">
                                    <svg class="h-5 w-5 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                            <div class="mt-3">
                                {{ $item->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li>Keine Listen vorhanden<li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>