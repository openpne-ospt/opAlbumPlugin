<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php op_mobile_page_title(__('My Album - %album%', array('%album%' => $album->title)))?>
<?php else: ?>
<?php op_mobile_page_title(__('%member%\'s Album - %album%', array('%member%' => $member->name, '%album%' => $album->title)))?>
<?php endif; ?>

<div style="padding: 2px;">
<div>
<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('width' => '180')) ?>
</center>
</div>

<div style="padding: 4px 0px 4px 0px;">
<small>
<?php echo link_to(__('Back to the album'), 'album_show', $album) ?>
<?php if ($albumImage->getPrevious($sf_user->getMemberId())): ?>
<?php echo ' - '.link_to(__('Previous'), 'album_image_show', $albumImage->getPrevious($sf_user->getMemberId())) ?>
<?php endif; ?>
<?php if ($albumImage->getNext($sf_user->getMemberId())): ?>
<?php echo ' - '.link_to(__('Next'), 'album_image_show', $albumImage->getNext($sf_user->getMemberId())) ?>
<?php endif; ?>
</small>
</div>

<?php if ('' !== $albumImage->description): ?>
<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<?php echo $albumImage->description ?>
</div>
<?php endif; ?>

<?php if ($member->id == $sf_user->getMemberId()): ?>
<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php echo link_to(__('Edit'), 'album_image_edit', $albumImage) ?><br>
<?php echo link_to(__('Delete'), 'album_image_delete_confirm', $albumImage) ?>
</small>
</div>
<?php endif; ?>
</div>

<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php echo link_to(__('My Albums'), 'album_list_mine'); ?><br>
<?php else: ?>
<?php echo link_to(__('%member%\'s Albums', array('%member%' => $member->name)), 'album_list_member', $member); ?><br>
<?php endif; ?>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php echo link_to(__('Most Recent'), 'album_list') ?><br>
<?php echo __('Upload Photos') ?>
</small>
</div>