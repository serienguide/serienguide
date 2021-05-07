<template></template>

<script type="text/javascript">
    export default {

        components: {
            //
        },

        mixins: [
            //
        ],

        props: {
            user: {
                required: false,
                type: Object,
                default() {
                    return null;
                },
            },
        },

        computed: {
            //
        },

        data () {
            return {
                //
            };
        },

        created () {
            this.$auth.setUser(this.user);
        }

    };
</script>