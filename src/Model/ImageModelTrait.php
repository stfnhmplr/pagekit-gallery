<?php

namespace Shw\Gallery\Model;

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
}
