<template>

    <div>
        <v-modal v-ref:modal :closed="close">
            <form class="uk-form uk-form stacked" @submit.prevent="update">

                <div class="uk-modal-header">
                    <h2>{{ 'Add Gallery' | trans }}</h2>
                </div>

                <div v-if="!galleries || !galleries.length" class="uk-form-row">
                    <p>{{ 'Please add and publish a gallery first!' | trans }}</p>
                </div>
                <div v-else>
                    <div class="uk-form-row">
                        <label for="form-gallery-id" class="uk-form-label">{{ 'Gallery' | trans }}</label>
                        <div class="uk-form-controls">
                            <select id="form-gallery-id" class="uk-width-1-1" v-model="gallery.id">
                                <option v-for="g in galleries" value="{{g.id}}">{{ g.title }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="uk-form-row uk-grid uk-form-stacked">
                        <div class="uk-width-1-2">
                            <label for="form-gallery-limit" class="uk-form-label">{{ 'Limit' | trans }}</label>
                            <input id="form-gallery-limit" type="number" min="1" v-model="gallery.limit">
                        </div>
                        <div class="uk-width-1-2">
                            <label for="form-gallery-link" class="uk-form-label">{{ 'Show Link?' | trans }}</label>
                            <input id="form-gallery-link" type="checkbox" v-model="gallery.showLink">
                        </div>
                    </div>
                    <p v-show="error" class="uk-alert uk-alert-danger">{{ error | trans }}</p>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-link uk-modal-close" type="button">{{ 'Cancel' | trans }}</button>
                    <button v-if="galleries && galleries.length" class="uk-button uk-button-link" type="submit">{{ 'Update' | trans }}</button>
                </div>

            </form>
        </v-modal>
    </div>

</template>

<script>
    module.exports = {

        data: function () {
            return {
                galleries: [],
                gallery: {
                    id: '',
                    showLink: true,
                    limit: ''
                },
                error: ''
            }
        },

        created: function () {
            this.$resource('api/gallery{/id}').query({filter: {minigallery: true}}).then(function (res) {
                this.galleries = res.data.galleries;
            });
        },

        ready: function () {
            this.$refs.modal.open();
            this.$set('gallery.showLink', true);
        },

        methods: {
            close: function() {
                this.$destroy(true);
            },
            update: function () {
                if (!this.gallery.id) {
                    this.$set('error', 'Please choose a gallery first');
                    return;
                }
                this.$refs.modal.close();
                this.$emit('select', this.gallery);
                this.$set('error', '');
            }
        }
    }
</script>