<template>

    <div itemprop="containsSeason" itemtype="http://schema.org/TVSeason" itemscope="">

        <div class="flex my-3 items-end">
            <img @click="show" :src="model.poster_url_sm" class="rounded-md mr-1 cursor-pointer" v-if="model.poster_path">
            <div @click="show" class="cursor-pointer flex-grow">
                <div class="flex">
                    <h3 class="font-bold">Staffel <span itemprop="seasonNumber">{{ model.season_number }}</span></h3>
                    <div wire:loading.delay class="ml-2 pointer-events-none">
                        <i class="fa fa-spinner fa-spin text-gray-400"></i>
                    </div>
                </div>
                <div class="text-sm text-gray-400"><span v-if="$auth.check()">{{ progress.watched_count }}/</span><span itemprop="numberOfEpisodes">{{ model.episode_count }}</span> Folgen</div>
            </div>
            <div v-if="$auth.check()">
                <buttons-watched-create class="relative inline-block" :model="model" :progress="progress" :is-stand-alone="true" @watching="watching"></buttons-watched-create>
            </div>
        </div>
        <div class="my-3 rounded h-2 w-full bg-blue-900" :title="progress.watched_count + '/' + progress.watchable_count + ' ' + progress.percent + '%'">
            <div class="bg-blue-500 h-2 text-xs leading-none text-center text-white transition-all duration-500" :class="progressbar_class" :style="{width: progress.percent + '%'}"></div>
        </div>

        <div class="p-5" v-if="is_fetching">
            <center>
                <span class="text-3xl">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>

        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95" v-else-if="episodes.length">
            <ul v-show="is_showing" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-3">
                <card-show :model="episode" :key="episode.id" img-type="backdrop" v-for="(episode, index) in episodes"></card-show>
            </ul>
        </transition>
    </div>

</template>

<script type="text/javascript">
    export default {

        props: {
            model: {
                tpye: Object,
                required: true,
            },
            isCurrent: {
                type: Boolean,
                required: true,
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
                episodes: [],
                progress: this.model.progress,
                is_showing: false,
                is_fetched: false,
                is_fetching: false,
                is_progressing: false,
            };
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(component.model.episodes_path)
                    .then( function (response) {
                        component.episodes = response.data;
                        component.is_fetched = true;
                        component.is_fetching = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Episoden konnten nicht geladen werden.');
                    });
            },
            progressed() {
                var component = this;
                component.is_progressing = true;
                axios.get(component.model.path)
                    .then(function (response) {
                        component.progress = response.data.progress;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Fortschritt konnten nicht geladen werden.');
                    })
                    .then(function () {
                        component.is_progressing = false;
                });
            },
            show() {
                if (! this.is_fetched) {
                    this.fetch();
                }

                this.is_showing = ! this.is_showing;
            },
            watching() {
                if (this.is_showing) {
                    return;
                }

                this.show();
            }
        },

        mounted() {

            var component = this;
            Bus.$on(component.model.progress_event_name, function (data) {
                component.progressed(data);
            });

            if (! this.isCurrent) {
                return;
            }

            this.fetch();
            this.is_showing = true;
        },

    };
</script>