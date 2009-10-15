<?php decorate_with('layoutC') ?>
<?php

$options['title'] = __('Edit the album');
$options['url'] = url_for('album_insert', $album);
$options['button'] = __('add');
$options['isMultipart'] = true;

op_include_form('albumForm', $form, $options);
?>
