<template>

    <div class="uk-grid pk-grid-large pk-width-sidebar-large uk-form-stacked" data-uk-grid-margin>
        <div class="pk-width-content">

            <div class="uk-form-row">
                <input class="uk-width-1-1 uk-form-large" type="text" name="title" :placeholder="'Enter Title' | trans" v-model="gallery.title" v-validate:required>
            </div>
            <div class="uk-form-row">
                <label for="form-photograph" class="uk-form-label">{{ 'Photograph' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-photograph" class="uk-width-1-1" type="text" v-model="gallery.photograph">
                </div>
            </div>
            <div class="uk-form-row">
                <label for="gallery-description" class="uk-form-label">{{ 'Description' | trans }}</label>
                <div class="uk-form-controls">
                    <v-editor id="gallery-description" :value.sync="gallery.description" :options="{markdown : gallery.data.markdown, height: 250}"></v-editor>
                </div>
            </div>

        </div>
        <div class="pk-width-sidebar">
          <div class="uk-form-row">
              <span class="uk-form-label">{{ 'Event Date' | trans }}</span>
              <div class="uk-form-controls">
                  <input-date :datetime.sync="gallery.date"></input-date>
              </div>
          </div>
            <div class="uk-panel">
                <div class="uk-form-row">
                    <label for="form-slug" class="uk-form-label">{{ 'Slug' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-slug" class="uk-width-1-1" type="text" v-model="gallery.slug">
                    </div>
                </div>
                <div class="uk-form-row">
                    <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-status" class="uk-width-1-1" v-model="gallery.status">
                            <option v-for="(id, status) in data.statuses" :value="id">{{status}}</option>
                        </select>
                    </div>
                </div>
                <div class="uk-form-row" v-if="data.canEditAll">
                    <label for="form-author" class="uk-form-label">{{ 'Author' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-author" class="uk-width-1-1" v-model="gallery.user_id">
                            <option v-for="author in data.authors" :value="author.id">{{author.username}}</option>
                        </select>
                    </div>
                </div>
                <div class="uk-form-row">
                    <span class="uk-form-label">{{ 'Restrict Access' | trans }}</span>
                    <div class="uk-form-controls uk-form-controls-text">
                        <p v-for="role in data.roles" class="uk-form-controls-condensed">
                            <label><input type="checkbox" :value="role.id" v-model="gallery.roles" number> {{ role.name }}</label>
                        </p>
                    </div>
                </div>
                <div class="uk-form-row">
                    <span class="uk-form-label">{{ 'Options' | trans }}</span>
                    <div class="uk-form-controls">
                        <label><input type="checkbox" v-model="gallery.data.markdown" value="1"> {{ 'Enable Markdown' | trans }}</label>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>

    module.exports = {

        props: ['gallery', 'data', 'form'],

        section: {
            label: 'Gallery'
        }

    };

</script>
