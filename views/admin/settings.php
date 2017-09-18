<?php $view->script('settings', 'gallery:app/bundle/settings.js', 'vue') ?>

<div id="settings" class="uk-form uk-form-horizontal" v-cloak>

    <div class="uk-grid pk-grid-large" data-uk-grid-margin>
        <div class="pk-width-sidebar">

            <div class="uk-panel">

                <ul class="uk-nav uk-nav-side pk-nav-large" data-uk-tab="{ connect: '#tab-content' }">
                    <li><a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'General' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-comment uk-icon-small uk-margin-right"></i> {{ 'Images' | trans }}</a></li>
                </ul>

            </div>

        </div>
        <div class="pk-width-content">

            <ul id="tab-content" class="uk-switcher uk-margin">
                <li>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <div data-uk-margin>

                            <h2 class="uk-margin-remove">{{ 'General' | trans }}</h2>

                        </div>
                        <div data-uk-margin>

                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>

                        </div>
                    </div>

                    <div class="uk-form-row">
                        <div class="uk-form-row">
                            <label class="uk-form-label">{{ 'Site Title' | trans }}</label>
                            <div class="uk-form-controls uk-form-controls-text">
                                <p class="uk-form-controls-condensed">
                                    <input type="text" v-model="config.gallery.title" class="uk-form-width-small">
                                </p>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <span class="uk-form-label">{{ 'Permalink' | trans }}</span>
                            <div class="uk-form-controls uk-form-controls-text">
                                <p class="uk-form-controls-condensed">
                                    <label>
                                        <input type="radio" v-model="config.permalink.type" value="">
                                        {{ 'Numeric' | trans }} <code>{{ '/123' | trans }}</code>
                                    </label>
                                </p>
                                <p class="uk-form-controls-condensed">
                                    <label>
                                        <input type="radio" v-model="config.permalink.type" value="{slug}">
                                        {{ 'Name' | trans }} <code>{{ '/sample-gallery' | trans }}</code>
                                    </label>
                                </p>
                                <p class="uk-form-controls-condensed">
                                    <label>
                                        <input type="radio" v-model="config.permalink.type" value="{year}/{month}/{day}/{slug}">
                                        {{ 'Day and name' | trans }} <code>{{ '/2014/06/12/sample-gallery' | trans }}</code>
                                    </label>
                                </p>
                                <p class="uk-form-controls-condensed">
                                    <label>
                                        <input type="radio" v-model="config.permalink.type" value="{year}/{month}/{slug}">
                                        {{ 'Month and name' | trans }} <code>{{ '/2014/06/sample-gallery' | trans }}</code>
                                    </label>
                                </p>
                                <p class="uk-form-controls-condensed">
                                    <label>
                                        <input type="radio" v-model="config.permalink.type" value="custom">
                                        {{ 'Custom' | trans }}
                                    </label>
                                    <input class="uk-form-small" type="text" v-model="config.permalink.custom">
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Galleries per page' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <input type="number" v-model="config.gallery.galleries_per_page" class="uk-form-width-small">
                            </p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Show back button' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <input type="checkbox" v-model="config.gallery.back_button" value="1">
                            </p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <span class="uk-form-label">{{ 'Default gallery settings' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <label><input type="checkbox" v-model="config.gallery.markdown_enabled"> {{ 'Enable Markdown' | trans }}</label>
                            </p>
                        </div>
                    </div>

                </li>
                <li>
                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <div data-uk-margin>
                            <h2 class="uk-margin-remove">{{ 'Images' | trans }}</h2>
                        </div>
                        <div data-uk-margin>
                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <span class="uk-form-label">{{ 'Thumbnail size' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-condensed">
                            <p>
                                <input class="uk-form-width-small" v-model="config.images.thumbnail_width" type="number" >
                                {{ 'Width' | trans }} (px)
                            </p>
                            <p>
                                <input class="uk-form-width-small" v-model="config.images.thumbnail_height" type="number">
                                {{ 'Height' | trans }} (px)
                            </p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <span class="uk-form-label">{{ 'Image size' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-condensed">
                            <p>
                                <input class="uk-form-width-small" v-model="config.images.image_width" type="number">
                                {{ 'Width' | trans }} (px)
                            </p>
                            <p>
                                <input class="uk-form-width-small" v-model="config.images.image_height" type="number">
                                {{ 'Height' | trans }} (px)
                            </p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Image quality' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <input type="number" v-model="config.images.image_quality" class="uk-form-width-small">
                            </p>
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <button class="uk-button" @click.prevent="clearCache">{{ 'clear cache' | trans }}</button>
                    </div>
                </li>
            </ul>

        </div>
    </div>

</div>
