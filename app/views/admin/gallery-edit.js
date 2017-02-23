window.Gallery = {

    el: '#gallery',

    data: function () {
        return {
            data: window.$data,
            gallery: window.$data.gallery,
            sections: []
        }
    },

    created: function () {

        var sections = [];

        _.forIn(this.$options.components, function (component, name) {

            var options = component.options || {};

            if (options.section) {
                sections.push(_.extend({name: name, priority: 0}, options.section));
            }

        });

        this.$set('sections', _.sortBy(sections, 'priority'));

        this.resource = this.$resource('api/gallery{/id}');
    },

    ready: function () {
        this.tab = UIkit.tab(this.$els.tab, {connect: this.$els.content});
    },

    methods: {

        save: function () {

            var data = {gallery: this.gallery, id: this.gallery.id};
            this.$broadcast('save', data);

            if(this.gallery.images.length < 1 && (this.gallery.status == 2 || this.gallery.status == 4)) {
                this.$notify(this.$trans('Please add some images before you publish', 'danger'));
                return false;
            }

            this.resource.save({id: this.gallery.id}, data).then(function (res) {
                var data = res.data;
                if (!this.gallery.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/gallery/gallery/edit', {id: data.gallery.id}))
                }
                this.$set('gallery', data.gallery);
                this.$notify(this.$trans('Gallery saved'));

            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        }

    },

    components: {
        settings: require('../../components/gallery-settings.vue')
    }

};

Vue.ready(window.Gallery);
