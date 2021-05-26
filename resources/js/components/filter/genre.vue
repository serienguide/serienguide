<template>

    <div>
        <multiselect v-model="value" track-by="id" label="name" :options="options" :searchable="true":allow-empty="false" @input="$emit('filtering', { filter: filterKey, value: value.id })"></multiselect>
    </div>

</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {

        components: {
            Multiselect,
        },

        props: {
            filterOptions: {
                required: true,
                type: Array,
            },
            filterKey: {
                required: false,
                type: String,
                default: 'genre_id',
            },
        },

        data() {
            return {
                value: this.filterOptions[0],
                options: this.filterOptions,
            };
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>