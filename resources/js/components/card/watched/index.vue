<template>
    <div>
        <div class="inline-block text-left">
            <div class="px-1">
                <button ref="showInputButton" @click="toggle" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" :class="(isStandAlone ? ' px-3 py-3 border border-gray-300 rounded-full' : '')" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <div v-show="is_open"
                ref="dropdown"
                class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10"
                :style="(isStandAlone ? 'min-width: 250px;' : 'width: 90%; min-width: 250px;')">
                <div class="rounded-md bg-white shadow-xs">
                    <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <div class="flex items-center mb-3">
                            <div class="flex-grow font-bold">Gesehen</div>
                            <div class="hidden">
                                <i class="fas fa-plus text-sm leading-5 text-gray-900 cursor-pointer"></i>
                            </div>
                        </div>
                        <div v-if="is_fetching" class="p-5">
                            <center>
                                <span style="font-size: 48px;">
                                    <i class="fas fa-spinner fa-spin"></i><br />
                                </span>
                                Lade Daten..
                            </center>
                        </div>
                        <ul v-else>
                            <li>
                                <button @click="create" type="button" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-center text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" :disabled="is_watching">
                                    <span v-show="! is_watching">Gesehen</span>
                                    <span><i class="text-gray-400 fas fa-spinner fa-spin" v-show="is_watching"></i></span>
                                    &nbsp
                                </button>
                            </li>
                            <show :model="item" :key="item.id" v-for="(item, index) in items" @destroyed="destroyed(index, $event)" @updated="updated(index, $event)"></show>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script type="text/javascript">
    import { createPopper } from '@popperjs/core';

    import show from './show.vue';

    export default {

        components: {
            show,
        },

        mixins: [
            //
        ],

        props: {
            model: {
                required: true,
                type: Object,
            },
            isStandAlone: {
                required: false,
                type: Boolean,
                default: false,
            },
        },

        computed: {
            //
        },

        data () {
            return {
                is_open: false,
                items: [],
                is_fetching: true,
                is_fetched: false,
                is_watching: false,
                popperInstance: null,
            };
        },

        mounted () {

            this.popperInstance = createPopper(this.$refs["showInputButton"], this.$refs["dropdown"], {
                placement: 'bottom-end',
            });

            var component = this;
            Bus.$on(component.model.watched_event_name, function (data) {
                component.fetch();
            });

            component.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(component.model.watched_path)
                    .then( function (response) {
                        component.items = response.data;
                        component.is_fetched = true;
                        component.is_fetching = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Daten konnten nicht geladen werden.');
                    });
            },
            create() {
                var component = this;
                component.is_watching = true;
                axios.post(component.model.watched_path)
                    .then(function (response) {
                        component.created(response.data);
                        Bus.$emit(component.model.watched_event_name, response.data);
                        if (component.model.is_movie) {
                            Bus.$emit(component.model.collection.progress_event_name, response.data);
                        }
                        else if (component.model.is_episode) {
                            Bus.$emit(component.model.show.progress_event_name, response.data);
                            Bus.$emit(component.model.season.progress_event_name, response.data);
                        }
                        Vue.success(component.model.name + ' zum ' + response.data.progress.watched_count + '. mal gesehen');
                })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error(component.model.name + ' konnten nicht als gesehen markiert werden.');
                })
                    .then( function () {
                        component.is_watching = false;
                });
            },
            created(data) {
                this.items.unshift(data.watched);
            },
            updated(index, data) {
                Vue.set(this.items, index, data.watched);
            },
            destroyed(index, data) {
                this.items.splice(index, 1);
                Bus.$emit(this.model.watched_event_name, data);
                if (this.model.is_movie) {
                    Bus.$emit(this.model.collection.progress_event_name, data);
                }
                else if (this.model.is_episode) {
                    Bus.$emit(this.model.show.progress_event_name, data);
                    Bus.$emit(this.model.season.progress_event_name, data);
                }
                Vue.success('Datensatz wurde gelöscht.');
            },
            toggle() {
                this.is_open = !this.is_open;
                this.popperInstance.update();
            },
        },

    };
</script>