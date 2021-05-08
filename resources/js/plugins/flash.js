const Flash = {
    install: function (Vue, options) {
        Vue.flash = function (text, type) {
            type = typeof type === 'undefined' ? 'success' : type;

            window.dispatchEvent(new CustomEvent('flash', {
                detail: {
                    text: text,
                    type: type,
                },
            }));
        };
        Vue.success = function (text) {
            Vue.flash(text, 'success');
        };
        Vue.error = function (text) {
            Vue.flash(text, 'error');
        };
    }
}

export default Flash;