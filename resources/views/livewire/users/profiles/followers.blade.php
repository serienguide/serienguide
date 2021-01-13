<div>
    <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Follower
        </h3>
        <div class="mt-3 flex sm:mt-0 sm:ml-4">
            <select wire:model="filter.follow_type" class="rounded-md border-gray-300">
                <option value="followers">Abonnenten</option>
                <option value="followings">abonniert</option>
            </select>
        </div>
    </div>

    <div wire:loading.delay class="text-center w-100 p-5">
        <i class="fa fa-spinner fa-spin fa-3x text-grey"></i>
    </div>
    <div class="mb-3 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach ($items as $item)
            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/{{ md5($item->email) }}" alt="">
                </div>
                <div class="flex-1 min-w-0">
                <a href="{{ $item->profile_path }}" class="focus:outline-none">
                    <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $item->name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ $item->followers_count }} Abonnenten
                        </p>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{ $items->links() }}

</div>