<?php use_helper('opAlbum') ?>

<?php if (count($albumList)): ?>
<div class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Recently Posted Albums') ?></h3></div>
<div class="block">

<ul class="articleList">
<?php foreach ($albumList as $album): ?>
<li><span class="date"><?php echo op_format_date($album->getCreatedAt(), 'XShortDateJa') ?></span><?php echo link_to($album->title, 'album_show', $album) ?></li>
<?php endforeach; ?>
</ul>

<div class="moreInfo">
<ul class="moreInfo">
<li><?php echo link_to(__('More'), 'album/listMember?id='.$memberId) ?></li>
</ul>
</div>

</div>
</div></div>
<?php endif; ?>
