module.exports = {

    plugin: true,

    created: function () {

        var vm = this, editor = this.$parent.editor;

        if (!editor || !editor.htmleditor) {
            return;
        }

        this.galleries = [];
        this.resource = this.$resource('api/gallery{/id}');

        editor.addButton('gallery', {
            title: this.$trans('Gallery'),
            label: '<img src="' + this.$url('packages/shw/gallery/assets/img/editor-icon.svg') + '" width="12px" height="12px" />'
        });

        editor.options.toolbar.push('gallery');

        editor
            .on('action.gallery', function (e, editor) {
                vm.openModal();
            })
            .on('render', function () {
                vm.galleries = editor.replaceInPreview(/\[gallery(.*?)\/]/gi, vm.replaceInPreview);
            })
            .on('renderLate', function () {
                while (vm.$children.length) {
                    vm.$children[0].$destroy();
                }

                Vue.nextTick(function () {
                    editor.preview.find('gallery-preview').each(function () {
                        vm.$compile(this);
                    });
                });
            });

        editor.debouncedRedraw();
    },

    methods: {

        openModal: function () {
            var editor = this.$parent.editor, cursor = editor.editor.getCursor();

            new this.$options.utils['gallery-picker']({
                parent: this,
                data: {
                    gallery: '',
                    galleries: this.galleries
                }
            }).$mount()
                .$appendTo('body')
                .$on('select', function (gallery) {
                    var replacement = '[gallery id="' + gallery.id + '"';
                    (gallery.limit > 1) ? replacement += ' limit="' + gallery.limit +'"' : '';
                    replacement += ' showLink="' + gallery.showLink + '"/]';
                    editor.editor.replaceRange(replacement, cursor);
                });
        },

        replaceInPreview: function (data) {
            var options = {
                id: data.matches[1].match(/id="(.+?)"/)[1],
                limit: (data.matches[1].match(/limit="(.+?)"/)) ? data.matches[1].match(/limit="(.+?)"/)[1] : false,
                showLink: data.matches[1].match(/showLink="(.+?)"/)[1],
            };

            var preview = '<gallery-preview :id="' + options.id + '" ';
            preview += (options.limit) ? ':limit="' + options.limit + '" ' : '';
            preview += ':show-link="' + options.showLink + '"></gallery-preview>';

            return preview;
        }

    },

    components: {
        'gallery-preview': require('../../components/gallery-preview.vue')
    },

    utils: {
        'gallery-picker': Vue.extend(require('../../components/gallery-picker.vue'))
    }
};

window.Editor.components['plugin-gallery'] = module.exports;