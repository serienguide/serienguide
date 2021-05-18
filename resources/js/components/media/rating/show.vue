<template>

    <div class="flex" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="bestRating" content="10">
        <meta itemprop="worstRating" content="0">
        <meta itemprop="ratingValue" :content="rating_stats.avg">
        <meta itemprop="ratingCount" :content="rating_stats.count">

        <div class="relative px-1" style="position: relative; padding: 0 10px;">
            <i class="fa fa-star fa-4x text-yellow-400"></i>
            <b id="" style="position: absolute; top: 20px; left: 0; width: 100%; text-align: center; font-size: 20px;" itemprop="ratingValue" :content="rating_stats.avg">{{ rating_stats.avg_formatted }}</b>
        </div>
        <div clas="flex flex-col justify-center">
            <div class="flex items-center h-1/2">
                <div class="inline-flex items-center justify-between" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                    <span class="font-bold mr-3">Deine Bewertung:</span>
                    <div class="flex justify-between" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" @mouseleave="hovered = rating_value">
                        <i @click="rate(n)" class="fas fa-star pl-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= n}" :title="'Mit ' + n + ' Punkten bewerten'" @mouseenter="hovered = n" v-for="n in 10"></i>
                        <i @click="rate(0)" class="fas fa-trash-alt pl-1 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" title="Bewertung löschen" @mouseenter="hovered = 0" v-if="! has_rating"></i>
                    </div>
                    <span class="ml-3 font-bold">{{ hovered }}</span>/10
                </div>
            </div>
            <div class="flex items-center h-1/2">
                <div class="inline-flex items-center justify-between">
                    Bewertung: <span class="ml-3 font-bold">{{ rating_stats.avg_formatted }}</span>/10 bei <span class="mx-1" itemprop="ratingCount">{{ rating_stats.count }}</span> Stimmen
                </div>
            </div>
        </div>

    </div>

</template>

<script type="text/javascript">
    import { ratingShowMixin } from "../../../mixins/media/rating/show.js";

    export default {

        mixins: [
            ratingShowMixin,
        ],

        data() {
            return {
                rating_stats: this.model.rating_stats,
            };
        },

        methods: {
            rated(data) {
                Vue.set(this, 'rating', data.rating);
                this.hovered = this.rating_value;
                this.rating_stats = data.rating_stats;
            },
        },

    };
</script>