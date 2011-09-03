<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php op_mobile_page_title(__('My Album'), $album->title)?>
<?php else: ?>
<?php op_mobile_page_title(__('%member%\'s Album', array('%member%' => $member->name)), $album->title)?>
<?php endif; ?>
<?php if ($pager->getNbResults()): ?>
<center><?php op_include_pager_total($pager); ?></center>

<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php foreach($pager->getResults() as $image): ?>
<center>
<?php echo link_to(image_tag_sf_image($image->getFile(), array('size' => '120x120', 'width' => '80')), 'album_image_show', $image) ?>
</center>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endforeach; ?>

<?php op_include_pager_navigation($pager, '@album_show?page=%d&id='.$album->id , array('is_total' => false)); ?>

<?php else: ?>
<?php echo __('There are no images.') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endif; ?>

<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php echo link_to(__('Edit'), 'album_edit', $album) ?><br>
<?php echo link_to(__('Delete'), 'album_delete_confirm', $album) ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endif; ?>
<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php echo link_to(__('My Albums'), 'album_list_mine'); ?><br>
<?php else: ?>
<?php echo link_to(__('%member%\'s Albums', array('%member%' => $member->name)), 'album_list_member', $member); ?><br>
<?php endif; ?>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php echo link_to(__('Most Recent'), 'album_list') ?><br>
<?php if ($album->isAuthor($sf_user->getMemberId()) && 'example.com' !== sfConfig::get('op_mail_domain')): ?>
<?php echo op_mail_to('mail_album_image_upload', array('id' => $album->id), __('Upload')) ?><br>
<?php endif; ?>
