<?php

use Shw\Gallery\Content\SlideshowPlugin;
use Shw\Gallery\Events\RouteListener;

return [

    'name' => 'gallery',
    'type' => 'extension',

    'autoload' => [

        'Shw\\Gallery\\' => 'src',

    ],

    'nodes' => [

        'Gallery' => [
            'name'       => '@gallery',
            'label'      => 'Gallery',
            'controller' => 'Shw\\Gallery\\Controller\\SiteController',
            'protected'  => true,

        ],

    ],

    'routes' => [

        '/gallery' => [
            'name'       => '@gallery',
            'controller' => [
                'Shw\\Gallery\\Controller\\GalleryController',
            ],
        ],

        '/api/gallery' => [
            'name'       => '@gallery/api',
            'controller' => [
                'Shw\\Gallery\\Controller\\GalleryApiController',
                'Shw\\Gallery\\Controller\\ImageApiController',
            ],
        ],

    ],

    'menu' => [

        'gallery' => [
            'label'  => 'Gallery',
            'icon'   => 'gallery:icon.svg',
            'url'    => '@gallery/gallery',
            'active' => '@gallery/gallery*',
            'access' => 'gallery: manage own galleries',
        ],

        'gallery: galleries' => [
            'parent' => 'gallery',
            'label'  => 'Galleries',
            'icon'   => 'gallery:icon.svg',
            'url'    => '@gallery/gallery',
            'active' => '@gallery/gallery*',
            'access' => 'gallery: manage own galleries',
        ],

        'gallery: settings' => [
            'parent' => 'gallery',
            'label'  => 'Settings',
            'url'    => '@gallery/settings',
            'active' => '@gallery/settings*',
            'access' => 'system: access settings',
        ],

        'gallery: whatsNew' => [
            'parent' => 'gallery',
            'label'  => 'Changelog',
            'url'    => '@gallery/changelog',
            'active' => '@gallery/changelog',
            'access' => 'gallery: manage own galleries',
        ],

    ],

    'permissions' => [

        'gallery: manage own galleries' => [
            'title'       => 'Manage own galleries',
            'description' => 'Create, edit, delete and publish galleries of their own',
        ],
        'gallery: manage all galleries' => [
            'title'       => 'Manage all galleries',
            'description' => 'Create, edit, delete and publish galleries by all users',
        ],
        'gallery: manage settings' => [
            'title' => 'Manage settings',
        ],

    ],

    'settings' => '@gallery/settings',

    'config' => [
        'gallery' => [
            'title'              => 'Gallery',
            'back_button'        => false,
            'galleries_per_page' => 10,
        ],

        'images' => [
            'image_width'       => 1200,
            'image_height'      => 1200,
            'thumbnail_width'   => 150,
            'thumbnail_height'  => 100,
            'image_quality'     => 90,
        ],
    ],

    'events' => [

        'boot' => function ($event, $app) {
            $app->subscribe(
                new RouteListener(),
                new SlideshowPlugin()
            );
        },

        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('gallery-link', 'gallery:app/bundle/link-gallery.js', '~panel-link');
            $scripts->register('gallery-dashboard', 'gallery:app/bundle/gallery-dashboard.js', '~dashboard');
            $scripts->register('gallery-meta', 'gallery:app/bundle/gallery-meta.js', '~gallery-edit');
            $scripts->register('gallery-images', 'gallery:app/bundle/gallery-images.js', '~gallery-edit');
            $scripts->register('editor-gallery', 'gallery:app/bundle/editor-plugin.js', '~editor');
            if ($app->module('tinymce')) {
                $scripts->register('tinymce-gallery', 'gallery:app/bundle/tinymce-plugin.js', '~editor-gallery');
            }
        },

    ],

];
