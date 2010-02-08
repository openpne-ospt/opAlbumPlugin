<?php slot('title', __('Album List')) ?>

<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php include_partial('searchForm') ?>

<?php
if (!isset($keyword))
{
  $pagerLink = 'album/list?page=%d';
}
else
{
  $pagerLink = 'album/search?keyword='.$keyword.'&page=%d';
}
?>
<?php if ($pager->getNbResults()): ?>
<div id="albumMonitoringList">
<p><?php echo op_include_pager_navigation($pager, $pagerLink) ?></p>
<?php foreach ($pager->getResults() as $album): ?>
<table>
<?php include_partial('album', array('album' => $album)) ?>
<tr><td colspan="2"><form action="<?php echo url_for('album/deleteConfirm?id='.$album->id) ?>" method="get"><input type="submit" value="<?php echo __('Delete') ?>" /></form></td></tr>
</table>
<?php endforeach; ?>
<p><?php echo op_include_pager_navigation($pager, $pagerLink) ?></p>
</div>
<?php else: ?>
<p><?php echo !isset($keyword) ? __('There are no albums.') : __('Your search "%1%" did not match any albums.', array('%1%' => $keyword)) ?></p>
<?php endif; ?>
