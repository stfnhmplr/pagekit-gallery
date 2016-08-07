<?php

namespace Shw\Gallery\Controller;

use Pagekit\Application as App;
use Shw\Gallery\Model\Gallery;
use Pagekit\User\Model\Role;

/**
 * @Access(admin=true)
 */
class GalleryController
{
    public function indexAction($filter = null, $page = null) {

      return [
          '$view' => [
              'title' => __('Galleries'),
              'name'  => 'gallery:views/admin/gallery-index.php'
          ],
          '$data' => [
              'statuses' => Gallery::getStatuses(),
              'authors'  => Gallery::getAuthors(),
              'canEditAll' => App::user()->hasAccess('gallery: manage all galleries'),
              'config'   => [
                  'filter' => (object) $filter,
                  'page'   => $page
              ]
          ]

      ];
    }

    /**
     * @Route("/edit", name="gallery/edit")
     * @Access("blog: manage own posts || blog: manage all posts")
     * @Request({"id": "int"})
     */
    public function editAction($id = 0)
    {
        try {

            if (!$gallery = Gallery::where(compact('id'))->related('user', 'images')->first()) {

                if ($id) {
                    App::abort(404, __('Invalid gallery id.'));
                }

                $gallery = Gallery::create([
                    'user_id' => App::user()->id,
                    'date' => new \DateTime(),
                    'status' => Gallery::STATUS_DRAFT
                ]);
            }

            $user = App::user();
            if(!$user->hasAccess('blog: manage all posts') && $gallery->user_id !== $user->id) {
                App::abort(403, __('Insufficient User Rights.'));
            }

            $roles = App::db()->createQueryBuilder()
                ->from('@system_role')
                ->where(['id' => Role::ROLE_ADMINISTRATOR])
                ->whereInSet('permissions', ['blog: manage all posts', 'blog: manage own posts'], false, 'OR')
                ->execute('id')
                ->fetchAll(\PDO::FETCH_COLUMN);

            $authors = App::db()->createQueryBuilder()
                ->from('@system_user')
                ->whereInSet('roles', $roles)
                ->execute('id, username')
                ->fetchAll();

            return [
                '$view' => [
                    'title' => $id ? __('Edit Gallery') : __('Add Gallery'),
                    'name'  => 'gallery/admin/gallery-edit.php'
                ],
                '$data' => [
                    'gallery'     => $gallery,
                    'statuses' => Gallery::getStatuses(),
                    'roles'    => array_values(Role::findAll()),
                    'canEditAll' => $user->hasAccess('blog: manage all posts'),
                    'authors'  => $authors
                ],
                'gallery' => $gallery
            ];

        } catch (\Exception $e) {

            App::message()->error($e->getMessage());

            return App::redirect('@gallery');
        }
    }


    /**
     * @Access("hello: manage settings")
     */
    public function settingsAction()
    {
        return [
            '$view' => [
                'title' => __('Gallery Settings'),
                'name'  => 'gallery:views/admin/settings.php'
            ],
            '$data' => [
                'config' => App::module('gallery')->config()
            ]
        ];
    }
}
