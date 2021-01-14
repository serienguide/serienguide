
<div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
    <div class="divide-y divide-gray-200">
        <div class="px-4 py-5 sm:px-6">
            <h2 id="notes-title" class="text-lg font-medium text-gray-900">Kommentare</h2>
        </div>
        <div class="px-4 py-6 sm:px-6">
            <ul class="space-y-8">
                @forelse($items as $item)
                    <li>
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="{{ $item->user->profile_photo_url }}" alt="">
                            </div>
                            <div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-gray-900">{{ $item->user->name }}</a>
                                </div>
                                <div class="mt-1 text-sm text-gray-700">
                                    <p>{{ $item->text }}</p>
                                </div>
                                <div class="mt-2 text-sm space-x-2">
                                    <span class="text-gray-500 font-medium" title="{{ $item->created_at->format('d.m.Y H:i') }}">{{ $item->created_at->diffForHumans() }}</span>
                                    @if (false)
                                        <span class="text-gray-500 font-medium">&middot;</span>
                                        <button type="button" class="text-gray-900 font-medium">Reply</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="flex space-x-3">
                            Sei der Erste, der einen Kommentar abgibt.
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    @auth
        <div class="bg-gray-50 px-4 py-6 sm:px-6">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="">
                </div>
                <div class="min-w-0 flex-1">
                    <div>
                        <label for="comment" class="sr-only">Kommentar</label>
                        <textarea wire:model.defer="form.text" id="comment" rows="3" class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" placeholder="kommentieren"></textarea>
                        @error('form.text')
                            <p class="mt-2 text-sm text-red-600" id="email-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <a href="#" class="group inline-flex items-start text-sm space-x-2 text-gray-500 hover:text-gray-900">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>
                                Some HTML is okay.
                            </span>
                        </a>
                        <button wire:click="comment" type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kommentieren
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>
