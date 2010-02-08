<div class="parts searchFormLine">
<form action="<?php echo url_for('@monitoring_album_image_search') ?>" method="get">
<p class="form">
<?php echo __('Album ID') ?>:
<input type="text" class="input_text" name="album_id" size="10" value="<?php if ($sf_request->hasParameter('album_id')) echo $sf_request->getParameter('album_id') ?>" />
<input type="submit" value="<?php echo __('Search') ?>" />
</p>
</form>
</div>

<div class="parts searchFormLine">
<form action="<?php echo url_for('@monitoring_album_image_search') ?>" method="get">
<p class="form">
<?php echo __('Keyword') ?>:
<input type="text" class="input_text" name="keyword" size="30" value="<?php if ($sf_request->hasParameter('keyword')) echo $sf_request->getParameter('keyword') ?>" />
<input type="submit" value="<?php echo __('Search') ?>" />
</p>
</form>
</div>
