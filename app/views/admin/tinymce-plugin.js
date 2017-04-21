/**
 * TinyMCE Plugin for the Gallery extension
 */

module.exports = {
    plugin: true,

    created: function () {
        var vm = this;

        if (typeof tinyMCE === 'undefined') {
            return;
        }

        this.$parent.editor.plugins.push('-gallery');

        tinyMCE.PluginManager.add('gallery', function (editor) {

            var showDialog = function () {
                new vm.$parent.$options.utils['gallery-picker']({
                    parent: vm,
                    data: {
                        gallery: '',
                        galleries: vm.galleries
                    }
                }).$mount()
                    .$appendTo('body')
                    .$on('select', function (gallery) {
                        var replacement = '[gallery id="' + gallery.id + '"';
                        (gallery.limit > 1) ? replacement += ' limit="' + gallery.limit + '"' : '';
                        replacement += ' showLink="' + gallery.showLink + '"/]';
                        editor.selection.setContent(replacement);
                        editor.fire('change');
                    });
            };

            editor.addButton('gallery', {
                tooltip: vm.$trans('Insert gallery'),
                icon: 'image',
                image: vm.$url('packages/shw/gallery/assets/img/editor-icon.svg'),
                onclick: showDialog
            });

            editor.addMenuItem('gallery', {
                text: 'Insert gallery',
                image: vm.$url('packages/shw/gallery/assets/img/editor-icon.svg'),
                context: 'insert',
                onclick: showDialog
            });
        });
    }
};

window.Editor.components['plugin-gallery'] = module.exports;
window.Editor.utils['gallery-picker'] = Vue.extend(require('../../components/gallery-picker.vue'));
