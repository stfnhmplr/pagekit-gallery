<?php

use Pagekit\Application;

/*
 * This array is the module definition.
 * It's used by Pagekit to load your extension and register all things
 * that your extension provides (routes, menu items, php classes etc)
 */
return [

    /*
     * Define a unique name.
     */
    'name' => 'gallery',

    /*
     * Define the type of this module.
     * Has to be 'extension' here. Can be 'theme' for a theme.
     */
    'type' => 'extension',

    /*
     * Main entry point. Called when your extension is both installed and activated.
     * Either assign an closure or a string that points to a PHP class.
     * Example: 'main' => 'Pagekit\\Hello\\HelloExtension'
     */
    'main' => function (Application $app) {

        // bootstrap code

    },

    /*
     * Register all namespaces to be loaded.
     * Map from namespace to folder where the classes are located.
     * Remember to escape backslashes with a second backslash.
     */
    'autoload' => [

        'Shw\\Gallery\\' => 'src',
        'Intervention\\Image\\' => 'vendor/intervention/image/src/Intervention/Image'

    ],

    /*
     * Define nodes. A node is similar to a route with the difference
     * that it can be placed anywhere in the menu structure. The
     * resulting route is therefore determined on runtime.
     */
    'nodes' => [

        'Gallery' => [

            // The name of the node route
            'name' => '@gallery',

            // Label to display in the backend
            'label' => 'Gallery',

            // The controller for this node. Each controller action will be mounted
            'controller' => 'Shw\\Gallery\\Controller\\SiteController',

            // A unique node that cannot be deleted, resides in "Not Linked" by default
            'protected' => true

        ]

    ],


    /*
     * Define routes.
     */
    'routes' => [

        '/gallery' => [
            'name' => '@gallery/admin',
            'controller' => [
                'Shw\\Gallery\\Controller\\GalleryController'
            ]
        ],
        '/api/gallery' => [
            'name' => '@gallery/api',
            'controller' => [
                'Shw\\Gallery\\Controller\\GalleryApiController',
                'Shw\\Gallery\\Controller\\ImageApiController'
            ]
        ]

    ],

    /*
     * Define menu items for the backend.
     */
    'menu' => [

        // name, can be used for menu hierarchy
        'gallery' => [

            // Label to display
            'label' => 'Gallery',

            // Icon to display
            'icon' => 'gallery:icon.svg',

            // URL this menu item links to
            'url' => '@gallery/admin',

            // Optional: Expression to check if menu item is active on current url
            // 'active' => '@hello*'

            // Optional: Limit access to roles which have specific permission assigned
            // 'access' => 'hello: manage hellos'
        ],

        'gallery: panel' => [

            // Parent menu item, makes this appear on 2nd level
            'parent' => 'gallery',

            // See above
            'label' => 'Gallery',
            'icon' => 'gallery:icon.svg',
            'url' => '@gallery/admin'
            // 'access' => 'hello: manage hellos'
        ],

        'gallery: settings' => [
            'parent' => 'gallery',
            'label' => 'Settings',
            'url' => '@gallery/admin/settings',
            'access' => 'system: manage settings'
        ]

    ],

    /*
     * Define permissions.
     * Will be listed in backend and can then be assigned to certain roles.
     */
    'permissions' => [

        'gallery: manage own galleries' => [
            'title' => 'Manage own galleries',
            'description' => 'Create, edit, delete and publish galleries of their own'
        ],
        'gallery: manage all galleries' => [
            'title' => 'Manage all galleries',
            'description' => 'Create, edit, delete and publish galleries by all users'
        ],
        'gallery: manage settings' => [
            'title' => 'Manage settings'
        ],

    ],

    /*
     * Link to a settings screen from the extensions listing.
     */
    'settings' => '@gallery/admin/settings',

    /*
     * Default module configuration.
     * Can be overwritten by changed config during runtime.
     */
    'config' => [
        'gallery' => [
            'title' => 'Gallery'
        ],

        'images' => [
            'image_width'   => 1200,
            'image_height'  => null,
            'thumbnail_width'   => 150,
            'thumbnail_height'  => 100
        ]
    ],

    /*
     * Listen to events.
     */
    'events' => [

        'view.scripts' => function ($event, $scripts) {
            $scripts->register('gallery-link', 'gallery:app/bundle/link-gallery.js', '~panel-link');
            $scripts->register('gallery-dashboard', 'gallery:app/bundle/dashboard.js', '~dashboard');
            $scripts->register('gallery-meta', 'gallery:app/bundle/gallery-meta.js', '~gallery-edit');
            $scripts->register('gallery-images', 'gallery:app/bundle/gallery-images.js', '~gallery-edit');

        }

    ]

];
