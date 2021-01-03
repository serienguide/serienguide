<x-app-layout :html-attributes="$html_attributes">
    <x-slot name="header">
        Kalender
    </x-slot>
    <x-container class="py-4">
        @livewire('calendar.index', ['start_of_week' => $start_of_week])
    </x-container>

    <div class="fixed opacity-25 hover:opacity-100" style="top:35%; right: 25px;">
        <a href="{{ route('calendar.index', ['year' => $next_week->year, 'week' => $next_week->week]) }}" title="KW {{ str_pad($next_week->week, 2, '0', STR_PAD_LEFT) }} {{ $next_week->year }}">
            <i class="fas fa-chevron-right fa-3x"></i>
        </a>
    </div>

    <div class="fixed opacity-25 hover:opacity-100" style="top:35%; left: 25px;">
        <a href="{{ route('calendar.index', ['year' => $previous_week->year, 'week' => $previous_week->week]) }}" title="KW {{ str_pad($previous_week->week, 2, '0', STR_PAD_LEFT) }} {{ $previous_week->year }}">
            <i class="fas fa-chevron-left fa-3x"></i>
        </a>
    </div>
</x-app-layout>