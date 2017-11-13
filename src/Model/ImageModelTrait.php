<?php

namespace Shw\Gallery\Model;

use Gregwar\Image\Image as GImage;
use Pagekit\Application as App;
use Pagekit\Database\ORM\ModelTrait;

trait ImageModelTrait
{
    use ModelTrait;

    /**
     * @Saving
     */
    public static function saving($event, Image $image)
    {
        $image->modified = new \DateTime();
    }

    /**
     * @param null $width
     * @param null $height
     *
     * @return \Gregwar\Image\Image
     */
    public function getThumbnail($width = null, $height = null)
    {
        if (!$width) {
            $width = App::module('gallery')->config('images.thumbnail_width');
        }
        if (!$height) {
            $height = App::module('gallery')->config('images.thumbnail_height');
        }

        return GImage::open('storage/shw-gallery/'.$this->filename)
            //->setCacheDir(App::path().'/storage/shw-gallery/cache')
            ->zoomCrop($width, $height)
            ->guess((int) App::module('gallery')->config('images.image_quality'));
    }

    /**
     * @param null $width
     * @param null $height
     *
     * @return \Gregwar\Image\Image
     */
    public function getImage($width = null, $height = null)
    {
        if (!$width) {
            $width = App::module('gallery')->config('images.image_width');
        }
        if (!$height) {
            $height = App::module('gallery')->config('images.image_height');
        }

        return GImage::open('storage/shw-gallery/'.$this->filename)
            //->setCacheDir(App::path().'/storage/shw-gallery/cache')
            ->cropResize($width, $height)
            ->guess((int) App::module('gallery')->config('images.image_quality'));
    }
}
