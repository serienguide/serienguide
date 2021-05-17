export const baseMixin = {

    components: {
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
            is_fetched: false,
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
            axios.get(component.index_path, {
                params: component.filter
            })
                .then(function (response) {
                    component.fetched(response);
                    component.is_fetching = false;
                    component.is_fetched = true;
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
        nexted({index, model}) {
            Vue.set(this.models, index, model);
        },
        searching(query) {
            this.filter.query = query;
            this.search();
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