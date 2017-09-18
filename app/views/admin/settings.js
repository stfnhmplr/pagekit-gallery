module.exports = {

    el: '#settings',

    data: function () {
        return window.$data;
    },

    methods: {

        save: function () {
            this.$http.post('admin/system/settings/config', {name: 'gallery', config: this.config}).then(function () {
                        this.$notify('Settings saved.');
                    }, function (data) {
                        this.$notify(data, 'danger');
                    }
                );
        },
        clearCache: function () {
            this.$http.put('/api/gallery/clearcache').then(function () {
                    this.$notify('Cache cleared.');
                }, function (err) {
                    this.$notify(err, 'danger');
                }
            );
        }

    }

};

Vue.ready(module.exports);
