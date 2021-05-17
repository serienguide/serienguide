<template>

    <deck-base :models="models" :pagination="pagination" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="true" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:empty>
            Keine Filme vorhanden.
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

        data () {
            return {
                index_path: '/movies',
            };
        },

    };
</script>