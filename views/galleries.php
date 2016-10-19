<?php $view->style('gallery', 'extensions/gallery/assets/css/gallery.css', 'uikit')?>

<h1><?= $shwGallery->config('gallery.title') ?></h1>

<?php if(!$galleries): ?>
    <h3 class="uk-h1 uk-text-muted uk-text-center"><?php echo __('No Galleries found') ?></h3>
<?php else: ?>
    <div class="uk-grid uk-grid-width-1-4" data-uk-grid-margin>
    <?php foreach ($galleries as $gallery): ?>
        <div class="uk-text-center">
            <h2 class="uk-h3"><?= $gallery->title ?></h2>
            <a class="uk-thumbnail uk-overlay-toggle" href="<?= $view->url('@gallery/id', ['id' => $gallery->id]) ?>">
                <img src="<?= $view->url()->getStatic('storage/shw-gallery/thumbnails/tn_'.$gallery->image->filename) ?>" alt="" />
            </a>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php

$range     = 3;
$total     = intval($total);
$page      = intval($page);
$pageIndex = $page - 1;

?>

<?php if ($total > 1) : ?>
<ul class="uk-pagination">


    <?php for($i=1;$i<=$total;$i++): ?>
        <?php if ($i <= ($pageIndex+$range) && $i >= ($pageIndex-$range)): ?>

            <?php if ($i == $page): ?>
                <li class="uk-active"><span><?=$i?></span></li>
            <?php else: ?>
                <li>
                    <a href="<?= $view->url('@blog/page', ['page' => $i]) ?>"><?=$i?></a>
                <li>
            <?php endif; ?>

        <?php elseif($i==1): ?>

            <li>
                <a href="<?= $view->url('@blog/page', ['page' => 1]) ?>">1</a>
            </li>
            <li><span>...</span></li>

        <?php elseif($i==$total): ?>

            <li><span>...</span></li>
            <li>
                <a href="<?= $view->url('@blog/page', ['page' => $total]) ?>"><?=$total?></a>
            </li>

        <?php endif; ?>
    <?php endfor; ?>

</ul>
<?php endif ?>
