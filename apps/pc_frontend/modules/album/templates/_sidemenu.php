<<?php// use_helper('opalbum') ?>

<div class="parts memberImageBox">
<p class="photo"><?php echo link_to(image_tag_sf_image($member->getImageFileName(), array('size' => '120x120')), 'member/profile?id='.$member->getId()) ?></p>
<p class="text"><?php echo $member->getName() ?></p>
</div>

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
