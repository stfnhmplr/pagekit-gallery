module.exports = [

    {
        entry: {
            "gallery-edit": "./app/views/admin/gallery-edit",
            "gallery-index": "./app/views/admin/gallery-index",
            "dashboard-gallery": "./app/components/dashboard-gallery.vue",
            "settings": "./app/views/admin/settings",
            "link-gallery": "./app/components/link-gallery.vue",
            "gallery-meta": "./app/components/gallery-meta.vue",
            "gallery-images": "./app/components/gallery-images.vue"
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue" }
            ]
        }
    }

];
