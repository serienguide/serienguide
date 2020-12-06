<div>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            <x-users.notification :notification="$item" />
        @empty
            <li class="py-4 flex items-center">Keine Benachrichtigungen vorhanden<li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
