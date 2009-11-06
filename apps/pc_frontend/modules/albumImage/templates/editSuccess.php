<?php

$options['title'] = __('Edit your uploaded photo');
$options['url'] = url_for('album_image_update', $albumImage);
$options['button'] = __('Edit');
$options['isMultipart'] = true;

op_include_form('albumForm', $form, $options);
?>

<?php
op_include_form('albumForm', $deleteForm, array(
  'title' => __('Delete this photo'),
  'button' => __('Delete'),
  'url' => url_for('album_image_delete', $albumImage),
));
?>
