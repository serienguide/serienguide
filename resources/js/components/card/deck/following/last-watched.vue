<template>

    <deck-base :models="models" :pagination="pagination" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="false" @paginating="filter.page = $event">

        <template v-slot:title>
            Aktionen von Freunden
        </template>

        <template v-slot:card>

            <card-show :action="model" :model="model.watchable" :key="model.id" v-for="(model, index) in models"></card-show>

        </template>

        <template v-slot:empty>
            Du folgst noch niemandem.
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
            indexPath: {
                required: true,
                type: String,
            },
        },

        data () {
            return {
                index_path: this.indexPath,
            };
        },

        methods: {
            //
        },

    };
</script>