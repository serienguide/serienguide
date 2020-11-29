<x-app-layout>
    <x-slot name="header">
        Listen
    </x-slot>

    <x-container class="py-4">
        @livewire('users.lists.index', ['user' => $user])
    </x-container>
</x-app-layout>