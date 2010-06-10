<?php op_mobile_page_title(__('Edit Photo'))?>

<div style="margin: 2px 0 4px 0px">
<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('width' => '180')) ?>
</center>
</div>

<?php
$options['url'] = url_for('album_image_update', $albumImage);
$options['button'] = __('Edit');
$options['isMultipart'] = true;

op_include_form('albumForm', $form, $options);
?>

<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php echo link_to(__('My Albums'), 'album_list_mine'); ?><br>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php echo link_to(__('Most Recent'), 'album_list') ?><br>
<?php echo __('Upload Photos') ?>
</small>
</div>