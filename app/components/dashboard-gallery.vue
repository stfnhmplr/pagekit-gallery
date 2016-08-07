<template>

    <form class="pk-panel-teaser uk-form uk-form-stacked" v-if="editing">

        <div class="uk-form-row">
            <label for="form-gallery-title" class="uk-form-label">{{ 'Title' | trans }}</label>

            <div class="uk-form-controls">
                <input id="form-gallery-title" class="uk-width-1-1" type="text" name="widget[title]" v-model="widget.title">
            </div>
        </div>

    </form>

    <div v-else>

        <h3 v-if="widget.title">{{ widget.title }}</h3>

    </div>

</template>

<script>

    module.exports = {

        type: {

            id: 'gallery',
            label: 'Gallery',
            defaults: {
                title: ''
            }

        },

        replace: false,

        props: ['widget', 'editing']

    };

    window.Dashboard.components.gallery = module.exports;

</script>
