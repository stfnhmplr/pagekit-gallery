<template>

    <div class="uk-form-row">
        <label for="form-link-gallery" class="uk-form-label">{{ 'View' | trans }}</label>
        <div class="uk-form-controls">
            <select id="form-link-gallery" class="uk-width-1-1" v-model="link">
                <option value="@gallery">{{ 'Galleries View' | trans }}</option>
                <optgroup :label="'Galleries' | trans">
                    <option v-for="p in galleries" :value="p | link">{{ p.title }}</option>
                </optgroup>
            </select>
        </div>
    </div>

</template>

<script>

    module.exports = {

        link: {
            label: 'Gallery'
        },

        props: ['link'],

        data: function () {
            return {
                galleries: []
            }
        },

        created: function () {
            this.$http.get('api/gallery').then(function (res) {
                this.$set('galleries', res.data.galleries);
            });
        },

        ready: function() {
            this.link = '@gallery';
        },

        filters: {
            link: function (gallery) {
                return '@gallery/id?id='+gallery.id;
            }
        }

    };

    window.Links.components['link-gallery'] = module.exports;

</script>
