<?php// use_helper('opAlbum', 'Text') ?>

<?php decorate_with('layoutB') ?>
<?php slot('op_sidemenu', get_component('album', 'sidemenu', array('member' => $member))) ?>

<?php /* {{{ albumDetailBox */ ?>
<div class="dparts albumDetailBox"><div class="parts">
<div class="partsHeading"><h3><?php echo __('album of %1%', array('%1%' => $member->getName())) ?></h3>
</div>

<dl>
<dd>
<div class="title">
<p class="heading"><?php echo $album->getTitle(); ?></p>
</div>
<div class="body">
<?php $images = $album->getAlbumImagesJoinFile() ?>
<?php if (count($images)): ?>
<ul class="photo">
<?php foreach ($images as $image): ?>
<li><a href="<?php echo sf_image_path($image->getFile()) ?>" target="_blank"><?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120')) ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php echo op_url_cmd(nl2br($album->getBody())) ?>
</div>
</dd>
<dt><?php echo nl2br(op_format_date($album->getCreatedAt(), 'XDateTimeJaBr')) ?></dt>
<dd><?php echo link_to('写真を追加', 'album/add/?id='.$album->getId()) ?></dd>
</dl>
<div class="partsHeading"><h3><?php echo __('photo list') ?></h3>
</div>
<?php $images = $album->getAlbumImagesJoinFile() ?>
<?php if (count($images)): ?>
<ul class="photo">
<?php foreach ($images as $image): ?>
<li>
<a href="<?php echo sf_image_path($image->getFile()) ?>" target="_blank"><?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120')) ?></a>
<?php echo link_to('写真を編集', 'album/photo/edit') ?>
<?php echo link_to(op_album_get_title_and_count($album), 'album_show', $album) ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

</div></div>
<?php /* }}} */ ?>

