<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listen
        </h2>
    </x-slot>

    @livewire('users.lists.index', ['user' => $user])
</x-app-layout>