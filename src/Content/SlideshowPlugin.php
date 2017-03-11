<?php

namespace Shw\Gallery\Content;

use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use Shw\Gallery\Model\Gallery;
use Shw\Gallery\Model\Image;

class SlideshowPlugin implements EventSubscriberInterface
{
    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        $content = $event->getContent();
        $pattern = '/\[gallery(.*?)\/]/';
        $pattern2 = '/(\w+?)=\"(.+?)\"/';

        if (preg_match_all($pattern, $content, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[1] as $key => $match) {
                preg_match_all($pattern2, $match, $attributes, PREG_PATTERN_ORDER);

                $attributes = array_merge(array_fill_keys(['id', 'showLink', 'limit'], ''),
                array_combine($attributes[1], $attributes[2]));
                $attributes['showLink'] = ($attributes['showLink'] === 'true') ? true : false;

                $gallery = Gallery::find($attributes['id']);

                $query = Image::query()->where(['gallery_id' => intval($attributes['id'])]);

                if ($attributes['limit'] > 0) {
                    $query = $query->limit(intval($attributes['limit']));
                }

                if ($images = $query->get()) {
                    $content = str_replace($matches[0][$key], App::view('gallery:views/slideshow.php', compact('images', 'attributes', 'gallery')), $content);
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
            'content.plugins' => ['onContentPlugins', 10],
        ];
    }
}
