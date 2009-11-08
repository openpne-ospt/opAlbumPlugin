<?php slot('_album_detail_table') ?>
<table>
<tr>
<td colspan="2" class="photo"><?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '600x600')) ?></td>
</tr>
<tr>
<th><?php echo __('Description') ?></th><td><?php echo $albumImage->getDescription() ?></td>
</tr>
</table>
<?php end_slot(); ?>

<?php echo op_include_box('albumImageDetailBox', get_slot('_album_detail_table'), array('title' => __('写真の表示'))) ?>

<p><?php echo link_to(__('Back to the album'), 'album_show', $album) ?></p>
