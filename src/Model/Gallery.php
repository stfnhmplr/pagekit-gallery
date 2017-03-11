<?php

namespace Shw\Gallery\Model;

use Pagekit\Application as App;
use Pagekit\System\Model\DataModelTrait;
use Pagekit\User\Model\AccessModelTrait;
use Pagekit\User\Model\User;

/**
 * @Entity(tableClass="@galleries")
 */
class Gallery implements \JsonSerializable
{
    use AccessModelTrait, DataModelTrait, GalleryModelTrait;

    /* Gallery draft status. */
    const STATUS_DRAFT = 0;

    /* Gallery pending review status. */
    const STATUS_PENDING_REVIEW = 1;

    /* Gallery published. */
    const STATUS_PUBLISHED = 2;

    /* Gallery unpublished. */
    const STATUS_UNPUBLISHED = 3;

    /* Minigallery only */
    const STATUS_MINIGALLERY = 4;

    /** @Column(type="integer") @Id */
    public $id;

    /** @Column(type="integer") */
    public $user_id;

    /** @Column(type="string") */
    public $title;

    /** @Column(type="datetime") */
    public $date;

    /** @Column(type="string") */
    public $slug;

    /** @Column(type="text") */
    public $description = '';

    /** @Column(type="string") */
    public $photograph;

    /** @Column(type="smallint") */
    public $status;

    /** @Column(type="datetime") */
    public $modified;

    /**
     * @BelongsTo(targetEntity="Pagekit\User\Model\User", keyFrom="user_id")
     */
    public $user;

    /**
     * @HasMany(targetEntity="Shw\Gallery\Model\Image", keyFrom="id", keyTo="gallery_id")
     */
    public $images;

    /** @var array */
    protected static $properties = [
        'author'     => 'getAuthor',
        'published'  => 'isPublished',
        'accessible' => 'isAccessible',
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PUBLISHED      => __('Published'),
            self::STATUS_UNPUBLISHED    => __('Unpublished'),
            self::STATUS_DRAFT          => __('Draft'),
            self::STATUS_PENDING_REVIEW => __('Pending Review'),
            self::STATUS_MINIGALLERY    => __('Minigallery only'),
        ];
    }

    public function getStatusText()
    {
        $statuses = self::getStatuses();

        return isset($statuses[$this->status]) ? $statuses[$this->status] : __('Unknown');
    }

    public function getAuthor()
    {
        return $this->user ? $this->user->username : null;
    }

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isAccessible(User $user = null)
    {
        return $this->isPublished() && $this->hasAccess($user ?: App::user());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $images = Image::query()->where(['gallery_id' => $this->id])->get();

        return $this->toArray(['images' => $images, 'url' => App::url('@gallery/id', ['id' => $this->id ?: 0], 'base')]);
    }
}
