<?php use_helper('opAlbum'); ?>

<?php decorate_with('layoutB') ?>
<?php slot('op_sidemenu', get_component('album', 'sidemenu', array('member' => $member))) ?>

<?php if ($sf_user->getMemberId() === $member->getId()): ?>
<?php op_include_box('newalbumLink', link_to(__('Post a album'), 'album_new'), array('title' => __('Post a album'))) ?>
<?php endif; ?>

<?php $title = __('albums of %1%', array('%1%' => $member->getName())) ?>
<?php if ($pager->getNbResults()): ?>
<div class="dparts recentList"><div class="parts">
<div class="partsHeading"><h3><?php echo $title ?></h3></div>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>

<?php foreach ($pager->getResults() as $album): ?>
<table>
<tr><td rowspan="4">
<?php echo link_to(image_tag_sf_image($album->getFile(), array('size' => '180x180')), 'album_show', $album) ?><br />
<?php echo link_to(__('Details'), 'album_show', $album) ?>
</td>
<th><?php echo __('Title') ?></th>
<td colspan="2"><?php echo $album->getTitle() ?></td>
</tr>
<tr>
<th><?php echo __('Description') ?></th>
<td colspan="2"><?php echo $album->getBody() ?></td>
</tr>
<tr>
<th><?php echo __('Publication') ?></th>
<td colspan="2"><?php echo $album->getPublic_flag() ?></td>
</tr>
<tr>
<th><?php echo __('Created') ?></th>
<td colspan="2"><?php echo $album->getCreatedAt() ?></td>
</tr>
</table>
<?php endforeach; ?>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>
</div></div>
<?php else: ?>
<?php op_include_box('albumList', __('There are no diaries'), array('title' => $title)) ?>
<?php endif; ?>
