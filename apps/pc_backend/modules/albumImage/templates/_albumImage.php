<tr><th><?php echo __('ID') ?></th><td><?php echo $albumImage->id ?></td></tr>
<tr><th><?php echo __('Album ID') ?></th><td><?php echo $albumImage->Album->title ?> (<?php echo __('ID') ?>: <?php echo $albumImage->album_id ?>)</td></tr>
<tr><th><?php echo __('Author') ?></th><td><?php echo $albumImage->Member->name ?></td></tr>
<tr><th><?php echo __('Created at') ?></th><td><?php echo op_format_date($albumImage->created_at, 'XDateTimeJa') ?></td></tr>
<tr><th><?php echo __('Description') ?></th><td><?php echo nl2br($albumImage->description) ?></td></tr>
<tr><th><?php echo __('Photo') ?></th><td><?php echo image_tag_sf_image($albumImage->getFile(), array('size' => '120x120')) ?></td></tr>
