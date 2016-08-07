<?php

namespace Shw\Gallery\Controller;

use Pagekit\Application as App;
use Shw\Gallery\Model\Gallery;
use Shw\Gallery\Model\Image;

/**
 * @Access("blog: manage own posts || blog: manage all posts")
 * @Route("/images", name="images")
 */
class ImageApiController
{
    /**
     * @Route("/", methods="GET")
     * @Request({"filter": "array", "page":"int"})
     */
    public function indexAction($filter = [], $page = 0)
    {
        $query  = Image::query();

        $limit = 10; //(int) $limit ?: App::module('blog')->config('posts.posts_per_page');
        $count = $query->count();
        $pages = ceil($count / $limit);
        $page  = max(0, min($pages - 1, $page));

        $images = array_values($query->offset($page * $limit)->limit($limit)->get());

        return compact('images', 'pages', 'count');
    }

    /**
     * @Route("/{id}", methods="GET", requirements={"id"="\d+"})
     */
    public function getAction($id)
    {
        return Image::where(['gallery_id' => $id])->get();
    }

    /**
     * @Route("/", methods="POST")
     * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
     * @Request({"gallery": "array", "id": "int"}, csrf=true)
     */
    public function saveAction($data, $id = 0)
    {
        if (!$id || !$image = Image::find($id)) {

            if ($id) {
                App::abort(404, __('Image not found.'));
            }

            $image = Image::create();
        }

        if (!$data['slug'] = App::filter($data['slug'] ?: $data['title'], 'slugify')) {
            App::abort(400, __('Invalid slug.'));
        }

        $image->save($data);

        return ['message' => 'success', 'image' => $image];
    }


    /**

     * @Request({"id": "int"}, csrf=true)
     */
    public function uploadAction($id)
    {
        if (!$id || !$gallery = Gallery::find($id)) {
            App::abort(404, __('Gallery not found.'));
        }

        $path = 'storage/shw-gallery/' . $gallery->slug;

        if(!file_exists($path)) {
            mkdir($path, 0755);
            mkdir($path."/thumbs", 0755);
        }

        $files = $_FILES['images'];

        $files = self::rearrange($files);

        foreach($files as $file) {
            $new_filename = strtolower(time()."_".$file['name']);
            move_uploaded_file($file['tmp_name'], $path."/".$new_filename);
        }

        return ['message' => 'success', 'images' => $files];
    }

    private function rearrange( $arr ){
        foreach( $arr as $key => $all ){
            foreach( $all as $i => $val ){
                $new[$i][$key] = $val;
            }
        }
        return $new;
    }

    /**
     * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
     * @Request({"id": "int"}, csrf=true)
     */
    public function deleteAction($id)
    {
        if ($gallery = Gallery::find($id)) {

            if(!App::user()->hasAccess('blog: manage all posts') && !App::user()->hasAccess('blog: manage own posts') && $gallery->user_id !== App::user()->id) {
                App::abort(400, __('Access denied.'));
            }

            $gallery->delete();
        }

        return ['message' => 'success'];
    }

    /**
     * @Route(methods="POST")
     * @Request({"ids": "int[]"}, csrf=true)
     */
    public function copyAction($ids = [])
    {
        foreach ($ids as $id) {
            if ($gallery = Gallery::find((int) $id)) {
                if(!App::user()->hasAccess('blog: manage all posts') && !App::user()->hasAccess('blog: manage own posts') && $gallery->user_id !== App::user()->id) {
                    continue;
                }

                $gallery = clone $gallery;
                $gallery->id = null;
                $gallery->status = Gallery::STATUS_DRAFT;
                $gallery->title = $gallery->title.' - '.__('Copy');
                $gallery->comment_count = 0;
                $gallery->date = new \DateTime();
                $gallery->save();
            }
        }

        return ['message' => 'success'];
    }

    /**
     * @Route("/bulk", methods="POST")
     * @Request({"galleries": "array"}, csrf=true)
     */
    public function bulkSaveAction($galleries = [])
    {
        foreach ($galleries as $data) {
            $this->saveAction($data, isset($data['id']) ? $data['id'] : 0);
        }

        return ['message' => 'success'];
    }

    /**
     * @Route("/bulk", methods="DELETE")
     * @Request({"ids": "array"}, csrf=true)
     */
    public function bulkDeleteAction($ids = [])
    {
        foreach (array_filter($ids) as $id) {
            $this->deleteAction($id);
        }

        return ['message' => 'success'];
    }
}
