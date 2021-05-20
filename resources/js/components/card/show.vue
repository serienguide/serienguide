<template>

    <li class="relative col-span-1 flex flex-col justify-between bg-white rounded-lg shadow" :itemtype="model.itemtype ? model.itemtype : false" :itemscope="model.itemtype ? '' : false">
        <div class="absolute inset-0 bg-gray-200 bg-opacity-50 z-10 inline-flex flex-col items-center justify-center" v-if="loadNext" v-show="is_nexting">
            <div><i class="fas fa-spinner fa-spin text-3xl"></i></div>
            <div>Lade nächste Episode</div>
        </div>
        <div class="rounded-t-lg h-2 w-full bg-yellow-900" :title="rating_stats.avg_formatted + '/10 ' + rating_stats.count + ' Stimmen'" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <meta itemprop="bestRating" content="10">
            <meta itemprop="worstRating" content="0">
            <meta itemprop="ratingValue" :content="rating_stats.avg">
            <meta itemprop="ratingCount" :content="rating_stats.count">

            <div class="bg-yellow-400 h-2 text-xs leading-none text-center text-white transition-all" :class="rating_bar_class" :style="{ width: (rating_stats.avg * 10) + '%' }"></div>
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
                <div class="absolute bottom-3 left-3" v-else>
                    <template v-if="model.is_episode">

                        <template v-if="model.episode_number == 1">

                            <span class="inline-flex items-center mb-1 px-3 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white" v-if="model.season.season_number == 1">
                                Serienstart
                            </span>

                            <span class="inline-flex items-center mb-1 px-3 py-0.5 rounded-full text-xs font-bold bg-green-500 text-white" v-else>
                                Staffelstart
                            </span>

                        </template>

                        <div class="flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-yellow-300 text-yellow-800" :class="(model.first_aired_de_at == null ? 'bg-blue-100 text-blue-800' : 'bg-yellow-300 text-yellow-800')" v-if="model.first_aired_at">
                            {{ model.first_aired_at_formatted }} <span v-if="(model.first_aired_de_at != null && model.show.air_time)">{{ model.show.air_time.substring(0, -3) }}</span>
                        </div>

                    </template>
                    <template v-else-if="model.is_movie">
                        <div class="flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-yellow-300 text-yellow-800" v-if="model.released_at">
                            {{ model.released_at_formatted }}
                        </div>
                    </template>
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

            <watched-create :model="model" :progress="progress"></watched-create>

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
    import watchedCreate from './watched/create.vue';

    export default {

        components: {
            lists,
            rating,
            watched,
            watchedCreate,
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
                if (this.rating_stats.avg > 0 && this.rating_stats.avg == 10) {
                    return 'rounded-tl-lg rounded-tr-lg';
                }

                if (this.rating_stats.avg > 0) {
                    return 'rounded-tl-lg';
                }

                return '';
            },
        },

        data () {
            return {
                progress: this.model.progress,
                rating_stats: this.model.rating_stats,
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
                        Vue.error(component.model.name + ' nächste Episode konnte nicht geladen werden.');
                })
                    .then(function () {
                        component.is_nexting = false;
                });
            },
            watched(data) {
                this.progress = data.progress;
                if (this.loadNext) {
                    this.next();
                }
            },
            rated(data) {
                this.rating_stats = data.rating_stats;
            },
        },

    };
</script>