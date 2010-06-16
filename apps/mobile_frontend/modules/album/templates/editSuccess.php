<?php op_mobile_page_title(__('Edit Album'))?>

<div style="margin: 2px 0 4px 0px">
<center>
<?php echo image_tag_sf_image($album->getFile(), array('size' => '180x180')) ?>
</center>
</div>

<?php
unset($form['file_id']);
$form->getWidget('title')->setAttribute('size', 30);
$form->getWidget('body')->setAttribute('rows', 5);
$form->getWidget('body')->setAttribute('cols', 23);

$options = array(
  'button' => __('Edit'),
  'isMultipart' => true,
);

$options['url'] = url_for('album_update', $album);

op_include_form('albumForm', $form, $options);
?>