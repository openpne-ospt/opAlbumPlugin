<?php use_helper('opAlbum'); ?>

<?php decorate_with('layoutB') ?>
<?php// slot('op_sidemenu', get_component('diary', 'sidemenu', array('member' => $member)) ?>

<?php if ($sf_user->getMemberId() === $member->getId()): ?>
<?php op_include_box('newalbumLink', link_to(__('Post a album'), 'album_new'), array('title' => __('Post a album'))) ?>
<?php endif; ?>

<?php $title = __('albums of %1%', array('%1%' => $member->getName())) ?>
<?php if ($pager->getNbResults()): ?>
<div class="dparts recentList"><div class="parts">
<div class="partsHeading"><h3><?php echo $title ?></h3></div>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>
<?php// $images = $album->getAlbumImagesJoinFile() ?>
<?php foreach ($pager->getResults() as $album): ?>
<dl>
<dd><?php echo $album->getTitle() ?></dd>
<dd><?php echo $album->getBody() ?></dd>
<dd><?php echo $album->getPublic_flag() ?></dd>
<dd><?php echo $album->getCreatedAt() ?></dd>
<dd><?php echo link_to(op_album_get_title_and_count($album), 'album_show', $album) ?></dd>
</dl>
<?php endforeach; ?>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'album/listMember?page=%d&id='.$member->getId()); ?></p></div>
</div></div>
<?php else: ?>
<?php op_include_box('albumList', __('There are no diaries'), array('title' => $title)) ?>
<?php endif; ?>
