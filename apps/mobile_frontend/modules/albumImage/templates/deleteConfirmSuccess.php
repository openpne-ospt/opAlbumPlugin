<?php
op_mobile_page_title(__('Delete Photo'));
?>

<?php echo __('Are you sure you want to delete this photo?') ?><br><br>

<form action="<?php echo url_for('album_image_delete', $albumImage) ?>" method="post">
<?php echo $form[$form->getCSRFFieldName()] ?>
<input type="submit" value=<?php echo __('Delete') ?>>
<?php echo link_to(__('Cancel%1%'), 'album_image_show', $albumImage) ?>
</form>