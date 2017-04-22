<?php $view->script('minigallery', 'gallery:app/bundle/slideshow.js', ['uikit', 'uikit-slideshow']) ?>

<div class="uk-slidenav-position uk-width-large-3-4 uk-container-center"
     data-uk-slideshow="">
    <h3 class="uk-margin-remove"><?= $gallery->title ?></h3>
    <ul class="uk-slideshow">
        <?php foreach ($images as $image): ?>
        <li>
            <img src="<?= $view->url()->getStatic($image->getImage()) ?>" alt="<?=$image->title?>" />
        </li>
        <?php endforeach ?>
    </ul>
    <?php if (count($images) > 1): ?>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    <?php endif ?>
    <?php if ($gallery->description) : ?>
        <p class="uk-margin-remove"><?= $gallery->description ?></p>
    <?php endif ?>
    <?php if ($attributes['showLink']) : ?>
        <a href="<?= $view->url('@gallery/id', ['id' => $gallery->id]) ?>"><?= __('more') ?> <i class="uk-icon uk-icon-arrow-right"></i></a>
    <?php endif ?>
</div>
