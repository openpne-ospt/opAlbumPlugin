<?php decorate_with('layoutC') ?>
<?php $options = array(
  'title' => 'Album',
  'url' => url_for('album/Create')
) ?>
<?php op_include_form('album_create', $form, $options); ?>
