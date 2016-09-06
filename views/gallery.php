<?php $view->style('gallery', 'extensions/gallery/assets/css/gallery.css', 'uikit')?>

<?php if($shwGallery->config('gallery.back_button')): ?>
    <a class="uk-button" href="<?= $view->url('@gallery') ?>"><?= __('back') ?></a>
<?php endif; ?>

<h1><?= $gallery->title ?></h1>

<?php if($gallery->photograph): ?>
<?= __('Photograph').': '.$gallery->photograph ?>
<?php endif; ?>

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
