<template>
    <div>
        <h2>{{ 'Image Upload' | trans }}</h2>
        <h3 class="uk-h1 uk-text-muted uk-text-center" v-if="!gallery.id">{{ 'Please save gallery first' | trans }}</h3>

        <div v-else>
            <!-- upload-field -->
            <div class="file-upload uk-container-center uk-width-large-3-4 uk-margin">
                <input id="file-input" type="file" name="files" multiple="multiple" accept="image/jpeg,image/png" @change="onFileChange">
                <img class="uk-align-center uk-margin-top" width="60" height="60" alt="Placeholder Image" src="/app/system/assets/images/placeholder-image.svg">
                <p v-if="!files.length" class="uk-text-center">
                    <a @click.prevent="triggerFileInput">{{ 'Drag images here or select some' | trans }}</a>
                </p>
                <div v-else>
                    <p class="uk-text-center">{{ '{1} %count% File selected|]1,Inf[ %count% Files selected' | transChoice files.length {count:files.length} }}</p>
                </div>
            </div>

            <!-- upload-buttons -->
            <div v-if="files.length" class="upload-buttons uk-text-center uk-margin-top">
                <button class="uk-button uk-button-primary" @click.prevent="upload">{{ 'Upload' | trans }}</button>
                <button class="uk-button" @click.prevent="reset">{{ 'Cancel' | trans }}</button>
            </div>

            <!-- images -->
            <h3 class="uk-h1 uk-text-muted uk-text-center" v-if="!gallery.images">{{ 'No images found' | trans }}</h3>
            <div class="uk-grid uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-5" v-else>
                <div class="uk-text-center" v-for="image in gallery.images">
                    <img class="uk-thumbnail pointer" :src="'/storage/shw-gallery/thumbnails/tn_' + image.filename" @click="editImage(image)"/>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-modal" v-el:modal>
        <div class="uk-modal-dialog" v-if="img">

            <div class="uk-modal-header">
                <h2 class="uk-margin-small-bottom">{{ 'Edit image' | trans }}</h2>
            </div>

            <div class="uk-form-row">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-title" class="uk-width-1-1" type="text" v-model="img.title">
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-link uk-modal-close" type="button">{{ 'Cancel' | trans }}</button>
                <button class="uk-button uk-button-link" @click.prevent="saveImage(img)">{{ 'Update' | trans }}</button>
            </div>

        </div>
    </div>
</template>

<style>
    .file-upload {
        border: 2px dashed #E5E5E5;
        position: relative;
    }

    .file-upload input {
        position: absolute;
        cursor: pointer;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    .pointer {
        cursor: pointer;
    }
</style>

<script>

    module.exports = {

        section: {
            label: 'Images',
            priority: 100
        },

        data: function () {
            return {
                files: [],
                form: {},
                images: [],
            }
        },

        props: ['gallery'],

        methods: {
            onFileChange(e) {
                this.files = e.target.files || e.dataTransfer.files;
                for(var i=0; i<this.files.length; i++) {
                    if(!_.isUndefined(this.files[i].name) && !(/\.(jpe?g|png)$/i).test(this.files[i].name)) {
                        this.$notify(this.$trans('Invalid file type. Only *.jpg, *.jpeg and *.png are supported'), 'danger');
                        this.reset();
                    }
                }
            },

            editImage: function (img) {

                if (!this.modal) {
                    this.modal = UIkit.modal(this.$els.modal);
                }

                this.$set('img', img);
                this.modal.show();
            },

            saveImage: function(img) {
                this.$resource('api/gallery/image{/id}').save({ id: img.id }, { image: img }).then(function () {
                    this.modal.hide();
                });
            },

            cancelEdit: function () {
                this.modal.hide();
            },

            upload() {

                this.form = new FormData();

                for(var key in this.files) {
                    this.form.append('images[' + key + ']', this.files[key]);
                }

                this.form.delete('images[item]');
                this.form.delete('images[length]');

                this.form.append('id', this.gallery.id);

                this.$http.post('api/gallery/upload', this.form)
                        .then(function (res) {
                    this.$notify(this.$trans((this.files.length > 1) ? 'Images uploaded' : 'Image uploaded'));
                    this.reset();
                    this.$set('gallery.images', res.data.images)

                });
            },

            reset() {
                document.getElementById("file-input").value = "";
                this.$set('files', []);
            },

            triggerFileInput() {
                document.getElementById("file-input").click();
            }
        }
    };

    window.Gallery.components['gallery-images'] = module.exports;

</script>
