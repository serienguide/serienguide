<x-app-layout>
    <x-slot name="header">
        Verbindungen
    </x-slot>

    <x-container class="py-4">
        <h2>Anlegen</h2>
        <div class="my-6 grid grid-cols-6 gap-3">
            <div>
                <a href="{{ route('login.provider.redirect', [ 'provider' => 'facebook' ]) }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Mit Facebook verbinden</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            @if (false)
                <div>
                    <a href="{{ route('login.provider.redirect', [ 'provider' => 'trakt' ]) }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Mit Trakt verbinden</span>
                        Trakt.tv
                    </a>
                </div>
            @endif
        </div>
        @if ($oauth_providers->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anbieter</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($oauth_providers as $oauth_provider)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $oauth_provider->provider_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <i class="fas fa-trash-alt cursor-pointer text-sm text-red-600 hover:text-red-900" onclick="document.getElementById('destroy_{{ $oauth_provider->id }}').submit();"></i>
                                <form method="POST" action="{{ $oauth_provider->path }}" id="destroy_{{ $oauth_provider->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-container>
</x-app-layout>