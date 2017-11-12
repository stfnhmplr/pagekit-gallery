<?php

namespace Shw\Gallery\Controller;

use Gregwar\Image\Image as GImage;
use Pagekit\Application as App;
use Shw\Gallery\Model\Image;

/**
 * @Access("gallery: manage own galleries || gallery: manage all galleries")
 * @Route("/image", name="image")
 */
class ImageApiController
{
    /**
     * @Route("/", methods="POST")
     * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
     * @Request({"image": "array", "id": "int"}, csrf=true)
     */
    public function saveAction($data, $id = 0)
    {
        if (!$id || !$image = Image::find($id)) {
            App::abort(404, __('Image not found.'));
        }

        if (!App::user()->hasAccess('gallery: manage all galleries')) {
            $data['user_id'] = App::user()->id;
        }

        // user without universal access can only edit their own galleries
        if (!App::user()->hasAccess('gallery: manage all galleries') && !App::user()->hasAccess('gallery: manage own galleries') && $image->user_id !== App::user()->id) {
            App::abort(400, __('Access denied'));
        }

        unset($data['modified']);
        $image->save($data);

        if ($data['rotate']) {
            $path = 'storage/shw-gallery/'.$data['filename'];

            $img = GImage::open($path)
                ->rotate(-$data['rotate'])
                ->save($path, (int) App::module('gallery')->config('images.image_quality'));
        }

        return ['message' => 'success'];
    }

    /**
     * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
     * @Request({"id": "int"}, csrf=true)
     */
    public function deleteAction($id)
    {
        if ($image = Image::find($id)) {
            if (!App::user()->hasAccess('gallery: manage all galleries') && !App::user()->hasAccess('gallery: manage own galleries') && $image->user_id !== App::user()->id) {
                App::abort(400, __('Access denied.'));
            }

            unlink('storage/shw-gallery/'.$image->filename);
            unlink('storage/shw-gallery/thumbnails/tn_'.$image->filename);

            $image->delete();

            $images = Image::query()->where(['gallery_id' => $image->gallery_id])->get();

            return ['message' => 'success', 'images' => $images];
        }
    }

    /**
     * @Route("/count", methods="GET")
     */
    public function countAction()
    {
        return Image::query()->count();
    }
}
