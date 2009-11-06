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
</div>

<?php $images = $pager->getResults() ?>
<div class="dparts albumImageList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('photo list') ?></h3>
</div>

<?php echo op_include_pager_navigation($pager, '@album_show?id='.$album->id.'&page=%d') ?>

<table>
<?php for ($i = 0; $i < count($images); $i = $i+2): ?>
<tr>
<?php for ($j = $i; $j < $i+2; $j++): ?>
<td>
<?php if (!empty($images[$j])): ?>
<p class="photo">
<?php echo link_to(image_tag_sf_image($images[$j]->getFile(), array('size' => '180x180')), 'album_image_show', $images[$j]) ?><br />
<?php echo link_to('写真を編集', 'album/photo/edit') ?>
</p>
<p class="text"><?php echo $images[$j]->getDescription() ?></p>
<?php endif; ?>
</td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</table>

<?php echo op_include_pager_navigation($pager, '@album_show?id='.$album->id.'&page=%d') ?>

</div>
</div>
</div>

