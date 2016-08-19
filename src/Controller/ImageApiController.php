<?php

namespace Shw\Gallery\Controller;

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

        if(!App::user()->hasAccess('gallery: manage all galleries')) {
            $data['user_id'] = App::user()->id;
        }

        // user without universal access can only edit their own galleries
        if(!App::user()->hasAccess('gallery: manage all galleries') && !App::user()->hasAccess('gallery: manage own galleries') && $image->user_id !== App::user()->id) {
            App::abort(400, __('Access denied'));
        }

        unset($data['modified']);

        $image->save($data);

        return ['message' => 'success'];
    }
}
