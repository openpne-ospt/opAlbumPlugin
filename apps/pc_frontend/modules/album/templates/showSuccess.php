<?php decorate_with('layoutA') ?>
<?php slot('album_show') ?>

<?php $images = $diary->getDiaryImagesJoinFile() ?>
<?php if (count($images)): ?>
<ul class="photo">
<?php foreach ($images as $image): ?>
<li><a href="<?php echo sf_image_path($image->getFile()) ?>" target="_blank"><?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120')) ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php end_slot('album_show') ?>
<?php op_include_box('album_show', get_slot('album_show'));
