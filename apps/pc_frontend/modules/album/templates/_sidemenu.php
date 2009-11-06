<?php use_helper('opAlbum') ?>

<?php if (count($recentAlbumList)): ?>
<div class="parts pageNav">
<div class="partsHeading"><h3><?php echo __('Recently Posted Albums') ?></h3></div>
<ul>
<?php foreach ($recentAlbumList as $_album): ?>
<li><?php echo link_to(op_album_get_title_and_count($_album), 'album_show', $_album) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
