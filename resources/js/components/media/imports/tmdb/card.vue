<template>

    <li class="col-span-1 flex flex-col justify-between bg-white rounded-lg shadow">

        <main @click="store()" class="flex-grow relative cursor-pointer rounded-t-lg">
            <img class="rounded-t-lg w-full" :src="item.poster_path_formatted">

            <div class="absolute inset-0 flex items-center justify-center">
                <button type="button" class="text-xl bg-white text-gray-700 border-gray-300 hover:text-gray-500 inline-flex items-center px-3 py-3 border border-gray-700 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" title="Importieren">
                    <i :class="(isStoring ? 'fa-spinner fa-spin' : 'fa-plus')" class="fas"></i>
                </button>
            </div>
            <div class="absolute bottom-3 left-3">
                <div class="flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-yellow-300 text-yellow-800" v-show="item.first_air_date_formatted">
                    {{ item.first_air_date_formatted }}
                </div>
            </div>
        </main>
        <footer :title="item.name" class="flex items-center px-3 py-1">
            <h3 class="flex-grow text-gray-900 leading-5 font-medium overflow-hidden whitespace-nowrap">
                {{ item.name }}
            </h3>
            <div class="ml-1">

            </div>
        </footer>

    </li>

</template>

<script type="text/javascript">
    export default {

        components: {
            //
        },

        mixins: [
            //
        ],

        props: {
            item: {
                required: false,
                type: Object,
            },
            uri: {
                required: true,
                type: String,
            },
        },

        computed: {
            //
        },

        data () {
            return {
                isStoring: false,
            };
        },

        methods: {
            store() {
                var component = this;

                if (component.isStoring) {
                    return;
                }

                component.isStoring = true;
                axios.post(component.uri, component.item)
                    .then(function (response) {
                        component.isStoring = false;
                        Vue.success(response.data.flash.text);
                        if (!response.data.is_created) {
                            location.href = response.data.path;
                        }
                    })
                    .catch(function (error) {
                        Vue.error('Datensätze konnten nicht geladen werden!');
                        console.log(error);
                        component.isStoring = false;
                    });
            },
        },

    };
</script>