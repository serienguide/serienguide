<template>

    <div>

        <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <slot name="title">

                    <filter-search v-model="filter.query" @input="$emit('searching', filter.query)" v-if="hasFilterSearch"></filter-search>

                </slot>
            </h3>
            <div class="mt-3 flex sm:mt-0 sm:ml-4">

                <slot name="filter"></slot>
                <slot name="sort"></slot>
                <slot name="actions"></slot>

            </div>
        </div>

        <div class="p-5" v-if="isFetching">
            <center>
                <span class="text-3xl">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <ul class="grid gap-6 mb-3" :class="(isLine ? 'grid-rows-1 grid-cols-6-3/4 sm:grid-cols-6-1/3 md:grid-cols-6-1/4 lg:grid-cols-6 xl:grid-cols-6 overflow-auto' : 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6')" v-else-if="models.length">
            <slot name="card">
                <card-show :model="model" :key="model.id" :load-next="true" v-for="(model, index) in models" @nexted="nexted(index, $event)"></card-show>
            </slot>
        </ul>
        <div class="p-2 rounded-lg bg-gray-200 mb-3" v-else-if="isFetched && models.length == 0">
            <div class="flex items-center justify-between">
                <p class="font-medium truncate text-center w-full">
                    <slot name="empty">
                        Keine Daten vorhanden
                    </slot>
                </p>
            </div>
        </div>


        <slot name="pagination">
            <pagination :pagination="pagination" @paginating="$emit('paginating', $event)"></pagination>
        </slot>

    </div>

</template>

<script type="text/javascript">
    import card from '../show.vue';
    import filterSearch from '../../filter/search.vue';
    import pagination from '../../pagination/index.vue';

    export default {

        components: {
            card,
            filterSearch,
            pagination,
        },

        mixins: [
            //
        ],

        props: {
            models: {
                required: true,
                type: Array,
            },
            pagination: {
                required: false,
                type: Object,
                default() {
                    return null;
                },
            },
            isFetching: {
                required: true,
                default: false,
                type: Boolean,
            },
            isFetched: {
                required: false,
                default: false,
                type: Boolean,
            },
            isLine: {
                required: false,
                default: false,
                type: Boolean,
            },
            hasFilterSearch: {
                required: false,
                default: false,
                type: Boolean,
            }
        },

        data() {
            return {
                filter: {
                    query: '',
                },
            };
        },

        methods: {
            nexted(index, model) {
                this.$emit('nexted', {
                    index: index,
                    model: model,
                });
            },
        },

    };
</script>