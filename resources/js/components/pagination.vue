<template>

    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6" v-show="pagination.lastPage > 1">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="#" @click.prevent="$emit('paginating', pagination.currentPage - 1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" v-show="pagination.prevPageUrl">
                Previous
            </a>
            <a href="#" @click.prevent="$emit('paginating', pagination.currentPage + 1)" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" v-show="pagination.nextPageUrl">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ pagination.from }}</span>
                    to
                    <span class="font-medium">{{ pagination.to }}</span>
                    of
                    <span class="font-medium">{{ pagination.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" @click.prevent="$emit('paginating', pagination.currentPage - 1)" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" v-show="pagination.prevPageUrl">
                        <span class="sr-only">Previous</span>
                        <!-- Heroicon name: solid/chevron-left -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" @click.prevent="$emit('paginating', n)" aria-current="page" class="z-10 relative inline-flex items-center px-4 py-2 border text-sm font-medium" v-for="n in pages" :class="(n == pagination.currentPage) ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'">
                        {{ n }}
                    </a>
                    <a href="#" @click.prevent="$emit('paginating', pagination.currentPage + 1)" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" v-show="pagination.nextPageUrl">
                        <span class="sr-only">Next</span>
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>

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
            pagination: {
                required: true,
                type: Object,
            }
        },

        computed: {
            pages() {
                var pages = [];
                for (var i = 1; i <= this.pagination.lastPage; i++) {
                    if (this.showPageButton(i)) {
                        const lastItem = pages[pages.length - 1];
                        if (lastItem < (i - 1) && lastItem != '...') {
                            pages.push('...');
                        }
                        pages.push(i);
                    }
                }

                return pages;
            },
        },

        data () {
            return {
                //
            };
        },

        mounted() {
            //
        },

        methods: {
            showPageButton(page) {
                if (page == 1 || page == this.pagination.lastPage) {
                    return true;
                }

                if (page <= this.filter.page + 2 && page >= this.filter.page - 2) {
                    return true;
                }

                return false;
            },
        },

    };
</script>