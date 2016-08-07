<?php //$view->style('gallery', 'extensions/gallery/assets/css/gallery.css')?>

<h1><?= $gallery->title ?></h1>

<?= __('Photograph').': '.$gallery->photograph ?>

<p><?= $gallery->description ?></p>

<ul class="uk-grid uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-5">
    <?php foreach ($images as $image): ?>
    <li class="uk-text-center">
        <a href="/storage/shw-gallery/<?= $image->filename ?>" data-uk-lightbox="{group:'gallery'}" title="<?= $image->title ?>">
            <img class="uk-thumbnail" src="/storage/shw-gallery/thumbnails/tn_<?= $image->filename ?>">
        </a>
    </li>
    <?php endforeach ?>
</ul>
