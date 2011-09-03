<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php op_mobile_page_title(__('My Album'), $album->title)?>
<?php else: ?>
<?php op_mobile_page_title(__('%member%\'s Album', array('%member%' => $member->name)), $album->title)?>
<?php endif; ?>

<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '180x180')) ?>
</center>

<?php if ('' !== $albumImage->description): ?>
<?php echo $albumImage->description ?>
<?php endif; ?>

<?php if ($albumImage->getPrevious($sf_user->getMemberId())): ?>
<?php echo link_to(__('Previous'), 'album_image_show', $albumImage->getPrevious($sf_user->getMemberId())) ?>
<?php endif; ?>
<?php if ($albumImage->getNext($sf_user->getMemberId())): ?>
 <?php echo link_to(__('Next'), 'album_image_show', $albumImage->getNext($sf_user->getMemberId())) ?>
<?php endif; ?>

<br><?php echo link_to(__('Back to the album'), 'album_show', $album) ?>

<?php if ($member->id == $sf_user->getMemberId()): ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php echo link_to(__('Edit'), 'album_image_edit', $albumImage) ?><br>
<?php echo link_to(__('Delete'), 'album_image_delete_confirm', $albumImage) ?>
<?php endif; ?>

<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
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
