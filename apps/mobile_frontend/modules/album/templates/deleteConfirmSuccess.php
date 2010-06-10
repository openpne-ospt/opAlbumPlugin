<?php
op_mobile_page_title(__('Delete Album'));
?>

<?php echo __('Are you sure you want to delete this album?') ?><br><br>

<form action="<?php echo url_for('album_delete', $album) ?>" method="post">
<?php echo $form[$form->getCSRFFieldName()] ?>
<input type="submit" value=<?php echo __('Delete') ?>>
<?php echo link_to(__('Cancel%1%'), 'album_show', $album) ?>
</form>