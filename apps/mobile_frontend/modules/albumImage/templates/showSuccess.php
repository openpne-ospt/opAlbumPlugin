<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php op_mobile_page_title(__('My Album - %album%', array('%album%' => $album->title)))?>
<?php else: ?>
<?php op_mobile_page_title(__('%member%\'s Album - %album%', array('%member%' => $member->name, '%album%' => $album->title)))?>
<?php endif; ?>

<div>
<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '180x180')) ?>
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

<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #0d6ddf;">
<?php include_component('albumImageComment','list',array('albumImage' => $albumImage, 'commentPage' => $commentPage)) ?>
<?php include_partial('albumComment/create', array('form' => $form, 'url' => url_for('@album_image_comment_create?id='.$albumImage->id), 'boxName' => 'formAlbumImageComment')) ?>
</div>

<?php if ($member->id == $sf_user->getMemberId()): ?>
<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php echo link_to(__('Edit'), 'album_image_edit', $albumImage) ?><br>
<?php echo link_to(__('Delete'), 'album_image_delete_confirm', $albumImage) ?>
</small>
</div>
<?php endif; ?>

<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
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
</small>
</div>