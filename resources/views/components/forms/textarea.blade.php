@props(['label' => '', 'value' => ''])

<div>
    @if ($label)
        <label for="{{ $attributes['name'] }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <div class="mt-1">
        <textarea {!! $attributes->merge(['class' => 'py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md']) !!} id="{{ $attributes['name'] }}" aria-describedby="{{ $attributes['name'] }}-description">
            {{ $value }}
        </textarea>
        @error($attributes['name'])
            <p class="mt-2 text-sm text-red-600" id="{{ $attributes['name'] }}-error">{{ $message }}</p>
        @enderror
    </div>
</div>