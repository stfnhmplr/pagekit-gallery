<template>

    <form class="pk-panel-teaser uk-form uk-form-stacked" v-if="editing">

        <div class="uk-form-row">
            <label for="form-gallery-title" class="uk-form-label">{{ 'Title' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-gallery-title" class="uk-width-1-1" type="text" name="widget[title]" v-model="widget.title">
            </div>
        </div>

        <div class="uk-form-row">
            <span class="uk-form-label">{{ 'Display' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <p class="uk-form-controls-condensed">
                    <label><input type="radio" value="all" v-model="widget.show"> {{ 'show all' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input type="radio" value="status" v-model="widget.show"> {{ 'only with status' | trans }}</label>
                </p>
                <p class="uk-form-controls-condensed">
                    <label><input type="checkbox" v-model="widget.showTeaser"> {{ 'show random image' | trans }}</label>
                </p>
            </div>
        </div>

        <div class="uk-form-row" v-if="widget.show == 'status'">
            <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-status" class="uk-width-1-1" v-model="widget.status">
                    <option v-for="(id, status) in widget.statuses" :value="id">{{status}}</option>
                </select>
            </div>
        </div>

    </form>

    <div class="uk-panel" v-else>
        <h3 v-if="widget.title">{{ widget.title }}</h3>
        <div v-if="widget.showTeaser" class="uk-panel-teaser">
            <img v-if="widget.teaser" :src="$url('storage/shw-gallery/' + widget.teaser.filename)" :alt="widget.teaser.title"/>
        </div>
        <ul class="uk-subnav">
            <li class="subtitle">
                <div class="uk-text-large uk-text-center">{{ widget.galleries }}</div>
                <div>{{ '{0} Galleries|{1} Gallery|]1,Inf[ Galleries' | transChoice widget.galleries }}</div>
            </li>
            <li class="subtitle">
                <div class="uk-text-large uk-text-center">{{ widget.images }}</div>
                <div>{{ '{0} Images|{1} Image |]1,Inf[ Images' | transChoice widget.images }}</div>
            </li>
        </ul>
    </div>

</template>

<style>
    .subtitle {
        padding-right: 1em;
        border-right: 1px solid #666666;
    }

    .subtitle:last-child {
        border-right: none;
    }

    .subtitle div {
        display: block;
    }
</style>

<script>

    module.exports = {

        type: {

            id: 'gallery',
            label: 'Gallery',
            defaults: {
                title: '',
                show: '',
                showTeaser: true,
                status: null,
            }

        },

        data: function () {
            return {
                galleries: '',
                images: '',
                statuses: [],
                teaser: '',
            }
        },

        watch: {

            'widget.show': {
                handler: 'load',
                immediate: true
            },

            'widget.status': 'load'

        },

        replace: false,

        props: ['widget', 'editing'],

        methods: {
            load: function() {
                var query = {filter: {}};

                (this.$get('widget.show') != 'all') ? query.filter.status = this.$get('widget.status') : '';

                this.$http.get('api/gallery/dashboard', query).then(function (res) {
                    this.$set('widget.galleries', res.data.galleries);
                    this.$set('widget.images', res.data.images);
                    this.$set('widget.statuses', res.data.statuses);
                    this.$set('widget.teaser', res.data.teaser);
                });
            }
        }
    };

    window.Dashboard.components['gallery'] = module.exports;

</script>
