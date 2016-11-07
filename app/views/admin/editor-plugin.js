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
            title: 'Gallery',
            label: '<i class="uk-icon-clone"></i>'
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

            new this.$options.utils['editor-picker']({
                parent: this,
                data: {
                    gallery: '',
                    galleries: this.galleries
                }
            }).$mount()
                .$appendTo('body')
                .$on('select', function (gallery) {
                    var replacement = '[gallery id="' + gallery.id + '"';
                    replacement += ' limit="' + gallery.limit + '" showLink="' + gallery.showLink + '"/]';
                    editor.editor.replaceRange(replacement, cursor);
                });
        },

        replaceInPreview: function (data) {
            var options = {
                id: data.matches[1].match(/id="(.+?)"/)[1],
                limit: data.matches[1].match(/limit="(.+?)"/)[1],
                showLink: data.matches[1].match(/showLink="(.+?)"/)[1],
            };

            return '<gallery-preview id="' + options.id + '" ' +
                'limit="' + options.limit + '" ' +
                'showLink="' + options.showLink + '"' +
                '></gallery-preview>';
        }

    },

    components: {
        'gallery-preview': require('../../components/editor-preview.vue')
    },

    utils: {
        'editor-picker': Vue.extend(require('../../components/editor-picker.vue'))
    }

};

window.Editor.components['editor-plugin'] = module.exports;