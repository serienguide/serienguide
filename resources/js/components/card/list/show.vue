<template>

    <li class="flex items-center" v-if="is_available">
        <i class="h-4 w-4 text-sm text-gray-400 fas fa-spinner fa-spin" v-show="is_toggling"></i>
        <input @click="toggle" :id="model.id + '_' + list.id" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" :checked="(list.items.length > 0)" :disabled="is_toggling" @click.prevent="" v-show="! is_toggling">
        <label :for="model.id + '_' + list.id" class="ml-2 block text-sm leading-5 text-gray-900">
            {{ list.name }}
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
            list: {
                required: true,
                type: Object,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {
            is_available() {
                if (this.model.is_movie && this.list.type == 'currently_watching') {
                    return false;
                }

                return true;
            }
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
                axios.put(component.list.toggle_path)
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