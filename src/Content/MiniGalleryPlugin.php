<?php

namespace Shw\Gallery\Content;

use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use Shw\Gallery\Model\Image;
use Shw\Gallery\Model\Gallery;

class MiniGalleryPlugin implements EventSubscriberInterface
{
    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        $content = $event->getContent();
        $pattern = '/\[gallery(.*?)](.*?)\[\/gallery]/';
        $pattern2 = '/(\w+?)=\"(.+?)\"/';

        if(preg_match_all($pattern, $content, $matches, PREG_PATTERN_ORDER)) {

            foreach($matches[1] as $key => $match) {

                preg_match_all($pattern2, $match, $attributes, PREG_PATTERN_ORDER);

                $attributes = array_merge(array_fill_keys(['id', 'title', 'width', 'height', 'limit'], ''),
                    array_combine($attributes[1], $attributes[2]));
                $attributes['description'] = (key_exists(0, $matches[2])) ? $matches[2][0] : null;

                $gallery_id = intval($attributes['id']);
                $gallery = Gallery::find($gallery_id);

                $query = Image::query()->where(compact('gallery_id'));

                if(key_exists('limit', $attributes)) {
                    $query = $query->limit(intval($attributes['limit']));
                }

                if($images = $query->get()) {
                    $content = str_replace($matches[0][$key], App::view('gallery:views/slideshow.php', compact('images', 'attributes')), $content);
                } else {
                    $content = str_replace($matches[0][$key], 'Images not found', $content);
                }
            }

            $event->setContent($content);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => ['onContentPlugins', 10]
        ];
    }
}
