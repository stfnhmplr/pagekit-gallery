<?php

namespace Shw\Gallery\Controller;

use Pagekit\Application as App;
use Pagekit\Markdown\Markdown;
use Pagekit\User\Model\Role;
use Shw\Gallery\Model\Gallery;

/**
 * @Access(admin=true)
 */
class GalleryController
{
    public function galleryAction($filter = null, $page = null)
    {
        return [
          '$view' => [
              'title' => __('Galleries'),
              'name'  => 'gallery:views/admin/gallery-index.php',
          ],
          '$data' => [
              'statuses'   => Gallery::getStatuses(),
              'authors'    => Gallery::getAuthors(),
              'canEditAll' => App::user()->hasAccess('gallery: manage all galleries'),
              'config'     => [
                  'filter' => (object) $filter,
                  'page'   => $page,
              ],
          ],

      ];
    }

    /**
     * @Route("/gallery/edit", name="gallery/edit")
     * @Access("gallery: manage own galleries || gallery: manage all galleries")
     * @Request({"id": "int"})
     */
    public function editAction($id = 0)
    {
        try {
            if (!$gallery = Gallery::where(compact('id'))->related('user', 'images')->first()) {
                if ($id) {
                    App::abort(404, __('Invalid gallery id'));
                }

                $gallery = Gallery::create([
                    'user_id' => App::user()->id,
                    'date'    => new \DateTime(),
                    'status'  => Gallery::STATUS_DRAFT,
                ]);
            }

            $user = App::user();
            if (!$user->hasAccess('gallery: manage all galleries') && $gallery->user_id !== $user->id) {
                App::abort(403, __('Insufficient User Rights.'));
            }

            $roles = App::db()->createQueryBuilder()
                ->from('@system_role')
                ->where(['id' => Role::ROLE_ADMINISTRATOR])
                ->whereInSet('permissions', ['gallery: manage all galleries', 'gallery: manage own galleries'], false, 'OR')
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
                    'name'  => 'gallery/admin/gallery-edit.php',
                ],
                '$data' => [
                    'gallery'     => $gallery,
                    'statuses'    => Gallery::getStatuses(),
                    'roles'       => array_values(Role::findAll()),
                    'canEditAll'  => $user->hasAccess('gallery: manage all galleries'),
                    'authors'     => $authors,
                ],
                'gallery' => $gallery,
            ];
        } catch (\Exception $e) {
            App::message()->error($e->getMessage());

            return App::redirect('@gallery');
        }
    }

    /**
     * @Access("system: access settings")
     */
    public function settingsAction()
    {
        return [
            '$view' => [
                'title' => __('Gallery Settings'),
                'name'  => 'gallery:views/admin/settings.php',
            ],
            '$data' => [
                'config' => App::module('gallery')->config(),
            ],
        ];
    }

    /**
     * @Access("gallery: manage own galleries || gallery: manage all galleries")
     */
    public function changelogAction()
    {
        $markdown = new Markdown();
        $content = $markdown->parse(
            file_get_contents(App::path().App::url()->getStatic('packages/shw/gallery/CHANGELOG.md'))
        );

        return [
            '$view' => [
                'title' => __('Changelog'),
                'name'  => 'gallery:views/admin/changelog.php',
            ],
            'content' => $content,
        ];
    }
}
