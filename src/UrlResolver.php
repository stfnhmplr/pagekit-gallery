<?php

namespace Shw\Gallery;

use Pagekit\Application as App;
use Pagekit\Routing\ParamsResolverInterface;
use Shw\Gallery\Model\Gallery;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UrlResolver implements ParamsResolverInterface
{
    const CACHE_KEY = 'gallery.routing';

    /**
     * @var bool
     */
    protected $cacheDirty = false;

    /**
     * @var array
     */
    protected $cacheEntries;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->cacheEntries = App::cache()->fetch(self::CACHE_KEY) ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $parameters = [])
    {
        if (isset($parameters['id'])) {
            return $parameters;
        }

        if (!isset($parameters['slug'])) {
            App::abort(404, 'Gallery not found');
        }

        $slug = $parameters['slug'];

        $id = false;
        foreach ($this->cacheEntries as $entry) {
            if ($entry['slug'] === $slug) {
                $id = $entry['id'];
            }
        }

        if (!$id) {
            if (!$gallery = Gallery::where(compact('slug'))->first()) {
                App::abort(404, 'Gallery not found');
            }

            $this->addCache($gallery);
            $id = $gallery->id;
        }

        $parameters['id'] = $id;

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(array $parameters = [])
    {
        $id = $parameters['id'];

        if (!isset($this->cacheEntries[$id])) {
            if (!$gallery = Gallery::where(compact('id'))->first()) {
                throw new RouteNotFoundException('Gallery not found');
            }

            $this->addCache($gallery);
        }

        $meta = $this->cacheEntries[$id];

        preg_match_all('#{([a-z]+)}#i', self::getPermalink(), $matches);

        if ($matches) {
            foreach ($matches[1] as $attribute) {
                if (isset($meta[$attribute])) {
                    $parameters[$attribute] = $meta[$attribute];
                }
            }
        }

        unset($parameters['id']);

        return $parameters;
    }

    public function __destruct()
    {
        if ($this->cacheDirty) {
            App::cache()->save(self::CACHE_KEY, $this->cacheEntries);
        }
    }

    /**
     * Gets the galleries permalink setting.
     *
     * @return string
     */
    public static function getPermalink()
    {
        static $permalink;

        if (null === $permalink) {
            $gallery = App::module('gallery');
            $permalink = $gallery->config('permalink.type');

            if ($permalink == 'custom') {
                $permalink = $gallery->config('permalink.custom');
            }
        }

        return $permalink;
    }

    protected function addCache($gallery)
    {
        $this->cacheEntries[$gallery->id] = [
            'id'     => $gallery->id,
            'slug'   => $gallery->slug,
            'year'   => $gallery->date->format('Y'),
            'month'  => $gallery->date->format('m'),
            'day'    => $gallery->date->format('d'),
            'hour'   => $gallery->date->format('H'),
            'minute' => $gallery->date->format('i'),
            'second' => $gallery->date->format('s'),
        ];

        $this->cacheDirty = true;
    }
}
