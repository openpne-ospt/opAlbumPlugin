<?php use_helper('opAlbum') ?>

<?php slot('_albumImageForm'); ?>
<tr>
<th><?php echo __('Album Name') ?></th><td><?php echo $album->title ?></td>
</tr>
<?php end_slot(); ?>

<?php
op_include_form('albumImageForm', $form, array(
  'title' => __('Add photos to this album'),
  'url' => url_for('album_image_insert', $album),
  'button' => __('Add'),
  'isMultipart' => true,
  'firstRow' => get_slot('_albumImageForm'),
));
?>
