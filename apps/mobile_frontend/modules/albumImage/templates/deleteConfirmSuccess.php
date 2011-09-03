<?php
op_mobile_page_title(__('Delete Photo'));
?>

<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '180x180')) ?>
</center>

<?php op_include_parts('yesNo', 'deleteAlbumImageConfirmForm', array(
  'body'      => __('Are you sure you want to delete this photo?'),
  'yes_form'  => new sfForm(),
  'yes_url'   => url_for('@album_image_delete?id='.$albumImage->id),
  'no_url'    => url_for('@album_image_show?id='.$albumImage->id),
  'no_method' => 'get',
  'align'     => 'center',
)) ?>
