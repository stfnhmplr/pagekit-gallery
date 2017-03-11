<?php

namespace Shw\Gallery\Model;

use Pagekit\Database\ORM\ModelTrait;

trait GalleryModelTrait
{
    use ModelTrait;

    /**
     * Get all users who have written an article.
     */
    public static function getAuthors()
    {
        return self::query()->select('user_id', 'name', 'username')->groupBy('user_id', 'name')->join('@system_user', 'user_id = @system_user.id')->execute()->fetchAll();
    }

    /**
     * @Saving
     */
    public static function saving($event, Gallery $gallery)
    {
        $gallery->modified = new \DateTime();

        $i = 2;
        $id = $gallery->id;

        while (self::where('slug = ?', [$gallery->slug])->where(function ($query) use ($id) {
            if ($id) {
                $query->where('id <> ?', [$id]);
            }
        })->first()) {
            $gallery->slug = preg_replace('/-\d+$/', '', $gallery->slug).'-'.$i++;
        }
    }

    /**
     * @Deleting
     */
    public static function deleting($event, Gallery $gallery)
    {
        self::getConnection()->delete('@images', ['gallery_id' => $gallery->id]);
    }
}
