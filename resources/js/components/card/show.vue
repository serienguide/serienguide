<template>

    <li class="relative col-span-1 flex flex-col justify-between bg-white rounded-lg shadow" :itemtype="model.itemtype ? model.itemtype : false" :itemscope="model.itemtype ? '' : false">
        <div class="absolute inset-0 bg-gray-200 bg-opacity-50 z-10 inline-flex flex-col items-center justify-center" v-if="loadNext" v-show="is_nexting">
            <div><i class="fas fa-spinner fa-spin text-3xl"></i></div>
            <div>Lade nächste Episode</div>
        </div>
        <div class="rounded-t-lg h-2 w-full bg-yellow-900" :title="model.vote_average_formatted + '/10 ' + model.vote_count + ' Stimmen'" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <meta itemprop="bestRating" content="10">
            <meta itemprop="worstRating" content="0">
            <meta itemprop="ratingValue" :content="model.vote_average">
            <meta itemprop="ratingCount" :content="model.vote_count">

            <div class="bg-yellow-400 h-2 text-xs leading-none text-center text-white transition-all" :class="rating_bar_class" :style="{ width: (model.vote_average * 10) + '%' }"></div>
        </div>

        <header class="flex items-center justify-center px-3 py-1" v-if="$auth.check()">

            <div class="flex-grow"></div>
            <rating :model="model" @rated="rated($event)"></rating>
            <lists :model="model" v-if="! model.is_episode"></lists>
            <watched :model="model" v-if="! model.is_show"></watched>

        </header>

        <main class="flex-grow relative">
            <a class="" :href="(model.is_episode ? model.show.path : model.path)" :title="model.name">

                <img loading="lazy" :src="(imgType == 'poster' ? model.poster_url : model.backdrop_url)" itemprop="image" />

                <div v-if="action != null">
                    <div class="absolute left-0 right-0 bottom-0 h-24" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
                        <div class="absolute bottom-8 left-0 right-0 inline-flex items-center justify-center" v-if="action.class_name == 'watched'">
                            <a class="inline-flex items-center" :href="action.user.profile_path" :title="action.user.name + ' hat ' + model.name + ' ' + action.watched_at_diff_for_humans + ' am ' + action.watched_at_formatted + ' gesehen'">
                                <img class="h-8 w-8 rounded-full ring-2 ring-white" :src="action.user.profile_photo_url" alt="">
                                <div class="-ml-2 inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-white" ><i class="fas fa-check"></i></div>
                            </a>
                        </div>
                        <div class="absolute bottom-8 left-0 right-0 inline-flex items-center justify-center" v-else-if="action.class_name == 'rating'">
                            <a class="inline-flex items-center" :href="action.user.profile_path" :title="action.user.name + ' hat ' + model.name + ' ' + action.created_at_diff_for_humans + ' am ' + action.created_at_formatted + ' mit ' + action.rating + ' Punkten bewertet'">
                                <img class="h-8 w-8 rounded-full ring-2 ring-white" :src="action.user.profile_photo_url" alt="">
                                <div class="-ml-2 inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-white" ><i class="fas fa-star text-yellow-400"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

            </a>

        </main>

        <footer class="flex items-center px-3 py-1">
            <h3 class="flex-grow text-gray-900 leading-5 font-medium overflow-hidden whitespace-nowrap">
                <a :href="model.path" :title="model.name" class="text-center" itemprop="url" v-if="model.is_episode">
                    <div class="font-bold"><span itemprop="partOfSeason">{{ model.season.season_number }}</span>x<span itemprop="episodeNumber">{{ model.episode_number }}</span></div>
                    <div class="text-gray-400" itemprop="name">{{ model.name }}&nbsp;</div>
                </a>
                <a :href="model.next_episode.path" :title="model.next_episode.number" class="text-center" v-else-if="model.next_episode">
                    <div class="font-bold"><span itemprop="partOfSeason">{{ model.next_episode.season.season_number }}</span>x<span itemprop="episodeNumber">{{ model.next_episode.episode_number }}</span></div>
                    <div class="text-xs text-gray-400">NÄCHSTE EPISODE</div>
                </a>
                <a :href="model.path" :title="model.name" itemprop="url" v-else>
                    <span itemprop="name">{{ model.name }}</span>
                </a>
            </h3>

            <div class="ml-1" v-if="$auth.check()">
                <button type="button" class="inline-flex items-center px-3 py-3 border border-gray-300 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" :class="watched_button_class" :title="watched_button_title" :disabled="is_watching" @click="watch()">
                    <i class="fas fa-check" v-show="! is_watching"></i>
                    <i class="fas fa-spinner fa-spin" v-show="is_watching"></i>
                </button>
            </div>
        </footer>

        <div class="rounded-b-lg h-2 w-full bg-blue-900" :title="progress_title">
            <div class="bg-blue-500 h-2 text-xs leading-none text-center text-white transition-all duration-500" :class="progress_bar_class" :style="{ width: progress.percent + '%' }"></div>
        </div>

    </li>

