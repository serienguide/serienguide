@props(['label' => ''])

<div>
    @if ($label)
        <label for="{{ $attributes['name'] }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <div class="mt-1">
        <input {!! $attributes->merge(['class' => 'shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md']) !!} id="{{ $attributes['name'] }}" aria-describedby="{{ $attributes['name'] }}-description">
        @error($attributes['name'])
            <p class="mt-2 text-sm text-red-600" id="{{ $attributes['name'] }}-error">{{ $message }}</p>
        @enderror
    </div>
</div>