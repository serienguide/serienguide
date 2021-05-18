<template>
    <div>

        <div class="inline-block text-left">
            <div class="px-1">
                <button @click="is_open = ! is_open" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa- fa-star" :class="rating_class"></i>
                </button>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <div v-show="is_open" class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="flex justify-between p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" @mouseleave="hovered = rating_value">
                            <i @click="rate(n)" class="fas fa-star px-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= n}" :title="'Mit ' + n + ' Punkten bewerten'" @mouseenter="hovered = n" v-for="n in 10"></i>
                            <i @click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" title="Bewertung löschen" @mouseenter="hovered = 0" v-if="! has_rating"></i>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

    </div>

</template>

<script type="text/javascript">
    import { ratingShowMixin } from "../../../mixins/media/rating/show.js";

    export default {

        mixins: [
            ratingShowMixin,
        ],

    };
</script>