</template>

<script type="text/javascript">
    import lists from './list/index.vue';
    import rating from './rating/show.vue';
    import watched from './watched/index.vue';

    export default {

        components: {
            lists,
            rating,
            watched,
        },

        mixins: [
            //
        ],

        props: {
            action: {
                required: false,
                type: Object,
                default() {
                    return null;
                },
            },
            model: {
                required: true,
                type: Object,
            },
            imgType: {
                required: false,
                default: 'poster',
                type: String,
            },
            loadNext: {
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
            progress_title() {
                return this.progress.watched_distinct_count + '/' + this.progress.watchable_count + ' ' + this.progress.percent + '%';
            },
            progress_bar_class() {
                if (this.progress.percent > 0 && this.progress.percent == 100) {
                    return 'rounded-bl-lg rounded-br-lg';
                }

                if (this.progress.percent > 0) {
                    return 'rounded-bl-lg';
                }

                return '';
            },
            rating_bar_class() {
                if (this.ratings.vote_average > 0 && this.ratings.vote_average == 10) {
                    return 'rounded-tl-lg rounded-tr-lg';
                }

                if (this.ratings.vote_average > 0) {
                    return 'rounded-tl-lg';
                }

                return '';
            },
            rating_points() {
                return (this.ratings.vote_average * this.ratings.vote_count);
            },
            vote_average_formatted() {
                return this.ratings.vote_average.format((this.ratings.vote_average == 10 || this.ratings.vote_average == 0) ? 0 : 1, ',', '');
            },
        },

        data () {
            return {
                progress: this.model.progress,
                ratings: {
                    vote_average: Number(this.model.vote_average),
                    vote_count: this.model.vote_count,
                },
                is_watching: false,
                is_nexting: false,
            };
        },

        mounted () {
            var component = this;
            Bus.$on(component.model.watched_event_name, function (data) {
                component.watched(data);
            });
            Bus.$on(component.model.rated_event_name, function (data) {
                component.rated(data);
            });
        },

        methods: {
            next() {
                var component = this;
                component.is_nexting = true;
                axios.get(component.model.next_path)
                    .then( function (response) {
                        if (! response.data) {
                            Vue.success('Du hast alle Episoden gesehen.');
                            return;
                        }

                        component.$emit('nexted', response.data);
                })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error(component.model.name + 'Nächste Episode konnte nicht geladen werden.');
                })
                    .then(function () {
                        component.is_nexting = false;
                });
            },
            watch() {
                var component = this;
                component.is_watching = true;
                axios.post(component.model.watched_path)
                    .then( function (response) {
                        Bus.$emit(component.model.watched_event_name, response.data);
                        Vue.success(component.model.name + ' zum ' + component.progress.watched_count + '. mal gesehen');
                        if (component.loadNext) {
                            component.next();
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
            rated(rating) {
                //
            }
        },

    };
</script>