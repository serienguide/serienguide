export const ratingShowMixin = {

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

    methods: {
        rate(value) {
            var component = this;

            if (! this.$auth.check()) {
                Vue.error('Du musst angemeldet sein, um etwas zu bewerten.');
                return;
            }

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
                    if (response.data.rating == null) {
                        Vue.success('Bewertung von ' + component.model.name + ' gelöscht.');
                    }
                    else {
                        Vue.success(component.model.name + ' mit ' + response.data.rating.rating + ' Punkten bewertet.');
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
        rated(data) {
            Vue.set(this, 'rating', data.rating);
            this.hovered = this.rating_value;
        },
    },

};