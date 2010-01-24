<?php use_helper('opAlbum') ?>

<?php decorate_with('layoutB') ?>
<?php slot('op_sidemenu', get_component('album', 'sidemenu', array('member' => $member))) ?>

<div class="dparts albumDetailBox"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Albums of %1%', array('%1%' => $member->name)) ?></h3>
</div>

<table>
<tr><td rowspan="<?php echo $album->isAuthor($sf_user->getMemberId()) ? 5 : 4 ?>" class="photo">
<?php echo image_tag_sf_image($album->getCoverImage(), array('size' => '120x120')) ?>
</td>
<th><?php echo __('Title') ?></th>
<td colspan="2"><?php echo $album->getTitle(); ?></td>
</tr>
<tr>
<th><?php echo __('Description') ?></th>
<td colspan="2"><?php echo $album->getBody() ?></td>
</tr>
<tr>
<th><?php echo __('Public flag') ?></th>
<td colspan="2"><?php echo $album->getPublicFlagLabel() ?></td>
</tr>
<tr>
<th><?php echo __('Created at') ?></th>
<td colspan="2"><?php echo op_format_date($album->getCreatedAt(), 'XDateTimeJaBr') ?></td>
</tr>
<?php if ($album->isAuthor($sf_user->getMemberId())): ?>
<tr>
<td colspan="3">
<?php echo link_to(__('Edit the album'), 'album_edit', $album) ?> | <?php echo link_to(__('Add photos to this album'), 'album_image_add', $album) ?>
</td>
</tr>
<?php endif; ?>
</table>
</div>
</div>

<div class="dparts albumImageList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('photo list') ?></h3>
</div>

<?php if ($pager->getNbResults()): ?>
<?php $images = $pager->getResults() ?>
<?php echo op_include_pager_navigation($pager, '@album_show?id='.$album->id.'&page=%d') ?>

<table>
<?php for ($i = 0; $i < count($images); $i = $i+2): ?>
<tr>
<?php for ($j = $i; $j < $i+2; $j++): ?>
<td>
<?php if (!empty($images[$j])): ?>
<p class="image">
<?php echo link_to(image_tag_sf_image($images[$j]->getFile(), array('size' => '180x180')), 'album_image_show', $images[$j]) ?><br />
<?php if ($album->isAuthor($sf_user->getMemberId())): ?>
<?php echo link_to(__('Edit the photo'), 'album_image_edit', $images[$j]) ?>
<?php endif; ?>
</p>
<p class="text"><?php echo $images[$j]->getDescription() ?></p>
<?php endif; ?>
</td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</table>

<?php echo op_include_pager_navigation($pager, '@album_show?id='.$album->id.'&page=%d') ?>
<?php else: ?>
<?php op_include_box('albumList', __('There are no images.')) ?>
<?php endif; ?>

</div>
</div>
