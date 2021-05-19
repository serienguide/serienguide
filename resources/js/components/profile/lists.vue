<template>

    <div>

        <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Listen
            </h3>
            <div class="mt-3 flex sm:mt-0 sm:ml-4">



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
            <template v-for="(model, index) in models">

                <li class="py-3" v-if="model.is_first_custom_list"></li>
                <li :key="model.id">
                    <div class="flex items-stretch px-4 py-4 sm:px-6 liste-info">
                        <div class="poster-items-wrapper">
                            <div class="posters">
                                <a :href="model.path">
                                    <div class="poster-items-wrapper">
                                        <div class="poster-items">
                                            <div class="poster-item" v-for="(item, item_index) in model.items">
                                                <div class="poster">
                                                    <img :src="item.medium.poster_url">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex-grow px-3">
                            <div class="py-3">
                                <div class="flex items-center">
                                    <div class="flex-grow flex items-center">
                                        <img loading="lazy" class="h-12 w-12 rounded-full mr-3" :src="model.user.profile_photo_url" alt="">
                                        <div>
                                            <a class="font-bold hover:text-gray-700" :href="model.path">{{ model.name }}</a>
                                            <div class="text-sm">von <a class="hover:text-gray-700" :href="model.user.profile_path">{{ model.user.name }}</a></div>
                                        </div>
                                    </div>
                                    <a class="text-gray-400 hover:text-gray-700" :href="model.path">
                                        <svg class="h-5 w-5 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="mt-3">
                                    {{ model.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

            </template>
        </ul>
        <div class="p-2 rounded-lg bg-gray-200 mb-3" v-else-if="(is_fetched && models.length == 0)">
            <div class="flex items-center justify-between">
                <p class="font-medium truncate text-center w-full">
                    Keine Listen vorhanden.
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