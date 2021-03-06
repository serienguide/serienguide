<div>
    <div class="flex items-center mb-3">
        <div class="flex-grow font-bold">Listen verwalten</div>
        <div class="hidden">
            <i class="fas fa-plus text-sm leading-5 text-gray-900 cursor-pointer"></i>
        </div>
    </div>
    <ul>
        @foreach ($lists as $list)
            @if ($list->is_custom != $is_custom)
                <?php $is_custom = true; ?>
                <li class="py-3"><hr class="text-gray-400"></li>
            @endif
            <li class="flex items-center" wire:key="{{ $model::ROUTE_NAME }}-{{ $model->id }}-list-{{ $list->id }}">
                <input wire:click="toggle({{ $list->id }})" id="{{ $model::ROUTE_NAME }}-{{ $model->id }}-list-{{ $list->id }}" type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" {!! ($list->items->count() > 0 ? 'checked="checked"' : '') !!}>
                <label for="{{ $model::ROUTE_NAME }}-{{ $model->id }}-list-{{ $list->id }}" class="ml-2 block text-sm leading-5 text-gray-900">
                    {{ $list->name }}
                </label>
            </li>
        @endforeach
    </ul>
</div>
