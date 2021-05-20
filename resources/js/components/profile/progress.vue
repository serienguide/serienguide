<template>

    <div>

        <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Fortschritt
            </h3>
            <div class="mt-3 flex sm:mt-0 sm:ml-4">

                <select v-model="filter.sort_by" class="ml-3 rounded-md border-gray-300" @change="search">
                    <option value="name">Name</option>
                    <option value="watched_percent">Fortschritt</option>
                    <option value="episodes_left">Folgen nicht gesehen</option>
                    <option value="runtime_left">Zeit nicht gesehen</option>
                    <option value="tmdb_popularity">Popularität</option>
                    <option value="runtime_sum">Laufzeit</option>
                    <option value="max_watched_at">Gesehen</option>
                </select>
            <button @click="toggle_sort_direction" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-sort-amount-up" v-show="filter.sort_direction == 'ASC'"></i>
                <i class="fas fa-sort-amount-down" v-show="filter.sort_direction == 'DESC'"></i>
            </button>

            </div>
        </div>

        <div class="p-5" v-if="is_fetching">
            <center>
                <span class="text-3xl">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <ul class="grid grid-cols-1 gap-6 mb-3" v-else-if="models.length">
            <li :key="model.id" v-for="(model, index) in models">
                <div class="flex">
                    <div class="flex-grow">
                        <h4 class="mb-3 text-base font-bold leading-6 font-medium text-gray-900"><a :href="model.show.path" class="hover:underline">{{ model.show.name }}</a></h4>
                        <media-progress-show :model="model.show"></media-progress-show>
                    </div>
                    <div class="px-5 hidden md:block max-w-sm" v-if="model.show.next_episode_to_watch">
                        <h4 class="mb-3 text-base font-bold leading-6 font-medium text-gray-900">Nächste Folge</h4>
                        <ul class="grid grid-rows-1">
                            <card-show :model="model.show.next_episode_to_watch" :key="'next_' + model.show.next_episode_to_watch.id" img-type="backdrop" :load-next="true" @nexted="nexted(index, $event)"></card-show>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
        <div class="p-2 rounded-lg bg-gray-200 mb-3" v-else-if="(is_fetched && models.length == 0)">
            <div class="flex items-center justify-between">
                <p class="font-medium truncate text-center w-full">
                    Noch keine Serien gesehen.
                </p>
            </div>
        </div>


        <pagination :pagination="pagination" @paginating="filter.page = $event"></pagination>

    </div>

</template>

<script type="text/javascript">
    import card from '../card/show.vue';
    import pagination from '../pagination/index.vue';

    import { baseMixin } from "../../mixins/deck/base.js";

    export default {

        components: {
            card,
            pagination,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            filters: {
                required: false,
                default() {
                    return null;
                },
            },
            indexPath: {
                required: true,
                type: String,
            },
            user: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                index_path: this.indexPath,
                filter: {
                    watchable_type: 0,
                    sort_by: 'max_watched_at',
                    sort_direction: 'DESC',
                },
                show_dividers: true,
            };
        },

        methods: {
            toggle_sort_direction() {
                if (this.filter.sort_direction == 'ASC') {
                    this.filter.sort_direction = 'DEC';
                }
                else if (this.filter.sort_direction == 'DESC') {
                    this.filter.sort_direction = 'ASC';
                }

                this.search();
            },
            nexted(index, model) {
                Vue.set(this.models[index].show, 'next_episode_to_watch', model);
            }
        },

    };
</script>