<center><?php op_include_pager_total($pager); ?></center>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php foreach ($pager->getResults() as $album): ?>
<?php echo link_to($album->Member->name, '@member_profile?id='.$album->Member->id) ?>
 <?php echo link_to($album->title, 'album_show', $album) ?><br>
<center><?php echo link_to(image_tag_sf_image($album->getCoverImage(), array('width' => '80', 'size' => '120x120')), 'album_show', $album) ?></center>
<?php echo $album->body ?>
 <?php echo op_format_activity_time(strtotime($album->getCreatedAt())) ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php endforeach; ?>
