<?php slot('title', __('Album Image List')) ?>

<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php include_partial('searchForm') ?>

<?php
if (isset($albumId))
{
  $pagerLink = 'albumImage/search?album_id='.$albumId.'&page=%d';
}
elseif (isset($keyword))
{
  $pagerLink = 'albumImage/search?keyword='.$keyword.'&page=%d';
}
else
{
  $pagerLink = 'albumImage/list?page=%d';
}
?>
<?php if ($pager->getNbResults()): ?>
<div id="albumMonitoringList">
<p><?php echo op_include_pager_navigation($pager, $pagerLink) ?></p>
<?php foreach ($pager->getResults() as $albumImage): ?>
<table>
<?php include_partial('albumImage', array('albumImage' => $albumImage)) ?>
<tr><td colspan="2"><form action="<?php echo url_for('albumImage/deleteConfirm?id='.$albumImage->id) ?>" method="get"><input type="submit" value="<?php echo __('Delete') ?>" /></form></td></tr>
</table>
<?php endforeach; ?>
<p><?php echo op_include_pager_navigation($pager, $pagerLink) ?></p>
</div>
<?php else: ?>
<p><?php echo !isset($keyword) ? __('There are no images.') : __('Your search "%1%" did not match any images.', array('%1%' => $keyword)) ?></p>
<?php endif; ?>
