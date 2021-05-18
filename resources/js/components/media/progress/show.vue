<template>

    <div>

        <div class="my-3 rounded h-4 w-full bg-blue-900" :title="progress.watched_count + '/' + progress.watchable_count + ' ' + progress.percent + '%'">
            <div class="bg-blue-500 h-4 text-xs leading-none text-center text-white" :class="progressbar_class" :style="{width: progress.percent + '%'}"></div>
        </div>
        <div>
            <span class="font-bold">{{ progress.watched_count }}</span>
            von <span class="font-bold">{{ progress.watchable_count }}</span>
            (<span class="font-bold">{{ Math.floor(progress.watched_runtime / 60) }}h
            {{ progress.watched_runtime % 60 }}m</span>)
            {{ progress.labels.plural }} gesehen.
            Es {{ (progress.unwatched_count == 1 ? ' ist' : 'sind') }}
            noch <span class="font-bold">{{ progress.unwatched_count }}</span>
            {{ (progress.unwatched_count == 1 ? progress.labels.singular : progress.labels.plural) }}
            übrig
            (<span class="font-bold">{{ Math.floor(progress.unwatched_runtime / 60) }}h {{ progress.unwatched_runtime % 60 }}m</span>).
        </div>
        <div v-if="last_watched">
            Zuletzt gesehen
            <a class="text-blue-500 hover:text-blue-600" :href="last_watched.watchable.path" v-if="model.is_collection">{{ last_watched.watchable.name }}</a>
            <a class="text-blue-500 hover:text-blue-600" :href="last_watched.watchable.path" v-else-if="model.is_show">{{ last_watched.watchable.season.season_number }}x{{ last_watched.watchable.episode_number }} {{ last_watched.watchable.name }}</a>
            {{ last_watched.watched_at_diff_for_humans }} am {{ last_watched.watched_at_for_progress }}
        </div>

    </div>

</template>

<script type="text/javascript">
    export default {

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {
            progressbar_class() {
                if (this.progress.percent == 100) {
                    return 'rounded-l rounded-r';
                }

                if (this.progress.percent > 0) {
                    return 'rounded-l';
                }

                return '';
            }
        },

        data() {
            return {
                is_progressing: false,
                progress: this.model.progress,
                last_watched: this.model.last_watched,
            };
        },

        methods: {
            progressed(data) {
                var component = this;
                component.is_progressing = true;
                axios.get(component.model.path)
                    .then(function (response) {
                        component.progress = response.data.progress;
                        component.last_watched = response.data.last_watched;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Fortschritt konnten nicht geladen werden.');
                    })
                    .then(function () {
                        component.is_progressing = false;
                });
            }
        },

        mounted() {
            var component = this;
            Bus.$on(component.model.progress_event_name, function (data) {
                component.progressed(data);
            });
        }

    };
</script>