<template>

    <deck-base :models="models" :pagination="pagination" :is-fetching="is_fetching" :is-fetched="is_fetched" :has-filter-search="true" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:title>
            Bewertet
        </template>

        <template v-slot:filter>

            <select v-model="filter.medium_type" class="rounded-md border-gray-300" @change="search">
                <option :value="key" v-for="(value, key) in filters.medium_types">{{ value }}</option>
            </select>

        </template>

        <template v-slot:actions>

            <button @click="show_dividers = !show_dividers" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium focus:outline-none" :class="(show_dividers ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white hover:bg-gray-50')">
                <i class="fas fa-code"></i>
            </button>

        </template>

        <template v-slot:card>

            <template v-for="date in dates">

                <li class="pb-1 border-b border-gray-200 sm:flex sm:items-center sm:justify-between" style="grid-column: 1 / -1;" v-show="show_dividers">
                    <h3 class="text-base leading-6 font-medium text-gray-900">
                        <i class="fas fa-history"></i> {{ date.title }}
                    </h3>
                    <div class="text-sm mt-3 flex sm:mt-0 sm:ml-4 text-gray-400">
                        {{ date.h }}h {{ date.m }}m
                    </div>
                </li>

                <card-show :action="model" :model="model.medium" :key="model.id" v-for="(model, index) in date.models"></card-show>

            </template>

        </template>

        <template v-slot:empty>
            Noch nichts bewertet.
        </template>

    </deck-base>

</template>

<script type="text/javascript">
    import deckBase from '../card/deck/base.vue';

    import { baseMixin } from "../../mixins/deck/base.js";

    export default {

        components: {
            deckBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            filters: {
                required: false,
                type: Object,
                default() {
                    return null;
                },
            },
            indexPath: {
                required: true,
                type: String,
            },
            user: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                dates: [],
                index_path: this.indexPath,
                filter: {
                    medium_type: 0,
                    page: 1,
                    sort_by: 'created_at',
                },
                show_dividers: true,
            };
        },

        methods: {
            fetched(response) {
                this.dates = response.data.dates;
                this.models = response.data.models.data;
                this.filter.page = response.data.models.current_page;
                this.pagination.nextPageUrl = response.data.models.next_page_url;
                this.pagination.prevPageUrl = response.data.models.prev_page_url;
                this.pagination.lastPage = response.data.models.last_page;
                this.pagination.currentPage = response.data.models.current_page;
                this.pagination.from = response.data.models.from;
                this.pagination.to = response.data.models.to;
                this.pagination.total = response.data.models.total;
            }
        },

    };
</script>