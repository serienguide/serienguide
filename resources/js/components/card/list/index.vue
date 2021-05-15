<template>
    <div>
        <div class="inline-block text-left" v-show="is_fetched">
            <div class="px-1">
                <button @click="toggleWatchlist" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" :class="watchlist_class">
                    <i class="fas fa-bookmark" v-show="! is_toggling"></i>
                    <i class="text-gray-400 fas fa-spinner fa-spin" v-show="is_toggling"></i>
                </button>
            </div>
        </div>
        <div class="inline-block text-left">
            <div class="px-1">
                <button @click="isOpen = ! isOpen" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-list"></i>
                </button>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <div v-show="isOpen"
                    class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10"
                    style="width: 90%; right: 5%;">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="flex items-center mb-3">
                                <div class="flex-grow font-bold">Listen verwalten</div>
                                <div class="hidden">
                                    <i class="fas fa-plus text-sm leading-5 text-gray-900 cursor-pointer"></i>
                                </div>
                            </div>
                            <ul>
                                <show :model="list" :key="list.id" v-for="(list, index) in lists" @toggled="toggled('lists', index, $event)"></show>
                                <li class="py-3"><hr class="text-gray-400" v-show="custom_lists.length > 0"></li>
                                <show :model="list" :key="list.id" v-for="(list, index) in custom_lists" @toggled="toggled('custom_lists', index, $event)"></show>
                            </ul>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

    </div>

</template>

<script type="text/javascript">
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
        },

        computed: {
            watchlist_class() {
                if (this.watchlist.length == 0) {
                    return '';
                }

                if (this.watchlist[0].items.length > 0) {
                    return 'text-blue-600 hover:text-blue-700 focus:outline-none focus:text-blue-700'
                }

                return 'text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600';
            }
        },

        data () {
            return {
                isOpen: false,
                custom_lists: [],
                lists: [],
                watchlist: [],
                is_fetched: false,
                is_toggling: false,
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                axios.get(component.model.lists_path, {
                    params: {

                    }
                })
                    .then( function (response) {
                        for(var list of response.data) {
                            if (list.is_custom) {
                                component.custom_lists.push(list);
                            }
                            else {
                                component.lists.push(list);
                            }

                            if (list.is_watchlist) {
                                component.watchlist.push(list);
                            }
                        }
                        component.is_fetched = true;
                    })
                    .catch(function (error) {
                        Vue.error('Daten konnten nicht geladen werden.');
                    });
            },
            toggled(list_type, index, item) {
                if (item == '') {
                    var items = [];
                    Vue.success(this.model.name + ' von Liste ' + this[list_type][index].name + ' entfernt.');
                }
                else {
                    var items = [item];
                    Vue.success(this.model.name + ' zu Liste ' + this[list_type][index].name + ' hinzugefügt.');
                }
                Vue.set(this[list_type][index], 'items', items);
            },
            toggleWatchlist() {
                var component = this;
                component.is_toggling = true;
                axios.put(component.watchlist[0].toggle_path)
                    .then( function (response) {
                        component.toggled('watchlist', 0, response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Listeneintrag konnte nicht aktualisiert werden.');
                    })
                    .then(function () {
                        component.is_toggling = false;
                    });
            },
        },

    };
</script>