<?php op_mobile_page_title(__('Edit Photo'))?>

<center>
<?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '180x180')) ?>
</center>

<?php
$options['url'] = url_for('album_image_update', $albumImage);
$options['button'] = __('Edit');
$options['align'] = 'center';

op_include_form('albumForm', $form, $options);
?>

<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php echo link_to(__('My Albums'), 'album_list_mine'); ?><br>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php echo link_to(__('Most Recent'), 'album_list') ?><br>
