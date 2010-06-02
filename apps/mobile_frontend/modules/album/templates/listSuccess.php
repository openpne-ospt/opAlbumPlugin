<?php op_mobile_page_title(__('Most Recent'))?>
<div style="padding: 1px 0px 4px 0px; border-bottom: 1px solid #b3ceef;">
<small>
<?php echo link_to(__('My Albums'), 'album_list_mine') ?><br>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php //echo __('Upload Photos') ?>
</small>
</div>
<div style="padding: 2px 0pt;">
<?php if ($pager->getNbResults()): ?>
<?php include_partial('list', array('pager' => $pager)) ?>
<div>
<?php op_include_pager_navigation($pager, '@album_list?page=%d', array('is_total' => false)); ?>
</div>
<?php else: ?>
<?php echo __('There are no albums') ?>
<?php endif; ?>
</div>