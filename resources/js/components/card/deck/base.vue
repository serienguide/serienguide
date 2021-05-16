<template>

    <div>

        <div class="mb-3">

            <filter-search v-model="filter.query" @input="search()"></filter-search>

        </div>

        <div class="p-5" v-if="is_fetching">
            <center>
                <span class="text-3xl">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3" v-else-if="models.length">
            <card-show :model="model" :key="model.id" :load-next="true" v-for="(model, index) in models"></card-show>
        </ul>

        <pagination :pagination="pagination" @paginating="filter.page = $event"></pagination>

    </div>

</template>

<script type="text/javascript">
    import card from '../show.vue';
    import filterSearch from '../../filter/search.vue';
    import pagination from '../../pagination.vue';

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
            //
        },

        computed: {
            page() {
                return this.filter.page;
            },
        },

        data () {
            return {
                filter: {
                    query: '',
                    page: 1,
                },
                is_fetching: false,
                models: [],
                pagination: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                    currentPage: 0,
                    from: 0,
                    to: 0,
                    total: 0,
                },
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.is_fetching = true;
                axios.get('/shows', {
                    params: component.filter
                })
                    .then(function (response) {
                        component.fetched(response);
                        component.is_fetching = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Datensätze konnten nicht geladen werden.');
                    });
            },
            fetched(response) {
                this.models = response.data.data;
                this.filter.page = response.data.current_page;
                this.pagination.nextPageUrl = response.data.next_page_url;
                this.pagination.prevPageUrl = response.data.prev_page_url;
                this.pagination.lastPage = response.data.last_page;
                this.pagination.currentPage = response.data.current_page;
                this.pagination.from = response.data.from;
                this.pagination.to = response.data.to;
                this.pagination.total = response.data.total;
            },
            search() {
                this.filter.page = 1;
                this.fetch();
            },
        },

        watch: {
            page () {
                this.fetch();
            },
        },

    };
</script>