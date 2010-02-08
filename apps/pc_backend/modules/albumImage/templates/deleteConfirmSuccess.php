<?php slot('title', __('Delete the image')) ?>

<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<p><?php echo __('Do you really delete this image?') ?></p>

<table>
<?php include_partial('albumImage', array('albumImage' => $albumImage)) ?>
<tr><td colspan="2">
<form action="<?php echo url_for('albumImage/delete?id='.$albumImage->id) ?>" method="post">
<?php echo $form[$form->getCSRFFieldName()] ?>
<input class="input_submit" type="submit" value="<?php echo __('Delete') ?>" />
</form>
</td></tr>
</table>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('Back to previous page'), 'history.back()') ?>
