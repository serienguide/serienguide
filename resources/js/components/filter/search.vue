<template>
    <div>
        <label for="search" class="block text-sm font-medium leading-5 text-gray-700">Suche</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input :value="value" type="search" class="form-input block w-full sm:text-sm sm:leading-5" ref="search" placeholder="Suche" autofocus="" @keyup="delay">
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            error: {
                type: String,
                required: false,
                default: '',
            },
            value: {
                required: true,
                type: String
            },
            shouldFocus: {
                required: false,
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                timeout: null,
            };
        },

        watch: {
            shouldFocus(newValue) {
                if (newValue) {
                    this.$refs['search'].focus();
                    this.$emit('focused');
                }
            },
        },

        methods: {
            delay () {
                var component = this;
                if (component.timeout) {
                    clearTimeout(component.timeout);
                    component.timeout = null;
                }
                component.timeout = setTimeout(function () {
                    component.$emit('input', component.$refs.search.value);
                }, 300);
            },
        },

    };
</script>