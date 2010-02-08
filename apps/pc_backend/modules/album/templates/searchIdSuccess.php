<?php slot('title', __('Album List')) ?>

<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php include_partial('searchForm') ?>

<?php if ($album): ?>
<div id="albumMonitoringList">
<table>
<?php include_partial('album', array('album' => $album)) ?>
<tr><td colspan="2"><form action="<?php echo url_for('album/deleteConfirm?id='.$album->id) ?>" method="get"><input type="submit" value="<?php echo __('Delete') ?>" /></form></td></tr>
</table>
</div>
<?php else: ?>
<p><?php echo __('Your search "%1%" did not match any albums.', array('%1%' => $sf_request->getParameter('id'))) ?></p>
<?php endif; ?>
