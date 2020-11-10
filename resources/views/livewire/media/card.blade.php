<div>
    <h3 class="p-6 text-gray-900 leading-5 font-medium"><a href="{{ $model->path }}">{{ $model->title }}</a></h3>
    @auth
        <div class="text-sm mb-5 text-gray-900">
            @if($model->watched_count)
                <div>
                    {{ $model->watched_count }} mal gesehen
                </div>
            @endif
            <button wire:click="watch" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                Gesehen
            </button>
        </div>
    @endauth
</div>
