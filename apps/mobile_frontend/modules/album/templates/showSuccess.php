<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php op_mobile_page_title(__('My Album - %album%', array('%album%' => $album->title)))?>
<?php else: ?>
<?php op_mobile_page_title(__('%member%\'s Album - %album%', array('%member%' => $member->name, '%album%' => $album->title)))?>
<?php endif; ?>
<div style="padding-top: 2px;">
<?php if ($pager->getNbResults()): ?>
<?php $images = $pager->getResults() ?>
<div style="padding-bottom: 4px; margin-bottom: 4px; border-bottom: 1px solid #b3ceef;">
<center><?php op_include_pager_total($pager); ?></center>
</div>
<div>
<table>
<?php for ($i = 0; $i < count($images); $i = $i+2): ?>
<tr>
<?php for ($j = $i; $j < $i+2; $j++): ?>
<?php if (!empty($images[$j])): ?>
<td>
<?php echo link_to(image_tag_sf_image($images[$j]->getFile(), array('width' => '75')), 'album_image_show', $images[$j]) ?>
</td>
<?php endif; ?>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</table>
</div>
<div>
<?php op_include_pager_navigation($pager, '@album_show?page=%d&id='.$album->id , array('is_total' => false)); ?>
</div>

<?php else: ?>
<?php echo __('There are no images') ?>
<?php endif; ?>

<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<?php echo $album->body ?>
<?php if ($member->id == $sf_user->getMemberId()): ?>
<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php echo link_to(__('Edit'), 'album_edit', $album) ?><br>
<?php echo link_to(__('Delete'), 'album_delete_confirm', $album) ?>
</small>
</div>
<?php endif; ?>
</div>
</div>
<div style="padding: 1px 0px 4px 0px; border-top: 1px solid #b3ceef;">
<small>
<?php if ($member->id == $sf_user->getMemberId()): ?>
<?php echo link_to(__('My Albums'), 'album_list_mine'); ?><br>
<?php else: ?>
<?php echo link_to(__('%member%\'s Albums', array('%member%' => $member->name)), 'album_list_member', $member); ?><br>
<?php endif; ?>
<?php echo link_to(__('Friend Albums'), 'album_list_friend') ?><br>
<?php echo link_to(__('Most Recent'), 'album_list') ?><br>
<?php echo __('Upload Photos') ?>
</small>
</div>