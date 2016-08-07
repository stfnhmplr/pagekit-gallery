<?php

namespace Shw\Gallery\Model;

use Pagekit\Application as App;
use Pagekit\Database\ORM\ModelTrait;
use Pagekit\System\Model\DataModelTrait;

/**
 * @Entity(tableClass="@images")
 */
class Image
{
    use DataModelTrait, ModelTrait;

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

}
