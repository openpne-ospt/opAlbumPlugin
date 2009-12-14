<?php use_helper('opAlbum'); ?>

<?php if ($pager->getNbResults()): ?>
<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Recently Posted Albums') ?></h3></div>
<?php echo op_include_pager_navigation($pager, 'album/list?page=%d'); ?>
<div class="block">
<?php foreach ($pager->getResults() as $album): ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="4" class="photo"><a href="<?php echo url_for('album_show', $album) ?>"><?php echo image_tag_sf_image($album->getCoverImage(), array('size' => '76x76')) ?></a></td>
<th><?php echo __('%Nickname%') ?></th><td><?php echo $album->getMember()->getName() ?></td>
</tr><tr>
<th><?php echo __('Title') ?></th><td><?php echo op_album_get_title_and_count($album) ?></td>
</tr><tr>
<th><?php echo __('Description') ?></th><td><?php echo op_truncate($album->getBody(), 36, '', 3) ?></td>
</tr><tr class="operation">
<th><?php echo __('Created at') ?></th><td><span class="text"><?php echo op_format_date($album->getCreatedAt(), 'XDateTimeJa') ?></span> <span class="moreInfo"><?php echo link_to(__('View this album'), 'album_show', $album) ?></span></td>
</tr></tbody></table></div></div>
<?php endforeach; ?>
</div>
<?php echo op_include_pager_navigation($pager, 'album/list?page=%d'); ?>
</div></div>
<?php else: ?>
<?php op_include_box('albumList', __('There are no albums.'), array('title' => $title)) ?>
<?php endif; ?>
