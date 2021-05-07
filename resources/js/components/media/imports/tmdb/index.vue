<template>

    <div class="mb-3">

        <filter-search v-model="filter.query" @input="search()"></filter-search>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mt-3 mb-3" v-else-if="items.length > 0">
            <card :item="item" :uri="uri" :key="item.id" v-for="(item, index) in items"></card>
        </ul>

    </div>

</template>

<script type="text/javascript">
    import card from './card.vue';
    import filterSearch from '../../../filter/search.vue';

    export default {

        components: {
            card,
            filterSearch,
        },

        mixins: [
            //
        ],

        props: {
            mediaType: {
                required: false,
                type: String,
            },
        },

        computed: {
            //
        },

        data () {
            return {
                items: [],
                isLoading: false,
                filter: {
                    query: '',
                },
                uri: '/' + this.mediaType + '/imports/tmdb',
            };
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Datensätze konnten nicht geladen werden!');
                        console.log(error);
                    });
            },
            search() {
                this.fetch();
            },
        },

    };
</script>