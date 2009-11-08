<?php use_helper('opAlbum') ?>

<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('My Albums') ?></h3></div>
<div class="block">

<?php if (count($albumList)): ?>
<ul class="articleList">
<?php foreach ($albumList as $album): ?>
<li><span class="date"><?php echo op_format_date($album->getCreatedAt(), 'XShortDateJa') ?></span><?php echo link_to($album->title, 'album_show', $album) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<div class="moreInfo">
<ul class="moreInfo">
<?php if (count($albumList)): ?>
<li><?php echo link_to(__('More'), 'album_list_mine') ?></li>
<?php endif; ?>
<li><?php echo link_to(__('Post a album'), 'album_new') ?></li>
</ul>
</div>

</div>
</div></div>
