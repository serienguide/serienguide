<div>
    <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    @forelse ($items as $item)
        <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow">
            <h3 class="p-6 text-gray-900 text-sm leading-5 font-medium"><a href="{{ $item->path }}">{{ $item->title }}</a></h3>
        </li>
    @empty
        Keine Filme vorhanden
    @endforelse
</div>
