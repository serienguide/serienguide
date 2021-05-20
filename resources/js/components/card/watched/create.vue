<template>

    <div class="ml-1" v-if="$auth.check()">
        <button type="button" class="inline-flex items-center px-3 py-3 border border-gray-300 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" :class="watched_button_class" :title="watched_button_title" :disabled="is_watching" @click="watch()">
            <i class="fas fa-check" v-show="! is_watching"></i>
            <i class="fas fa-spinner fa-spin" v-show="is_watching"></i>
        </button>
    </div>

</template>

<script type="text/javascript">
    export default {

        props: {
            model: {
                required: true,
                type: Object,
            },
            progress: {
                required: true,
                type: Object,
            },
            isStandAlone: {
                required: false,
                default: false,
                type: Boolean,
            },
        },

        computed: {
            watched_button_class() {
                if (! this.$auth.check()) {
                    return 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700';
                }

                if (this.progress.watched_count == 0) {
                    return 'bg-white text-gray-700 border-gray-300 hover:text-gray-500';
                }

                if (this.progress.watched_count % 2 == 0) {
                    return 'bg-green-600 text-white border-green-600 hover:bg-green-700';
                }

                return 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700';
            },
            watched_button_title() {
                if (! this.$auth.check()) {
                    return '';
                }

                if (this.progress.watched_count > 0) {
                    return this.progress.watched_count + ' mal gesehen';
                }

                return 'Noch nicht gesehen';
            },
        },

        data() {
            return {
                is_watching: false,
            };
        },

        methods: {
            watch() {
                var component = this;
                component.is_watching = true;
                component.$emit('watching');
                axios.post(component.model.watched_path)
                    .then( function (response) {
                        Bus.$emit(component.model.watched_event_name, response.data);

                        component.watched(response.data);

                        if (component.model.is_movie && component.model.collection) {
                            Bus.$emit(component.model.collection.progress_event_name, response.data);
                        }
                        else if (component.model.is_episode) {
                            Bus.$emit(component.model.show.progress_event_name, response.data);
                            Bus.$emit(component.model.season.progress_event_name, response.data);
                        }
                        else if (component.model.is_show) {
                            Bus.$emit(component.model.progress_event_name, response.data);
                        }
                        else if(component.model.is_season) {
                            Bus.$emit(component.model.progress_event_name, response.data);
                            Bus.$emit(component.model.show.progress_event_name, response.data);
                        }

                        if (component.model.is_show || component.model.is_season) {
                            for (var event_name in response.data.watched) {
                                Bus.$emit(event_name, response.data.watched[event_name]);
                            }
                        }

                        if (component.model.is_show) {
                            Vue.success(component.progress.watched_count + ' Episoden der Serie ' + component.model.name + ' als gesehen markiert.');
                            for (var index in component.model.seasons) {
                                Bus.$emit(component.model.seasons[index].progress_event_name, response.data);
                            }
                        }
                        else if (component.model.is_season) {
                            Vue.success(component.progress.watched_count + ' Episoden der ' + component.model.season_number + '. Staffel der Serie ' + component.model.show.name + ' als gesehen markiert.');
                        }
                        else {
                            Vue.success(component.model.name + ' zum ' + component.progress.watched_count + '. mal gesehen.');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error(component.model.name + ' konnten nicht als gesehen markiert werden.');
                    })
                    .then(function () {
                        component.is_watching = false;
                });
            },
            watched(data) {
                this.progress = data.progress;
            },
        },

        mounted() {
            if (! this.isStandAlone) {
                return;
            }

            var component = this;
            Bus.$on(component.model.watched_event_name, function (data) {
                component.watched(data);
            });
        }
    };
</script>