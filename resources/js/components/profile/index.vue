<template>

    <div>
        Profil Übersicht
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
            fetch() {
                return;
            }
        },

    };
</script>