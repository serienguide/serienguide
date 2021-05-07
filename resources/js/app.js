require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

Vue.prototype.$auth = {
    user: null,
    check: function () {
        return (this.user != null);
    },
    setUser: function (user) {
        this.user = user;
    },
};

import Flash from './plugins/flash.js';
Vue.use(Flash);

Vue.component('auth', require('./components/auth.vue').default);
Vue.component('card-show', require('./components/card/show.vue').default);
Vue.component('media-imports-tmdb-index', require('./components/media/imports/tmdb/index.vue').default);

const app = new Vue({
    el: '#app',
});