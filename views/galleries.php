<?php $view->style('gallery', 'gallery:assets/css/gallery.css', 'uikit')?>

<h1><?= $shwGallery->config('gallery.title') ?></h1>

<?php if (!$galleries): ?>
    <h3 class="uk-h1 uk-text-muted uk-text-center"><?php echo __('No Galleries found') ?></h3>
<?php else: ?>
    <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-3 uk-grid-width-small-1-2" data-uk-grid-margin>
    <?php foreach ($galleries as $gallery): ?>
        <div class="uk-text-center">
            <h2 class="uk-h3"><?= $gallery->title ?></h2>
            <a class="uk-thumbnail uk-overlay-toggle" href="<?= $view->url('@gallery/id', ['id' => $gallery->id]) ?>">
                <img src="<?= $view->url()->getStatic($gallery->image->getThumbnail()) ?>" alt="" />
            </a>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
