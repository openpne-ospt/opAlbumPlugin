<?php use_helper('opAlbum'); ?>

<?php $title = __('Recently Posted Albums of %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize()->titleize())) ?>
<?php if ($pager->getNbResults()): ?>
<div class="dparts recentAlbumList"><div class="parts">
<div class="partsHeading"><h3><?php echo $title ?></h3></div>
<div class="pagerRelative"><p class="number"><?php echo op_include_pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>

<?php foreach ($pager->getResults() as $album): ?>
<table>
<tr><td rowspan="4" class="photo">
<?php echo link_to(image_tag_sf_image($album->getCoverImage(), array('size' => '120x120')), 'album_show', $album) ?><br />
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
<th><?php echo __('Public Flag') ?></th>
<td colspan="2"><?php echo $album->getPublic_flag() ?></td>
</tr>
<tr>
<th><?php echo __('Created at') ?></th>
<td colspan="2"><?php echo $album->getCreatedAt() ?></td>
</tr>
</table>
<?php endforeach; ?>
<div class="pagerRelative"><p class="number"><?php echo op_include_pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>
</div></div>
<?php else: ?>
<?php op_include_box('albumList', __('There are no albums'), array('title' => $title)) ?>
<?php endif; ?>
