<?php $view->script('galleries-index', 'gallery:app/bundle/gallery-index.js', 'vue') ?>

<div id="galleries" class="uk-form" v-cloak>

    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
        <div class="uk-flex uk-flex-middle uk-flex-wrap" data-uk-margin>

            <h2 class="uk-margin-remove" v-if="!selected.length">{{ '{0} %count% Galleries|{1} %count% Gallery|]1,Inf[ %count% Galleries' | transChoice count {count:count} }}</h2>

            <template v-else>
                <h2 class="uk-margin-remove">{{ '{1} %count% Gallery selected|]1,Inf[ %count% Galleries selected' | transChoice selected.length {count:selected.length} }}</h2>

                <div class="uk-margin-left">
                    <ul class="uk-subnav pk-subnav-icon">
                        <li><a class="pk-icon-check pk-icon-hover" title="Publish" data-uk-tooltip="{delay: 500}" @click="status(2)"></a></li>
                        <li><a class="pk-icon-block pk-icon-hover" title="Unpublish" data-uk-tooltip="{delay: 500}" @click="status(3)"></a></li>
                        <li><a class="pk-icon-copy pk-icon-hover" title="Copy" data-uk-tooltip="{delay: 500}" @click="copy"></a></li>
                        <li><a class="pk-icon-delete pk-icon-hover" title="Delete" data-uk-tooltip="{delay: 500}" @click="remove" v-confirm="'Delete Galleries?'"></a></li>
                    </ul>
                </div>
            </template>

            <div class="pk-search">
                <div class="uk-search">
                    <input class="uk-search-field" type="text" v-model="config.filter.search" debounce="300">
                </div>
            </div>

        </div>
        <div data-uk-margin>

            <a class="uk-button uk-button-primary" :href="$url.route('admin/gallery/gallery/edit')">{{ 'Add Gallery' | trans }}</a>

        </div>
    </div>

    <div class="uk-overflow-container">
        <table class="uk-table uk-table-hover uk-table-middle">
            <thead>
                <tr>
                    <th class="pk-table-width-minimum"><input type="checkbox" v-check-all:selected.literal="input[name=id]" number></th>
                    <th class="pk-table-min-width-200" v-order:title="config.filter.order">{{ 'Title' | trans }}</th>
                    <th class="pk-table-width-100">{{ 'Photograph' | trans }}</th>
                    <th class="pk-table-width-100 uk-text-center">
                        <input-filter :title="$trans('Status')" :value.sync="config.filter.status" :options="statusOptions"></input-filter>
                    </th>
                    <th class="pk-table-width-100">
                        <span v-if="!canEditAll">{{ 'Author' | trans }}</span>
                        <input-filter :title="$trans('Author')" :value.sync="config.filter.author" :options="authors" v-else></input-filter>
                    </th>
                    <th class="pk-table-width-100" v-order:date="config.filter.order">{{ 'Date' | trans }}</th>
                    <th class="pk-table-width-200 pk-table-min-width-200">{{ 'URL' | trans }}</th>
                </tr>
            </thead>
            <tbody>
                <tr class="check-item" v-for="gallery in galleries" :class="{'uk-active': active(gallery)}">
                    <td><input type="checkbox" name="id" :value="gallery.id"></td>
                    <td>
                        <a :href="$url.route('admin/gallery/gallery/edit', { id: gallery.id })">{{ gallery.title }}</a>
                    </td>
                    <td>
                        {{ gallery.photograph }}
                    </td>
                    <td class="uk-text-center">
                        <a :title="getStatusText(gallery)" :class="{
                                'pk-icon-circle': gallery.status == 0,
                                'pk-icon-circle-warning': gallery.status == 1,
                                'pk-icon-circle-success': gallery.status == 2 && gallery.published,
                                'pk-icon-circle-danger': gallery.status == 3,
                                'pk-icon-schedule': gallery.status == 2 && !gallery.published
                            }" @click="toggleStatus(gallery)"></a>
                    </td>
                    <td>
                        <a :href="$url.route('admin/user/edit', { id: gallery.user_id })">{{ gallery.author }}</a>
                    </td>
                    <td>
                        {{ gallery.date | date }}
                    </td>
                    <td class="pk-table-text-break">
                        <a target="_blank" v-if="gallery.accessible && gallery.url" :href="this.$url.route(gallery.url.substr(1))">{{ decodeURI(gallery.url) }}</a>
                        <span v-if="!gallery.accessible && gallery.url">{{ decodeURI(gallery.url) }}</span>
                        <span v-if="!gallery.url">{{ 'Disabled' | trans }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="uk-h1 uk-text-muted uk-text-center" v-show="galleries && !galleries.length">{{ 'No Galleries found' | trans }}</h3>

    <v-pagination :page.sync="config.page" :pages="pages" v-show="pages > 1 || page > 0"></v-pagination>

</div>
