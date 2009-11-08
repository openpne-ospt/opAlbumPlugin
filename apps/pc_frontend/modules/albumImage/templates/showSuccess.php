<?php slot('_album_detail_table') ?>
<?php if ($albumImage->getPrevious($sf_user->getMemberId()) || $albumImage->getNext($sf_user->getMemberId())): ?>
<div class="block prevNextLinkLine">
<?php if ($albumImage->getPrevious($sf_user->getMemberId())): ?>
<p class="prev"><?php echo link_to(__('Previous photo'), 'album_image_show', $albumImage->getPrevious($sf_user->getMemberId())) ?></p>
<?php endif; ?>
<?php if ($albumImage->getNext($sf_user->getMemberId())): ?>
<p class="next"><?php echo link_to(__('Next photo'), 'album_image_show', $albumImage->getNext($sf_user->getMemberId())) ?></p>
<?php endif; ?>
</div>
<?php endif; ?>

<table>
<tr>
<td colspan="2" class="photo"><?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '600x600')) ?></td>
</tr>
<tr>
<th><?php echo __('Description') ?></th><td><?php echo $albumImage->getDescription() ?></td>
</tr>
</table>
<?php end_slot(); ?>

<?php echo op_include_box('albumImageDetailBox', get_slot('_album_detail_table'), array('title' => __('View this photo'))) ?>

<?php op_include_line('backLink', link_to(__('Back to the album'), 'album_show', $album)) ?>
