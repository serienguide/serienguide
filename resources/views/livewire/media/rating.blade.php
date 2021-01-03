<div class="flex" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    <meta itemprop="bestRating" content="10">
    <meta itemprop="worstRating" content="0">

    <div style="position: relative; padding: 0 10px;">
        <i class="fa fa-star fa-4x text-yellow-400"></i>
        <b id="" style="position: absolute; top: 20px; left: 0; width: 100%; text-align: center; font-size: 20px;" itemprop="ratingValue" content="{{ $model->vote_average }}">{{ number_format($model->vote_average, (in_array($model->vote_average, [0, 10]) ? 0 : 1) , ',', '') }}</b>
    </div>
    <div clas="flex flex-col justify-center">
        <div class="flex items-center h-1/2">
            <div class="inline-flex items-center justify-between" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" x-data="{ rating: {{ is_null($user_rating) ? 0 : $user_rating->rating }}, hovered: {{ is_null($user_rating) ? 0 : $user_rating->rating }} }" @mouseleave="hovered = rating > 0 ? rating : 0">
                <span class="font-bold mr-3">Deine Bewertung:</span>
                @auth
                    @if (! is_null($user_rating))
                        <i wire:click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-red-500" @mouseenter="hovered = 0"></i>
                    @endif
                    @for ($i = 1; $i <= 10; $i++)
                        <i wire:click="rate({{ $i }})" data-rating="{{ $i }}" class="fas fa-star pl-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= {{ $i }}}" @mouseenter="hovered = {{ $i }}"></i>
                    @endfor
                @else
                    @for ($i = 1; $i <= 10; $i++)
                        <i data-rating="{{ $i }}" class="fas fa-star pl-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= {{ $i }}}" @mouseenter="hovered = {{ $i }}"></i>
                    @endfor
                @endauth
                <span class="ml-3 font-bold" x-text="hovered"></span>/10
            </div>
        </div>
        <div class="flex items-center h-1/2">
            <div class="inline-flex items-center justify-between">
                Bewertung: <span class="ml-3 font-bold">{{ number_format($model->vote_average, (in_array($model->vote_average, [0, 10]) ? 0 : 1) , ',', '') }}</span>/10 bei <span class="mx-1" itemprop="ratingCount">{{ $model->vote_count }}</span> Stimmen
            </div>
        </div>
    </div>
</div>
