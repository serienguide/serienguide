<template>
    <div>

        <div class="inline-block text-left">
            <div class="px-1">
                <button ref="showInputButton" @click="toggle" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-star" :class="rating_class"></i>
                </button>
            </div>

                <div ref="dropdown" v-show="is_open" class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="flex justify-between p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" @mouseleave="hovered = rating_value">
                            <i @click="rate(n)" class="fas fa-star px-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= n}" :title="'Mit ' + n + ' Punkten bewerten'" @mouseenter="hovered = n" v-for="n in 10"></i>
                            <i @click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" title="Bewertung löschen" @mouseenter="hovered = 0" v-if="! has_rating"></i>
                        </div>
                    </div>
                </div>
        </div>

    </div>

</template>

<script type="text/javascript">
    import { createPopper } from '@popperjs/core';

    import { ratingShowMixin } from "../../../mixins/media/rating/show.js";

    export default {

        mixins: [
            ratingShowMixin,
        ],

        mounted() {
            this.popperInstance = createPopper(this.$refs["showInputButton"], this.$refs["dropdown"], {
                placement: 'bottom-end',
            });
        },

        data() {
            return {
                popperInstance: null,
            };
        },

        methods: {
            toggle() {
                this.is_open = !this.is_open;
                this.popperInstance.update();
            }
        }

    };
</script>