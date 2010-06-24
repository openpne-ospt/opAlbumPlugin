<?php
op_mobile_page_title(__('Delete Comment'));
?>

<?php echo __('Do you really delete this comment?') ?><br><br>

<form action="<?php echo url_for('album_comment_delete', $albumComment) ?>" method="post">
<?php echo $form[$form->getCSRFFieldName()] ?>
<input class="input_submit" type="submit" value="<?php echo __('Delete') ?>" />
<?php echo link_to(__('Cancel%1%'), 'album_show', $album) ?>
</form>
