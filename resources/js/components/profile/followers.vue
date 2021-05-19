<template>

    <div>

        <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Follower
            </h3>
            <div class="mt-3 flex sm:mt-0 sm:ml-4">

                <select v-model="filter.follow_type" class="rounded-md border-gray-300" @change="search">
                    <option value="followers">Abonnenten</option>
                    <option value="followings">abonniert</option>
                </select>

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
        <div class="mb-3 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" v-else-if="models.length">
            <template v-for="(model, index) in models">

                <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" :src="model.profile_photo_url" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                    <a :href="model.profile_path" class="focus:outline-none">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">
                                {{ model.name }}
                            </p>
                            <p class="text-sm text-gray-500 truncate">
                                {{ model.followers_count }} Abonnenten
                            </p>
                        </a>
                    </div>
                </div>

            </template>
        </div>
        <div class="p-2 rounded-lg bg-gray-200 mb-3" v-else-if="(is_fetched && models.length == 0)">
            <div class="flex items-center justify-between">
                <p class="font-medium truncate text-center w-full">
                    Keine Nutzer vorhanden.
                </p>
            </div>
        </div>


        <pagination :pagination="pagination" @paginating="filter.page = $event"></pagination>

    </div>

</template>

<script type="text/javascript">
    import pagination from '../pagination/index.vue';

    import { baseMixin } from "../../mixins/deck/base.js";

    export default {

        components: {
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
                    follow_type: 'followings',
                },
            };
        },

        methods: {
            //
        },

    };
</script>