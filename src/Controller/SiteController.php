<?php

namespace Shw\Gallery\Controller;

use Pagekit\Application as App;
use Shw\Gallery\Model\Gallery;
use Shw\Gallery\Model\Image;

class SiteController
{
    /**
     * @var Module
     */
    protected $gallery;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->gallery = App::module('gallery');
    }

    /**
     * @Route("/")
     * @Route("/page/{page}", name="page", requirements={"page" = "\d+"})
     */
    public function indexAction($page = 1)
    {
        if (!App::node()->hasAccess(App::user())) {
            App::abort(403, __('Insufficient User Rights.'));
        }

        $query = Gallery::where(['status = ?'], [Gallery::STATUS_PUBLISHED])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->related('user');

        if (!$limit = $this->gallery->config('gallery.galleries_per_page')) {
            $limit = 10;
        }

        $count = $query->count('id');
        $total = ceil($count / $limit);
        $page = max(1, min($total, $page));

        $query->offset(($page - 1) * $limit)->limit($limit)->orderBy('date', 'DESC');

        foreach ($galleries = $query->get() as $gallery) {
            $gallery->image = Image::query()->where(['gallery_id' => $gallery->id])->first();
        }

        return [
            '$view' => [
                'title'     => __('Galleries'),
                'name'      => 'gallery/galleries.php',
                'link:feed' => [
                    'rel'   => 'alternate',
                    'href'  => App::url('@blog/feed'),
                    'title' => App::module('system/site')->config('title'),
                    //'type' => App::feed()->create($this->gallery->config('feed.type'))->getMIMEType()
                ],
            ],
            'shwGallery' => $this->gallery,
            'galleries'  => $galleries,
            'total'      => $total,
            'page'       => $page,
        ];
    }

    /**
     * @Route("/{id}", name="id")
     */
    public function galleryAction($id)
    {
        if (!$gallery = Gallery::where(['id = ?', 'status = ?'], [$id, Gallery::STATUS_PUBLISHED])->related('user')->first()) {
            App::abort(404, __('Gallery not found!'));
        }

        $user = App::user();

        if (!$gallery->hasAccess($user)) {
            App::abort(403, __('Insufficient User Rights.'));
        }

        $description = $gallery->get('meta.og:description');
        if (!$description) {
            $description = strip_tags($gallery->description);
            $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,").'...';
        }

        if (!$images = Image::query()->where(['gallery_id' => $gallery->id])->get()) {
            App::abort(404, __('No images found'));
        }

        $image = array_values($images)[0];

        return [
            '$view' => [
                'title'                  => __($gallery->title),
                'name'                   => 'gallery/gallery.php',
                'og:type'                => 'article',
                'article:published_time' => $gallery->date->format(\DateTime::ATOM),
                'article:modified_time'  => $gallery->modified->format(\DateTime::ATOM),
                'article:author'         => $gallery->user->name,
                'og:title'               => $gallery->get('meta.og:title') ?: $gallery->title,
                'og:description'         => $description,
                'og:image'               => App::url()->getStatic('/storage/shw-gallery/thumbnails/tn_'.$image->filename, [], 0),
            ],
            'shwGallery' => $this->gallery,
            'gallery'    => $gallery,
            'images'     => $images,
        ];
    }
}
