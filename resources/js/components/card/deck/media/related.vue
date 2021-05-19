<template>

    <deck-base :models="models" :pagination="pagination" :is-line="true" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="false" v-if="(is_fetched && models.length)">

        <template v-slot:title>
            Das könnte dir auch gefallen
        </template>

    </deck-base>

</template>

<script type="text/javascript">
    import deckBase from '../base.vue';

    import { baseMixin } from "../../../../mixins/deck/base.js";

    export default {

        components: {
            deckBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            model: {
                required: true,
                type: Object,
            }
        },

        data () {
            return {
                index_path: this.model.related_path,
            };
        },

        methods: {
            fetched(response) {
                this.models = response.data;
            }
        },

    };
</script>