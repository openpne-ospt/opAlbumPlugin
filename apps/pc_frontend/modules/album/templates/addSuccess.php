<?php

$options['title'] = __('Add photos to this album');
$options['url'] = url_for('album_insert', $album);
$options['button'] = __('add');
$options['isMultipart'] = true;

op_include_form('albumForm', $form, $options);
?>
