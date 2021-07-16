<template>

    <div>
        <multiselect v-model="value" track-by="id" label="name" :options="options" :internal-search="false" :clear-on-select="false" :close-on-select="false" :multiple="true" :searchable="true" :options-limit="300" :limit="3" :loading="is_fetching" placeholder="Schauspieler" @search-change="fetch" @input="$emit('filtering', { filter: filterKey, value: value_ids })"></multiselect>
    </div>

</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        components: {
            Multiselect,
        },

        props: {
            filterKey: {
                required: false,
                type: String,
                default: 'person_ids',
            },
        },

        computed: {
            value_ids() {
                return this.value.reduce(function (ids, item) {
                    ids.push(item.id);

                    return ids;
                }, []);
            },
        },

        mounted() {
            this.fetch('ryan');
        },

        data() {
            return {
                value: [],
                options: [],
                is_fetching: false,
                index_path: '/filters/people'
            };
        },

        methods: {
            fetch(query) {
                if (query.length < 3) {
                    return;
                }
                var component = this;
                component.is_fetching = true;
                axios.get(component.index_path, {
                    params: {
                        query: query,
                    }
                })
                    .then(function (response) {
                        component.options = response.data;
                        component.is_fetched = true;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Datensätze konnten nicht geladen werden.');
                    })
                    .then(function () {
                        component.is_fetching = false;
                    });
            },
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>