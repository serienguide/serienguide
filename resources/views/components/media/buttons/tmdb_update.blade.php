<div class="px-1 inline-block">
    <a href="{{ route('media.tmdb.update', [ 'medium_type' => ($model->is_episode ? 'episodes' : $model::ROUTE_NAME), 'model' => $model->id ]) }}" class="flex items-center text-gray-400 px-3 py-3 border border-gray-300 rounded-full hover:text-gray-600 focus:outline-none focus:text-gray-600">
        <i class="fas fa-sync"></i>
    </a>
</div>