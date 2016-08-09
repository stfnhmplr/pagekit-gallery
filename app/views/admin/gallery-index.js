module.exports = {

    name: 'gallery',

    el: '#galleries',

    data: function() {
        return _.merge({
            galleries: false,
            config: {
                filter: this.$session.get('galleries.filter', {order: 'date desc', limit:25})
            },
            pages: 0,
            count: '',
            selected: [],
            canEditAll: false
        }, window.$data);
    },

    ready: function () {
        this.resource = this.$resource('api/gallery{/id}');
        this.$watch('config.page', this.load, {immediate: true});
    },

    watch: {

        'config.filter': {
            handler: function (filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }

                this.$session.set('galleries.filter', filter);
            },
            deep: true
        }

    },

    computed: {

        statusOptions: function () {

            var options = _.map(this.$data.statuses, function (status, id) {
                return { text: status, value: id };
            });

            return [{ label: this.$trans('Filter by'), options: options }];
        },

        authors: function() {

            var options = _.map(this.$data.authors, function (author) {
                return { text: author.username, value: author.user_id };
            });

            return [{ label: this.$trans('Filter by'), options: options }];
        }
    },

    methods: {

        active: function (gallery) {
            return this.selected.indexOf(gallery.id) != -1;
        },

        save: function (gallery) {
            this.resource.save({ id: gallery.id }, { gallery: gallery }).then(function () {
                this.load();
                this.$notify(this.$trans('Gallery saved'));
            });
        },

        status: function(status) {

            var galleries = this.getSelected();

            galleries.forEach(function(gallery) {
                gallery.status = status;
            });

            this.resource.save({ id: 'bulk' }, { galleries: galleries }).then(function () {
                this.load();
                this.$notify(this.$trans('Galleries saved'));
            });
        },

        remove: function() {

            this.resource.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify(this.$trans('Galleries deleted'));
            });
        },

        toggleStatus: function (gallery) {
            gallery.status = gallery.status === 2 ? 3 : 2;
            this.save(gallery);
        },

        copy: function() {

            if (!this.selected.length) {
                return;
            }

            this.resource.save({ id: 'copy' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify(this.$trans('Galleries copied'));
            });
        },

        load: function () {
            this.resource.query({ filter: this.config.filter, page: this.config.page }).then(function (res) {

                var data = res.data;

                this.$set('galleries', data.galleries);
                this.$set('pages', data.pages);
                this.$set('count', data.count);
                this.$set('selected', []);
            });
        },

        getSelected: function() {
            return this.galleries.filter(function(gallery) { return this.selected.indexOf(gallery.id) !== -1; }, this);
        },

        getStatusText: function(gallery) {
            return this.statuses[gallery.status];
        }

    }

};

Vue.ready(module.exports);
