<template>

    <li class="my-1" v-if="is_editing">
        <div class="flex h-10 rounded-md shadow-sm">
            <input type="text" v-model="form.watched_at_formatted" class="rounded-none rounded-l-md block w-full sm:text-sm border-gray-300">
            <button type="button" class="-ml-px relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" :disabled="is_updating" @click="update">
                <i class="fas fa-save text-sm text-gray-500 hover:text-gray-700 cursor-pointer" title="Speichern" v-show="! is_updating"></i>
                <i class="text-gray-400 text-sm fas fa-spinner fa-spin" v-show="is_updating"></i>
            </button>
            <button type="button" class="-ml-px relative inline-flex items-center space-x-2 px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" @click="is_editing = false">
                <i class="mr-1 fas fa-times text-sm text-gray-500 hover:text-gray-700 cursor-pointer" title="Abbrechen"></i>
            </button>
        </div>
    </li>
    <li class="my-1" v-else>
        <div class="flex items-center h-10 justify-between">
            <div @click="is_editing = true" class="text-sm leading-5 font-medium truncate cursor-pointer flex items-center" :title="model.watched_at_formatted + (model.created_at != model.watched_at ? ' Erstellt: ' + model.created_at_formatted : '')">
                <div class="inline-flex items-center p-2 border border-gray-300 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                    <i class="fas fa-check"></i>
                </div>
                <div class="ml-1">{{ model.watched_at_diff_for_humans }}</div>
            </div>
            <div class="ml-2 mr-3 flex-shrink-0 flex">
                <i @click="destroy" class="fas fa-trash-alt text-sm text-gray-500 hover:text-gray-700 cursor-pointer" title="Löschen" v-show="! is_destroying"></i>
                <i class="text-gray-400 text-sm fas fa-spinner fa-spin" v-show="is_destroying"></i>
            </div>
        </div>
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
                is_editing: false,
                is_destroying: false,
                is_updating: false,
                form: {
                    watched_at_formatted: this.model.watched_at_formatted,
                },
            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.model.path)
                    .then(function (response) {
                        component.$emit('destroyed', response.data);
                })
                    .catch(function (error) {
                        Vue.error('Datensatz konnte nicht gelöscht werden');
                });
            },
            update() {
                var component = this;
                component.is_updating = true;
                axios.put(component.model.path, component.form)
                    .then( function (response) {
                        component.is_editing = false;
                        component.$emit('updated', response.data);
                        Vue.success('Datensatz gespeichert.');
                })
                    .catch(function (error) {
                        Vue.error('Datensatz konnte nicht gespeichert werden');
                })
                    .then( function () {
                        component.is_updating = false;
                    });
            },
        },

    };
</script>