<?php decorate_with('layoutC') ?>

<?php include_partial('form', array('form' => $form, 'album' => $album)) ?>

<div id="formAlbumDelete" class="dparts box"><div class="parts">
<div class="partsHeading">
<h3><?php echo __('Delete this album') ?></h3>
</div>
<div class="block">
<form action="<?php echo url_for('album_delete_confirm', $album) ?>">
<div class="operation">
<ul class="moreInfo button">
<li>
<input type="submit" class="input_submit" value="<?php echo __('Delete') ?>" />
</li>
</ul>
</div>
</form>
</div>
</div></div>
