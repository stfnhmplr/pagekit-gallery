<?php $view->script('minigallery', '', ['uikit', 'uikit-slideshow']) ?>

<div class="uk-slidenav-position uk-width-large-3-4 uk-container-center"
     style="width:<?= $attributes['width'] ?>; height:<?= $attributes['height'] ?>;"
     data-uk-slideshow="">
    <?php if ($attributes['title']) : ?>
        <h3 class="uk-margin-remove"><?= $attributes['title'] ?></h3>
    <?php endif ?>
    <ul class="uk-slideshow">
        <?php foreach ($images as $image): ?>
        <li>
            <img src="storage/shw-gallery/<?=$image->filename?>" alt="<?=$image->title?>">
        </li>
        <?php endforeach ?>
    </ul>
    <?php if(count($images) > 1): ?>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
    <?php endif ?>
    <?php if ($attributes['description']) : ?>
        <p class="uk-margin-remove"><?= $attributes['description'] ?></p>
    <?php endif ?>
</div>

