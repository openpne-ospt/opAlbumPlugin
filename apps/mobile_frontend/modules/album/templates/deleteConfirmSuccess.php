<?php
op_mobile_page_title(__('Delete Album'), $album->title);
?>

<?php op_include_parts('yesNo', 'deleteAlbumConfirmForm', array(
  'body'      => __('Are you sure you want to delete this album?'),
  'yes_form'  => new sfForm(),
  'yes_url'   => url_for('@album_delete?id='.$album->id),
  'no_url'    => url_for('@album_show?id='.$album->id),
  'no_method' => 'get',
  'align'     => 'center',
)) ?>
