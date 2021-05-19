<template>

    <ul class="grid grid-rows-1 grid-cols-4-1/1 gap-6 sm:grid-cols-4-1/2 md:grid-cols-4-1/2 lg:grid-cols-4 overflow-auto">

        <card-show :model="next_episode" :key="'next_' + next_episode.id" img-type="backdrop" :load-next="true" @nexted="nexted($event)" v-if="next_episode"></card-show>

        <card-show :model="episode" :key="episode.id" img-type="backdrop" v-for="(episode, index) in episodes"></card-show>

    </ul>

</template>

<script type="text/javascript">
    export default {

        props: {
            nextEpisode: {
                required: false,
                default() {
                    return null;
                },
            },
            episodes: {
                required: false,
                default() {
                    return [];
                },
            }
        },

        computed: {
            //
        },

        data() {
            return {
                next_episode: this.nextEpisode,
            };
        },

        methods: {
            nexted(model) {
                this.next_episode = model;
            },
        },

        mounted() {
            //
        },

    };
</script>