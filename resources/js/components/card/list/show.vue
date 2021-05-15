<template>

    <li class="flex items-center">
        <i class="h-4 w-4 text-sm text-gray-400 fas fa-spinner fa-spin" v-show="is_toggling"></i>
        <input @click="toggle" :id="model.id" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" :checked="(model.items.length > 0)" :disabled="is_toggling" @click.prevent="" v-show="! is_toggling">
        <label :for="model.id" class="ml-2 block text-sm leading-5 text-gray-900">
            {{ model.name }}
        </label>
    </li>

</template>

<script type="text/javascript">
    export default {

        components: {
            //
        },

        mixins: [
            //
        ],

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {
            //
        },

        data () {
            return {
                is_toggling: false,
            };
        },

        mounted () {
            //
        },

        methods: {
            toggle() {
                var component = this;
                component.is_toggling = true;
                axios.put(component.model.toggle_path)
                    .then( function (response) {
                        component.$emit('toggled', response.data);
                    })
                    .catch(function (error) {
                        Vue.error('Listeneintrag konnte nicht aktualisiert werden.');
                    })
                    .then(function () {
                        component.is_toggling = false;
                    });
            },
        },

    };
</script>