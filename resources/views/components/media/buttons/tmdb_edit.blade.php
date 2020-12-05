@if($model->tmdb_path)
    <div class="px-1 inline-block">
        <a href="{{ $model->tmdb_path }}" target="_blank" class="flex items-center text-gray-400 px-3 py-3 border border-gray-300 rounded-full hover:text-gray-600 focus:outline-none focus:text-gray-600">
            <i class="fas fa-pen"></i>
        </a>
    </div>
@endif