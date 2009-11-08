<?php use_helper('opAlbum') ?>

<?php decorate_with('layoutB') ?>
<?php slot('op_sidemenu', get_component('album', 'sidemenu', array('member' => $member))) ?>

<div class="dparts albumDetailBox"><div class="parts">
<div class="partsHeading"><h3><?php echo $album->getTitle(); ?></h3>
</div>

<table>
<tr><td rowspan="4">
<?php echo image_tag_sf_image($album->getFile(), array('size' => '180x180')) ?>
</td>
<th><?php echo __('Description') ?></th>
<td colspan="2"><?php echo $album->getBody() ?></td>
</tr>
<tr>
<th><?php echo __('Publication') ?></th>
<td colspan="2"><?php echo $album->getPublic_flag() ?></td>
</tr>
<tr>
<th><?php echo __('Created') ?></th>
<td colspan="2"><?php echo op_format_date($album->getCreatedAt(), 'XDateTimeJaBr') ?></td>
</tr>
<tr>
<td colspan="3"><?php echo link_to('写真を追加', 'album_image_add', $album) ?></td>
</tr>
</table>


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
