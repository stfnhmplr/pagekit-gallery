<?php

namespace Shw\Gallery\Model;

use Pagekit\System\Model\DataModelTrait;

/**
 * @Entity(tableClass="@images")
 */
class Image implements \JsonSerializable
{
    use DataModelTrait, ImageModelTrait;

    /** @Column(type="integer") @Id */
    public $id;

    /** @Column(type="integer") */
    public $gallery_id;

    /** @Column(type="integer") */
    public $user_id;

    /** @Column(type="string") */
    public $title;

    /** @Column(type="string") */
    public $filename;

    /** @Column(type="integer") */
    public $sort_order;

    /** @Column(type="datetime") */
    public $modified;

    /** @BelongsTo(targetEntity="Pagekit\User\Model\User", keyFrom="user_id") */
    public $user;

    /** @BelongsTo(targetEntity="Shw\Gallery\Model\Gallery", keyFrom="gallery_id") */
    public $gallery;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [
            'thumbnail' => $this->getThumbnail(),
            'image'     => $this->getImage(),
        ];

        return $this->toArray($data);
    }
}
