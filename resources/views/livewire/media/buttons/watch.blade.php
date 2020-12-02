<button wire:click="watch" type="button" class="inline-flex items-center px-3 py-3 border border-gray-300 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 {{ $button_class }}" title="@if($model->watched_count) {{ $model->watched_count }} mal gesehen @endif">
    <i class="fas fa-check"></i>
</button>
