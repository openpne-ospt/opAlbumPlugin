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

<?php echo op_include_box('albumImageDetailBox', get_slot('_album_detail_table')) ?>

<?php

$options['title'] = __('Edit your uploaded photo');
$options['url'] = url_for('album_image_update', $albumImage);
$options['button'] = __('Edit');
$options['isMultipart'] = true;

op_include_form('albumForm', $form, $options);
?>

<?php
op_include_form('albumDeleteForm', new sfForm(), array(
  'title' => __('Delete this photo'),
  'button' => __('Delete'),
  'url' => url_for('album_image_delete', $albumImage),
));
?>

<?php op_include_line('backLink', link_to(__('Back to the album'), 'album_show', $album)) ?>
