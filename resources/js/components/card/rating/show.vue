<template>
    <div>

        <div class="inline-block text-left">
            <div class="px-1">
                <button @click="is_open = ! is_open" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa- fa-star" :class="rating_class"></i>
                </button>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <div v-show="is_open" class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="flex justify-between p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" @mouseleave="hovered = rating_value">
                            <i @click="rate(n)" class="fas fa-star px-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= n}" :title="'Mit ' + n + ' Punkten bewerten'" @mouseenter="hovered = n" v-for="n in 10"></i>
                            <i @click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" title="Bewertung löschen" @mouseenter="hovered = 0" v-if="! has_rating"></i>
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
            has_rating() {
                return (this.rating === null || ! this.rating);
            },
            rating_class() {
                if (! this.rating) {
                    return '';
                }

                return 'text-yellow-400 hover:text-yellow-600 focus:outline-none focus:text-yellow-600';
            },
            rating_value() {
                return (this.rating == null ? 0 : this.rating.rating);
            },
        },

        data () {
            return {
                is_open: false,
                is_rating: false,
                rating: this.model.rating_by_user,
                hovered: (this.model.rating_by_user == null ? 0 : this.model.rating_by_user.rating),
            };
        },

        mounted () {
            //
        },

        methods: {
            rate(value) {
                var component = this;
                if (component.is_rating) {
                    return;
                }
                component.is_rating = true;
                axios.post(component.model.rate_path, {
                    rating: value
                })
                    .then( function (response) {
                        component.rated(response.data);
                        Bus.$emit(component.model.rated_event_name, response.data);
                        if (response.data == '') {
                            Vue.success('Bewertung von ' + component.model.name + ' gelöscht.');
                        }
                        else {
                            Vue.success(component.model.name + ' mit ' + response.data.rating + ' Punkten bewertet.');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Bewertung konnten nicht gespeichert werden.');
                    })
                    .then(function () {
                        component.is_rating = false;
                    });
            },
            rated(rating) {
                Vue.set(this, 'rating', rating);
                this.hovered = this.rating_value;
            },
        },

    };
</script>