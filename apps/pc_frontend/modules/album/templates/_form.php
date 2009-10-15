<?php
$form->getWidget('title')->setAttribute('size', 40);
$form->getWidget('body')->setAttribute('rows', 10);
$form->getWidget('body')->setAttribute('cols', 50);

$options = array(
  'button' => __('Save'),
  'isMultipart' => true,
);

if ($form->isNew())
{
  $options['title'] = __('Post a album');
  $options['url'] = url_for('album_create');
}
else
{
  $options['title'] = __('Edit the album');
  $options['url'] = url_for('album_update', $album);
}

op_include_form('albumForm', $form, $options);
?>
