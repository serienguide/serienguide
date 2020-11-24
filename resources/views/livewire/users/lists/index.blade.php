<div>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            <li>
                <a class="block hover:bg-gray-50" href="{{ $item->path }}">
                    <div class="flex items-center px-4 py-4 sm:px-6">
                        <div class="flex-grow">{{ $item->name }}</div>
                        <div>
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li>Keine Listen vorhanden<li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>