<template>

    <deck-base :models="models" :pagination="pagination" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="false" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:empty>
            Keine Einträge vorhanden.
        </template>

        <template v-slot:card>
            <card-show :model="model.medium" :key="model.id" :load-next="false" v-for="(model, index) in models"></card-show>
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
            },
        },

        data () {
            return {
                index_path: this.model.items_path,
            };
        },

    };
</script>