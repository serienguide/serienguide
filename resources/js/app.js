require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

Vue.prototype.$auth = {
    user: null,
    lists: [],
    check: function () {
        return (this.user != null);
    },
    setUser: function (user) {
        this.user = user;
    },
};

/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param integer n: length of decimal
 * @param integer x: length of whole part
 * @param mixed   s: sections delimiter
 * @param mixed   c: decimal delimiter
 */
Number.prototype.format = function(decimals, dec_point, thousands_sep) {
    var number = (this + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + (Math.round(n * k) / k).toFixed(prec);
    };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};

import Flash from './plugins/flash.js';
Vue.use(Flash);

Vue.component('auth', require('./components/auth.vue').default);
Vue.component('buttons-lists', require('./components/card/list/index.vue').default);
Vue.component('buttons-watched-create', require('./components/card/watched/create.vue').default);
Vue.component('buttons-watched-index', require('./components/card/watched/index.vue').default);
Vue.component('card-show', require('./components/card/show.vue').default);
Vue.component('deck-base', require('./components/card/deck/base.vue').default);
Vue.component('deck-calendar-index', require('./components/card/deck/calendar/index.vue').default);
Vue.component('deck-episodes-next', require('./components/card/deck/episodes/next.vue').default);
Vue.component('deck-following-last-watched', require('./components/card/deck/following/last-watched.vue').default);
Vue.component('deck-lists-show', require('./components/card/deck/lists/show.vue').default);
Vue.component('deck-movies-index', require('./components/card/deck/movies/index.vue').default);
Vue.component('deck-shows-index', require('./components/card/deck/shows/index.vue').default);
Vue.component('deck-shows-last-aired-episodes', require('./components/card/deck/shows/last-aired-episodes.vue').default);
Vue.component('deck-media-related', require('./components/card/deck/media/related.vue').default);
Vue.component('deck-seasons-index', require('./components/card/deck/seasons/index.vue').default);
Vue.component('media-imports-tmdb-index', require('./components/media/imports/tmdb/index.vue').default);
Vue.component('media-rating-show', require('./components/media/rating/show.vue').default);
Vue.component('media-progress-show', require('./components/media/progress/show.vue').default);
Vue.component('profile-index', require('./components/profile/index.vue').default);
Vue.component('profile-followers', require('./components/profile/followers.vue').default);
Vue.component('profile-lists', require('./components/profile/lists.vue').default);
Vue.component('profile-progress', require('./components/profile/progress.vue').default);
Vue.component('profile-rated', require('./components/profile/rated.vue').default);
Vue.component('profile-watched', require('./components/profile/watched.vue').default);

const app = new Vue({
    el: '#app',
});