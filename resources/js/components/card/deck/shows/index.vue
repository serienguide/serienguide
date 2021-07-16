<template>

    <deck-base :models="models" :pagination="pagination" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="true" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:filter>

            <filter-genre :filter-options="filterOptions.genre" @filtering="filtering($event)"></filter-genre>
            <filter-person class="ml-1" @filtering="filtering($event)"></filter-person>

        </template>

        <template v-slot:empty>
            Keine Serien vorhanden.
        </template>

    </deck-base>

</template>

<script type="text/javascript">
    import deckBase from '../base.vue';
    import filterGenre from '../../../filter/genre.vue';
    import filterPerson from '../../../filter/person.vue';

    import { baseMixin } from "../../../../mixins/deck/base.js";

    export default {

        components: {
            deckBase,
            filterGenre,
            filterPerson,
        },

        mixins: [
            baseMixin,
        ],

        data () {
            return {
                index_path: '/shows',
            };
        },

    };
</script>